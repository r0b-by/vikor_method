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
            // Seeder untuk peran dan izin (dari Spatie)
            RolesAndPermissionsSeeder::class,
            // Seeder untuk contoh user
            UserSeeder::class,
            // Seeder untuk data kriteria (harus sebelum AlternatifPenilaianSeeder)
            CriteriaSeeder::class,
            // Seeder untuk data alternatif dan penilaian (matriks)
            AlternatifPenilaianSeeder::class,
        ]);
    }
}
