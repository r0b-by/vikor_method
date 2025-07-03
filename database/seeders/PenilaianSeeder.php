<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon; // Import Carbon for date handling
use App\Models\AcademicPeriod; // Import AcademicPeriod model
use App\Models\Alternatif; // Import Alternatif model
use App\Models\Criteria; // Import Criteria model
use App\Models\Penilaian; // Import Penilaian model to use its helper method

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
            $studentName = $alternatif->user->name ?? $alternatif->alternatif_name;
            
            if (!isset($studentEvaluations[$studentName])) {
                $this->command->warn("Data evaluasi tidak ditemukan untuk: {$studentName}");
                continue;
            }

            foreach ($criterias as $criteria) {
                $criteriaCode = $criteria->criteria_code;
                
                if (!isset($studentEvaluations[$studentName][$criteriaCode])) {
                    $nilai = 0;
                    $certificateDetailsJson = null;
                } else {
                    $evaluationData = $studentEvaluations[$studentName][$criteriaCode];
                    
                    if ($criteria->input_type === 'poin' && is_array($evaluationData) && isset($evaluationData['detail'])) {
                        $certificateDetails = $this->formatCertificateDetails($evaluationData['detail'], $criteria);
                        $nilai = $this->calculateTotalPoints($certificateDetails);
                        $certificateDetailsJson = json_encode($certificateDetails);
                    } else {
                        $nilai = is_array($evaluationData) ? ($evaluationData['nilai'] ?? 0) : $evaluationData;
                        $certificateDetailsJson = null;
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
                    'point' => $subCriteria->point,
                    'sub_total' => $subCriteria->point * $count
                ];
            } else {
                $this->command->warn("Sub kriteria '{$level}' tidak ditemukan untuk kriteria {$criteria->criteria_code}");
            }
        }
        
        return $formatted;
    }

    /**
     * Calculate total points from certificate details
     */
    private function calculateTotalPoints(array $certificateDetails): int
    {
        return array_reduce($certificateDetails, fn($total, $cert) => $total + ($cert['sub_total'] ?? 0), 0);
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
                'C1' => 88.84,
                'C2' => 80,
                'C3' => 80,
                'C4' => [
                    'nilai' => 32,
                    'detail' => ['Provinsi' => 2, 'Kabupaten/Kota' => 2, 'Sekolah' => 1]
                ],
                'C5' => [
                    'nilai' => 18,
                    'detail' => ['Provinsi' => 1, 'Kabupaten/Kota' => 1, 'Partisipasi' => 2]
                ]
            ],
            'Budi Santoso' => [
                'C1' => 63.91,
                'C2' => 40,
                'C3' => 60,
                'C4' => [
                    'nilai' => 32,
                    'detail' => ['Nasional' => 2, 'Sekolah' => 2, 'Partisipasi' => 2]
                ],
                'C5' => [
                    'nilai' => 32,
                    'detail' => ['Nasional' => 1, 'Provinsi' => 2, 'Kabupaten/Kota' => 1]
                ]
            ],
            'Citra Dewi' => [
                'C1' => 97.43,
                'C2' => 100,
                'C3' => 40,
                'C4' => [
                    'nilai' => 8,
                    'detail' => ['Sekolah' => 1, 'Partisipasi' => 2]
                ],
                'C5' => [
                    'nilai' => 38,
                    'detail' => ['Nasional' => 1, 'Provinsi' => 2, 'Kabupaten/Kota' => 1, 'Sekolah' => 1, 'Partisipasi' => 1]
                ]
            ],
            'Dian Purnama' => [
                'C1' => 77.02,
                'C2' => 40,
                'C3' => 80,
                'C4' => [
                    'nilai' => 20,
                    'detail' => ['Nasional' => 1, 'Sekolah' => 2, 'Partisipasi' => 1]
                ],
                'C5' => [
                    'nilai' => 52,
                    'detail' => ['Nasional' => 2, 'Provinsi' => 2, 'Kabupaten/Kota' => 2, 'Partisipasi' => 2]
                ]
            ],
            'Eka Ramadhan' => [
                'C1' => 79.43,
                'C2' => 20,
                'C3' => 100,
                'C4' => [
                    'nilai' => 40,
                    'detail' => ['Provinsi' => 2, 'Kabupaten/Kota' => 2, 'Sekolah' => 2, 'Partisipasi' => 2]
                ],
                'C5' => [
                    'nilai' => 28,
                    'detail' => ['Nasional' => 2, 'Provinsi' => 1]
                ]
            ],
            'Farah Aulia' => [
                'C1' => 88.78,
                'C2' => 80,
                'C3' => 100,
                'C4' => [
                    'nilai' => 40,
                    'detail' => ['Nasional' => 1, 'Provinsi' => 1, 'Kabupaten/Kota' => 2, 'Sekolah' => 2, 'Partisipasi' => 1]
                ],
                'C5' => [
                    'nilai' => 32,
                    'detail' => ['Nasional' => 1, 'Provinsi' => 1, 'Kabupaten/Kota' => 2, 'Partisipasi' => 1]
                ]
            ],
            'Gilang Saputra' => [
                'C1' => 70.95,
                'C2' => 40,
                'C3' => 40,
                'C4' => [
                    'nilai' => 18,
                    'detail' => ['Nasional' => 1, 'Sekolah' => 1, 'Partisipasi' => 2]
                ],
                'C5' => [
                    'nilai' => 20,
                    'detail' => ['Kabupaten/Kota' => 2, 'Sekolah' => 2]
                ]
            ],
            'Hana Salsabila' => [
                'C1' => 62.23,
                'C2' => 100,
                'C3' => 40,
                'C4' => [
                    'nilai' => 40,
                    'detail' => ['Nasional' => 2, 'Kabupaten/Kota' => 2, 'Sekolah' => 2]
                ],
                'C5' => [
                    'nilai' => 48,
                    'detail' => ['Nasional' => 1, 'Provinsi' => 2, 'Kabupaten/Kota' => 2, 'Sekolah' => 2, 'Partisipasi' => 1]
                ]
            ],
            'Indra Wijaya' => [
                'C1' => 81.38,
                'C2' => 80,
                'C3' => 100,
                'C4' => [
                    'nilai' => 8,
                    'detail' => ['Sekolah' => 1, 'Partisipasi' => 2]
                ],
                'C5' => [
                    'nilai' => 44,
                    'detail' => ['Nasional' => 2, 'Kabupaten/Kota' => 2, 'Sekolah' => 2, 'Partisipasi' => 2]
                ]
            ],
            'Joko Prasetyo' => [
                'C1' => 65.33,
                'C2' => 60,
                'C3' => 100,
                'C4' => [
                    'nilai' => 36,
                    'detail' => ['Provinsi' => 2, 'Kabupaten/Kota' => 2, 'Sekolah' => 2]
                ],
                'C5' => [
                    'nilai' => 12,
                    'detail' => ['Provinsi' => 1, 'Partisipasi' => 2]
                ]
            ],
            'Kurniawan Hidayat' => [
                'C1' => 73.34,
                'C2' => 100,
                'C3' => 60,
                'C4' => [
                    'nilai' => 16,
                    'detail' => ['Kabupaten/Kota' => 1, 'Sekolah' => 2, 'Partisipasi' => 1]
                ],
                'C5' => [
                    'nilai' => 36,
                    'detail' => ['Nasional' => 2, 'Kabupaten/Kota' => 2, 'Partisipasi' => 2]
                ]
            ],
            'Lestari Dewi' => [
                'C1' => 62.90,
                'C2' => 60,
                'C3' => 60,
                'C4' => [
                    'nilai' => 38,
                    'detail' => ['Nasional' => 2, 'Provinsi' => 2, 'Partisipasi' => 1]
                ],
                'C5' => [
                    'nilai' => 32,
                    'detail' => ['Nasional' => 1, 'Provinsi' => 1, 'Kabupaten/Kota' => 1, 'Sekolah' => 2]
                ]
            ],
            'Mahmud Risky' => [
                'C1' => 96.89,
                'C2' => 20,
                'C3' => 40,
                'C4' => [
                    'nilai' => 34,
                    'detail' => ['Nasional' => 1, 'Provinsi' => 1, 'Kabupaten/Kota' => 2, 'Partisipasi' => 2]
                ],
                'C5' => [
                    'nilai' => 16,
                    'detail' => ['Nasional' => 1, 'Sekolah' => 1, 'Partisipasi' => 1]
                ]
            ],
            'Nadya Amelia' => [
                'C1' => 96.09,
                'C2' => 20,
                'C3' => 20,
                'C4' => [
                    'nilai' => 26,
                    'detail' => ['Nasional' => 1, 'Provinsi' => 1, 'Sekolah' => 2]
                ],
                'C5' => [
                    'nilai' => 38,
                    'detail' => ['Nasional' => 2, 'Provinsi' => 1, 'Kabupaten/Kota' => 1, 'Partisipasi' => 2]
                ]
            ],
            'Omar Zaki' => [
                'C1' => 92.50,
                'C2' => 80,
                'C3' => 100,
                'C4' => [
                    'nilai' => 24,
                    'detail' => ['Nasional' => 2, 'Sekolah' => 1]
                ],
                'C5' => [
                    'nilai' => 30,
                    'detail' => ['Provinsi' => 1, 'Kabupaten/Kota' => 2, 'Sekolah' => 2, 'Partisipasi' => 1]
                ]
            ],
            'Putri Ayu' => [
                'C1' => 66.14,
                'C2' => 20,
                'C3' => 20,
                'C4' => [
                    'nilai' => 36,
                    'detail' => ['Nasional' => 1, 'Provinsi' => 2, 'Sekolah' => 2, 'Partisipasi' => 1]
                ],
                'C5' => [
                    'nilai' => 32,
                    'detail' => ['Provinsi' => 1, 'Kabupaten/Kota' => 2, 'Sekolah' => 2, 'Partisipasi' => 2]
                ]
            ],
            'Qori Rahma' => [
                'C1' => 90.90,
                'C2' => 80,
                'C3' => 20,
                'C4' => [
                    'nilai' => 28,
                    'detail' => ['Nasional' => 1, 'Provinsi' => 1, 'Kabupaten/Kota' => 1, 'Sekolah' => 1]
                ],
                'C5' => [
                    'nilai' => 16,
                    'detail' => ['Provinsi' => 1, 'Kabupaten/Kota' => 1, 'Partisipasi' => 1]
                ]
            ],
            'Rizky Fadilah' => [
                'C1' => 74.70,
                'C2' => 80,
                'C3' => 100,
                'C4' => [
                    'nilai' => 38,
                    'detail' => ['Nasional' => 1, 'Provinsi' => 1, 'Kabupaten/Kota' => 2, 'Sekolah' => 2]
                ],
                'C5' => [
                    'nilai' => 36,
                    'detail' => ['Nasional' => 1, 'Provinsi' => 2, 'Kabupaten/Kota' => 1, 'Partisipasi' => 2]
                ]
            ],
            'Siti Nurhaliza' => [
                'C1' => 92.22,
                'C2' => 40,
                'C3' => 20,
                'C4' => [
                    'nilai' => 24,
                    'detail' => ['Nasional' => 2, 'Partisipasi' => 2]
                ],
                'C5' => [
                    'nilai' => 58,
                    'detail' => ['Nasional' => 2, 'Provinsi' => 2, 'Kabupaten/Kota' => 2, 'Sekolah' => 2, 'Partisipasi' => 1]
                ]
            ],
            'Taufik Hidayat' => [
                'C1' => 61.52,
                'C2' => 80,
                'C3' => 20,
                'C4' => [
                    'nilai' => 34,
                    'detail' => ['Nasional' => 1, 'Provinsi' => 1, 'Kabupaten/Kota' => 1, 'Sekolah' => 2, 'Partisipasi' => 1]
                ],
                'C5' => [
                    'nilai' => 44,
                    'detail' => ['Nasional' => 2, 'Provinsi' => 1, 'Kabupaten/Kota' => 2, 'Sekolah' => 1]
                ]
            ],
            'Umar Alfaruq' => [
                'C1' => 89.25,
                'C2' => 80,
                'C3' => 60,
                'C4' => [
                    'nilai' => 36,
                    'detail' => ['Nasional' => 2, 'Kabupaten/Kota' => 2, 'Partisipasi' => 2]
                ],
                'C5' => [
                    'nilai' => 18,
                    'detail' => ['Nasional' => 1, 'Kabupaten/Kota' => 1, 'Partisipasi' => 1]
                ]
            ],
            'Vina Maharani' => [
                'C1' => 73.61,
                'C2' => 80,
                'C3' => 20,
                'C4' => [
                    'nilai' => 22,
                    'detail' => ['Provinsi' => 2, 'Kabupaten/Kota' => 1]
                ],
                'C5' => [
                    'nilai' => 16,
                    'detail' => ['Provinsi' => 1, 'Kabupaten/Kota' => 1, 'Partisipasi' => 1]
                ]
            ],
            'Wahyu Pradana' => [
                'C1' => 82.35,
                'C2' => 80,
                'C3' => 80,
                'C4' => [
                    'nilai' => 24,
                    'detail' => ['Nasional' => 1, 'Kabupaten/Kota' => 2, 'Partisipasi' => 1]
                ],
                'C5' => [
                    'nilai' => 12,
                    'detail' => ['Kabupaten/Kota' => 1, 'Sekolah' => 1, 'Partisipasi' => 1]
                ]
            ],
            'Xavier Muhammad' => [
                'C1' => 69.51,
                'C2' => 80,
                'C3' => 40,
                'C4' => [
                    'nilai' => 22,
                    'detail' => ['Nasional' => 1, 'Kabupaten/Kota' => 2]
                ],
                'C5' => [
                    'nilai' => 26,
                    'detail' => ['Nasional' => 2, 'Kabupaten/Kota' => 1]
                ]
            ],
            'Yusuf Kurnia' => [
                'C1' => 74.40,
                'C2' => 20,
                'C3' => 40,
                'C4' => [
                    'nilai' => 34,
                    'detail' => ['Nasional' => 1, 'Provinsi' => 1, 'Kabupaten/Kota' => 2, 'Sekolah' => 1]
                ],
                'C5' => [
                    'nilai' => 20,
                    'detail' => ['Provinsi' => 1, 'Sekolah' => 2, 'Partisipasi' => 2]
                ]
            ],
            'Zahra Melati' => [
                'C1' => 83.67,
                'C2' => 40,
                'C3' => 80,
                'C4' => [
                    'nilai' => 16,
                    'detail' => ['Provinsi' => 2]
                ],
                'C5' => [
                    'nilai' => 32,
                    'detail' => ['Provinsi' => 1, 'Kabupaten/Kota' => 2, 'Sekolah' => 2, 'Partisipasi' => 2]
                ]
            ],
            'Agus Saputra' => [
                'C1' => 64.17,
                'C2' => 60,
                'C3' => 40,
                'C4' => [
                    'nilai' => 18,
                    'detail' => ['Nasional' => 1, 'Sekolah' => 2]
                ],
                'C5' => [
                    'nilai' => 36,
                    'detail' => ['Nasional' => 2, 'Provinsi' => 1, 'Kabupaten/Kota' => 1, 'Partisipasi' => 1]
                ]
            ],
            'Bella Safira' => [
                'C1' => 83.36,
                'C2' => 40,
                'C3' => 60,
                'C4' => [
                    'nilai' => 30,
                    'detail' => ['Nasional' => 2, 'Sekolah' => 2, 'Partisipasi' => 1]
                ],
                'C5' => [
                    'nilai' => 46,
                    'detail' => ['Nasional' => 2, 'Provinsi' => 1, 'Kabupaten/Kota' => 2, 'Sekolah' => 1, 'Partisipasi' => 1]
                ]
            ],
            'Dedy Firmansyah' => [
                'C1' => 76.42,
                'C2' => 20,
                'C3' => 40,
                'C4' => [
                    'nilai' => 32,
                    'detail' => ['Nasional' => 1, 'Provinsi' => 1, 'Kabupaten/Kota' => 2, 'Partisipasi' => 1]
                ],
                'C5' => [
                    'nilai' => 34,
                    'detail' => ['Nasional' => 1, 'Provinsi' => 1, 'Kabupaten/Kota' => 1, 'Sekolah' => 2, 'Partisipasi' => 1]
                ]
            ],
            'Erika Putri' => [
                'C1' => 94.37,
                'C2' => 80,
                'C3' => 60,
                'C4' => [
                    'nilai' => 38,
                    'detail' => ['Nasional' => 1, 'Provinsi' => 2, 'Kabupaten/Kota' => 1, 'Sekolah' => 1, 'Partisipasi' => 1]
                ],
                'C5' => [
                    'nilai' => 30,
                    'detail' => ['Nasional' => 2, 'Provinsi' => 1, 'Partisipasi' => 1]
                ]
            ]
        ];
    }
}
