<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrashLog extends Model
{
    use HasFactory;

    // Izinkan kolom-kolom ini diisi secara massal
    protected $fillable = [
        'user_id',
        'amount',
        'points',
        'source',
    ];

    // (Opsional) Relasi balik ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}