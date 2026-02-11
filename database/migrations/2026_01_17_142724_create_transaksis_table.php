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
        Schema::create('transaksis', function (Blueprint $table) {
            $table->id();
            $table->string('kode_transaksi')->unique();
            $table->enum('kategori', ['reguler', 'member'])->default('reguler');
            $table->foreignId('member_id')->nullable()->constrained('members');
            $table->foreignId('user_id')->constrained('users');
            $table->dateTime('tgl_transaksi');
            $table->decimal('grand_total', 15, 2);

            $table->enum('tipe_pembayaran', ['tunai','bon', 'simpanan']);
            $table->enum('status', ['open','closed'])->default('closed');
            $table->decimal('total_bon', 15, 2)->default(0);
            $table->decimal('total_tunai', 15, 2)->default(0);

            // RELASI KE SIMPAN PINJAM
            // $table->foreignId('pinjam_id')->nullable()->constrained('loans');

            $table->timestamps();
            // data tidak sepenuhnya dihapus, berjaga-jaga untuk keperluan audit trail
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};
