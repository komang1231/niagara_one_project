<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasGeneratedCode;

class GeneralSetting extends Model
{
    use SoftDeletes;

    protected $table = 'general_settings';
    protected $fillable = ['key', 'name', 'value'];
}
