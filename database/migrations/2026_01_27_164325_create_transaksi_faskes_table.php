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
        Schema::create('transaksi_faskes', function (Blueprint $table) {
            $table->id();
            $table->string('kode_transaksi')->unique();
            $table->date('tanggal');
            $table->foreignId('member_id')->constrained('members')->onDelete('cascade')->nullable();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('nama');
            $table->string('COA');
            $table->enum('status',['open','closed'])->default('open');
            $table->enum('Debit/Credit', ['Debit', 'Credit']);
            $table->decimal('Nominal', 15, 2);
            $table->text('Keterangan')->nullable();
            $table->string('kode_pendaftaran')->nullable();
            $table->foreign('kode_pendaftaran')->references('no_registrasi')->on('pendaftaran_kliniks')->onDelete('cascade')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_faskes');
    }
};
