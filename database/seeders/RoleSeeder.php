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
            ['kode' => 'SUPER_ADMIN', 'nama' => 'Super Admin'],
            ['kode' => 'ADMIN_TENANT', 'nama' => 'Admin Tenant'],
            ['kode' => 'HR_MANAGER', 'nama' => 'HR Manager'],
            ['kode' => 'HR_STAFF', 'nama' => 'HR Staff'],
            ['kode' => 'RECRUITER', 'nama' => 'Recruiter'],
            ['kode' => 'HEAD_OF_DEPARTMENT', 'nama' => 'Head of Department'],
            ['kode' => 'EMPLOYEE', 'nama' => 'Employee'],
        ];

        foreach ($roles as $role) {
            \App\Models\Role::create($role);
        }
    }
}