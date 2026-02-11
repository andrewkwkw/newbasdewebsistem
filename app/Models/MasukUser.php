<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasukUser extends Model
{
    use HasFactory;

    protected $table = 'masuk_users'; // nama tabel sudah benar
    protected $fillable = [
        'user_id',
        'jenis_sampah_id',
        'jml_sampah_perkg',
        'uang',
        'admin_id',
    ];

    // 🔹 Relasi ke jenis sampah
    public function jenisSampah()
    {
        return $this->belongsTo(JenisSampah::class, 'jenis_sampah_id');
    }

    // 🔹 Relasi ke user penerima
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // opsional: relasi ke admin
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    protected static function booted()
    {
        static::created(function ($masukUser) {
            $admin = $masukUser->user->admin;
            if ($admin) {
                $admin->saldo -= $masukUser->uang;
                $admin->save();
            }
        });
    }

}