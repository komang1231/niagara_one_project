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
            ['kode' => 'JBT_01', 'nama' => 'Jabatan Pimpinan Tinggi'],
            ['kode' => 'JBT_02', 'nama' => 'Jabatan Administrator'],
            ['kode' => 'JBT_03', 'nama' => 'Jabatan Pengawas'],
            ['kode' => 'JBT_04', 'nama' => 'Jabatan Pelaksana'],
        ];

        foreach ($jobLevels as $jobLevel) {
            \App\Models\JobLevel::create($jobLevel);
        }
    }
}
