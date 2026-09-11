<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PermintaanTukarShift extends Model {
    protected $table = 'permintaan_tukar_shifts';
    protected $fillable = ['kode', 'karyawan_pengaju', 'karyawan_pengganti', 'tanggal_tujuan', 'shift_pengaju', 'shift_pengganti', 'approved_by', 'approved_at'];

//
}
