<?php

namespace App\Http\Controllers;

use App\Http\Requests\CutiRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;
use App\Models\Cuti;
use App\Services\CodeGenerator;

class CutiController extends Controller
{
    public function index(Request $request)
    {
        $startIndex = microtime(true);
        $previewKode = \App\Services\CodeGenerator::generate(\App\Models\Cuti::class, 'CUTI');
        $statusOptions = [
            'aktif' => 'Aktif',
            'nonaktif' => 'Nonaktif',
        ];

        $cuti = $this->filter($request)
            ->orderByDesc('kode') 
            // ->latest()
            ->paginate(10)
            ->withQueryString();

        if ($request->ajax() || $request->wantsJson()) {
            Log::debug('Cuti index timings', ['ajax' => true, 'ms' => round((microtime(true) - $startIndex) * 1000, 2)]);
            return view('components.table.table', compact('cuti'));
        }

        Log::debug('Cuti index timings', ['ajax' => false, 'ms' => round((microtime(true) - $startIndex) * 1000, 2)]);
        return view('cuti.index', compact('statusOptions', 'cuti', 'previewKode'));
    }

    private function filter(Request $request)
    {
        $search = $request->query('search');
        $status = $request->has('status')
            ? (array) $request->query('status', [])
            : null;

        return Cuti::query()
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

    public function store(CutiRequest $request)
    {
        $start = microtime(true);
        $data = $request->validated();
        $data['status'] = $data['status'] === '1' ? 'aktif' : 'nonaktif';

        $before = microtime(true);
        $cuti = Cuti::create($data);
        $after = microtime(true);

        Log::debug('Cuti store timings', [
            'total_ms' => round((microtime(true) - $start) * 1000, 2),
            'create_ms' => round(($after - $before) * 1000, 2),
            'id' => $cuti->id ?? null,
        ]);

        return redirect()->route('cuti.index')->with('success', 'Cuti berhasil ditambahkan.');
    }

    public function edit(Cuti $cuti)
    {
        return view('cuti.form-edit', compact('cuti'));
    }   
    public function editData($id)
    {
        $cuti = Cuti::findOrFail($id);
        return response()->json([
            'id' => $cuti->id,
            'kode' => $cuti->kode,
            'nama' => $cuti->nama,
            'kuota_hari_default' => $cuti->kuota_hari_default,
            'status' => $cuti->status,
        ]);
    }

    public function update(CutiRequest $request, Cuti $cuti)
    {
        $data = $request->validated();
        $data['status'] = $data['status'] === '1' ? 'aktif' : 'nonaktif';

        Log::debug('Data update cuti', [
            'id' => $cuti->id,
            'data' => $data,
        ]);

        $cuti->update($data);
        return redirect()->route('cuti.index')->with('success', 'Cuti berhasil diperbarui.');
    }

    public function toggleStatus(Cuti $cuti)
    {
        $cuti->update([
            'status' => $cuti->status === 'aktif' ? 'nonaktif' : 'aktif',
        ]);

        return response()->json([
            'success' => true,
            'status' => $cuti->status,
        ]);
    }

    public function destroy($id)
    {
        $cuti = Cuti::findOrFail($id);

        // if ($cuti->divisi()->exists()) {
        //     return redirect()
        //         ->route('cuti.index')
        //         ->with(
        //             'error',
        //             'Cuti tidak dapat dihapus karena masih digunakan oleh data Divisi.'
        //         );
        // }

        $cuti->delete();

        return redirect()
            ->route('cuti.index')
            ->with(
                'success',
                'Cuti berhasil dipindahkan ke Trash.'
            );
    }


    public function trash()
    {
        $cutis = Cuti::onlyTrashed()->paginate(10);
        return view('cuti.trash', compact('cutis'));
    }

    public function restore($id)
    {
        Cuti::onlyTrashed()->findOrFail($id)->restore();
        return redirect()->route('cuti.trash')->with('success', 'Cuti berhasil dipulihkan.');
    }

    public function forceDelete($id)
    {
        Cuti::onlyTrashed()
            ->findOrFail($id)
            ->forceDelete();

        return redirect()
            ->route('cuti.trash')
            ->with('success', 'Cuti berhasil dihapus permanen.');
    }
}
