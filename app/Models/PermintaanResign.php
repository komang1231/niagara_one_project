<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PermintaanResign extends Model {
    protected $table = 'permintaan_resigns';
    protected $fillable = ['kode', 'karyawan_id', 'tanggal_efektif', 'alasan', 'approved_by', 'approved_at'];

//
}
