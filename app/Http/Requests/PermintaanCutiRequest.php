<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\PermintaanCuti;
use Illuminate\Validation\Rule;

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
                'required',
                'file',
                'mimes:pdf,doc,docx',
                'max:20480',
            ],

            'pengganti_karyawan_id' => [
                'nullable',
                'integer',
                'exists:karyawans,id',
            ],

            'details' => [
                Rule::requiredIf(function () {
                    $cuti = \App\Models\Cuti::find($this->input('cuti_id'));

                    return !$cuti
                        || strtolower(trim($cuti->nama)) !== 'cuti tahunan';
                }),
                'array',
                'min:1',
            ],

            'details.*.tanggal' => [
                'required',
                'date',
                'distinct',
            ],

            'details.*.setengah_hari' => [
                Rule::requiredIf(function () {
                    $cuti = \App\Models\Cuti::find($this->input('cuti_id'));

                    return !$cuti
                        || strtolower(trim($cuti->nama)) !== 'cuti tahunan';
                }),
                'boolean',
            ],
        ];
    }

    protected function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if ($validator->errors()->isNotEmpty()) {
                return;
            }

            $karyawanId = auth()->user()?->karyawan_id;

            if (!$karyawanId) {
                return;
            }

            $tanggalMulai = $this->input('tanggal_mulai');
            $tanggalSelesai = $this->input('tanggal_selesai');

            if (!$tanggalMulai || !$tanggalSelesai) {
                return;
            }

            $bentrok = PermintaanCuti::query()
                ->where('karyawan_id', $karyawanId)
                ->whereNull('rejected_at')
                ->where(function ($query) use ($tanggalMulai, $tanggalSelesai) {
                    $query
                        ->whereDate('tanggal_mulai', '<=', $tanggalSelesai)
                        ->whereDate('tanggal_selesai', '>=', $tanggalMulai);
                })
                ->exists();

            if ($bentrok) {
                $validator->errors()->add(
                    'tanggal_mulai',
                    'Tanggal cuti yang diajukan bentrok dengan permintaan cuti lain yang masih aktif atau sedang menunggu approval.'
                );
            }
        });
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
