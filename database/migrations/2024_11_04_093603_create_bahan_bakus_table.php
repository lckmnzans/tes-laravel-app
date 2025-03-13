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
        Schema::create('bahan_bakus', function (Blueprint $table) {
            $table->id();
            $table->integer('no')->nullable();
            $table->string('kodeBahan')->unique(); // Kode bahan, harus unik
            $table->string('namaBahan'); // Nama bahan
            $table->integer('stokBahan'); // Stok bahan
            $table->integer('stok_minimum')->default(100); // Batas stok minimum, contoh default 10
            $table->integer('hargaBahan'); // Harga bahan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bahan_bakus');
    }
};
