<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasGeneratedCode;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Karyawan;
use App\Models\StatusKepegawaian;

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

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'karyawan_id');
    }

    public function statusKepegawaian()
    {
        return $this->belongsTo(
            StatusKepegawaian::class,
            'status_kepegawaian_id'
        );
    }

    protected $table = 'kontrak_karyawans';
    protected $fillable = ['nomor_kontrak', 'karyawan_id', 'status_kepegawaian_id', 'tanggal_mulai', 'tanggal_berakhir', 'status'];

    //
}
