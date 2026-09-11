<?php

namespace Database\Seeders;

use App\Models\Provinsi as ProvinsiModel;
use Illuminate\Database\Seeder;
use Laravolt\Indonesia\Models\Province as IndonesiaProvince;

class ProvinsiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (IndonesiaProvince::all() as $province) {
            ProvinsiModel::firstOrCreate(
                ['kode' => $province->code],
                [
                    'kode' => $province->code,
                    'nama' => $province->name,
                    'status' => 'aktif',
                ]
            );
        }
    }
}
