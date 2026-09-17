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

        // Cari kode DEP pada tahun yang sama
        $likePrefix = $prefix . '-' . $tahun2 . '%';

        $increment = $modelClass::where($field, 'like', $likePrefix)
            ->count() + 1;

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