<?php

namespace App\Services;

class CodeGenerator
{
    public static function generate(string $modelClass, string $prefix, string $field = 'kode'): string
    {
        do {
            $tanggal = now()->format('ymd');
            $random = strtoupper(substr(str_shuffle('ABCDEFGHJKLMNPQRSTUVWXYZ23456789'), 0, 4));
            $kode = $prefix . $tanggal . '-' . $random;

            $sudahAda = $modelClass::where($field, $kode)->exists();
        } while ($sudahAda);

        return $kode;
    }
}