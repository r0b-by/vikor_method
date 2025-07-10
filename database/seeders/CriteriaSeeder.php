<?php

namespace Database\Seeders;

use App\Models\Criteria;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CriteriaSeeder extends Seeder
{
    public function run()
    {
        // Nonaktifkan sementara foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Criteria::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Dapatkan user admin pertama atau buat default
        $admin = User::role('admin')->first();
        
        if (!$admin) {
            $admin = User::create([
                'name' => 'Default Admin',
                'email' => 'admin@example.com',
                'password' => bcrypt('password'),
            ])->assignRole('admin');
        }

        $criterias = [
            [
                'no' => '1',
                'criteria_code' => 'C1',
                'criteria_name' => 'Nilai Akademik',
                'criteria_type' => 'Benefit',
                'input_type' => 'manual',
                'weight' => 0.30,
                'created_by' => $admin->id,
            ],
            [
                'no' => '2',
                'criteria_code' => 'C2',
                'criteria_name' => 'Pendapatan Orang Tua',
                'criteria_type' => 'Cost',
                'input_type' => 'manual',
                'weight' => 0.25,
                'created_by' => $admin->id,
            ],
            [
                'no' => '3',
                'criteria_code' => 'C3',
                'criteria_name' => 'Jumlah Tanggungan',
                'criteria_type' => 'Benefit',
                'input_type' => 'manual',
                'weight' => 0.20,
                'created_by' => $admin->id,
            ],
            [
                'no' => '4',
                'criteria_code' => 'C4',
                'criteria_name' => 'Prestasi Akademik',
                'criteria_type' => 'Benefit',
                'input_type' => 'poin',
                'weight' => 0.15,
                'created_by' => $admin->id,
            ],
            [
                'no' => '5',
                'criteria_code' => 'C5',
                'criteria_name' => 'Prestasi Non-Akademik',
                'criteria_type' => 'Benefit',
                'input_type' => 'poin',
                'weight' => 0.10,
                'created_by' => $admin->id,
            ],
        ];

        foreach ($criterias as $criteria) {
            Criteria::create($criteria);
        }
    }
}