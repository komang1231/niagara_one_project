<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class User extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ['kode' => 'SUPER_ADMIN', 'nama' => 'Super Admin'],
        //     ['kode' => 'ADMIN_TENANT', 'nama' => 'Admin Tenant'],
        //     ['kode' => 'HR_MANAGER', 'nama' => 'HR Manager'],
        //     ['kode' => 'HR_STAFF', 'nama' => 'HR Staff'],
        //     ['kode' => 'RECRUITER', 'nama' => 'Recruiter'],
        //     ['kode' => 'HEAD_OF_DEPARTMENT', 'nama' => 'Head of Department'],
        //     ['kode' => 'EMPLOYEE', 'nama' => 'Employee'],
        $RoleMap = \App\Models\Role::whereIn('kode', ['SUPER_ADMIN', 'ADMIN_TENANT', 'HR_MANAGER', 'HR_STAFF', 'RECRUITER', 'HEAD_OF_DEPARTMENT', 'EMPLOYEE'])
            ->pluck('id', 'kode');
        $users = [
            // KODE = 'USR_' + INISIAL DEPAN NAMA ROLE + 3 DIGIT ''no_tlp' TERAKHIR + detik+menit+jam
            [
                'kode' => 'USR_SA89010810',
                'role_id' => $RoleMap['SUPER_ADMIN'] ?? null,
                'karyawan_id' => null,
                'nama' => 'Super Admin',
                'no_tlp' => '081234567890',
                'email' => 'admin@gmail.com',
                'password' => bcrypt('1234567890'),
            ],
        ];

        foreach ($users as $user) {
            \App\Models\User::create($user);
        }
    }
}
