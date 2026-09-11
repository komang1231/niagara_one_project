<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JobLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jobLevels = [
            ['kode' => 'JABATAN_PIMPINAN_TINGGI', 'nama' => 'Jabatan Pimpinan Tinggi'],
            ['kode' => 'JABATAN_ADMINISTRATOR', 'nama' => 'Jabatan Administrator'],
            ['kode' => 'JABATAN_PENGAWAS', 'nama' => 'Jabatan Pengawas'],
            ['kode' => 'JABATAN_PELAKSANA', 'nama' => 'Jabatan Pelaksana'],
        ];

        foreach ($jobLevels as $jobLevel) {
            \App\Models\JobLevel::create($jobLevel);
        }
    }
}
