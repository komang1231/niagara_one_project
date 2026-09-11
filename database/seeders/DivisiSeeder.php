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
        $departemenMap = DepartemenModel::whereIn('kode', ['HRD', 'IT', 'FIN', 'MKT', 'OPS'])
            ->pluck('id', 'kode');

        $Divisi = [
            ['kode' => 'HRD-RECRUIT', 'nama' => 'Recruitment', 'departemen_id' => $departemenMap['HRD'] ?? null],
            ['kode' => 'IT-WEB', 'nama' => 'Web Development', 'departemen_id' => $departemenMap['IT'] ?? null],
            ['kode' => 'IT-INFRA', 'nama' => 'Infrastructure', 'departemen_id' => $departemenMap['IT'] ?? null],
            ['kode' => 'FIN-ACC', 'nama' => 'Accounting', 'departemen_id' => $departemenMap['FIN'] ?? null],
            ['kode' => 'MKT-DIGITAL', 'nama' => 'Digital Marketing', 'departemen_id' => $departemenMap['MKT'] ?? null],
            ['kode' => 'OPS-HOTEL', 'nama' => 'Hotel Operations', 'departemen_id' => $departemenMap['OPS'] ?? null],
            ['kode' => 'OPS-WAREHOUSE', 'nama' => 'Warehouse', 'departemen_id' => $departemenMap['OPS'] ?? null],
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
