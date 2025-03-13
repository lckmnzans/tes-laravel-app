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
        Schema::create('pelanggans', function (Blueprint $table) {
            $table->id();
            $table->integer('no')->nullable(); // Untuk nomor pelanggan
            $table->string('nama_customer'); // Nama pelanggan
            $table->text('alamat'); // Alamat pelanggan
            $table->string('no_hp'); // Nomor telepon pelanggan
            $table->string('email')->unique(); // Email pelanggan
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pelanggans');
    }
};
