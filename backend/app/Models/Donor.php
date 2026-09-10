<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donor extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'telepon',
        'email',
        'alamat',
    ];

    public function donations()
    {
        return $this->hasMany(Donation::class);
    }
}
