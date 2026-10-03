<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PermintaanTukarShiftRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'karyawan_pengganti' => 'required|exists:karyawans,id',
            'tanggal_tujuan' => 'required|date',
            'shift_pengaju' => 'required|exists:shifts,id',
            'shift_pengganti' => 'required|exists:shifts,id',
        ];
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
