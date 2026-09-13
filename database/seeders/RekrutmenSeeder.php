<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RekrutmenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $lowongan = \App\Models\Lowongan::where('kode', 'LOW-001')->first();
        $jenjangPendidikan = \App\Models\JenjangPendidikan::where('kode', 'S1')->first();
        $sumberPelamar = \App\Models\SumberPelamar::first();

        \App\Models\Rekrutmen::firstOrCreate(
            ['kode' => 'REK-001'],
            [
                'lowongan_id' => $lowongan->id,
                'job_level_id' => $lowongan->job_level_id,
                'job_position_id' => $lowongan->job_position_id,
                'cabang_kantor_id' => $lowongan->cabang_kantor_id,
                'nama' => 'Andi Wijaya',
                'email' => 'andi.wijaya@gmail.com',
                'no_tlp' => '081298765432',
                'file_cv' => 'dummy-cv-andi.pdf',
                'jenjang_pendidikan_id' => $jenjangPendidikan->id ?? null,
                'sumber_pelamar_id' => $sumberPelamar->id ?? null,
                'status_rekrutmen' => 'pelamar',
            ]
        );
    }
}
