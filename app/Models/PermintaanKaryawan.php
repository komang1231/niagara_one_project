<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasGeneratedCode;
use Illuminate\Database\Eloquent\SoftDeletes;

class PermintaanKaryawan extends Model {
    use HasGeneratedCode, SoftDeletes;

    protected function getCodePrefix(): string
    {
        return 'PMK';
    }

    protected $table = 'permintaan_karyawans';
    protected $fillable = ['kode', 'karyawan_id', 'job_position_id', 'job_level_id', 'jumlah', 'approved_by', 'approved_at'];

//
}
