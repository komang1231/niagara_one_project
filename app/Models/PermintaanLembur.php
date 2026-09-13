<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasGeneratedCode;

class PermintaanLembur extends Model
{
    use HasGeneratedCode;

    protected function getCodePrefix(): string
    {
        return 'PML';
    }

    protected $table = 'permintaan_lemburs';
    protected $fillable = ['kode', 'karyawan_id', 'tanggal_tujuan', 'jam_mulai', 'jam_selesai', 'alasan', 'pengali', 'approved_by', 'approved_at'];

    //
}
