<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class PenilaianSeeder extends Seeder
{
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('penilaians')->truncate();
        Schema::enableForeignKeyConstraints();

        $studentEvaluations = $this->getStudentEvaluationData();
        $criterias = DB::table('criterias')->get();
        $alternatifs = DB::table('alternatifs')
            ->join('users', 'alternatifs.user_id', '=', 'users.id')
            ->select('alternatifs.id', 'users.name')
            ->get();

        $penilaianData = [];
        
        foreach ($alternatifs as $alternatif) {
            foreach ($criterias as $criteria) {
                $criteriaCode = $criteria->criteria_code;

                // Coba dapatkan data evaluasi dari $studentEvaluations
                // Jika tidak ditemukan, default ke array dengan 'nilai' 0 dan 'detail' null
                $evaluationData = $studentEvaluations[$alternatif->name][$criteriaCode] ?? ['nilai' => 0, 'detail' => null];

                // Ekstrak nilai dan detail. Pastikan nilai selalu ada, default ke 0 jika tidak valid.
                $nilai = is_array($evaluationData) && isset($evaluationData['nilai']) ? $evaluationData['nilai'] : (is_scalar($evaluationData) ? $evaluationData : 0);
                $detail = is_array($evaluationData) && isset($evaluationData['detail']) ? $evaluationData['detail'] : null;

                // Tambahkan data penilaian ke array, sekarang tidak ada kondisi 'if ($evaluation)'
                $penilaianData[] = [
                    'id_alternatif' => $alternatif->id,
                    'id_criteria' => $criteria->id,
                    'nilai' => $nilai,
                    'certificate_details' => $detail ? json_encode($this->formatCertificateDetails($detail)) : null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        DB::table('penilaians')->insert($penilaianData);
    }

    private function formatCertificateDetails($detail)
    {
        $formatted = [];
        foreach ($detail as $level => $count) {
            $formatted[] = [
                'level' => $level,
                'count' => $count
            ];
        }
        return $formatted;
    }

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