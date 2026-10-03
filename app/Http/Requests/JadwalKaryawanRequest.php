<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class JadwalKaryawanRequest extends FormRequest
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
                'array',
                'min:1',
            ],

            'karyawan_id.*' => [
                'required',
                'exists:karyawans,id',
                Rule::exists('karyawans', 'id')->where(function ($query) {
                    $query->where('status', 'aktif');
                }),
            ],

            'jadwal' => [
                'required',
                'array',
                'min:1',
            ],

            'jadwal.*.shift_id' => [
                'required',
                'exists:shifts,id',
            ],

            'jadwal.*.tanggal' => [
                'required',
                'date',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'karyawan_id.required' => 'Karyawan wajib dipilih.',
            'karyawan_id.array' => 'Data karyawan tidak valid.',
            'karyawan_id.min' => 'Minimal pilih satu karyawan.',

            'karyawan_id.*.required' => 'Karyawan wajib dipilih.',
            'karyawan_id.*.exists' => 'Karyawan tidak ditemukan atau tidak aktif.',

            'jadwal.required' => 'Jadwal wajib diisi.',
            'jadwal.array' => 'Data jadwal tidak valid.',
            'jadwal.min' => 'Minimal tambahkan satu jadwal.',

            'jadwal.*.shift_id.required' => 'Shift wajib dipilih.',
            'jadwal.*.shift_id.exists' => 'Shift tidak ditemukan.',

            'jadwal.*.tanggal.required' => 'Tanggal wajib diisi.',
            'jadwal.*.tanggal.date' => 'Format tanggal tidak valid.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $jadwal = $this->input('jadwal', []);

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

        foreach ($jadwal as $index => $item) {
            $tanggal = $item['tanggal'] ?? null;

            if (!$tanggal) {
                continue;
            }

            if (
                preg_match('/^(\d{1,2}) ([A-Za-z]+) (\d{4})$/', $tanggal, $match)
                && isset($bulan[$match[2]])
            ) {
                $jadwal[$index]['tanggal'] = sprintf(
                    '%04d-%02d-%02d',
                    $match[3],
                    $bulan[$match[2]],
                    $match[1]
                );
            }
        }

        $this->merge([
            'jadwal' => $jadwal,
        ]);
    }
}
