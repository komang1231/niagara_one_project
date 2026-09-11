<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Karyawan extends Model
{
    protected $table = 'karyawans';
    protected $fillable = ['nip', 'rekrutmen_id', 'job_position_id', 'job_level_id', 'cabang_kantor_id', 'gaji', 'nama', 'email', 'no_hp', 'nik', 'no_bpjs_ketenagakerjaan', 'no_bpjs_kesehatan', 'no_npwp', 'jenjang_pendidikan_id', 'status_kawin_id', 'agama_id', 'status_kepegawaian_id', 'bank_id', 'nama_bank', 'no_rekening'];
    // 'status' DIKELUARKAN
}
