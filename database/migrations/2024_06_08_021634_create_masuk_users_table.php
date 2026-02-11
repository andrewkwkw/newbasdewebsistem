<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('masuk_users', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // PERBAIKAN: Nama tabel referensi diubah menjadi 'jenis_sampah' (tanpa 's')
            $table->foreignId('jenis_sampah_id')->constrained('jenis_sampah')->onDelete('cascade');
            
            $table->integer('uang');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('masuk_users');
    }
};