<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up()
{
    Schema::create('jenis_sampah', function (Blueprint $table) {
        $table->engine = 'InnoDB';
        $table->id();
        $table->unsignedBigInteger('admin_id')->nullable();
        $table->string('nama_sampah');
        $table->string('harga_per_kg');
        $table->timestamps();
    });
}

public function down()
{
    Schema::dropIfExists('jenis_sampah');
}

};
