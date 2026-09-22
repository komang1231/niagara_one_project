<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SaldoCuti extends Model {
    use SoftDeletes;
    
    protected $table = 'saldo_cutis';
    protected $fillable = ['cuti_id', 'karyawan_id', 'tahun', 'saldo', 'terpakai'];

//
}
