<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\JobPosition as JobPositionModel;
use App\Models\JobLevel as JobLevelModel;

class PermintaanKaryawanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $karyawanMap = \App\Models\Karyawan::whereIn('nip', ['NIP-2600010920', 'NIP-2600020920', 'NIP-2600030920', 'NIP-2600040920', 'NIP-2600050920'])
            ->pluck('id', 'nip');
        $jobPositionMap = JobPositionModel::whereIn('kode', ['JOBP-2600010920', 'JOBP-2600020920', 'JOBP-2600030920', 'JOBP-2600040920', 'JOBP-2600050920', 'JOBP-2600060920', 'JOBP-2600070920'])
            ->pluck('id', 'kode');
        $jobLevelMap = JobLevelModel::whereIn('kode', ['JOBL-2600010920', 'JOBL-2600020920', 'JOBL-2600030920', 'JOBL-2600040920', 'JOBL-2600050920', 'JOBL-2600060920', 'JOBL-2600070920', 'JOBL-2600080920', 'JOBL-2600090920', 'JOBL-2600100920', 'JOBL-2600110920', 'JOBL-2600120920', 'JOBL-2600130920'])
            ->pluck('id', 'kode');

        \App\Models\PermintaanKaryawan::firstOrCreate(
            [
                'kode' => 'PMK-2600010920',
                'karyawan_id' => $karyawanMap['NIP-2600010920'] ?? null,
                'job_position_id' => $jobPositionMap['JOBP-2600010920'] ?? null,
                'job_level_id' => $jobLevelMap['JOBL-2600010920'] ?? null,
                'jumlah' => 2,
            ],
            [
                'kode' => 'PMK-2600020920',
                'karyawan_id' => $karyawanMap['NIP-2600020920'] ?? null,
                'job_position_id' => $jobPositionMap['JOBP-2600020920'] ?? null,
                'job_level_id' => $jobLevelMap['JOBL-2600020920'] ?? null,
                'jumlah' => 1,
            ],
            // [
            //     'kode' => 'PMK-2600030920',
            //     'karyawan_id' => $karyawanMap['NIP-2600030920'] ?? null,
            //     'job_position_id' => $jobPositionMap['JOBP-2600030920'] ?? null,
            //     'job_level_id' => $jobLevel->id ?? null,
            //     'jumlah' => 3,
            // ],
            // [
            //     'kode' => 'PMK-2600040920',
            //     'karyawan_id' => $karyawanMap['NIP-2600040920'] ?? null,
            //     'job_position_id' => $jobPositionMap['JOBP-2600040920'] ?? null,
            //     'job_level_id' => $jobLevel->id ?? null,
            //     'jumlah' => 2,
            // ],
            // [
            //     'kode' => 'PMK-2600050920',
            //     'karyawan_id' => $karyawanMap['NIP-2600050920'] ?? null,
            //     'job_position_id' => $jobPositionMap['JOBP-2600050920'] ?? null,
            //     'job_level_id' => $jobLevel->id ?? null,
            //     'jumlah' => 1,
            // ]
        );
    }
}
