<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PermintaanCutiDetail extends Model {
    use SoftDeletes;
    
    protected $table = 'permintaan_cuti_details';
    protected $fillable = ['permintaan_cuti_id', 'tanggal', 'setengah_hari'];

public function permintaanCuti()
    {
        return $this->belongsTo(PermintaanCuti::class, 'permintaan_cuti_id');
    }
}
