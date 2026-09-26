<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasGeneratedCode;
use Illuminate\Database\Eloquent\SoftDeletes;

class PermintaanLembur extends Model
{
    use HasGeneratedCode, SoftDeletes;

    protected function getCodePrefix(): string
    {
        return 'PML';
    }

    protected $table = 'permintaan_lemburs';
    protected $fillable = ['kode', 'karyawan_id', 'tanggal_tujuan', 'jam_mulai', 'jam_selesai', 'alasan', 'pengali'];
    // processed_by, approved_at & rejected_at DIKELUARKAN dari fillable
}
