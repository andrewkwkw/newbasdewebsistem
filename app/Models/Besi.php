<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Besi extends Model
{
    use HasFactory;
    protected $fillable = [
        'berat',
        'nominal'
    ];
}
