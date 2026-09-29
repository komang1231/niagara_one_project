<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasGeneratedCode;
use Illuminate\Database\Eloquent\SoftDeletes;

class PermintaanTukarShift extends Model {
    use HasGeneratedCode, SoftDeletes;

    protected function getCodePrefix(): string
    {
        return 'PMTS';
    }

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    protected $table = 'permintaan_tukar_shifts';
    protected $fillable = ['kode', 'karyawan_pengaju', 'karyawan_pengganti', 'tanggal_tujuan', 'shift_pengaju', 'shift_pengganti'];
    // processed_by, approved_at & rejected_at DIKELUARKAN dari fillable
}
