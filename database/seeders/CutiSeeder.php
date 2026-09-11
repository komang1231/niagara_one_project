<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CutiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $Cuti = [
            ['kode' => 'CUTI_TAHUNAN', 'nama' => 'Cuti Tahunan', 'kuota_hari_default' => 12],
            ['kode' => 'CUTI_SAKIT', 'nama' => 'Cuti Sakit', 'kuota_hari_default' => 3],
            ['kode' => 'CUTI_MELAHIRKAN', 'nama' => 'Cuti Melahirkan', 'kuota_hari_default' => 60],
            ['kode' => 'CUTI_DINAS_LUAR', 'nama' => 'Cuti Dinas Luar', 'kuota_hari_default' => 5],
            ['kode' => 'CUTI_TIDAK_DIBAYAR', 'nama' => 'Cuti Alasan Penting', 'kuota_hari_default' => 5],
            ['kode' => 'WFH', 'nama' => 'Work From Home', 'kuota_hari_default' => 0],
            // ['kode' => 'CUTI_BERSALIN', 'nama' => 'Cuti Bersalin', 'kuota_hari_default' => 90],
            // ['kode' => 'CUTI_PENYAKIT_KRONIS', 'nama' => 'Cuti Penyakit Kronis', 'kuota_hari_default' => 30],
            // ['kode' => 'CUTI_PENDIDIKAN', 'nama' => 'Cuti Pendidikan', 'kuota_hari_default' => 60],
            // ['kode' => 'CUTI_KHUSUS', 'nama' => 'Cuti Khusus', 'kuota_hari_default' => 10],
        ];

        foreach ($Cuti as $c) {
            \App\Models\Cuti::create($c);
        }
    }
}
