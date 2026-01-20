<?php

use Illuminate\Database\Eloquent\SoftDeletes;
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
        Schema::create('pinjaman', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('member_id')->constrained('members')->cascadeOnDelete();
            $table->string('kode_pinjaman')->unique();
            $table->enum('jenis', ['uang', 'barang']);
            $table->decimal('jumlah_pinjaman', 15, 2);
            $table->decimal('total_pinjaman', 15, 2);
            $table->date('tanggal_pinjaman');
            $table->date('tanggal_jatuh_tempo');
            $table->decimal('bunga', 5, 2)->default(0);
            $table->unsignedInteger('tenor'); // dalam bulan
            $table->enum('status', ['aktif', 'macet', 'lunas'])->default('aktif');
            $table->text('catatan')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pinjaman');
    }
};
