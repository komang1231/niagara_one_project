<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasGeneratedCode;

class JobLevel extends Model {
    use HasGeneratedCode, SoftDeletes;
    protected function getCodePrefix(): string
    {
        return 'JOBL';
    }

    protected $table = 'job_levels';
    protected $guarded = [];

}
