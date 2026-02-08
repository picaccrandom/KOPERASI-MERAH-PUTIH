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
        Schema::create('simpanan_details', function (Blueprint $table) {
            $table->id();
            $table->string('no_transaksi_sp')->unique();
            $table->foreign('no_transaksi_sp')->references('no_transaksi_sp')->on('transaksi__s_p_s')->cascadeOnDelete();
            $table->date('tanggal');
            $table->decimal('saldo', 15, 2)->default(0);
            $table->decimal('biaya_admin', 15, 2)->default(0);
            $table->enum('jenis', ['pokok', 'wajib', 'sukarela']);
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('simpanan_details');
    }
};
