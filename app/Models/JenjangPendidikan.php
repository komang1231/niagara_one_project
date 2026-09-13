<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasGeneratedCode;

class JenjangPendidikan extends Model {
    use HasGeneratedCode;
    protected function getCodePrefix(): string
    {
        return 'JP';
    }

    protected $table = 'jenjang_pendidikans';
    protected $guarded = [];

}
