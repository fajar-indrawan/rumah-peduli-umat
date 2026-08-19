<?php

namespace App\Services;

use App\Models\ChartOfAccount;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ChartOfAccountService
{
    public function getPaginatedCategories(int $perPage = 10): LengthAwarePaginator
    {
        return ChartOfAccount::with('category')->orderBy('id', 'desc')->paginate($perPage);
    }

    public function getAllChartOfAccounts(): Collection
    {
        // Mengambil semua COA beserta data relasi kategorinya
        return ChartOfAccount::with('category')->latest()->get();
    }
    
    public function storeChartOfAccount(array $data): ChartOfAccount
    {
        return ChartOfAccount::create($data);
    }
    
    public function getChartOfAccountById(int $id): ChartOfAccount
    {
        return ChartOfAccount::findOrFail($id);
    }
    
    public function updateCategory(ChartOfAccount $chartOfAccount, array $data): ChartOfAccount
    {
        $chartOfAccount->update($data);

        // Ambil data yang terupdate dari database
        return $chartOfAccount->fresh();
    }
    
    public function deleteCategory(ChartOfAccount $chartOfAccount): ChartOfAccount
    {
        $chartOfAccount->delete();
        return $chartOfAccount;
    }
}
