<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KeluarUser extends Model
{
    use HasFactory;
    protected $table = 'keluar_users';
    protected $fillable = [
        'uang',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

