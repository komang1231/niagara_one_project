<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SumberPelamarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sumberPelamar = [
            ['kode' => '', 'nama' => 'Website Perusahaan'],
            ['kode' => '', 'nama' => 'LinkedIn'],
            ['kode' => '', 'nama' => 'Jobstreet'],
            ['kode' => '', 'nama' => 'Instagram'],
            ['kode' => '', 'nama' => 'Referensi Karyawan'],
            ['kode' => '', 'nama' => 'Walk-in / Datang Langsung'],
            ['kode' => '', 'nama' => 'Job Fair'],
            ['kode' => '', 'nama' => 'Kampus / Kampus Recruitment'],
            ['kode' => '', 'nama' => 'Agency/Headhunter'],
        ];
        foreach ($sumberPelamar as $sumber) {
            \App\Models\SumberPelamar::create($sumber);
        }
    }
}
