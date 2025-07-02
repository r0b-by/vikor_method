<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('alternatifs', function (Blueprint $table) {
            // Add these two columns
            $table->string('tahun_ajaran')->nullable()->after('user_id'); // Adjust 'after' as needed
            $table->string('semester')->nullable()->after('tahun_ajaran');
        });
    }

    public function down(): void
    {
        Schema::table('alternatifs', function (Blueprint $table) {
            $table->dropColumn(['tahun_ajaran', 'semester']);
        });
    }
};
