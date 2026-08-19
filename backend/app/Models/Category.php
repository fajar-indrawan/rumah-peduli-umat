<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    /** @use HasFactory<\Database\Factories\CategoryFactory> */
    use HasFactory;

    protected $fillable = [
        'nama',
        'tipe',
    ];

    // Relasi satu Kategori memiliki banyak Chart of Accounts
    public function chartOfAccounts(): HasMany
    {
        // Parameter: (TargetModel, foreign_key_di_tabel_chart_of_accounts, primary_key_tabel_ini)
        return $this->hasMany(ChartOfAccount::class, 'id_kategori', 'id');
    }
}
