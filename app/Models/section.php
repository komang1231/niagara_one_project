<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasGeneratedCode;
use App\Models\Divisi;

class Section extends Model
{
    use HasGeneratedCode;

    protected function getCodePrefix(): string
    {
        return 'SEC';
    }

    protected $table = 'sections';
    protected $guarded = [];

    public function divisi()
    {
        return $this->belongsTo(Divisi::class, 'divisi_id');
    }
}