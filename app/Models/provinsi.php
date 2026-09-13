<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasGeneratedCode;

class Provinsi extends Model {
    use HasGeneratedCode;

    protected function getCodePrefix(): string
    {
        return 'PROV';
    }

    protected $table = 'provinsis';
    protected $guarded = [];

}
