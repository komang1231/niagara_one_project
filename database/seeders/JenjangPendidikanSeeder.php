<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JenjangPendidikanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jenjangPendidikan = [
            ['kode' => 'SD', 'nama' => 'Sekolah Dasar'],
            ['kode' => 'SMP', 'nama' => 'Sekolah Menengah Pertama'],
            ['kode' => 'SMA', 'nama' => 'Sekolah Menengah Atas'],
            ['kode' => 'SMK', 'nama' => 'Sekolah Menengah Kejuruan'],
            ['kode' => 'D1', 'nama' => 'Diploma 1'],
            ['kode' => 'D2', 'nama' => 'Diploma 2'],
            ['kode' => 'D3', 'nama' => 'Diploma 3'],
            ['kode' => 'D4', 'nama' => 'Diploma 4'],
            ['kode' => 'S1', 'nama' => 'Sarjana (S1)'],
            ['kode' => 'S2', 'nama' => 'Magister (S2)'],
            ['kode' => 'S3', 'nama' => 'Doktor (S3)'],
        ];

        foreach ($jenjangPendidikan as $jenjang) {
            \App\Models\JenjangPendidikan::create($jenjang);
        }
    }
}
