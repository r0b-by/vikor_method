<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CriteriaSeeder extends Seeder
{
    public function run()
    {
        $criterias = [
            [
                'criteria_code' => 'C1',
                'criteria_name' => 'Nilai Akademik',
                'criteria_type' => 'benefit',
                'weight' => 0.30,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'criteria_code' => 'C2',
                'criteria_name' => 'Pendapatan Orang Tua',
                'criteria_type' => 'cost',
                'weight' => 0.25,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'criteria_code' => 'C3',
                'criteria_name' => 'Jumlah Tanggungan',
                'criteria_type' => 'benefit',
                'weight' => 0.20,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'criteria_code' => 'C4',
                'criteria_name' => 'Prestasi Akademik',
                'criteria_type' => 'benefit',
                'weight' => 0.15,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'criteria_code' => 'C5',
                'criteria_name' => 'Prestasi Non-Akademik',
                'criteria_type' => 'benefit',
                'weight' => 0.10,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('criterias')->insert($criterias);
    }
}