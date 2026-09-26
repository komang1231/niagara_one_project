<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cuti as CutiModel;
use App\Models\Karyawan as KaryawanModel;
use App\Models\PermintaanCuti as PermintaanCutiModel;

class PermintaanCutiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $karyawanMap = KaryawanModel::whereIn('nik', [
            '5171012345670001',
            '5171012345670002',
            '5171012345670003',
            '5171012345670004',
            '5171012345670005',
        ])->pluck('id', 'nik');

        $cutiMap = CutiModel::whereIn('nama', [
            'Cuti Tahunan',
            'Cuti Sakit',
            'Cuti Melahirkan',
            'Cuti Dinas Luar',
            'Cuti Alasan Penting',
        ])->pluck('id', 'nama');

        $data = [
            [
                'kode' => '',
                'karyawan_id' => $karyawanMap['5171012345670001'] ?? null,
                'cuti_id' => $cutiMap['Cuti Tahunan'] ?? null,
                'tanggal_mulai' => '2026-10-01',
                'tanggal_selesai' => '2026-10-03',
                'alasan' => 'Keperluan keluarga.',
                'pengganti_karyawan_id' => $karyawanMap['5171012345670002'] ?? null,
                'detail' => [
                    ['tanggal' => '2026-10-01', 'setengah_hari' => false],
                    ['tanggal' => '2026-10-02', 'setengah_hari' => false],
                    ['tanggal' => '2026-10-03', 'setengah_hari' => false],
                ],
            ],
            [
                'kode' => '',
                'karyawan_id' => $karyawanMap['5171012345670002'] ?? null,
                'cuti_id' => $cutiMap['Cuti Sakit'] ?? null,
                'tanggal_mulai' => '2026-10-05',
                'tanggal_selesai' => '2026-10-06',
                'alasan' => 'Pemulihan kondisi kesehatan.',
                'pengganti_karyawan_id' => null,
                'detail' => [
                    ['tanggal' => '2026-10-05', 'setengah_hari' => false],
                    ['tanggal' => '2026-10-06', 'setengah_hari' => false],
                ],
            ],
            [
                'kode' => '',
                'karyawan_id' => $karyawanMap['5171012345670003'] ?? null,
                'cuti_id' => $cutiMap['Cuti Melahirkan'] ?? null,
                'tanggal_mulai' => '2026-10-12',
                'tanggal_selesai' => '2026-10-16',
                'alasan' => 'Keperluan persalinan.',
                'pengganti_karyawan_id' => $karyawanMap['5171012345670004'] ?? null,
                'detail' => [
                    ['tanggal' => '2026-10-12', 'setengah_hari' => false],
                    ['tanggal' => '2026-10-13', 'setengah_hari' => false],
                    ['tanggal' => '2026-10-14', 'setengah_hari' => false],
                    ['tanggal' => '2026-10-15', 'setengah_hari' => false],
                    ['tanggal' => '2026-10-16', 'setengah_hari' => false],
                ],
            ],
            [
                'kode' => '',
                'karyawan_id' => $karyawanMap['5171012345670004'] ?? null,
                'cuti_id' => $cutiMap['Cuti Dinas Luar'] ?? null,
                'tanggal_mulai' => '2026-10-20',
                'tanggal_selesai' => '2026-10-21',
                'alasan' => 'Pelaksanaan tugas dinas luar.',
                'pengganti_karyawan_id' => null,
                'detail' => [
                    ['tanggal' => '2026-10-20', 'setengah_hari' => false],
                    ['tanggal' => '2026-10-21', 'setengah_hari' => false],
                ],
            ],
            [
                'kode' => '',
                'karyawan_id' => $karyawanMap['5171012345670005'] ?? null,
                'cuti_id' => $cutiMap['Cuti Alasan Penting'] ?? null,
                'tanggal_mulai' => '2026-10-26',
                'tanggal_selesai' => '2026-10-27',
                'alasan' => 'Keperluan penting keluarga.',
                'pengganti_karyawan_id' => null,
                'detail' => [
                    ['tanggal' => '2026-10-26', 'setengah_hari' => false],
                    ['tanggal' => '2026-10-27', 'setengah_hari' => false],
                ],
            ],
        ];

        foreach ($data as $item) {
            $details = $item['detail'];
            unset($item['detail']);

            $permintaanCuti = PermintaanCutiModel::firstOrCreate(
                [
                    'kode' => $item['kode'],
                ],
                $item
            );

            foreach ($details as $detail) {
                $permintaanCuti->details()->firstOrCreate(
                    [
                        'tanggal' => $detail['tanggal'],
                    ],
                    [
                        'setengah_hari' => $detail['setengah_hari'],
                    ]
                );
            }
        }
    }
}