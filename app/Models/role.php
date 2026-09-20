<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasGeneratedCode;

class Role extends Model
{
    use HasGeneratedCode, SoftDeletes;
    protected function getCodePrefix(): string
    {
        return 'ROLE';
    }
    protected function getCodeField(): string
    {
        return 'kode';
    }

    protected $table = 'roles';
    protected $guarded = [];
}
