<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('jenis_sampah', function (Blueprint $table) {
            $table->integer('harga_per_poin')->default(0)->after('harga_per_kg');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jenis_sampah', function (Blueprint $table) {
            $table->dropColumn('harga_per_poin');
        });
    }
};
