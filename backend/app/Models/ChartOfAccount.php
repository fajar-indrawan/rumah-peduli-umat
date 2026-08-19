<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChartOfAccount extends Model
{
    /** @use HasFactory<\Database\Factories\ChartOfAccountFactory> */
    use HasFactory;
    
    protected $fillable = [
        'kode',
        'nama',
        'id_kategori',
    ];
    
    // relasi ke kategory
    public function category(): BelongsTo
    {
        // Parameter: (TargetModel, foreign_key_chart_of_account, primary_key)
        return $this->belongsTo(Category::class, 'id_kategori', 'id');
    }
    
    // Relasi satu COA memiliki banyak Transaksi
    public function transactions(): HasMany
    {
        // Parameter: (TargetModel, foreign_key_di_tabel_transactions, local_key_di_tabel_ini)
        return $this->hasMany(Transaction::class, 'kode_coa', 'kode');
    }
}
