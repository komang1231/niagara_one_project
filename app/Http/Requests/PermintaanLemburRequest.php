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

            'tanggal_tujuan' => 'required|date',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i',
            'alasan' => 'required|string',
            // 'pengali' => 'required|numeric|min:0',
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

    protected function prepareForValidation(): void
    {
        if ($this->filled('tanggal_tujuan')) {
            $tanggal = $this->input('tanggal_tujuan');

            $bulan = [
                'Januari' => '01',
                'Februari' => '02',
                'Maret' => '03',
                'April' => '04',
                'Mei' => '05',
                'Juni' => '06',
                'Juli' => '07',
                'Agustus' => '08',
                'September' => '09',
                'Oktober' => '10',
                'November' => '11',
                'Desember' => '12',
            ];

            if (
                preg_match('/^(\d{1,2}) ([A-Za-z]+) (\d{4})$/', $tanggal, $match)
                && isset($bulan[$match[2]])
            ) {

                $tanggal = sprintf(
                    '%04d-%02d-%02d',
                    $match[3],
                    $bulan[$match[2]],
                    $match[1]
                );

                $this->merge([
                    'tanggal_tujuan' => $tanggal,
                ]);
            }
        } else {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'tanggal_tujuan' => 'Format tanggal tidak valid.',
            ]);
        }
    }
}
