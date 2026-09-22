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
            ['kode' => '', 'nama' => 'Sekolah Dasar / SD'],
            ['kode' => '', 'nama' => 'Sekolah Menengah Pertama / SMP '],
            ['kode' => '', 'nama' => 'Sekolah Menengah Atas / SMA'],
            ['kode' => '', 'nama' => 'Sekolah Menengah Kejuruan / SMK'],
            ['kode' => '', 'nama' => 'Diploma 1 / D1'],
            ['kode' => '', 'nama' => 'Diploma 2 / D2'],
            ['kode' => '', 'nama' => 'Diploma 3 / D3'],
            ['kode' => '', 'nama' => 'Diploma 4 / D4'],
            ['kode' => '', 'nama' => 'Sarjana / S1'],
            ['kode' => '', 'nama' => 'Magister / S2'],
            ['kode' => '', 'nama' => 'Doktor / S3'],
        ];

        foreach ($jenjangPendidikan as $jenjang) {
            \App\Models\JenjangPendidikan::create($jenjang);
        }
    }
}
