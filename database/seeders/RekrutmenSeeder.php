<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Departemen as DepartemenModel;
use App\Models\Divisi as DivisiModel;
use App\Models\Section as SectionModel;
use App\Models\JobPosition as JobPositionModel;

class RekrutmenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $lowongan = \App\Models\Lowongan::first();
        $departemenMap = DepartemenModel::whereIn('kode', ['DEP-2600010920', 'DEP-2600020920', 'DEP-2600030920', 'DEP-2600040920', 'DEP-2600050920'])
            ->pluck('id', 'kode');
        $divisiMap = DivisiModel::whereIn('kode', ['DIV-2600010920', 'DIV-2600020920', 'DIV-2600030920', 'DIV-2600040920', 'DIV-2600050920', 'DIV-2600010921', 'DIV-2600010922'])
            ->pluck('id', 'kode');
        $sectionMap = SectionModel::whereIn('kode', ['SEC-2600010920', 'SEC-2600020920', 'SEC-2600030920', 'SEC-2600040920', 'SEC-2600050920', 'SEC-2600060920', 'SEC-2600070920', 'SEC-2600080920'])
            ->pluck('id', 'kode');
        $jobPositionMap = JobPositionModel::whereIn('kode', ['JOBP-2600010920', 'JOBP-2600020920', 'JOBP-2600030920', 'JOBP-2600040920', 'JOBP-2600050920', 'JOBP-2600060920', 'JOBP-2600070920'])
            ->pluck('id', 'kode');
        $jobLevel = \App\Models\JobLevel::first();
        $jenjangPendidikan = \App\Models\JenjangPendidikan::where('kode', 'S1')->first();
        $sumberPelamar = \App\Models\SumberPelamar::first();

        \App\Models\Rekrutmen::firstOrCreate(
            [
                'kode' => 'REK-2600010920',
                'lowongan_id' => $lowongan->id,
                'departemen_id' => $departemenMap->get('DEP-2600010920'),
                'divisi_id' => $divisiMap->get('DIV-2600010920'),
                'section_id' => $sectionMap->get('SEC-2600010920'),
                'job_position_id' => $jobPositionMap->get('JOBP-2600010920'),
                'job_level_id' => $jobLevel->id,
                'cabang_kantor_id' => $lowongan->cabang_kantor_id,
                'nama' => 'Andi Wijaya',
                'email' => 'andi.wijaya@gmail.com',
                'no_tlp' => '081298765432',
                'file_cv' => 'dummy-cv-andi.pdf',
                'jenjang_pendidikan_id' => $jenjangPendidikan->id ?? null,
                'sumber_pelamar_id' => $sumberPelamar->id ?? null,
                'status_rekrutmen' => 'pelamar',
            ],
            [
                'kode' => 'REK-2600020920',
                'lowongan_id' => $lowongan->id,
                'departemen_id' => $departemenMap->get('DEP-2600020920'),
                'divisi_id' => $divisiMap->get('DIV-2600020920'),
                'section_id' => $sectionMap->get('SEC-2600020920'),
                'job_position_id' => $jobPositionMap->get('JOBP-2600020920'),
                'job_level_id' => $jobLevel->id,
                'cabang_kantor_id' => $lowongan->cabang_kantor_id,
                'nama' => 'Budi Santoso',
                'email' => 'budi.santoso@example.com',
                'no_tlp' => '081234567890',
                'file_cv' => 'dummy-cv-budi.pdf',
                'jenjang_pendidikan_id' => $jenjangPendidikan->id ?? null,
                'sumber_pelamar_id' => $sumberPelamar->id ?? null,
                'status_rekrutmen' => 'pelamar',
            ],
            [
                'kode' => 'REK-2600030920',
                'lowongan_id' => $lowongan->id,
                'departemen_id' => $departemenMap->get('DEP-2600030920'),
                'divisi_id' => $divisiMap->get('DIV-2600030920'),
                'section_id' => $sectionMap->get('SEC-2600030920'),
                'job_position_id' => $jobPositionMap->get('JOBP-2600030920'),
                'job_level_id' => $jobLevel->id,
                'cabang_kantor_id' => $lowongan->cabang_kantor_id,
                'nama' => 'Citra Lestari',
                'email' => 'citra.lestari@example.com',
                'no_tlp' => '082345678901',
                'file_cv' => 'dummy-cv-citra.pdf',
                'jenjang_pendidikan_id' => $jenjangPendidikan->id ?? null,
                'sumber_pelamar_id' => $sumberPelamar->id ?? null,
                'status_rekrutmen' => 'interview',
            ],
            [
                'kode' => 'REK-2600040920',
                'lowongan_id' => $lowongan->id,
                'departemen_id' => $departemenMap->get('DEP-2600040920'),
                'divisi_id' => $divisiMap->get('DIV-2600040920'),
                'section_id' => $sectionMap->get('SEC-2600040920'),
                'job_position_id' => $jobPositionMap->get('JOBP-2600040920'),
                'job_level_id' => $jobLevel->id,
                'cabang_kantor_id' => $lowongan->cabang_kantor_id,
                'nama' => 'Dewi Putri',
                'email' => 'dewi.putri@example.com',
                'no_tlp' => '083456789012',
                'file_cv' => 'dummy-cv-dewi.pdf',
                'jenjang_pendidikan_id' => $jenjangPendidikan->id ?? null,
                'sumber_pelamar_id' => $sumberPelamar->id ?? null,
                'status_rekrutmen' => 'diterima',
            ],
            [
                'kode' => 'REK-2600050920',
                'lowongan_id' => $lowongan->id,
                'departemen_id' => $departemenMap->get('DEP-2600050920'),
                'divisi_id' => $divisiMap->get('DIV-2600050920'),
                'section_id' => $sectionMap->get('SEC-2600050920'),
                'job_position_id' => $jobPositionMap->get('JOBP-2600050920'),
                'job_level_id' => $jobLevel->id,
                'cabang_kantor_id' => $lowongan->cabang_kantor_id,
                'nama' => 'Eko Prasetyo',
                'email' => 'eko.prasetyo@example.com',
                'no_tlp' => '084567890123',
                'file_cv' => 'dummy-cv-eko.pdf',
                'jenjang_pendidikan_id' => $jenjangPendidikan->id ?? null,
                'sumber_pelamar_id' => $sumberPelamar->id ?? null,
                'status_rekrutmen' => 'ditolak',
            ],
        );
    }
}
