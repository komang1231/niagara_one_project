<?php

namespace App\Http\Controllers;

use App\Models\PermintaanKaryawan;
use App\Models\PermintaanCuti;
use App\Models\PermintaanLembur;
use App\Models\PermintaanResign;
use App\Models\PermintaanTukarShift;
use App\Services\ApprovalService;
use App\Models\SaldoCuti;
use Carbon\Carbon;

class ApprovalController extends Controller
{
    public function index()
    {
        $tabs = [
            'karyawan' => 'Karyawan',
            'cuti' => 'Cuti',
            'lembur' => 'Lembur',
            'resign' => 'Resign',
            'tukar-shift' => 'Tukar Shift',
        ];
        $tab = collect($tabs)->map(fn($label, $key) => [
            'key' => $key,
            'label' => $label,
        ])->values();
        $user = auth()->user();

        $allowedRequesterRoles = match ($user->role->nama) {
            'HR Manager' => [
                'HR Staff',
                'Recruiter',
                'Head of Departemen',
                'Employee',
            ],

            'Admin Tenant' => [
                'HR Manager',
            ],

            'Super Admin' => [
                'Admin Tenant',
            ],

            default => [],
        };

        $permintaanKaryawan = PermintaanKaryawan::whereNull('processed_by')
            ->whereHas('karyawan.user.role', function ($query) use ($allowedRequesterRoles) {
                $query->whereIn('nama', $allowedRequesterRoles);
            })
            ->latest('id')
            ->paginate(10, ['*'], 'karyawan_page')
            ->withQueryString();

        $permintaanCuti = PermintaanCuti::WhereHas('karyawan.user.role', function ($query) use ($allowedRequesterRoles) {
                $query->whereIn('nama', $allowedRequesterRoles);
            })
            ->latest('id')
            ->paginate(10, ['*'], 'cuti_page')
            ->withQueryString();

        $permintaanLembur = PermintaanLembur::whereNull('processed_by')
            ->whereNull('rejected_at')
            ->whereHas('karyawan.user.role', function ($query) use ($allowedRequesterRoles) {
                $query->whereIn('nama', $allowedRequesterRoles);
            })
            ->latest('id')
            ->paginate(10, ['*'], 'lembur_page')
            ->withQueryString();

        $permintaanResign = PermintaanResign::whereNull('processed_by')
            ->whereNull('rejected_at')
            ->whereHas('karyawan.user.role', function ($query) use ($allowedRequesterRoles) {
                $query->whereIn('nama', $allowedRequesterRoles);
            })
            ->latest('id')
            ->paginate(10, ['*'], 'resign_page')
            ->withQueryString();

        $permintaanTukarShift = PermintaanTukarShift::whereNull('processed_by')
            ->whereNull('rejected_at')
            ->whereHas('karyawanPengaju.user.role', function ($query) use ($allowedRequesterRoles) {
                $query->whereIn('nama', $allowedRequesterRoles);
            })
            ->latest('id')
            ->paginate(10, ['*'], 'tukar_shift_page')
            ->withQueryString();

        return view('approval.index', compact(
            'tabs',
            'tab',

            'permintaanKaryawan',
            'permintaanCuti',
            'permintaanLembur',
            'permintaanResign',
            'permintaanTukarShift'
        ));
    }

    public function approveKaryawan($id)
    {
        $permintaan = PermintaanKaryawan::findOrFail($id);

        if ($permintaan->approved_at || $permintaan->rejected_at) {
            return redirect()
                ->route('approval.index')
                ->with('error', 'Permintaan Karyawan sudah pernah diproses.');
        }

        $pemohon = $permintaan->karyawan?->user;

        if (!$pemohon || !ApprovalService::getApprovers($pemohon)->contains('id', auth()->id())) {
            return redirect()
                ->route('approval.index')
                ->with('error', 'Kamu tidak memiliki izin untuk menyetujui permintaan ini.');
        }

        $permintaan->processed_by = auth()->id();
        $permintaan->approved_at = now();
        $permintaan->rejected_at = null;
        $permintaan->save();

        return redirect()
            ->route('approval.index')
            ->with('success', 'Permintaan Karyawan berhasil disetujui.');
    }

    public function rejectKaryawan($id)
    {
        $permintaan = PermintaanKaryawan::findOrFail($id);

        if ($permintaan->approved_at || $permintaan->rejected_at) {
            return redirect()
                ->route('approval.index')
                ->with('error', 'Permintaan Karyawan sudah pernah diproses.');
        }

        $pemohon = $permintaan->karyawan?->user;

        if (!$pemohon || !ApprovalService::getApprovers($pemohon)->contains('id', auth()->id())) {
            return redirect()
                ->route('approval.index')
                ->with('error', 'Kamu tidak memiliki izin untuk menolak permintaan ini.');
        }

        $permintaan->processed_by = auth()->id();
        $permintaan->approved_at = null;
        $permintaan->rejected_at = now();
        $permintaan->save();

        return redirect()
            ->route('approval.index')
            ->with('success', 'Permintaan Karyawan berhasil ditolak.');
    }

    public function approveCuti($id)
    {
        $permintaan = PermintaanCuti::findOrFail($id);

        if ($permintaan->approved_at || $permintaan->rejected_at) {
            return redirect()
                ->route('approval.index')
                ->with('error', 'Permintaan Cuti sudah pernah diproses.');
        }

        $pemohon = $permintaan->karyawan?->user;

        if (!$pemohon || !ApprovalService::getApprovers($pemohon)->contains('id', auth()->id())) {
            return redirect()
                ->route('approval.index')
                ->with('error', 'Kamu tidak memiliki izin untuk memproses permintaan ini.');
        }

        /*
     * Cuti Tahunan wajib memiliki saldo cuti.
     */
        $isCutiTahunan = $permintaan->cuti
            && strtolower(trim($permintaan->cuti->nama)) === 'cuti tahunan';

        if ($isCutiTahunan) {
            $tahun = Carbon::parse($permintaan->tanggal_mulai)->year;

            $saldoCuti = SaldoCuti::where('karyawan_id', $permintaan->karyawan_id)
                ->where('tahun', $tahun)
                ->first();

            if (!$saldoCuti) {
                return redirect()
                    ->route('approval.index')
                    ->with(
                        'error',
                        'Permintaan Cuti tidak dapat disetujui karena karyawan belum memenuhi masa kerja 1 tahun untuk mendapatkan saldo Cuti Tahunan.'
                    );
            }

            $tanggalMulai = Carbon::parse($permintaan->tanggal_mulai);
            $tanggalSelesai = Carbon::parse($permintaan->tanggal_selesai);

            $jumlahHari = $tanggalMulai->diffInDays($tanggalSelesai) + 1;

            $sisaSaldo = $saldoCuti->saldo - $saldoCuti->terpakai;

            if ($jumlahHari > $sisaSaldo) {
                return redirect()
                    ->route('approval.index')
                    ->with(
                        'error',
                        "Permintaan Cuti tidak dapat disetujui karena sisa Cuti Tahunan hanya {$sisaSaldo} hari, sedangkan pengajuan membutuhkan {$jumlahHari} hari."
                    );
            }

            $saldoCuti->update([
                'terpakai' => $saldoCuti->terpakai + $jumlahHari,
            ]);
        }

        $permintaan->processed_by = auth()->id();
        $permintaan->approved_at = now();
        $permintaan->rejected_at = null;
        $permintaan->save();

        return redirect()
            ->route('approval.index')
            ->with('success', 'Permintaan Cuti berhasil disetujui.');
    }

    public function rejectCuti($id)
    {
        $permintaan = PermintaanCuti::findOrFail($id);

        if ($permintaan->approved_at || $permintaan->rejected_at) {
            return redirect()
                ->route('approval.index')
                ->with('error', 'Permintaan Cuti sudah pernah diproses.');
        }

        $pemohon = $permintaan->karyawan?->user;

        if (!$pemohon || !ApprovalService::getApprovers($pemohon)->contains('id', auth()->id())) {
            return redirect()
                ->route('approval.index')
                ->with('error', 'Kamu tidak memiliki izin untuk memproses permintaan ini.');
        }

        $permintaan->processed_by = auth()->id();
        $permintaan->approved_at = null;
        $permintaan->rejected_at = now();
        $permintaan->save();

        return redirect()
            ->route('approval.index')
            ->with('success', 'Permintaan Cuti berhasil ditolak.');
    }

    public function approveLembur($id)
    {
        $permintaan = PermintaanLembur::findOrFail($id);

        if ($permintaan->approved_at || $permintaan->rejected_at) {
            return redirect()
                ->route('approval.index')
                ->with('error', 'Permintaan Lembur sudah pernah diproses.');
        }

        $pemohon = $permintaan->karyawan?->user;

        if (!$pemohon || !ApprovalService::getApprovers($pemohon)->contains('id', auth()->id())) {
            return redirect()
                ->route('approval.index')
                ->with('error', 'Kamu tidak memiliki izin untuk memproses permintaan ini.');
        }

        $permintaan->processed_by = auth()->id();
        $permintaan->approved_at = now();
        $permintaan->rejected_at = null;
        $permintaan->save();

        return redirect()
            ->route('approval.index')
            ->with('success', 'Permintaan Lembur berhasil disetujui.');
    }

    public function rejectLembur($id)
    {
        $permintaan = PermintaanLembur::findOrFail($id);

        if ($permintaan->approved_at || $permintaan->rejected_at) {
            return redirect()
                ->route('approval.index')
                ->with('error', 'Permintaan Lembur sudah pernah diproses.');
        }

        $pemohon = $permintaan->karyawan?->user;

        if (!$pemohon || !ApprovalService::getApprovers($pemohon)->contains('id', auth()->id())) {
            return redirect()
                ->route('approval.index')
                ->with('error', 'Kamu tidak memiliki izin untuk memproses permintaan ini.');
        }

        $permintaan->processed_by = auth()->id();
        $permintaan->approved_at = null;
        $permintaan->rejected_at = now();
        $permintaan->save();

        return redirect()
            ->route('approval.index')
            ->with('success', 'Permintaan Lembur berhasil ditolak.');
    }

    public function approveResign($id)
    {
        $permintaan = PermintaanResign::findOrFail($id);

        if ($permintaan->approved_at || $permintaan->rejected_at) {
            return redirect()
                ->route('approval.index')
                ->with('error', 'Permintaan Resign sudah pernah diproses.');
        }

        $pemohon = $permintaan->karyawan?->user;

        if (!$pemohon || !ApprovalService::getApprovers($pemohon)->contains('id', auth()->id())) {
            return redirect()
                ->route('approval.index')
                ->with('error', 'Kamu tidak memiliki izin untuk memproses permintaan ini.');
        }

        $permintaan->processed_by = auth()->id();
        $permintaan->approved_at = now();
        $permintaan->rejected_at = null;
        $permintaan->save();

        return redirect()
            ->route('approval.index')
            ->with('success', 'Permintaan Resign berhasil disetujui.');
    }

    public function rejectResign($id)
    {
        $permintaan = PermintaanResign::findOrFail($id);

        if ($permintaan->approved_at || $permintaan->rejected_at) {
            return redirect()
                ->route('approval.index')
                ->with('error', 'Permintaan Resign sudah pernah diproses.');
        }

        $pemohon = $permintaan->karyawan?->user;

        if (!$pemohon || !ApprovalService::getApprovers($pemohon)->contains('id', auth()->id())) {
            return redirect()
                ->route('approval.index')
                ->with('error', 'Kamu tidak memiliki izin untuk memproses permintaan ini.');
        }

        $permintaan->processed_by = auth()->id();
        $permintaan->approved_at = null;
        $permintaan->rejected_at = now();
        $permintaan->save();

        return redirect()
            ->route('approval.index')
            ->with('success', 'Permintaan Resign berhasil ditolak.');
    }

    public function approveTukarShift($id)
    {
        $permintaan = PermintaanTukarShift::findOrFail($id);

        if ($permintaan->approved_at || $permintaan->rejected_at) {
            return redirect()
                ->route('approval.index')
                ->with('error', 'Permintaan Tukar Shift sudah pernah diproses.');
        }

        $pemohon = $permintaan->karyawan?->user;

        if (!$pemohon || !ApprovalService::getApprovers($pemohon)->contains('id', auth()->id())) {
            return redirect()
                ->route('approval.index')
                ->with('error', 'Kamu tidak memiliki izin untuk memproses permintaan ini.');
        }

        $permintaan->processed_by = auth()->id();
        $permintaan->approved_at = now();
        $permintaan->rejected_at = null;
        $permintaan->save();

        return redirect()
            ->route('approval.index')
            ->with('success', 'Permintaan Tukar Shift berhasil disetujui.');
    }

    public function rejectTukarShift($id)
    {
        $permintaan = PermintaanTukarShift::findOrFail($id);

        if ($permintaan->approved_at || $permintaan->rejected_at) {
            return redirect()
                ->route('approval.index')
                ->with('error', 'Permintaan Tukar Shift sudah pernah diproses.');
        }

        $pemohon = $permintaan->karyawan?->user;

        if (!$pemohon || !ApprovalService::getApprovers($pemohon)->contains('id', auth()->id())) {
            return redirect()
                ->route('approval.index')
                ->with('error', 'Kamu tidak memiliki izin untuk memproses permintaan ini.');
        }

        $permintaan->processed_by = auth()->id();
        $permintaan->approved_at = null;
        $permintaan->rejected_at = now();
        $permintaan->save();

        return redirect()
            ->route('approval.index')
            ->with('success', 'Permintaan Tukar Shift berhasil ditolak.');
    }
}
