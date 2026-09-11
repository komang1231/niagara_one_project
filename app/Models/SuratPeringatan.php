<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuratPeringatan extends Model {
    protected $table = 'surat_peringatans';
    protected $fillable = ['kode', 'karyawan_id', 'jenis_surat', 'file', 'masa_berlaku', 'status'];

//
}
