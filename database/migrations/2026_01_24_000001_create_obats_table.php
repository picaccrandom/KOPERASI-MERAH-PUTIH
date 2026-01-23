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
        Schema::create('obats', function (Blueprint $table) {
            $table->id();
            // Identitas Obat
            $table->string('kode_obat')->unique();
            $table->string('nama_obat');
            $table->string('kategori')->nullable(); // Misal: Antibiotik, Vitamin, Analgetik
            
            // Manajemen Stok Terintegrasi
            $table->integer('stok_gudang')->default(0); // Stok Induk di Gudang Apotek
            $table->integer('stok_apotek')->default(0); // Stok Retail di Apotek
            $table->integer('min_stok')->default(10);   // Batas peringatan stok menipis
            
            // Keuangan & Satuan
            $table->decimal('harga_beli', 15, 2)->default(0); // Untuk hitung laba
            $table->decimal('harga_jual', 15, 2);
            $table->string('satuan'); // Tablet, Botol, Strip, Pcs
            
            // Keamanan Medis
            $table->date('tgl_kadaluwarsa')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('obats');
    }
};