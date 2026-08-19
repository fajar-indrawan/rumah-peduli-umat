<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\ChartOfAccount;
use Illuminate\Database\Seeder;

class ChartOfAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $chartOfAccounts = [
        //     ['kode' => '401', 'nama' => 'Gaji Karyawan'],
        //     ['kode' => '402', 'nama' => 'Gaji Ketua MPR'],
        //     ['kode' => '403', 'nama' => 'Profit Trading'],
        //     ['kode' => '601', 'nama' => 'Biaya Sekolah'],
        //     ['kode' => '602', 'nama' => 'Bensin'],
        //     ['kode' => '603', 'nama' => 'Parkir'],
        //     ['kode' => '604', 'nama' => 'Makan Siang'],
        //     ['kode' => '605', 'nama' => 'Makanan Pokok Bulanan'],
        // ];
        
        // foreach ($chartOfAccounts as $chartOfAccount) {
        //     // updateOrCreate akan meng-update jika nama sudah ada, atau membuat baru jika belum ada
        //     ChartOfAccount::updateOrCreate(
        //         ['kode' => $chartOfAccount['kode']], // Kriteria pencarian
        //         ['nama' => $chartOfAccount['nama']] // Data yang diisi/diperbarui
        //     );
        // }

        $chartOfAccounts = [
            ['kode' => '401', 'nama' => 'Gaji Karyawan', 'kategori' => 'Salary'],
            ['kode' => '402', 'nama' => 'Gaji Ketua MPR', 'kategori' => 'Salary'],
            ['kode' => '403', 'nama' => 'Profit Trading', 'kategori' => 'Other Income'],
            ['kode' => '601', 'nama' => 'Biaya Sekolah', 'kategori' => 'Family Expense'],
            ['kode' => '602', 'nama' => 'Bensin', 'kategori' => 'Transport Expense'],
            ['kode' => '603', 'nama' => 'Parkir', 'kategori' => 'Transport Expense'],
            ['kode' => '604', 'nama' => 'Makan Siang', 'kategori' => 'Meal Expense'],
            ['kode' => '605', 'nama' => 'Makanan Pokok Bulanan', 'kategori' => 'Meal Expense'],
        ];

        foreach ($chartOfAccounts as $item) {
            // Cari ID Kategori berdasarkan nama kategorinya
            $category = Category::where('nama', $item['kategori'])->first();

            if ($category) {
                ChartOfAccount::updateOrCreate(
                    ['kode' => $item['kode']],
                    [
                        'nama'        => $item['nama'],
                        'id_kategori' => $category->id, // << PENTING: Masukkan id_kategori
                    ]
                );
            }
        }
    }
}
