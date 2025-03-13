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
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->string('kode_po')->nullable(); // untuk kode PO, nullable
            $table->foreignId('delivery_request_id')->constrained('delivery_requests')->onDelete('cascade'); // Relasi ke DR
            $table->decimal('total_amount', 15, 2); // Total harga PO berdasarkan DR
            $table->enum('status', ['tertunda', 'sudah dibuat'])->default('sudah dibuat'); // Status PO (pending, approved)
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_orders');
    }
};
