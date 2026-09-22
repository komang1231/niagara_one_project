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

        $jobPositions = [
            [
                'kode' => '',
                'nama' => 'Backend Developer',
                'section_id' => $sectionMap['Backend'] ?? null,
            ],
            [
                'kode' => '',
                'nama' => 'Frontend Developer',
                'section_id' => $sectionMap['Frontend'] ?? null,
            ],
            [
                'kode' => '',
                'nama' => 'QA Engineer',
                'section_id' => $sectionMap['Quality Assurance'] ?? null,
            ],
            [
                'kode' => '',
                'nama' => 'Recruitment Specialist',
                'section_id' => $sectionMap['Talent Acquisition'] ?? null,
            ],
            [
                'kode' => '',
                'nama' => 'SEO Specialist',
                'section_id' => $sectionMap['SEO'] ?? null,
            ],
            [
                'kode' => '',
                'nama' => 'Front Office Staff',
                'section_id' => $sectionMap['Front Office'] ?? null,
            ],
            [
                'kode' => '',
                'nama' => 'Housekeeping Staff',
                'section_id' => $sectionMap['Housekeeping'] ?? null,
            ],
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
