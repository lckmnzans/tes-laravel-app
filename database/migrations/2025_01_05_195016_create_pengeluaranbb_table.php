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
        Schema::create('pengeluaranbb', function (Blueprint $table) {
            $table->id(); // ID unik
            $table->foreignId('production_schedule_id')
                  ->constrained('production_schedules')
                  ->onDelete('cascade'); // Relasi ke production_schedules
            $table->string('kode_sjm', 50)->unique(); // Kode unik SJM
            $table->date('tanggal_pengeluaran'); // Tanggal Pengeluaran
            $table->string('keterangan', 255)->nullable(); // Keterangan tambahan
            $table->timestamps(); // created_at dan updated_at
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengeluaranbb');
    }
};
