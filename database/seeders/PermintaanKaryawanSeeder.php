<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\JobPosition as JobPositionModel;
use App\Models\JobLevel as JobLevelModel;
use App\Models\Karyawan as KaryawanModel;
use App\Models\CabangKantor as CabangKantorModel;
use App\Models\Departemen as DepartemenModel;
use App\Models\Divisi as DivisiModel;
use App\Models\Section as SectionModel;

class PermintaanKaryawanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $karyawanMap = KaryawanModel::whereIn('nik', [
            '5171012345670001',
            '5171012345670002',
            '5171012345670003',
            '5171012345670004',
            '5171012345670005',
        ])->pluck('id', 'nik');

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

        \App\Models\PermintaanKaryawan::firstOrCreate(
            [
                'kode' => '',
                'karyawan_id' => $karyawanMap['5171012345670001'] ?? null,
                'cabang_kantor_id' => $cabangKantorMap['Kantor Pusat Denpasar'] ?? null,
                'departemen_id' => $departemenMap['Marketing'] ?? null,
                'divisi_id' => $divisiMap[''] ?? null,
                'section_id' => $sectionMap[''] ?? null,
                'job_position_id' => $jobPositionMap[''] ?? null,
                'job_level_id' => $jobLevelMap['Departement Head'] ?? null,
                'jumlah' => 2,
            ],
            [
                'kode' => '',
                'karyawan_id' => $karyawanMap['5171012345670002'] ?? null,
                'cabang_kantor_id' => $cabangKantorMap['Cabang Jakarta'] ?? null,
                'departemen_id' => $departemenMap['Information Technology'] ?? null,
                'divisi_id' => $divisiMap[''] ?? null,
                'section_id' => $sectionMap[''] ?? null,
                'job_position_id' => $jobPositionMap[''] ?? null,
                'job_level_id' => $jobLevelMap['Departement Head'] ?? null,
                'jumlah' => 1,
            ],
            [
                'kode' => '',
                'karyawan_id' => $karyawanMap['5171012345670003'] ?? null,
                'cabang_kantor_id' => $cabangKantorMap['Cabang Surabaya'] ?? null,
                'departemen_id' => $departemenMap['Information Technology'] ?? null,
                'divisi_id' => $divisiMap['Web Development'] ?? null,
                'section_id' => $sectionMap[''] ?? null,
                'job_position_id' => $jobPositionMap[''] ?? null,
                'job_level_id' => $jobLevelMap['Division Head'] ?? null,
                'jumlah' => 3,
            ],
            [
                'kode' => '',
                'karyawan_id' => $karyawanMap['5171012345670004'] ?? null,
                'cabang_kantor_id' => $cabangKantorMap['Cabang Genteng'] ?? null,
                'departemen_id' => $departemenMap['Information Technology'] ?? null,
                'divisi_id' => $divisiMap['Web Development'] ?? null,
                'section_id' => $sectionMap['Front Office'] ?? null,
                'job_position_id' => $jobPositionMap['Front Office Staff'] ?? null,
                'job_level_id' => $jobLevelMap['Junior'] ?? null,
                'jumlah' => 2,
            ],
            [
                'kode' => '',
                'karyawan_id' => $karyawanMap['5171012345670005'] ?? null,
                'cabang_kantor_id' => $cabangKantorMap['Cabang Kuta'] ?? null,
                'departemen_id' => $departemenMap['Information Technology'] ?? null,
                'divisi_id' => $divisiMap['Web Development'] ?? null,
                'section_id' => $sectionMap['Housekeeping'] ?? null,
                'job_position_id' => $jobPositionMap['Housekeeping Staff'] ?? null,
                'job_level_id' => $jobLevelMap['Junior'] ?? null,
                'jumlah' => 1,
            ]
        );
    }
}
