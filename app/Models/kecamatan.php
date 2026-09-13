<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasGeneratedCode;

class Kecamatan extends Model {
    use HasGeneratedCode;

    protected function getCodePrefix(): string
    {
        return 'KEC';
    }

    protected $table = 'kecamatans';
    protected $guarded = [];

}
