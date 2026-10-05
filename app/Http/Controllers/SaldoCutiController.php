<?php

namespace App\Http\Controllers;

use App\Models\GeneralSetting;
use App\Models\Karyawan;
use App\Models\SaldoCuti;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SaldoCutiController extends Controller
{
    public function index(Request $request)
    {
        $tahun = now()->year;

        // Buat saldo tahun berjalan untuk karyawan yang sudah berhak
        $this->buatSaldoTahunBerjalan();

        $query = SaldoCuti::with([
            'karyawan.departemen',
        ])
            ->where('tahun', $tahun)
            ->orderByDesc('tahun');

        // Pencarian berdasarkan nama atau NIP karyawan
        if ($request->filled('search')) {
            $search = $request->search;

            $query->whereHas('karyawan', function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('nip', 'like', "%{$search}%");
            });
        }

        $saldoCuti = $query
            ->paginate(10)
            ->withQueryString();

        return view('saldo-cuti.index', compact(
            'saldoCuti',
            'tahun'
        ));
    }

    private function buatSaldoTahunBerjalan(): void
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

        foreach ($karyawans as $karyawan) {
            $this->buatSaldoJikaBerhak($karyawan, $tahun);
        }
    }

    private function buatSaldoJikaBerhak(?Karyawan $karyawan, ?int $tahun = null): ?SaldoCuti
    {
        if (!$karyawan) {
            return null;
        }

        $tahun ??= now()->year;

        if ($karyawan->status !== 'aktif') {
            return null;
        }

        $kontrakPertama = $karyawan->KontrakKaryawan
            ->sortBy('tanggal_mulai')
            ->first();

        if (!$kontrakPertama) {
            return null;
        }

        $tanggalMulaiKerja = Carbon::parse($kontrakPertama->tanggal_mulai);
        $tanggalBerhakCuti = $tanggalMulaiKerja->copy()->addYear();

        if (now()->lt($tanggalBerhakCuti)) {
            return null;
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

        return $saldo->fresh();
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
