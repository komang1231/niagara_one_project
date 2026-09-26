<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasGeneratedCode;
use App\Models\Karyawan;
use App\Models\CabangKantor;
use App\Models\Departemen;
use App\Models\Divisi;
use App\Models\Section;
use App\Models\JobPosition;
use App\Models\JobLevel;
use App\Models\User;

class PermintaanKaryawan extends Model
{
    use HasGeneratedCode, SoftDeletes;

    protected function getCodePrefix(): string
    {
        return 'PMK';
    }

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class);
    }

    public function cabangKantor()
    {
        return $this->belongsTo(CabangKantor::class);
    }

    public function departemen()
    {
        return $this->belongsTo(Departemen::class);
    }

    public function divisi()
    {
        return $this->belongsTo(Divisi::class);
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function jobPosition()
    {
        return $this->belongsTo(JobPosition::class);
    }

    public function jobLevel()
    {
        return $this->belongsTo(JobLevel::class);
    }

    public function processedBy()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    protected $table = 'permintaan_karyawans';
    protected $casts = [
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
    ];
    protected $fillable = ['kode', 'karyawan_id', 'cabang_kantor_id', 'departemen_id', 'divisi_id', 'section_id', 'job_position_id', 'job_level_id', 'jumlah'];
    // processed_by, processed_at & rejected_at DIKELUARKAN dari fillable
}
