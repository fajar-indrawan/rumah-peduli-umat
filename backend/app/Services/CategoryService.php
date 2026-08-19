<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class CategoryService
{    
    public function getPaginatedCategories(int $perPage = 10): LengthAwarePaginator
    {
        return Category::orderBy('id', 'desc')->paginate($perPage);
    }
    
    public function getAllCategories(): Collection
    {
        // dapatkan semua kategori terbaru
        return Category::latest()->get();
    }

    public function getCategoryById(int $id): Category
    {
        return Category::findOrFail($id);
    }

    public function storeCategory(array $data): Category
    {
        return Category::create($data);
    }
    
    public function updateCategory(Category $category, array $data): Category
    {
        $category->update($data);

        // Ambil data yang terupdate dari database
        return $category->fresh();
    }
    
    public function deleteCategory(Category $category): Category
    {
        $category->delete();
        return $category;
    }
}
