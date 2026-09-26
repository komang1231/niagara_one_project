<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PermintaanKaryawanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'karyawan_id' => ['required', 'integer', 'exists:karyawans,id'],
            'cabang_kantor_id' => ['required', 'integer', 'exists:cabang_kantors,id'],
            'departemen_id' => ['required', 'integer', 'exists:departemens,id'],
            'divisi_id' => ['nullable', 'integer', 'exists:divisis,id'],
            'section_id' => ['nullable', 'integer', 'exists:sections,id'],
            'job_position_id' => ['nullable', 'integer', 'exists:job_positions,id'],
            'job_level_id' => ['required', 'integer', 'exists:job_levels,id'],
            'jumlah' => ['required', 'integer', 'min:1'],
        ];
    }

    // public function messages(): array
    // {
    //     return [
    //         'karyawan_id.required' => 'Pemohon wajib dipilih.',
    //         'karyawan_id.exists' => 'Pemohon tidak ditemukan.',

    //         'cabang_kantor_id.required' => 'Cabang kantor wajib dipilih.',
    //         'cabang_kantor_id.exists' => 'Cabang kantor tidak ditemukan.',

    //         'departemen_id.required' => 'Departemen wajib dipilih.',
    //         'departemen_id.exists' => 'Departemen tidak ditemukan.',

    //         'divisi_id.exists' => 'Divisi tidak ditemukan.',

    //         'section_id.exists' => 'Section tidak ditemukan.',

    //         'job_position_id.exists' => 'Job position tidak ditemukan.',

    //         'job_level_id.required' => 'Job level wajib dipilih.',
    //         'job_level_id.exists' => 'Job level tidak ditemukan.',

    //         'jumlah.required' => 'Jumlah kebutuhan karyawan wajib diisi.',
    //         'jumlah.integer' => 'Jumlah kebutuhan karyawan harus berupa angka.',
    //         'jumlah.min' => 'Jumlah kebutuhan karyawan minimal 1.',
    //     ];
    // }
}