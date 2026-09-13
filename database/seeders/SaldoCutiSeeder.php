<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SaldoCutiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $karyawan = \App\Models\Karyawan::first(); // cuma ada 1 dummy sekarang
        $cutiTahunan = \App\Models\Cuti::where('kode', 'CUTI_TAHUNAN')->first(); // sesuaikan kode aslimu

        \App\Models\SaldoCuti::firstOrCreate(
            [
                'cuti_id' => $cutiTahunan->id,
                'karyawan_id' => $karyawan->id,
                'tahun' => now()->year,
            ],
            [
                'saldo' => 12,
                'terpakai' => 0,
            ]
        );
    }
}
