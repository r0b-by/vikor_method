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
        Schema::table('penilaians', function (Blueprint $table) {
            // Ubah 'semester' menjadi 'certificate_details' atau kolom lain yang sudah ada
            $table->date('tanggal_penilaian')->nullable()->after('certificate_details');
            $table->time('jam_penilaian')->nullable()->after('tanggal_penilaian');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penilaians', function (Blueprint $table) {
            $table->dropColumn([
                'tanggal_penilaian',
                'jam_penilaian'
            ]);
        });
    }
};