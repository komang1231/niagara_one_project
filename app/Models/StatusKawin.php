<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasGeneratedCode;

class StatusKawin extends Model {
    use HasGeneratedCode;
    protected function getCodePrefix(): string
    {
        return 'SKAWIN';
    }

    protected $table = 'status_kawins';
    protected $guarded = [];

}
