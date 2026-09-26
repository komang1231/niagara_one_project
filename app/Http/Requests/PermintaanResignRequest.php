<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PermintaanResignRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'karyawan_id' => 'required|exists:karyawans,id',
            'tanggal_efektif' => 'required|date|after_or_equal:today',
            'alasan' => 'required|string|max:255',
        ];
    }
}