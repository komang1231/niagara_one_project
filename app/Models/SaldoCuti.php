<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasGeneratedCode;
use App\Models\Cuti;
use App\Models\Karyawan;

class SaldoCuti extends Model {
    use SoftDeletes, HasGeneratedCode;

    protected function getCodePrefix(): string
    {
        return 'SLDCUTI';
    }

    protected function cuti()
    {
        return $this->belongsTo(Cuti::class, 'cuti_id');
    }

    protected function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'karyawan_id');
    }
    
    protected $table = 'saldo_cutis';
    protected $fillable = ['cuti_id', 'karyawan_id', 'tahun', 'saldo', 'terpakai'];
}