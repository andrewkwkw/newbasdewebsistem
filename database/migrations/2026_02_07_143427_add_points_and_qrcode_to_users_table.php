<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // Di dalam file migration
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('qr_code')->nullable()->index(); // Untuk pencarian user_id
            $table->integer('points')->default(0); // Untuk nyimpen total poin
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
