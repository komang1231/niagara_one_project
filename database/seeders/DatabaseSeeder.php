<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{

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
            KaryawanSeeder::class,
            User::class,
            PermintaanKaryawanSeeder::class,
            LowonganSeeder::class,
            RekrutmenSeeder::class,
            // SaldoCutiSeeder::class,
            PermintaanCutiSeeder::class,
            PermintaanResignSeeder::class,
            PermintaanLemburSeeder::class,
            PermintaanTukarShiftSeeder::class, 
            KontrakKaryawanSeeder::class,
            JadwalKaryawanSeeder::class,
        ]);
    }
}
