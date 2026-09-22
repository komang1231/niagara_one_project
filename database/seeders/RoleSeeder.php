<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            ['kode' => '', 'nama' => 'Super Admin'],
            ['kode' => '', 'nama' => 'Admin Tenant'],
            ['kode' => '', 'nama' => 'HR Manager'],
            ['kode' => '', 'nama' => 'HR Staff'],
            ['kode' => '', 'nama' => 'Recruiter'],
            ['kode' => '', 'nama' => 'Head of Department'],
            ['kode' => '', 'nama' => 'Employee'],
        ];

        foreach ($roles as $role) {
            \App\Models\Role::create($role);
        }
    }
}