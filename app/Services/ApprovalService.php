<?php

namespace App\Services;

use App\Models\User;

class ApprovalService
{
    public static function getApproverRole(string $roleName): ?string
    {
        return match ($roleName) {
            'Employee',
            'Head of Departemen',
            'Recruiter',
            'HR Staff' => 'HR Manager',

            'HR Manager' => 'Admin Tenant',

            'Admin Tenant' => 'Super Admin',

            'Super Admin' => null,

            default => null,
        };
    }

    public static function getApprovers(User $user)
    {
        $approverRole = self::getApproverRole($user->role->nama);

        if (!$approverRole) {
            return collect();
        }

        return User::whereHas('role', function ($query) use ($approverRole) {
            $query->where('nama', $approverRole);
        })->get();
    }
}