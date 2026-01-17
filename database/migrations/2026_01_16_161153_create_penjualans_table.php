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
        Schema::create('penjualans', function (Blueprint $table) {
            $table->id();
            $table->string('no_invoice')->unique(); // Nomor struk belanja
            $table->foreignId('member_id')->nullable()->constrained('members'); // Relasi ke Anggota
            $table->decimal('total_harga', 15, 2); // Total belanja
            $table->enum('metode_bayar', ['Tunai', 'Bon']); // Pembeda untuk integrasi Simpan Pinjam
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penjualans');
    }
};
