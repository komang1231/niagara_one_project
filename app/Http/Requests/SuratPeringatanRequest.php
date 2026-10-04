<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SuratPeringatanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'karyawan_id' => [
                'required',
                'exists:karyawans,id',
            ],

            'jenis_surat' => [
                'required',
                Rule::in(['SP1', 'SP2', 'SP3']),
            ],

            'file' => [
                $this->isMethod('POST') ? 'required' : 'nullable',
                'file',
                'mimes:pdf,jpg,jpeg,png',
                'max:5120',
            ],

            'status' => [
                'required',
                Rule::in(['0', '1', 'aktif', 'nonaktif']),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'karyawan_id.required' => 'Karyawan wajib dipilih.',
            'karyawan_id.exists' => 'Karyawan tidak ditemukan.',

            'jenis_surat.required' => 'Jenis surat wajib dipilih.',
            'jenis_surat.in' => 'Jenis surat harus SP1, SP2, atau SP3.',

            'file.required' => 'File surat wajib diunggah.',
            'file.file' => 'File surat tidak valid.',
            'file.mimes' => 'File surat harus berupa PDF, JPG, JPEG, atau PNG.',
            'file.max' => 'Ukuran file surat maksimal 5 MB.',

            'status.required' => 'Status wajib diisi.',
            'status.in' => 'Status tidak valid.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'status' => $this->status == '1' || $this->status === 'aktif'
                ? 'aktif'
                : 'nonaktif',
        ]);
    }
}