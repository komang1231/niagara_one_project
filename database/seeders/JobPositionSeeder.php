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
            ['nama' => 'Backend Developer', 'section_id' => $sectionMap['IT-BE'] ?? null],
            ['nama' => 'Frontend Developer', 'section_id' => $sectionMap['IT-FE'] ?? null],
            ['nama' => 'QA Engineer', 'section_id' => $sectionMap['IT-QA'] ?? null],
            ['nama' => 'Recruitment Specialist', 'section_id' => $sectionMap['HRD-TA'] ?? null],
            ['nama' => 'SEO Specialist', 'section_id' => $sectionMap['MKT-SEO'] ?? null],
            ['nama' => 'Front Office Staff', 'section_id' => $sectionMap['OPS-FO'] ?? null],
            ['nama' => 'Housekeeping Staff', 'section_id' => $sectionMap['OPS-HK'] ?? null],
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
