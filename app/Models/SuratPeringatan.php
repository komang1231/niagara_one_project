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

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    public function getJenisSPAttribute()
    {
        return $this->attributes['jenis_surat'] ?? 'SP1'; // default ke SP1 jika tidak ada
    }

    

    protected $table = 'surat_peringatans';
    protected $fillable = ['kode', 'karyawan_id', 'jenis_surat', 'file', 'masa_berlaku', 'status'];
}
