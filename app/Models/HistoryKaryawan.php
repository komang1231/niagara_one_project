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

class HistoryKaryawan extends Model
{
    use SoftDeletes;

    protected function getCodePrefix(): string
    {
        return 'SK';
    }
    protected function getCodeField(): string
    {
        return 'kode';
    }

    protected $table = 'history_karyawans';

    protected $fillable = [
        'kode',
        'file_sk',
        'karyawan_id',

        'cabang_lama',
        'cabang_baru',

        'departemen_lama',
        'departemen_baru',

        'divisi_lama',
        'divisi_baru',

        'section_lama',
        'section_baru',

        'posisi_lama',
        'posisi_baru',

        'level_lama',
        'level_baru',

        'jenis_perubahan',
        'tanggal_efektif',
        'status',
    ];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'karyawan_id');
    }

    public function cabangLama()
    {
        return $this->belongsTo(CabangKantor::class, 'cabang_lama');
    }

    public function cabangBaru()
    {
        return $this->belongsTo(CabangKantor::class, 'cabang_baru');
    }

    public function departemenLama()
    {
        return $this->belongsTo(Departemen::class, 'departemen_lama');
    }

    public function departemenBaru()
    {
        return $this->belongsTo(Departemen::class, 'departemen_baru');
    }

    public function divisiLama()
    {
        return $this->belongsTo(Divisi::class, 'divisi_lama');
    }

    public function divisiBaru()
    {
        return $this->belongsTo(Divisi::class, 'divisi_baru');
    }

    public function sectionLama()
    {
        return $this->belongsTo(Section::class, 'section_lama');
    }

    public function sectionBaru()
    {
        return $this->belongsTo(Section::class, 'section_baru');
    }

    public function posisiLama()
    {
        return $this->belongsTo(JobPosition::class, 'posisi_lama');
    }

    public function posisiBaru()
    {
        return $this->belongsTo(JobPosition::class, 'posisi_baru');
    }

    public function levelLama()
    {
        return $this->belongsTo(JobLevel::class, 'level_lama');
    }

    public function levelBaru()
    {
        return $this->belongsTo(JobLevel::class, 'level_baru');
    }
}