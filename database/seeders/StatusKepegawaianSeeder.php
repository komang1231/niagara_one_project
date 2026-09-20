<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StatusKepegawaianSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statusKepegawaian = [
            ['nama' => 'PKWT (Kontrak)'],
            ['nama' => 'PKWTT (Tetap)'],
            ['nama' => 'Probation'],
            ['nama' => 'Magang'],
        ];

        foreach ($statusKepegawaian as $status) {
            \App\Models\StatusKepegawaian::create($status);
        }
    }
}
