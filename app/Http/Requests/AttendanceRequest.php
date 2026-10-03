<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AttendanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'latitude' => [
                'required',
                'numeric',
                'between:-90,90',
            ],

            'longitude' => [
                'required',
                'numeric',
                'between:-180,180',
            ],

            'keterangan' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'latitude.required' => 'Lokasi latitude wajib dikirim.',
            'latitude.numeric' => 'Latitude tidak valid.',
            'latitude.between' => 'Nilai latitude tidak valid.',

            'longitude.required' => 'Lokasi longitude wajib dikirim.',
            'longitude.numeric' => 'Longitude tidak valid.',
            'longitude.between' => 'Nilai longitude tidak valid.',

            'keterangan.string' => 'Keterangan harus berupa teks.',
            'keterangan.max' => 'Keterangan maksimal 1000 karakter.',
        ];
    }
}