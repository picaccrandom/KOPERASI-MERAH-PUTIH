<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

   public function up(): void
    {
        Schema::create('pendaftaran_kliniks', function (Blueprint $table) {
            $table->id();
            $table->string('no_registrasi')->unique();
            $table->foreignId('member_id')->constrained('members')->onDelete('cascade');
            $table->string('keluhan');
            $table->string('tensi', 20)->nullable();
            $table->decimal('biaya_daftar', 15, 2)->default(50000);
            $table->enum('status', ['antri', 'diperiksa', 'selesai'])->default('antri');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pendaftaran_kliniks');
    }
};
