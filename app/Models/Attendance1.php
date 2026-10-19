<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Karyawan;
use App\Models\Shift;
use App\Models\User;

class Attendance extends Model
{
    use SoftDeletes;

    protected $table = 'attendances';

    protected $fillable = [
        'karyawan_id',
        'shift_id',
        'tanggal',
        'latitude',
        'longitude',
        'is_manual',
        'keterangan',
        'jam_masuk',
        'jam_keluar',
        'total_menit_terlambat',
        'total_menit_pulang_cepat',
        'total_menit_kerja',
        'input_by',
        'status',
    ];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'karyawan_id');
    }

    public function shift()
    {
        return $this->belongsTo(Shift::class, 'shift_id');
    }

    public function inputBy()
    {
        return $this->belongsTo(User::class, 'input_by');
    }
}