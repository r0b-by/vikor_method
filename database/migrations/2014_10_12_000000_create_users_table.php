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

            // Menambahkan foreign key ke tabel users sendiri jika approved_by merujuk ke user lain
            // Jika approved_by merujuk ke ID user (admin) di tabel users, Anda bisa menambahkan ini:
            // $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
            // Atau jika Anda tidak ingin relasi foreign key, cukup biarkan sebagai unsignedBigInteger.
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
