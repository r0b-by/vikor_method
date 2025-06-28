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
        Schema::table('users', function (Blueprint $table) {
            // Pastikan NIS unik karena merupakan Nomor Induk Siswa
            $table->string('nis')->unique()->nullable()->after('email');
            $table->string('kelas')->nullable()->after('nis');
            $table->string('jurusan')->nullable()->after('kelas');
            $table->text('alamat')->nullable()->after('jurusan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['nis', 'kelas', 'jurusan', 'alamat']);
        });
    }
};