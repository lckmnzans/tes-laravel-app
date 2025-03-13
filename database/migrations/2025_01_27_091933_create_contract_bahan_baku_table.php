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
        Schema::create('contract_bahan_baku', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contract_id')
                  ->constrained('supplier_contracts')
                  ->onDelete('cascade'); // Relasi ke supplier_contracts
            $table->foreignId('bahan_baku_id')
                  ->constrained('bahan_bakus')
                  ->onDelete('cascade'); // Relasi ke bahan_bakus
            $table->decimal('harga_per_unit', 15, 2); // Harga per unit bahan baku
            $table->decimal('cif', 15, 2); // Biaya CIF bahan baku
            $table->integer('min_order')->comment('Minimal order bahan baku dalam kontrak'); // Minimal order
            $table->timestamps(); // created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contract_bahan_baku');
    }
};
