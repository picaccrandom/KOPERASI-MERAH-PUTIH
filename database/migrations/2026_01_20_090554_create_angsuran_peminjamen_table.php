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
        Schema::create('angsuran_peminjamen', function (Blueprint $table) {
            $table->id();
            $table->string('kode_pinjaman');
            $table->foreign('kode_pinjaman')->references('kode_pinjaman')->on('pinjaman')->onDelete('cascade');

            // Angsuran ke berapa
            $table->unsignedInteger('angsuran_ke');

            // Jatuh tempo & pembayaran
            $table->date('batas_bayar');
            $table->date('tanggal_bayar')->nullable();

            // Nominal
            $table->decimal('jumlah_angsuran', 15, 2); // pokok + bunga
            $table->decimal('jumlah_bayar', 15, 2)->default(0);
            $table->decimal('denda', 15, 2)->default(0);

            // Status angsuran
            $table->enum('status', ['belum', 'lunas'])->default('belum');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('angsuran_peminjamen');
    }
};
