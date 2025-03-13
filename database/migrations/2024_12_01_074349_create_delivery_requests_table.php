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
        Schema::create('delivery_requests', function (Blueprint $table) {
            $table->id(); // ID unik untuk Delivery Request
            $table->string('no_dr')->unique(); // Nomor unik Delivery Request
            $table->foreignId('pelanggan_id')->constrained('pelanggans')->onDelete('cascade'); // Relasi ke tabel pelanggans
            $table->decimal('total_harga', 15, 2); // Total harga
            $table->enum('status_dr', ['tertunda', 'disetujui'])->default('tertunda'); // Status DR
            $table->enum('status_po', ['tertunda', 'sudah dibuat'])->default('tertunda'); // Status Purchase Order
            $table->enum('status_invoice', ['tertunda', 'sudah dibuat'])->default('tertunda'); // Status Invoice
            $table->timestamps(); // Kolom created_at dan updated_at
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_requests');
    }
};
