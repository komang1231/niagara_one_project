<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AgamaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $Agama = [
            ['kode' => '', 'nama' => 'Islam'],
            ['kode' => '', 'nama' => 'Kristen'],
            ['kode' => '', 'nama' => 'Katolik'],
            ['kode' => '', 'nama' => 'Hindu'],
            ['kode' => '', 'nama' => 'Buddha'],
            ['kode' => '', 'nama' => 'Konghucu'],
        ];

        foreach ($Agama as $data) {
            \App\Models\Agama::create($data);
        }
    }
}
