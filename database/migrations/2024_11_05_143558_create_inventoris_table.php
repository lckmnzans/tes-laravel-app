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
        Schema::create('inventoris', function (Blueprint $table) {
            $table->id();
            $table->integer('no')->nullable();
            $table->string('nama_bahan');
            $table->string('jenis_bahan');
            $table->date('tanggal_penerimaan')->nullable();
            $table->string('supplier')->nullable();
            $table->integer('jumlah')->nullable();
            $table->integer('stok_tersedia')->nullable();
            $table->integer('safety_stock')->nullable();
            $table->decimal('harga', 10, 2);
            $table->decimal('total_harga', 15, 2)->nullable();
            $table->string('lokasi_stok')->nullable();
            $table->string('no_penerimaan')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventoris');
    }
};
