<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\HasilVikor;
use App\Models\criteria;
use App\Models\penilaian;
use App\Models\alternatif;
use Illuminate\Http\Request;

class HitungController extends Controller
{
    /**
     * Constructor to apply authorization middleware.
     * Only 'admin' and 'guru' can access methods in this controller.
     */
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin|guru']); // Ensures only admin or guru
    }

    /**
     * Displays the calculation page and performs VIKOR calculations.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $criterias = criteria::all();
        $alternatifs = alternatif::all();
        $penilaians = penilaian::with(['alternatif', 'criteria'])->get();

        // Initialize variables for VIKOR calculation results
        $normalizedValues = [];
        $weightedNormalization = [];
        $ideal = [];
        $Si = [];
        $Ri = [];
        $finalValues = [];
        $ranking = [];
        $calculationPerformed = false; // Flag to check if calculation was performed

        // Perform VIKOR calculation only if all necessary data is available
        if (!$criterias->isEmpty() && !$alternatifs->isEmpty() && !$penilaians->isEmpty()) {
            // Perform VIKOR calculations
            $normalizedValues = $this->getNormalisasi($criterias, $alternatifs, $penilaians);
            $weightedNormalization = $this->getNormalisasiTerbobot($criterias, $normalizedValues);
            $ideal = $this->getIdeal($weightedNormalization);
            $SiRi = $this->getSiRi($criterias, $alternatifs, $weightedNormalization, $ideal);
            $Si = $SiRi['Si'];
            $Ri = $SiRi['Ri'];
            $finalValues = $this->getQi($Si, $Ri);
            $ranking = $this->getRanking($finalValues);
            $calculationPerformed = true;

            // Save new VIKOR results
            DB::beginTransaction();
            try {
                HasilVikor::truncate(); // Clear all old data
                foreach ($alternatifs as $key => $alt) {
                    // Ensure indexes $key exist in $Si, $Ri, $finalValues, and $ranking
                    $nilaiS = $Si[$key] ?? 0;
                    $nilaiR = $Ri[$key] ?? 0;
                    $nilaiQ = $finalValues[$key] ?? 0;
                    $rankingAlt = $ranking[$key] ?? null;

                    HasilVikor::create([
                        'id_alternatif' => $alt->id,
                        'nilai_s' => $nilaiS,
                        'nilai_r' => $nilaiR,
                        'nilai_q' => $nilaiQ,
                        'ranking' => $rankingAlt,
                        'status' => ($rankingAlt !== null && $rankingAlt <= 10) ? 'Lulus' : 'Tidak Lulus', // Status logic can be adjusted
                    ]);
                }
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                // Instead of redirecting, set a session error message
                session()->flash('error', 'Gagal menyimpan hasil perhitungan: ' . $e->getMessage());
            }
        } else {
            // Set a session warning if data is incomplete but still display the page
            session()->flash('warning', 'Data kriteria, alternatif, atau penilaian tidak lengkap. Perhitungan VIKOR tidak dapat dilakukan.');
        }


        return view('dashboard.hitung', [
            'criteria' => $criterias,
            'alternatif' => $alternatifs,
            'penilaian' => $penilaians,
            'normalisasi' => $normalizedValues,
            'weightedNormalization' => $weightedNormalization,
            'ideal' => $ideal,
            'Si' => $Si,
            'Ri' => $Ri,
            'finalValues' => $finalValues,
            'ranking' => $ranking,
            'calculationPerformed' => $calculationPerformed, // Pass the flag to the view
        ]);
    }

    /**
     * Calculates normalized values.
     *
     * @param \Illuminate\Database\Eloquent\Collection $criterias
     * @param \Illuminate\Database\Eloquent\Collection $alternatifs
     * @param \Illuminate\Database\Eloquent\Collection $penilaians
     * @return array
     */
    private function getNormalisasi($criterias, $alternatifs, $penilaians)
    {
        $normalizedValues = [];
        foreach ($criterias as $keyColumn => $c) {
            $values = $penilaians->where('id_criteria', $c->id);
            $nilai = $values->pluck('nilai')->toArray();
            
            if (empty($nilai)) {
                foreach ($alternatifs as $keyRow => $a) {
                    $normalizedValues[$keyRow][$keyColumn] = 0;
                }
                continue;
            }

            $maxVal = max($nilai);
            $minVal = min($nilai);
            $range = ($maxVal - $minVal) == 0 ? 1 : $maxVal - $minVal;

            foreach ($alternatifs as $keyRow => $a) {
                $value = $values->where('id_alternatif', $a->id)->first();
                if (!$value) {
                    $normalizedValues[$keyRow][$keyColumn] = 0;
                    continue;
                }

                $normalized = ($value->criteria->criteria_type == 'Cost')
                    ? ($maxVal - $value->nilai) / $range
                    : ($value->nilai - $minVal) / $range;

                $normalizedValues[$keyRow][$keyColumn] = round($normalized, 3);
            }
        }
        return $normalizedValues;
    }

    /**
     * Calculates weighted normalization.
     *
     * @param \Illuminate\Database\Eloquent\Collection $criterias
     * @param array $normalized
     * @return array
     */
    private function getNormalisasiTerbobot($criterias, $normalized)
    {
        $weightedNormalization = [];
        if (empty($normalized)) {
            return [];
        }
        
        foreach ($normalized as $keyRow => $row) {
            foreach ($criterias as $keyColumn => $c) {
                $normalizedValue = $row[$keyColumn] ?? 0;
                $weightedNormalization[$keyRow][$keyColumn] = round($c->weight * $normalizedValue, 3);
            }
        }
        return $weightedNormalization;
    }

    /**
     * Calculates ideal solution values.
     *
     * @param array $weightedNormalization
     * @return array
     */
    private function getIdeal($weightedNormalization)
    {
        $ideal = [];
        if (empty($weightedNormalization)) {
            return [];
        }

        $firstRowKeys = array_keys(current($weightedNormalization));
        
        foreach ($firstRowKeys as $col) {
            $ideal[$col] = max(array_column($weightedNormalization, $col));
        }
        return $ideal;
    }

    /**
     * Calculates Si and Ri values.
     *
     * @param \Illuminate\Database\Eloquent\Collection $criterias
     * @param \Illuminate\Database\Eloquent\Collection $alternatifs
     * @param array $weightedNormalization
     * @param array $ideal
     * @return array
     */
    private function getSiRi($criterias, $alternatifs, $weightedNormalization, $ideal)
    {
        $sum = [];
        $max = [];

        foreach ($alternatifs as $keyRow => $a) {
            $selisih = [];
            foreach ($criterias as $keyColumn => $c) {
                $vij = $weightedNormalization[$keyRow][$keyColumn] ?? 0;
                $idealVal = $ideal[$keyColumn] ?? 0;
                
                $selisih[] = round($idealVal - $vij, 6);
            }
            $sum[$keyRow] = empty($selisih) ? 0 : round(array_sum($selisih), 6);
            $max[$keyRow] = empty($selisih) ? 0 : round(max($selisih), 6);
        }

        return ['Si' => $sum, 'Ri' => $max];
    }

    /**
     * Calculates Qi values.
     *
     * @param array $Si
     * @param array $Ri
     * @return array
     */
    private function getQi($Si, $Ri)
    {
        $V = 0.5;
        $Smax = empty($Si) ? 0 : max($Si);
        $Smin = empty($Si) ? 0 : min($Si);
        $Rmax = empty($Ri) ? 0 : max($Ri);
        $Rmin = empty($Ri) ? 0 : min($Ri);

        $finalValues = [];
        foreach ($Si as $key => $s) {
            $denomS = ($Smax - $Smin);
            $val1 = ($denomS == 0) ? 0 : ($s - $Smin) / $denomS;
            
            $denomR = ($Rmax - $Rmin);
            $val2 = ($denomR == 0) ? 0 : ($Ri[$key] - $Rmin) / $denomR;
            
            $finalValues[$key] = round($V * $val1 + (1 - $V) * $val2, 3);
        }

        return $finalValues;
    }

    /**
     * Gets ranking based on Qi values.
     *
     * @param array $finalValues
     * @return array
     */
    private function getRanking($finalValues)
    {
        $ranking = [];
        if (empty($finalValues)) {
            return [];
        }
        asort($finalValues);
        $rank = 1;
        foreach (array_keys($finalValues) as $key) {
            $ranking[$key] = $rank++;
        }
        return $ranking;
    }

    /**
     * Displays normalized decision matrix.
     *
     * @return \Illuminate\Http\Response
     */
    public function tampilNormalisasi()
    {
        $criterias = criteria::all();
        $alternatifs = alternatif::all();
        $penilaians = penilaian::with(['alternatif', 'criteria'])->get();

        // Perform calculation only if data is available
        if (!$criterias->isEmpty() && !$alternatifs->isEmpty() && !$penilaians->isEmpty()) {
            $normalizedValues = $this->getNormalisasi($criterias, $alternatifs, $penilaians);
        } else {
            $normalizedValues = []; // Set empty if no data
            session()->flash('warning', 'Data kriteria, alternatif, atau penilaian tidak lengkap. Tabel ini mungkin kosong.');
        }

        return view('dashboard.hitung.normalisasi', [
            'criteria' => $criterias,
            'alternatif' => $alternatifs,
            'normalisasi' => $normalizedValues,
        ]);
    }

    /**
     * Displays weighted normalized matrix.
     *
     * @return \Illuminate\Http\Response
     */
    public function tampilNormalisasiTerbobot()
    {
        $criterias = criteria::all();
        $alternatifs = alternatif::all();
        $penilaians = penilaian::with(['alternatif', 'criteria'])->get();

        // Perform calculation only if data is available
        if (!$criterias->isEmpty() && !$alternatifs->isEmpty() && !$penilaians->isEmpty()) {
            $normalizedValues = $this->getNormalisasi($criterias, $alternatifs, $penilaians);
            $weightedNormalization = $this->getNormalisasiTerbobot($criterias, $normalizedValues);
        } else {
            $normalizedValues = [];
            $weightedNormalization = []; // Set empty if no data
            session()->flash('warning', 'Data kriteria, alternatif, atau penilaian tidak lengkap. Tabel ini mungkin kosong.');
        }

        return view('dashboard.hitung.normalisasiterbobot', [
            'criteria' => $criterias,
            'alternatif' => $alternatifs,
            'weightedNormalization' => $weightedNormalization,
        ]);
    }

    /**
     * Displays ideal difference.
     *
     * @return \Illuminate\Http\Response
     */
    public function tampilSelisihIdeal()
    {
        $criterias = criteria::all();
        $alternatifs = alternatif::all();
        $penilaians = penilaian::with(['alternatif', 'criteria'])->get();

        // Perform calculation only if data is available
        if (!$criterias->isEmpty() && !$alternatifs->isEmpty() && !$penilaians->isEmpty()) {
            $normalizedValues = $this->getNormalisasi($criterias, $alternatifs, $penilaians);
            $weightedNormalization = $this->getNormalisasiTerbobot($criterias, $normalizedValues);
            $ideal = $this->getIdeal($weightedNormalization);
        } else {
            $normalizedValues = [];
            $weightedNormalization = [];
            $ideal = []; // Set empty if no data
            session()->flash('warning', 'Data kriteria, alternatif, atau penilaian tidak lengkap. Tabel ini mungkin kosong.');
        }

        return view('dashboard.hitung.selisihideal', [
            'criteria' => $criterias,
            'alternatif' => $alternatifs,
            'ideal' => $ideal,
            'weightedNormalization' => $weightedNormalization,
        ]);
    }

    /**
     * Displays decision matrix.
     *
     * @return \Illuminate\Http\Response
     */
    public function tampilMatriks()
    {
        $criterias = criteria::all();
        $alternatifs = alternatif::all();
        $penilaians = penilaian::all();

        if ($criterias->isEmpty() || $alternatifs->isEmpty() || $penilaians->isEmpty()) {
            // No calculation needed, just display empty table or a warning
            session()->flash('warning', 'Data kriteria, alternatif, atau penilaian tidak lengkap. Tabel ini mungkin kosong.');
        }

        return view('dashboard.hitung.matriks', [
            'criteria' => $criterias,
            'alternatif' => $alternatifs,
            'penilaian' => $penilaians,
        ]);
    }

    /**
     * Displays utility (Si and Ri).
     *
     * @return \Illuminate\Http\Response
     */
    public function tampilUtility()
    {
        $criterias = Criteria::all();
        $alternatifs = Alternatif::all();
        $penilaian = Penilaian::with(['alternatif', 'criteria'])->get();

        $Si = [];
        $Ri = [];

        if (!$criterias->isEmpty() && !$alternatifs->isEmpty() && !$penilaian->isEmpty()) {
            $bobot = $criterias->pluck('weight')->toArray();
            if (empty($bobot) || array_sum($bobot) == 0) {
                session()->flash('warning', 'Bobot kriteria tidak valid atau nol semua. Perhitungan tidak dapat dilakukan.');
            } else {
                $normalizedValues = $this->getNormalisasi($criterias, $alternatifs, $penilaian);
                $weightedNormalization = $this->getNormalisasiTerbobot($criterias, $normalizedValues);
                $ideal = $this->getIdeal($weightedNormalization);
                $SiRi = $this->getSiRi($criterias, $alternatifs, $weightedNormalization, $ideal);
                $Si = $SiRi['Si'];
                $Ri = $SiRi['Ri'];
            }
        } else {
            session()->flash('warning', 'Data kriteria, alternatif, atau penilaian tidak ditemukan. Tabel ini mungkin kosong.');
        }
        
        return view('dashboard.hitung.utility', [
            'criteria' => $criterias,
            'alternatif' => $alternatifs,
            'Si' => $Si,
            'Ri' => $Ri,
            // Pass empty arrays if calculation not performed, or the actual data
            'matriks' => [], // You might need to pass actual matriks if it's used in the view
            'normal' => [],
            'terbobot' => [],
            'ideal' => []
        ]);
    }

    /**
     * Displays compromise (Qi values and ranking).
     *
     * @return \Illuminate\Http\Response
     */
    public function tampilKompromi()
    {
        $alternatif = Alternatif::all();
        $hasil = HasilVikor::with('alternatif')->orderBy('ranking')->get(); // Get results with ranking

        $finalValues = [];
        $ranking = [];

        if (!$alternatif->isEmpty() && !$hasil->isEmpty()) {
            foreach ($alternatif as $key => $alt) {
                $data = $hasil->where('id_alternatif', $alt->id)->first(); // Find result data for this alternative

                $finalValues[$key] = $data ? $data->nilai_q : null;
                $ranking[$key] = $data ? $data->ranking : null;

                $alt->alternatif_code = $alt->alternatif_code ?? 'A' . ($key + 1); // Use alternative code property
            }
        } else {
             session()->flash('warning', 'Data alternatif atau hasil perhitungan tidak ditemukan. Tabel ini mungkin kosong.');
        }

        return view('dashboard.hitung.kompromi', [
            'alternatif' => $alternatif,
            'finalValues' => $finalValues,
            'ranking' => $ranking,
        ]);
    }

    /**
     * Saves VIKOR calculation results.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function simpan(Request $request)
    {
        // Validate and prevent null array errors
        $request->validate([
            'finalValues' => 'required|array',
            'finalValues.*' => 'nullable|numeric', // Nullable because it can be 0 if no data
            'alternatif' => 'required|array',
            'alternatif.*' => 'required|exists:alternatifs,id',
            'ranking' => 'required|array',
            'ranking.*' => 'nullable|integer', // Nullable because it can be 0 if no data
            'Si' => 'nullable|array', // Add validation if you send Si/Ri from frontend
            'Si.*' => 'nullable|numeric',
            'Ri' => 'nullable|array',
            'Ri.*' => 'nullable|numeric',
        ]);


        DB::beginTransaction();
        try {
            // Clear old data, or you can use updateOrCreate as already in index()
            // HasilVikor::truncate();

            foreach ($request->finalValues as $key => $value) {
                $alternatifId = $request->alternatif[$key] ?? null;
                $rankingValue = $request->ranking[$key] ?? null;
                $nilaiS = $request->Si[$key] ?? null; // Get S value if sent
                $nilaiR = $request->Ri[$key] ?? null; // Get R value if sent

                if ($alternatifId !== null) {
                    HasilVikor::updateOrCreate(
                        ['id_alternatif' => $alternatifId],
                        [
                            'nilai_q' => $value,
                            'ranking' => $rankingValue,
                            'nilai_s' => $nilaiS, // Save S value
                            'nilai_r' => $nilaiR, // Save R value
                            'status' => ($rankingValue !== null && $rankingValue <= 10) ? 'Lulus' : 'Tidak Lulus',
                            'created_at' => now(),
                            'updated_at' => now()
                        ]
                    );
                }
            }

            DB::commit();

            // Create PDF
            // Get latest data after saving
            $hasilPdf = HasilVikor::with('alternatif')->orderBy('ranking', 'asc')->get();
            $dataPdf = [
                'hasilLengkap' => $hasilPdf,
                // You can pass other data needed by the PDF view here
            ];

            $pdf = Pdf::loadView('pdf.hasil-vikor', $dataPdf);
            return $pdf->download('hasil-perhitungan-vikor.pdf');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
        }
    }
}
