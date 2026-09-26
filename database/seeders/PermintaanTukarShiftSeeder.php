<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Karyawan as KaryawanModel;
use App\Models\Shift as ShiftModel;
use App\Models\PermintaanTukarShift as PermintaanTukarShiftModel;

class PermintaanTukarShiftSeeder extends Seeder
{
    public function run(): void
    {
        $karyawanMap = KaryawanModel::whereIn('nik', [
            '5171012345670001',
            '5171012345670002',
            '5171012345670003',
            '5171012345670004',
            '5171012345670005',
        ])->pluck('id', 'nik');

        $shiftMap = ShiftModel::whereIn('nama', [
            'Pagi',
            'Siang',
            'Sore',
            'Malam',
        ])->pluck('id', 'nama');

        $data = [
            [
                'kode' => '',
                'karyawan_pengaju' => $karyawanMap['5171012345670001'] ?? null,
                'karyawan_pengganti' => $karyawanMap['5171012345670002'] ?? null,
                'tanggal_tujuan' => '2026-10-03',
                'shift_pengaju' => $shiftMap['Pagi'] ?? null,
                'shift_pengganti' => $shiftMap['Siang'] ?? null,
            ],
            [
                'kode' => '',
                'karyawan_pengaju' => $karyawanMap['5171012345670002'] ?? null,
                'karyawan_pengganti' => $karyawanMap['5171012345670003'] ?? null,
                'tanggal_tujuan' => '2026-10-07',
                'shift_pengaju' => $shiftMap['Siang'] ?? null,
                'shift_pengganti' => $shiftMap['Malam'] ?? null,
            ],
            [
                'kode' => '',
                'karyawan_pengaju' => $karyawanMap['5171012345670003'] ?? null,
                'karyawan_pengganti' => $karyawanMap['5171012345670004'] ?? null,
                'tanggal_tujuan' => '2026-10-12',
                'shift_pengaju' => $shiftMap['Malam'] ?? null,
                'shift_pengganti' => $shiftMap['Pagi'] ?? null,
            ],
            [
                'kode' => '',
                'karyawan_pengaju' => $karyawanMap['5171012345670004'] ?? null,
                'karyawan_pengganti' => $karyawanMap['5171012345670005'] ?? null,
                'tanggal_tujuan' => '2026-10-18',
                'shift_pengaju' => $shiftMap['Sore'] ?? null,
                'shift_pengganti' => $shiftMap['Malam'] ?? null,
            ],
            [
                'kode' => '',
                'karyawan_pengaju' => $karyawanMap['5171012345670005'] ?? null,
                'karyawan_pengganti' => $karyawanMap['5171012345670001'] ?? null,
                'tanggal_tujuan' => '2026-10-24',
                'shift_pengaju' => $shiftMap['Siang'] ?? null,
                'shift_pengganti' => $shiftMap['Pagi'] ?? null,
            ],
        ];

        foreach ($data as $item) {
            PermintaanTukarShiftModel::create($item);
        }
    }
}