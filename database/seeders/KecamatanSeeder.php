<?php

namespace Database\Seeders;

use App\Models\Kecamatan as KecamatanModel;
use App\Models\KotaKabupaten as KotaKabupatenModel;
use Illuminate\Database\Seeder;
use Laravolt\Indonesia\Models\District as IndonesiaDistrict;

class KecamatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kotaMap = KotaKabupatenModel::pluck('id', 'kode');

        foreach (IndonesiaDistrict::all() as $district) {
            $kotaId = $kotaMap[$district->city_code] ?? null;

            if (empty($kotaId)) {
                continue;
            }

            KecamatanModel::firstOrCreate(
                ['kode' => $district->code],
                [
                    'kota_kabupaten_id' => $kotaId,
                    'kode' => $district->code,
                    'nama' => $district->name,
                    'status' => 'aktif',
                ]
            );
        }
    }
}
