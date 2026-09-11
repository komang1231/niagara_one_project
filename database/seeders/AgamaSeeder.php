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
            ['kode' => 'ISL', 'nama' => 'Islam'],
            ['kode' => 'KRS', 'nama' => 'Kristen'],
            ['kode' => 'KTL', 'nama' => 'Katolik'],
            ['kode' => 'HND', 'nama' => 'Hindu'],
            ['kode' => 'BDH', 'nama' => 'Buddha'],
            ['kode' => 'KHC', 'nama' => 'Konghucu'],
        ];

        foreach ($Agama as $data) {
            \App\Models\Agama::create($data);
        }
    }
}
