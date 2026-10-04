<?php

namespace App\Http\Controllers;

use App\Http\Requests\AttendanceRequest;
use App\Models\Attendance;
use App\Models\JadwalKaryawan;
use App\Models\PermintaanCuti;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;

class AttendanceController extends Controller
{
    /**
     * Halaman Attendance.
     */
    public function index()
    {
        return view('attendance.index', $this->todayStatus());
    }

    /**
     * Kondisi absensi user yang login untuk HARI INI (hanya membaca, tidak menulis).
     * Dipakai view Attendance dan Dashboard supaya tombol Check-in/Check-out tahu kapan aktif.
     * Urutan cek mengikuti checkIn() / checkOut() di bawah.
     *
     * $state: no_karyawan | no_jadwal | cuti | ready | working | done | recorded
     */
    public function todayStatus(): array
    {
        $now = now();
        $tanggal = $now->toDateString();

        $data = [
            'state' => 'no_karyawan',
            'now' => $now,
            'attendance' => null,
            'shift' => null,
            'canCheckIn' => false,
            'canCheckOut' => false,
            'earlyCheckout' => false,
            'message' => null,
        ];

        $karyawan = auth()->user()?->karyawan;

        if (!$karyawan) {
            $data['message'] = 'Akun kamu belum terhubung dengan data karyawan, jadi absensi belum bisa dilakukan. Hubungi HR/Admin.';
            return $data;
        }

        $attendance = Attendance::query()
            ->with('shift')
            ->where('karyawan_id', $karyawan->id)
            ->whereDate('tanggal', $tanggal)
            ->first();

        if ($attendance) {
            $data['attendance'] = $attendance;
            $data['shift'] = $attendance->shift;

            if ($attendance->jam_masuk && !$attendance->jam_keluar) {
                $data['state'] = 'working';
                $data['canCheckOut'] = true;

                if ($attendance->shift) {
                    $pulang = Carbon::parse($attendance->shift->jam_pulang);
                    $waktuPulang = Carbon::today()->setTime($pulang->hour, $pulang->minute, $pulang->second);

                    if ($attendance->shift->lintas_hari) {
                        $waktuPulang->addDay();
                    }

                    $data['earlyCheckout'] = $now->lessThan($waktuPulang);
                }
            } elseif ($attendance->jam_masuk && $attendance->jam_keluar) {
                $data['state'] = 'done';
            } else {
                $data['state'] = 'recorded';
            }

            return $data;
        }

        $jadwal = JadwalKaryawan::query()
            ->with('shift')
            ->where('karyawan_id', $karyawan->id)
            ->whereDate('tanggal', $tanggal)
            ->where('status', 'aktif')
            ->first();

        if (!$jadwal || !$jadwal->shift) {
            $data['state'] = 'no_jadwal';
            $data['message'] = !$jadwal
                ? 'Kamu tidak memiliki jadwal kerja hari ini.'
                : 'Shift pada jadwal hari ini tidak ditemukan.';
            return $data;
        }

        $data['shift'] = $jadwal->shift;

        $sedangCuti = PermintaanCuti::query()
            ->where('karyawan_id', $karyawan->id)
            ->whereDate('tanggal_mulai', '<=', $tanggal)
            ->whereDate('tanggal_selesai', '>=', $tanggal)
            ->whereNotNull('approved_at')
            ->whereNull('rejected_at')
            ->exists();

        if ($sedangCuti) {
            $data['state'] = 'cuti';
            $data['message'] = 'Kamu sedang dalam masa cuti yang telah disetujui.';
            return $data;
        }

        $data['state'] = 'ready';
        $data['canCheckIn'] = true;

        return $data;
    }

    public function checkIn(AttendanceRequest $request): RedirectResponse
    {
        $user = auth()->user();

        // 1. Ambil karyawan dari user yang sedang login
        $karyawan = $user->karyawan;

        if (!$karyawan) {
            return back()
                ->with('error', 'Akun kamu belum terhubung dengan data karyawan.');
        }

        $tanggal = now()->toDateString();

        // 2. Cek apakah sudah check-in hari ini
        $attendanceSudahAda = Attendance::query()
            ->where('karyawan_id', $karyawan->id)
            ->whereDate('tanggal', $tanggal)
            ->exists();

        if ($attendanceSudahAda) {
            return back()
                ->with('error', 'Kamu sudah melakukan absensi hari ini.');
        }

        // 3. Cek jadwal kerja hari ini
        $jadwal = JadwalKaryawan::query()
            ->with('shift')
            ->where('karyawan_id', $karyawan->id)
            ->whereDate('tanggal', $tanggal)
            ->where('status', 'aktif')
            ->first();

        if (!$jadwal) {
            return back()
                ->with('error', 'Kamu tidak memiliki jadwal kerja hari ini.');
        }

        // 4. Pastikan shift tersedia
        if (!$jadwal->shift) {
            return back()
                ->with('error', 'Shift pada jadwal hari ini tidak ditemukan.');
        }

        $shift = $jadwal->shift;

        // 5. Cek cuti yang sudah disetujui
        $cuti = PermintaanCuti::query()
            ->where('karyawan_id', $karyawan->id)
            ->whereDate('tanggal_mulai', '<=', $tanggal)
            ->whereDate('tanggal_selesai', '>=', $tanggal)
            ->whereNotNull('approved_at')
            ->whereNull('rejected_at')
            ->exists();

        if ($cuti) {
            return back()
                ->with('error', 'Kamu sedang dalam masa cuti yang telah disetujui.');
        }

        // 6. Waktu check-in sekarang
        $sekarang = now();

        // 7. Waktu masuk berdasarkan shift
        $jamMasukShift = Carbon::parse($shift->jam_masuk);

        $waktuMasukShift = Carbon::today()->setTime(
            $jamMasukShift->hour,
            $jamMasukShift->minute,
            $jamMasukShift->second
        );

        // 8. Ambil toleransi keterlambatan
        $toleransi = (int) ($shift->toleransi_keterlambatan ?? 0);

        $batasToleransi = $waktuMasukShift->copy()
            ->addMinutes($toleransi);

        // 9. Tentukan status absensi
        $status = 'hadir';
        $totalMenitTerlambat = null;

        if ($sekarang->greaterThan($batasToleransi)) {
            $status = 'terlambat';

            $totalMenitTerlambat = $waktuMasukShift->diffInMinutes($sekarang);
        }

        // 10. Simpan attendance
        Attendance::create([
            'karyawan_id' => $karyawan->id,
            'shift_id' => $shift->id,
            'tanggal' => $tanggal,
            'latitude' => $request->validated('latitude'),
            'longitude' => $request->validated('longitude'),
            'is_manual' => false,
            'keterangan' => $request->validated('keterangan'),
            'jam_masuk' => $sekarang->format('H:i:s'),
            'jam_keluar' => null,
            'total_menit_terlambat' => $totalMenitTerlambat,
            'total_menit_pulang_cepat' => null,
            'total_menit_kerja' => null,
            'input_by' => $user->id,
            'status' => $status,
        ]);

        return back()
            ->with(
                'success',
                $status === 'terlambat'
                    ? 'Check in berhasil. Kamu tercatat terlambat.'
                    : 'Check in berhasil.'
            );
    }

    public function checkOut(AttendanceRequest $request): RedirectResponse
    {
        $user = auth()->user();

        // 1. Ambil karyawan dari user yang sedang login
        $karyawan = $user->karyawan;

        if (!$karyawan) {
            return back()
                ->with('error', 'Akun kamu belum terhubung dengan data karyawan.');
        }

        $tanggal = now()->toDateString();

        // 2. Cari attendance hari ini
        $attendance = Attendance::query()
            ->where('karyawan_id', $karyawan->id)
            ->whereDate('tanggal', $tanggal)
            ->whereNotNull('jam_masuk')
            ->whereNull('jam_keluar')
            ->first();

        if (!$attendance) {
            return back()
                ->with('error', 'Kamu belum melakukan check in atau sudah melakukan check out.');
        }

        // 3. Waktu check out
        $sekarang = now();

        // 4. Ambil shift
        $shift = $attendance->shift;

        if (!$shift) {
            return back()
                ->with('error', 'Shift pada attendance tidak ditemukan.');
        }

        // 5. Tentukan waktu pulang berdasarkan shift
        $jamPulangShift = Carbon::parse($shift->jam_pulang);

        $waktuPulangShift = Carbon::today()->setTime(
            $jamPulangShift->hour,
            $jamPulangShift->minute,
            $jamPulangShift->second
        );

        // Jika shift lintas hari, waktu pulang berada di hari berikutnya
        if ($shift->lintas_hari) {
            $waktuPulangShift->addDay();
        }

        // 6. Hitung pulang cepat
        $totalMenitPulangCepat = null;

        if ($sekarang->lessThan($waktuPulangShift)) {
            $totalMenitPulangCepat = $sekarang->diffInMinutes($waktuPulangShift);
        }

        // 7. Hitung total menit kerja
        $waktuMasuk = Carbon::parse(
            $attendance->tanggal . ' ' . $attendance->jam_masuk
        );

        $totalMenitKerja = $waktuMasuk->diffInMinutes($sekarang);

        // 8. Update attendance
        $attendance->update([
            'jam_keluar' => $sekarang->format('H:i:s'),
            'latitude' => $request->validated('latitude'),
            'longitude' => $request->validated('longitude'),
            'total_menit_pulang_cepat' => $totalMenitPulangCepat,
            'total_menit_kerja' => $totalMenitKerja,
        ]);

        return back()
            ->with('success', 'Check out berhasil.');
    }
}
