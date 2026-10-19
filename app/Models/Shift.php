<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasGeneratedCode;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\JadwalKaryawan;

class Shift extends Model {
    use HasGeneratedCode, SoftDeletes;
    protected function getCodePrefix(): string
    {
        return 'SHIFT';
    }

    public function jadwalKaryawan()
    {
        return $this->hasMany(JadwalKaryawan::class, 'shift_id');
    }

    protected $table = 'shifts';
    protected $guarded = [];

}
