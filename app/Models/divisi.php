<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasGeneratedCode;
use App\Models\Departemen;

class Divisi extends Model
{
    use HasGeneratedCode, SoftDeletes;

    protected function getCodePrefix(): string
    {
        return 'DIV';
    }
    protected function getCodeField(): string
    {
        return 'kode';
    }

    protected $table = 'divisis';
    protected $guarded = [];

    public function departemen()
    {
        return $this->belongsTo(Departemen::class, 'departemen_id');
    }
}
