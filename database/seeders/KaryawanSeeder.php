<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CabangKantor as CabangKantorModel;
use App\Models\Section as SectionModel;
use App\Models\Divisi as DivisiModel;
use App\Models\Departemen as DepartemenModel;
use App\Models\JobPosition as JobPositionModel;
use App\Models\JobLevel as JobLevelModel;
use App\Models\StatusKepegawaian as StatusKepegawaianModel;
use App\Models\JenjangPendidikan as JenjangPendidikanModel;
use App\Models\StatusKawin as StatusKawinModel;
use App\Models\Agama as AgamaModel;
use App\Models\Bank as BankModel;

class KaryawanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run(): void
    {
        $cabangKantorMap = CabangKantorModel::whereIn('nama', [
            'Kantor Pusat Denpasar',
            'Cabang Kuta',
            'Cabang Jakarta',
            'Cabang Surabaya',
            'Cabang Bandung',
            'Cabang Yogyakarta',
            'Cabang Medan',
        ])->pluck('id', 'nama');

        $departemenMap = DepartemenModel::whereIn('nama', [
            'Human Resources Department',
            'Information Technology',
            'Finance',
            'Marketing',
            'Operations',
        ])->pluck('id', 'nama');

        $divisiMap = DivisiModel::whereIn('nama', [
            'Recruitment',
            'Web Development',
            'Infrastructure',
            'Accounting',
            'Digital Marketing',
            'Hotel Operations',
            'Warehouse',
        ])->pluck('id', 'nama');

        $sectionMap = SectionModel::whereIn('nama', [
            'Backend',
            'Frontend',
            'Quality Assurance',
            'Talent Acquisition',
            'SEO',
            'Social Media',
            'Front Office',
            'Housekeeping',
        ])->pluck('id', 'nama');

        $jobPositionMap = JobPositionModel::whereIn('nama', [
            'Backend Developer',
            'Frontend Developer',
            'QA Engineer',
            'Recruitment Specialist',
            'SEO Specialist',
            'Front Office Staff',
            'Housekeeping Staff',
        ])->pluck('id', 'nama');

        $statusKepegawaianMap = StatusKepegawaianModel::whereIn('nama', [
            'PKWT',
            'PKWTT',
            'Probation',
            'Magang',
        ])->pluck('id', 'nama');

        $jobLevelMap = JobLevelModel::whereIn('nama', [
            'Intership',
            'Junior',
            'Intermediate',
            'Senior',
            'SPV',
            'Section Head',
            'Division Head',
            'Departement Head',
            'Branch Manager',
            'Country Manager',
            'C-Level',
            'Direktur',
            'Komisaris',
        ])->pluck('id', 'nama');

        $jenjangPendidikanMap = JenjangPendidikanModel::whereIn('nama', [
            'Sekolah Dasar / SD',
            'Sekolah Menengah Pertama / SMP',
            'Sekolah Menengah Atas / SMA',
            'Sekolah Menengah Kejuruan / SMK',
            'Diploma 1 / D1',
            'Diploma 2 / D2',
            'Diploma 3 / D3',
            'Diploma 4 / D4',
            'Sarjana / S1',
            'Magister / S2',
            'Doktor / S3',
        ])->pluck('id', 'nama');

        $statusKawinMap = StatusKawinModel::whereIn('nama', [
            'Belum Kawin',
            'Kawin Belum Tercatat',
            'Kawin Tercatat',
            'Cerai Hidup',
            'Cerai Mati',
        ])->pluck('id', 'nama');

        $agamaMap = AgamaModel::whereIn('nama', [
            'Islam',
            'Kristen',
            'Katolik',
            'Hindu',
            'Buddha',
            'Konghucu',
        ])->pluck('id', 'nama');

        $bankMap = BankModel::whereIn('nama', [
            'Bank Rakyat Indonesia (BRI)',
            'Bank Mandiri',
            'Bank Negara Indonesia (BNI)',
            'Bank Central Asia (BCA)',
            'Bank Danamon',
            'Bank Permata',
            'CIMB Niaga',
            'Bank Tabungan Negara (BTN)',
            'Bank Syariah Indonesia (BSI)',
            'Bank BJB',
            'Bank DKI',
            'BPD DIY',
            'Bank Papua',
            'Bank Jago',
            'Blu by BCA Digital',
            'SeaBank',
        ])->pluck('id', 'nama');

        $karyawans = [
            [
                'nip' => '',
                'nik' => '5171012345670001',
                'lowongan_id' => null,
                'rekrutmen_id' => null,
                'departemen_id' => $departemenMap['Human Resources Department'] ?? null,
                'divisi_id' => $divisiMap['Recruitment'] ?? null,
                'section_id' => $sectionMap['Talent Acquisition'] ?? null,
                'job_position_id' => $jobPositionMap['Backend Developer'] ?? null,
                'job_level_id' => $jobLevelMap['Junior'] ?? null,
                'cabang_kantor_id' => $cabangKantorMap['Kantor Pusat Denpasar'] ?? null,
                'gaji' => 8000000,
                'nama' => 'Budi Santoso',
                'email' => 'budi.santoso@niagaraone.com',
                'no_tlp' => '081234567890',
                'no_bpjs_ketenagakerjaan' => '12345678901',
                'no_bpjs_kesehatan' => '1234567890123',
                'no_npwp' => '1234567890123456',
                'jenjang_pendidikan_id' => $jenjangPendidikanMap->get('Sarjana / S1') ?? null,
                'status_kawin_id' => $statusKawinMap->get('Kawin Tercatat') ?? null,
                'agama_id' => $agamaMap->get('Islam') ?? null,
                'status_kepegawaian_id' => $statusKepegawaianMap['PKWT'] ?? null,
                'bank_id' => $bankMap->get('Bank Central Asia (BCA)') ?? null,
                'nama_bank' => 'BCA',
                'no_rekening' => '1234567890',
            ],
            [
                'nip' => '',
                'nik' => '5171012345670002',
                'lowongan_id' => null,
                'rekrutmen_id' => null,
                'departemen_id' => $departemenMap['Information Technology'] ?? null,
                'divisi_id' => $divisiMap['Web Development'] ?? null,
                'section_id' => $sectionMap['Frontend'] ?? null,
                'job_position_id' => $jobPositionMap['Frontend Developer'] ?? null,
                'job_level_id' => $jobLevelMap['Intership'] ?? null,
                'cabang_kantor_id' => $cabangKantorMap['Cabang Kuta'] ?? null,
                'gaji' => 7500000,
                'nama' => 'Siti Rahmawati',
                'email' => 'siti.rahmawati@niagaraone.com',
                'no_tlp' => '081234567891',
                'no_bpjs_ketenagakerjaan' => '12345678902',
                'no_bpjs_kesehatan' => '1234567890124',
                'no_npwp' => '1234567890123457',
                'jenjang_pendidikan_id' => $jenjangPendidikanMap['Sarjana / S1'] ?? null,
                'status_kawin_id' => $statusKawinMap['Kawin Tercatat'] ?? null,
                'agama_id' => $agamaMap['Islam'] ?? null,
                'status_kepegawaian_id' => $statusKepegawaianMap['PKWTT'] ?? null,
                'bank_id' => $bankMap['Bank Rakyat Indonesia (BRI)'] ?? null,
                'nama_bank' => 'BRI',
                'no_rekening' => '1234567891',
            ],
            [
                'nip' => '',
                'nik' => '5171012345670003',
                'lowongan_id' => null,
                'rekrutmen_id' => null,
                'departemen_id' => $departemenMap['Information Technology'] ?? null,
                'divisi_id' => $divisiMap['Infrastructure'] ?? null,
                'section_id' => $sectionMap['DevOps'] ?? null,
                'job_position_id' => $jobPositionMap['QA Engineer'] ?? null,
                'job_level_id' => $jobLevelMap['Intermediate'] ?? null,
                'cabang_kantor_id' => $cabangKantorMap['Cabang Yogyakarta'] ?? null,
                'gaji' => 9000000,
                'nama' => 'Andi Wijaya',
                'email' => 'andi.wijaya@niagaraone.com',
                'no_tlp' => '081234567892',
                'no_bpjs_ketenagakerjaan' => '12345678903',
                'no_bpjs_kesehatan' => '1234567890125',
                'no_npwp' => '1234567890123458',
                'jenjang_pendidikan_id' => $jenjangPendidikanMap['Sarjana / S1'] ?? null,
                'status_kawin_id' => $statusKawinMap['Kawin Tercatat'] ?? null,
                'agama_id' => $agamaMap['Islam'] ?? null,
                'status_kepegawaian_id' => $statusKepegawaianMap['Probation'] ?? null,
                'bank_id' => $bankMap['Bank Mandiri'] ?? null,
                'nama_bank' => 'Mandiri',
                'no_rekening' => '1234567892',
            ],
            [
                'nip' => '',
                'nik' => '5171012345670004',
                'lowongan_id' => null,
                'rekrutmen_id' => null,
                'departemen_id' => $departemenMap['Information Technology'] ?? null,
                'divisi_id' => $divisiMap['Web Development'] ?? null,
                'section_id' => $sectionMap['Backend'] ?? null,
                'job_position_id' => $jobPositionMap['Backend Developer'] ?? null,
                'job_level_id' => $jobLevelMap['Senior'] ?? null,
                'cabang_kantor_id' => $cabangKantorMap['Kantor Pusat Denpasar'] ?? null,
                'gaji' => 7000000,
                'nama' => 'Dewi Lestari',
                'email' => 'dewi.lestari@niagaraone.com',
                'no_tlp' => '081234567893',
                'no_bpjs_ketenagakerjaan' => '12345678904',
                'no_bpjs_kesehatan' => '1234567890126',
                'no_npwp' => '1234567890123459',
                'jenjang_pendidikan_id' => $jenjangPendidikanMap['Sarjana / S1'] ?? null,
                'status_kawin_id' => $statusKawinMap['Kawin Tercatat'] ?? null,
                'agama_id' => $agamaMap['Islam'] ?? null,
                'status_kepegawaian_id' => $statusKepegawaianMap['Magang'] ?? null,
                'bank_id' => $bankMap['Bank Negara Indonesia (BNI)'] ?? null,
                'nama_bank' => 'BNI',
                'no_rekening' => '1234567893',
            ],
            [
                'nip' => '',
                'nik' => '5171012345670005',
                'lowongan_id' => null,
                'rekrutmen_id' => null,
                'departemen_id' => $departemenMap['Human Resources Department'] ?? null,
                'divisi_id' => $divisiMap['Recruitment'] ?? null,
                'section_id' => $sectionMap['Talent Acquisition'] ?? null,
                'job_position_id' => $jobPositionMap['Recruitment Specialist'] ?? null,
                'job_level_id' => $jobLevelMap['Senior'] ?? null,
                'cabang_kantor_id' => $cabangKantorMap['Cabang Kuta'] ?? null,
                'gaji' => 8500000,
                'nama' => 'Rizky Pratama',
                'email' => 'rizky.pratama@niagaraone.com',
                'no_tlp' => '081234567894',
                'no_bpjs_ketenagakerjaan' => '12345678905',
                'no_bpjs_kesehatan' => '1234567890127',
                'no_npwp' => '1234567890123460',
                'jenjang_pendidikan_id' => $jenjangPendidikanMap['Sarjana / S1'] ?? null,
                'status_kawin_id' => $statusKawinMap['Kawin Tercatat'] ?? null,
                'agama_id' => $agamaMap['Islam'] ?? null,
                'status_kepegawaian_id' => $statusKepegawaianMap['PKWTT'] ?? null,
                'bank_id' => $bankMap['CIMB Niaga'] ?? null,
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
