<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasGeneratedCode;

class KotaKabupaten extends Model {
    use HasGeneratedCode;

    protected function getCodePrefix(): string
    {
        return 'KKAB';
    }

    protected $table = 'kota_kabupatens';
    protected $guarded = [];

}
