<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class HariLiburRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if ($this->filled('tanggal')) {
            try {
                $this->merge([
                    'tanggal' => \Carbon\Carbon::parse($this->tanggal)->format('Y-m-d'),
                ]);
            } catch (\Exception $e) {
                // Biarkan validasi 'date' menangani tanggal yang tidak valid
            }
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nama' => 'required|string|max:100',
            'tanggal' => 'required|date',
            // Terima nilai dari UI (switch) sebagai 0/1, mapping dilakukan di controller
            'status' => 'required|in:0,1',
        ];
    }
}
