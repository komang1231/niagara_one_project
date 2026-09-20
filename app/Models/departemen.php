<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasGeneratedCode;

class Departemen extends Model
{
    use HasGeneratedCode, SoftDeletes;
    protected function getCodePrefix(): string
    {
        return 'DEP';
    }
    protected function getCodeField(): string
    {
        return 'kode';
    }

    protected $table = 'departemens';
    protected $guarded = [];

    //
}
