<?php

namespace App\Services;

use App\Models\HistoryKaryawan;

class CodeGenerator
{
    public static function generate(
        string $modelClass,
        string $prefix,
        string $field = 'kode'
    ): string {
        /*
         * Khusus HistoryKaryawan / Nomor SK
         *
         * Format:
         * SK/DD/MM/YY/0001
         *
         * Contoh:
         * SK/17/10/26/0001
         */
        if ($modelClass === HistoryKaryawan::class) {
            $tahun2 = now()->format('y');
            $bulan = now()->format('m');
            $hari = now()->format('d');

            $lastCode = $modelClass::withTrashed()
                ->where($field, 'like', $prefix . '/%/%/%/%')
                ->whereRaw(
                    "SUBSTRING_INDEX($field, '/', -1) REGEXP '^[0-9]{4}$'"
                )
                ->orderByRaw(
                    "CAST(SUBSTRING_INDEX($field, '/', -1) AS UNSIGNED) DESC"
                )
                ->value($field);

            $increment = 1;

            if ($lastCode) {
                $increment = (
                    (int) substr(
                        $lastCode,
                        strrpos($lastCode, '/') + 1
                    )
                ) + 1;
            }

            $incrementPadded = str_pad(
                $increment,
                4,
                '0',
                STR_PAD_LEFT
            );

            return $prefix
                . '/'
                . $hari
                . '/'
                . $bulan
                . '/'
                . $tahun2
                . '/'
                . $incrementPadded;
        }

        /*
         * Generator lama
         * Format:
         * PREFIX-YYNNNNMMDD
         *
         * Contoh:
         * DEP-2600011008
         */
        $tahun2 = now()->format('y');
        $bulan = now()->format('m');
        $hari = now()->format('d');

        // Cari kode pada tahun dan bulan yang sama,
        // termasuk data yang sudah di-soft delete.
        $likePrefix = $prefix . '-' . $tahun2 . '%';

        $lastCode = $modelClass::withTrashed()
            ->where($field, 'like', $likePrefix)
            ->whereRaw(
                "SUBSTRING($field, " . (strlen($prefix) + 8) . ", 2) = ?",
                [$bulan]
            )
            ->orderByDesc($field)
            ->value($field);

        $increment = 1;

        if ($lastCode) {
            $increment = ((int) substr(
                $lastCode,
                strlen($prefix) + 3,
                4
            )) + 1;
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