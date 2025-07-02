<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon; // Import Carbon for date handling

class AcademicPeriodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Disable foreign key checks to allow truncating
        Schema::disableForeignKeyConstraints();
        // Clear existing data from the academic_periods table
        DB::table('academic_periods')->truncate();
        // Enable foreign key checks
        Schema::enableForeignKeyConstraints();

        // Define the academic periods to be seeded
        $periods = [
            [
                'tahun_ajaran' => '2023/2024',
                'semester' => 'Ganjil',
                'start_date' => '2023-07-17',
                'end_date' => '2023-12-22',
                'is_active' => false,
            ],
            [
                'tahun_ajaran' => '2023/2024',
                'semester' => 'Genap',
                'start_date' => '2024-01-08',
                'end_date' => '2024-06-28',
                'is_active' => false,
            ],
            [
                'tahun_ajaran' => '2024/2025',
                'semester' => 'Ganjil',
                'start_date' => '2024-07-15',
                'end_date' => '2024-12-20',
                'is_active' => true, // Set this period as active
            ],
            [
                'tahun_ajaran' => '2024/2025',
                'semester' => 'Genap',
                'start_date' => '2025-01-06',
                'end_date' => '2025-06-27',
                'is_active' => false,
            ],
        ];

        // Insert the academic periods into the database
        foreach ($periods as $period) {
            DB::table('academic_periods')->insert([
                'tahun_ajaran' => $period['tahun_ajaran'],
                'semester' => $period['semester'],
                'start_date' => $period['start_date'],
                'end_date' => $period['end_date'],
                'is_active' => $period['is_active'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
