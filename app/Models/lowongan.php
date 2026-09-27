<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasGeneratedCode;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\PermintaanKaryawan;
use App\Models\CabangKantor;
use App\Models\Departemen;
use App\Models\Divisi;
use App\Models\Section;
use App\Models\JobPosition;
use App\Models\JobLevel;

class Lowongan extends Model
{
    use HasGeneratedCode, SoftDeletes;

    protected function getCodePrefix(): string
    {
        return 'LOW';
    }

    public function permintaanKaryawan()
    {
        return $this->belongsTo(PermintaanKaryawan::class, 'permintaan_karyawan_id');
    }

    public function cabangKantor()
    {
        return $this->belongsTo(CabangKantor::class, 'cabang_kantor_id');
    }

    public function departemen()
    {
        return $this->belongsTo(Departemen::class, 'departemen_id');
    }

    public function divisi()
    {
        return $this->belongsTo(Divisi::class, 'divisi_id');
    }

    public function section()
    {
        return $this->belongsTo(Section::class, 'section_id');
    }

    public function jobPosition()
    {
        return $this->belongsTo(JobPosition::class, 'job_position_id');
    }

    public function jobLevel()
    {
        return $this->belongsTo(JobLevel::class, 'job_level_id');
    }


    
    protected $table = 'lowongans';
    protected $fillable = ['kode', 'judul', 'permintaan_karyawan_id', 'cabang_kantor_id', 'departemen_id', 'divisi_id', 'section_id', 'job_position_id', 'job_level_id', 'kuota', 'kualifikasi', 'deskripsi', 'min_gaji', 'max_gaji', 'tanggal_buka', 'tanggal_tutup', 'status'];
}