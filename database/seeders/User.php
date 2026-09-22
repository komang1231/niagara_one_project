<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Karyawan as KaryawanModel;
use App\Models\Role as RoleModel;

class User extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roleMap = RoleModel::whereIn('nama', [
            'Super Admin',
            'Admin Tenant',
            'HR Manager',
            'HR Staff',
            'Recruiter',
            'Head of Department',
            'Employee',
        ])->pluck('id', 'nama');

        $karyawanMap = KaryawanModel::whereIn('nik', [
            '5171012345670001',
            '5171012345670002',
            '5171012345670003',
            '5171012345670004',
            '5171012345670005',
        ])->pluck('id', 'nik');
        
        $users = [
            // KODE = 'USR_' + INISIAL DEPAN NAMA ROLE + 3 DIGIT ''no_tlp' TERAKHIR + detik+menit+jam
            [
                'kode' => '',
                'role_id' => $roleMap['Super Admin'] ?? null,
                'karyawan_id' => $karyawanMap['5171012345670001'] ?? null,
                'nama' => 'Super Admin',
                'no_tlp' => '081234567890',
                'email' => 'admin@gmail.com',
                'password' => bcrypt('1234567890'),
            ],
            [
                'kode' => '',
                'role_id' => $roleMap['Admin Tenant'] ?? null,
                'karyawan_id' => $karyawanMap['5171012345670002'] ?? null,
                'nama' => 'Admin Tenant',
                'no_tlp' => '081234567891',
                'email' => 'admin.tenant@gmail.com',
                'password' => bcrypt('1234567890'),
            ],
            [
                'kode' => '',
                'role_id' => $roleMap['HR Manager'] ?? null,
                'karyawan_id' => $karyawanMap['5171012345670003'] ?? null,
                'nama' => 'HR Manager',
                'no_tlp' => '081234567892',
                'email' => 'hr.manager@gmail.com',
                'password' => bcrypt('1234567890'),
            ],
            [
                'kode' => '',
                'role_id' => $roleMap['HR Staff'] ?? null,
                'karyawan_id' => $karyawanMap['5171012345670004'] ?? null,
                'nama' => 'HR Staff',
                'no_tlp' => '081234567893',
                'email' => 'hr.staff@gmail.com',
                'password' => bcrypt('1234567890'),
            ],
            [
                'kode' => '',
                'role_id' => $roleMap['Recruiter'] ?? null,
                'karyawan_id' => $karyawanMap['5171012345670005'] ?? null,
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
