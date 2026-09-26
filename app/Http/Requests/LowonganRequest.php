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
    public function rules(): array
    {
        return [
            // $table->string('judul', 100);
            // $table->unsignedInteger('permintaan_karyawan_id')->nullable();
            // $table->unsignedInteger('cabang_kantor_id');
            // $table->unsignedInteger('departemen_id');
            // $table->unsignedInteger('divisi_id')->nullable();
            // $table->unsignedInteger('section_id')->nullable();
            // $table->unsignedInteger('job_position_id')->nullable();
            // $table->unsignedInteger('job_level_id');
            // $table->unsignedInteger('kuota');
            // $table->text('kualifikasi');
            // $table->text('deskripsi');
            // $table->decimal('min_gaji', 20, 4);
            // $table->decimal('max_gaji', 20, 4);
            // $table->date('tanggal_buka');
            // $table->date('tanggal_tutup');
        
            'judul' => 'required|string|max:100',
            'permintaan_karyawan_id' => 'nullable|integer|exists:permintaan_karyawan,id',
            'cabang_kantor_id' => 'required|integer|exists:cabang_kantor,id',
            'departemen_id' => 'required|integer|exists:departemens,id',
            'divisi_id' => 'nullable|integer|exists:divisis,id',
            'section_id' => 'nullable|integer|exists:sections,id',
            'job_position_id' => 'nullable|integer|exists:job_positions,id',
            'job_level_id' => 'required|integer|exists:job_levels,id',
            'kuota' => 'required|integer|min:1',
            'kualifikasi' => 'required|string',
            'deskripsi' => 'required|string',
            'min_gaji' => 'required|numeric|min:0',
            'max_gaji' => 'required|numeric|min:0|gte:min_gaji',
            'tanggal_buka' => 'required|date',
            'tanggal_tutup' => 'required|date|after_or_equal:tanggal_buka',
            'status' => 'required|in:0,1',
        ];
    }
}
