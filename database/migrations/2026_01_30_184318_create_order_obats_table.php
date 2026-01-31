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
        Schema::create('order_obats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pendaftaran_klinik_id')->constrained('pendaftaran_kliniks')->onDelete('cascade');
            $table->date('tanggal_order');
            $table->foreignId('member_id')->constrained('members')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->text('order_body');
            $table->decimal('nominal', 15, 2);
            $table->enum('status', ['selesai', 'belum'])->default('belum');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_obats');
    }
};
