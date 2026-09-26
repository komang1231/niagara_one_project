<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasGeneratedCode;
use Illuminate\Database\Eloquent\SoftDeletes;

class CabangKantor extends Model {
    use HasGeneratedCode, SoftDeletes;

    protected function getCodePrefix(): string
    {
        return 'CAB';
    }

    protected $table = 'cabang_kantors';
    protected $guarded = [];

//
}
