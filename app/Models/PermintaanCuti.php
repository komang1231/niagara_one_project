<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasGeneratedCode;
use Illuminate\Database\Eloquent\SoftDeletes;

class PermintaanCuti extends Model
{
    use HasGeneratedCode, SoftDeletes;

    protected function getCodePrefix(): string
    {
        return 'PMC';
    }

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    public function cuti()
    {
        return $this->belongsTo(Cuti::class);
    }

    public function details()
    {
        return $this->hasMany(PermintaanCutiDetail::class, 'permintaan_cuti_id', 'id');
    }

    protected $table = 'permintaan_cutis';
    protected $fillable = [
        'kode',
        'cuti_id',
        'karyawan_id',
        'tanggal_mulai',
        'tanggal_selesai',
        'alasan',
        'lampiran',
        'pengganti_karyawan_id',
        'processed_by',
        'processed_at',
        'approved_at',
        'rejected_at',
    ];
    // processed_by, approved_at & rejected_at DIKELUARKAN dari fillable

}
