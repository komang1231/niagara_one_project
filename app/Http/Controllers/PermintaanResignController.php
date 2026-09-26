<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\PermintaanResignRequest;
use App\Models\PermintaanResign;

class PermintaanResignController extends Controller
{
    // public function index(Request $request)
    // {
    //     $startIndex = microtime(true);
    //     $previewKode = \App\Services\CodeGenerator::generate(\App\Models\PermintaanResign::class, 'PMR');

    //     $permintaanResign = $this->filter($request)
    //         ->orderByDesc('kode')
    //         // ->latest()
    //         ->paginate(10)
    //         ->withQueryString();

    //     if ($request->ajax() || $request->wantsJson()) {
    //         Log::debug('PermintaanResign index timings', ['ajax' => true, 'ms' => round((microtime(true) - $startIndex) * 1000, 2)]);
    //         return view('components.table.table', compact('permintaanResign'));
    //     }

    //     Log::debug('PermintaanResign index timings', ['ajax' => false, 'ms' => round((microtime(true) - $startIndex) * 1000, 2)]);
    //     return view('permintaan-resign.index', compact('permintaanResign', 'previewKode'));
    // }

    private function filter(Request $request)
    {
        $search = $request->query('search');

        return PermintaanResign::query()
            ->when($search, fn($query) => $query->where(function ($q) use ($search) {
                $q->where('kode', 'like', "%{$search}%");
            }));
    }

    public function create()
    {
        //
    }

    public function store(PermintaanResignRequest $request)
    {
        $start = microtime(true);
        $data = $request->validated();

        $before = microtime(true);
        $permintaanResign = PermintaanResign::create($data);
        $after = microtime(true);

        Log::debug('PermintaanResign store timings', [
            'total_ms' => round((microtime(true) - $start) * 1000, 2),
            'create_ms' => round(($after - $before) * 1000, 2),
            'id' => $permintaanResign->id ?? null,
        ]);

        return redirect()->route('permintaan.index')->with('success', 'Permintaan Resign berhasil ditambahkan.');
    }

    public function edit(PermintaanResign $permintaanResign)
    {
        // if (
        //     $permintaanResign->approved_at ||
        //     $permintaanResign->rejected_at
        // ) {
        //     return redirect()
        //         ->route('permintaan-resign.index')
        //         ->with(
        //             'error',
        //             'Permintaan Resign yang sudah diproses tidak dapat diubah.'
        //         );
        // }

        // return view(
        //     'permintaan-resign.form-edit',
        //     compact('permintaanResign')
        // );
    }
    public function editData($id)
    {
        $permintaanResign = PermintaanResign::findOrFail($id);

        if (
            $permintaanResign->approved_at ||
            $permintaanResign->rejected_at
        ) {
            return response()->json([
                'success' => false,
                'message' => 'Permintaan Resign yang sudah diproses tidak dapat diubah.',
            ], 422);
        }

        //['kode', 'karyawan_id', 'tanggal_efektif', 'alasan'];
        return response()->json([
            'success' => true,
            'id' => $permintaanResign->id,
            'kode' => $permintaanResign->kode,
            'karyawan_id' => $permintaanResign->karyawan_id,
            'tanggal_efektif' => $permintaanResign->tanggal_efektif,
            'alasan' => $permintaanResign->alasan,
        ]);
    }

    public function update(PermintaanResignRequest $request, PermintaanResign $permintaanResign)
    {
        if (
            $permintaanResign->approved_at ||
            $permintaanResign->rejected_at
        ) {
            return redirect()
                ->route('permintaan.index')
                ->with(
                    'error',
                    'Permintaan Resign yang sudah diproses tidak dapat diubah.'
                );
        }

        $data = $request->validated();

        Log::debug('Data update permintaan resign', [
            'id' => $permintaanResign->id,
            'data' => $data,
        ]);

        $permintaanResign->update($data);

        return redirect()
            ->route('permintaan.index')
            ->with(
                'success',
                'Permintaan Resign berhasil diperbarui.'
            );
    }

    public function destroy($id)
    {
        $permintaanResign = PermintaanResign::findOrFail($id);

        if (
            $permintaanResign->approved_at ||
            $permintaanResign->rejected_at
        ) {
            return redirect()
                ->route('permintaan.index')
                ->with(
                    'error',
                    'Permintaan Resign yang sudah diproses tidak dapat dihapus.'
                );
        }

        $permintaanResign->delete();

        return redirect()
            ->route('permintaan.index')
            ->with(
                'success',
                'Permintaan Resign berhasil dipindahkan ke Trash.'
            );
    }


    public function trash()
    {
        $permintaanResigns = PermintaanResign::onlyTrashed()->paginate(10);
        return view('permintaan-resign.trash', compact('permintaanResigns'));
    }

    public function restore($id)
    {
        PermintaanResign::onlyTrashed()->findOrFail($id)->restore();
        return redirect()->route('permintaan-resign.trash')->with('success', 'Permintaan Resign berhasil dipulihkan.');
    }

    public function forceDelete($id)
    {
        PermintaanResign::onlyTrashed()
            ->findOrFail($id)
            ->forceDelete();

        return redirect()
            ->route('permintaan-resign.trash')
            ->with('success', 'Permintaan Resign berhasil dihapus permanen.');
    }

    public function approve(PermintaanResign $permintaanResign)
    {
        if (
            $permintaanResign->approved_at ||
            $permintaanResign->rejected_at
        ) {
            return redirect()
                ->route('permintaan.index')
                ->with(
                    'error',
                    'Permintaan Resign sudah pernah diproses.'
                );
        }

        $permintaanResign->processed_by = auth()->id();
        $permintaanResign->approved_at = now();
        $permintaanResign->rejected_at = null;
        $permintaanResign->save();

        return redirect()
            ->route('permintaan.index')
            ->with(
                'success',
                'Permintaan Resign berhasil disetujui.'
            );
    }

    public function reject(PermintaanResign $permintaanResign)
    {
        if (
            $permintaanResign->approved_at ||
            $permintaanResign->rejected_at
        ) {
            return redirect()
                ->route('permintaan.index')
                ->with(
                    'error',
                    'Permintaan Resign sudah pernah diproses.'
                );
        }

        $permintaanResign->processed_by = auth()->id();
        $permintaanResign->approved_at = null;
        $permintaanResign->rejected_at = now();
        $permintaanResign->save();

        return redirect()
            ->route('permintaan.index')
            ->with(
                'success',
                'Permintaan Resign berhasil ditolak.'
            );
    }
}
