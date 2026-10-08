<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\JadwalKaryawan;
use Illuminate\Validation\Validator;

class PermintaanTukarShiftRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    // public function rules(): array
    // {
    //     return [
    //         'karyawan_pengganti' => 'required|exists:karyawans,id',
    //         'tanggal_tujuan' => 'required|date',
    //         'shift_pengaju' => 'required|exists:shifts,id',
    //         'shift_pengganti' => 'required|exists:shifts,id',
    //     ];
    // }
    public function rules(): array
    {
        return [
            'karyawan_pengaju' => [
                'required',
                'exists:karyawans,id',
            ],

            'karyawan_pengganti' => [
                'required',
                'exists:karyawans,id',
                'different:karyawan_pengaju',
            ],

            'tanggal_tujuan' => [
                'required',
                'date',
            ],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function (Validator $validator) {
            if ($validator->errors()->isNotEmpty()) {
                return;
            }

            $tanggal = $this->input('tanggal_tujuan');
            $karyawanPengaju = $this->input('karyawan_pengaju');
            $karyawanPengganti = $this->input('karyawan_pengganti');

            $jadwalPengaju = JadwalKaryawan::where('karyawan_id', $karyawanPengaju)
                ->whereDate('tanggal', $tanggal)
                ->where('status', 'aktif')
                ->first();

            $jadwalPengganti = JadwalKaryawan::where('karyawan_id', $karyawanPengganti)
                ->whereDate('tanggal', $tanggal)
                ->where('status', 'aktif')
                ->first();

            if (!$jadwalPengaju) {
                $validator->errors()->add(
                    'tanggal_tujuan',
                    'Karyawan pengaju tidak memiliki jadwal aktif pada tanggal tersebut.'
                );
            }

            if (!$jadwalPengganti) {
                $validator->errors()->add(
                    'karyawan_pengganti',
                    'Karyawan pengganti tidak memiliki jadwal aktif pada tanggal tersebut.'
                );
            }

            if ($jadwalPengaju && $jadwalPengganti) {
                $this->merge([
                    'shift_pengaju' => $jadwalPengganti->shift_id,
                    'shift_pengganti' => $jadwalPengaju->shift_id,
                ]);
            }
        });
    }

    protected function prepareForValidation(): void
    {
        $karyawanPengaju = auth()->user()->karyawan_id;

        $this->merge([
            'karyawan_pengaju' => $karyawanPengaju,
        ]);


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
