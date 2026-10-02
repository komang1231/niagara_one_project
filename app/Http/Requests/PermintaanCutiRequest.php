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

    protected function prepareForValidation(): void
    {
        if ($this->filled('tanggal_mulai')) {
            $tanggal = $this->input('tanggal_mulai');

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
                    'tanggal_mulai' => $tanggal,
                ]);
            }
        }

        if ($this->filled('tanggal_selesai')) {
            $tanggal = $this->input('tanggal_selesai');

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
                    'tanggal_selesai' => $tanggal,
                ]);
            }
        }
    }
}