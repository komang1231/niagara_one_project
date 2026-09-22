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
            ['kode' => '', 'nama' => 'Human Resources Department'],
            ['kode' => '', 'nama' => 'Information Technology'],
            ['kode' => '', 'nama' => 'Finance'],
            ['kode' => '', 'nama' => 'Marketing'],
            ['kode' => '', 'nama' => 'Operations'],
        ];

        foreach ($Departemen as $dept) {
            DepartemenModel::firstOrCreate(
                ['kode' => $dept['kode']],
                $dept
            );
        }
    }
}
