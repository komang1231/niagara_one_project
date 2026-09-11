<?php

namespace Database\Seeders;

use App\Models\JobPosition as JobPositionModel;
use App\Models\Section as SectionModel;
use Illuminate\Database\Seeder;

class JobPositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sectionMap = SectionModel::whereIn('kode', ['IT-BE', 'IT-FE', 'IT-QA', 'HRD-TA', 'MKT-SEO', 'OPS-FO', 'OPS-HK'])
            ->pluck('id', 'kode');

        $jobPositions = [
            ['kode' => 'JP-BE-01', 'nama' => 'Backend Developer', 'section_id' => $sectionMap['IT-BE'] ?? null],
            ['kode' => 'JP-FE-01', 'nama' => 'Frontend Developer', 'section_id' => $sectionMap['IT-FE'] ?? null],
            ['kode' => 'JP-QA-01', 'nama' => 'QA Engineer', 'section_id' => $sectionMap['IT-QA'] ?? null],
            ['kode' => 'JP-HRD-01', 'nama' => 'Recruitment Specialist', 'section_id' => $sectionMap['HRD-TA'] ?? null],
            ['kode' => 'JP-MKT-01', 'nama' => 'SEO Specialist', 'section_id' => $sectionMap['MKT-SEO'] ?? null],
            ['kode' => 'JP-OPS-01', 'nama' => 'Front Office Staff', 'section_id' => $sectionMap['OPS-FO'] ?? null],
            ['kode' => 'JP-OPS-02', 'nama' => 'Housekeeping Staff', 'section_id' => $sectionMap['OPS-HK'] ?? null],
        ];

        foreach ($jobPositions as $jobPosition) {
            if (empty($jobPosition['section_id'])) {
                continue;
            }

            JobPositionModel::firstOrCreate(
                ['kode' => $jobPosition['kode']],
                $jobPosition
            );
        }
    }
}
