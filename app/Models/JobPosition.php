<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasGeneratedCode;
use App\Models\Section;

class JobPosition extends Model
{
    use HasGeneratedCode, SoftDeletes;
    protected function getCodePrefix(): string
    {
        return 'JOBP';
    }
    protected function getCodeField(): string
    {
        return 'kode';
    }

    protected $table = 'job_positions';
    protected $guarded = [];

    public function section()
    {
        return $this->belongsTo(Section::class, 'section_id');
    }
}
