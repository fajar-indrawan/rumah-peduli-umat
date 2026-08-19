<?php

namespace App\Http\Requests\ChartOfAccount;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateChartOfAccountRequest extends FormRequest
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
        // mengambil instansi Chart Of Account dari route parameter
        $param = $this->route('chart_of_account') 
            ?? $this->route('chartOfAccount') 
            ?? $this->route('id') 
            ?? collect($this->route()->parameters())->first();
        $id = is_object($param) ? $param->id : $param;

        return [
            'kode' => [
                'required',
                'string',
                'max:15',
                // Mengecualikan (ignore) ID dari data yang sedang diproses agar tidak terkena aturan duplikasi dirinya sendiri.
                Rule::unique('chart_of_accounts', 'kode')->ignore($id),
            ],
            'nama' => [
                'required',
                'string',
                'max:50',
                Rule::unique('chart_of_accounts', 'nama')->ignore($id),
            ],
            'id_kategori' => [
                'required',
                'integer',
                'exists:chart_of_accounts,id', // Memastikan ID kategori ada di tabel categories
            ],
        ];
    }

    // tampilkan pesan validasi
    public function messages(): array
    {
        return [
            'kode.required' => 'Kode Chart Of Account wajib diisi.',
            'kode.unique' => 'Kode Chart Of Account wajib diisi.',
            'kode.max' => 'Kode Chart Of Account maksimal 15 karakter.',

            'nama.required' => 'Nama Chart Of Account wajib diisi.',
            'nama.unique' => 'Nama Chart Of Account sudah terdaftar.',
            'nama.max' => 'Nama Chart Of Account maksimal 50 karakter.',
            
            'id_kategori.required' => 'Kategori wajib dipilih.',
            'id_kategori.integer' => 'Format ID Kategori tidak valid.',
            'id_kategori.exists' => 'Kategori yang dipilih tidak ditemukan.',
        ];
    }
}
