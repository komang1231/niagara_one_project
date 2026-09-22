<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClosingPeriodeAbsensi extends Model {
    use SoftDeletes;
    protected $table = 'closing_periode_absensis';
    protected $fillable = ['kode', 'bulan', 'tahun', 'closed_by', 'status'];

//
}
