<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LowonganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permintaan = \App\Models\PermintaanKaryawan::first();
        $jobPosition = \App\Models\JobPosition::first();
        $jobLevel = \App\Models\JobLevel::first();
        $cabang = \App\Models\CabangKantor::first();

        \App\Models\Lowongan::firstOrCreate(
            ['kode' => 'LOW-001'],
            [
                'judul' => 'Lowongan Backend Developer',
                'permintaan_karyawan_id' => $permintaan->id,
                'cabang_kantor_id' => $cabang->id,
                'job_position_id' => $jobPosition->id,
                'job_level_id' => $jobLevel->id,
                'kuota' => 2,
                'kualifikasi' => 'Minimal S1 Teknik Informatika, menguasai PHP & Laravel',
                'deskripsi' => 'Membangun dan memelihara backend aplikasi HRD',
                'min_gaji' => 6000000,
                'max_gaji' => 9000000,
                'tanggal_buka' => now(),
                'tanggal_tutup' => now()->addDays(30),
            ]
        );
    }
}
