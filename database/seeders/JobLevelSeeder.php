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
            ['nama' => 'Intership'],
            ['nama' => 'Junior'],
            ['nama' => 'Intermediate'],
            ['nama' => 'Senior'],
            ['nama' => 'SPV'],
            ['nama' => 'Section Head'],
            ['nama' => 'Division Head'],
            ['nama' => 'Departement Head'],
            ['nama' => 'Branch Manager'],
            ['nama' => 'Country Manager'],
            ['nama' => 'C-Level'],
            ['nama' => 'Direktur'],
            ['nama' => 'Komisaris'],  
        ];

        foreach ($jobLevels as $jobLevel) {
            \App\Models\JobLevel::create($jobLevel);
        }
    }
}
