<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistoryKaryawan extends Model {
    protected $table = 'history_karyawans';
    protected $fillable = ['nomor_sk', 'file_sk', 'karyawan_id', 'level_lama', 'level_baru', 'posisi_lama', 'posisi_baru', 'cabang_lama', 'cabang_baru', 'jenis_perubahan', 'tanggal_efektif', 'status'];

//
}
