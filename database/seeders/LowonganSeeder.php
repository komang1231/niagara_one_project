<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Departemen as DepartemenModel;
use App\Models\Divisi as DivisiModel;
use App\Models\Section as SectionModel;
use App\Models\JobPosition as JobPositionModel;

class LowonganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permintaan = \App\Models\PermintaanKaryawan::first();
        $departemenMap = DepartemenModel::whereIn('kode', ['DEP-2600010920', 'DEP-2600020920', 'DEP-2600030920', 'DEP-2600040920', 'DEP-2600050920'])
            ->pluck('id', 'kode');
        $divisiMap = DivisiModel::whereIn('kode', ['DIV-2600010920', 'DIV-2600020920', 'DIV-2600030920', 'DIV-2600040920', 'DIV-2600050920', 'DIV-2600010921', 'DIV-2600010922'])
            ->pluck('id', 'kode');
        $sectionMap = SectionModel::whereIn('kode', ['SEC-2600010920', 'SEC-2600020920', 'SEC-2600030920', 'SEC-2600040920', 'SEC-2600050920', 'SEC-2600060920', 'SEC-2600070920', 'SEC-2600080920'])
            ->pluck('id', 'kode');
        $jobPositionMap = JobPositionModel::whereIn('kode', ['JOBP-2600010920', 'JOBP-2600020920', 'JOBP-2600030920', 'JOBP-2600040920', 'JOBP-2600050920', 'JOBP-2600060920', 'JOBP-2600070920'])
            ->pluck('id', 'kode');
        $jobLevel = \App\Models\JobLevel::first();
        $cabang = \App\Models\CabangKantor::first();

        \App\Models\Lowongan::firstOrCreate(
            [
                'kode' => 'LOW-2600010920',
                'judul' => 'Lowongan Backend Developer',
                'permintaan_karyawan_id' => $permintaan->id,
                'cabang_kantor_id' => $cabang->id,
                'departemen_id' => $departemenMap->get('DEP-2600010920'),
                'divisi_id' => $divisiMap->get('DIV-2600010920'),
                'section_id' => $sectionMap->get('SEC-2600010920'),
                'job_position_id' => $jobPositionMap->get('JOBP-2600010920'),
                'job_level_id' => $jobLevel->id,
                'kuota' => 2,
                'kualifikasi' => 'Minimal S1 Teknik Informatika, menguasai PHP & Laravel',
                'deskripsi' => 'Membangun dan memelihara backend aplikasi HRD',
                'min_gaji' => 6000000,
                'max_gaji' => 9000000,
                'tanggal_buka' => now(),
                'tanggal_tutup' => now()->addDays(30),
            ],
            [
                'kode' => 'LOW-2600020920',
                'judul' => 'Lowongan Frontend Developer',
                'permintaan_karyawan_id' => $permintaan->id,
                'cabang_kantor_id' => $cabang->id,
                'departemen_id' => $departemenMap->get('DEP-2600020920'),
                'divisi_id' => $divisiMap->get('DIV-2600010920'),
                'section_id' => $sectionMap->get('SEC-2600020920'),
                'job_position_id' => $jobPositionMap->get('JOBP-2600020920'),
                'job_level_id' => $jobLevel->id,
                'kuota' => 1,
                'kualifikasi' => 'Menguasai HTML, CSS, JavaScript, dan framework frontend (Vue/React)',
                'deskripsi' => 'Mengembangkan tampilan dan interaksi pengguna aplikasi HRD',
                'min_gaji' => 5000000,
                'max_gaji' => 8000000,
                'tanggal_buka' => now(),
                'tanggal_tutup' => now()->addDays(30),
            ],
            [
                'kode' => 'LOW-2600030920',
                'judul' => 'Lowongan QA Engineer',
                'permintaan_karyawan_id' => $permintaan->id,
                'cabang_kantor_id' => $cabang->id,
                'departemen_id' => $departemenMap->get('DEP-2600030920'),
                'divisi_id' => $divisiMap->get('DIV-2600010920'),
                'section_id' => $sectionMap->get('SEC-2600030920'),
                'job_position_id' => $jobPositionMap->get('JOBP-2600030920'),
                'job_level_id' => $jobLevel->id,
                'kuota' => 1,
                'kualifikasi' => 'Pengalaman testing otomatis, familiarity dengan Postman/Selenium',
                'deskripsi' => 'Menjamin kualitas dan stabilitas aplikasi HRD',
                'min_gaji' => 4500000,
                'max_gaji' => 7000000,
                'tanggal_buka' => now(),
                'tanggal_tutup' => now()->addDays(30),
            ],
            [
                'kode' => 'LOW-2600040920',
                'judul' => 'Lowongan DevOps Engineer',
                'permintaan_karyawan_id' => $permintaan->id,
                'cabang_kantor_id' => $cabang->id,
                'departemen_id' => $departemenMap->get('DEP-2600040920'),
                'divisi_id' => $divisiMap->get('DIV-2600040920'),
                'section_id' => $sectionMap->get('SEC-2600040920'),
                'job_position_id' => $jobPositionMap->get('JOBP-2600040920'),
                'job_level_id' => $jobLevel->id,
                'kuota' => 1,
                'kualifikasi' => 'Pengalaman CI/CD, Docker, dan pengelolaan server',
                'deskripsi' => 'Mengelola infrastruktur dan pipeline deployment',
                'min_gaji' => 7000000,
                'max_gaji' => 11000000,
                'tanggal_buka' => now(),
                'tanggal_tutup' => now()->addDays(30),
            ],
            [
                'kode' => 'LOW-2600050920',
                'judul' => 'Lowongan HR Generalist',
                'permintaan_karyawan_id' => $permintaan->id,
                'cabang_kantor_id' => $cabang->id,
                'departemen_id' => $departemenMap->get('DEP-2600050920'),
                'divisi_id' => $divisiMap->get('DIV-2600050920'),
                'section_id' => $sectionMap->get('SEC-2600050920'),
                'job_position_id' => $jobPositionMap->get('JOBP-2600050920'),
                'job_level_id' => $jobLevel->id,
                'kuota' => 1,
                'kualifikasi' => 'Pengalaman HR, administrasi, dan rekrutmen',
                'deskripsi' => 'Mendukung proses rekrutmen dan administrasi karyawan',
                'min_gaji' => 4000000,
                'max_gaji' => 6500000,
                'tanggal_buka' => now(),
                'tanggal_tutup' => now()->addDays(30),
            ],
        );
    }
}
