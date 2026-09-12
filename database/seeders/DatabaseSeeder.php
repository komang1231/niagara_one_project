<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AgamaSeeder::class,
            BankSeeder::class,
            RoleSeeder::class,
            ShiftSeeder::class,
            JenjangPendidikanSeeder::class,
            StatusKawinSeeder::class,
            StatusKepegawaianSeeder::class,
            SumberPelamarSeeder::class,
            JobLevelSeeder::class,
            DepartemenSeeder::class,
            DivisiSeeder::class,
            SectionSeeder::class,
            JobPositionSeeder::class,
            ProvinsiSeeder::class,
            KotaKabupatenSeeder::class,
            KecamatanSeeder::class,
            CabangKantorSeeder::class,
            HariLiburSeeder::class,
            CutiSeeder::class,
            SaldoCutiSeeder::class,
            LowonganSeeder::class,
            RekrutmenSeeder::class,
            KaryawanSeeder::class,
            KontrakKaryawanSeeder::class,
            JadwalKaryawanSeeder::class,
            User::class,
        ]);
    }
}
