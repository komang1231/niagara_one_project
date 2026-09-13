<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasGeneratedCode;

class Role extends Model
{
    use HasGeneratedCode;
    protected function getCodePrefix(): string
    {
        return 'ROLE';
    }

    protected $table = 'roles';
    protected $guarded = [];
}
