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
            ['nama' => 'Human Resources Department'],
            ['nama' => 'Information Technology'],
            ['nama' => 'Finance'],
            ['nama' => 'Marketing'],
            ['nama' => 'Operations'],
        ];

        foreach ($Departemen as $dept) {
            DepartemenModel::firstOrCreate(
                ['kode' => $dept['kode']],
                $dept
            );
        }
    }
}
