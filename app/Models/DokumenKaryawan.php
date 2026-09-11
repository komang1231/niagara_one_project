<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DokumenKaryawan extends Model {
    protected $table = 'dokumen_karyawans';
    protected $fillable = ['karyawan_id', 'jenis_dokumen', 'file', 'tanggal_kadaluarsa', 'status'];

//
}
