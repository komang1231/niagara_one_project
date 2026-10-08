<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RekrutmenDiterimaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'role_id' => [
                'required',
                'integer',
                'exists:roles,id',
            ],

            'gaji' => [
                'required',
                'numeric',
                'min:0',
            ],

            'nik' => [
                'required',
                'digits:16',
                Rule::unique('karyawans', 'nik'),
            ],

            'no_bpjs_ketenagakerjaan' => [
                'required',
                'digits:11',
                Rule::unique('karyawans', 'no_bpjs_ketenagakerjaan'),
            ],

            'no_bpjs_kesehatan' => [
                'required',
                'digits:13',
                Rule::unique('karyawans', 'no_bpjs_kesehatan'),
            ],

            'no_npwp' => [
                'required',
                'digits:16',
                Rule::unique('karyawans', 'no_npwp'),
            ],

            'status_kawin_id' => [
                'required',
                'integer',
                'exists:status_kawins,id',
            ],

            'agama_id' => [
                'required',
                'integer',
                'exists:agamas,id',
            ],

            'status_kepegawaian_id' => [
                'required',
                'integer',
                'exists:status_kepegawaians,id',
            ],

            'bank_id' => [
                'required',
                'integer',
                'exists:banks,id',
            ],

            'nama_bank' => [
                'required',
                'string',
                'max:100',
            ],

            'no_rekening' => [
                'required',
                'string',
                'max:30',
                Rule::unique('karyawans', 'no_rekening'),
            ],

            'status' => [
                'required',
                'in:aktif,nonaktif,resign',
            ],
        ];
    }
}