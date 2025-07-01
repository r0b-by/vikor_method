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
        $this->middleware(['auth', 'role:admin|guru']);
    }

    /**
     * Displays the main calculation page and performs VIKOR calculations.
     * This method will now primarily handle the initial calculation and
     * display the final results (compromise values and ranking), and
     * also manage the session messages for incomplete data.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $criterias = criteria::all();
        $alternatifs = alternatif::with('hasilVikor')->get();
        $penilaians = penilaian::with(['alternatif', 'criteria'])->get();

        // Inisialisasi variabel
        $normalizedValues = [];
        $weightedNormalization = [];
        $ideal = [];
        $Si = [];
        $Ri = [];
        $finalValues = [];
        $ranking = [];
        $calculationPerformed = false;

        if (!$criterias->isEmpty() &&
            !$alternatifs->isEmpty() &&
            !$penilaians->isEmpty() &&
            ($alternatifs->count() > 0 && $criterias->count() > 0) &&
            $penilaians->count() === ($alternatifs->count() * $criterias->count())) {

            // Lakukan perhitungan
            $normalizedValues = $this->getNormalisasi($criterias, $alternatifs, $penilaians);
            $weightedNormalization = $this->getNormalisasiTerbobot($criterias, $normalizedValues);
            $ideal = $this->getIdeal($weightedNormalization);
            $SiRi = $this->getSiRi($criterias, $alternatifs, $weightedNormalization, $ideal);
            $Si = $SiRi['Si'];
            $Ri = $SiRi['Ri'];
            $finalValues = $this->getQi($Si, $Ri);
            $ranking = $this->getRanking($finalValues);
            $calculationPerformed = true;

            // Simpan hasil ke database (This part remains the same as it's the core calculation and saving)
            DB::beginTransaction();
            try {
                HasilVikor::truncate();

                foreach ($alternatifs as $alt) {
                    $altId = $alt->id;
                    $nilaiS = $Si[$altId] ?? 0;
                    $nilaiR = $Ri[$altId] ?? 0;
                    $nilaiQ = $finalValues[$altId] ?? 0;
                    $rankingAlt = $ranking[$altId] ?? null;

                    HasilVikor::create([
                        'id_alternatif' => $altId,
                        'nilai_s' => $nilaiS,
                        'nilai_r' => $nilaiR,
                        'nilai_q' => $nilaiQ,
                        'ranking' => $rankingAlt,
                        'status' => ($rankingAlt !== null && $rankingAlt <= 10) ? 'Lulus' : 'Tidak Lulus',
                    ]);

                    $alt->status_perhitungan = 'calculated';
                    $alt->save();
                }

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                session()->flash('error', 'Gagal menyimpan hasil: ' . $e->getMessage());
            }
        } else {
            session()->flash('warning', 'Data kriteria, alternatif, atau penilaian tidak lengkap. Perhitungan tidak dapat dilakukan. Pastikan semua nilai penilaian telah diisi untuk setiap alternatif dan kriteria.');
            $calculationPerformed = false;
        }

        return view('dashboard.hitung', [
            'criterias' => $criterias,
            'alternatifs' => $alternatifs,
            'penilaians' => $penilaians, // Needed for Matriks Keputusan
            'normalisasi' => $normalizedValues,
            'weightedNormalization' => $weightedNormalization,
            'ideal' => $ideal,
            'Si' => $Si,
            'Ri' => $Ri,
            'finalValues' => $finalValues,
            'ranking' => $ranking,
            'calculationPerformed' => $calculationPerformed,
        ]);
    }

    // PERBAIKAN 5: Modifikasi fungsi pendukung untuk gunakan ID sebagai key
    private function getNormalisasi($criterias, $alternatifs, $penilaians)
    {
        $normalizedValues = [];
        foreach ($criterias as $c) {
            $values = $penilaians->where('id_criteria', $c->id);
            $nilai = $values->pluck('nilai')->toArray();

            if (empty($nilai)) continue;

            $maxVal = max($nilai);
            $minVal = min($nilai);
            $range = ($maxVal - $minVal) ?: 1;

            foreach ($alternatifs as $alt) {
                $value = $values->where('id_alternatif', $alt->id)->first();
                if (!$value) {
                    $normalizedValues[$alt->id][$c->id] = 0;
                    continue;
                }

                $normalized = ($c->criteria_type == 'Cost')
                    ? ($maxVal - $value->nilai) / $range
                    : ($value->nilai - $minVal) / $range;

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
        $firstRow = reset($weightedNormalization);
        foreach ($firstRow as $criteriaId => $val) {
            $columnValues = array_column($weightedNormalization, $criteriaId);
            $ideal[$criteriaId] = max($columnValues);
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
        $V = 0.5;
        $finalValues = [];

        if (empty($Si) || empty($Ri)) {
            return $finalValues;
        }

        $Smax = max($Si);
        $Smin = min($Si);
        $Rmax = max($Ri);
        $Rmin = min($Ri);

        $denomS = ($Smax - $Smin) ?: 1;
        $denomR = ($Rmax - $Rmin) ?: 1;

        foreach ($Si as $altId => $s) {
            $val1 = ($s - $Smin) / $denomS;
            $val2 = ($Ri[$altId] - $Rmin) / $denomR;
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
        asort($finalValues); 
        $rank = 1;
        foreach ($finalValues as $altId => $value) {
            $ranking[$altId] = $rank++;
        }
        return $ranking;
    }

    /**
     * Displays normalized decision matrix.
     *
     * @return \Illuminate\Http\Response
     */
    public function tampilMatriksKeputusan()
    {
        $criterias = criteria::all();
        $alternatifs = alternatif::all();
        $penilaians = penilaian::with(['alternatif', 'criteria'])->get();

        if ($criterias->isEmpty() || $alternatifs->isEmpty() || $penilaians->isEmpty() || $penilaians->count() !== ($alternatifs->count() * $criterias->count())) {
            session()->flash('warning', 'Data kriteria, alternatif, atau penilaian tidak lengkap. Matriks ini mungkin kosong.');
        }

        return view('dashboard.hitung', [
            'criterias' => $criterias,
            'alternatifs' => $alternatifs,
            'penilaians' => $penilaians,
        ]);
    }

    public function tampilNormalisasi()
    {
        $criterias = criteria::all();
        $alternatifs = alternatif::all();
        $penilaians = penilaian::with(['alternatif', 'criteria'])->get();
        $normalizedValues = [];

        if (!$criterias->isEmpty() && !$alternatifs->isEmpty() && !$penilaians->isEmpty() && ($alternatifs->count() > 0 && $criterias->count() > 0) && $penilaians->count() === ($alternatifs->count() * $criterias->count())) {
            $normalizedValues = $this->getNormalisasi($criterias, $alternatifs, $penilaians);
        } else {
            session()->flash('warning', 'Data kriteria, alternatif, atau penilaian tidak lengkap. Tabel ini mungkin kosong.');
        }

        return view('dashboard.hitung', [
            'criterias' => $criterias,
            'alternatifs' => $alternatifs,
            'normalisasi' => $normalizedValues
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
        $weightedNormalization = [];

        if (!$criterias->isEmpty() && !$alternatifs->isEmpty() && !$penilaians->isEmpty() && ($alternatifs->count() > 0 && $criterias->count() > 0) && $penilaians->count() === ($alternatifs->count() * $criterias->count())) {
            $normalizedValues = $this->getNormalisasi($criterias, $alternatifs, $penilaians);
            $weightedNormalization = $this->getNormalisasiTerbobot($criterias, $normalizedValues);
        } else {
            session()->flash('warning', 'Data kriteria, alternatif, atau penilaian tidak lengkap. Tabel ini mungkin kosong.');
        }

        return view('dashboard.hitung', [
            'criterias' => $criterias,
            'alternatifs' => $alternatifs,
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
        $ideal = [];
        $weightedNormalization = [];

        if (!$criterias->isEmpty() && !$alternatifs->isEmpty() && !$penilaians->isEmpty() && ($alternatifs->count() > 0 && $criterias->count() > 0) && $penilaians->count() === ($alternatifs->count() * $criterias->count())) {
            $normalizedValues = $this->getNormalisasi($criterias, $alternatifs, $penilaians);
            $weightedNormalization = $this->getNormalisasiTerbobot($criterias, $normalizedValues);
            $ideal = $this->getIdeal($weightedNormalization);
        } else {
            session()->flash('warning', 'Data kriteria, alternatif, atau penilaian tidak lengkap. Tabel ini mungkin kosong.');
        }

        return view('dashboard.hitung', [ // Updated view name
            'criterias' => $criterias,
            'alternatifs' => $alternatifs,
            'ideal' => $ideal,
            'weightedNormalization' => $weightedNormalization,
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
        $penilaians = Penilaian::with(['alternatif', 'criteria'])->get(); 

        $Si = [];
        $Ri = [];

        if (!$criterias->isEmpty() && !$alternatifs->isEmpty() && !$penilaians->isEmpty() && ($alternatifs->count() > 0 && $criterias->count() > 0) && $penilaians->count() === ($alternatifs->count() * $criterias->count())) {
            $bobot = $criterias->pluck('weight')->toArray();
            if (empty($bobot) || array_sum($bobot) == 0) {
                session()->flash('warning', 'Bobot kriteria tidak valid atau nol semua. Perhitungan tidak dapat dilakukan.');
            } else {
                $normalizedValues = $this->getNormalisasi($criterias, $alternatifs, $penilaians);
                $weightedNormalization = $this->getNormalisasiTerbobot($criterias, $normalizedValues);
                $ideal = $this->getIdeal($weightedNormalization);
                $SiRi = $this->getSiRi($criterias, $alternatifs, $weightedNormalization, $ideal);
                $Si = $SiRi['Si'];
                $Ri = $SiRi['Ri'];
            }
        } else {
            session()->flash('warning', 'Data kriteria, alternatif, atau penilaian tidak ditemukan atau tidak lengkap. Tabel ini mungkin kosong.');
        }

        return view('dashboard.hitung', [
            'criterias' => $criterias,
            'alternatifs' => $alternatifs,
            'Si' => $Si,
            'Ri' => $Ri,
        ]);
    }
    /**
     * Displays compromise values (Qi) and final ranking.
     *
     * @return \Illuminate\Http\Response
     */
    public function tampilKompromi()
    {
        $criterias = Criteria::all();
        $alternatifs = Alternatif::all();
        $penilaians = Penilaian::with(['alternatif', 'criteria'])->get();

        $finalValues = [];
        $ranking = [];
        $Si = []; 
        $Ri = [];

        if (!$criterias->isEmpty() &&
            !$alternatifs->isEmpty() &&
            !$penilaians->isEmpty() &&
            ($alternatifs->count() > 0 && $criterias->count() > 0) &&
            $penilaians->count() === ($alternatifs->count() * $criterias->count())) {

            // Perform all necessary calculations to get Qi and Ranking
            $normalizedValues = $this->getNormalisasi($criterias, $alternatifs, $penilaians);
            $weightedNormalization = $this->getNormalisasiTerbobot($criterias, $normalizedValues);
            $ideal = $this->getIdeal($weightedNormalization);
            $SiRi = $this->getSiRi($criterias, $alternatifs, $weightedNormalization, $ideal);
            $Si = $SiRi['Si'];
            $Ri = $SiRi['Ri'];
            $finalValues = $this->getQi($Si, $Ri);
            $ranking = $this->getRanking($finalValues);

        } else {
            session()->flash('warning', 'Data kriteria, alternatif, atau penilaian tidak lengkap. Tabel ini mungkin kosong.');
        }

        return view('dashboard.hitung', [
            'criterias' => $criterias,
            'alternatifs' => $alternatifs,
            'finalValues' => $finalValues,
            'ranking' => $ranking,
            'Si' => $Si, // Pass Si and Ri to the kompromi view for the hidden inputs
            'Ri' => $Ri,
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
            'finalValues.*' => 'nullable|numeric',
            'alternatif' => 'required|array', 
            'alternatif.*' => 'required|exists:alternatifs,id',
            'ranking' => 'required|array',
            'ranking.*' => 'nullable|integer',
            'Si' => 'nullable|array',
            'Si.*' => 'nullable|numeric',
            'Ri' => 'nullable|array',
            'Ri.*' => 'nullable|numeric',
        ]);

        DB::beginTransaction();
        try {
            HasilVikor::truncate(); 

            foreach ($request->alternatif as $key => $alternatifId) {
                $nilaiQ = $request->finalValues[$key] ?? 0;
                $rankingValue = $request->ranking[$key] ?? null;
                $nilaiS = $request->Si[$key] ?? 0; 
                $nilaiR = $request->Ri[$key] ?? 0; 

                HasilVikor::create([
                    'id_alternatif' => $alternatifId,
                    'nilai_q' => $nilaiQ,
                    'ranking' => $rankingValue,
                    'nilai_s' => $nilaiS,
                    'nilai_r' => $nilaiR,
                    'status' => ($rankingValue !== null && $rankingValue <= 10) ? 'Lulus' : 'Tidak Lulus',
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                // Update status_perhitungan for the alternative
                Alternatif::where('id', $alternatifId)->update(['status_perhitungan' => 'calculated']);
            }

            DB::commit();

            // Create PDF
            $hasilPdf = HasilVikor::with('alternatif')->orderBy('ranking', 'asc')->get();
            $dataPdf = [
                'hasilLengkap' => $hasilPdf,
            ];

            $pdf = Pdf::loadView('pdf.hasil-vikor', $dataPdf);
            return $pdf->download('hasil-perhitungan-vikor.pdf');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
        }
    }
}