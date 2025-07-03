<?php

namespace Database\Seeders;

use App\Models\Criteria;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CriteriaSeeder extends Seeder
{
    public function run()
    {
        // Solusi 1: Nonaktifkan sementara foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Criteria::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Solusi Alternatif 2: Gunakan delete() (lebih lambat)
        // Criteria::query()->delete();

        $criterias = [
            [
                'no' => '1', // Tambahkan kolom 'no' dengan nilai unik
                'criteria_code' => 'C1',
                'criteria_name' => 'Nilai Akademik',
                'criteria_type' => 'Benefit',
                'input_type' => 'manual',
                'weight' => 0.30,
            ],
            [
                'no' => '2', // Tambahkan kolom 'no' dengan nilai unik
                'criteria_code' => 'C2',
                'criteria_name' => 'Pendapatan Orang Tua',
                'criteria_type' => 'Cost',
                'input_type' => 'manual',
                'weight' => 0.25,
            ],
            [
                'no' => '3', // Tambahkan kolom 'no' dengan nilai unik
                'criteria_code' => 'C3',
                'criteria_name' => 'Jumlah Tanggungan',
                'criteria_type' => 'Benefit',
                'input_type' => 'manual',
                'weight' => 0.20,
            ],
            [
                'no' => '4', // Tambahkan kolom 'no' dengan nilai unik
                'criteria_code' => 'C4',
                'criteria_name' => 'Prestasi Akademik',
                'criteria_type' => 'Benefit',
                'input_type' => 'poin',
                'weight' => 0.15,
            ],
            [
                'no' => '5', // Tambahkan kolom 'no' dengan nilai unik
                'criteria_code' => 'C5',
                'criteria_name' => 'Prestasi Non-Akademik',
                'criteria_type' => 'Benefit',
                'input_type' => 'poin',
                'weight' => 0.10,
            ],
        ];

        foreach ($criterias as $criteria) {
            Criteria::create($criteria);
        }
    }
}