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
            ['kode' => '', 'nama' => 'Belum Kawin'],
            ['kode' => '', 'nama' => 'Kawin Belum Tercatat'],
            ['kode' => '', 'nama' => 'Kawin Tercatat'],
            ['kode' => '', 'nama' => 'Cerai Hidup'],
            ['kode' => '', 'nama' => 'Cerai Mati'],
        ];

        foreach ($statusKawin as $status) {
            \App\Models\StatusKawin::create($status);
        }
    }
}
