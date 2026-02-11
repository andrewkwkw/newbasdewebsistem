<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'jenis_sampah_id', 'berat', 'type', 'amount', 'description'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function jenisSampah()
    {
        return $this->belongsTo(JenisSampah::class, 'jenis_sampah_id');
    }

    public function getTotalUangAttribute()
    {
        if ($this->jenisSampah) {
            return $this->berat * $this->jenisSampah->harga_per_kg;
        }

        return $this->amount; // fallback transaksi lama
    }

    // -------------------------------------------------------------------
    // BAGIAN "booted" SAYA HAPUS.
    // KARENA LOGIKA SALDO SUDAH DITANGANI MANUAL DI CONTROLLER.
    // JIKA TIDAK DIHAPUS, SALDO AKAN TERPOTONG 2 KALI (DOUBLE DEDUCT).
    // -------------------------------------------------------------------
}