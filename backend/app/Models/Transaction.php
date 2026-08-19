<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    /** @use HasFactory<\Database\Factories\TransactionFactory> */
    use HasFactory;
    
    protected $fillable = [
        'tanggal',
        'kode_coa',
        'desc',
        'debit',
        'credit',
    ];
    
    // relasi ke chart of account
    public function chartOfAccount(): BelongsTo
    {
        // Parameter: (TargetModel, foreign_key_transaction, fk)
        return $this->belongsTo(ChartOfAccount::class, 'kode_coa', 'kode');
    }
}
