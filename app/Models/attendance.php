<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attendance extends Model {
    use SoftDeletes;
    protected $table = 'attendances';
    protected $fillable = ['karyawan_id', 'shift_id', 'tanggal', 'latitude', 'longitude', 'is_manual', 'keterangan', 'jam_masuk', 'jam_keluar', 'total_menit_terlambat', 'total_menit_pulang_cepat', 'total_menit_kerja', 'input_by', 'status'];

//
}
