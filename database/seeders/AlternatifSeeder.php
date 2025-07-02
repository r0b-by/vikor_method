<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\User; // Import the User model

class AlternatifSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('alternatifs')->truncate();
        Schema::enableForeignKeyConstraints();

        // Get only student users (excluding admin and teachers) and include tahun_ajaran and semester
        // It's better to use the Eloquent model for clarity and relationships
        $students = User::role('siswa') // Assuming 'siswa' role is assigned to students
                        ->select('id', 'name', 'tahun_ajaran', 'semester') // Select necessary columns
                        ->orderBy('id')
                        ->get();

        foreach ($students as $index => $student) {
            DB::table('alternatifs')->insert([
                'user_id' => $student->id,
                'alternatif_code' => 'ALT-' . str_pad($student->id, 4, '0', STR_PAD_LEFT), // Use student ID for unique code
                'alternatif_name' => $student->name,
                'tahun_ajaran' => $student->tahun_ajaran, // Add tahun_ajaran from user
                'semester' => $student->semester,       // Add semester from user
                'status_perhitungan' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
