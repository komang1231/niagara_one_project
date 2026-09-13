<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KaryawanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cabangKantorMap = \App\Models\CabangKantor::whereIn('kode', ['CB-001', 'CB-002', 'CB-003'])->pluck('id', 'kode'); // sesuaikan kode cabang kantormu
        $jobPosition = \App\Models\JobPosition::first();
        $statusKepegawaian = \App\Models\StatusKepegawaian::where('kode', 'PKWTT')->first();
        $jobLevel = \App\Models\JobLevel::first();

        \App\Models\Karyawan::firstOrCreate(
            ['nik' => '5171012345670001'],
            [
                'nip' => 'NIP-0001',
                'lowongan_id' => null,
                'rekrutmen_id' => null,
                'job_position_id' => $jobPosition->id ?? null,
                'job_level_id' => $jobLevel->id ?? null,
                'cabang_kantor_id' => $cabangKantorMap['CB-001'] ?? null,
                'gaji' => 8000000,
                'nama' => 'Budi Santoso',
                'email' => 'budi.santoso@niagaraone.com',
                'no_tlp' => '081234567890',
                'no_bpjs_ketenagakerjaan' => '12345678901',
                'no_bpjs_kesehatan' => '1234567890123',
                'no_npwp' => '1234567890123456',
                'jenjang_pendidikan_id' => \App\Models\JenjangPendidikan::where('kode', 'S1')->first()->id ?? null,
                'status_kawin_id' => \App\Models\StatusKawin::first()->id ?? null,
                'agama_id' => \App\Models\Agama::first()->id ?? null,
                'status_kepegawaian_id' => $statusKepegawaian->id ?? null,
                'bank_id' => \App\Models\Bank::first()->id ?? null,
                'nama_bank' => 'BCA',
                'no_rekening' => '1234567890',
                'status' => 'aktif',
            ]
        );
    }
}
