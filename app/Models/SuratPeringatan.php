<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasGeneratedCode;
use Illuminate\Database\Eloquent\SoftDeletes;

class SuratPeringatan extends Model
{
    use HasGeneratedCode, SoftDeletes;

    protected function getCodePrefix(): string
    {
        return $this->jenis_sp; // otomatis: SP1, SP2, atau SP3
    }

    protected $table = 'surat_peringatans';
    protected $fillable = ['kode', 'karyawan_id', 'jenis_surat', 'file', 'masa_berlaku', 'status'];

    //
}
