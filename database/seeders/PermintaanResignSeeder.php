<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Karyawan as KaryawanModel;
use App\Models\PermintaanResign as PermintaanResignModel;

class PermintaanResignSeeder extends Seeder
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

        $data = [
            [
                'kode' => '',
                'karyawan_id' => $karyawanMap['5171012345670001'] ?? null,
                'tanggal_efektif' => '2026-11-01',
                'alasan' => 'Melanjutkan karier di perusahaan lain.',
            ],
            [
                'kode' => '',
                'karyawan_id' => $karyawanMap['5171012345670002'] ?? null,
                'tanggal_efektif' => '2026-11-05',
                'alasan' => 'Keperluan keluarga.',
            ],
            [
                'kode' => '',
                'karyawan_id' => $karyawanMap['5171012345670003'] ?? null,
                'tanggal_efektif' => '2026-11-10',
                'alasan' => 'Melanjutkan pendidikan.',
            ],
            [
                'kode' => '',
                'karyawan_id' => $karyawanMap['5171012345670004'] ?? null,
                'tanggal_efektif' => '2026-11-15',
                'alasan' => 'Relokasi tempat tinggal.',
            ],
            [
                'kode' => '',
                'karyawan_id' => $karyawanMap['5171012345670005'] ?? null,
                'tanggal_efektif' => '2026-11-20',
                'alasan' => 'Mendapatkan kesempatan pekerjaan baru.',
            ],
        ];

        foreach ($data as $item) {
            PermintaanResignModel::create($item);
        }
    }
}