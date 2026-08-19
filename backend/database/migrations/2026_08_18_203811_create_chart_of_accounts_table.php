<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('chart_of_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->unique(); // 1. Tambahkan unique()
            $table->string('nama');
            
            // Gunakan foreignId agar tipe data sesuai (unsignedBigInteger)
            // 'constrained' menunjuk ke tabel 'categories'
            // 'onDelete('restrict')' mencegah hapus kategori jika masih ada akun terkait
            $table->foreignId('id_kategori')
                  ->default(1)
                  ->constrained('categories')
                  ->onDelete('restrict');
                  
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chart_of_accounts');
    }
};
