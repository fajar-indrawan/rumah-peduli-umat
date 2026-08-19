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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->string('kode_coa');

            // Foreign Key ke kolom 'kode' di 'chart_of_accounts'
            $table->foreign('kode_coa')
                  ->references('kode')
                  ->on('chart_of_accounts')
                  ->onUpdate('cascade') // Jika kode COA diubah, di tabel transaksi otomatis berubah
                  ->onDelete('restrict'); // Mencegah hapus COA jika sudah ada transaksi

            $table->string('desc')->nullable();
            $table->decimal('debit', 15, 2)->default(0); 
            $table->decimal('credit', 15, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
