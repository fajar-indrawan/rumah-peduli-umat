<?php

namespace App\Services;

use App\Models\Transaction;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

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
