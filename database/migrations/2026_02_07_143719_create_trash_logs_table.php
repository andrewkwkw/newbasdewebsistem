<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('trash_logs', function (Blueprint $table) {
            $table->id();
            // Relasi ke tabel users (karena user_id kita ambil dari id user yg punya QR)
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            $table->float('amount');    // Untuk total_trash
            $table->integer('points');  // Untuk points_earned
            $table->string('source')->nullable(); // Contoh: 'smart_trash_gateway'
            $table->timestamps(); // Created_at & Updated_at otomatis
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trash_logs');
    }
};
