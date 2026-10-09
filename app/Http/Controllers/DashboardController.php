<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Models\Departemen;
use App\Models\StatusKepegawaian;
use App\Models\Lowongan;
use App\Models\Rekrutmen;
use App\Models\CabangKantor;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | FILTER PERIODE DASHBOARD
        |--------------------------------------------------------------------------
        */

        $tanggalMulai = $request->filled('periode_from')
            ? Carbon::parse($request->input('periode_from'))->startOfDay()
            : now()->startOfMonth();

        $tanggalSelesai = $request->filled('periode_to')
            ? Carbon::parse($request->input('periode_to'))->endOfDay()
            : now()->endOfDay();

        if ($tanggalMulai->gt($tanggalSelesai)) {
            $tanggalAwal = $tanggalMulai->copy();

            $tanggalMulai = $tanggalSelesai->copy()->startOfDay();
            $tanggalSelesai = $tanggalAwal->copy()->endOfDay();
        }

        /*
        |--------------------------------------------------------------------------
        | FILTER CABANG DAN DEPARTEMEN
        |--------------------------------------------------------------------------
        */

        $cabangIds = collect((array) $request->input('cabang', []))
            ->filter(fn ($id) => is_scalar($id) && is_numeric($id))
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values()
            ->all();

        $departemenIds = collect((array) $request->input('departemen', []))
            ->filter(fn ($id) => is_scalar($id) && is_numeric($id))
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values()
            ->all();

        /*
        |--------------------------------------------------------------------------
        | QUERY DASAR KARYAWAN
        |--------------------------------------------------------------------------
        */

        $queryKaryawan = Karyawan::query();

        if (!empty($cabangIds)) {
            $queryKaryawan->whereIn('cabang_kantor_id', $cabangIds);
        }

        if (!empty($departemenIds)) {
            $queryKaryawan->whereIn('departemen_id', $departemenIds);
        }

        /*
        |--------------------------------------------------------------------------
        | KPI KARYAWAN
        |--------------------------------------------------------------------------
        */

        $totalKaryawan = (clone $queryKaryawan)
            ->where('status', 'aktif')
            ->count();

        $karyawanBaru = (clone $queryKaryawan)
            ->whereBetween('created_at', [
                $tanggalMulai,
                $tanggalSelesai,
            ])
            ->count();

        $karyawanKeluar = (clone $queryKaryawan)
            ->where('status', 'resign')
            ->whereBetween('updated_at', [
                $tanggalMulai,
                $tanggalSelesai,
            ])
            ->count();

        $turnoverRate = $totalKaryawan > 0
            ? round(($karyawanKeluar / $totalKaryawan) * 100, 1)
            : 0;

        /*
        |--------------------------------------------------------------------------
        | KARYAWAN PER DEPARTEMEN
        |--------------------------------------------------------------------------
        */

        $queryDepartemen = Departemen::query()
            ->where('status', 'aktif');

        if (!empty($departemenIds)) {
            $queryDepartemen->whereIn('id', $departemenIds);
        }

        $karyawanPerDepartemen = $queryDepartemen
            ->withCount([
                'karyawan as jumlah_karyawan' => function ($query) use ($cabangIds) {
                    $query->where('status', 'aktif');

                    if (!empty($cabangIds)) {
                        $query->whereIn('cabang_kantor_id', $cabangIds);
                    }
                },
            ])
            ->orderBy('nama')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | KOMPOSISI STATUS KEPEGAWAIAN
        |--------------------------------------------------------------------------
        */

        $komposisiKepegawaian = StatusKepegawaian::query()
            ->where('status', 'aktif')
            ->withCount([
                'karyawan as jumlah_karyawan' => function ($query) use (
                    $cabangIds,
                    $departemenIds
                ) {
                    $query->where('status', 'aktif');

                    if (!empty($cabangIds)) {
                        $query->whereIn('cabang_kantor_id', $cabangIds);
                    }

                    if (!empty($departemenIds)) {
                        $query->whereIn('departemen_id', $departemenIds);
                    }
                },
            ])
            ->orderBy('nama')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | PILIHAN FILTER
        |--------------------------------------------------------------------------
        */

        $cabangOptions = CabangKantor::query()
            ->where('status', 'aktif')
            ->orderBy('nama')
            ->pluck('nama', 'id')
            ->toArray();

        $departemenOptions = Departemen::query()
            ->where('status', 'aktif')
            ->orderBy('nama')
            ->pluck('nama', 'id')
            ->toArray();

        /*
        |--------------------------------------------------------------------------
        | LOWONGAN DAN PELAMAR
        |--------------------------------------------------------------------------
        */

        $lowonganAktif = Lowongan::count();

        $pelamarBaru = Rekrutmen::query()
            ->whereBetween('created_at', [
                $tanggalMulai,
                $tanggalSelesai,
            ])
            ->count();

        /*
        |--------------------------------------------------------------------------
        | ATTENDANCE HARI INI
        |--------------------------------------------------------------------------
        */

        $attendanceController = app(AttendanceController::class);

        $attendanceToday = $attendanceController->todayStatus();

        /*
        |--------------------------------------------------------------------------
        | MONITORING ATTENDANCE
        |--------------------------------------------------------------------------
        */

        $queryAttendance = Attendance::with(['karyawan', 'shift'])
            ->orderByDesc('tanggal')
            ->orderByDesc('karyawan_id');

        // Pencarian nama atau NIP karyawan.
        if ($request->filled('search')) {
            $search = $request->input('search');

            $queryAttendance->whereHas('karyawan', function ($query) use ($search) {
                $query->where('nama', 'like', "%{$search}%")
                    ->orWhere('nip', 'like', "%{$search}%");
            });
        }

        // Filter status attendance.
        if ($request->filled('status')) {
            $statuses = (array) $request->input('status', []);

            $queryAttendance->whereIn('status', $statuses);
        }

        // Filter tanggal monitoring.
        if ($request->filled('tanggal_from')) {
            $queryAttendance->whereDate(
                'tanggal',
                '>=',
                $request->input('tanggal_from')
            );
        }

        if ($request->filled('tanggal_to')) {
            $queryAttendance->whereDate(
                'tanggal',
                '<=',
                $request->input('tanggal_to')
            );
        }

        $attendances = $queryAttendance
            ->paginate(10)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | KIRIM DATA KE DASHBOARD
        |--------------------------------------------------------------------------
        */

        return view('dashboard', array_merge(
            compact(
                'totalKaryawan',
                'karyawanBaru',
                'karyawanKeluar',
                'turnoverRate',
                'lowonganAktif',
                'pelamarBaru',
                'karyawanPerDepartemen',
                'komposisiKepegawaian',
                'cabangOptions',
                'departemenOptions',
                'tanggalMulai',
                'tanggalSelesai',
                'cabangIds',
                'departemenIds',
                'attendances'
            ),
            $attendanceToday
        ));
    }
}

