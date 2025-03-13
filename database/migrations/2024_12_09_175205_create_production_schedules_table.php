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
        Schema::create('production_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_order_id')->constrained('purchase_orders')->onDelete('cascade');
            $table->string('kode')->nullable();
            $table->date('schedule_date');
            $table->date('expected_finish_date');
            $table->datetime('prep_materials_completed_at')->nullable();
            $table->datetime('production_completed_at')->nullable();
            $table->timestamp('packaging_completed_at')->nullable();
            $table->timestamp('quality_control_completed_at')->nullable();
            $table->timestamp('shipping_completed_at')->nullable();
            $table->string('sjm_document')->nullable();
            $table->string('document')->nullable();

            // Enum proses berdasarkan data di database (1-6 dan 0)
            $table->enum('proses', ['1', '2', '3', '4', '5', '6', '0'])->default('1'); 
            $table->enum('statusProduksi', ['belum', 'sudah', 'selesai'])->default('belum'); 

            $table->integer('quantity_to_produce');
            $table->integer('produced_quantity')->default(0);
            $table->integer('waste_quantity')->default(0);
            $table->text('deskription')->nullable();

            $table->datetime('target_prep_materials')->nullable();
            $table->datetime('target_production')->nullable();
            $table->datetime('target_packaging')->nullable();
            $table->datetime('target_quality_control')->nullable();
            $table->datetime('target_shipping')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('production_schedules');
    }
};
