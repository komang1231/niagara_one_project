<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class LowonganRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    protected function prepareForValidation(): void
    {
        if ($this->filled('tanggal_buka')) {
            $tanggal = $this->input('tanggal_buka');

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
                    'tanggal_buka' => $tanggal,
                ]);
            }
        }

        if ($this->filled('tanggal_tutup')) {
            $tanggal = $this->input('tanggal_tutup');

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
                    'tanggal_tutup' => $tanggal,
                ]);
            }
        }
    }

    public function rules(): array
    {
        return [
            'judul' => 'required|string|max:100',
            'permintaan_karyawan_id' => 'nullable|integer|exists:permintaan_karyawans,id',
            'cabang_kantor_id' => 'required|integer|exists:cabang_kantors,id',
            'departemen_id' => 'required|integer|exists:departemens,id',
            'divisi_id' => 'nullable|integer|exists:divisis,id',
            'section_id' => 'nullable|integer|exists:sections,id',
            'job_position_id' => 'nullable|integer|exists:job_positions,id',
            'job_level_id' => 'required|integer|exists:job_levels,id',
            'kuota' => 'required|integer|min:1|max:99',
            'kualifikasi' => 'required|string',
            'deskripsi' => 'required|string',
            'min_gaji' => 'required|numeric|min:0',
            'max_gaji' => 'required|numeric|min:0|gte:min_gaji',
            'tanggal_buka' => 'required|date',
            'tanggal_tutup' => 'required|date|after_or_equal:tanggal_buka',
            'status' => 'required|in:0,1',
        ];
    }

    public function messages(): array
    {
        return [
            'judul.required' => 'Judul lowongan harus diisi.',
            'judul.string' => 'Judul lowongan harus berupa teks.',
            'judul.max' => 'Judul lowongan tidak boleh lebih dari 100 karakter.',
            'permintaan_karyawan_id.integer' => 'Permintaan karyawan harus berupa angka.',
            'permintaan_karyawan_id.exists' => 'Permintaan karyawan yang dipilih tidak valid.',
            'cabang_kantor_id.required' => 'Cabang kantor harus dipilih.',
            'cabang_kantor_id.integer' => 'Cabang kantor harus berupa angka.',
            'cabang_kantor_id.exists' => 'Cabang kantor yang dipilih tidak valid.',
            'departemen_id.required' => 'Departemen harus dipilih.',
            'departemen_id.exists' => 'Departemen yang dipilih tidak valid.',
            // divisi, section, job position tidak wajib dipilih.
            'divisi_id.exists' => 'Divisi yang dipilih tidak valid.',
            'section_id.exists' => 'Section yang dipilih tidak valid.',
            'job_position_id.exists' => 'Posisi pekerjaan yang dipilih tidak valid.',
            'job_level_id.required' => 'Tingkat pekerjaan harus dipilih.',
            'job_level_id.exists' => 'Tingkat pekerjaan yang dipilih tidak valid.',
            // min gaji harus lebih kecil dari max gaji / max gaji harus lebih besar dari min gaji
            'min_gaji.required' => 'Gaji minimum harus diisi.',
            'min_gaji.numeric' => 'Gaji minimum harus berupa angka.',
            'min_gaji.min' => 'Gaji minimum tidak boleh kurang dari 0.',
            'max_gaji.required' => 'Gaji maksimum harus diisi.',
            'max_gaji.numeric' => 'Gaji maksimum harus berupa angka.',
            'max_gaji.min' => 'Gaji maksimum tidak boleh kurang dari 0.',
            'max_gaji.gte' => 'Gaji maksimum harus lebih besar atau sama dengan gaji minimum.',
            'kuota.required' => 'Kuota lowongan harus diisi.',
            'kuota.integer' => 'Kuota lowongan harus berupa angka.',
            'kuota.min' => 'Kuota lowongan tidak boleh kurang dari 1.',
            'kuota.max' => 'Kuota lowongan tidak boleh lebih dari 99.',
            'kualifikasi.required' => 'Kualifikasi kandidat harus diisi.',
            'kualifikasi.string' => 'Kualifikasi kandidat harus berupa teks.',
            'deskripsi.required' => 'Deskripsi pekerjaan harus diisi.',
            'deskripsi.string' => 'Deskripsi pekerjaan harus berupa teks.',
            'tanggal_buka.required' => 'Tanggal buka lowongan harus diisi.',
            'tanggal_buka.date' => 'Tanggal buka lowongan harus berupa tanggal yang valid.',
            'tanggal_tutup.required' => 'Tanggal tutup lowongan harus diisi.',
            'tanggal_tutup.date' => 'Tanggal tutup lowongan harus berupa tanggal yang valid.',
            'tanggal_tutup.after_or_equal' => 'Tanggal tutup lowongan harus sama dengan atau setelah tanggal buka lowongan.',
            'status.required' => 'Status lowongan harus dipilih.',
            'status.in' => 'Status lowongan harus berupa 0 (nonaktif) atau 1 (aktif).',
            // Tambahkan pesan kesalahan lainnya sesuai kebutuhan
        ];
    }
}
