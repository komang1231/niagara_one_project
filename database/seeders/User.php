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
        $RoleMap = \App\Models\Role::whereIn('kode', ['SUPER_ADMIN', 'ADMIN_TENANT', 'HR_MANAGER', 'HR_STAFF', 'RECRUITER', 'HEAD_OF_DEPARTMENT', 'EMPLOYEE'])
            ->pluck('id', 'kode');
        $KaryawanMap = \App\Models\Karyawan::whereIn('nip', ['NIP-2600010920', 'NIP-2600020920', 'NIP-2600030920', 'NIP-2600040920', 'NIP-2600050920'])
            ->pluck('id', 'nip');
        $users = [
            // KODE = 'USR_' + INISIAL DEPAN NAMA ROLE + 3 DIGIT ''no_tlp' TERAKHIR + detik+menit+jam
            [
                'kode' => 'USR-2600010920',
                'role_id' => $RoleMap['SUPER_ADMIN'] ?? null,
                'karyawan_id' => $KaryawanMap['NIP-2600010920'] ?? null,
                'nama' => 'Super Admin',
                'no_tlp' => '081234567890',
                'email' => 'admin@gmail.com',
                'password' => bcrypt('1234567890'),
            ],
            [
                'kode' => 'USR-2600020920',
                'role_id' => $RoleMap['ADMIN_TENANT'] ?? null,
                'karyawan_id' => $KaryawanMap['NIP-2600020920'] ?? null,
                'nama' => 'Admin Tenant',
                'no_tlp' => '081234567891',
                'email' => 'admin.tenant@gmail.com',
                'password' => bcrypt('1234567890'),
            ],
            [
                'kode' => 'USR-2600030920',
                'role_id' => $RoleMap['HR_MANAGER'] ?? null,
                'karyawan_id' => $KaryawanMap['NIP-2600030920'] ?? null,
                'nama' => 'HR Manager',
                'no_tlp' => '081234567892',
                'email' => 'hr.manager@gmail.com',
                'password' => bcrypt('1234567890'),
            ],
            [
                'kode' => 'USR-2600040920',
                'role_id' => $RoleMap['HR_STAFF'] ?? null,
                'karyawan_id' => $KaryawanMap['NIP-2600040920'] ?? null,
                'nama' => 'HR Staff',
                'no_tlp' => '081234567893',
                'email' => 'hr.staff@gmail.com',
                'password' => bcrypt('1234567890'),
            ],
            [
                'kode' => 'USR-2600050920',
                'role_id' => $RoleMap['RECRUITER'] ?? null,
                'karyawan_id' => $KaryawanMap['NIP-2600050920'] ?? null,
                'nama' => 'Recruiter',
                'no_tlp' => '081234567894',
                'email' => 'recruiter@gmail.com',
                'password' => bcrypt('1234567890'),
            ],
        ];

        foreach ($users as $user) {
            \App\Models\User::create($user);
        }
    }
}
