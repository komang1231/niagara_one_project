<?php

namespace Database\Seeders;

use App\Models\User;
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
            JobPositionSeeder::class,
            DepartemenSeeder::class,
            DivisiSeeder::class,
            SectionSeeder::class,
            ProvinsiSeeder::class,
            KotaKabupatenSeeder::class,
            KecamatanSeeder::class,
            CabangKantorSeeder::class,
            HariLiburSeeder::class,
        ]);

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }
}
