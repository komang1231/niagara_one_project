<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PermintaanCutiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'cuti_id' => [
                'required',
                'integer',
                'exists:cutis,id',
            ],

            'karyawan_id' => [
                'required',
                'integer',
                'exists:karyawans,id',
            ],

            'tanggal_mulai' => [
                'required',
                'date',
            ],

            'tanggal_selesai' => [
                'required',
                'date',
                'after_or_equal:tanggal_mulai',
            ],

            'alasan' => [
                'nullable',
                'string',
            ],

            'lampiran' => [
                'nullable',
                'string',
                'max:255',
            ],

            'pengganti_karyawan_id' => [
                'nullable',
                'integer',
                'exists:karyawans,id',
            ],

            'details' => [
                'required',
                'array',
                'min:1',
            ],

            'details.*.tanggal' => [
                'required',
                'date',
                'distinct',
            ],

            'details.*.setengah_hari' => [
                'required',
                'boolean',
            ],
        ];
    }
}