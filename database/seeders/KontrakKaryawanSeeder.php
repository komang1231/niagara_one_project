<?php

namespace Database\Seeders;

use App\Models\Karyawan;
use App\Models\StatusKepegawaian;
use App\Models\KontrakKaryawan;
use Illuminate\Database\Seeder;

class KontrakKaryawanSeeder extends Seeder
{
    public function run(): void
    {
        $karyawanMap = Karyawan::whereIn('nik', [
            '5171012345670001',
            '5171012345670002',
            '5171012345670003',
            '5171012345670004',
            '5171012345670005',
        ])->pluck('id', 'nik');

        $statusKepegawaianMap = StatusKepegawaian::whereIn('nama', [
            'PKWT',
            'PKWTT',
            'Probation',
            'Magang',
        ])->pluck('id', 'nama');

        $data = [
            [
                'nik' => '5171012345670001',
                'status' => 'PKWTT',
                'tanggal_mulai' => '2024-01-01',
                'tanggal_berakhir' => '2025-01-01',
            ],
            [
                'nik' => '5171012345670002',
                'status' => 'PKWT',
                'tanggal_mulai' => '2025-01-01',
                'tanggal_berakhir' => '2026-01-01',
            ],
            [
                'nik' => '5171012345670003',
                'status' => 'PKWT',
                'tanggal_mulai' => '2025-10-15',
                'tanggal_berakhir' => '2026-10-15',
            ],
            [
                'nik' => '5171012345670004',
                'status' => 'Probation',
                'tanggal_mulai' => '2026-01-01',
                'tanggal_berakhir' => '2027-01-01',
            ],
            [
                'nik' => '5171012345670005',
                'status' => 'Magang',
                'tanggal_mulai' => '2026-10-01',
                'tanggal_berakhir' => '2027-10-01',
            ],
        ];

        foreach ($data as $item) {
            $karyawanId = $karyawanMap[$item['nik']] ?? null;
            $statusKepegawaianId = $statusKepegawaianMap[$item['status']] ?? null;

            if (!$karyawanId || !$statusKepegawaianId) {
                continue;
            }

            KontrakKaryawan::updateOrCreate(
                [
                    'karyawan_id' => $karyawanId,
                    'tanggal_mulai' => $item['tanggal_mulai'],
                ],
                [
                    'status_kepegawaian_id' => $statusKepegawaianId,
                    'tanggal_berakhir' => $item['tanggal_berakhir'],
                    'status' => 'aktif',
                ]
            );
        }
    }
}