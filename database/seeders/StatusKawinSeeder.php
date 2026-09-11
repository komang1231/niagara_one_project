<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StatusKawinSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statusKawin = [
            ['kode' => 'BELUM_KAWIN', 'nama' => 'Belum Kawin'],
            ['kode' => 'KAWIN_BELUM_TERCATAT', 'nama' => 'Kawin Belum Tercatat'],
            ['kode' => 'KAWIN_TERCATAT', 'nama' => 'Kawin Tercatat'],
            ['kode' => 'CERAI_HIDUP', 'nama' => 'Cerai Hidup'],
            ['kode' => 'CERAI_MATI', 'nama' => 'Cerai Mati'],
        ];

        foreach ($statusKawin as $status) {
            \App\Models\StatusKawin::create($status);
        }
    }
}
