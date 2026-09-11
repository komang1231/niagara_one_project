<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ShiftSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $shifts = [
            ['kode' => 'SHIFT_PAGI', 'nama' => 'Pagi'],
            ['kode' => 'SHIFT_Siang', 'nama' => 'Siang'],
            ['kode' => 'SHIFT_SORE', 'nama' => 'Sore'],
            ['kode' => 'SHIFT_MALAM', 'nama' => 'Malam'],
        ];

        foreach ($shifts as $shift) {
            \App\Models\Shift::create($shift);
        }
    }
}
