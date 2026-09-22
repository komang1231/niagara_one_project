<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasGeneratedCode;
use Illuminate\Database\Eloquent\SoftDeletes;

class SumberPelamar extends Model {
    use HasGeneratedCode, SoftDeletes;
    protected function getCodePrefix(): string
    {
        return 'SUMBERP';
    }

    protected $table = 'sumber_pelamars';
    protected $guarded = [];

}
