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
        Schema::create('distribusi_barangs', function (Blueprint $table) {
            $table->id();
            $table->string('kode_barang')->unique();
            $table->string('nama_barang');
            
            // Stok khusus skala besar
            $table->integer('stok_pusat')->default(0); 
            $table->string('satuan_besar'); // Contoh: KARUNG, TON, BAL
            
            // Harga per satuan besar untuk faktur luar desa
            $table->decimal('harga_per_satuan', 15, 2)->default(0);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('distribusi_barangs');
    }
};
