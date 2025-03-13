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
        Schema::create('supplier_contracts', function (Blueprint $table) {
            $table->id(); // ID unik kontrak
            $table->unsignedBigInteger('supplier_id'); // Referensi ke tabel suppliers
            $table->date('start_date'); // Tanggal mulai kontrak
            $table->date('end_date'); // Tanggal akhir kontrak
            $table->string('method')->nullable(); // Metode kontrak
            $table->enum('status', ['1', '2', '3'])->nullable(); // Status kontrak
            $table->integer('due_day')->nullable(); // Jangka waktu pembayaran
            $table->text('dokument')->nullable(); // Dokumen kontrak
            $table->string('currency', 10)->nullable(); // Mata uang
            $table->timestamps(); // Kolom created_at dan updated_at

            // Foreign key constraints
            $table->foreign('supplier_id')
                ->references('id')
                ->on('suppliers')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplier_contracts');
    }
};
