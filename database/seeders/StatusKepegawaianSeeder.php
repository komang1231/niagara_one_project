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
            ['kode' => 'PKWT', 'nama' => 'PKWT (Kontrak)'],
            ['kode' => 'PKWTT', 'nama' => 'PKWTT (Tetap)'],
            ['kode' => 'PROBATION', 'nama' => 'Probation'],
            ['kode' => 'MAGANG', 'nama' => 'Magang'],
        ];

        foreach ($statusKepegawaian as $status) {
            \App\Models\StatusKepegawaian::create($status);
        }
    }
}
