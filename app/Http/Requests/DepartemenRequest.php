<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DepartemenRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // $id = $this->route('departemen')?->id;

        return [
            'nama' => 'required|string|max:100',
            // Terima nilai dari UI (switch) sebagai 0/1, mapping dilakukan di controller
            'status' => 'required|in:0,1',
        ];
    }
}