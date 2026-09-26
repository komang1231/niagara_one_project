<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasGeneratedCode;
use App\Models\Lowongan;
use App\Models\Departemen;
use App\Models\Divisi;
use App\Models\Section;
use App\Models\JobPosition;
use App\Models\JobLevel;
use App\Models\CabangKantor;
use App\Models\JenjangPendidikan;
use App\Models\SumberPelamar;

class Rekrutmen extends Model
{
    use HasGeneratedCode, SoftDeletes;

    protected function getCodePrefix(): string
    {
        return 'REK';
    }

    protected function lowongan()
    {
        return $this->belongsTo(Lowongan::class, 'lowongan_id');
    }

    protected function departemen()
    {
        return $this->belongsTo(Departemen::class, 'departemen_id');
    }

    protected function divisi()
    {
        return $this->belongsTo(Divisi::class, 'divisi_id');
    }

    protected function section()
    {
        return $this->belongsTo(Section::class, 'section_id');
    }

    protected function jobPosition()
    {
        return $this->belongsTo(JobPosition::class, 'job_position_id');
    }

    protected function jobLevel()
    {
        return $this->belongsTo(JobLevel::class, 'job_level_id');
    }

    protected function cabangKantor()
    {
        return $this->belongsTo(CabangKantor::class, 'cabang_kantor_id');
    }

    protected function jenjangPendidikan()
    {
        return $this->belongsTo(JenjangPendidikan::class, 'jenjang_pendidikan_id');
    }

    protected function sumberPelamar()
    {
        return $this->belongsTo(SumberPelamar::class, 'sumber_pelamar_id');
    }

    protected $table = 'rekrutmens';
    protected $fillable = ['kode', 'lowongan_id', 'departemen_id', 'divisi_id', 'section_id', 'job_position_id', 'job_level_id', 'cabang_kantor_id', 'nama', 'email', 'no_tlp', 'file_cv', 'jenjang_pendidikan_id', 'sumber_pelamar_id', 'status_rekrutmen', 'pool_talent', 'status'];

}
