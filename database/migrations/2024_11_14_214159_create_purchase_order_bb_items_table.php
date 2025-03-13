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
        Schema::create('purchase_order_bb_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('purchase_order_bb_id');
            $table->unsignedBigInteger('purchase_request_id');
            $table->unsignedBigInteger('bahan_baku_id');
            $table->integer('jumlah_order');
            $table->decimal('harga_per_unit', 10, 2);
            $table->decimal('total_harga', 15, 2);
            $table->string('kode_hs')->nullable();
            $table->text('deskripsi')->nullable();
            $table->string('satuan', 50)->nullable();
            $table->integer('jumlah_kemasan')->nullable();
            $table->string('jenis_kemasan')->nullable();
            $table->decimal('cif', 15, 2)->nullable();
            $table->timestamps();
            
            // Foreign key constraints
            $table->foreign('purchase_order_bb_id')
                ->references('id')
                ->on('purchase_order_bbs')
                ->onDelete('cascade');

            $table->foreign('purchase_request_id')
                ->references('id')
                ->on('purchase_requests')
                ->onDelete('cascade');

            $table->foreign('bahan_baku_id')
                ->references('id')
                ->on('bahan_bakus')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_order_bb_items');
    }
};
