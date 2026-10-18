<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Shift;

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
            'istirahat_menit' => ['required', 'integer', 'min:0'],
            'toleransi_keterlambatan' => ['required', 'integer', 'min:0'],
            'status' => ['required', 'in:0,1'],
            'warna' => ['required', 'string', 'max:10'],
        ];
    }

    public function messages(): array
    {
        return [
            'nama.required' => 'Nama shift wajib diisi.',
            'jam_masuk.required' => 'Jam masuk wajib diisi.',
            'jam_masuk.date_format' => 'Format jam masuk harus HH:MM.',
            'istirahat_menit.required' => 'Waktu istirahat wajib diisi.',
            'istirahat_menit.integer' => 'Waktu istirahat harus berupa angka.',
            'istirahat_menit.min' => 'Waktu istirahat tidak boleh kurang dari 0 menit.',
            'toleransi_keterlambatan.required' => 'Toleransi keterlambatan wajib diisi.',
            'toleransi_keterlambatan.integer' => 'Toleransi keterlambatan harus berupa angka.',
            'toleransi_keterlambatan.min' => 'Toleransi keterlambatan tidak boleh kurang dari 0 menit.',
            'status.required' => 'Status wajib dipilih.',
            'status.in' => 'Status tidak valid.',
            'warna.required' => 'Warna shift wajib diisi.',
            'warna.max' => 'Warna maksimal 10 karakter.',
        ];
    }


    protected function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if (!$this->filled('jam_masuk')) {
                return;
            }

            $waktuKerja = (int) (
                \App\Models\GeneralSetting::where('key', 'waktu_kerja')->value('value') ?? 8
            );

            $jamMasuk = \Carbon\Carbon::createFromFormat('H:i', $this->input('jam_masuk'));
            $jamPulang = $jamMasuk->copy()->addHours($waktuKerja)->format('H:i:s');

            $query = Shift::where('jam_masuk', $jamMasuk->format('H:i:s'))
                ->where('jam_pulang', $jamPulang);

            // Saat edit, kecualikan shift yang sedang diedit.
            $shift = $this->route('shift');

            if ($shift instanceof Shift) {
                $query->where('id', '!=', $shift->id);
            }

            if ($query->exists()) {
                $validator->errors()->add(
                    'jam_masuk',
                    'Kombinasi jam masuk dan jam pulang tersebut sudah digunakan oleh shift lain.'
                );
            }
        });
    }
}
