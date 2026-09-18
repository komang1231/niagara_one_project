<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Divisi;
use App\Models\Section;

class KaryawanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nip' => [
                'required',
                'string',
                'max:20',
                Rule::unique('karyawans', 'nip')->ignore($this->karyawan),
            ],

            'rekrutmen_id' => [
                'nullable',
                'exists:rekrutmens,id',
            ],

            'lowongan_id' => [
                'nullable',
                'exists:lowongans,id',
            ],

            'departemen_id' => [
                'required',
                'exists:departemens,id',
            ],

            'divisi_id' => [
                'nullable',
                'exists:divisis,id',
            ],

            'section_id' => [
                'nullable',
                'exists:sections,id',
            ],

            'job_position_id' => [
                'nullable',
                'exists:job_positions,id',
            ],

            'job_level_id' => [
                'required',
                'exists:job_levels,id',
            ],

            'cabang_kantor_id' => [
                'required',
                'exists:cabang_kantor,id',
            ],

            'gaji' => [
                'required',
                'numeric',
                'min:0',
            ],

            'nama' => [
                'required',
                'string',
                'max:100',
            ],

            'email' => [
                'required',
                'email',
                'max:150',
                Rule::unique('karyawans', 'email')->ignore($this->karyawan),
            ],

            'no_tlp' => [
                'required',
                'string',
                'max:20',
                Rule::unique('karyawans', 'no_tlp')->ignore($this->karyawan),
            ],

            'nik' => [
                'required',
                'digits:16',
                Rule::unique('karyawans', 'nik')->ignore($this->karyawan),
            ],

            'no_bpjs_ketenagakerjaan' => [
                'required',
                'digits:11',
                Rule::unique('karyawans', 'no_bpjs_ketenagakerjaan')->ignore($this->karyawan),
            ],

            'no_bpjs_kesehatan' => [
                'required',
                'digits:13',
                Rule::unique('karyawans', 'no_bpjs_kesehatan')->ignore($this->karyawan),
            ],

            'no_npwp' => [
                'required',
                'digits:16',
                Rule::unique('karyawans', 'no_npwp')->ignore($this->karyawan),
            ],

            'jenjang_pendidikan_id' => [
                'required',
                'exists:jenjang_pendidikans,id',
            ],

            'status_kawin_id' => [
                'required',
                'exists:status_kawins,id',
            ],

            'agama_id' => [
                'required',
                'exists:agamas,id',
            ],

            'status_kepegawaian_id' => [
                'required',
                'exists:status_kepegawaians,id',
            ],

            'bank_id' => [
                'required',
                'exists:banks,id',
            ],

            'nama_bank' => [
                'required',
                'string',
                'max:100',
            ],

            'no_rekening' => [
                'required',
                'string',
                'max:30',
                Rule::unique('karyawans', 'no_rekening')->ignore($this->karyawan),
            ],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->filled('divisi_id')) {
                $divisi = Divisi::find($this->divisi_id);

                if (!$divisi || $divisi->departemen_id != $this->departemen_id) {
                    $validator->errors()->add(
                        'divisi_id',
                        'Divisi tidak sesuai dengan departemen yang dipilih.'
                    );
                }
            }

            if ($this->filled('section_id')) {
                $section = Section::find($this->section_id);

                if (!$section || $section->divisi_id != $this->divisi_id) {
                    $validator->errors()->add(
                        'section_id',
                        'Section tidak sesuai dengan divisi yang dipilih.'
                    );
                }
            }
        });
    }
}