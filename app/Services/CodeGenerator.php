<?php

namespace App\Services;

class CodeGenerator
{
    public static function generate(string $modelClass, string $prefix, string $field = 'kode'): string
    {
        do {
            $tanggal = now()->format('ymd');
            $random = strtoupper(substr(str_shuffle('ABCDEFGHJKLMNPQRSTUVWXYZ23456789'), 0, 4));
            $kode = $prefix . '-' . $tanggal . $random;

            $sudahAda = $modelClass::where($field, $kode)->exists();
        } while ($sudahAda);

        // ATAU

        // Rumus kedua: PREFIX + TAHUN(2) + INCREMENT + BULAN(2) + HARI(2)
        // - TAHUN: 2 digit terakhir
        // - INCREMENT: di-reset tiap bulan (hitung berdasarkan prefix+tahun+bulan)
        // - INCREMENT akan dipadded ke 4 digit
        do {
            $tahun2 = now()->format('y'); // 2 digit tahun
            $bulan = now()->format('m');
            $hari = now()->format('d');

            // hitung increment untuk prefix+tahun+bulan, agar reset tiap bulan
            $likePrefix = $prefix . '-' . $tahun2 . $bulan . '%';
            $increment = $modelClass::where($field, 'like', $likePrefix)->count() + 1;
            $incrementPadded = str_pad($increment, 4, '0', STR_PAD_LEFT);

            $kode = $prefix . '-' . $tahun2 . $incrementPadded . $bulan . $hari;

            $sudahAda = $modelClass::where($field, $kode)->exists();
        } while ($sudahAda);

        return $kode;
    }
}