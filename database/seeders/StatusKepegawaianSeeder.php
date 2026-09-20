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
            ['kode' => 'SKP-2600010920', 'nama' => 'PKWT (Kontrak)'],
            ['kode' => 'SKP-2600020920', 'nama' => 'PKWTT (Tetap)'],
            ['kode' => 'SKP-2600030920', 'nama' => 'Probation'],
            ['kode' => 'SKP-2600040920', 'nama' => 'Magang'],
        ];

        foreach ($statusKepegawaian as $status) {
            \App\Models\StatusKepegawaian::create($status);
        }
    }
}
