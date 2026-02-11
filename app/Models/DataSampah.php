<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataSampah extends Model
{
    use HasFactory;
    protected $table = 'data_sampah';
    protected $fillable = ['user_id',
                           'nominal',
                           'nominal_pergram',
                           'jml_sampah_perkg'];

    public function user()
    {
    return $this->belongsTo(User::class);
    }
}