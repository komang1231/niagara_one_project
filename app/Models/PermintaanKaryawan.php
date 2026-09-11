<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PermintaanKaryawan extends Model {
    protected $table = 'permintaan_karyawans';
    protected $fillable = ['kode', 'karyawan_id', 'job_position_id', 'job_level_id', 'jumlah', 'approved_by', 'approved_at'];

//
}
