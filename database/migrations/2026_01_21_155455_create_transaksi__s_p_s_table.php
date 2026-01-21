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
        Schema::create('transaksi__s_p_s', function (Blueprint $table) {
            $table->id();
            $table->string('no_transaksi_sp')->unique();
            $table->date('tanggal');
            $table->foreignId('member_id')->constrained('members')->onDelete('cascade');
            $table->string('nama');
            $table->string('COA');
            $table->enum('Debit/Credit', ['Debit', 'Credit']);
            $table->decimal('Nominal', 15, 2);
            $table->text('Keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi__s_p_s');
    }
};
