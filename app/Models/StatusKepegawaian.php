<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasGeneratedCode;

class StatusKepegawaian extends Model {
    use HasGeneratedCode;
    protected function getCodePrefix(): string
    {
        return 'SKP';
    }

    protected $table = 'status_kepegawaians';
    protected $guarded = [];

}
