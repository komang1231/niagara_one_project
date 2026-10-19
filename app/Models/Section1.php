<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasGeneratedCode;
use App\Models\Divisi;

class Section extends Model
{
    use HasGeneratedCode, SoftDeletes;

    protected function getCodePrefix(): string
    {
        return 'SEC';
    }
    protected function getCodeField(): string
    {
        return 'kode';
    }

    protected $table = 'sections';
    protected $guarded = [];

    public function divisi()
    {
        return $this->belongsTo(Divisi::class, 'divisi_id');
    }

    public function jobPosition()
    {
        return $this->hasMany(JobPosition::class, 'section_id');
    }
}
