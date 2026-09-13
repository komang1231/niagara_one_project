<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasGeneratedCode;

class Bank extends Model {
    use HasGeneratedCode;

    protected function getCodePrefix(): string
    {
        return 'BANK';
    }

    protected $table = 'banks';
    protected $guarded = [];

}
