<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon; // Import Carbon for date handling
use App\Models\AcademicPeriod; // Import AcademicPeriod model
use App\Models\Alternatif; // Import Alternatif model
use App\Models\Criteria; // Import Criteria model
use App\Models\Penilaian; // Import Penilaian model (though its helper method isn't used directly here, it's good practice to keep it if it's part of the application context)

class PenilaianSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('penilaians')->truncate();
        Schema::enableForeignKeyConstraints();

        // Ambil periode akademik yang aktif atau yang paling baru
        $activeAcademicPeriod = $this->getActiveAcademicPeriod();

        if (!$activeAcademicPeriod) {
            $this->command->error('Tidak ada periode akademik ditemukan. Pastikan AcademicPeriodSeeder sudah dijalankan.');
            return;
        }

        $this->command->info("Seeding Penilaian for Tahun Ajaran: {$activeAcademicPeriod->tahun_ajaran}, Semester: {$activeAcademicPeriod->semester}");

        $studentEvaluations = $this->getStudentEvaluationData();
        $criterias = Criteria::with('subs')->get();
        
        $alternatifs = Alternatif::with('user')
            ->where('tahun_ajaran', $activeAcademicPeriod->tahun_ajaran)
            ->where('semester', $activeAcademicPeriod->semester)
            ->get();

        if ($alternatifs->isEmpty()) {
            $this->command->warn("Tidak ada alternatif ditemukan untuk periode ini. Penilaian tidak dapat di-seed.");
            return;
        }

        if ($criterias->isEmpty()) {
            $this->command->warn("Tidak ada kriteria ditemukan. Penilaian tidak dapat di-seed.");
            return;
        }

        $penilaianData = $this->preparePenilaianData($alternatifs, $criterias, $studentEvaluations, $activeAcademicPeriod);

        if (!empty($penilaianData)) {
            DB::table('penilaians')->insert($penilaianData);
            $count = count($penilaianData);
            $this->command->info("Berhasil menambahkan {$count} data penilaian untuk {$alternatifs->count()} siswa.");
        } else {
            $this->command->error('Tidak ada data penilaian yang berhasil dibuat.');
        }
    }

    /**
     * Get active academic period
     */
    private function getActiveAcademicPeriod()
    {
        return AcademicPeriod::where('is_active', true)->first() 
            ?? AcademicPeriod::orderBy('tahun_ajaran', 'desc')
                ->orderBy('semester', 'desc')
                ->first();
    }

    /**
     * Prepare penilaian data for insertion
     */
    private function preparePenilaianData($alternatifs, $criterias, $studentEvaluations, $activeAcademicPeriod)
    {
        $penilaianData = [];
        $currentDateTime = Carbon::now();
        
        foreach ($alternatifs as $alternatif) {
            // Use user's name if available, otherwise fallback to alternatif_name
            $studentName = $alternatif->user->name ?? $alternatif->alternatif_name;
            
            if (!isset($studentEvaluations[$studentName])) {
                $this->command->warn("Data evaluasi tidak ditemukan untuk: {$studentName}. Melewatkan alternatif ini.");
                continue;
            }

            foreach ($criterias as $criteria) {
                $criteriaCode = $criteria->criteria_code;
                
                $nilai = 0;
                $certificateDetailsJson = null;

                if (isset($studentEvaluations[$studentName][$criteriaCode])) {
                    $evaluationData = $studentEvaluations[$studentName][$criteriaCode];
                    
                    if (($criteriaCode === 'C4' || $criteriaCode === 'C5') && is_array($evaluationData) && isset($evaluationData['detail'])) {
                        $certificateDetails = $this->formatCertificateDetails($evaluationData['detail'], $criteria);
                        $nilai = $this->calculateTotalPoints($certificateDetails);
                        $certificateDetailsJson = json_encode($certificateDetails);
                    } else {
                        // For C1, C2, C3, directly use the value
                        $nilai = is_array($evaluationData) ? ($evaluationData['nilai'] ?? 0) : $evaluationData;
                    }
                }

                $penilaianData[] = [
                    'id_alternatif' => $alternatif->id,
                    'id_criteria' => $criteria->id,
                    'nilai' => $nilai,
                    'academic_period_id' => $activeAcademicPeriod->id,
                    'tanggal_penilaian' => $currentDateTime->toDateString(),
                    'jam_penilaian' => $currentDateTime->toTimeString(),
                    'certificate_details' => $certificateDetailsJson,
                    'created_at' => $currentDateTime,
                    'updated_at' => $currentDateTime,
                ];
            }
        }

        return $penilaianData;
    }

    /**
     * Format certificate details to match with criteria subs
     * This method ensures that the points used are from the database (CriteriaSub model)
     * and not hardcoded in the seeder's getStudentEvaluationData.
     */
    private function formatCertificateDetails(array $details, Criteria $criteria): array
    {
        $formatted = [];
        
        foreach ($details as $level => $count) {
            $subCriteria = $criteria->subs->firstWhere('label', $level);
            
            if ($subCriteria) {
                $formatted[] = [
                    'level' => $subCriteria->label,
                    'count' => $count,
                    'point' => $subCriteria->point, // Use point from CriteriaSub
                    'sub_total' => $subCriteria->point * $count
                ];
            } else {
                $this->command->warn("Sub kriteria '{$level}' tidak ditemukan untuk kriteria {$criteria->criteria_code}.");
            }
        }
        
        return $formatted;
    }

    /**
     * Calculate total points from certificate details, with a cap of 100.
     */
    private function calculateTotalPoints(array $certificateDetails): int
    {
        $total = array_reduce($certificateDetails, fn($total, $cert) => $total + ($cert['sub_total'] ?? 0), 0);
        return min(100, $total); // Apply the 100-point cap here
    }
    
    /**
     * Returns the sample student evaluation data.
     *
     * @return array
     */
    private function getStudentEvaluationData()
    {
        return [
            'Ahmad Fauzan' => [
                'C1' => 90,
                'C2' => 40, // (3-4 juta)
                'C3' => 40, // (3 orang)
                'C4' => [
                    'detail' => ['Nasional' => 1, 'Provinsi' => 1, 'Kabupaten/Kota' => 1, 'Sekolah' => 1] // 4 certs
                ],
                'C5' => [
                    'detail' => ['Internasional' => 1, 'Nasional' => 1, 'Sekolah' => 1] // 3 certs
                ]
            ],
            'Budi Santoso' => [
                'C1' => 85,
                'C2' => 60, // (2-3 juta)
                'C3' => 60, // (4 orang)
                'C4' => [
                    'detail' => ['Internasional' => 1, 'Nasional' => 1, 'Provinsi' => 1] // 3 certs
                ],
                'C5' => [
                    'detail' => ['Provinsi' => 1, 'Kabupaten/Kota' => 1, 'Sekolah' => 1] // 3 certs
                ]
            ],
            'Citra Dewi' => [
                'C1' => 95,
                'C2' => 20, // (> 4 juta)
                'C3' => 20, // (≤ 2 orang)
                'C4' => [
                    'detail' => ['Internasional' => 1, 'Nasional' => 1, 'Provinsi' => 1, 'Kabupaten/Kota' => 1] // 4 certs
                ],
                'C5' => [
                    'detail' => ['Internasional' => 1, 'Nasional' => 1, 'Provinsi' => 1] // 3 certs
                ]
            ],
            'Dian Purnama' => [
                'C1' => 88,
                'C2' => 80, // (1-2 juta)
                'C3' => 80, // (5 orang)
                'C4' => [
                    'detail' => ['Nasional' => 1, 'Provinsi' => 1, 'Kabupaten/Kota' => 1] // 3 certs
                ],
                'C5' => [
                    'detail' => ['Provinsi' => 1, 'Kabupaten/Kota' => 1] // 2 certs
                ]
            ],
            'Eka Ramadhan' => [
                'C1' => 82,
                'C2' => 100, // (≤ 1 juta)
                'C3' => 100, // (≥ 6 orang)
                'C4' => [
                    'detail' => ['Provinsi' => 1, 'Kabupaten/Kota' => 1, 'Sekolah' => 1] // 3 certs
                ],
                'C5' => [
                    'detail' => ['Nasional' => 1, 'Provinsi' => 1, 'Sekolah' => 1] // 3 certs
                ]
            ],
            'Farah Aulia' => [
                'C1' => 93,
                'C2' => 40, // (3-4 juta)
                'C3' => 60, // (4 orang)
                'C4' => [
                    'detail' => ['Internasional' => 1, 'Nasional' => 1, 'Provinsi' => 2] // 4 certs
                ],
                'C5' => [
                    'detail' => ['Nasional' => 1, 'Kabupaten/Kota' => 1, 'Sekolah' => 1] // 3 certs
                ]
            ],
            'Gilang Saputra' => [
                'C1' => 87,
                'C2' => 60, // (2-3 juta)
                'C3' => 40, // (3 orang)
                'C4' => [
                    'detail' => ['Nasional' => 1, 'Kabupaten/Kota' => 1] // 2 certs
                ],
                'C5' => [
                    'detail' => ['Provinsi' => 1, 'Sekolah' => 1] // 2 certs
                ]
            ],
            'Hana Salsabila' => [
                'C1' => 91,
                'C2' => 20, // (> 4 juta)
                'C3' => 20, // (≤ 2 orang)
                'C4' => [
                    'detail' => ['Internasional' => 1, 'Nasional' => 2, 'Provinsi' => 1] // 4 certs
                ],
                'C5' => [
                    'detail' => ['Internasional' => 1, 'Nasional' => 1, 'Kabupaten/Kota' => 1] // 3 certs
                ]
            ],
            'Indra Wijaya' => [
                'C1' => 84,
                'C2' => 80, // (1-2 juta)
                'C3' => 80, // (5 orang)
                'C4' => [
                    'detail' => ['Provinsi' => 1, 'Kabupaten/Kota' => 1, 'Sekolah' => 1] // 3 certs
                ],
                'C5' => [
                    'detail' => ['Nasional' => 1, 'Sekolah' => 1] // 2 certs
                ]
            ],
            'Joko Prasetyo' => [
                'C1' => 89,
                'C2' => 100, // (≤ 1 juta)
                'C3' => 100, // (≥ 6 orang)
                'C4' => [
                    'detail' => ['Nasional' => 1, 'Provinsi' => 1, 'Sekolah' => 1] // 3 certs
                ],
                'C5' => [
                    'detail' => ['Provinsi' => 1, 'Kabupaten/Kota' => 1] // 2 certs
                ]
            ],
            'Kurniawan Hidayat' => [
                'C1' => 86,
                'C2' => 20, // (> 4 juta)
                'C3' => 80, // (5 orang)
                'C4' => [
                    'detail' => ['Nasional' => 1, 'Provinsi' => 1, 'Kabupaten/Kota' => 2] // 4 certs
                ],
                'C5' => [
                    'detail' => ['Internasional' => 1, 'Nasional' => 1, 'Provinsi' => 1] // 3 certs
                ]
            ],
            'Lestari Dewi' => [
                'C1' => 94,
                'C2' => 100, // (≤ 1 juta)
                'C3' => 60, // (4 orang)
                'C4' => [
                    'detail' => ['Internasional' => 1, 'Nasional' => 2, 'Provinsi' => 1] // 4 certs
                ],
                'C5' => [
                    'detail' => ['Internasional' => 1, 'Nasional' => 1, 'Provinsi' => 1] // 3 certs
                ]
            ],
            'Mahmud Risky' => [
                'C1' => 81,
                'C2' => 60, // (2-3 juta)
                'C3' => 40, // (3 orang)
                'C4' => [
                    'detail' => ['Kabupaten/Kota' => 1, 'Sekolah' => 1] // 2 certs
                ],
                'C5' => [
                    'detail' => ['Provinsi' => 1, 'Sekolah' => 1] // 2 certs
                ]
            ],
            'Nadya Amelia' => [
                'C1' => 91,
                'C2' => 80, // (1-2 juta)
                'C3' => 20, // (≤ 2 orang)
                'C4' => [
                    'detail' => ['Internasional' => 2, 'Nasional' => 1, 'Provinsi' => 1] // 4 certs (might hit 100 cap)
                ],
                'C5' => [
                    'detail' => ['Internasional' => 1, 'Nasional' => 1, 'Provinsi' => 1] // 3 certs
                ]
            ],
            'Omar Zaki' => [
                'C1' => 83,
                'C2' => 40, // (3-4 juta)
                'C3' => 100, // (≥ 6 orang)
                'C4' => [
                    'detail' => ['Provinsi' => 1, 'Kabupaten/Kota' => 1, 'Sekolah' => 1] // 3 certs
                ],
                'C5' => [
                    'detail' => ['Kabupaten/Kota' => 1, 'Sekolah' => 1] // 2 certs
                ]
            ],
            'Putri Ayu' => [
                'C1' => 96,
                'C2' => 60, // (2-3 juta)
                'C3' => 20, // (≤ 2 orang)
                'C4' => [
                    'detail' => ['Internasional' => 2, 'Nasional' => 2, 'Provinsi' => 2] // 6 certs (will be capped at 100)
                ],
                'C5' => [
                    'detail' => ['Internasional' => 2, 'Nasional' => 2, 'Provinsi' => 2] // 6 certs (will be capped at 100)
                ]
            ],
            'Qori Rahma' => [
                'C1' => 80,
                'C2' => 40, // (3-4 juta)
                'C3' => 60, // (4 orang)
                'C4' => [
                    'detail' => ['Nasional' => 1, 'Provinsi' => 1, 'Kabupaten/Kota' => 1] // 3 certs
                ],
                'C5' => [
                    'detail' => ['Provinsi' => 1, 'Kabupaten/Kota' => 1, 'Sekolah' => 1] // 3 certs
                ]
            ],
            'Rizky Fadilah' => [
                'C1' => 79,
                'C2' => 20, // (> 4 juta)
                'C3' => 40, // (3 orang)
                'C4' => [
                    'detail' => ['Provinsi' => 1, 'Kabupaten/Kota' => 1, 'Sekolah' => 1] // 3 certs
                ],
                'C5' => [
                    'detail' => ['Nasional' => 1, 'Provinsi' => 1, 'Sekolah' => 1] // 3 certs
                ]
            ],
            'Siti Nurhaliza' => [
                'C1' => 93,
                'C2' => 80, // (1-2 juta)
                'C3' => 20, // (≤ 2 orang)
                'C4' => [
                    'detail' => ['Internasional' => 1, 'Nasional' => 2, 'Provinsi' => 1] // 4 certs
                ],
                'C5' => [
                    'detail' => ['Internasional' => 1, 'Nasional' => 1, 'Kabupaten/Kota' => 1] // 3 certs
                ]
            ],
            'Taufik Hidayat' => [
                'C1' => 77,
                'C2' => 100, // (≤ 1 juta)
                'C3' => 100, // (≥ 6 orang)
                'C4' => [
                    'detail' => ['Kabupaten/Kota' => 1, 'Sekolah' => 1] // 2 certs
                ],
                'C5' => [
                    'detail' => ['Provinsi' => 1, 'Sekolah' => 1] // 2 certs
                ]
            ],
            'Umar Alfaruq' => [
                'C1' => 86,
                'C2' => 60, // (2-3 juta)
                'C3' => 80, // (5 orang)
                'C4' => [
                    'detail' => ['Nasional' => 1, 'Provinsi' => 1, 'Kabupaten/Kota' => 1] // 3 certs
                ],
                'C5' => [
                    'detail' => ['Internasional' => 1, 'Nasional' => 1, 'Sekolah' => 1] // 3 certs
                ]
            ],
            'Vina Maharani' => [
                'C1' => 92,
                'C2' => 40, // (3-4 juta)
                'C3' => 60, // (4 orang)
                'C4' => [
                    'detail' => ['Internasional' => 1, 'Nasional' => 1, 'Provinsi' => 2] // 4 certs
                ],
                'C5' => [
                    'detail' => ['Nasional' => 1, 'Provinsi' => 1, 'Kabupaten/Kota' => 1] // 3 certs
                ]
            ],
            'Wahyu Pradana' => [
                'C1' => 82,
                'C2' => 80, // (1-2 juta)
                'C3' => 80, // (5 orang)
                'C4' => [
                    'detail' => ['Provinsi' => 1, 'Kabupaten/Kota' => 1, 'Sekolah' => 1] // 3 certs
                ],
                'C5' => [
                    'detail' => ['Provinsi' => 1, 'Sekolah' => 1] // 2 certs
                ]
            ],
            'Xavier Muhammad' => [
                'C1' => 91,
                'C2' => 100, // (≤ 1 juta)
                'C3' => 100, // (≥ 6 orang)
                'C4' => [
                    'detail' => ['Internasional' => 1, 'Nasional' => 1, 'Provinsi' => 1] // 3 certs
                ],
                'C5' => [
                    'detail' => ['Nasional' => 1, 'Provinsi' => 1, 'Kabupaten/Kota' => 1] // 3 certs
                ]
            ],
            'Yusuf Kurnia' => [
                'C1' => 84,
                'C2' => 60, // (2-3 juta)
                'C3' => 40, // (3 orang)
                'C4' => [
                    'detail' => ['Kabupaten/Kota' => 1, 'Sekolah' => 1] // 2 certs
                ],
                'C5' => [
                    'detail' => ['Provinsi' => 1, 'Sekolah' => 1] // 2 certs
                ]
            ],
            'Zahra Melati' => [
                'C1' => 95,
                'C2' => 20, // (> 4 juta)
                'C3' => 20, // (≤ 2 orang)
                'C4' => [
                    'detail' => ['Internasional' => 2, 'Nasional' => 1, 'Provinsi' => 1] // 4 certs (might hit 100 cap)
                ],
                'C5' => [
                    'detail' => ['Internasional' => 1, 'Nasional' => 2, 'Provinsi' => 1] // 4 certs
                ]
            ],
            'Agus Saputra' => [
                'C1' => 86,
                'C2' => 80, // (1-2 juta)
                'C3' => 80, // (5 orang)
                'C4' => [
                    'detail' => ['Nasional' => 1, 'Provinsi' => 1, 'Kabupaten/Kota' => 1] // 3 certs
                ],
                'C5' => [
                    'detail' => ['Provinsi' => 1, 'Kabupaten/Kota' => 1] // 2 certs
                ]
            ],
            'Bella Safira' => [
                'C1' => 90,
                'C2' => 40, // (3-4 juta)
                'C3' => 60, // (4 orang)
                'C4' => [
                    'detail' => ['Internasional' => 1, 'Nasional' => 1, 'Provinsi' => 1, 'Sekolah' => 1] // 4 certs
                ],
                'C5' => [
                    'detail' => ['Internasional' => 1, 'Nasional' => 1, 'Sekolah' => 1] // 3 certs
                ]
            ],
            'Dedy Firmansyah' => [
                'C1' => 83,
                'C2' => 100, // (≤ 1 juta)
                'C3' => 100, // (≥ 6 orang)
                'C4' => [
                    'detail' => ['Provinsi' => 1, 'Kabupaten/Kota' => 1, 'Sekolah' => 1] // 3 certs
                ],
                'C5' => [
                    'detail' => ['Nasional' => 1, 'Provinsi' => 1] // 2 certs
                ]
            ],
            'Erika Putri' => [
                'C1' => 92,
                'C2' => 20, // (> 4 juta)
                'C3' => 20, // (≤ 2 orang)
                'C4' => [
                    'detail' => ['Internasional' => 2, 'Nasional' => 1, 'Provinsi' => 1] // 4 certs (might hit 100 cap)
                ],
                'C5' => [
                    'detail' => ['Internasional' => 1, 'Nasional' => 1, 'Provinsi' => 1] // 3 certs
                ]
            ]
        ];
    }
}