<?php

namespace Database\Seeders;

use App\Models\Departemen as DepartemenModel;
use Illuminate\Database\Seeder;

class DepartemenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $Departemen = [
            ['kode' => 'DEP-2600010920', 'nama' => 'Human Resources Department'],
            ['kode' => 'DEP-2600020920', 'nama' => 'Information Technology'],
            ['kode' => 'DEP-2600030920', 'nama' => 'Finance'],
            ['kode' => 'DEP-2600040920', 'nama' => 'Marketing'],
            ['kode' => 'DEP-2600050920', 'nama' => 'Operations'],
        ];

        foreach ($Departemen as $dept) {
            DepartemenModel::firstOrCreate(
                ['kode' => $dept['kode']],
                $dept
            );
        }
    }
}
