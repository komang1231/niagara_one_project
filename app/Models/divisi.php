<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasGeneratedCode;
use App\Models\Departemen;

class Divisi extends Model
{
    use HasGeneratedCode;

    protected function getCodePrefix(): string
    {
        return 'DIV';
    }

    protected $table = 'divisis';
    protected $guarded = [];

    public function departemen()
    {
        return $this->belongsTo(Departemen::class, 'departemen_id');
    }
}