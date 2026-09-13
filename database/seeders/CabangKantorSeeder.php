<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CabangKantorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kecamatanMap = \App\Models\Kecamatan::whereIn('nama', ['Denpasar Selatan', 'Kuta Utara', 'Menteng'])
            ->pluck('id', 'nama');

        $cabang = [
            [
                'kode' => 'CB-001',
                'nama' => 'Kantor Pusat Denpasar',
                'latitude' => -8.6858,
                'longitude' => 115.2126,
                'radius_geofence' => 100,
                'alamat_lengkap' => 'Jl. Diponegoro No. 1, Denpasar Selatan',
                'kecamatan_id' => $kecamatanMap['DENPASAR SELATAN'] ?? null,
                'kode_pos' => '80114',
            ],
            [
                'kode' => 'CB-002',
                'nama' => 'Cabang Kuta',
                'latitude' => -8.7183,
                'longitude' => 115.1686,
                'radius_geofence' => 100,
                'alamat_lengkap' => 'Jl. Raya Kuta No. 45, Kuta Utara',
                'kecamatan_id' => $kecamatanMap['KUTA UTARA'] ?? null,
                'kode_pos' => '80361',
            ],
            [
                'kode' => 'CB-003',
                'nama' => 'Cabang Jakarta',
                'latitude' => -6.1966,
                'longitude' => 106.8383,
                'radius_geofence' => 150,
                'alamat_lengkap' => 'Jl. Cikini Raya No. 10, Menteng',
                'kecamatan_id' => $kecamatanMap['MENTENG'] ?? null,
                'kode_pos' => '10330',
            ],
        ];

        foreach ($cabang as $data) {
            \App\Models\CabangKantor::firstOrCreate(['kode' => $data['kode']], $data);
        }
    }
}
