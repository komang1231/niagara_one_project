<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasGeneratedCode;

class StatusKepegawaian extends Model
{
    use HasGeneratedCode, SoftDeletes;
    protected function getCodePrefix(): string
    {
        return 'SKP';
    }
    protected function getCodeField(): string
    {
        return 'kode';
    }

    protected $table = 'status_kepegawaians';
    protected $guarded = [];
}
