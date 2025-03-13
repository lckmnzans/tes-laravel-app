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
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id(); // ID unik untuk supplier
            $table->string('kode_supplier', 20)->unique()->nullable(); // Kode unik supplier
            $table->string('nama_perusahaan'); // Nama perusahaan supplier
            $table->text('alamat'); // Alamat perusahaan
            $table->string('negara', 100)->nullable(); // Negara asal supplier
            $table->string('contact_person'); // Nama orang yang dapat dihubungi
            $table->string('no_cp'); // Nomor telepon contact person
            $table->string('no_tlp'); // Nomor telepon perusahaan
            $table->string('npwp', 50)->nullable(); // Nomor NPWP supplier
            $table->string('email')->unique(); // Email supplier
            $table->text('catatan')->nullable(); // Catatan tambahan
            $table->timestamps(); // Kolom created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
