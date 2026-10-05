<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class KontrakKaryawanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'karyawan_id' => [
                'required',
                'integer',
                'exists:karyawans,id',
            ],

            'status_kepegawaian_id' => [
                'required',
                'integer',
                'exists:status_kepegawaians,id',
            ],

            'tanggal_mulai' => [
                'required',
                'date',
            ],

            'tanggal_berakhir' => [
                'nullable',
                'date',
                'after:tanggal_mulai',
            ],

            'status' => [
                'nullable',
                'in:aktif,nonaktif',
            ],
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->filled('tanggal_mulai')) {
            $tanggalMulai = $this->input('tanggal_mulai');

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
                preg_match(
                    '/^(\d{1,2}) ([A-Za-z]+) (\d{4})$/',
                    $tanggalMulai,
                    $match
                )
                && isset($bulan[$match[2]])
            ) {
                $tanggalMulai = sprintf(
                    '%04d-%02d-%02d',
                    $match[3],
                    $bulan[$match[2]],
                    $match[1]
                );
            }

            $this->merge([
                'tanggal_mulai' => $tanggalMulai,
            ]);
        }
    }
}