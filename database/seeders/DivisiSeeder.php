<?php

namespace Database\Seeders;

use App\Models\Departemen as DepartemenModel;
use App\Models\Divisi as DivisiModel;
use Illuminate\Database\Seeder;

class DivisiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $departemenMap = DepartemenModel::whereIn('nama', [
            'Human Resources Department',
            'Information Technology',
            'Finance',
            'Marketing',
            'Operations',
        ])->pluck('id', 'nama');

        $Divisi = [
            [
                'kode' => '',
                'nama' => 'Recruitment',
                'departemen_id' => $departemenMap['Human Resources Department'] ?? null,
            ],
            [
                'kode' => '',
                'nama' => 'Web Development',
                'departemen_id' => $departemenMap['Information Technology'] ?? null,
            ],
            [
                'kode' => '',
                'nama' => 'Infrastructure',
                'departemen_id' => $departemenMap['Information Technology'] ?? null,
            ],
            [
                'kode' => '',
                'nama' => 'Accounting',
                'departemen_id' => $departemenMap['Finance'] ?? null,
            ],
            [
                'kode' => '',
                'nama' => 'Digital Marketing',
                'departemen_id' => $departemenMap['Marketing'] ?? null,
            ],
            [
                'kode' => '',
                'nama' => 'Hotel Operations',
                'departemen_id' => $departemenMap['Operations'] ?? null,
            ],
            [
                'kode' => '',
                'nama' => 'Warehouse',
                'departemen_id' => $departemenMap['Operations'] ?? null,
            ],
        ];

        foreach ($Divisi as $div) {
            if (empty($div['departemen_id'])) {
                continue;
            }

            DivisiModel::firstOrCreate(
                ['kode' => $div['kode']],
                $div
            );
        }
    }
}
