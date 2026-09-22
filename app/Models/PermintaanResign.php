<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasGeneratedCode;
use Illuminate\Database\Eloquent\SoftDeletes;

class PermintaanResign extends Model
{
    use HasGeneratedCode, SoftDeletes;

    protected function getCodePrefix(): string
    {
        return 'PMR';
    }

    protected $table = 'permintaan_resigns';
    protected $fillable = ['kode', 'karyawan_id', 'tanggal_efektif', 'alasan', 'approved_by', 'approved_at'];

    //
}
