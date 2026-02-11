<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plastik extends Model
{
    use HasFactory;

    protected $table = 'plastik';
    protected $fillable = [
        'berat',
        'nominal'
    ];

}
