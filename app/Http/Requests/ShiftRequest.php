<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Carbon\Carbon;

class ShiftRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama' => ['required', 'string', 'max:100'],
            'jam_masuk' => ['required', 'date_format:H:i'],
            'jam_pulang' => ['required', 'date_format:H:i'],
            'istirahat_menit' => ['required', 'integer', 'min:0'],
            'toleransi_keterlambatan' => ['required', 'integer', 'min:0'],
            'status' => ['required', 'in:0,1'],
            'warna' => ['required', 'string', 'max:10'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if (
                !$this->filled('jam_masuk') ||
                !$this->filled('jam_pulang')
            ) {
                return;
            }

            $jamMasuk = Carbon::createFromFormat('H:i', $this->jam_masuk);
            $jamPulang = Carbon::createFromFormat('H:i', $this->jam_pulang);

            if ($jamMasuk->equalTo($jamPulang)) {
                $validator->errors()->add(
                    'jam_pulang',
                    'Jam pulang tidak boleh sama dengan jam masuk.'
                );
            }
        });
    }

    // public function messages(): array
    // {
    //     return [
    //         'nama.required' => 'Nama shift wajib diisi.',
    //         'jam_masuk.required' => 'Jam masuk wajib diisi.',
    //         'jam_masuk.date_format' => 'Format jam masuk harus HH:MM.',
    //         'jam_pulang.required' => 'Jam pulang wajib diisi.',
    //         'jam_pulang.date_format' => 'Format jam pulang harus HH:MM.',
    //         'istirahat_menit.required' => 'Waktu istirahat wajib diisi.',
    //         'istirahat_menit.integer' => 'Waktu istirahat harus berupa angka.',
    //         'istirahat_menit.min' => 'Waktu istirahat tidak boleh kurang dari 0 menit.',
    //         'toleransi_keterlambatan.required' => 'Toleransi keterlambatan wajib diisi.',
    //         'toleransi_keterlambatan.integer' => 'Toleransi keterlambatan harus berupa angka.',
    //         'toleransi_keterlambatan.min' => 'Toleransi keterlambatan tidak boleh kurang dari 0 menit.',
    //         'status.required' => 'Status wajib dipilih.',
    //         'status.in' => 'Status tidak valid.',
    //         'warna.required' => 'Warna shift wajib diisi.',
    //         'warna.max' => 'Warna maksimal 10 karakter.',
    //     ];
    // }
}