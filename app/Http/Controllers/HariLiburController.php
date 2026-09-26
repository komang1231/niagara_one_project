<?php

namespace App\Http\Controllers;

use App\Http\Requests\HariLiburRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;
use App\Models\HariLibur;
use App\Services\CodeGenerator;

class HariLiburController extends Controller
{
    public function index(Request $request)
    {
        $startIndex = microtime(true);
        $previewKode = \App\Services\CodeGenerator::generate(\App\Models\HariLibur::class, 'HL');
        $statusOptions = [
            'aktif' => 'Aktif',
            'nonaktif' => 'Nonaktif',
        ];

        $hariLibur = $this->filter($request)
            ->orderByDesc('kode') 
            // ->latest()
            ->paginate(10)
            ->withQueryString();

        if ($request->ajax() || $request->wantsJson()) {
            Log::debug('HariLibur index timings', ['ajax' => true, 'ms' => round((microtime(true) - $startIndex) * 1000, 2)]);
            return view('components.table.table', compact('hariLibur'));
        }

        Log::debug('HariLibur index timings', ['ajax' => false, 'ms' => round((microtime(true) - $startIndex) * 1000, 2)]);
        return view('hari-libur.index', compact('statusOptions', 'hariLibur', 'previewKode'));
    }

    private function filter(Request $request)
    {
        $search = $request->query('search');
        $status = $request->has('status')
            ? (array) $request->query('status', [])
            : null;

        return HariLibur::query()
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

    public function store(HariLiburRequest $request)
    {
        $start = microtime(true);
        $data = $request->validated();
        $data['status'] = $data['status'] === '1' ? 'aktif' : 'nonaktif';

        $before = microtime(true);
        $hariLibur = HariLibur::create($data);
        $after = microtime(true);

        Log::debug('HariLibur store timings', [
            'total_ms' => round((microtime(true) - $start) * 1000, 2),
            'create_ms' => round(($after - $before) * 1000, 2),
            'id' => $hariLibur->id ?? null,
        ]);

        return redirect()->route('hari-libur.index')->with('success', 'Hari Libur berhasil ditambahkan.');
    }

    public function edit(HariLibur $hariLibur)
    {
        return view('hari-libur.form-edit', compact('hariLibur'));
    }

    public function editData($id)
    {
        $hariLibur = HariLibur::findOrFail($id);
        return response()->json([
            'id' => $hariLibur->id,
            'kode' => $hariLibur->kode,
            'nama' => $hariLibur->nama,
            'tanggal' => $hariLibur->tanggal,
            'status' => $hariLibur->status,
        ]);
    }

    public function update(HariLiburRequest $request, HariLibur $hariLibur)
    {
        $data = $request->validated();
        $data['status'] = $data['status'] === '1' ? 'aktif' : 'nonaktif';

        Log::debug('Data update hari libur', [
            'id' => $hariLibur->id,
            'data' => $data,
        ]);

        $hariLibur->update($data);
        return redirect()->route('hari-libur.index')->with('success', 'Hari Libur berhasil diperbarui.');
    }

    public function toggleStatus(HariLibur $hariLibur)
    {
        $hariLibur->update([
            'status' => $hariLibur->status === 'aktif' ? 'nonaktif' : 'aktif',
        ]);

        return response()->json([
            'success' => true,
            'status' => $hariLibur->status,
        ]);
    }

    public function destroy($id)
    {
        $hariLibur = HariLibur::findOrFail($id);

        // if ($hariLibur->divisi()->exists()) {
        //     return redirect()
        //         ->route('hari-libur.index')
        //         ->with(
        //             'error',
        //             'Hari Libur tidak dapat dihapus karena masih digunakan oleh data Divisi.'
        //         );
        // }

        $hariLibur->delete();

        return redirect()
            ->route('hari-libur.index')
            ->with(
                'success',
                'Hari Libur berhasil dipindahkan ke Trash.'
            );
    }


    public function trash()
    {
        $hariLiburs = HariLibur::onlyTrashed()->paginate(10);
        return view('hari-libur.trash', compact('hariLiburs'));
    }

    public function restore($id)
    {
        HariLibur::onlyTrashed()->findOrFail($id)->restore();
        return redirect()->route('hari-libur.trash')->with('success', 'Hari Libur berhasil dipulihkan.');
    }

    public function forceDelete($id)
    {
        HariLibur::onlyTrashed()
            ->findOrFail($id)
            ->forceDelete();

        return redirect()
            ->route('hari-libur.trash')
            ->with('success', 'Hari Libur berhasil dihapus permanen.');
    }
}
