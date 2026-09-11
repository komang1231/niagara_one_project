<?php

namespace Database\Seeders;

use App\Models\KotaKabupaten as KotaKabupatenModel;
use App\Models\Provinsi as ProvinsiModel;
use Illuminate\Database\Seeder;
use Laravolt\Indonesia\Models\City as IndonesiaCity;

class KotaKabupatenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $provinsiMap = ProvinsiModel::pluck('id', 'kode');

        foreach (IndonesiaCity::all() as $city) {
            $provinsiId = $provinsiMap[$city->province_code] ?? null;

            if (empty($provinsiId)) {
                continue;
            }

            KotaKabupatenModel::firstOrCreate(
                ['kode' => $city->code],
                [
                    'provinsi_id' => $provinsiId,
                    'kode' => $city->code,
                    'nama' => $city->name,
                    'status' => 'aktif',
                ]
            );
        }
    }
}
