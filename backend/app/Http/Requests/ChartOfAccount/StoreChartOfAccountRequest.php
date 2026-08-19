<?php

namespace App\Http\Requests\ChartOfAccount;

use Illuminate\Foundation\Http\FormRequest;

class StoreChartOfAccountRequest extends FormRequest
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
            'kode' => ['required', 'string', 'max:15', 'unique:chart_of_accounts,kode'],
            'nama' => ['required', 'string', 'max:50', 'unique:chart_of_accounts,nama'],
        ];
    }
    
    // tampilkan pesan validasi
    public function messages(): array
    {
        return [
            'kode.required' => 'Kode Chart Of Account wajib diisi.',
            'kode.unique' => 'Kode Chart Of Account sudah terdaftar.',
            'kode.max' => 'Kode Chart Of Account maksimal 50 karakter.',

            'nama.required' => 'Nama Chart Of Account wajib diisi.',
            'nama.unique' => 'Nama Chart Of Account sudah terdaftar.',
            'nama.max' => 'Nama Chart Of Account maksimal 50 karakter.',
        ];
    }
}
