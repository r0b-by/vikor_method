<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHasilVikorTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('hasil_vikor')) {
            Schema::create('hasil_vikor', function (Blueprint $table) {
                $table->id();
                // Perbaikan di sini: Menentukan tabel 'alternatifs' secara eksplisit
                $table->foreignId('id_alternatif')->nullable()->constrained('alternatifs');
                $table->double('nilai_s')->nullable();
                $table->double('nilai_r')->nullable();
                $table->double('nilai_q')->nullable();
                $table->integer('ranking')->nullable();
                $table->enum('status', ['Lulus', 'Tidak Lulus'])->nullable();
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('hasil_vikor');
    }
}
