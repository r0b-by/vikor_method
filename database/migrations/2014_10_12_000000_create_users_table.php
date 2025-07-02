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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('tahun_ajaran')->nullable(); // <-- Diperbaiki
            $table->string('semester')->nullable();     // <-- Diperbaiki
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
            // Kolom status untuk persetujuan registrasi
            $table->enum('status', ['pending', 'active', 'rejected'])->default('pending');
            // Kolom untuk menyimpan ID admin yang menyetujui
            $table->unsignedBigInteger('approved_by')->nullable();
            // Kolom untuk menyimpan waktu persetujuan
            $table->timestamp('approved_at')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
