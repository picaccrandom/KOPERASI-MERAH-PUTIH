<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
<<<<<<< HEAD
{
    /**
     * Run the migrations.
     */
    public function up(): void
=======
    {
        /**
         * Run the migrations.
         */
        public function up(): void
>>>>>>> main2
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
<<<<<<< HEAD
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
=======
            $table->string('username')->unique(); // Ganti email menjadi username
            $table->string('password');           // Untuk menyimpan password terenkripsi
>>>>>>> main2
            $table->rememberToken();
            $table->timestamps();
        });

<<<<<<< HEAD
=======
        // Bagian password_reset_tokens dan sessions biarkan saja apa adanya
>>>>>>> main2
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }
<<<<<<< HEAD

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
=======
>>>>>>> main2
};
