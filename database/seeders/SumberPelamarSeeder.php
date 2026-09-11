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
            ['kode' => 'WEBSITE', 'nama' => 'Website Perusahaan'],
            ['kode' => 'LINKEDIN', 'nama' => 'LinkedIn'],
            ['kode' => 'JOBSTREET', 'nama' => 'Jobstreet'],
            ['kode' => 'INSTAGRAM', 'nama' => 'Instagram'],
            ['kode' => 'REFERRAL', 'nama' => 'Referensi Karyawan'],
            ['kode' => 'WALKIN', 'nama' => 'Walk-in / Datang Langsung'],
            ['kode' => 'JOBFAIR', 'nama' => 'Job Fair'],
            ['kode' => 'KAMPUS', 'nama' => 'Kampus / Kampus Recruitment'],
            ['kode' => 'AGENCY', 'nama' => 'Agency/Headhunter'],
            // ['kode' => 'LAINNYA', 'nama' => 'Lainnya'],
        ];
        foreach ($sumberPelamar as $sumber) {
            \App\Models\SumberPelamar::create($sumber);
        }
    }
}
