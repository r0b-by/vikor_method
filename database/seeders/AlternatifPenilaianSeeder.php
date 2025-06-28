<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\alternatif; // Pastikan Anda mengimpor model Alternatif Anda
use App\Models\criteria;   // Pastikan Anda mengimpor model Criteria Anda
use App\Models\penilaian;  // Pastikan Anda mengimpor model Penilaian Anda

class AlternatifPenilaianSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan tabel kosong sebelum menambahkan data baru jika Anda tidak ingin duplikasi
        // alternatif::truncate();
        // penilaian::truncate();

        // Data Alternatif (Siswa)
        $alternatifsData = [
            ['alternatif_code' => 'A1', 'alternatif_name' => 'Ahmad Fauzan'],
            ['alternatif_code' => 'A2', 'alternatif_name' => 'Budi Santoso'],
            ['alternatif_code' => 'A3', 'alternatif_name' => 'Citra Dewi'],
            ['alternatif_code' => 'A4', 'alternatif_name' => 'Dian Purnama'],
            ['alternatif_code' => 'A5', 'alternatif_name' => 'Eka Ramadhan'],
            ['alternatif_code' => 'A6', 'alternatif_name' => 'Farah Aulia'],
            ['alternatif_code' => 'A7', 'alternatif_name' => 'Gilang Saputra'],
            ['alternatif_code' => 'A8', 'alternatif_name' => 'Hana Salsabila'],
            ['alternatif_code' => 'A9', 'alternatif_name' => 'Indra Wijaya'],
            ['alternatif_code' => 'A10', 'alternatif_name' => 'Joko Prasetyo'],
            ['alternatif_code' => 'A11', 'alternatif_name' => 'Kurniawan Hidayat'],
            ['alternatif_code' => 'A12', 'alternatif_name' => 'Lestari Dewi'],
            ['alternatif_code' => 'A13', 'alternatif_name' => 'Mahmud Risky'],
            ['alternatif_code' => 'A14', 'alternatif_name' => 'Nadya Amelia'],
            ['alternatif_code' => 'A15', 'alternatif_name' => 'Omar Zaki'],
            ['alternatif_code' => 'A16', 'alternatif_name' => 'Putri Ayu'],
            ['alternatif_code' => 'A17', 'alternatif_name' => 'Qori Rahma'],
            ['alternatif_code' => 'A18', 'alternatif_name' => 'Rizky Fadilah'],
            ['alternatif_code' => 'A19', 'alternatif_name' => 'Siti Nurhaliza'],
            ['alternatif_code' => 'A20', 'alternatif_name' => 'Taufik Hidayat'],
            ['alternatif_code' => 'A21', 'alternatif_name' => 'Umar Alfaruq'],
            ['alternatif_code' => 'A22', 'alternatif_name' => 'Vina Maharani'],
            ['alternatif_code' => 'A23', 'alternatif_name' => 'Wahyu Pradana'],
            ['alternatif_code' => 'A24', 'alternatif_name' => 'Xavier Muhammad'],
            ['alternatif_code' => 'A25', 'alternatif_name' => 'Yusuf Kurnia'],
            ['alternatif_code' => 'A26', 'alternatif_name' => 'Zahra Melati'],
            ['alternatif_code' => 'A27', 'alternatif_name' => 'Agus Saputra'],
            ['alternatif_code' => 'A28', 'alternatif_name' => 'Bella Safira'],
            ['alternatif_code' => 'A29', 'alternatif_name' => 'Dedy Firmansyah'],
            ['alternatif_code' => 'A30', 'alternatif_name' => 'Erika Putri'],
        ];

        // Masukkan data alternatif ke database
        foreach ($alternatifsData as $data) {
            alternatif::firstOrCreate(
                ['alternatif_code' => $data['alternatif_code']],
                $data
            );
        }

        // Ambil semua kriteria yang sudah ada (dibutuhkan untuk mendapatkan ID)
        $criterias = criteria::all()->keyBy('criteria_code');

        // Data Penilaian (Matrix)
        $penilaiansData = [
            ['alternatif_code' => 'A1', 'C1' => 87, 'C2' => 20, 'C3' => 100, 'C4' => 40, 'C5' => 0],
            ['alternatif_code' => 'A2', 'C1' => 81, 'C2' => 20, 'C3' => 40, 'C4' => 60, 'C5' => 20],
            ['alternatif_code' => 'A3', 'C1' => 83, 'C2' => 20, 'C3' => 60, 'C4' => 100, 'C5' => 20],
            ['alternatif_code' => 'A4', 'C1' => 84, 'C2' => 80, 'C3' => 60, 'C4' => 60, 'C5' => 20],
            ['alternatif_code' => 'A5', 'C1' => 75, 'C2' => 80, 'C3' => 40, 'C4' => 60, 'C5' => 20],
            ['alternatif_code' => 'A6', 'C1' => 74, 'C2' => 80, 'C3' => 40, 'C4' => 40, 'C5' => 30],
            ['alternatif_code' => 'A7', 'C1' => 84, 'C2' => 40, 'C3' => 60, 'C4' => 100, 'C5' => 0],
            ['alternatif_code' => 'A8', 'C1' => 92, 'C2' => 60, 'C3' => 60, 'C4' => 40, 'C5' => 20],
            ['alternatif_code' => 'A9', 'C1' => 81, 'C2' => 60, 'C3' => 60, 'C4' => 0, 'C5' => 20],
            ['alternatif_code' => 'A10', 'C1' => 76, 'C2' => 80, 'C3' => 60, 'C4' => 60, 'C5' => 20],
            ['alternatif_code' => 'A11', 'C1' => 90, 'C2' => 60, 'C3' => 100, 'C4' => 100, 'C5' => 20],
            ['alternatif_code' => 'A12', 'C1' => 82, 'C2' => 40, 'C3' => 80, 'C4' => 40, 'C5' => 20],
            ['alternatif_code' => 'A13', 'C1' => 87, 'C2' => 40, 'C3' => 80, 'C4' => 100, 'C5' => 20],
            ['alternatif_code' => 'A14', 'C1' => 71, 'C2' => 60, 'C3' => 20, 'C4' => 40, 'C5' => 20],
            ['alternatif_code' => 'A15', 'C1' => 70, 'C2' => 60, 'C3' => 60, 'C4' => 60, 'C5' => 0],
            ['alternatif_code' => 'A16', 'C1' => 71, 'C2' => 40, 'C3' => 100, 'C4' => 0, 'C5' => 20],
            ['alternatif_code' => 'A17', 'C1' => 81, 'C2' => 20, 'C3' => 80, 'C4' => 100, 'C5' => 20],
            ['alternatif_code' => 'A18', 'C1' => 75, 'C2' => 20, 'C3' => 80, 'C4' => 100, 'C5' => 20],
            ['alternatif_code' => 'A19', 'C1' => 76, 'C2' => 80, 'C3' => 60, 'C4' => 0, 'C5' => 0],
            ['alternatif_code' => 'A20', 'C1' => 95, 'C2' => 40, 'C3' => 80, 'C4' => 60, 'C5' => 60],
            ['alternatif_code' => 'A21', 'C1' => 91, 'C2' => 40, 'C3' => 20, 'C4' => 100, 'C5' => 0],
            ['alternatif_code' => 'A22', 'C1' => 78, 'C2' => 80, 'C3' => 80, 'C4' => 0, 'C5' => 100],
            ['alternatif_code' => 'A23', 'C1' => 91, 'C2' => 40, 'C3' => 60, 'C4' => 100, 'C5' => 20],
            ['alternatif_code' => 'A24', 'C1' => 89, 'C2' => 80, 'C3' => 40, 'C4' => 40, 'C5' => 60],
            ['alternatif_code' => 'A25', 'C1' => 89, 'C2' => 80, 'C3' => 80, 'C4' => 0, 'C5' => 0],
            ['alternatif_code' => 'A26', 'C1' => 70, 'C2' => 60, 'C3' => 100, 'C4' => 0, 'C5' => 0],
            ['alternatif_code' => 'A27', 'C1' => 95, 'C2' => 60, 'C3' => 60, 'C4' => 60, 'C5' => 60],
            ['alternatif_code' => 'A28', 'C1' => 90, 'C2' => 60, 'C3' => 20, 'C4' => 40, 'C5' => 20],
            ['alternatif_code' => 'A29', 'C1' => 70, 'C2' => 100, 'C3' => 100, 'C4' => 0, 'C5' => 60],
            ['alternatif_code' => 'A30', 'C1' => 76, 'C2' => 60, 'C3' => 40, 'C4' => 40, 'C5' => 20],
        ];

        // Looping untuk setiap alternatif dan memasukkan penilaiannya
        foreach ($penilaiansData as $penilaianRow) {
            $alternatifCode = $penilaianRow['alternatif_code'];
            // Ambil ID alternatif berdasarkan kode
            $alternatif = alternatif::where('alternatif_code', $alternatifCode)->first();

            if ($alternatif) {
                foreach ($criterias as $criteriaCode => $criteria) {
                    // Pastikan kolom 'nilai' ada di data penilaian
                    if (isset($penilaianRow[$criteriaCode])) {
                        penilaian::firstOrCreate(
                            [
                                'id_alternatif' => $alternatif->id,
                                'id_criteria' => $criteria->id,
                            ],
                            [
                                'nilai' => $penilaianRow[$criteriaCode],
                            ]
                        );
                    }
                }
            }
        }

        $this->command->info('Data alternatif dan penilaian berhasil diisi!');
    }
}
