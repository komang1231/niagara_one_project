<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class RekrutmenRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // $table->string('kode', 20)->unique();
            // $table->unsignedInteger('lowongan_id')->nullable();
            // $table->unsignedInteger('departemen_id');
            // $table->unsignedInteger('divisi_id')->nullable();
            // $table->unsignedInteger('section_id')->nullable();
            // $table->unsignedInteger('job_position_id')->nullable();
            // $table->unsignedInteger('job_level_id');    
            // $table->unsignedInteger('cabang_kantor_id');
            // $table->string('nama', 100);
            // $table->string('email', 150)->unique();
            // $table->string('no_tlp', 20)->unique();
            // $table->string('file_cv', 255);
            // $table->unsignedInteger('jenjang_pendidikan_id');
            // $table->unsignedInteger('sumber_pelamar_id');
            // $table->enum('status_rekrutmen', ['pelamar', 'screening', 'interview', 'offering', 'diterima','ditolak'])->default('pelamar');
            // $table->enum('pool_talent', ['rehire', 'blacklist'])->nullable();
            // $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');

            'kode' => 'required|string|max:20|unique:rekrutmen,kode',
            'lowongan_id' => 'nullable|integer|exists:lowongans,id',
            'departemen_id' => 'required|integer|exists:departemens,id',
            'divisi_id' => 'nullable|integer|exists:divisis,id',
            'section_id' => 'nullable|integer|exists:sections,id',
            'job_position_id' => 'nullable|integer|exists:job_positions,id',
            'job_level_id' => 'required|integer|exists:job_levels,id',
            'cabang_kantor_id' => 'required|integer|exists:cabang_kantor,id',
            'nama' => 'required|string|max:100',
            'email' => 'required|string|email|max:150|unique:rekrutmen,email',
            'no_tlp' => 'required|string|max:20|unique:rekrutmen,no_tlp',
            'file_cv' => 'required|string|max:255',
            'jenjang_pendidikan_id' => 'required|integer|exists:jenjang_pendidikan,id',
            'sumber_pelamar_id' => 'required|integer|exists:sumber_pelamars,id',
            'status_rekrutmen' => 'required|in:pelamar,screening,interview,offering,diterima,ditolak',
            'pool_talent' => 'nullable|in:rehire,blacklist',
            'status' => 'required|in:aktif,nonaktif',
        ];
    }
}
