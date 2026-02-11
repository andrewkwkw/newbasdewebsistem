<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('masuk_users', function (Blueprint $table) {
            // Tambah kolom jml_sampah_perkg
            if (!Schema::hasColumn('masuk_users', 'jml_sampah_perkg')) {
                $table->decimal('jml_sampah_perkg', 10, 2)->after('jenis_sampah_id');
            }

            // Tambah kolom admin_id
            if (!Schema::hasColumn('masuk_users', 'admin_id')) {
                $table->unsignedBigInteger('admin_id')->nullable()->after('uang');
            }
        });
    }

    public function down(): void
    {
        Schema::table('masuk_users', function (Blueprint $table) {
            if (Schema::hasColumn('masuk_users', 'jml_sampah_perkg')) {
                $table->dropColumn('jml_sampah_perkg');
            }

            if (Schema::hasColumn('masuk_users', 'admin_id')) {
                $table->dropColumn('admin_id');
            }
        });
    }
};