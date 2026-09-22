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
            ['kode' => '', 'nama' => 'Intership'],
            ['kode' => '', 'nama' => 'Junior'],
            ['kode' => '', 'nama' => 'Intermediate'],
            ['kode' => '', 'nama' => 'Senior'],
            ['kode' => '', 'nama' => 'SPV'],
            ['kode' => '', 'nama' => 'Section Head'],
            ['kode' => '', 'nama' => 'Division Head'],
            ['kode' => '', 'nama' => 'Departement Head'],
            ['kode' => '', 'nama' => 'Branch Manager'],
            ['kode' => '', 'nama' => 'Country Manager'],
            ['kode' => '', 'nama' => 'C-Level'],
            ['kode' => '', 'nama' => 'Direktur'],
            ['kode' => '', 'nama' => 'Komisaris'],
        ];

        foreach ($jobLevels as $jobLevel) {
            \App\Models\JobLevel::create($jobLevel);
        }
    }
}
