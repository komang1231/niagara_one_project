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
            ['kode' => 'JBT_05', 'nama' => 'Intership'],
            ['kode' => 'JBT_06', 'nama' => 'Junior'],
            ['kode' => 'JBT_08', 'nama' => 'Intermediate'],
            ['kode' => 'JBT_04', 'nama' => 'Senior'],
            ['kode' => 'JBT_03', 'nama' => 'SPV'],
            ['kode' => 'JBT_02', 'nama' => 'Section Head'],
            ['kode' => 'JBT_01', 'nama' => 'Division Head'],
            ['kode' => 'JBT_07', 'nama' => 'Departement Head'],
            ['kode' => 'JBT_09', 'nama' => 'Branch Manager'],
            ['kode' => 'JBT_10', 'nama' => 'Country Manager'],
            ['kode' => 'JBT_11', 'nama' => 'C-Level'],
            ['kode' => 'JBT_12', 'nama' => 'Direktur'],
            ['kode' => 'JBT_13', 'nama' => 'Komisaris'],  
        ];

        foreach ($jobLevels as $jobLevel) {
            \App\Models\JobLevel::create($jobLevel);
        }
    }
}
