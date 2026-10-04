<?php

namespace App\Console\Commands;

use App\Models\GeneralSetting;
use App\Models\Karyawan;
use App\Models\SaldoCuti;
use Carbon\Carbon;
use Illuminate\Console\Command;

class GenerateSaldoCuti extends Command
{
    protected $signature = 'saldo-cuti:generate';

    protected $description = 'Membuat saldo cuti tahunan untuk karyawan yang sudah berhak';

    public function handle(): int
    {
        $tahun = now()->year;

        $karyawans = Karyawan::query()
            ->where('status', 'aktif')
            ->with([
                'kontrakKaryawan' => function ($query) {
                    $query->orderBy('tanggal_mulai');
                },
            ])
            ->get();

        $dibuat = 0;
        $dilewati = 0;

        foreach ($karyawans as $karyawan) {
            $kontrakPertama = $karyawan->kontrakKaryawan
                ->sortBy('tanggal_mulai')
                ->first();

            if (!$kontrakPertama) {
                $dilewati++;
                continue;
            }

            $tanggalMulaiKerja = Carbon::parse($kontrakPertama->tanggal_mulai);
            $tanggalBerhakCuti = $tanggalMulaiKerja->copy()->addYear();

            if (now()->lt($tanggalBerhakCuti)) {
                $dilewati++;
                continue;
            }

            $saldo = SaldoCuti::firstOrCreate(
                [
                    'karyawan_id' => $karyawan->id,
                    'tahun' => $tahun,
                ],
                [
                    'saldo' => $this->ambilKuotaTahunan(),
                    'terpakai' => 0,
                ]
            );

            $this->sinkronkanTerpakai($saldo);

            if ($saldo->wasRecentlyCreated) {
                $dibuat++;
            }
        }

        $this->info("Saldo cuti tahun {$tahun} selesai diproses.");
        $this->info("Saldo baru dibuat: {$dibuat}");
        $this->info("Karyawan dilewati: {$dilewati}");

        return self::SUCCESS;
    }

    private function ambilKuotaTahunan(): int
    {
        return (int) (
            GeneralSetting::where('key', 'annual_leave_kuota')
                ->value('value') ?? 0
        );
    }

    private function sinkronkanTerpakai(SaldoCuti $saldo): void
    {
        $mulaiTahun = Carbon::create($saldo->tahun, 1, 1)->startOfDay();
        $akhirTahun = Carbon::create($saldo->tahun, 12, 31)->endOfDay();

        $terpakai = 0;

        $permintaanCutis = $saldo->karyawan
            ->permintaanCuti()
            ->with('cuti')
            ->whereNotNull('approved_at')
            ->whereNull('rejected_at')
            ->whereDate('tanggal_mulai', '<=', $akhirTahun)
            ->whereDate('tanggal_selesai', '>=', $mulaiTahun)
            ->get();

        foreach ($permintaanCutis as $permintaanCuti) {
            if (!$permintaanCuti->cuti) {
                continue;
            }

            if (strtolower(trim($permintaanCuti->cuti->nama)) !== 'cuti tahunan') {
                continue;
            }

            $tanggalMulai = Carbon::parse($permintaanCuti->tanggal_mulai)
                ->max($mulaiTahun);

            $tanggalSelesai = Carbon::parse($permintaanCuti->tanggal_selesai)
                ->min($akhirTahun);

            if ($tanggalMulai->lte($tanggalSelesai)) {
                $terpakai += $tanggalMulai->diffInDays($tanggalSelesai) + 1;
            }
        }

        $saldo->update([
            'terpakai' => $terpakai,
        ]);
    }
}