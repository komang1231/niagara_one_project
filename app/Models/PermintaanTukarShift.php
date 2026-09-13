<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasGeneratedCode;

class PermintaanTukarShift extends Model {
    use HasGeneratedCode;

    protected function getCodePrefix(): string
    {
        return 'PMTS';
    }

    protected $table = 'permintaan_tukar_shifts';
    protected $fillable = ['kode', 'karyawan_pengaju', 'karyawan_pengganti', 'tanggal_tujuan', 'shift_pengaju', 'shift_pengganti', 'approved_by', 'approved_at'];

//
}
