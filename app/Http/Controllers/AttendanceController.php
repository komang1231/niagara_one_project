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
