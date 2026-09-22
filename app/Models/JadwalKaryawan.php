<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JadwalKaryawan extends Model {
    use SoftDeletes;
    
    protected $table = 'jadwal_karyawans';
    protected $fillable = ['shift_id', 'karyawan_id', 'tanggal', 'status'];

//
}
