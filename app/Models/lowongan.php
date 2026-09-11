<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lowongan extends Model {
    protected $table = 'lowongans';
    protected $fillable = ['kode', 'judul', 'permintaan_karyawan_id', 'cabang_kantor_id', 'job_position_id', 'job_level_id', 'kuota', 'kualifikasi', 'deskripsi', 'min_gaji', 'max_gaji', 'tanggal_buka', 'tanggal_tutup', 'status'];

//
}
