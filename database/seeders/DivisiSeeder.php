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
        $departemenMap = DepartemenModel::whereIn('kode', ['DEP-2600010920', 'DEP-2600020920', 'DEP-2600030920', 'DEP-2600040920', 'DEP-2600050920'])
            ->pluck('id', 'kode');

        $Divisi = [
            ['kode' => 'DIV-2600010920', 'nama' => 'Recruitment', 'departemen_id' => $departemenMap['DEP-2600010920'] ?? null],
            ['kode' => 'DIV-2600020920', 'nama' => 'Web Development', 'departemen_id' => $departemenMap['DEP-2600020920'] ?? null],
            ['kode' => 'DIV-2600030920', 'nama' => 'Infrastructure', 'departemen_id' => $departemenMap['DEP-2600030920'] ?? null],
            ['kode' => 'DIV-2600040920', 'nama' => 'Accounting', 'departemen_id' => $departemenMap['DEP-2600040920'] ?? null],
            ['kode' => 'DIV-2600050920', 'nama' => 'Digital Marketing', 'departemen_id' => $departemenMap['DEP-2600050920'] ?? null],
            ['kode' => 'DIV-2600010921', 'nama' => 'Hotel Operations', 'departemen_id' => $departemenMap['DEP-2600010921'] ?? null],
            ['kode' => 'DIV-2600010922', 'nama' => 'Warehouse', 'departemen_id' => $departemenMap['DEP-2600010922'] ?? null],
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
