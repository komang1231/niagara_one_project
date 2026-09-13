<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasGeneratedCode;

class Agama extends Model {
    use HasGeneratedCode;

    protected function getCodePrefix(): string
    {
        return 'AGM';
    }

    protected $table = 'agamas';
    protected $guarded = [];

}
