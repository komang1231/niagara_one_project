<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasGeneratedCode;

class JobPosition extends Model {
    use HasGeneratedCode;
    protected function getCodePrefix(): string
    {
        return 'JOBP';
    }

    protected $table = 'job_positions';
    protected $guarded = [];

}
