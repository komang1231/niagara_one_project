<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasGeneratedCode;

class Cuti extends Model {
    use HasGeneratedCode;
    protected function getCodePrefix(): string
    {
        return 'CUTI';
    }

    protected $table = 'cutis';
    protected $guarded = [];

}
