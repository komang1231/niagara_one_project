<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasGeneratedCode;
use Illuminate\Database\Eloquent\SoftDeletes;

class Agama extends Model {
    use HasGeneratedCode, SoftDeletes;

    protected function getCodePrefix(): string
    {
        return 'AGM';
    }

    protected $table = 'agamas';
    protected $guarded = [];

}
