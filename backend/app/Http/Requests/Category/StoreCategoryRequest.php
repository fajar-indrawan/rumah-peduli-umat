<?php

namespace App\Http\Requests\Category;

use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryRequest extends FormRequest
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
            'nama' => ['required', 'string', 'max:50', 'unique:categories,nama'],
            'tipe' => ['required', 'string', 'max:50'],
        ];
    }
    
    // tampilkan pesan validasi
    public function messages(): array
    {
        return [
            'nama.required' => 'Nama kategori wajib diisi.',
            'nama.unique' => 'Nama kategori sudah terdaftar.',
            'nama.max' => 'Nama kategori maksimal 50 karakter.',

            'tipe.required' => 'Tipe kategori wajib diisi.',
            'tipe.max' => 'Tipe kategori maksimal 50 karakter.',
        ];
    }
}
