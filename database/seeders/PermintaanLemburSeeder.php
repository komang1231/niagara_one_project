<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Karyawan as KaryawanModel;
use App\Models\PermintaanLembur as PermintaanLemburModel;

class PermintaanLemburSeeder extends Seeder
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

        $data = [
            [
                'kode' => '',
                'karyawan_id' => $karyawanMap['5171012345670001'] ?? null,
                'tanggal_tujuan' => '2026-10-02',
                'jam_mulai' => '17:00',
                'jam_selesai' => '21:00',
                'alasan' => 'Penyelesaian pekerjaan yang harus diselesaikan pada hari yang sama.',
                'pengali' => 1.5,
            ],
            [
                'kode' => '',
                'karyawan_id' => $karyawanMap['5171012345670002'] ?? null,
                'tanggal_tujuan' => '2026-10-05',
                'jam_mulai' => '18:00',
                'jam_selesai' => '22:00',
                'alasan' => 'Maintenance dan deployment sistem.',
                'pengali' => 1.5,
            ],
            [
                'kode' => '',
                'karyawan_id' => $karyawanMap['5171012345670003'] ?? null,
                'tanggal_tujuan' => '2026-10-10',
                'jam_mulai' => '19:00',
                'jam_selesai' => '23:00',
                'alasan' => 'Penyelesaian laporan dan pekerjaan operasional.',
                'pengali' => 2,
            ],
            [
                'kode' => '',
                'karyawan_id' => $karyawanMap['5171012345670004'] ?? null,
                'tanggal_tujuan' => '2026-10-17',
                'jam_mulai' => '17:30',
                'jam_selesai' => '21:30',
                'alasan' => 'Persiapan kebutuhan operasional.',
                'pengali' => 1.5,
            ],
            [
                'kode' => '',
                'karyawan_id' => $karyawanMap['5171012345670005'] ?? null,
                'tanggal_tujuan' => '2026-10-24',
                'jam_mulai' => '18:00',
                'jam_selesai' => '22:00',
                'alasan' => 'Penyelesaian pekerjaan administrasi.',
                'pengali' => 1.5,
            ],
        ];

        foreach ($data as $item) {
            PermintaanLemburModel::firstOrCreate(
                [
                    'kode' => $item['kode'],
                ],
                $item
            );
        }
    }
}