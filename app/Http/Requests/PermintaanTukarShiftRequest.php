<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PermintaanTukarShiftRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'karyawan_pengaju' => 'required|exists:karyawans,id',
            'karyawan_pengganti' => 'required|exists:karyawans,id',
            'tanggal_tujuan' => 'required|date',
            'shift_pengaju' => 'required|exists:shifts,id',
            'shift_pengganti' => 'required|exists:shifts,id',
        ];
    }
}