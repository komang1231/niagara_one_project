<?php

namespace App\Http\Controllers;

use App\Http\Requests\PermintaanCutiRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\PermintaanCuti;
use App\Models\PermintaanCutiDetail;
use App\Services\CodeGenerator;

class PermintaanCutiController extends Controller
{
    // public function index(Request $request)
    // {
    //     $startIndex = microtime(true);

    //     $previewKode = CodeGenerator::generate(
    //         PermintaanCuti::class,
    //         'PMC'
    //     );

    //     $permintaanCuti = $this->filter($request)
    //         ->orderByDesc('kode')
    //         ->paginate(10)
    //         ->withQueryString();

    //     if ($request->ajax() || $request->wantsJson()) {
    //         Log::debug('PermintaanCuti index timings', [
    //             'ajax' => true,
    //             'ms' => round(
    //                 (microtime(true) - $startIndex) * 1000,
    //                 2
    //             ),
    //         ]);

    //         return view(
    //             'components.table.table',
    //             compact('permintaanCuti')
    //         );
    //     }

    //     Log::debug('PermintaanCuti index timings', [
    //         'ajax' => false,
    //         'ms' => round(
    //             (microtime(true) - $startIndex) * 1000,
    //             2
    //         ),
    //     ]);

    //     return view(
    //         'permintaan.cuti.index',
    //         compact(
    //             'permintaanCuti',
    //             'previewKode'
    //         )
    //     );
    // }

    private function filter(Request $request)
    {
        $search = $request->query('search');

        return PermintaanCuti::query()
            ->when($search, fn($query) => $query->where(function ($q) use ($search) {
                $q->where('kode', 'like', "%{$search}%");
            }));
    }

    public function create()
    {
        //
    }

    public function store(PermintaanCutiRequest $request)
    {
        $start = microtime(true);

        $data = $request->validated();

        $details = $data['details'];
        unset($data['details']);

        $permintaanCuti = DB::transaction(function () use ($data, $details) {

            $permintaanCuti = PermintaanCuti::create($data);

            foreach ($details as $detail) {
                $permintaanCuti->details()->create([
                    'tanggal' => $detail['tanggal'],
                    'setengah_hari' => $detail['setengah_hari'],
                ]);
            }

            return $permintaanCuti;
        });

        Log::debug('PermintaanCuti store timings', [
            'total_ms' => round(
                (microtime(true) - $start) * 1000,
                2
            ),
            'id' => $permintaanCuti->id,
        ]);

        return redirect()
            ->route('permintaan.index')
            ->with(
                'success',
                'Permintaan Cuti berhasil ditambahkan.'
            );
    }

    public function edit(PermintaanCuti $permintaanCuti)
    {
        //
    }

    public function editData($id)
    {
        $permintaanCuti = PermintaanCuti::with('details')
            ->findOrFail($id);

        if (
            $permintaanCuti->approved_at ||
            $permintaanCuti->rejected_at
        ) {
            return response()->json([
                'success' => false,
                'message' => 'Permintaan Cuti yang sudah diproses tidak dapat diubah.',
            ], 422);
        }

        return response()->json([
            'success' => true,
            'id' => $permintaanCuti->id,
            'kode' => $permintaanCuti->kode,
            'cuti_id' => $permintaanCuti->cuti_id,
            'karyawan_id' => $permintaanCuti->karyawan_id,
            'tanggal_mulai' => $permintaanCuti->tanggal_mulai,
            'tanggal_selesai' => $permintaanCuti->tanggal_selesai,
            'alasan' => $permintaanCuti->alasan,
            'lampiran' => $permintaanCuti->lampiran,
            'pengganti_karyawan_id' => $permintaanCuti->pengganti_karyawan_id,
            'details' => $permintaanCuti->details->map(function ($detail) {
                return [
                    'id' => $detail->id,
                    'tanggal' => $detail->tanggal,
                    'setengah_hari' => $detail->setengah_hari,
                ];
            }),
        ]);
    }

    public function update(
        PermintaanCutiRequest $request,
        PermintaanCuti $permintaanCuti
    ) {
        if (
            $permintaanCuti->approved_at ||
            $permintaanCuti->rejected_at
        ) {
            return redirect()
                ->route('permintaan.index')
                ->with(
                    'error',
                    'Permintaan Cuti yang sudah diproses tidak dapat diubah.'
                );
        }

        $data = $request->validated();

        $details = $data['details'];
        unset($data['details']);

        Log::debug('Data update permintaan cuti', [
            'id' => $permintaanCuti->id,
            'data' => $data,
            'details' => $details,
        ]);

        DB::transaction(function () use (
            $permintaanCuti,
            $data,
            $details
        ) {
            $permintaanCuti->update($data);

            $permintaanCuti->details()->forceDelete();

            foreach ($details as $detail) {
                $permintaanCuti->details()->create([
                    'tanggal' => $detail['tanggal'],
                    'setengah_hari' => $detail['setengah_hari'],
                ]);
            }
        });

        return redirect()
            ->route('permintaan.index')
            ->with(
                'success',
                'Permintaan Cuti berhasil diperbarui.'
            );
    }

    public function destroy($id)
    {
        $permintaanCuti = PermintaanCuti::findOrFail($id);

        if (
            $permintaanCuti->approved_at ||
            $permintaanCuti->rejected_at
        ) {
            return redirect()
                ->route('permintaan.index')
                ->with(
                    'error',
                    'Permintaan Cuti yang sudah diproses tidak dapat dihapus.'
                );
        }

        $permintaanCuti->delete();

        return redirect()
            ->route('permintaan.index')
            ->with(
                'success',
                'Permintaan Cuti berhasil dipindahkan ke Trash.'
            );
    }

    public function trash()
    {
        $permintaanCutis = PermintaanCuti::onlyTrashed()
            ->paginate(10);

        return view(
            'permintaan-cuti.trash',
            compact('permintaanCutis')
        );
    }

    public function restore($id)
    {
        PermintaanCuti::onlyTrashed()
            ->findOrFail($id)
            ->restore();

        return redirect()
            ->route('permintaan-cuti.trash')
            ->with(
                'success',
                'Permintaan Cuti berhasil dipulihkan.'
            );
    }

    public function forceDelete($id)
    {
        PermintaanCuti::onlyTrashed()
            ->findOrFail($id)
            ->forceDelete();

        return redirect()
            ->route('permintaan-cuti.trash')
            ->with(
                'success',
                'Permintaan Cuti berhasil dihapus permanen.'
            );
    }

    public function approve(PermintaanCuti $permintaanCuti)
    {
        if (
            $permintaanCuti->approved_at ||
            $permintaanCuti->rejected_at
        ) {
            return redirect()
                ->route('permintaan.index')
                ->with(
                    'error',
                    'Permintaan Cuti sudah pernah diproses.'
                );
        }

        $permintaanCuti->processed_by = auth()->id();
        $permintaanCuti->approved_at = now();
        $permintaanCuti->rejected_at = null;
        $permintaanCuti->save();

        return redirect()
            ->route('permintaan.index')
            ->with(
                'success',
                'Permintaan Cuti berhasil disetujui.'
            );
    }

    public function reject(PermintaanCuti $permintaanCuti)
    {
        if (
            $permintaanCuti->approved_at ||
            $permintaanCuti->rejected_at
        ) {
            return redirect()
                ->route('permintaan.index')
                ->with(
                    'error',
                    'Permintaan Cuti sudah pernah diproses.'
                );
        }

        $permintaanCuti->processed_by = auth()->id();
        $permintaanCuti->approved_at = null;
        $permintaanCuti->rejected_at = now();
        $permintaanCuti->save();

        return redirect()
            ->route('permintaan.index')
            ->with(
                'success',
                'Permintaan Cuti berhasil ditolak.'
            );
    }
}