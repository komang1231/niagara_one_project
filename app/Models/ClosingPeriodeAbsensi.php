<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClosingPeriodeAbsensi extends Model
{
    use SoftDeletes;

    protected $table = 'closing_periode_absensis';

    protected $fillable = [
        'bulan',
        'tahun',
        'closed_by',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'closed_by');
    }
}