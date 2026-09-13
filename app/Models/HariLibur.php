<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasGeneratedCode;

class HariLibur extends Model
{
    use HasGeneratedCode;
    protected function getCodePrefix(): string
    {
        return 'HL';
    }

    protected $table = 'hari_liburs';
    protected $guarded = [];
}
