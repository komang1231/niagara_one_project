<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ShiftSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $shifts = [
            ['kode' => 'SHIFT_PAGI', 
            'nama' => 'Pagi', 
            'jam_masuk' => '07:00:00', 
            'jam_pulang' => '15:00:00', 
            'lintas_hari' => false, 
            'istirahat_menit' => 60, 
            'toleransi_keterlambatan' => 15, 
            'status' => 'aktif'],
            
            ['kode' => 'SHIFT_Siang', 'nama' => 'Siang', 'jam_masuk' => '15:00:00', 'jam_pulang' => '23:00:00', 'lintas_hari' => false, 'istirahat_menit' => 60, 'toleransi_keterlambatan' => 15, 'status' => 'aktif'],
            ['kode' => 'SHIFT_SORE', 'nama' => 'Sore', 'jam_masuk' => '13:00:00', 'jam_pulang' => '21:00:00', 'lintas_hari' => false, 'istirahat_menit' => 60, 'toleransi_keterlambatan' => 15, 'status' => 'aktif'],
            ['kode' => 'SHIFT_MALAM', 'nama' => 'Malam', 'jam_masuk' => '21:00:00', 'jam_pulang' => '05:00:00', 'lintas_hari' => true, 'istirahat_menit' => 60, 'toleransi_keterlambatan' => 15, 'status' => 'aktif'],
        ];

        foreach ($shifts as $shift) {
            \App\Models\Shift::create($shift);
        }
    }
}
