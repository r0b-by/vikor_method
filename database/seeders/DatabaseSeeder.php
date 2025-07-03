<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            AcademicPeriodSeeder::class,
            UserSeeder::class,
            CriteriaSeeder::class,
            CriteriaSubSeeder::class,
            AlternatifSeeder::class,
            PenilaianSeeder::class,
            //HasilVikorSeeder::class,
            PermissionsSeeder::class,
        ]);

    }
}
