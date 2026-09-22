<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Departemen as DepartemenModel;
use App\Models\Divisi as DivisiModel;
use App\Models\Section as SectionModel;
use App\Models\JobPosition as JobPositionModel;
use App\Models\JobLevel as JobLevelModel;
use App\Models\CabangKantor as CabangKantorModel;

class LowonganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permintaan = \App\Models\PermintaanKaryawan::first();

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

        $cabangKantorMap = CabangKantorModel::whereIn('nama', [
            'Kantor Pusat Denpasar',
            'Cabang Kuta',
            'Cabang Jakarta',
            'Cabang Surabaya',
            'Cabang Bandung',
            'Cabang Yogyakarta',
            'Cabang Medan',
        ])->pluck('id', 'nama');

        \App\Models\Lowongan::create([
            'kode' => '',
            'judul' => 'Lowongan Backend Developer',
            'permintaan_karyawan_id' => $permintaan->id,
            'cabang_kantor_id' => $cabangKantorMap->get('Kantor Pusat Denpasar'),
            'departemen_id' => $departemenMap->get('Information Technology'),
            'divisi_id' => $divisiMap->get('Web Development'),
            'section_id' => $sectionMap->get('Backend'),
            'job_position_id' => $jobPositionMap->get('Backend Developer'),
            'job_level_id' => $jobLevelMap->get('Junior'),
            'kuota' => 2,
            'kualifikasi' => 'Minimal S1 Teknik Informatika, menguasai PHP & Laravel',
            'deskripsi' => 'Membangun dan memelihara backend aplikasi HRD',
            'min_gaji' => 6000000,
            'max_gaji' => 9000000,
            'tanggal_buka' => now(),
            'tanggal_tutup' => now()->addDays(30),
        ]);

        \App\Models\Lowongan::create([
            'kode' => '',
            'judul' => 'Lowongan Frontend Developer',
            'permintaan_karyawan_id' => $permintaan->id,
            'cabang_kantor_id' => $cabangKantorMap->get('Kantor Pusat Denpasar'),
            'departemen_id' => $departemenMap->get('Information Technology'),
            'divisi_id' => $divisiMap->get('Web Development'),
            'section_id' => $sectionMap->get('Frontend'),
            'job_position_id' => $jobPositionMap->get('Frontend Developer'),
            'job_level_id' => $jobLevelMap->get('Junior'),
            'kuota' => 1,
            'kualifikasi' => 'Menguasai HTML, CSS, JavaScript, dan framework frontend (Vue/React)',
            'deskripsi' => 'Mengembangkan tampilan dan interaksi pengguna aplikasi HRD',
            'min_gaji' => 5000000,
            'max_gaji' => 8000000,
            'tanggal_buka' => now(),
            'tanggal_tutup' => now()->addDays(30),
        ]);

        \App\Models\Lowongan::create([
            'kode' => '',
            'judul' => 'Lowongan QA Engineer',
            'permintaan_karyawan_id' => $permintaan->id,
            'cabang_kantor_id' => $cabangKantorMap->get('Kantor Pusat Denpasar'),
            'departemen_id' => $departemenMap->get('Information Technology'),
            'divisi_id' => $divisiMap->get('Web Development'),
            'section_id' => $sectionMap->get('Quality Assurance'),
            'job_position_id' => $jobPositionMap->get('QA Engineer'),
            'job_level_id' => $jobLevelMap->get('Intermediate'),
            'kuota' => 1,
            'kualifikasi' => 'Pengalaman testing otomatis, familiarity dengan Postman/Selenium',
            'deskripsi' => 'Menjamin kualitas dan stabilitas aplikasi HRD',
            'min_gaji' => 4500000,
            'max_gaji' => 7000000,
            'tanggal_buka' => now(),
            'tanggal_tutup' => now()->addDays(30),
        ]);

        \App\Models\Lowongan::create([
            'kode' => '',
            'judul' => 'Lowongan DevOps Engineer',
            'permintaan_karyawan_id' => $permintaan->id,
            'cabang_kantor_id' => $cabangKantorMap->get('Kantor Pusat Denpasar'),
            'departemen_id' => $departemenMap->get('Information Technology'),
            'divisi_id' => $divisiMap->get('Infrastructure'),
            'section_id' => null,
            'job_position_id' => null,
            'job_level_id' => $jobLevelMap->get('Senior'),
            'kuota' => 1,
            'kualifikasi' => 'Pengalaman CI/CD, Docker, dan pengelolaan server',
            'deskripsi' => 'Mengelola infrastruktur dan pipeline deployment',
            'min_gaji' => 7000000,
            'max_gaji' => 11000000,
            'tanggal_buka' => now(),
            'tanggal_tutup' => now()->addDays(30),
        ]);

        \App\Models\Lowongan::create([
            'kode' => '',
            'judul' => 'Lowongan HR Generalist',
            'permintaan_karyawan_id' => $permintaan->id,
            'cabang_kantor_id' => $cabangKantorMap->get('Kantor Pusat Denpasar'),
            'departemen_id' => $departemenMap->get('Human Resources Department'),
            'divisi_id' => $divisiMap->get('Recruitment'),
            'section_id' => $sectionMap->get('Talent Acquisition'),
            'job_position_id' => $jobPositionMap->get('Recruitment Specialist'),
            'job_level_id' => $jobLevelMap->get('Intermediate'),
            'kuota' => 1,
            'kualifikasi' => 'Pengalaman HR, administrasi, dan rekrutmen',
            'deskripsi' => 'Mendukung proses rekrutmen dan administrasi karyawan',
            'min_gaji' => 4000000,
            'max_gaji' => 6500000,
            'tanggal_buka' => now(),
            'tanggal_tutup' => now()->addDays(30),
        ]);
    }
}
