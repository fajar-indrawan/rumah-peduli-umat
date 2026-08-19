<?php

namespace App\Http\Requests\Transaction;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTransactionRequest extends FormRequest
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
        $transaction = $this->route('transaction');

        return [
            'tanggal' => ['required', 'date'],
            // Memastikan kode_coa wajib ada di kolom 'kode' tabel 'chart_of_accounts'
            'kode_coa' => ['required', 'string', 'max:50', 'exists:chart_of_accounts,kode'],
            'desc' => ['nullable', 'string', 'max:225'],
            // Minimal bernilai 0, tipe angka (numeric), desimal maksimal 2 angka di belakang koma
            'debit'    => ['required', 'numeric', 'min:0', 'decimal:0,2'],
            'credit'   => ['required', 'numeric', 'min:0', 'decimal:0,2'],
        ];
    }
    
    // tampilkan pesan validasi
    public function messages(): array
    {
        return [
            'tanggal.required' => 'Tanggal wajib diisi.',
            'tanggal.date' => 'Format tanggal tidak valid (gunakan format YYYY-MM-DD).',

            'kode_coa.required' => 'Kode COA wajib diisi.',
            'kode_coa.string' => 'Kode COA harus berupa teks.',
            'kode_coa.max' => 'Kode COA maksimal 50 karakter.',
            'kode_coa.exists' => 'Kode COA yang dimasukkan tidak terdaftar di sistem.',

            'desc.string' => 'Keterangan harus berupa teks.',
            'desc.max' => 'Keterangan maksimal 255 karakter.',

            'debit.required' => 'Nilai debit wajib diisi (isi 0 jika tidak ada).',
            'debit.numeric' => 'Nilai debit harus berupa angka.',
            'debit.min' => 'Nilai debit tidak boleh bernilai negatif.',
            'debit.decimal' => 'Nilai debit maksimal memiliki 2 angka desimal di belakang koma.',

            'credit.required' => 'Nilai kredit wajib diisi (isi 0 jika tidak ada).',
            'credit.numeric' => 'Nilai kredit harus berupa angka.',
            'credit.min' => 'Nilai kredit tidak boleh bernilai negatif.',
            'credit.decimal' => 'Nilai kredit maksimal memiliki 2 angka desimal di belakang koma.',
        ];
    }
}
