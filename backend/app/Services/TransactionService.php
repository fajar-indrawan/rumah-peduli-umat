<?php

namespace App\Services;

use App\Models\Transaction;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
// use Illuminate\Support\Facades\Log;

class TransactionService
{
    public function getPaginatedCategories(int $perPage = 10): LengthAwarePaginator
    {
        return Transaction::with('chartOfAccount')->orderBy('id', 'desc')->paginate($perPage);
    }

    public function getAllTransactions(): Collection
    {
        // dapatkan semua kategori terbaru
        return Transaction::with('chartOfAccount')->latest()->get();
    }
    
    public function storeTransaction(array $data): Transaction
    {
        // Log::info('$data', $data);
        // // $data {"tanggal":"2024-01-02","desc":"Peralatan Rumah","kode_coa":"402","debit":1,"credit":1}
        // // $kode_coa = $data["kode_coa"];
        // $kode_coa = data_get($data, 'kode_coa');
        // $arr = json_decode($data, true);
        // $kode_coa = $arr['kode_coa']; // Hasil: "402"
            
        // $kategori = Transaction::with('chartOfAccount')
        // ->whereHas('chartOfAccount', function ($query) use ($kode_coa) {
        //     $query->where('kode_coa', $kode_coa);
        // })
        // ->get();
        // Log::info('$kategori', $kategori);


        // // if () {
        // // income
        // // }
        // // // expense

        return Transaction::create($data);
    }
    
    public function getTransactionById(int $id): Transaction
    {
        return Transaction::findOrFail($id);
    }
    
    public function updateTransaction(Transaction $transaction, array $data): Transaction
    {
        $transaction->update($data);

        // Ambil data yang terupdate dari database
        return $transaction->fresh();
    }

    public function deleteTransaction(Transaction $transaction): Transaction
    {
        $transaction->delete();
        return $transaction;
    }
}
