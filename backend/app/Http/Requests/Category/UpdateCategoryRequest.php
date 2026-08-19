<?php

namespace App\Http\Requests\Category;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCategoryRequest extends FormRequest
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
        // mengambil instansi Category dari route parameter
        $category = $this->route('category');

        return [
            'nama' => [
                'required',
                'string',
                'max:50',
                // 2. Gunakan Rule::unique()->ignore() yang aman dari Object Model
                Rule::unique('categories', 'nama')->ignore($category),
            ],
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
