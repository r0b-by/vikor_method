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
        Schema::create('penilaians', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_alternatif');
            $table->unsignedBigInteger('id_criteria');
            $table->double('nilai');
            $table->json('certificate_details')->nullable(); // Untuk menyimpan detail sertifikat
            $table->timestamps();

            $table->foreign('id_alternatif')->references('id')->on('alternatifs')->onDelete('cascade');
            $table->foreign('id_criteria')->references('id')->on('criterias')->onDelete('cascade');
            $table->foreignId('academic_period_id')->nullable()->constrained('academic_periods')->after('id_criteria');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penilaians');
    }
};
