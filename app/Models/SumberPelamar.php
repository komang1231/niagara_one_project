<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasGeneratedCode;

class SumberPelamar extends Model {
    use HasGeneratedCode;
    protected function getCodePrefix(): string
    {
        return 'SUMBERP';
    }

    protected $table = 'sumber_pelamars';
    protected $guarded = [];

}
