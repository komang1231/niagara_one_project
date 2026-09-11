<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PermintaanCutiDetail extends Model {
    protected $table = 'permintaan_cuti_details';
    protected $fillable = ['permintaan_cuti_id', 'tanggal', 'setengah_hari'];

public function permintaanCuti()
    {
        return $this->belongsTo(PermintaanCuti::class, 'permintaan_cuti_id');
    }
}
