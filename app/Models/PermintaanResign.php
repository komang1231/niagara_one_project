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

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    protected $table = 'permintaan_resigns';
    protected $fillable = ['kode', 'karyawan_id', 'tanggal_efektif', 'alasan'];
    // processed_by, approved_at & rejected_at DIKELUARKAN dari fillable
}
