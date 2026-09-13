<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasGeneratedCode;

class Rekrutmen extends Model
{
    use HasGeneratedCode;

    protected function getCodePrefix(): string
    {
        return 'REK';
    }

    protected $table = 'rekrutmens';
    protected $fillable = ['kode', 'lowongan_id', 'job_position_id', 'job_level_id', 'cabang_kantor_id', 'nama', 'email', 'no_hp', 'file_cv', 'jenjang_pendidikan_id', 'sumber_pelamar_id', 'status_rekrutmen', 'pool_talent', 'status'];

    //
}
