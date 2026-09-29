<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JadwalKaryawan extends Model {
    use SoftDeletes;

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class);
    }

    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }
    
    protected $table = 'jadwal_karyawans';
    protected $fillable = ['shift_id', 'karyawan_id', 'tanggal', 'status'];

//
}
