<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermintaanKaryawanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $karyawan = \App\Models\Karyawan::where('nip', 'NIP-0001')->first();
        $jobPosition = \App\Models\JobPosition::where('kode', 'JP-BE-01')->first();
        $jobLevel = \App\Models\JobLevel::where('kode', 'JBT_04')->first();

        \App\Models\PermintaanKaryawan::firstOrCreate(
            ['kode' => 'PK-001'],
            [
                'karyawan_id' => $karyawan->id,
                'job_position_id' => $jobPosition->id ?? null,
                'job_level_id' => $jobLevel->id ?? null,
                'jumlah' => 2,
            ]
        );
    }
}
