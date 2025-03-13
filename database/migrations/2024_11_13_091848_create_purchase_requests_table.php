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
        Schema::create('purchase_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bahan_baku_id');
            $table->integer('jumlah'); // Jumlah bahan baku yang diminta
            $table->string('status')->default('pending'); // Status PR, misalnya "pending", "approved"
            $table->timestamps();
        
            $table->foreign('bahan_baku_id')->references('id')->on('bahan_bakus')->onDelete('cascade');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_requests');
    }
};
