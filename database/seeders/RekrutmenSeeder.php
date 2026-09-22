<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Departemen as DepartemenModel;
use App\Models\Divisi as DivisiModel;
use App\Models\Section as SectionModel;
use App\Models\JobPosition as JobPositionModel;
use App\Models\JenjangPendidikan as JenjangPendidikanModel;
use App\Models\SumberPelamar as SumberPelamarModel;
use App\Models\JobLevel as JobLevelModel;
use App\Models\CabangKantor as CabangKantorModel;

class RekrutmenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $lowonganMap = \App\Models\Lowongan::whereIn('judul', [
            'Lowongan Backend Developer',
            'Lowongan Frontend Developer',
            'Lowongan QA Engineer',
            'Lowongan DevOps Engineer',
            'Lowongan HR Generalist',
        ])->pluck('id', 'judul');

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

        $sumberPelamarMap = SumberPelamarModel::whereIn('nama', [
            'Website Perusahaan',
            'LinkedIn',
            'Jobstreet',
            'Instagram',
            'Referensi Karyawan',
            'Walk-in / Datang Langsung',
            'Job Fair',
            'Kampus / Kampus Recruitment',
            'Agency/Headhunter',
        ])->pluck('id', 'nama');

        $cabangKantorMap = CabangKantorModel::whereIn('nama', [
            'Kantor Pusat Denpasar',
            'Cabang Kuta',
            'Cabang Jakarta',
            'Cabang Surabaya',
            'Cabang Bandung',
            'Cabang Yogyakarta',
            'Cabang Medan',
        ])->pluck('id', 'nama');

        \App\Models\Rekrutmen::firstOrCreate(
            [
                'kode' => '',
                'lowongan_id' => $lowonganMap['Lowongan DevOps Engineer'] ?? null,
                'departemen_id' => $departemenMap['Information Technology'] ?? null,
                'divisi_id' => $divisiMap['Web Development'] ?? null,
                'section_id' => $sectionMap['DevOps'] ?? null,
                'job_position_id' => $jobPositionMap['DevOps Engineer'] ?? null,
                'job_level_id' => $jobLevelMap['Intermediate'] ?? null,
                'cabang_kantor_id' => $cabangKantorMap['Kantor Pusat Denpasar'] ?? null,
                'nama' => 'Andi Wijaya',
                'email' => 'andi.wijaya@gmail.com',
                'no_tlp' => '081298765432',
                'file_cv' => 'dummy-cv-andi.pdf',
                'jenjang_pendidikan_id' => $jenjangPendidikanMap['Sarjana / S1'] ?? null,
                'sumber_pelamar_id' => $sumberPelamarMap['Website Perusahaan'] ?? null,
                'status_rekrutmen' => 'pelamar',
            ],
            [
                'kode' => '',
                'lowongan_id' => $lowonganMap['Lowongan QA Engineer'] ?? null,
                'departemen_id' => $departemenMap['Information Technology'] ?? null,
                'divisi_id' => $divisiMap['Web Development'] ?? null,
                'section_id' => $sectionMap['Quality Assurance'] ?? null,
                'job_position_id' => $jobPositionMap['QA Engineer'] ?? null,
                'job_level_id' => $jobLevelMap['Junior'] ?? null,
                'cabang_kantor_id' => $cabangKantorMap['Kantor Pusat Denpasar'] ?? null,
                'nama' => 'Budi Santoso',
                'email' => 'budi.santoso@example.com',
                'no_tlp' => '081234567890',
                'file_cv' => 'dummy-cv-budi.pdf',
                'jenjang_pendidikan_id' => $jenjangPendidikanMap['Sarjana / S1'] ?? null,
                'sumber_pelamar_id' => $sumberPelamarMap['Website Perusahaan'] ?? null,
                'status_rekrutmen' => 'pelamar',
            ],
            [
                'kode' => '',
                'lowongan_id' => $lowonganMap['Lowongan HR Generalist'] ?? null,
                'departemen_id' => $departemenMap['Human Resources Department'] ?? null,
                'divisi_id' => $divisiMap['Recruitment'] ?? null,
                'section_id' => $sectionMap['Talent Acquisition'] ?? null,
                'job_position_id' => $jobPositionMap['Recruitment Specialist'] ?? null,
                'job_level_id' => $jobLevelMap['Junior'] ?? null,
                'cabang_kantor_id' => $cabangKantorMap['Kantor Pusat Denpasar'] ?? null,
                'nama' => 'Citra Lestari',
                'email' => 'citra.lestari@example.com',
                'no_tlp' => '082345678901',
                'file_cv' => 'dummy-cv-citra.pdf',
                'jenjang_pendidikan_id' => $jenjangPendidikanMap['Sarjana / S1'] ?? null,
                'sumber_pelamar_id' => $sumberPelamarMap['Website Perusahaan'] ?? null,
                'status_rekrutmen' => 'interview',
            ],
            [
                'kode' => '',
                'lowongan_id' => $lowonganMap['Lowongan Frontend Developer'] ?? null,
                'departemen_id' => $departemenMap['Information Technology'] ?? null,
                'divisi_id' => $divisiMap['Web Development'] ?? null,
                'section_id' => $sectionMap['Frontend'] ?? null,
                'job_position_id' => $jobPositionMap['Frontend Developer'] ?? null,
                'job_level_id' => $jobLevelMap['Senior'] ?? null,
                'cabang_kantor_id' => $cabangKantorMap['Kantor Pusat Denpasar'] ?? null,
                'nama' => 'Dewi Putri',
                'email' => 'dewi.putri@example.com',
                'no_tlp' => '083456789012',
                'file_cv' => 'dummy-cv-dewi.pdf',
                'jenjang_pendidikan_id' => $jenjangPendidikanMap['Sarjana / S1'] ?? null,
                'sumber_pelamar_id' => $sumberPelamarMap['Website Perusahaan'] ?? null,
                'status_rekrutmen' => 'diterima',
            ],
            [
                'kode' => '',
                'lowongan_id' => $lowonganMap['Lowongan Backend Developer'] ?? null,
                'departemen_id' => $departemenMap['Information Technology'] ?? null,
                'divisi_id' => $divisiMap['Web Development'] ?? null,
                'section_id' => $sectionMap['Backend'] ?? null,
                'job_position_id' => $jobPositionMap['Backend Developer'] ?? null,
                'job_level_id' => $jobLevelMap['Junior'] ?? null,
                'cabang_kantor_id' => $cabangKantorMap['Kantor Pusat Denpasar'] ?? null,
                'nama' => 'Eko Prasetyo',
                'email' => 'eko.prasetyo@example.com',
                'no_tlp' => '084567890123',
                'file_cv' => 'dummy-cv-eko.pdf',
                'jenjang_pendidikan_id' => $jenjangPendidikanMap['Sarjana / S1'] ?? null,
                'sumber_pelamar_id' => $sumberPelamarMap['Website Perusahaan'] ?? null,
                'status_rekrutmen' => 'ditolak',
            ],
        );
    }
}
