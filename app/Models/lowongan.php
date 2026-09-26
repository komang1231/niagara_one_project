<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasGeneratedCode;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\PermintaanKaryawan;

class Lowongan extends Model
{
    use HasGeneratedCode, SoftDeletes;

    protected function getCodePrefix(): string
    {
        return 'LOW';
    }

    protected function permintaanKaryawan()
    {
        return $this->belongsTo(PermintaanKaryawan::class, 'permintaan_karyawan_id');
    }
    
    protected $table = 'lowongans';
    protected $fillable = ['kode', 'judul', 'permintaan_karyawan_id', 'cabang_kantor_id', 'department_id', 'divisi_id', 'section_id', 'job_position_id', 'job_level_id', 'kuota', 'kualifikasi', 'deskripsi', 'min_gaji', 'max_gaji', 'tanggal_buka', 'tanggal_tutup', 'status'];
}