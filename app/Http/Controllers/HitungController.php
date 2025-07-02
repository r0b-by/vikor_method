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
        $noDataMessage = null;

        if ($selectedTahunAjaran && $selectedSemester) {
            $alternatifs = Alternatif::with('user')
                ->where('tahun_ajaran', $selectedTahunAjaran)
                ->where('semester', $selectedSemester)
                ->get();

            if ($alternatifs->isEmpty()) {
                $noDataMessage = 'Tidak ada data siswa untuk Tahun Ajaran ' . $selectedTahunAjaran . ' Semester ' . $selectedSemester . '.';
            }

            $penilaians = Penilaian::with(['alternatif', 'criteria'])
                ->whereIn('id_alternatif', $alternatifs->pluck('id'))
                ->get();
        }

        $currentCalculation = [
            'normalizedValues' => [],
            'weightedNormalization' => [],
            'ideal' => [],
            'Si' => [],
            'Ri' => [],
            'finalValues' => [],
            'ranking' => [],
            'calculationPerformed' => false,
            'calculationMessage' => 'Pilih Tahun Ajaran dan Semester di atas, lalu klik "Proses Perhitungan" untuk melihat hasil VIKOR.',
            'currentTahunAjaran' => $selectedTahunAjaran,
            'currentSemester' => $selectedSemester,
            'noDataMessage' => $noDataMessage
        ];

        if ($request->has('process_calculation') && $selectedTahunAjaran && $selectedSemester) {
            $expectedPenilaianCount = $alternatifs->count() * $criterias->count();

            if (
                !$criterias->isEmpty() &&
                !$alternatifs->isEmpty() &&
                !$penilaians->isEmpty() &&
                $penilaians->count() === $expectedPenilaianCount
            ) {
                $currentCalculation['normalizedValues'] = $this->getNormalisasi($criterias, $alternatifs, $penilaians);
                $currentCalculation['weightedNormalization'] = $this->getNormalisasiTerbobot($criterias, $currentCalculation['normalizedValues']);
                $currentCalculation['ideal'] = $this->getIdeal($currentCalculation['weightedNormalization']);
                $SiRi = $this->getSiRi($criterias, $alternatifs, $currentCalculation['weightedNormalization'], $currentCalculation['ideal']);
                $currentCalculation['Si'] = $SiRi['Si'];
                $currentCalculation['Ri'] = $SiRi['Ri'];
                $currentCalculation['finalValues'] = $this->getQi($currentCalculation['Si'], $currentCalculation['Ri']);
                $currentCalculation['ranking'] = $this->getRanking($currentCalculation['finalValues']);
                $currentCalculation['calculationPerformed'] = true;
                $currentCalculation['calculationMessage'] = 'Perhitungan VIKOR berhasil dilakukan untuk Tahun Ajaran ' . $selectedTahunAjaran . ' Semester ' . $selectedSemester . '.';
            } else {
                $currentCalculation['calculationMessage'] = 'Data penilaian tidak lengkap atau kosong untuk periode ini.';
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
                'alternatifs' => $alternatifs,
                'penilaians' => $penilaians,
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

    /**
     * Saves VIKOR calculation results as a new historical entry.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
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

        DB::beginTransaction();
        try {
            // Hapus hasil perhitungan yang sudah ada untuk tahun ajaran dan semester ini
            HasilVikor::where('tahun_ajaran', $request->tahun_ajaran)
                      ->where('semester', $request->semester)
                      ->delete();

            $currentDate = Carbon::now()->toDateString();
            $currentTime = Carbon::now()->toTimeString();

            foreach ($request->alternatif_ids as $alternatifId) {
                $nilaiQ = $request->finalValues[$alternatifId] ?? 0;
                $rankingValue = $request->ranking[$alternatifId] ?? null;
                $nilaiS = $request->Si[$alternatifId] ?? 0;
                $nilaiR = $request->Ri[$alternatifId] ?? 0;

                HasilVikor::create([
                    'id_alternatif' => $alternatifId,
                    'nilai_q' => $nilaiQ,
                    'ranking' => $rankingValue,
                    'nilai_s' => $nilaiS,
                    'nilai_r' => $nilaiR,
                    'status' => ($rankingValue !== null && $rankingValue <= 10) ? 'Lulus' : 'Tidak Lulus',
                    'tahun_ajaran' => $request->tahun_ajaran,
                    'semester' => $request->semester,
                    'tanggal_penilaian' => $currentDate,
                    'jam_penilaian' => $currentTime,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            DB::commit();
            return redirect()->route('hitung.index')->with('success', 'Hasil perhitungan VIKOR berhasil disimpan sebagai riwayat!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
        }
    }


    // --- Metode-metode perhitungan VIKOR (dengan perbaikan kecil dan konsistensi) ---
    private function getNormalisasi($criterias, $alternatifs, $penilaians)
    {
        $normalizedValues = [];
        foreach ($criterias as $c) {
            $valuesForCriteria = $penilaians->where('id_criteria', $c->id);
            $nilaiCollection = $valuesForCriteria->pluck('nilai');

            if ($nilaiCollection->isEmpty()) {
                 foreach ($alternatifs as $alt) {
                    $normalizedValues[$alt->id][$c->id] = 0;
                }
                continue;
            }

            $maxVal = $nilaiCollection->max();
            $minVal = $nilaiCollection->min();
            $range = ($maxVal - $minVal);

            foreach ($alternatifs as $alt) {
                $value = $penilaians->where('id_alternatif', $alt->id)
                                    ->where('id_criteria', $c->id)
                                    ->first();

                if (!$value) {
                    $normalizedValues[$alt->id][$c->id] = 0;
                    continue;
                }

                if ($range == 0) { // Menghindari pembagian dengan nol
                    $normalized = 0;
                } else {
                    $normalized = ($c->criteria_type == 'Cost')
                        ? ($maxVal - $value->nilai) / $range
                        : ($value->nilai - $minVal) / $range;
                }

                $normalizedValues[$alt->id][$c->id] = round($normalized, 3);
            }
        }
        return $normalizedValues;
    }

    private function getNormalisasiTerbobot($criterias, $normalized)
    {
        $weightedNormalization = [];
        foreach ($normalized as $altId => $row) {
            foreach ($criterias as $c) {
                $normalizedValue = $row[$c->id] ?? 0;
                $weightedNormalization[$altId][$c->id] = round($c->weight * $normalizedValue, 3);
            }
        }
        return $weightedNormalization;
    }

    private function getIdeal($weightedNormalization)
    {
        $ideal = [];
        if (empty($weightedNormalization)) {
            return $ideal;
        }
        // Ambil semua ID kriteria dari baris pertama (alternatif pertama)
        $criteriaIds = array_keys(reset($weightedNormalization));

        foreach ($criteriaIds as $criteriaId) {
            $columnValues = array_column($weightedNormalization, $criteriaId);
            // Pastikan array tidak kosong sebelum memanggil max/min
            if (empty($columnValues)) {
                $ideal[$criteriaId] = 0;
            } else {
                $ideal[$criteriaId] = max($columnValues); // Ideal terbaik adalah nilai maksimum
            }
        }
        return $ideal;
    }

    private function getSiRi($criterias, $alternatifs, $weightedNormalization, $ideal)
    {
        $Si = [];
        $Ri = [];

        foreach ($alternatifs as $alt) {
            $selisih = [];
            foreach ($criterias as $c) {
                $vij = $weightedNormalization[$alt->id][$c->id] ?? 0;
                $idealVal = $ideal[$c->id] ?? 0;
                $selisih[] = abs($idealVal - $vij);
            }
            $Si[$alt->id] = round(array_sum($selisih), 6);
            $Ri[$alt->id] = round(max($selisih), 6);
        }

        return ['Si' => $Si, 'Ri' => $Ri];
    }

    private function getQi($Si, $Ri)
    {
        $V = 0.5; // Bobot strategi 'mayoritas' vs 'individu'
        $finalValues = [];

        if (empty($Si) || empty($Ri)) {
            return $finalValues;
        }

        $Smax = max($Si);
        $Smin = min($Si);
        $Rmax = max($Ri);
        $Rmin = min($Ri);

        $denomS = ($Smax - $Smin);
        $denomR = ($Rmax - $Rmin);

        foreach ($Si as $altId => $s) {
            $val1 = ($denomS == 0) ? 0 : ($s - $Smin) / $denomS;
            $val2 = ($denomR == 0) ? 0 : ($Ri[$altId] - $Rmin) / $denomR;
            $finalValues[$altId] = round($V * $val1 + (1 - $V) * $val2, 3);
        }

        return $finalValues;
    }

    private function getRanking($finalValues)
    {
        $ranking = [];
        if (empty($finalValues)) {
            return $ranking;
        }
        asort($finalValues); // Urutkan dari nilai Qi terkecil (terbaik)
        $rank = 1;
        foreach ($finalValues as $altId => $value) {
            $ranking[$altId] = $rank++;
        }
        return $ranking;
    }

    // Metode ini tidak lagi digunakan untuk filtering penilaians karena kolom sudah dihapus
    // Filter dilakukan pada alternatif terlebih dahulu.
    private function getFilteredPenilaians(Request $request)
    {
        // This method is no longer directly used for filtering penilaians by tahun_ajaran/semester
        // as those columns have been removed from the penilaians table.
        // Penilaians are now fetched based on filtered Alternatifs.
        return Penilaian::all(); // Or modify as needed if you need a different subset
    }

    private function getAvailablePeriodsForHistory()
    {
        $availableTahunAjarans = HasilVikor::select('tahun_ajaran')
                                            ->distinct()
                                            ->whereNotNull('tahun_ajaran')
                                            ->pluck('tahun_ajaran');

        $availableSemesters = HasilVikor::select('semester')
                                           ->distinct()
                                           ->whereNotNull('semester')
                                           ->pluck('semester');
        return [
            'availableTahunAjarans' => $availableTahunAjarans,
            'availableSemesters' => $availableSemesters,
        ];
    }
}
