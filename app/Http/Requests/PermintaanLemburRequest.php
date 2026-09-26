<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class PermintaanLemburRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'karyawan_id' => 'required|exists:karyawans,id',
            'tanggal_tujuan' => 'required|date',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i',
            'alasan' => 'required|string',
            'pengali' => 'required|numeric|min:0',
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $mulai = $this->input('jam_mulai');
            $selesai = $this->input('jam_selesai');

            if (
                !$mulai ||
                !$selesai ||
                $validator->errors()->has('jam_mulai') ||
                $validator->errors()->has('jam_selesai')
            ) {
                return;
            }

            [$jamMulai, $menitMulai] = array_map('intval', explode(':', $mulai));
            [$jamSelesai, $menitSelesai] = array_map('intval', explode(':', $selesai));

            $menitMulai = ($jamMulai * 60) + $menitMulai;
            $menitSelesai = ($jamSelesai * 60) + $menitSelesai;

            if ($menitSelesai <= $menitMulai) {
                $menitSelesai += 24 * 60;
            }

            $durasi = ($menitSelesai - $menitMulai) / 60;

            if ($durasi < 4 || $durasi > 10) {
                $validator->errors()->add(
                    'jam_selesai',
                    'Durasi lembur harus antara 4 sampai 10 jam.'
                );
            }
        });
    }
}