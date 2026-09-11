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
            ['kode' => 'HRD', 'nama' => 'Human Resources Department'],
            ['kode' => 'IT', 'nama' => 'Information Technology'],
            ['kode' => 'FIN', 'nama' => 'Finance'],
            ['kode' => 'MKT', 'nama' => 'Marketing'],
            ['kode' => 'OPS', 'nama' => 'Operations'],
        ];

        foreach ($Departemen as $dept) {
            DepartemenModel::firstOrCreate(
                ['kode' => $dept['kode']],
                $dept
            );
        }
    }
}
