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
            ['kode' => 'HL-01', 'tanggal' => $tahun . '-01-01', 'nama' => 'Tahun Baru Masehi'],
            ['kode' => 'HL-02', 'tanggal' => $tahun . '-02-17', 'nama' => 'Tahun Baru Imlek'],
            ['kode' => 'HL-03', 'tanggal' => $tahun . '-03-19', 'nama' => 'Hari Raya Nyepi'],
            ['kode' => 'HL-04', 'tanggal' => $tahun . '-03-21', 'nama' => 'Hari Raya Idul Fitri'],
            ['kode' => 'HL-05', 'tanggal' => $tahun . '-03-22', 'nama' => 'Hari Raya Idul Fitri (Hari ke-2)'],
            ['kode' => 'HL-06', 'tanggal' => $tahun . '-05-01', 'nama' => 'Hari Buruh Internasional'],
            ['kode' => 'HL-07', 'tanggal' => $tahun . '-05-14', 'nama' => 'Kenaikan Yesus Kristus'],
            ['kode' => 'HL-08', 'tanggal' => $tahun . '-05-27', 'nama' => 'Hari Raya Idul Adha'],
            ['kode' => 'HL-09', 'tanggal' => $tahun . '-05-31', 'nama' => 'Hari Raya Waisak'],
            ['kode' => 'HL-10', 'tanggal' => $tahun . '-06-01', 'nama' => 'Hari Lahir Pancasila'],
            ['kode' => 'HL-11', 'tanggal' => $tahun . '-08-17', 'nama' => 'Hari Kemerdekaan Indonesia'],
            ['kode' => 'HL-12', 'tanggal' => $tahun . '-12-25', 'nama' => 'Hari Raya Natal'],
        ];

        foreach ($hariLibur as $libur) {
            \App\Models\HariLibur::create($libur);
        }
    }
}
