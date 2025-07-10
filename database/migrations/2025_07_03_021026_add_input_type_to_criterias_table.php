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
        Schema::table('criterias', function (Blueprint $table) {
            $table->string('input_type', 10)
                  ->default('manual')
                  ->after('criteria_type')
                  ->comment('Tipe input: manual atau poin');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('criterias', function (Blueprint $table) {
            $table->dropColumn('input_type');
        });
    }
};