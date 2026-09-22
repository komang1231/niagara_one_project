<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DokumenKaryawan extends Model {
    use SoftDeletes;
    protected $table = 'dokumen_karyawans';
    protected $fillable = ['karyawan_id', 'jenis_dokumen', 'file', 'tanggal_kadaluarsa', 'status'];

//
}
