<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('devices', function (Blueprint $table) {
            $table->id();
            $table->string('name')->default('Smart Trash 1');
            $table->enum('status', ['idle', 'triggered', 'busy'])->default('idle'); // Status Alat
            $table->unsignedBigInteger('current_user_id')->nullable(); // Siapa yang lagi pakai
            $table->timestamps();
        });

        // Insert 1 alat default (Dummy) biar langsung bisa dipake
        DB::table('devices')->insert([
            'name' => 'Main Device',
            'status' => 'idle',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('devices');
    }
};
