<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasGeneratedCode;

class Section extends Model {
    use HasGeneratedCode;
    protected function getCodePrefix(): string
    {
        return 'SEC';
    }

    protected $table = 'sections';
    protected $guarded = [];

}
