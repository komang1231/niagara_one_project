<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\PermintaanLemburRequest;
use App\Models\PermintaanLembur;

class PermintaanLemburController extends Controller
{
    // public function index(Request $request)
    // {
    //     $startIndex = microtime(true);
    //     $previewKode = \App\Services\CodeGenerator::generate(\App\Models\PermintaanLembur::class, 'PML');

    //     $permintaanLembur = $this->filter($request)
    //         ->orderByDesc('kode')
    //         // ->latest()
    //         ->paginate(10)
    //         ->withQueryString();

    //     if ($request->ajax() || $request->wantsJson()) {
    //         Log::debug('PermintaanLembur index timings', ['ajax' => true, 'ms' => round((microtime(true) - $startIndex) * 1000, 2)]);
    //         return view('components.table.table', compact('permintaanLembur'));
    //     }

    //     Log::debug('PermintaanLembur index timings', ['ajax' => false, 'ms' => round((microtime(true) - $startIndex) * 1000, 2)]);
    //     return view('permintaan-lembur.index', compact('permintaanLembur', 'previewKode'));
    // }

    private function filter(Request $request)
    {
        $search = $request->query('search');

        return PermintaanLembur::query()
            ->when($search, fn($query) => $query->where(function ($q) use ($search) {
                $q->where('kode', 'like', "%{$search}%");
            }));
    }

    public function create()
    {
        //
    }

    public function store(PermintaanLemburRequest $request)
    {
        $start = microtime(true);
        $data = $request->validated();

        $before = microtime(true);
        $permintaanLembur = PermintaanLembur::create($data);
        $after = microtime(true);

        Log::debug('PermintaanLembur store timings', [
            'total_ms' => round((microtime(true) - $start) * 1000, 2),
            'create_ms' => round(($after - $before) * 1000, 2),
            'id' => $permintaanLembur->id ?? null,
        ]);

        return redirect()->route('permintaan.index')->with('success', 'Permintaan Lembur berhasil ditambahkan.');
    }

    public function edit(PermintaanLembur $permintaanLembur)
    {
        // if (
        //     $permintaanLembur->approved_at ||
        //     $permintaanLembur->rejected_at
        // ) {
        //     return redirect()
        //         ->route('permintaan-lembur.index')
        //         ->with(
        //             'error',
        //             'Permintaan Lembur yang sudah diproses tidak dapat diubah.'
        //         );
        // }

        // return view(
        //     'permintaan-lembur.form-edit',
        //     compact('permintaanLembur')
        // );
    }
    public function editData($id)
    {
        $permintaanLembur = PermintaanLembur::findOrFail($id);

        if (
            $permintaanLembur->approved_at ||
            $permintaanLembur->rejected_at
        ) {
            return response()->json([
                'success' => false,
                'message' => 'Permintaan Lembur yang sudah diproses tidak dapat diubah.',
            ], 422);
        }

        //['kode', 'karyawan_id', 'tanggal_tujuan', 'jam_mulai', 'jam_selesai', 'alasan', 'pengali'];
        return response()->json([
            'success' => true,
            'id' => $permintaanLembur->id,
            'kode' => $permintaanLembur->kode,
            'karyawan_id' => $permintaanLembur->karyawan_id,
            'tanggal_tujuan' => $permintaanLembur->tanggal_tujuan,
            'jam_mulai' => $permintaanLembur->jam_mulai,
            'jam_selesai' => $permintaanLembur->jam_selesai,
            'alasan' => $permintaanLembur->alasan,
            'pengali' => $permintaanLembur->pengali,
        ]);
    }

    public function update(PermintaanLemburRequest $request, PermintaanLembur $permintaanLembur)
    {
        if (
            $permintaanLembur->approved_at || $permintaanLembur->rejected_at
        ) {
            return redirect()
                ->route('permintaan.index')
                ->with(
                    'error',
                    'Permintaan Lembur yang sudah diproses tidak dapat diubah.'
                );
        }

        $data = $request->validated();

        Log::debug('Data update permintaan lembur', [
            'id' => $permintaanLembur->id,
            'data' => $data,
        ]);

        $permintaanLembur->update($data);

        return redirect()
            ->route('permintaan.index')
            ->with(
                'success',
                'Permintaan Lembur berhasil diperbarui.'
            );
    }

    public function destroy($id)
    {
        $permintaanLembur = PermintaanLembur::findOrFail($id);

        if (
            $permintaanLembur->approved_at ||
            $permintaanLembur->rejected_at
        ) {
            return redirect()
                ->route('permintaan.index')
                ->with(
                    'error',
                    'Permintaan Lembur yang sudah diproses tidak dapat dihapus.'
                );
        }

        $permintaanLembur->delete();

        return redirect()
            ->route('permintaan.index')
            ->with(
                'success',
                'Permintaan Lembur berhasil dipindahkan ke Trash.'
            );
    }


    public function trash()
    {
        $permintaanLemburs = PermintaanLembur::onlyTrashed()->paginate(10);
        return view('permintaan-lembur.trash', compact('permintaanLemburs'));
    }

    public function restore($id)
    {
        PermintaanLembur::onlyTrashed()->findOrFail($id)->restore();
        return redirect()->route('permintaan-lembur.trash')->with('success', 'Permintaan Lembur berhasil dipulihkan.');
    }

    public function forceDelete($id)
    {
        PermintaanLembur::onlyTrashed()
            ->findOrFail($id)
            ->forceDelete();

        return redirect()
            ->route('permintaan-lembur.trash')
            ->with('success', 'Permintaan Lembur berhasil dihapus permanen.');
    }

    public function approve(PermintaanLembur $permintaanLembur)
    {
        if (
            $permintaanLembur->approved_at ||
            $permintaanLembur->rejected_at
        ) {
            return redirect()
                ->route('permintaan.index')
                ->with(
                    'error',
                    'Permintaan Lembur sudah pernah diproses.'
                );
        }

        $permintaanLembur->processed_by = auth()->id();
        $permintaanLembur->approved_at = now();
        $permintaanLembur->rejected_at = null;
        $permintaanLembur->save();

        return redirect()
            ->route('permintaan.index')
            ->with(
                'success',
                'Permintaan Lembur berhasil disetujui.'
            );
    }

    public function reject(PermintaanLembur $permintaanLembur)
    {
        if (
            $permintaanLembur->approved_at ||
            $permintaanLembur->rejected_at
        ) {
            return redirect()
                ->route('permintaan.index')
                ->with(
                    'error',
                    'Permintaan Lembur sudah pernah diproses.'
                );
        }

        $permintaanLembur->processed_by = auth()->id();
        $permintaanLembur->approved_at = null;
        $permintaanLembur->rejected_at = now();
        $permintaanLembur->save();

        return redirect()
            ->route('permintaan.index')
            ->with(
                'success',
                'Permintaan Lembur berhasil ditolak.'
            );
    }
}
