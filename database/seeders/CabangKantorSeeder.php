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
        $kecamatanMap = \App\Models\Kecamatan::whereIn('nama', ['Denpasar Selatan', 'Kuta Utara', 'Menteng', 'Genteng', 'Bandung Wetan', 'Gedongtengen', 'Medan Kota'])
            ->pluck('id', 'nama');

        $cabang = [
            [
                'kode' => '',
                'nama' => 'Kantor Pusat Denpasar',
                'latitude' => -8.6858,
                'longitude' => 115.2126,
                'radius_geofence' => 100,
                'alamat_lengkap' => 'Jl. Diponegoro No. 1, Denpasar Selatan',
                'kecamatan_id' => $kecamatanMap['DENPASAR SELATAN'] ?? null,
                'kode_pos' => '80114',
            ],
            [
                'kode' => '',
                'nama' => 'Cabang Kuta',
                'latitude' => -8.7183,
                'longitude' => 115.1686,
                'radius_geofence' => 100,
                'alamat_lengkap' => 'Jl. Raya Kuta No. 45, Kuta Utara',
                'kecamatan_id' => $kecamatanMap['KUTA UTARA'] ?? null,
                'kode_pos' => '80361',
            ],
            [
                'kode' => '',
                'nama' => 'Cabang Jakarta',
                'latitude' => -6.1966,
                'longitude' => 106.8383,
                'radius_geofence' => 150,
                'alamat_lengkap' => 'Jl. Cikini Raya No. 10, Menteng',
                'kecamatan_id' => $kecamatanMap['MENTENG'] ?? null,
                'kode_pos' => '10330',
            ],
            [
                'kode' => '',
                'nama' => 'Cabang Surabaya',
                'latitude' => -7.2575,
                'longitude' => 112.7520,
                'radius_geofence' => 150,
                'alamat_lengkap' => 'Jl. Raya Surabaya No. 100, Genteng',
                'kecamatan_id' => $kecamatanMap['GENTENG'] ?? null,
                'kode_pos' => '60271',
            ],
            [
                'kode' => '',
                'nama' => 'Cabang Bandung',
                'latitude' => -6.9175,
                'longitude' => 107.6191,
                'radius_geofence' => 150,
                'alamat_lengkap' => 'Jl. Braga No. 50, Bandung Wetan',
                'kecamatan_id' => $kecamatanMap['BANDUNG WETAN'] ?? null,
                'kode_pos' => '40115',
            ],
            [
                'kode' => '',
                'nama' => 'Cabang Yogyakarta',
                'latitude' => -7.7972,
                'longitude' => 110.3688,
                'radius_geofence' => 150,
                'alamat_lengkap' => 'Jl. Malioboro No. 20, Gedongtengen',
                'kecamatan_id' => $kecamatanMap['GEDONGTENGEN'] ?? null,
                'kode_pos' => '55271',
            ],
            [
                'kode' => '',
                'nama' => 'Cabang Medan',
                'latitude' => 3.5952,
                'longitude' => 98.6722,
                'radius_geofence' => 150,
                'alamat_lengkap' => 'Jl. Merdeka No. 10, Medan Kota',
                'kecamatan_id' => $kecamatanMap['MEDAN KOTA'] ?? null,
                'kode_pos' => '20112',
            ],
        ];

        foreach ($cabang as $data) {
            \App\Models\CabangKantor::firstOrCreate(['kode' => $data['kode']], $data);
        }
    }
}
