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
        Schema::create('penerimaanbb', function (Blueprint $table) {
            $table->id();  
            $table->foreignId('bahan_baku_id')->constrained('bahan_bakus')->onDelete('cascade');
            $table->foreignId('purchase_order_bb_id')->constrained('purchase_order_bbs')->onDelete('cascade');
            $table->date('tanggal_terima')->nullable();
            $table->enum('status', ['1', '2', '3'])->default('1'); // Sesuai dengan database
            $table->text('catatan')->nullable();
            $table->integer('reject')->nullable(); // Tambahan kolom reject
            $table->integer('jumlah_terima')->default(0);
            $table->integer('jumlah_order');
            $table->string('lokasi_stok', 255)->nullable();
            $table->text('status_barang')->nullable(); // Tambahan kolom status_barang
            $table->string('bukti', 255)->nullable(); // Tambahan kolom bukti
            $table->timestamps();
        });
    } 

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penerimaanbb');
    }
};
