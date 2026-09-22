<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasGeneratedCode;
use Illuminate\Database\Eloquent\SoftDeletes;

class JenjangPendidikan extends Model {
    use HasGeneratedCode, SoftDeletes;
    protected function getCodePrefix(): string
    {
        return 'JP';
    }

    protected $table = 'jenjang_pendidikans';
    protected $guarded = [];

}
