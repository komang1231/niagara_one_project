<?php

namespace Database\Seeders;

use App\Models\Divisi as DivisiModel;
use App\Models\Section as SectionModel;
use Illuminate\Database\Seeder;

class SectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $divisiMap = DivisiModel::whereIn('kode', ['IT-WEB', 'IT-INFRA', 'HRD-RECRUIT', 'MKT-DIGITAL', 'OPS-HOTEL'])
            ->pluck('id', 'kode');

        $Section = [
            ['kode' => 'IT-BE', 'nama' => 'Backend', 'divisi_id' => $divisiMap['IT-WEB'] ?? null],
            ['kode' => 'IT-FE', 'nama' => 'Frontend', 'divisi_id' => $divisiMap['IT-WEB'] ?? null],
            ['kode' => 'IT-QA', 'nama' => 'Quality Assurance', 'divisi_id' => $divisiMap['IT-WEB'] ?? null],
            ['kode' => 'HRD-TA', 'nama' => 'Talent Acquisition', 'divisi_id' => $divisiMap['HRD-RECRUIT'] ?? null],
            ['kode' => 'MKT-SEO', 'nama' => 'SEO', 'divisi_id' => $divisiMap['MKT-DIGITAL'] ?? null],
            ['kode' => 'MKT-SOCMED', 'nama' => 'Social Media', 'divisi_id' => $divisiMap['MKT-DIGITAL'] ?? null],
            ['kode' => 'OPS-FO', 'nama' => 'Front Office', 'divisi_id' => $divisiMap['OPS-HOTEL'] ?? null],
            ['kode' => 'OPS-HK', 'nama' => 'Housekeeping', 'divisi_id' => $divisiMap['OPS-HOTEL'] ?? null],
        ];

        foreach ($Section as $item) {
            if (empty($item['divisi_id'])) {
                continue;
            }

            SectionModel::firstOrCreate(
                ['kode' => $item['kode']],
                $item
            );
        }
    }
}
