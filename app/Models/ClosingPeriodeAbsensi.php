<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClosingPeriodeAbsensi extends Model {
    protected $table = 'closing_periode_absensis';
    protected $fillable = ['kode', 'bulan', 'tahun', 'closed_by', 'status'];

//
}
