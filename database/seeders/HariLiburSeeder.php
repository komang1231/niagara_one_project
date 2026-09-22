<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HariLiburSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tahun = date('Y');
        $hariLibur = [
            ['kode' => '', 'tanggal' => $tahun . '-01-01', 'nama' => 'Tahun Baru Masehi'],
            ['kode' => '', 'tanggal' => $tahun . '-02-17', 'nama' => 'Tahun Baru Imlek'],
            ['kode' => '', 'tanggal' => $tahun . '-03-19', 'nama' => 'Hari Raya Nyepi'],
            ['kode' => '', 'tanggal' => $tahun . '-03-21', 'nama' => 'Hari Raya Idul Fitri'],
            ['kode' => '', 'tanggal' => $tahun . '-03-22', 'nama' => 'Hari Raya Idul Fitri (Hari ke-2)'],
            ['kode' => '', 'tanggal' => $tahun . '-05-01', 'nama' => 'Hari Buruh Internasional'],
            ['kode' => '', 'tanggal' => $tahun . '-05-14', 'nama' => 'Kenaikan Yesus Kristus'],
            ['kode' => '', 'tanggal' => $tahun . '-05-27', 'nama' => 'Hari Raya Idul Adha'],
            ['kode' => '', 'tanggal' => $tahun . '-05-31', 'nama' => 'Hari Raya Waisak'],
            ['kode' => '', 'tanggal' => $tahun . '-06-01', 'nama' => 'Hari Lahir Pancasila'],
            ['kode' => '', 'tanggal' => $tahun . '-08-17', 'nama' => 'Hari Kemerdekaan Indonesia'],
            ['kode' => '', 'tanggal' => $tahun . '-12-25', 'nama' => 'Hari Raya Natal'],
        ];

        foreach ($hariLibur as $libur) {
            \App\Models\HariLibur::create($libur);
        }
    }
}
