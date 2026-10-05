<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\ClosingPeriodeAbsensi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClosingPeriodeAbsensiController extends Controller
{
    public function index(Request $request)
    {
        $namaBulan = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];

        $query = ClosingPeriodeAbsensi::query()
            ->with('user')
            ->orderByDesc('tahun')
            ->orderByDesc('bulan');

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search, $namaBulan) {
                $q->whereHas('user', function ($userQuery) use ($search) {
                    $userQuery->where('nama', 'like', "%{$search}%");
                });

                foreach ($namaBulan as $bulan => $nama) {
                    if (stripos($nama, $search) !== false) {
                        $q->orWhere('bulan', $bulan);
                    }
                }

                if (is_numeric($search)) {
                    $q->orWhere('tahun', (int) $search);
                }
            });
        }

        if ($request->has('tahun')) {
            $tahun = (array) $request->query('tahun', []);

            if (!empty($tahun)) {
                $query->whereIn('tahun', $tahun);
            }
        }

        if ($request->has('bulan')) {
            $bulan = (array) $request->query('bulan', []);

            if (!empty($bulan)) {
                $query->whereIn('bulan', $bulan);
            }
        }

        $closingAttendances = $query
            ->paginate(10)
            ->withQueryString();

        $tahunOptions = ClosingPeriodeAbsensi::query()
            ->select('tahun')
            ->distinct()
            ->orderByDesc('tahun')
            ->pluck('tahun', 'tahun');

        if ($tahunOptions->isEmpty()) {
            $tahunOptions = collect([
                now()->year => (string) now()->year,
            ]);
        }

        return view('closing-attendance.index', compact(
            'closingAttendances',
            'namaBulan',
            'tahunOptions'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'bulan_closing' => ['required', 'integer', 'between:1,12'],
            'tahun_closing' => ['required', 'integer', 'min:2000', 'max:2100'],
        ]);

        $bulan = (int) $validated['bulan_closing'];
        $tahun = (int) $validated['tahun_closing'];

        $sudahClosing = ClosingPeriodeAbsensi::query()
            ->where('bulan', $bulan)
            ->where('tahun', $tahun)
            ->exists();

        if ($sudahClosing) {
            return redirect()
                ->route('closing-attendance.index')
                ->with(
                    'error',
                    'Periode tersebut sudah pernah di-closing.'
                );
        }

        DB::transaction(function () use ($bulan, $tahun) {
            ClosingPeriodeAbsensi::create([
                'bulan' => $bulan,
                'tahun' => $tahun,
                'closed_by' => auth()->id(),
            ]);
        });

        return redirect()
            ->route('closing-attendance.index')
            ->with(
                'success',
                'Periode absensi berhasil di-closing.'
            );
    }

    public function show($id)
    {
        $closing = ClosingPeriodeAbsensi::query()
            ->with('user')
            ->findOrFail($id);

        $awal = Carbon::create(
            $closing->tahun,
            $closing->bulan,
            1
        )->startOfMonth();

        $akhir = $awal->copy()->endOfMonth();

        $attendances = Attendance::query()
            ->with([
                'karyawan',
                'shift',
            ])
            ->whereBetween('tanggal', [
                $awal->toDateString(),
                $akhir->toDateString(),
            ])
            ->orderBy('tanggal')
            ->orderBy('karyawan_id')
            ->get();

        $summary = [
            'total_karyawan' => $attendances
                ->pluck('karyawan_id')
                ->unique()
                ->count(),

            'hadir' => $attendances
                ->where('status', 'hadir')
                ->count(),

            'terlambat' => $attendances
                ->where('status', 'terlambat')
                ->count(),

            'cuti' => $attendances
                ->where('status', 'cuti')
                ->count(),

            'izin' => $attendances
                ->where('status', 'izin')
                ->count(),

            'alpa' => $attendances
                ->where('status', 'alpa')
                ->count(),
        ];

        return view('closing-attendance.detail', compact(
            'closing',
            'awal',
            'akhir',
            'attendances',
            'summary'
        ));
    }
}