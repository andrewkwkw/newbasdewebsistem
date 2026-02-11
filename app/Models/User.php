<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'username',
        'email',
        'password',
        'no_telpon',
        'tempat',
        'tanggal_lahir',
        'fullname',
        'name',      // Tambahan: standar Laravel biasanya pakai 'name'
        'role',
        'approve',
        'admin_id',
        'saldo',
        'qr_code',   // Agar bisa simpan ID kartu/QR
        'points'     // Agar bisa simpan poin sampah
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed'
    ];

    protected $dates = ['tanggal_lahir'];

    // 🔹 Relasi ke admin
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    // 🔹 Relasi ke users yang dikelola admin
    public function handledUsers()
    {
        return $this->hasMany(User::class, 'admin_id');
    }

    public function getTotalSaldoAttribute()
    {
        return $this->transactions->sum(fn($t) => $t->total_uang);
    }

    // 🔹 Relasi ke transaksi (utama)
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'user_id');
    }

    // Relasi lain
    public function masukUsers()
    {
        return $this->hasMany(MasukUser::class);
    }

    public function keluarUsers()
    {
        return $this->hasMany(KeluarUser::class);
    }

    public function dataSampah()
    {
        return $this->hasMany(DataSampah::class, 'user_id');
    }
}