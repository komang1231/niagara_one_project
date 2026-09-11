<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KontrakKaryawan extends Model {
    protected $table = 'kontrak_karyawans';
    protected $fillable = ['nomor_kontrak', 'karyawan_id', 'status_kepegawaian_id', 'tanggal_mulai', 'tanggal_berakhir', 'status'];

//
}
