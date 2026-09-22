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
            ['kode' => '', 'nama' => 'PKWT'],
            ['kode' => '', 'nama' => 'PKWTT'],
            ['kode' => '', 'nama' => 'Probation'],
            ['kode' => '', 'nama' => 'Magang'],
        ];

        foreach ($statusKepegawaian as $status) {
            \App\Models\StatusKepegawaian::create($status);
        }
    }
}
