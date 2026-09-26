<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\PermintaanTukarShiftRequest;
use App\Models\PermintaanTukarShift;

class PermintaanTukarShiftController extends Controller
{
    // public function index(Request $request)
    // {
    //     $startIndex = microtime(true);
    //     $previewKode = \App\Services\CodeGenerator::generate(\App\Models\PermintaanTukarShift::class, 'PMTS');

    //     $permintaanTukarShift = $this->filter($request)
    //         ->orderByDesc('kode')
    //         // ->latest()
    //         ->paginate(10)
    //         ->withQueryString();

    //     if ($request->ajax() || $request->wantsJson()) {
    //         Log::debug('PermintaanTukarShift index timings', ['ajax' => true, 'ms' => round((microtime(true) - $startIndex) * 1000, 2)]);
    //         return view('components.table.table', compact('permintaanTukarShift'));
    //     }

    //     Log::debug('PermintaanTukarShift index timings', ['ajax' => false, 'ms' => round((microtime(true) - $startIndex) * 1000, 2)]);
    //     return view('permintaan-tukar-shift.index', compact('permintaanTukarShift', 'previewKode'));
    // }

    private function filter(Request $request)
    {
        $search = $request->query('search');

        return PermintaanTukarShift::query()
            ->when($search, fn($query) => $query->where(function ($q) use ($search) {
                $q->where('kode', 'like', "%{$search}%");
            }));
    }

    public function create()
    {
        //
    }

    public function store(PermintaanTukarShiftRequest $request)
    {
        $start = microtime(true);
        $data = $request->validated();

        $before = microtime(true);
        $permintaanTukarShift = PermintaanTukarShift::create($data);
        $after = microtime(true);

        Log::debug('PermintaanTukarShift store timings', [
            'total_ms' => round((microtime(true) - $start) * 1000, 2),
            'create_ms' => round(($after - $before) * 1000, 2),
            'id' => $permintaanTukarShift->id ?? null,
        ]);

        return redirect()->route('permintaan.index')->with('success', 'Permintaan Tukar Shift berhasil ditambahkan.');
    }

    public function edit(PermintaanTukarShift $permintaanTukarShift)
    {
        // if (
        //     $permintaanTukarShift->approved_at ||
        //     $permintaanTukarShift->rejected_at
        // ) {
        //     return redirect()
        //         ->route('permintaan-tukar-shift.index')
        //         ->with(
        //             'error',
        //             'Permintaan Tukar Shift yang sudah diproses tidak dapat diubah.'
        //         );
        // }

        // return view(
        //     'permintaan-tukar-shift.form-edit',
        //     compact('permintaanTukarShift')
        // );
    }
    public function editData($id)
    {
        $permintaanTukarShift = PermintaanTukarShift::findOrFail($id);

        if (
            $permintaanTukarShift->approved_at ||
            $permintaanTukarShift->rejected_at
        ) {
            return response()->json([
                'success' => false,
                'message' => 'Permintaan Tukar Shift yang sudah diproses tidak dapat diubah.',
            ], 422);
        }

        //['kode', 'karyawan_pengaju', 'karyawan_pengganti', 'tanggal_tujuan', 'shift_pengaju', 'shift_pengganti'];
        return response()->json([
            'success' => true,
            'id' => $permintaanTukarShift->id,
            'kode' => $permintaanTukarShift->kode,
            'karyawan_pengaju' => $permintaanTukarShift->karyawan_pengaju,
            'karyawan_pengganti' => $permintaanTukarShift->karyawan_pengganti,
            'tanggal_tujuan' => $permintaanTukarShift->tanggal_tujuan,
            'shift_pengaju' => $permintaanTukarShift->shift_pengaju,
            'shift_pengganti' => $permintaanTukarShift->shift_pengganti,
        ]);
    }

    public function update(PermintaanTukarShiftRequest $request, PermintaanTukarShift $permintaanTukarShift)
    {
        if (
            $permintaanTukarShift->approved_at ||
            $permintaanTukarShift->rejected_at
        ) {
            return redirect()
                ->route('permintaan.index')
                ->with(
                    'error',
                    'Permintaan Tukar Shift yang sudah diproses tidak dapat diubah.'
                );
        }

        $data = $request->validated();

        Log::debug('Data update permintaan tukar shift', [
            'id' => $permintaanTukarShift->id,
            'data' => $data,
        ]);

        $permintaanTukarShift->update($data);

        return redirect()
            ->route('permintaan.index')
            ->with(
                'success',
                'Permintaan Tukar Shift berhasil diperbarui.'
            );
    }

    public function destroy($id)
    {
        $permintaanTukarShift = PermintaanTukarShift::findOrFail($id);

        if (
            $permintaanTukarShift->approved_at ||
            $permintaanTukarShift->rejected_at
        ) {
            return redirect()
                ->route('permintaan.index')
                ->with(
                    'error',
                    'Permintaan Tukar Shift yang sudah diproses tidak dapat dihapus.'
                );
        }

        $permintaanTukarShift->delete();

        return redirect()
            ->route('permintaan.index')
            ->with(
                'success',
                'Permintaan Tukar Shift berhasil dipindahkan ke Trash.'
            );
    }


    public function trash()
    {
        $permintaanTukarShifts = PermintaanTukarShift::onlyTrashed()->paginate(10);
        return view('permintaan-tukar-shift.trash', compact('permintaanTukarShifts'));
    }

    public function restore($id)
    {
        PermintaanTukarShift::onlyTrashed()->findOrFail($id)->restore();
        return redirect()->route('permintaan-tukar-shift.trash')->with('success', 'Permintaan Tukar Shift berhasil dipulihkan.');
    }

    public function forceDelete($id)
    {
        PermintaanTukarShift::onlyTrashed()
            ->findOrFail($id)
            ->forceDelete();

        return redirect()
            ->route('permintaan-tukar-shift.trash')
            ->with('success', 'Permintaan Tukar Shift berhasil dihapus permanen.');
    }

    public function approve(PermintaanTukarShift $permintaanTukarShift)
    {
        if (
            $permintaanTukarShift->approved_at ||
            $permintaanTukarShift->rejected_at
        ) {
            return redirect()
                ->route('permintaan.index')
                ->with(
                    'error',
                    'Permintaan Tukar Shift sudah pernah diproses.'
                );
        }

        $permintaanTukarShift->processed_by = auth()->id();
        $permintaanTukarShift->approved_at = now();
        $permintaanTukarShift->rejected_at = null;
        $permintaanTukarShift->save();

        return redirect()
            ->route('permintaan.index')
            ->with(
                'success',
                'Permintaan Tukar Shift berhasil disetujui.'
            );
    }

    public function reject(PermintaanTukarShift $permintaanTukarShift)
    {
        if (
            $permintaanTukarShift->approved_at ||
            $permintaanTukarShift->rejected_at
        ) {
            return redirect()
                ->route('permintaan.index')
                ->with(
                    'error',
                    'Permintaan Tukar Shift sudah pernah diproses.'
                );
        }

        $permintaanTukarShift->processed_by = auth()->id();
        $permintaanTukarShift->approved_at = null;
        $permintaanTukarShift->rejected_at = now();
        $permintaanTukarShift->save();

        return redirect()
            ->route('permintaan.index')
            ->with(
                'success',
                'Permintaan Tukar Shift berhasil ditolak.'
            );
    }
}
