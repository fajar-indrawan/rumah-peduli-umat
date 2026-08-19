<?php

namespace App\Http\Requests\Transaction;

use Illuminate\Foundation\Http\FormRequest;

class StoreTransactionRequest extends FormRequest
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
            'tanggal' => ['required', 'date'],
            'desc' => ['nullable', 'string', 'max:225'],
            'kode_coa' => ['required', 'exists:chart_of_accounts,kode'], // Memastikan kode_coa yang diinput benar-benar ada di tabel COA.
            'debit' => ['required_without:credit', 'numeric', 'min:0'], // Memastikan setidaknya salah satu antara debit atau credit diisi.
            'credit' => ['required_without:debit', 'numeric', 'min:0'],
        ];
    }

    // tampilkan pesan validasi
    public function messages(): array
    {
        return [
            'tanggal.required' => 'Tanggal wajib diisi.',
            'date.date' => 'Format tanggal tidak valid.',

            'kode_coa.required' => 'Kode coa wajib dipilih.',
            'kode_coa.exists' => 'Kode coa yang dipilih tidak terdaftar di sistem.',
            
            'desc.string' => 'Deskripsi harus berupa teks.',
            'desc.max' => 'Deskripsi tidak boleh melebihi 255 karakter.',
            
            'debit.required_without' => 'Nominal debit wajib diisi jika credit kosong.',
            'debit.numeric' => 'Nominal debit harus berupa angka.',
            'debit.min' => 'Nominal debit tidak boleh kurang dari 0.',
            
            'credit.required_without' => 'Nominal credit wajib diisi jika debit kosong.',
            'credit.numeric' => 'Nominal credit harus berupa angka.',
            'credit.min' => 'Nominal credit tidak boleh kurang dari 0.',
        ];
    }
}
