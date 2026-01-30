<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void {
        // Tabel Daftar Akun (Co-A)
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->string('kode_akun')->unique(); // Contoh: 1101 (Kas), 4101 (Pendapatan Apotek)
            $table->string('nama_akun');
            $table->enum('kategori', ['Aset', 'Liabilitas', 'Ekuitas', 'Pendapatan', 'Beban']);
            $table->decimal('saldo_awal', 15, 2)->default(0);
            $table->timestamps();
        });

        // Tabel Jurnal Umum (Posting Otomatis)
        Schema::create('jurnals', function (Blueprint $table) {
            $table->id();
            $table->date('tgl_transaksi');
            $table->string('keterangan');
            $table->string('referensi'); // No Invoice dari Kasir/Apotek/SP
            $table->decimal('debit', 15, 2)->default(0);
            $table->decimal('kredit', 15, 2)->default(0);
            $table->foreignId('account_id')->constrained('accounts');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounting_tables');
    }
};
