<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasGeneratedCode;

class JobPosition extends Model {
    use HasGeneratedCode, SoftDeletes;
    protected function getCodePrefix(): string
    {
        return 'JOBP';
    }

    protected $table = 'job_positions';
    protected $guarded = [];

}
