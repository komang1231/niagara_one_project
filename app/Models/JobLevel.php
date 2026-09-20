<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasGeneratedCode;
use App\Models\Section;

class JobLevel extends Model
{
    use HasGeneratedCode, SoftDeletes;
    protected function getCodePrefix(): string
    {
        return 'JOBL';
    }
    protected function getCodeField(): string
    {
        return 'kode';
    }

    protected $table = 'job_levels';
    protected $guarded = [];

    public function karyawan()
    {
        return $this->hasMany(Karyawan::class, 'job_level_id');
    }
}
