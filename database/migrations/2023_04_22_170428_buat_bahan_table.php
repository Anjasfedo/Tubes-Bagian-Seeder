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
        Schema::create('bahan', function (Blueprint $table) {
            $table->increments('id_bahan');
            $table->unsignedInteger('id_jenis');
            $table->string('kode_bahan');
            $table->string('nama_bahan')->unique();
            $table->integer('harga_beli');
            $table->tinyInteger('diskon')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bahan');
    }
};
