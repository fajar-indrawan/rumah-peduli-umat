<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // // jalankan db seeder kategori        
        // $this->call([
        //     CategorySeeder::class,
        //     ChartOfAccountSeeder::class,
        //     TransactionSeeder::class,
        // ]);
        
        User::create([
            'name' => 'Admin Donasi',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password123'),
        ]);
    }
}
