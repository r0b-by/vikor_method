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
            $table->string('label', 100);
            $table->integer('point');
            $table->timestamps();

            // Index untuk foreign key
            $table->index('criteria_id');
            
            // Unique constraint untuk mencegah duplikasi point dalam satu criteria
            $table->unique(['criteria_id', 'point']);
            
            // Foreign key constraint
            $table->foreign('criteria_id')
                  ->references('id')
                  ->on('criterias')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('criteria_subs', function (Blueprint $table) {
            // Hapus foreign key dan unique constraint terlebih dahulu
            $table->dropUnique(['criteria_id', 'point']);
            $table->dropForeign(['criteria_id']);
        });
        
        Schema::dropIfExists('criteria_subs');
    }
};