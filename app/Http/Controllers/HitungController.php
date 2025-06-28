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
     * Konstruktor untuk menerapkan middleware otorisasi.
     * Hanya 'admin' dan 'guru' yang dapat mengakses metode-metode di controller ini.
     */
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin|guru']); // Memastikan hanya admin atau guru
    }

    /**
     * Menampilkan halaman perhitungan dan melakukan perhitungan VIKOR.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $criterias = criteria::all();
        $alternatifs = alternatif::all();
        $penilaians = penilaian::with(['alternatif', 'criteria'])->get();

        // Validasi awal: jika tidak ada data kriteria, alternatif, atau penilaian
        if ($criterias->isEmpty() || $alternatifs->isEmpty() || $penilaians->isEmpty()) {
            return redirect()->back()->with('error', 'Data kriteria, alternatif, atau penilaian tidak lengkap. Pastikan semua data tersedia.');
        }

        // Melakukan perhitungan VIKOR
        $normalizedValues = $this->getNormalisasi($criterias, $alternatifs, $penilaians);
        $weightedNormalization = $this->getNormalisasiTerbobot($criterias, $normalizedValues);
        $ideal = $this->getIdeal($weightedNormalization);
        $SiRi = $this->getSiRi($criterias, $alternatifs, $weightedNormalization, $ideal);
        $finalValues = $this->getQi($SiRi['Si'], $SiRi['Ri']);
        $ranking = $this->getRanking($finalValues);

        // Hapus data lama HasilVikor dan simpan data baru
        DB::beginTransaction();
        try {
            HasilVikor::truncate(); // Hapus semua data lama
            foreach ($alternatifs as $key => $alt) {
                // Pastikan indeks $key ada di $SiRi['Si'], $SiRi['Ri'], $finalValues, dan $ranking
                $nilaiS = $SiRi['Si'][$key] ?? 0;
                $nilaiR = $SiRi['Ri'][$key] ?? 0;
                $nilaiQ = $finalValues[$key] ?? 0;
                $rankingAlt = $ranking[$key] ?? null;

                HasilVikor::create([
                    'id_alternatif' => $alt->id,
                    'nilai_s' => $nilaiS,
                    'nilai_r' => $nilaiR,
                    'nilai_q' => $nilaiQ,
                    'ranking' => $rankingAlt,
                    'status' => ($rankingAlt !== null && $rankingAlt <= 10) ? 'Lulus' : 'Tidak Lulus', // Logika status bisa disesuaikan
                ]);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menyimpan hasil perhitungan: ' . $e->getMessage());
        }


        return view('dashboard.hitung', [
            'criteria' => $criterias,
            'alternatif' => $alternatifs,
            'penilaian' => $penilaians,
            'normalisasi' => $normalizedValues,
            'weightedNormalization' => $weightedNormalization,
            'ideal' => $ideal,
            'Si' => $SiRi['Si'],
            'Ri' => $SiRi['Ri'],
            'finalValues' => $finalValues,
            'ranking' => $ranking,
        ]);
    }

    /**
     * Menghitung nilai normalisasi.
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
     * Menghitung normalisasi terbobot.
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
     * Menghitung nilai solusi ideal.
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
     * Menghitung nilai Si dan Ri.
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
     * Menghitung nilai Qi.
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
     * Mendapatkan ranking berdasarkan nilai Qi.
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
     * Menampilkan normalisasi matriks keputusan.
     *
     * @return \Illuminate\Http\Response
     */
    public function tampilNormalisasi()
    {
        $criterias = criteria::all();
        $alternatifs = alternatif::all();
        $penilaians = penilaian::with(['alternatif', 'criteria'])->get();

        if ($criterias->isEmpty() || $alternatifs->isEmpty() || $penilaians->isEmpty()) {
            return redirect()->back()->with('error', 'Data kriteria, alternatif, atau penilaian tidak lengkap.');
        }

        $normalizedValues = $this->getNormalisasi($criterias, $alternatifs, $penilaians);

        return view('dashboard.hitung.normalisasi', [
            'criteria' => $criterias,
            'alternatif' => $alternatifs,
            'normalisasi' => $normalizedValues,
        ]);
    }

    /**
     * Menampilkan normalisasi perkalian matriks terbobot.
     *
     * @return \Illuminate\Http\Response
     */
    public function tampilNormalisasiTerbobot()
    {
        $criterias = criteria::all();
        $alternatifs = alternatif::all();
        $penilaians = penilaian::with(['alternatif', 'criteria'])->get();

        if ($criterias->isEmpty() || $alternatifs->isEmpty() || $penilaians->isEmpty()) {
            return redirect()->back()->with('error', 'Data kriteria, alternatif, atau penilaian tidak lengkap.');
        }

        $normalizedValues = $this->getNormalisasi($criterias, $alternatifs, $penilaians);
        $weightedNormalization = $this->getNormalisasiTerbobot($criterias, $normalizedValues);

        return view('dashboard.hitung.normalisasiterbobot', [
            'criteria' => $criterias,
            'alternatif' => $alternatifs,
            'weightedNormalization' => $weightedNormalization,
        ]);
    }

    /**
     * Menampilkan selisih ideal.
     *
     * @return \Illuminate\Http\Response
     */
    public function tampilSelisihIdeal()
    {
        $criterias = criteria::all();
        $alternatifs = alternatif::all();
        $penilaians = penilaian::with(['alternatif', 'criteria'])->get();

        if ($criterias->isEmpty() || $alternatifs->isEmpty() || $penilaians->isEmpty()) {
            return redirect()->back()->with('error', 'Data kriteria, alternatif, atau penilaian tidak lengkap.');
        }

        $normalizedValues = $this->getNormalisasi($criterias, $alternatifs, $penilaians);
        $weightedNormalization = $this->getNormalisasiTerbobot($criterias, $normalizedValues);
        $ideal = $this->getIdeal($weightedNormalization);

        return view('dashboard.hitung.selisihideal', [
            'criteria' => $criterias,
            'alternatif' => $alternatifs,
            'ideal' => $ideal,
            'weightedNormalization' => $weightedNormalization,
        ]);
    }

    /**
     * Menampilkan matriks keputusan.
     *
     * @return \Illuminate\Http\Response
     */
    public function tampilMatriks()
    {
        $criterias = criteria::all();
        $alternatifs = alternatif::all();
        $penilaians = penilaian::all();

        if ($criterias->isEmpty() || $alternatifs->isEmpty() || $penilaians->isEmpty()) {
            return redirect()->back()->with('error', 'Data kriteria, alternatif, atau penilaian tidak lengkap.');
        }

        return view('dashboard.hitung.matriks', [
            'criteria' => $criterias,
            'alternatif' => $alternatifs,
            'penilaian' => $penilaians,
        ]);
    }

    /**
     * Menampilkan utility (Si dan Ri).
     *
     * @return \Illuminate\Http\Response
     */
    public function tampilUtility()
    {
        $criterias = Criteria::all();
        $alternatifs = Alternatif::all();
        $penilaian = Penilaian::with(['alternatif', 'criteria'])->get();

        if ($criterias->isEmpty() || $alternatifs->isEmpty() || $penilaian->isEmpty()) {
            return redirect()->back()->with('error', 'Data kriteria, alternatif, atau penilaian tidak ditemukan.');
        }

        $bobot = $criterias->pluck('weight')->toArray();
        if (empty($bobot) || array_sum($bobot) == 0) {
            return redirect()->back()->with('error', 'Bobot kriteria tidak valid atau nol semua.');
        }

        $matriks = [];
        foreach ($alternatifs as $i => $alt) {
            foreach ($criterias as $j => $c) {
                $item = $penilaian->where('id_alternatif', $alt->id)
                    ->where('id_criteria', $c->id)
                    ->first();
                $nilai = $item ? $item->nilai : 0;

                $matriks[$i][$j] = $nilai;
            }
        }
        
        $normalizedValues = $this->getNormalisasi($criterias, $alternatifs, $penilaian);
        $weightedNormalization = $this->getNormalisasiTerbobot($criterias, $normalizedValues);
        $ideal = $this->getIdeal($weightedNormalization);
        $SiRi = $this->getSiRi($criterias, $alternatifs, $weightedNormalization, $ideal);
        $Si = $SiRi['Si'];
        $Ri = $SiRi['Ri'];

        return view('dashboard.hitung.utility', [
            'criteria' => $criterias,
            'alternatif' => $alternatifs,
            'Si' => $Si,
            'Ri' => $Ri,
            'matriks' => $matriks,
            'normal' => $normalizedValues,
            'terbobot' => $weightedNormalization,
            'ideal' => $ideal
        ]);
    }

    /**
     * Menampilkan kompromi (nilai Qi dan ranking).
     *
     * @return \Illuminate\Http\Response
     */
    public function tampilKompromi()
    {
        $alternatif = Alternatif::all();
        $hasil = HasilVikor::with('alternatif')->orderBy('ranking')->get(); // Ambil hasil dengan ranking

        $finalValues = [];
        $ranking = [];

        foreach ($alternatif as $key => $alt) {
            $data = $hasil->where('id_alternatif', $alt->id)->first(); // Cari data hasil untuk alternatif ini

            $finalValues[$key] = $data ? $data->nilai_q : null;
            $ranking[$key] = $data ? $data->ranking : null;

            $alt->alternatif_code = $alt->alternatif_code ?? 'A' . ($key + 1); // Menggunakan properti model alternatif
        }

        return view('dashboard.hitung.kompromi', [
            'alternatif' => $alternatif,
            'finalValues' => $finalValues,
            'ranking' => $ranking,
        ]);
    }

    /**
     * Menyimpan hasil perhitungan VIKOR.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function simpan(Request $request)
    {
        // Validasi dan cegah error array null
        $request->validate([
            'finalValues' => 'required|array',
            'finalValues.*' => 'nullable|numeric', // Nullable karena bisa 0 jika tidak ada data
            'alternatif' => 'required|array',
            'alternatif.*' => 'required|exists:alternatifs,id',
            'ranking' => 'required|array',
            'ranking.*' => 'nullable|integer', // Nullable karena bisa 0 jika tidak ada data
            'Si' => 'nullable|array', // Tambahkan validasi jika Anda mengirim Si/Ri dari frontend
            'Si.*' => 'nullable|numeric',
            'Ri' => 'nullable|array',
            'Ri.*' => 'nullable|numeric',
        ]);


        DB::beginTransaction();
        try {
            // Hapus data lama, atau Anda bisa menggunakan updateOrCreate yang sudah di index()
            // HasilVikor::truncate();

            foreach ($request->finalValues as $key => $value) {
                $alternatifId = $request->alternatif[$key] ?? null;
                $rankingValue = $request->ranking[$key] ?? null;
                $nilaiS = $request->Si[$key] ?? null; // Ambil nilai S jika dikirim
                $nilaiR = $request->Ri[$key] ?? null; // Ambil nilai R jika dikirim

                if ($alternatifId !== null) {
                    HasilVikor::updateOrCreate(
                        ['id_alternatif' => $alternatifId],
                        [
                            'nilai_q' => $value,
                            'ranking' => $rankingValue,
                            'nilai_s' => $nilaiS, // Simpan nilai S
                            'nilai_r' => $nilaiR, // Simpan nilai R
                            'status' => ($rankingValue !== null && $rankingValue <= 10) ? 'Lulus' : 'Tidak Lulus',
                            'created_at' => now(),
                            'updated_at' => now()
                        ]
                    );
                }
            }

            DB::commit();

            // Buat PDF
            // Ambil data terbaru setelah penyimpanan
            $hasilPdf = HasilVikor::with('alternatif')->orderBy('ranking', 'asc')->get();
            $dataPdf = [
                'hasilLengkap' => $hasilPdf,
                // Anda bisa meneruskan data lain yang diperlukan oleh view PDF di sini
            ];

            $pdf = Pdf::loadView('pdf.hasil-vikor', $dataPdf);
            return $pdf->download('hasil-perhitungan-vikor.pdf');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
        }
    }
}
