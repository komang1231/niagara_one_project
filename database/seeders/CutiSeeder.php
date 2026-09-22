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
            ['kode' => '', 'nama' => 'Cuti Tahunan', 'kuota_hari_default' => 12],
            ['kode' => '', 'nama' => 'Cuti Sakit', 'kuota_hari_default' => 0],
            ['kode' => '', 'nama' => 'Cuti Melahirkan', 'kuota_hari_default' => 0],
            ['kode' => '', 'nama' => 'Cuti Dinas Luar', 'kuota_hari_default' => 0],
            ['kode' => '', 'nama' => 'Cuti Alasan Penting', 'kuota_hari_default' => 0],
            ['kode' => '', 'nama' => 'Work From Home', 'kuota_hari_default' => 0],
        ];

        foreach ($Cuti as $c) {
            \App\Models\Cuti::create($c);
        }
    }
}
