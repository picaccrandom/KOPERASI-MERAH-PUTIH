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
        Schema::create('bon_details', function (Blueprint $table) {
            $table->id();
            $table->string('no_transaksi_sp');
            $table->foreign('no_transaksi_sp')->references('no_transaksi_sp')->on('transaksi__s_p_s')->cascadeOnDelete();
            $table->enum('status',['belum', 'lunas'])->default('belum');;
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bon_details');
    }
};
