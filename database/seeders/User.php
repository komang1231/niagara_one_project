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
            'Head of Departemen',
            'Employee',
        ])->pluck('id', 'nama');

        $karyawanMap = KaryawanModel::whereIn('nik', [
            '5171012345670001',
            '5171012345670002',
            '5171012345670003',
            '5171012345670004',
            '5171012345670005',
            '5171012345670006',
            '5171012345670007',
        ])->pluck('id', 'nik');
        
        $users = [
            // KODE = 'USR_' + INISIAL DEPAN NAMA ROLE + 3 DIGIT ''no_tlp' TERAKHIR + detik+menit+jam
            [
                'kode' => '',
                'role_id' => $roleMap['Super Admin'] ?? null,
                'karyawan_id' => $karyawanMap['5171012345670001'] ?? null,
                'nama' => $karyawanMap['5171012345670001'] ? KaryawanModel::find($karyawanMap['5171012345670001'])->nama : 'Super Admin',
                'no_tlp' => $karyawanMap['5171012345670001'] ? KaryawanModel::find($karyawanMap['5171012345670001'])->no_tlp : '081234567890',
                'email' => 'admin@gmail.com',
                'password' => bcrypt('1234567890'),
            ],
            [
                'kode' => '',
                'role_id' => $roleMap['Admin Tenant'] ?? null,
                'karyawan_id' => $karyawanMap['5171012345670002'] ?? null,
                'nama' => $karyawanMap['5171012345670002'] ? KaryawanModel::find($karyawanMap['5171012345670002'])->nama : 'Admin Tenant',
                'no_tlp' => $karyawanMap['5171012345670002'] ? KaryawanModel::find($karyawanMap['5171012345670002'])->no_tlp : '081234567891',
                'email' => 'admin.tenant@gmail.com',
                'password' => bcrypt('1234567890'),
            ],
            [
                'kode' => '',
                'role_id' => $roleMap['HR Manager'] ?? null,
                'karyawan_id' => $karyawanMap['5171012345670003'] ?? null,
                'nama' => $karyawanMap['5171012345670003'] ? KaryawanModel::find($karyawanMap['5171012345670003'])->nama : 'HR Manager',
                'no_tlp' => $karyawanMap['5171012345670003'] ? KaryawanModel::find($karyawanMap['5171012345670003'])->no_tlp : '081234567892',
                'email' => 'hr.manager@gmail.com',
                'password' => bcrypt('1234567890'),
            ],
            [
                'kode' => '',
                'role_id' => $roleMap['HR Staff'] ?? null,
                'karyawan_id' => $karyawanMap['5171012345670004'] ?? null,
                'nama' => $karyawanMap['5171012345670004'] ? KaryawanModel::find($karyawanMap['5171012345670004'])->nama : 'HR Staff',
                'no_tlp' => $karyawanMap['5171012345670004'] ? KaryawanModel::find($karyawanMap['5171012345670004'])->no_tlp : '081234567893',
                'email' => 'hr.staff@gmail.com',
                'password' => bcrypt('1234567890'),
            ],
            [
                'kode' => '',
                'role_id' => $roleMap['Recruiter'] ?? null,
                'karyawan_id' => $karyawanMap['5171012345670005'] ?? null,
                'nama' => $karyawanMap['5171012345670005'] ? KaryawanModel::find($karyawanMap['5171012345670005'])->nama : 'Recruiter',
                'no_tlp' => $karyawanMap['5171012345670005'] ? KaryawanModel::find($karyawanMap['5171012345670005'])->no_tlp : '081234567894',
                'email' => 'recruiter@gmail.com',
                'password' => bcrypt('1234567890'),
            ],
            [
                'kode' => '',
                'role_id' => $roleMap['Head of Departemen'] ?? null,
                'karyawan_id' => $karyawanMap['5171012345670006'] ?? null,
                'nama' => $karyawanMap['5171012345670006'] ? KaryawanModel::find($karyawanMap['5171012345670006'])->nama : 'Head of Departemen',
                'no_tlp' => $karyawanMap['5171012345670006'] ? KaryawanModel::find($karyawanMap['5171012345670006'])->no_tlp : '081234567895',
                'email' => 'head.departemen@gmail.com',
                'password' => bcrypt('1234567890'),
            ],
            [
                'kode' => '',
                'role_id' => $roleMap['Employee'] ?? null,
                'karyawan_id' => $karyawanMap['5171012345670007'] ?? null,
                'nama' => $karyawanMap['5171012345670007'] ? KaryawanModel::find($karyawanMap['5171012345670007'])->nama : 'Employee',
                'no_tlp' => $karyawanMap['5171012345670007'] ? KaryawanModel::find($karyawanMap['5171012345670007'])->no_tlp : '081234567896',
                'email' => 'employee@gmail.com',
                'password' => bcrypt('1234567890'),
            ]
        ];

        foreach ($users as $user) {
            \App\Models\User::create($user);
        }
    }
}
