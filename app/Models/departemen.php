<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasGeneratedCode;

class Departemen extends Model
{
    use HasGeneratedCode;
    protected function getCodePrefix(): string
    {
        return 'DEP';
    }

    protected $table = 'departemens';
    protected $guarded = [];

    //
}
