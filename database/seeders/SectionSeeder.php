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
        $divisiMap = DivisiModel::whereIn('nama', [
            'Recruitment',
            'Web Development',
            'Infrastructure',
            'Accounting',
            'Digital Marketing',
            'Hotel Operations',
            'Warehouse',
        ])->pluck('id', 'nama');

        $Section = [
            [
                'kode' => '',
                'nama' => 'Backend',
                'divisi_id' => $divisiMap['Web Development'] ?? null,
            ],
            [
                'kode' => '',
                'nama' => 'Frontend',
                'divisi_id' => $divisiMap['Web Development'] ?? null,
            ],
            [
                'kode' => '',
                'nama' => 'Quality Assurance',
                'divisi_id' => $divisiMap['Web Development'] ?? null,
            ],
            [
                'kode' => '',
                'nama' => 'Talent Acquisition',
                'divisi_id' => $divisiMap['Recruitment'] ?? null,
            ],
            [
                'kode' => '',
                'nama' => 'SEO',
                'divisi_id' => $divisiMap['Digital Marketing'] ?? null,
            ],
            [
                'kode' => '',
                'nama' => 'Social Media',
                'divisi_id' => $divisiMap['Digital Marketing'] ?? null,
            ],
            [
                'kode' => '',
                'nama' => 'Front Office',
                'divisi_id' => $divisiMap['Hotel Operations'] ?? null,
            ],
            [
                'kode' => '',
                'nama' => 'Housekeeping',
                'divisi_id' => $divisiMap['Hotel Operations'] ?? null,
            ],
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
