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
        Schema::create('pending_profile_updates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Foreign key ke tabel users
            $table->json('original_data')->nullable(); // Data profil asli sebelum perubahan (opsional)
            $table->json('proposed_data'); // Data profil yang diajukan (nama, email, dll.)
            $table->text('reason')->nullable(); // Alasan perubahan, jika ada
            $table->string('status')->default('pending'); // Status: pending, approved, rejected
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null'); // Admin yang menyetujui
            $table->timestamp('approved_at')->nullable(); // Waktu persetujuan
            $table->timestamps(); // created_at, updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pending_profile_updates');
    }
};

