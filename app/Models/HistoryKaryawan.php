<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasGeneratedCode;

class HistoryKaryawan extends Model
{
    use HasGeneratedCode;

    protected function getCodePrefix(): string
    {
        return 'SK';
    }

    protected function getCodeField(): string
    {
        return 'nomor_sk';
    }

    protected $table = 'history_karyawans';
    protected $fillable = ['nomor_sk', 'file_sk', 'karyawan_id', 'level_lama', 'level_baru', 'posisi_lama', 'posisi_baru', 'cabang_lama', 'cabang_baru', 'jenis_perubahan', 'tanggal_efektif', 'status'];

    //
}
