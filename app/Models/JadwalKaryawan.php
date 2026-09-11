<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalKaryawan extends Model {
    protected $table = 'jadwal_karyawans';
    protected $fillable = ['shift_id', 'karyawan_id', 'tanggal', 'status'];

//
}
