<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['nama' => 'Salary', 'tipe' => 'income'],
            ['nama' => 'Other Income', 'tipe' => 'income'],
            ['nama' => 'Family Expense', 'tipe' => 'expense'],
            ['nama' => 'Transport Expense', 'tipe' => 'expense'],
            ['nama' => 'Meal Expense', 'tipe' => 'expense'],
        ];

        foreach ($categories as $category) {
            // updateOrCreate akan meng-update jika nama sudah ada, atau membuat baru jika belum ada
            Category::updateOrCreate(
                ['nama' => $category['nama']], // Kriteria pencarian
                ['tipe' => $category['tipe']] // Data yang diisi/diperbarui
            );
        }
    }
}
