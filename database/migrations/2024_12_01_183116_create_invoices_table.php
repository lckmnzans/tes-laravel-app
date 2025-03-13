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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id(); // ID unik untuk Invoice
            $table->foreignId('purchase_order_id')->constrained('purchase_orders')->onDelete('cascade'); // Relasi ke Purchase Orders
            $table->decimal('amount', 15, 2); // Jumlah total pada invoice
            $table->enum('status', ['lunas', 'belum lunas', 'termin'])->default('belum lunas'); // Status pembayaran invoice
            $table->timestamps(); // created_at dan updated_at
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
