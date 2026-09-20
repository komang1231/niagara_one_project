<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Section as SectionModel;
use App\Models\Divisi as DivisiModel;
use App\Models\Departemen as DepartemenModel;
use App\Models\JobPosition as JobPositionModel;

class KaryawanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run(): void
    {
        $cabangKantorMap = \App\Models\CabangKantor::whereIn(
            'kode',
            ['CB-001', 'CB-002', 'CB-003']
        )->pluck('id', 'kode');

        $departemenMap = DepartemenModel::whereIn('kode', ['DEP-2600010920', 'DEP-2600020920', 'DEP-2600030920', 'DEP-2600040920', 'DEP-2600050920'])
            ->pluck('id', 'kode');
        $divisiMap = DivisiModel::whereIn('kode', ['DIV-2600010920', 'DIV-2600020920', 'DIV-2600030920', 'DIV-2600040920', 'DIV-2600050920', 'DIV-2600010921', 'DIV-2600010922'])
            ->pluck('id', 'kode');
        $sectionMap = SectionModel::whereIn('kode', ['SEC-2600010920', 'SEC-2600020920', 'SEC-2600030920', 'SEC-2600040920', 'SEC-2600050920', 'SEC-2600060920', 'SEC-2600070920', 'SEC-2600080920'])
            ->pluck('id', 'kode');
        $jobPositionMap = JobPositionModel::whereIn('kode', ['JOBP-2600010920', 'JOBP-2600020920', 'JOBP-2600030920', 'JOBP-2600040920', 'JOBP-2600050920', 'JOBP-2600060920', 'JOBP-2600070920'])
            ->pluck('id', 'kode');
        $statusKepegawaian = \App\Models\StatusKepegawaian::where('kode', 'SKP-2600020920')->first();
        $jobLevel = \App\Models\JobLevel::first();

        $jenjangPendidikan = \App\Models\JenjangPendidikan::where('kode', 'S1')->first();
        $statusKawin = \App\Models\StatusKawin::first();
        $agama = \App\Models\Agama::first();
        $bank = \App\Models\Bank::first();

        $karyawans = [
            [
                'nip' => 'NIP-2600010920',
                'nik' => '5171012345670001',
                'lowongan_id' => null,
                'rekrutmen_id' => null,
                'departemen_id' => $departemenMap['DEP-2600010920'] ?? null,
                'divisi_id' => $divisiMap['DIV-2600010920'] ?? null,
                'section_id' => $sectionMap['SEC-2600010920'] ?? null,
                'job_position_id' => $jobPositionMap['JOBP-2600010920'] ?? null,
                'job_level_id' => $jobLevel->id ?? null,
                'cabang_kantor_id' => $cabangKantorMap['CB-001'] ?? null,
                'gaji' => 8000000,
                'nama' => 'Budi Santoso',
                'email' => 'budi.santoso@niagaraone.com',
                'no_tlp' => '081234567890',
                'no_bpjs_ketenagakerjaan' => '12345678901',
                'no_bpjs_kesehatan' => '1234567890123',
                'no_npwp' => '1234567890123456',
                'jenjang_pendidikan_id' => $jenjangPendidikan->id ?? null,
                'status_kawin_id' => $statusKawin->id ?? null,
                'agama_id' => $agama->id ?? null,
                'status_kepegawaian_id' => $statusKepegawaian->id ?? null,
                'bank_id' => $bank->id ?? null,
                'nama_bank' => 'BCA',
                'no_rekening' => '1234567890',
            ],
            [
                'nip' => 'NIP-2600020920',
                'nik' => '5171012345670002',
                'lowongan_id' => null,
                'rekrutmen_id' => null,
                'departemen_id' => $departemenMap['DEP-2600020920'] ?? null,
                'divisi_id' => $divisiMap['DIV-2600020920'] ?? null,
                'section_id' => $sectionMap['SEC-2600020920'] ?? null,
                'job_position_id' => $jobPositionMap['JOBP-2600020920'] ?? null,
                'job_level_id' => $jobLevel->id ?? null,
                'cabang_kantor_id' => $cabangKantorMap['CB-002'] ?? null,
                'gaji' => 7500000,
                'nama' => 'Siti Rahmawati',
                'email' => 'siti.rahmawati@niagaraone.com',
                'no_tlp' => '081234567891',
                'no_bpjs_ketenagakerjaan' => '12345678902',
                'no_bpjs_kesehatan' => '1234567890124',
                'no_npwp' => '1234567890123457',
                'jenjang_pendidikan_id' => $jenjangPendidikan->id ?? null,
                'status_kawin_id' => $statusKawin->id ?? null,
                'agama_id' => $agama->id ?? null,
                'status_kepegawaian_id' => $statusKepegawaian->id ?? null,
                'bank_id' => $bank->id ?? null,
                'nama_bank' => 'BRI',
                'no_rekening' => '1234567891',
            ],
            [
                'nip' => 'NIP-2600030920',
                'nik' => '5171012345670003',
                'lowongan_id' => null,
                'rekrutmen_id' => null,
                'departemen_id' => $departemenMap['DEP-2600030920'] ?? null,
                'divisi_id' => $divisiMap['DIV-2600030920'] ?? null,
                'section_id' => $sectionMap['SEC-2600030920'] ?? null,
                'job_position_id' => $jobPositionMap['JOBP-2600030920'] ?? null,
                'job_level_id' => $jobLevel->id ?? null,
                'cabang_kantor_id' => $cabangKantorMap['CB-003'] ?? null,
                'gaji' => 9000000,
                'nama' => 'Andi Wijaya',
                'email' => 'andi.wijaya@niagaraone.com',
                'no_tlp' => '081234567892',
                'no_bpjs_ketenagakerjaan' => '12345678903',
                'no_bpjs_kesehatan' => '1234567890125',
                'no_npwp' => '1234567890123458',
                'jenjang_pendidikan_id' => $jenjangPendidikan->id ?? null,
                'status_kawin_id' => $statusKawin->id ?? null,
                'agama_id' => $agama->id ?? null,
                'status_kepegawaian_id' => $statusKepegawaian->id ?? null,
                'bank_id' => $bank->id ?? null,
                'nama_bank' => 'Mandiri',
                'no_rekening' => '1234567892',
            ],
            [
                'nip' => 'NIP-2600040920',
                'nik' => '5171012345670004',
                'lowongan_id' => null,
                'rekrutmen_id' => null,
                'departemen_id' => $departemenMap['DEP-2600040920'] ?? null,
                'divisi_id' => $divisiMap['DIV-2600040920'] ?? null,
                'section_id' => $sectionMap['SEC-2600040920'] ?? null,
                'job_position_id' => $jobPositionMap['JOBP-2600040920'] ?? null,
                'job_level_id' => $jobLevel->id ?? null,
                'cabang_kantor_id' => $cabangKantorMap['CB-001'] ?? null,
                'gaji' => 7000000,
                'nama' => 'Dewi Lestari',
                'email' => 'dewi.lestari@niagaraone.com',
                'no_tlp' => '081234567893',
                'no_bpjs_ketenagakerjaan' => '12345678904',
                'no_bpjs_kesehatan' => '1234567890126',
                'no_npwp' => '1234567890123459',
                'jenjang_pendidikan_id' => $jenjangPendidikan->id ?? null,
                'status_kawin_id' => $statusKawin->id ?? null,
                'agama_id' => $agama->id ?? null,
                'status_kepegawaian_id' => $statusKepegawaian->id ?? null,
                'bank_id' => $bank->id ?? null,
                'nama_bank' => 'BNI',
                'no_rekening' => '1234567893',
            ],
            [
                'nip' => 'NIP-2600050920',
                'nik' => '5171012345670005',
                'lowongan_id' => null,
                'rekrutmen_id' => null,
                'departemen_id' => $departemenMap['DEP-2600050920'] ?? null,
                'divisi_id' => $divisiMap['DIV-2600050920'] ?? null,
                'section_id' => $sectionMap['SEC-2600050920'] ?? null,
                'job_position_id' => $jobPositionMap['JOBP-2600050920'] ?? null,
                'job_level_id' => $jobLevel->id ?? null,
                'cabang_kantor_id' => $cabangKantorMap['CB-002'] ?? null,
                'gaji' => 8500000,
                'nama' => 'Rizky Pratama',
                'email' => 'rizky.pratama@niagaraone.com',
                'no_tlp' => '081234567894',
                'no_bpjs_ketenagakerjaan' => '12345678905',
                'no_bpjs_kesehatan' => '1234567890127',
                'no_npwp' => '1234567890123460',
                'jenjang_pendidikan_id' => $jenjangPendidikan->id ?? null,
                'status_kawin_id' => $statusKawin->id ?? null,
                'agama_id' => $agama->id ?? null,
                'status_kepegawaian_id' => $statusKepegawaian->id ?? null,
                'bank_id' => $bank->id ?? null,
                'nama_bank' => 'CIMB Niaga',
                'no_rekening' => '1234567894',
            ],
        ];

        foreach ($karyawans as $karyawan) {
            \App\Models\Karyawan::firstOrCreate(
                ['nik' => $karyawan['nik']],
                $karyawan
            );
        }
    }
}
