<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('penilaians', function (Blueprint $table) {
            $table->string('tahun_ajaran')->nullable()->after('id_criteria'); // Adjust 'after'
            $table->string('semester')->nullable()->after('tahun_ajaran');
        });
    }

    public function down(): void
    {
        Schema::table('penilaians', function (Blueprint $table) {
            $table->dropColumn(['tahun_ajaran', 'semester']);
        });
    }
};
