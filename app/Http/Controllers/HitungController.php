<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\HasilVikor;
use App\Models\Criteria;
use App\Models\Penilaian;
use App\Models\Alternatif;
use App\Models\AcademicPeriod;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class HitungController extends Controller
{
    /**
     * Constructor to apply authorization middleware.
     * Only 'admin' and 'guru' can access methods in this controller.
     */
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin|guru']);
    }

    public function index(Request $request)
    {
        $criterias = Criteria::all();
        $academicPeriods = AcademicPeriod::orderBy('tahun_ajaran', 'desc')->orderBy('semester')->get();

        $selectedTahunAjaran = $request->input('tahun_ajaran_hitung');
        $selectedSemester = $request->input('semester_hitung');

        // Determine the active or latest academic period if not explicitly selected
        if (!$selectedTahunAjaran && !$selectedSemester) {
            $activePeriod = AcademicPeriod::where('is_active', true)->first();
            if ($activePeriod) {
                $selectedTahunAjaran = $activePeriod->tahun_ajaran;
                $selectedSemester = $activePeriod->semester;
            } elseif ($academicPeriods->isNotEmpty()) {
                $latestPeriod = AcademicPeriod::orderBy('tahun_ajaran', 'desc')->orderBy('semester', 'desc')->first();
                if ($latestPeriod) {
                    $selectedTahunAjaran = $latestPeriod->tahun_ajaran;
                    $selectedSemester = $latestPeriod->semester;
                }
            }
        }

        $alternatifs = collect();
        $penilaians = collect();
        $decisionMatrix = [];
        $noDataMessage = null;
        $calculationErrorMessage = null; // New variable for calculation specific errors

        $currentCalculation = [
            'decisionMatrix' => [],
            'normalizedValues' => [],
            'weightedNormalizedValues' => [],
            'Si' => [],
            'Ri' => [],
            'finalValues' => [],
            'ranking' => [],
            'calculationPerformed' => false,
            'calculationComplete' => false,
            'calculationMessage' => 'Pilih Tahun Ajaran dan Semester di atas, lalu klik "Proses Perhitungan" untuk melihat hasil VIKOR.',
            'currentTahunAjaran' => $selectedTahunAjaran,
            'currentSemester' => $selectedSemester,
            'noDataMessage' => null,
            'isAcceptable' => false,
            'condition1' => false,
            'condition2' => false,
            'topStudents' => collect(),
            'showSaveButton' => false,
            'calculationSteps' => [],
            'stabilityConditions' => [
                'majority_rule' => ['v' => 0.7, 'condition' => false, 'Qi' => []],
                'consensus' => ['v' => 0.5, 'condition' => false, 'Qi' => []], // Added for completeness, default v
                'with_veto' => ['v' => 0.3, 'condition' => false, 'Qi' => []]
            ],
            'DQ' => 0,
            'Q_diff' => 0,
            'X_plus' => [], // Ideal Best Values
            'X_minus' => [], // Ideal Worst Values
            'S_plus' => 0, // Maximum S value
            'S_minus' => 0, // Minimum S value
            'R_plus' => 0, // Maximum R value
            'R_minus' => 0, // Minimum R value
            'v_value' => 0.5 // Default v value
        ];

        if ($selectedTahunAjaran && $selectedSemester) {
            $alternatifs = Alternatif::with('user')
                ->where('tahun_ajaran', $selectedTahunAjaran)
                ->where('semester', $selectedSemester)
                ->get();

            if ($alternatifs->isEmpty()) {
                $noDataMessage = 'Tidak ada data siswa untuk Tahun Ajaran ' . $selectedTahunAjaran . ' Semester ' . $selectedSemester . '.';
                $currentCalculation['noDataMessage'] = $noDataMessage;
            } else {
                $penilaians = Penilaian::with(['alternatif', 'criteria'])
                    ->whereIn('id_alternatif', $alternatifs->pluck('id'))
                    ->get();

                // Build initial decision matrix
                foreach ($alternatifs as $alt) {
                    foreach ($criterias as $c) {
                        $nilai = $penilaians->where('id_alternatif', $alt->id)
                                             ->where('id_criteria', $c->id)
                                             ->first();
                        $decisionMatrix[$alt->id][$c->id] = [
                            'value' => $nilai ? (float)$nilai->nilai : 0, // Ensure float
                            'criteria_type' => $c->criteria_type
                        ];
                    }
                }
                $currentCalculation['decisionMatrix'] = $decisionMatrix;
            }
        }

        // Only perform calculation if process_calculation flag is present and data exists
        if ($request->has('process_calculation') && $selectedTahunAjaran && $selectedSemester && !$alternatifs->isEmpty() && !$criterias->isEmpty()) {
            try {
                // Validate total criteria weights
                $totalWeight = $criterias->sum('weight');
                if (round($totalWeight, 4) !== 1.0) { // Using round for float comparison
                    throw ValidationException::withMessages([
                        'weight_error' => 'Total bobot kriteria harus 1.0. Saat ini total bobot adalah ' . round($totalWeight, 2) . '.'
                    ]);
                }

                // Filter alternatives to ensure all have complete criteria data
                $filteredAlternatifs = collect();
                $missingDataCount = 0;
                foreach ($alternatifs as $alt) {
                    $isComplete = true;
                    foreach ($criterias as $c) {
                        if (!isset($decisionMatrix[$alt->id][$c->id]) || $decisionMatrix[$alt->id][$c->id]['value'] === 0 && !in_array($c->id, $penilaians->where('id_alternatif', $alt->id)->where('nilai', '>', 0)->pluck('id_criteria')->toArray())) {
                            // This checks if the value is explicitly 0, or if it's missing (0 default) and not genuinely zero.
                            // A more robust check might be needed if 0 is a valid score.
                            // For now, let's assume missing data means 0, and we explicitly check if a record existed for it.
                            $penilaianExists = $penilaians->where('id_alternatif', $alt->id)->where('id_criteria', $c->id)->isNotEmpty();
                            if (!$penilaianExists) {
                                $isComplete = false;
                                break;
                            }
                        }
                    }
                    if ($isComplete) {
                        $filteredAlternatifs->push($alt);
                    } else {
                        $missingDataCount++;
                    }
                }

                if ($filteredAlternatifs->isEmpty()) {
                    throw ValidationException::withMessages([
                        'data_error' => 'Tidak ada alternatif dengan data penilaian yang lengkap untuk semua kriteria yang aktif.'
                    ]);
                }

                $alternatifs = $filteredAlternatifs; // Use filtered alternatives for calculation
                if ($missingDataCount > 0) {
                    $calculationErrorMessage = "Perhatian: $missingDataCount siswa diabaikan dari perhitungan karena data penilaian tidak lengkap.";
                }

                // Step 1: Decision Matrix (already built above)
                $currentCalculation['calculationSteps'][] = [
                    'title' => 'Matriks Keputusan (F)',
                    'data' => array_map(function($row) {
                        return array_map(fn($item) => $item['value'], $row);
                    }, $decisionMatrix),
                    'type' => 'decision_matrix',
                    'description' => 'Matriks keputusan awal yang berisi nilai-nilai alternatif untuk setiap kriteria.'
                ];

                // Step 2: Determine Ideal Best (X_plus) and Ideal Worst (X_minus) values
                $X_plus = [];
                $X_minus = [];
                foreach ($criterias as $c) {
                    // Collect values only for alternatives that are included in the calculation
                    $valuesForCriteria = collect();
                    foreach ($alternatifs as $alt) {
                        if (isset($decisionMatrix[$alt->id][$c->id])) {
                            $valuesForCriteria->push($decisionMatrix[$alt->id][$c->id]['value']);
                        }
                    }

                    if ($valuesForCriteria->isEmpty()) {
                        $X_plus[$c->id] = 0;
                        $X_minus[$c->id] = 0;
                        continue;
                    }

                    if ($c->criteria_type == 'Cost') {
                        $X_plus[$c->id] = $valuesForCriteria->min();
                        $X_minus[$c->id] = $valuesForCriteria->max();
                    } else { // Benefit criteria
                        $X_plus[$c->id] = $valuesForCriteria->max();
                        $X_minus[$c->id] = $valuesForCriteria->min();
                    }
                }
                $currentCalculation['X_plus'] = $X_plus;
                $currentCalculation['X_minus'] = $X_minus;
                $currentCalculation['calculationSteps'][] = [
                    'title' => 'Nilai Ideal Terbaik ($X^+$) dan Ideal Terburuk ($X^-$)',
                    'data' => [
                        'X_plus' => array_map(fn($val) => round($val, 4), $X_plus),
                        'X_minus' => array_map(fn($val) => round($val, 4), $X_minus)
                    ],
                    'type' => 'ideal_values',
                    'description' => 'Nilai ideal terbaik ($X^+$) dan ideal terburuk ($X^-$) untuk setiap kriteria.'
                ];

                // Step 3: Normalization
                $normalizedValues = [];
                foreach ($alternatifs as $alt) {
                    foreach ($criterias as $c) {
                        $currentValue = $decisionMatrix[$alt->id][$c->id]['value'] ?? 0;
                        $denom = $X_plus[$c->id] - $X_minus[$c->id];

                        if (abs($denom) < 1e-9) { // Check for near-zero denominator
                            $normalized = 0;
                        } else {
                            if ($c->criteria_type == 'Benefit') {
                                $normalized = ($X_plus[$c->id] - $currentValue) / $denom;
                            } else { // Cost criteria
                                $normalized = ($currentValue - $X_minus[$c->id]) / $denom;
                            }
                        }
                        $normalizedValues[$alt->id][$c->id] = $normalized; // Keep full precision
                    }
                }
                $currentCalculation['normalizedValues'] = array_map(function($row) {
                    return array_map(fn($val) => round($val, 4), $row); // Round for display only
                }, $normalizedValues);
                $currentCalculation['calculationSteps'][] = [
                    'title' => 'Normalisasi Matriks',
                    'data' => $currentCalculation['normalizedValues'],
                    'type' => 'normalization',
                    'description' => 'Matriks yang telah dinormalisasi menggunakan rumus VIKOR.'
                ];

                // Step 4: Weighted Normalized Values
                $weightedNormalizedValues = [];
                foreach ($normalizedValues as $altId => $normalizedRow) {
                    foreach ($criterias as $c) {
                        $normalizedVal = $normalizedRow[$c->id] ?? 0;
                        $weightedNormalizedValues[$altId][$c->id] = $c->weight * $normalizedVal; // Keep full precision
                    }
                }
                $currentCalculation['weightedNormalizedValues'] = array_map(function($row) {
                    return array_map(fn($val) => round($val, 4), $row); // Round for display only
                }, $weightedNormalizedValues);
                $currentCalculation['calculationSteps'][] = [
                    'title' => 'Matriks Normalisasi Terbobot',
                    'data' => $currentCalculation['weightedNormalizedValues'],
                    'type' => 'weighted_normalized',
                    'description' => 'Matriks normalisasi yang telah dikalikan dengan bobot kriteria.'
                ];

                // Step 5: Si and Ri values
                $Si = [];
                $Ri = [];
                foreach ($alternatifs as $alt) {
                    $sumWjRij = 0;
                    $maxWjRij = 0;

                    foreach ($criterias as $c) {
                        // Use full precision weighted normalized value
                        $weightedNormalizedValue = $weightedNormalizedValues[$alt->id][$c->id] ?? 0;

                        $sumWjRij += $weightedNormalizedValue;
                        if ($weightedNormalizedValue > $maxWjRij) {
                            $maxWjRij = $weightedNormalizedValue;
                        }
                    }
                    $Si[$alt->id] = $sumWjRij; // Keep full precision
                    $Ri[$alt->id] = $maxWjRij; // Keep full precision
                }
                $currentCalculation['Si'] = array_map(fn($val) => round($val, 4), $Si); // Round for display
                $currentCalculation['Ri'] = array_map(fn($val) => round($val, 4), $Ri); // Round for display

                // Calculate S and R ranges
                $S_values = array_values($Si);
                $currentCalculation['S_plus'] = !empty($S_values) ? max($S_values) : 0;
                $currentCalculation['S_minus'] = !empty($S_values) ? min($S_values) : 0;

                $R_values = array_values($Ri);
                $currentCalculation['R_plus'] = !empty($R_values) ? max($R_values) : 0;
                $currentCalculation['R_minus'] = !empty($R_values) ? min($R_values) : 0;

                $currentCalculation['calculationSteps'][] = [
                    'title' => 'Nilai Si dan Ri',
                    'data' => [
                        'Si' => $currentCalculation['Si'],
                        'Ri' => $currentCalculation['Ri'],
                        'S_plus' => round($currentCalculation['S_plus'], 4),
                        'S_minus' => round($currentCalculation['S_minus'], 4),
                        'R_plus' => round($currentCalculation['R_plus'], 4),
                        'R_minus' => round($currentCalculation['R_minus'], 4)
                    ],
                    'type' => 'si_ri',
                    'description' => 'Nilai Si (utility measure) dan Ri (regret measure) untuk setiap alternatif.'
                ];

                // Step 6: Qi values (default v=0.5)
                $finalValues = [];
                $denomS = ($currentCalculation['S_plus'] - $currentCalculation['S_minus']);
                $denomR = ($currentCalculation['R_plus'] - $currentCalculation['R_minus']);

                foreach ($Si as $altId => $s) {
                    $term1 = (abs($denomS) < 1e-9) ? 0 : ($s - $currentCalculation['S_minus']) / $denomS;
                    $term2 = (abs($denomR) < 1e-9) ? 0 : ($Ri[$altId] - $currentCalculation['R_minus']) / $denomR;
                    $finalValues[$altId] = $currentCalculation['v_value'] * $term1 + (1 - $currentCalculation['v_value']) * $term2; // Keep full precision
                }
                $currentCalculation['finalValues'] = array_map(fn($val) => round($val, 4), $finalValues); // Round for display

                // Calculate Qi for all stability conditions
                // Ensure consensus is based on v=0.5 to accurately represent the primary ranking
                $vValuesToTest = ['consensus' => 0.5, 'majority_rule' => 0.7, 'with_veto' => 0.3];
                foreach ($vValuesToTest as $key => $v) {
                    $conditionQi = [];
                    foreach ($Si as $altId => $s) {
                        $term1 = (abs($denomS) < 1e-9) ? 0 : ($s - $currentCalculation['S_minus']) / $denomS;
                        $term2 = (abs($denomR) < 1e-9) ? 0 : ($Ri[$altId] - $currentCalculation['R_minus']) / $denomR;
                        $conditionQi[$altId] = $v * $term1 + (1 - $v) * $term2; // Keep full precision
                    }
                    $currentCalculation['stabilityConditions'][$key]['Qi'] = array_map(fn($val) => round($val, 4), $conditionQi); // Round for display
                }
                // Update consensus condition
                $currentCalculation['stabilityConditions']['consensus']['condition'] = true; // Always true for the primary v_value

                $currentCalculation['calculationSteps'][] = [
                    'title' => 'Nilai Qi',
                    'data' => [
                        'finalValues' => $currentCalculation['finalValues'],
                        'v_value' => $currentCalculation['v_value'],
                        'term1_calculation' => '($S - S^-) / (S^+ - S^-)',
                        'term2_calculation' => '($R - R^-) / (R^+ - R^-)',
                        'formula' => 'Qi = v * (S - S^-)/(S^+ - S^-) + (1-v) * (R - R^-)/(R^+ - R^-)'
                    ],
                    'type' => 'qi_values',
                    'description' => 'Perhitungan nilai Qi menggunakan v = ' . $currentCalculation['v_value']
                ];

                // Step 7: Ranking with stability conditions
                $rankingResults = $this->getRanking(
                    $finalValues, // Pass full precision values
                    $Si,
                    $Ri,
                    $currentCalculation['stabilityConditions']
                );

                $currentCalculation['ranking'] = $rankingResults['ranking'];
                $currentCalculation['isAcceptable'] = $rankingResults['isAcceptable'];
                $currentCalculation['condition1'] = $rankingResults['condition1'];
                $currentCalculation['condition2'] = $rankingResults['condition2'];
                $currentCalculation['stabilityConditions'] = $rankingResults['stabilityConditions'];
                $currentCalculation['DQ'] = round($rankingResults['DQ'], 4);
                $currentCalculation['Q_diff'] = round($rankingResults['Q_diff'], 4);

                $currentCalculation['calculationSteps'][] = [
                    'title' => 'Perankingan dan Kondisi Penerimaan Solusi Kompromi',
                    'data' => [
                        'ranking' => $rankingResults['ranking'],
                        'isAcceptable' => $rankingResults['isAcceptable'],
                        'condition1' => $rankingResults['condition1'],
                        'condition2' => $rankingResults['condition2'],
                        'DQ' => $currentCalculation['DQ'],
                        'Q_diff' => $currentCalculation['Q_diff'],
                        'stabilityConditions' => $currentCalculation['stabilityConditions']
                    ],
                    'type' => 'ranking',
                    'description' => 'Hasil perankingan dan pemeriksaan kondisi penerimaan solusi kompromi (Acceptable Advantage dan Acceptable Stability).'
                ];

                // Get top N students (e.g., top 10)
                $rankedQValues = collect($finalValues)->sort()->keys()->all();
                $topNAltIds = array_slice($rankedQValues, 0, min(count($rankedQValues), 10)); // Top 10 or fewer if less than 10

                $currentCalculation['topStudents'] = $alternatifs->filter(function($alt) use ($topNAltIds) {
                    return in_array($alt->id, $topNAltIds);
                })->sortBy(function($alt) use ($currentCalculation) {
                    return $currentCalculation['ranking'][$alt->id];
                });

                $currentCalculation['calculationPerformed'] = true;
                $currentCalculation['calculationComplete'] = true;
                $currentCalculation['showSaveButton'] = true;
                $currentCalculation['calculationMessage'] = 'Perhitungan VIKOR berhasil dilakukan untuk Tahun Ajaran ' . $selectedTahunAjaran . ' Semester ' . $selectedSemester . '.';
                if ($calculationErrorMessage) {
                     $currentCalculation['calculationMessage'] .= " " . $calculationErrorMessage;
                }

            } catch (ValidationException $e) {
                // Flash errors to session to display in view
                return redirect()->back()->withErrors($e->errors())->withInput();
            } catch (\Exception $e) {
                $currentCalculation['calculationMessage'] = 'Terjadi kesalahan saat melakukan perhitungan: ' . $e->getMessage();
                report($e); // Log the error
            }
        } elseif ($selectedTahunAjaran && $selectedSemester) {
            $currentCalculation['calculationMessage'] = 'Tahun Ajaran: ' . $selectedTahunAjaran . ', Semester: ' . $selectedSemester . '. Klik "Proses Perhitungan" untuk melihat hasil VIKOR.';
        }

        $selectedTahunAjaranHistory = $request->input('tahun_ajaran_history');
        $selectedSemesterHistory = $request->input('semester_history');

        $historicalResultsQuery = HasilVikor::with(['alternatif.user']);
        if ($selectedTahunAjaranHistory) {
            $historicalResultsQuery->where('tahun_ajaran', $selectedTahunAjaranHistory);
        }
        if ($selectedSemesterHistory) {
            $historicalResultsQuery->where('semester', $selectedSemesterHistory);
        }

        $historicalResults = $historicalResultsQuery->orderBy('tanggal_penilaian', 'desc')
            ->orderBy('jam_penilaian', 'desc')
            ->get();

        $availableTahunAjarans = HasilVikor::select('tahun_ajaran')->distinct()->whereNotNull('tahun_ajaran')->pluck('tahun_ajaran');
        $availableSemesters = HasilVikor::select('semester')->distinct()->whereNotNull('semester')->pluck('semester');

        return view('dashboard.hitung', array_merge(
            $currentCalculation,
            [
                'criterias' => $criterias,
                'alternatifs' => $alternatifs, // This will contain filtered alternatives if calculation was performed
                'penilaians' => $penilaians, // This should still be all penilaians for initial matrix view
                'historicalResults' => $historicalResults,
                'academicPeriods' => $academicPeriods,
                'availableTahunAjarans' => $availableTahunAjarans,
                'availableSemesters' => $availableSemesters,
                'selectedTahunAjaranHistory' => $selectedTahunAjaranHistory,
                'selectedSemesterHistory' => $selectedSemesterHistory,
            ]
        ));
    }

    public function performCalculation(Request $request)
    {
        return $this->index($request->merge(['process_calculation' => true]));
    }

    public function simpan(Request $request)
    {
        $request->validate([
            'finalValues' => 'required|array',
            'finalValues.*' => 'nullable|numeric',
            'alternatif_ids' => 'required|array',
            'alternatif_ids.*' => 'required|exists:alternatifs,id',
            'ranking' => 'required|array',
            'ranking.*' => 'nullable|integer',
            'Si' => 'nullable|array',
            'Si.*' => 'nullable|numeric',
            'Ri' => 'nullable|array',
            'Ri.*' => 'nullable|numeric',
            'tahun_ajaran' => 'required|string|max:255',
            'semester' => 'required|string|max:255',
        ]);

        // Re-calculate the DQ and Q_diff to ensure consistency with the current data
        // This is important because the calculation might be done in one request and saved in another.
        // It helps ensure integrity if the data changes between calculation and save.
        $alternatifs = Alternatif::whereIn('id', $request->alternatif_ids)
                                  ->where('tahun_ajaran', $request->tahun_ajaran)
                                  ->where('semester', $request->semester)
                                  ->get();
        $criterias = Criteria::all();
        $penilaians = Penilaian::whereIn('id_alternatif', $alternatifs->pluck('id'))->get();

        $decisionMatrix = [];
        foreach ($alternatifs as $alt) {
            foreach ($criterias as $c) {
                $nilai = $penilaians->where('id_alternatif', $alt->id)
                                     ->where('id_criteria', $c->id)
                                     ->first();
                $decisionMatrix[$alt->id][$c->id] = [
                    'value' => $nilai ? (float)$nilai->nilai : 0,
                    'criteria_type' => $c->criteria_type
                ];
            }
        }

        $X_plus = [];
$X_minus = [];

foreach ($criterias as $c) {
    $valuesForCriteria = collect();

    foreach ($alternatifs as $alt) {
        if (isset($decisionMatrix[$alt->id][$c->id])) {
            $valuesForCriteria->push($decisionMatrix[$alt->id][$c->id]['value']);
        }
    }

    if ($valuesForCriteria->isEmpty()) {
        $X_plus[$c->id] = 0;
        $X_minus[$c->id] = 0;
        continue;
    }

    $X_plus[$c->id] = $valuesForCriteria->max(); // X+
    $X_minus[$c->id] = $valuesForCriteria->min(); // X-
}

$normalizedValues = [];

foreach ($alternatifs as $alt) {
    foreach ($criterias as $c) {
        $currentValue = $decisionMatrix[$alt->id][$c->id]['value'] ?? 0;
        $Xmax = $X_plus[$c->id];
        $Xmin = $X_minus[$c->id];
        $denom = $Xmax - $Xmin;

        if (abs($denom) < 1e-9) {
            $normalized = 0;
        } else {
            if (strtolower(trim($c->criteria_type)) == 'cost') {
                // ✅ Rumus cost benar: (Xij - Xmin) / (Xmax - Xmin)
                $normalized = ($currentValue - $Xmin) / $denom;
            } else {
                // ✅ Rumus benefit: (Xmax - Xij) / (Xmax - Xmin)
                $normalized = ($Xmax - $currentValue) / $denom;
            }
        }

        $normalizedValues[$alt->id][$c->id] = round($normalized, 4);
    }
}


        $weightedNormalizedValues = [];
        foreach ($normalizedValues as $altId => $normalizedRow) {
            foreach ($criterias as $c) {
                $normalizedVal = $normalizedRow[$c->id] ?? 0;
                $weightedNormalizedValues[$altId][$c->id] = $c->weight * $normalizedVal;
            }
        }

        $Si_recalculated = [];
        $Ri_recalculated = [];
        foreach ($alternatifs as $alt) {
            $sumWjRij = 0;
            $maxWjRij = 0;
            foreach ($criterias as $c) {
                $weightedNormalizedValue = $weightedNormalizedValues[$alt->id][$c->id] ?? 0;
                $sumWjRij += $weightedNormalizedValue;
                if ($weightedNormalizedValue > $maxWjRij) {
                    $maxWjRij = $weightedNormalizedValue;
                }
            }
            $Si_recalculated[$alt->id] = $sumWjRij;
            $Ri_recalculated[$alt->id] = $maxWjRij;
        }

        $S_values_recalculated = array_values($Si_recalculated);
        $S_plus_recalculated = !empty($S_values_recalculated) ? max($S_values_recalculated) : 0;
        $S_minus_recalculated = !empty($S_values_recalculated) ? min($S_values_recalculated) : 0;

        $R_values_recalculated = array_values($Ri_recalculated);
        $R_plus_recalculated = !empty($R_values_recalculated) ? max($R_values_recalculated) : 0;
        $R_minus_recalculated = !empty($R_values_recalculated) ? min($R_values_recalculated) : 0;

        $finalValues_recalculated = [];
        $denomS_recalculated = ($S_plus_recalculated - $S_minus_recalculated);
        $denomR_recalculated = ($R_plus_recalculated - $R_minus_recalculated);
        $v_value = 0.5; // Use default v_value for the primary ranking

        foreach ($Si_recalculated as $altId => $s) {
            $term1 = (abs($denomS_recalculated) < 1e-9) ? 0 : ($s - $S_minus_recalculated) / $denomS_recalculated;
            $term2 = (abs($denomR_recalculated) < 1e-9) ? 0 : ($Ri_recalculated[$altId] - $R_minus_recalculated) / $denomR_recalculated;
            $finalValues_recalculated[$altId] = $v_value * $term1 + (1 - $v_value) * $term2;
        }

        // Calculate DQ and Q_diff for saving purposes
        $m = count($finalValues_recalculated);
        $DQ = ($m > 1) ? 1 / ($m - 1) : 0;
        $sortedQValues = collect($finalValues_recalculated)->sort()->values()->all();
        $Q_A1 = $sortedQValues[0] ?? 0;
        $Q_A2 = $sortedQValues[1] ?? 0;
        $Q_diff = ($Q_A2 - $Q_A1);


        DB::beginTransaction();
        try {
            // Delete old data for the selected period
            HasilVikor::where('tahun_ajaran', $request->tahun_ajaran)
                ->where('semester', $request->semester)
                ->delete();

            $currentDate = now()->toDateString();
            $currentTime = now()->toTimeString();

            foreach ($request->alternatif_ids as $alternatifId) {
                // Use recalculated values for consistency and avoid relying on client-side data
                $nilaiQ = $finalValues_recalculated[$alternatifId] ?? 0;
                $rankingValue = $request->ranking[$alternatifId] ?? null; // Ranking can still come from request as it's sorted client-side
                $nilaiS = $Si_recalculated[$alternatifId] ?? 0;
                $nilaiR = $Ri_recalculated[$alternatifId] ?? 0;

                // Status based on Q value (application specific)
                $status = ($nilaiQ <= 0.5) ? 'Lulus' : 'Tidak Lulus';

                HasilVikor::create([
                    'id_alternatif' => $alternatifId,
                    'nilai_q' => round($nilaiQ, 4), // Store rounded value for Q
                    'ranking' => $rankingValue,
                    'nilai_s' => round($nilaiS, 4), // Store rounded value for S
                    'nilai_r' => round($nilaiR, 4), // Store rounded value for R
                    'status' => $status,
                    'tahun_ajaran' => $request->tahun_ajaran,
                    'semester' => $request->semester,
                    'tanggal_penilaian' => $currentDate,
                    'jam_penilaian' => $currentTime,
                    'dq_value' => round($DQ, 4), // Store DQ
                    'q_diff_value' => round($Q_diff, 4), // Store Q_diff
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            DB::commit();
            return redirect()->route('dashboard.hitung')->with('success', 'Hasil perhitungan VIKOR berhasil disimpan sebagai riwayat!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
        }
    }

    /**
     * Helper function to get ranking and check stability conditions.
     * @param array $finalValues Full precision Qi values (v=0.5)
     * @param array $Si Full precision Si values
     * @param array $Ri Full precision Ri values
     * @param array $stabilityConditions Initial stability conditions array
     * @return array
     */
    private function getRanking($finalValues, $Si, $Ri, $stabilityConditions)
    {
        $ranking = [];
        $isAcceptable = false;
        $condition1 = false;
        $condition2 = false;

        if (empty($finalValues)) {
            return [
                'ranking' => $ranking,
                'isAcceptable' => $isAcceptable,
                'condition1' => $condition1,
                'condition2' => $condition2,
                'stabilityConditions' => $stabilityConditions,
                'DQ' => 0,
                'Q_diff' => 0
            ];
        }

        // Sort by default Qi (v=0.5) to get the primary ranking
        asort($finalValues); // Sorts by value, maintaining key association

        $rankedAlternatives = [];
        $rank = 1;
        foreach ($finalValues as $altId => $value) {
            $rankedAlternatives[$rank] = ['id' => $altId, 'Q' => $value];
            $ranking[$altId] = $rank++;
        }

        // Check Acceptable Advantage (Condition 1)
        $m = count($finalValues);
        $DQ = 0;
        $Q_A1 = $rankedAlternatives[1]['Q'] ?? 0;
        $Q_A2 = $rankedAlternatives[2]['Q'] ?? null;
        $Q_diff = 0;

        if ($m > 1) {
            $DQ = 1 / ($m - 1);
            if ($Q_A2 !== null) {
                $Q_diff = $Q_A2 - $Q_A1;
                $condition1 = ($Q_diff >= $DQ);
            } else {
                $condition1 = false; // Only one alternative, so A2 doesn't exist for comparison
            }
        } else { // Only one alternative, it's always the best
            $condition1 = true;
        }

        // Check Stability Conditions (Condition 2)
        $condition2 = true; // Assume true initially, then set to false if any sub-condition fails

        $altIdRank1_V05 = $rankedAlternatives[1]['id'] ?? null;

        // Ensure v=0.7 (Majority Rule) is checked
        $majorityQi = $stabilityConditions['majority_rule']['Qi'];
        // Sort for 0.7v
        $majorityQiSorted = $majorityQi;
        asort($majorityQiSorted);
        $altIdRank1_V07 = key($majorityQiSorted);
        $stabilityConditions['majority_rule']['condition'] = ($altIdRank1_V05 === $altIdRank1_V07);
        $condition2 = $condition2 && $stabilityConditions['majority_rule']['condition'];

        // Ensure v=0.3 (With Veto) is checked
        $vetoQi = $stabilityConditions['with_veto']['Qi'];
        // Sort for 0.3v
        $vetoQiSorted = $vetoQi;
        asort($vetoQiSorted);
        $altIdRank1_V03 = key($vetoQiSorted);
        $stabilityConditions['with_veto']['condition'] = ($altIdRank1_V05 === $altIdRank1_V03);
        $condition2 = $condition2 && $stabilityConditions['with_veto']['condition'];

        // If condition1 is true, but condition2 is false, then multiple alternatives might be compromise solutions.
        // If condition1 is false, the top alternative is not significantly better.
        // VIKOR's "Acceptable solution" is when both conditions are met.
        $isAcceptable = $condition1 && $condition2;

        return [
            'ranking' => $ranking,
            'isAcceptable' => $isAcceptable,
            'condition1' => $condition1,
            'condition2' => $condition2,
            'stabilityConditions' => $stabilityConditions,
            'DQ' => $DQ,
            'Q_diff' => $Q_diff
        ];
    }
}