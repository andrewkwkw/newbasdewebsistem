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
        Schema::table('users', function (Blueprint $table) {
            // Cek dulu biar gak error kalau kolomnya udah ada
            if (!Schema::hasColumn('users', 'qr_code')) {
                $table->string('qr_code')->nullable()->after('email');
            }
            if (!Schema::hasColumn('users', 'points')) {
                $table->integer('points')->default(0)->after('saldo');
            }
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
