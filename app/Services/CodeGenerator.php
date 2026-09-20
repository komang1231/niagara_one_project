<?php

namespace App\Services;

class CodeGenerator
{
    public static function generate(
        string $modelClass,
        string $prefix,
        string $field = 'kode'
    ): string {
        $tahun2 = now()->format('y');
        $bulan = now()->format('m');
        $hari = now()->format('d');

        // Cari kode pada tahun dan bulan yang sama,
        // termasuk data yang sudah di-soft delete.
        $likePrefix = $prefix . '-' . $tahun2 . '%';

        $lastCode = $modelClass::withTrashed()
            ->where($field, 'like', $likePrefix)
            ->whereRaw(
                "SUBSTRING($field, " . (strlen($prefix) + 4) . ", 2) = ?",
                [$bulan]
            )
            ->orderByDesc($field)
            ->value($field);

        $increment = 1;

        if ($lastCode) {
            $increment = ((int) substr($lastCode, strlen($prefix) + 3, 4)) + 1;
        }

        $incrementPadded = str_pad(
            $increment,
            4,
            '0',
            STR_PAD_LEFT
        );

        return $prefix
            . '-'
            . $tahun2
            . $incrementPadded
            . $bulan
            . $hari;
    }
}