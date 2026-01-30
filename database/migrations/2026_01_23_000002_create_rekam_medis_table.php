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
        Schema::create('rekam_medis', function (Blueprint $table) {
            $table->id();
            // Kolom pendaftaran_id wajib ada sebagai relasi ke Riswan/Iriana
            $table->foreignId('pendaftaran_id')->constrained('pendaftaran_kliniks')->onDelete('cascade');
            $table->text('diagnosa');
            $table->text('tindakan');
            $table->text('resep_obat')->nullable();
            $table->enum('status_resep', ['diproses', 'selesai'])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rekam_medis');
    }
};
