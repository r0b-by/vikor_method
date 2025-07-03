<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('criteria_subs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('criteria_id');
            $table->string('label', 100); // Menambahkan panjang maksimum untuk string
            $table->integer('point')->default(0); // Menambahkan default value
            $table->timestamps();

            // Menambahkan index untuk foreign key
            $table->index('criteria_id');
            
            // Foreign key constraint dengan opsi onDelete cascade
            $table->foreign('criteria_id')
                  ->references('id')
                  ->on('criterias')
                  ->onDelete('cascade')
                  ->onUpdate('cascade'); // Menambahkan onUpdate cascade
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        // Menghapus foreign key constraint terlebih dahulu
        Schema::table('criteria_subs', function (Blueprint $table) {
            $table->dropForeign(['criteria_id']);
        });
        
        // Baru kemudian drop tabel
        Schema::dropIfExists('criteria_subs');
    }
};