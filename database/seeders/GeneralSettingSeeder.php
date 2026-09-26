<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GeneralSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            ['key' => 'waktu_kerja', 'name' => 'Waktu Kerja', 'value' => '8'],
            ['key' => 'waktu_istirahat', 'name' => 'Waktu Istirahat', 'value' => '1'],
            ['key' => 'annual_leave_kuota', 'name' => 'Kuota Cuti Tahunan', 'value' => '12'],
            ['key' => 'logo', 'name' => 'Logo', 'value' => null],
            ['key' => 'title', 'name' => 'Judul', 'value' => 'Niagara One'],
            ['key' => 'default_password', 'name' => 'Password Default', 'value' => 'Niagara@123'],
        ];

        foreach ($settings as $data) {
            \App\Models\GeneralSetting::firstOrCreate(['key' => $data['key']], $data);
        }
    }
}
