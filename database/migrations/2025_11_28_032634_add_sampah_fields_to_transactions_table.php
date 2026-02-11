<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            // tambah kolom jika belum ada
            if (!Schema::hasColumn('transactions', 'jenis_sampah_id')) {
                $table->unsignedBigInteger('jenis_sampah_id')->nullable()->after('user_id');
            }

            if (!Schema::hasColumn('transactions', 'berat')) {
                $table->decimal('berat', 8, 2)->nullable()->after('jenis_sampah_id');
            }

            // relasi ke jenis_sampah
            $table->foreign('jenis_sampah_id')
                  ->references('id')
                  ->on('jenis_sampah')
                  ->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('transactions', function (Blueprint $table) {
            if (Schema::hasColumn('transactions', 'jenis_sampah_id')) {
                $table->dropForeign(['jenis_sampah_id']);
                $table->dropColumn('jenis_sampah_id');
            }

            if (Schema::hasColumn('transactions', 'berat')) {
                $table->dropColumn('berat');
            }
        });
    }
};