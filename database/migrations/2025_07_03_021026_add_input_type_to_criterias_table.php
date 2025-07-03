<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('criterias', function (Blueprint $table) {
            $table->string('input_type')->default('manual')->after('criteria_type');
        });
    }

    public function down()
    {
        Schema::table('criterias', function (Blueprint $table) {
            $table->dropColumn('input_type');
        });
    }
};