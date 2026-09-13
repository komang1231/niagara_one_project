<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasGeneratedCode;

class Divisi extends Model
{
    use HasGeneratedCode;
    protected function getCodePrefix(): string
    {
        return 'DIV';
    }
    
    protected $table = 'divisis';
    protected $guarded = [];

    //
}
