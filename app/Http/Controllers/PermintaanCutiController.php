<?php

namespace App\Http\Controllers;

use App\Http\Requests\PermintaanCutiRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\PermintaanCuti;
use App\Models\PermintaanCutiDetail;
use App\Services\CodeGenerator;
use App\Models\SaldoCuti;
use Carbon\Carbon;
use App\Models\Cuti;
use Illuminate\Validation\ValidationException;

class PermintaanCutiController extends Controller
{
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

        $data['karyawan_id'] = auth()->user()->karyawan_id;

        /*
     * Tentukan jenis cuti.
     */
        $cuti = Cuti::find($data['cuti_id']);

        $isCutiTahunan = $cuti
            && preg_replace('/\s+/', ' ', trim($cuti->nama)) === 'Cuti Tahunan';
        

        /*
     * Cuti Tahunan tidak mengisi details dari form.
     * Detail tanggal dibuat otomatis berdasarkan tanggal mulai - selesai.
     *
     * Cuti selain Tahunan tetap menggunakan details dari form.
     */
        if ($isCutiTahunan) {
            $details = [];

            $tanggalMulai = Carbon::parse($data['tanggal_mulai']);
            $tanggalSelesai = Carbon::parse($data['tanggal_selesai']);

            for (
                $tanggal = $tanggalMulai->copy();
                $tanggal->lte($tanggalSelesai);
                $tanggal->addDay()
            ) {
                $details[] = [
                    'tanggal' => $tanggal->format('Y-m-d'),
                    'setengah_hari' => false,
                ];
            }
        } else {
            $details = $data['details'];
        }

        unset($data['details']);

        /*
     * Validasi saldo Cuti Tahunan sebelum permintaan dibuat.
     */
        if ($isCutiTahunan) {
            $tahun = Carbon::parse($data['tanggal_mulai'])->year;

            $saldoCuti = SaldoCuti::where('karyawan_id', $data['karyawan_id'])
                ->where('tahun', $tahun)
                ->first();

            if (!$saldoCuti) {
                throw ValidationException::withMessages([
                    'tanggal_mulai' =>
                    'Kamu belum memiliki saldo Cuti Tahunan karena belum memenuhi masa kerja 1 tahun.',
                ]);
            }

            $tanggalMulai = Carbon::parse($data['tanggal_mulai']);
            $tanggalSelesai = Carbon::parse($data['tanggal_selesai']);

            $jumlahHari = $tanggalMulai->diffInDays($tanggalSelesai) + 1;

            $sisaSaldo = $saldoCuti->saldo - $saldoCuti->terpakai;

            if ($jumlahHari > $sisaSaldo) {
                throw ValidationException::withMessages([
                    'tanggal_mulai' =>
                    "Sisa Cuti Tahunan kamu hanya {$sisaSaldo} hari, sedangkan pengajuan membutuhkan {$jumlahHari} hari.",
                ]);
            }
        }

        $permintaanCuti = DB::transaction(function () use ($data, $details, $isCutiTahunan) {

            $permintaanCuti = PermintaanCuti::create($data);

            foreach ($details as $detail) {
                $permintaanCuti->details()->create([
                    'tanggal' => $detail['tanggal'],
                    'setengah_hari' => $detail['setengah_hari'],
                ]);
            }

            // Super Admin langsung disetujui.
            if (auth()->user()->role?->nama === 'Super Admin') {

                if ($isCutiTahunan) {
                    $tahun = Carbon::parse($permintaanCuti->tanggal_mulai)->year;

                    $saldoCuti = SaldoCuti::where(
                        'karyawan_id',
                        $permintaanCuti->karyawan_id
                    )
                        ->where('tahun', $tahun)
                        ->lockForUpdate()
                        ->first();

                    if (!$saldoCuti) {
                        throw new \RuntimeException(
                            'Permintaan Cuti tidak dapat disetujui karena saldo Cuti Tahunan tidak tersedia.'
                        );
                    }

                    $tanggalMulai = Carbon::parse($permintaanCuti->tanggal_mulai);
                    $tanggalSelesai = Carbon::parse($permintaanCuti->tanggal_selesai);

                    $jumlahHari = $tanggalMulai->diffInDays($tanggalSelesai) + 1;

                    $sisaSaldo = $saldoCuti->saldo - $saldoCuti->terpakai;

                    if ($jumlahHari > $sisaSaldo) {
                        throw new \RuntimeException(
                            "Permintaan Cuti tidak dapat disetujui karena sisa Cuti Tahunan hanya {$sisaSaldo} hari, sedangkan pengajuan membutuhkan {$jumlahHari} hari."
                        );
                    }

                    $saldoCuti->update([
                        'terpakai' => $saldoCuti->terpakai + $jumlahHari,
                    ]);
                }

                $permintaanCuti->processed_by = auth()->id();
                $permintaanCuti->approved_at = now();
                $permintaanCuti->rejected_at = null;
                $permintaanCuti->save();
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

        $message = auth()->user()->role?->nama === 'Super Admin'
            ? 'Permintaan Cuti berhasil diajukan dan langsung disetujui.'
            : 'Permintaan Cuti berhasil ditambahkan.';

        return redirect()
            ->route('permintaan.index')
            ->with('success', $message);
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

    public function update(PermintaanCutiRequest $request, PermintaanCuti $permintaanCuti)
    {
        dd($request->all());

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

    // public function approve(PermintaanCuti $permintaanCuti)
    // {
    //     if (
    //         $permintaanCuti->approved_at ||
    //         $permintaanCuti->rejected_at
    //     ) {
    //         return redirect()
    //             ->route('permintaan.index')
    //             ->with(
    //                 'error',
    //                 'Permintaan Cuti sudah pernah diproses.'
    //             );
    //     }

    //     $permintaanCuti->processed_by = auth()->id();
    //     $permintaanCuti->approved_at = now();
    //     $permintaanCuti->rejected_at = null;
    //     $permintaanCuti->save();

    //     return redirect()
    //         ->route('permintaan.index')
    //         ->with(
    //             'success',
    //             'Permintaan Cuti berhasil disetujui.'
    //         );
    // }

    // public function reject(PermintaanCuti $permintaanCuti)
    // {
    //     if (
    //         $permintaanCuti->approved_at ||
    //         $permintaanCuti->rejected_at
    //     ) {
    //         return redirect()
    //             ->route('permintaan.index')
    //             ->with(
    //                 'error',
    //                 'Permintaan Cuti sudah pernah diproses.'
    //             );
    //     }

    //     $permintaanCuti->processed_by = auth()->id();
    //     $permintaanCuti->approved_at = null;
    //     $permintaanCuti->rejected_at = now();
    //     $permintaanCuti->save();

    //     return redirect()
    //         ->route('permintaan.index')
    //         ->with(
    //             'success',
    //             'Permintaan Cuti berhasil ditolak.'
    //         );
    // }
}
