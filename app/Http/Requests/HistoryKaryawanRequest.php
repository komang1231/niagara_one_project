<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class HistoryKaryawanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'file_sk' => [
                'nullable',
                'file',
                'mimes:pdf',
                'max:5120',
            ],

            'karyawan_id' => [
                'required',
                'integer',
                'exists:karyawans,id',
            ],

            'cabang_lama' => [
                'nullable',
                'integer',
                'exists:cabang_kantors,id',
            ],

            'cabang_baru' => [
                'nullable',
                'integer',
                'exists:cabang_kantors,id',
            ],

            'departemen_lama' => [
                'required',
                'integer',
                'exists:departemens,id',
            ],

            'departemen_baru' => [
                'required',
                'integer',
                'exists:departemens,id',
            ],

            'divisi_lama' => [
                'required',
                'integer',
                'exists:divisis,id',
            ],

            'divisi_baru' => [
                'required',
                'integer',
                'exists:divisis,id',
            ],

            'section_lama' => [
                'required',
                'integer',
                'exists:sections,id',
            ],

            'section_baru' => [
                'required',
                'integer',
                'exists:sections,id',
            ],

            'posisi_lama' => [
                'required',
                'integer',
                'exists:job_positions,id',
            ],

            'posisi_baru' => [
                'required',
                'integer',
                'exists:job_positions,id',
            ],

            'level_lama' => [
                'required',
                'integer',
                'exists:job_levels,id',
            ],

            'level_baru' => [
                'required',
                'integer',
                'exists:job_levels,id',
            ],

            'jenis_perubahan' => [
                'required',
                Rule::in(['promosi', 'demosi', 'rotasi', 'mutasi']),
            ],

            'tanggal_efektif' => [
                'required',
                'date',
            ],

            'status' => [
                'required',
                Rule::in(['aktif', 'nonaktif']),
            ],
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->filled('tanggal_efektif')) {
            $tanggal = $this->input('tanggal_efektif');

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
                    'tanggal_efektif' => $tanggal,
                ]);
            }
        }
    }
}