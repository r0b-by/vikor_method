<?php

namespace Database\Seeders;

use App\Models\Criteria;
use App\Models\CriteriaSub;
use Illuminate\Database\Seeder;

class CriteriaSubSeeder extends Seeder
{
    public function run()
    {
        // Nonaktifkan foreign key check sementara
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        CriteriaSub::truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $prestasiAkademik = Criteria::where('criteria_code', 'C4')->first();
        $prestasiNonAkademik = Criteria::where('criteria_code', 'C5')->first();

        if (!$prestasiAkademik || !$prestasiNonAkademik) {
            $this->command->error('Kriteria C4 atau C5 tidak ditemukan! Pastikan CriteriaSeeder sudah dijalankan.');
            return;
        }

        $subCriterias = [
            // Sub-criteria for Prestasi Akademik (C4)
            [
                'criteria_id' => $prestasiAkademik->id,
                'label' => 'Internasional',
                'point' => 25, // Updated point
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'criteria_id' => $prestasiAkademik->id,
                'label' => 'Nasional',
                'point' => 20, // Updated point
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'criteria_id' => $prestasiAkademik->id,
                'label' => 'Provinsi',
                'point' => 15, // Updated point
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'criteria_id' => $prestasiAkademik->id,
                'label' => 'Kabupaten/Kota',
                'point' => 10, // Updated point
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'criteria_id' => $prestasiAkademik->id,
                'label' => 'Sekolah',
                'point' => 5, // Updated point
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Sub-criteria for Prestasi Non-Akademik (C5)
            [
                'criteria_id' => $prestasiNonAkademik->id,
                'label' => 'Internasional',
                'point' => 25, // Updated point
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'criteria_id' => $prestasiNonAkademik->id,
                'label' => 'Nasional',
                'point' => 20, // Updated point
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'criteria_id' => $prestasiNonAkademik->id,
                'label' => 'Provinsi',
                'point' => 15, // Updated point
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'criteria_id' => $prestasiNonAkademik->id,
                'label' => 'Kabupaten/Kota',
                'point' => 10, // Updated point
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'criteria_id' => $prestasiNonAkademik->id,
                'label' => 'Sekolah',
                'point' => 5, // Updated point
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($subCriterias as $subCriteria) {
            CriteriaSub::create($subCriteria);
        }

        $this->command->info('Berhasil menambahkan '.count($subCriterias).' sub-kriteria.');
    }
}