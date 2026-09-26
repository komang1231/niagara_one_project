<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CutiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $Cuti = [
            ['kode' => '', 'nama' => 'Cuti Tahunan'],
            ['kode' => '', 'nama' => 'Cuti Sakit'],
            ['kode' => '', 'nama' => 'Cuti Melahirkan'],
            ['kode' => '', 'nama' => 'Cuti Dinas Luar'],
            ['kode' => '', 'nama' => 'Cuti Alasan Penting'],
            ['kode' => '', 'nama' => 'Work From Home'],
        ];

        foreach ($Cuti as $c) {
            \App\Models\Cuti::create($c);
        }
    }
}
