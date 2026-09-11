<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rekrutmen extends Model {
    protected $table = 'rekrutmens';
    protected $fillable = ['kode', 'lowongan_id', 'job_position_id', 'job_level_id', 'cabang_kantor_id', 'nama', 'email', 'no_hp', 'file_cv', 'jenjang_pendidikan_id', 'sumber_pelamar_id', 'pool_talent', 'status'];

//
}
