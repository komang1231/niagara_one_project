<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasGeneratedCode;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Karyawan;

class PermintaanTukarShift extends Model {
    use HasGeneratedCode, SoftDeletes;

    protected function getCodePrefix(): string
    {
        return 'PMTS';
    }

    public function karyawanPengaju()
    {
        return $this->belongsTo(Karyawan::class, 'karyawan_pengaju');
    }

    public function karyawanPengganti()
    {
        return $this->belongsTo(Karyawan::class, 'karyawan_pengganti');
    }

    public function shiftPengaju()
    {
        return $this->belongsTo(Shift::class, 'shift_pengaju');
    }

    public function shiftPengganti()
    {
        return $this->belongsTo(Shift::class, 'shift_pengganti');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    protected $table = 'permintaan_tukar_shifts';
    protected $fillable = ['kode', 'karyawan_pengaju', 'karyawan_pengganti', 'tanggal_tujuan', 'shift_pengaju', 'shift_pengganti'];
    // processed_by, approved_at & rejected_at DIKELUARKAN dari fillable
}
