<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaldoCuti extends Model {
    protected $table = 'saldo_cutis';
    protected $fillable = ['cuti_id', 'karyawan_id', 'tahun', 'saldo', 'terpakai'];

//
}
