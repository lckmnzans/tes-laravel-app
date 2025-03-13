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
        Schema::create('purchase_order_bbs', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->nullable(); // Kode PO
            $table->unsignedBigInteger('supplier_id');
            $table->date('tanggal_po');
            $table->enum('status_order', ['1', '2', '3'])->default('1'); // Status enum
            $table->date('tanggal_pengiriman')->nullable();
            $table->decimal('rate', 10, 2)->nullable();
            $table->text('foot_note')->nullable();
            $table->string('no_daftar')->nullable();
            $table->string('no_aju')->nullable();
            $table->date('tanggal_daftar')->nullable();
            $table->decimal('total_amount', 15, 2)->nullable();
            $table->string('no_invoice')->nullable(); // Nomor Invoice
            $table->string('dokumen_invoice')->nullable(); 
            $table->string('surat_jalan')->unique()->nullable(); // Surat Jalan (unique)
            $table->string('dokument_sjm')->nullable(); // Dokumen Surat Jalan Masuk
            $table->string('no_pembayaran')->nullable(); // Nomor Pembayaran
            $table->timestamps();
        
            // Relasi Foreign Key
            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_order_bbs');
    }
};
