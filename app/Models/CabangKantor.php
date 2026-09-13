<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasGeneratedCode;

class CabangKantor extends Model {
    use HasGeneratedCode;

    protected function getCodePrefix(): string
    {
        return 'CAB';
    }

    protected $table = 'cabang_kantor';
    protected $guarded = [];

//
}
