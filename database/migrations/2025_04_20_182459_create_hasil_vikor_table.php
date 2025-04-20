<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHasilVikorTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('hasil_vikor', function (Blueprint $table) {
            $table->id(); // kolom id (primary key)
            $table->unsignedBigInteger('id_alternatif')->nullable(); // relasi ke tabel alternatif
            $table->double('nilai_s')->nullable(); // nilai S
            $table->double('nilai_r')->nullable(); // nilai R
            $table->double('nilai_q')->nullable(); // nilai Q
            $table->integer('ranking')->nullable(); // urutan ranking
            $table->enum('status', ['Lulus', 'Tidak Lulus'])->nullable(); // status kelulusan
            $table->timestamps(); // created_at dan updated_at

            // Foreign key constraint (opsional jika ada tabel alternatif)
            $table->foreign('id_alternatif')->references('id')->on('alternatifs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hasil_vikor');
    }
}
