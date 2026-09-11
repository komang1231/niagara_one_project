<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PermintaanLembur extends Model {
    protected $table = 'permintaan_lemburs';
    protected $fillable = ['kode', 'karyawan_id', 'tanggal_tujuan', 'jam_mulai', 'jam_selesai', 'alasan', 'pengali', 'approved_by', 'approved_at'];

//
}
