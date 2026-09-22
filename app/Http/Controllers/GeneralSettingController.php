<?php

namespace App\Http\Controllers;

use App\Http\Requests\GeneralSettingRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;
use App\Models\GeneralSetting;
use App\Services\CodeGenerator;

class GeneralSettingController extends Controller
{
    public function index(Request $request)
    {
        $startIndex = microtime(true);
        $previewKode = \App\Services\CodeGenerator::generate(\App\Models\GeneralSetting::class, 'GENERAL_SETTING');
        $statusOptions = [
            'aktif' => 'Aktif',
            'nonaktif' => 'Nonaktif',
        ];

        $generalSetting = $this->filter($request)
            ->orderByDesc('kode') 
            // ->latest()
            ->paginate(10)
            ->withQueryString();

        if ($request->ajax() || $request->wantsJson()) {
            Log::debug('GeneralSetting index timings', ['ajax' => true, 'ms' => round((microtime(true) - $startIndex) * 1000, 2)]);
            return view('components.table.table', compact('generalSetting'));
        }

        Log::debug('GeneralSetting index timings', ['ajax' => false, 'ms' => round((microtime(true) - $startIndex) * 1000, 2)]);
        return view('general-setting.index', compact('statusOptions', 'generalSetting', 'previewKode'));
    }

    private function filter(Request $request)
    {
        $search = $request->query('search');
        $status = $request->has('status')
            ? (array) $request->query('status', [])
            : null;

        return GeneralSetting::query()
            ->when($search, fn($query) => $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('kode', 'like', "%{$search}%");
            }))
            ->when(!is_null($status), fn($query) => $query->whereIn('status', $status));
    }

    public function create()
    {
        //
    }

    public function store(GeneralSettingRequest $request)
    {
        $start = microtime(true);
        $data = $request->validated();
        $data['status'] = $data['status'] === '1' ? 'aktif' : 'nonaktif';

        $before = microtime(true);
        $generalSetting = GeneralSetting::create($data);
        $after = microtime(true);

        Log::debug('GeneralSetting store timings', [
            'total_ms' => round((microtime(true) - $start) * 1000, 2),
            'create_ms' => round(($after - $before) * 1000, 2),
            'id' => $generalSetting->id ?? null,
        ]);

        return redirect()->route('general-setting.index')->with('success', 'General Setting berhasil ditambahkan.');
    }

    public function edit(GeneralSetting $generalSetting)
    {
        return view('general-setting.form-edit', compact('generalSetting'));
    }
    public function editData($id)
    {
        $generalSetting = GeneralSetting::findOrFail($id);
        return response()->json([
            'id' => $generalSetting->id,
            'key' => $generalSetting->key,
            'name' => $generalSetting->name,
            'value' => $generalSetting->value,
            'status' => $generalSetting->status,
        ]);
    }

    public function update(GeneralSettingRequest $request, GeneralSetting $generalSetting)
    {
        $data = $request->validated();
        $data['status'] = $data['status'] === '1' ? 'aktif' : 'nonaktif';

        Log::debug('Data update general setting', [
            'id' => $generalSetting->id,
            'data' => $data,
        ]);

        $generalSetting->update($data);
        return redirect()->route('general-setting.index')->with('success', 'General Setting berhasil diperbarui.');
    }

    public function toggleStatus(GeneralSetting $generalSetting)
    {
        $generalSetting->update([
            'status' => $generalSetting->status === 'aktif' ? 'nonaktif' : 'aktif',
        ]);

        return response()->json([
            'success' => true,
            'status' => $generalSetting->status,
        ]);
    }

    public function destroy($id)
    {
        $generalSetting = GeneralSetting::findOrFail($id);

        if ($generalSetting->divisi()->exists()) {
            return redirect()
                ->route('general-setting.index')
                ->with(
                    'error',
                    'General Setting tidak dapat dihapus karena masih digunakan oleh data Divisi.'
                );
        }

        $generalSetting->delete();

        return redirect()
            ->route('general-setting.index')
            ->with(
                'success',
                'General Setting berhasil dipindahkan ke Trash.'
            );
    }


    public function trash()
    {
        $generalSettings = GeneralSetting::onlyTrashed()->paginate(10);
        return view('general-setting.trash', compact('generalSettings'));
    }

    public function restore($id)
    {
        GeneralSetting::onlyTrashed()->findOrFail($id)->restore();
        return redirect()->route('general-setting.trash')->with('success', 'General Setting berhasil dipulihkan.');
    }

    public function forceDelete($id)
    {
        GeneralSetting::onlyTrashed()
            ->findOrFail($id)
            ->forceDelete();

        return redirect()
            ->route('general-setting.trash')
            ->with('success', 'General Setting berhasil dihapus permanen.');
    }
}
