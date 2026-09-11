<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PermintaanCuti extends Model {
    protected $table = 'permintaan_cutis';
    protected $fillable = ['kode', 'cuti_id', 'karyawan_id', 'tanggal_mulai', 'tanggal_selesai', 'alasan', 'lampiran', 'pengganti_karyawan_id'];
// approved_by & approved_at DIKELUARKAN dari fillable

public function details()
    {
        return $this->hasMany(PermintaanCutiDetail::class, 'permintaan_cuti_id', 'id');
    }
}
