<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasGeneratedCode;
use App\Models\Rekrutmen;
use App\Models\Lowongan;
use App\Models\Departemen;
use App\Models\Divisi;
use App\Models\Section;
use App\Models\JobPosition;
use App\Models\JobLevel;
use App\Models\CabangKantor;
use App\Models\JenjangPendidikan;
use App\Models\StatusKawin;
use App\Models\Agama;
use App\Models\StatusKepegawaian;
use App\Models\Bank;

class Karyawan extends Model
{
    use HasGeneratedCode;

    protected function getCodePrefix(): string
    {
        return 'NIP';
    }

    protected function getCodeField(): string
    {
        return 'nip';
    }

    public function rekrutmen()
    {
        return $this->belongsTo(Rekrutmen::class, 'rekrutmen_id');
    }

    public function lowongan()
    {
        return $this->belongsTo(Lowongan::class, 'lowongan_id');
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

    public function cabangKantor()
    {
        return $this->belongsTo(CabangKantor::class, 'cabang_kantor_id');
    }

    public function jenjangPendidikan()
    {
        return $this->belongsTo(JenjangPendidikan::class, 'jenjang_pendidikan_id');
    }

    public function statusKawin()
    {
        return $this->belongsTo(StatusKawin::class, 'status_kawin_id');
    }

    public function agama()
    {
        return $this->belongsTo(Agama::class, 'agama_id');
    }

    public function statusKepegawaian()
    {
        return $this->belongsTo(StatusKepegawaian::class, 'status_kepegawaian_id');
    }

    public function bank()
    {
        return $this->belongsTo(Bank::class, 'bank_id');
    }

    protected $table = 'karyawans';
    protected $fillable = ['nip', 'rekrutmen_id', 'lowongan_id', 'departemen_id', 'divisi_id', 'section_id', 'job_position_id', 'job_level_id', 'cabang_kantor_id', 'gaji', 'nama', 'email', 'no_tlp', 'nik', 'no_bpjs_ketenagakerjaan', 'no_bpjs_kesehatan', 'no_npwp', 'jenjang_pendidikan_id', 'status_kawin_id', 'agama_id', 'status_kepegawaian_id', 'bank_id', 'nama_bank', 'no_rekening'];
    // 'status' DIKELUARKAN
}
