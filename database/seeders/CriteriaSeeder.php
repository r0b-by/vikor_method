<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\criteria; // Pastikan Anda mengimpor model Criteria Anda

class CriteriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan tabel kosong sebelum menambahkan data baru jika Anda tidak ingin duplikasi
        // criteria::truncate(); // Hapus baris ini jika Anda tidak ingin menghapus data yang ada

        $criterias = [
            [
                'criteria_code' => 'C1',
                'criteria_name' => 'Nilai Akademik',
                'criteria_type' => 'benefit', // Semakin tinggi semakin baik
                'weight' => 0.30,
            ],
            [
                'criteria_code' => 'C2',
                'criteria_name' => 'Pendapatan Orang Tua',
                'criteria_type' => 'cost', // Semakin rendah semakin baik (misal untuk beasiswa)
                'weight' => 0.25,
            ],
            [
                'criteria_code' => 'C3',
                'criteria_name' => 'Jumlah Tanggungan',
                'criteria_type' => 'benefit', // Semakin tinggi semakin baik
                'weight' => 0.20,
            ],
            [
                'criteria_code' => 'C4',
                'criteria_name' => 'Prestasi Akademik',
                'criteria_type' => 'benefit', // Semakin tinggi semakin baik
                'weight' => 0.15,
            ],
            [
                'criteria_code' => 'C5',
                'criteria_name' => 'Prestasi Non-Akademik',
                'criteria_type' => 'benefit', // Semakin tinggi semakin baik
                'weight' => 0.10,
            ],
        ];

        foreach ($criterias as $criteriaData) {
            criteria::firstOrCreate(
                ['criteria_code' => $criteriaData['criteria_code']], // Coba temukan berdasarkan kode kriteria
                $criteriaData // Jika tidak ada, buat baru dengan data ini
            );
        }

        $this->command->info('Data kriteria berhasil diisi!');
    }
}
