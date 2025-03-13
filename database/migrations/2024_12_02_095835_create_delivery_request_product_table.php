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
        Schema::create('delivery_request_product', function (Blueprint $table) {
            $table->id(); // ID unik
            $table->foreignId('delivery_request_id')->constrained('delivery_requests')->onDelete('cascade'); // Relasi ke delivery_requests
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade'); // Relasi ke products
            $table->integer('quantity')->default(0); // Jumlah produk
            $table->decimal('total_price', 10, 2)->nullable(); // Total harga (nullable)
            $table->timestamps(); // Kolom created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_request_product');
    }
};
