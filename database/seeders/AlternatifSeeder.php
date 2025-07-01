<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AlternatifSeeder extends Seeder
{
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('alternatifs')->truncate();
        Schema::enableForeignKeyConstraints();

        // Get only student users (excluding admin and teachers)
        $students = DB::table('users')
            ->where('email', '!=', 'admin@example.com')
            ->where('kelas', '!=', 'Guru')
            ->orderBy('id')
            ->get();

        foreach ($students as $index => $student) {
            DB::table('alternatifs')->insert([
                'user_id' => $student->id,
                'alternatif_code' => 'ALT' . str_pad($index + 1, 3, '0', STR_PAD_LEFT),
                'alternatif_name' => $student->name,
                'status_perhitungan' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}