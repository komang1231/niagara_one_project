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
        $sectionMap = SectionModel::whereIn('kode', ['SEC-2600010920', 'SEC-2600020920', 'SEC-2600030920', 'SEC-2600040920', 'SEC-2600050920', 'SEC-2600060920', 'SEC-2600070920', 'SEC-2600080920'])
            ->pluck('id', 'kode');

        $jobPositions = [
            ['kode' => 'JOBP-2600010920', 'nama' => 'Backend Developer', 'section_id' => $sectionMap['SEC-2600010920'] ?? null],
            ['kode' => 'JOBP-2600020920', 'nama' => 'Frontend Developer', 'section_id' => $sectionMap['SEC-2600020920'] ?? null],
            ['kode' => 'JOBP-2600030920', 'nama' => 'QA Engineer', 'section_id' => $sectionMap['SEC-2600030920'] ?? null],
            ['kode' => 'JOBP-2600040920', 'nama' => 'Recruitment Specialist', 'section_id' => $sectionMap['SEC-2600040920'] ?? null],
            ['kode' => 'JOBP-2600050920', 'nama' => 'SEO Specialist', 'section_id' => $sectionMap['SEC-2600050920'] ?? null],
            ['kode' => 'JOBP-2600060920', 'nama' => 'Front Office Staff', 'section_id' => $sectionMap['SEC-2600060920'] ?? null],
            ['kode' => 'JOBP-2600070920', 'nama' => 'Housekeeping Staff', 'section_id' => $sectionMap['SEC-2600070920'] ?? null],
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
