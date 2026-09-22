<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasGeneratedCode;
use Illuminate\Database\Eloquent\SoftDeletes;

class KontrakKaryawan extends Model
{
    use HasGeneratedCode, SoftDeletes;

    protected function getCodePrefix(): string
    {
        return 'KTR';
    }

    protected function getCodeField(): string
    {
        return 'nomor_kontrak';
    }

    protected $table = 'kontrak_karyawans';
    protected $fillable = ['nomor_kontrak', 'karyawan_id', 'status_kepegawaian_id', 'tanggal_mulai', 'tanggal_berakhir', 'status'];

    //
}
