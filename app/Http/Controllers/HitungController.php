<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\HasilVikor;
use App\Models\criteria;
use App\Models\penilaian;
use App\Models\alternatif;
use Illuminate\Http\Request;
// use App\Services\VikorService; // Jika ini tidak digunakan, bisa dihapus

class HitungController extends Controller
{
    public function index()
    {
        $criterias = criteria::all();
        $alternatifs = alternatif::all();
        $penilaians = penilaian::with(['alternatif', 'criteria'])->get();

        // Validasi awal: jika tidak ada data kriteria, alternatif, atau penilaian
        if ($criterias->isEmpty() || $alternatifs->isEmpty() || $penilaians->isEmpty()) {
            // Anda bisa mengembalikan error atau redirect ke halaman sebelumnya
            return redirect()->back()->with('error', 'Data kriteria, alternatif, atau penilaian tidak lengkap. Pastikan semua data tersedia.');
        }

        $normalizedValues = $this->getNormalisasi($criterias, $alternatifs, $penilaians);
        $weightedNormalization = $this->getNormalisasiTerbobot($criterias, $normalizedValues);
        $ideal = $this->getIdeal($weightedNormalization);
        $SiRi = $this->getSiRi($criterias, $alternatifs, $weightedNormalization, $ideal);
        $finalValues = $this->getQi($SiRi['Si'], $SiRi['Ri']);
        $ranking = $this->getRanking($finalValues);

        // Simpan ke DB
        HasilVikor::truncate(); // Hapus data lama sebelum menyimpan yang baru
        foreach ($alternatifs as $key => $alt) {
            // Pastikan indeks $key ada di $SiRi['Si'], $SiRi['Ri'], $finalValues, dan $ranking
            $nilaiS = $SiRi['Si'][$key] ?? 0;
            $nilaiR = $SiRi['Ri'][$key] ?? 0;
            $nilaiQ = $finalValues[$key] ?? 0;
            $rankingAlt = $ranking[$key] ?? null; // Bisa null jika ada masalah ranking

            HasilVikor::create([
                'id_alternatif' => $alt->id,
                'nilai_s' => $nilaiS,
                'nilai_r' => $nilaiR,
                'nilai_q' => $nilaiQ,
                'ranking' => $rankingAlt,
                'status' => ($rankingAlt !== null && $rankingAlt <= 10) ? 'Lulus' : 'Tidak Lulus',
            ]);
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

    private function getNormalisasi($criterias, $alternatifs, $penilaians)
    {
        $normalizedValues = [];
        foreach ($criterias as $keyColumn => $c) {
            $values = $penilaians->where('id_criteria', $c->id);
            $nilai = $values->pluck('nilai')->toArray();
            
            // Periksa jika $nilai kosong, hindari error max/min
            if (empty($nilai)) {
                foreach ($alternatifs as $keyRow => $a) {
                    $normalizedValues[$keyRow][$keyColumn] = 0;
                }
                continue;
            }

            $maxVal = max($nilai);
            $minVal = min($nilai);
            $range = ($maxVal - $minVal) == 0 ? 1 : $maxVal - $minVal; // Hindari pembagian nol

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

    private function getNormalisasiTerbobot($criterias, $normalized)
    {
        $weightedNormalization = [];
        // Pastikan $normalized tidak kosong sebelum diiterasi
        if (empty($normalized)) {
            return [];
        }
        
        foreach ($normalized as $keyRow => $row) {
            foreach ($criterias as $keyColumn => $c) {
                // Pastikan $row[$keyColumn] ada
                $normalizedValue = $row[$keyColumn] ?? 0;
                $weightedNormalization[$keyRow][$keyColumn] = round($c->weight * $normalizedValue, 3);
            }
        }
        return $weightedNormalization;
    }

    private function getIdeal($weightedNormalization)
    {
        $ideal = [];
        // Pastikan $weightedNormalization tidak kosong
        if (empty($weightedNormalization)) {
            return [];
        }

        // Ambil kunci kolom dari baris pertama (asumsi semua baris memiliki struktur kolom yang sama)
        $firstRowKeys = array_keys(current($weightedNormalization));
        
        foreach ($firstRowKeys as $col) {
            $ideal[$col] = max(array_column($weightedNormalization, $col));
        }
        return $ideal;
    }

    private function getSiRi($criterias, $alternatifs, $weightedNormalization, $ideal)
    {
        $sum = [];
        $max = [];

        foreach ($alternatifs as $keyRow => $a) {
            $selisih = [];
            foreach ($criterias as $keyColumn => $c) {
                // Pastikan indeks ada sebelum diakses
                $vij = $weightedNormalization[$keyRow][$keyColumn] ?? 0;
                $idealVal = $ideal[$keyColumn] ?? 0;
                
                $selisih[] = round($idealVal - $vij, 6);
            }
            // Jika $selisih kosong (misalnya karena data tidak lengkap), array_sum dan max akan error
            $sum[$keyRow] = empty($selisih) ? 0 : round(array_sum($selisih), 6);    // S_i
            $max[$keyRow] = empty($selisih) ? 0 : round(max($selisih), 6);          // R_i
        }

        return ['Si' => $sum, 'Ri' => $max];
    }

    private function getQi($Si, $Ri)
    {
        $V = 0.5;
        // Pastikan $Si dan $Ri tidak kosong sebelum min/max
        $Smax = empty($Si) ? 0 : max($Si);
        $Smin = empty($Si) ? 0 : min($Si);
        $Rmax = empty($Ri) ? 0 : max($Ri);
        $Rmin = empty($Ri) ? 0 : min($Ri);

        $finalValues = [];
        foreach ($Si as $key => $s) {
            // Hindari pembagian nol
            $denomS = ($Smax - $Smin);
            $val1 = ($denomS == 0) ? 0 : ($s - $Smin) / $denomS;
            
            $denomR = ($Rmax - $Rmin);
            $val2 = ($denomR == 0) ? 0 : ($Ri[$key] - $Rmin) / $denomR;
            
            $finalValues[$key] = round($V * $val1 + (1 - $V) * $val2, 3);
        }

        return $finalValues;
    }

    private function getRanking($finalValues)
    {
        $ranking = [];
        // Pastikan $finalValues tidak kosong
        if (empty($finalValues)) {
            return [];
        }
        asort($finalValues); // Mengurutkan dari nilai terkecil (VIKOR: nilai Q terkecil adalah yang terbaik)
        $rank = 1;
        foreach (array_keys($finalValues) as $key) {
            $ranking[$key] = $rank++;
        }
        return $ranking;
    }

    public function tampilNormalisasi()
    {
        $criterias = criteria::all();
        $alternatifs = alternatif::all();
        $penilaians = penilaian::with(['alternatif', 'criteria'])->get();

        if ($criterias->isEmpty() || $alternatifs->isEmpty() || $penilaians->isEmpty()) {
            return redirect()->back()->with('error', 'Data kriteria, alternatif, atau penilaian tidak lengkap.');
        }

        $normalizedValues = $this->getNormalisasi($criterias, $alternatifs, $penilaians);
        $desiredDecimalPlaces = 3; // Variabel ini sebenarnya sudah di handle di getNormalisasi

        return view('dashboard.hitung.normalisasi', [
            'criteria' => $criterias,
            'alternatif' => $alternatifs,
            'normalisasi' => $normalizedValues,
        ]);
    }

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
        // $desiredDecimalPlaces = 3; // Variabel ini sudah di handle di getNormalisasiTerbobot

        return view('dashboard.hitung.normalisasiterbobot', [
            'criteria' => $criterias,
            'alternatif' => $alternatifs,
            'weightedNormalization' => $weightedNormalization,
        ]);
    }

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
        // $desiredDecimalPlaces = 3; // Variabel ini tidak digunakan langsung di sini

        return view('dashboard.hitung.selisihideal', [
            'criteria' => $criterias,
            'alternatif' => $alternatifs,
            'ideal' => $ideal,
            'weightedNormalization' => $weightedNormalization,
        ]);
    }

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

    public function tampilUtility()
    {
        $criterias = Criteria::all();
        $alternatifs = Alternatif::all();
        $penilaian = Penilaian::with(['alternatif', 'criteria'])->get(); // Gunakan with untuk relasi

        // Validasi awal: jika tidak ada data, hentikan
        if ($criterias->isEmpty() || $alternatifs->isEmpty() || $penilaian->isEmpty()) {
            return redirect()->back()->with('error', 'Data kriteria, alternatif, atau penilaian tidak ditemukan.');
        }

        // Ambil bobot dari kriteria
        // Pastikan bobot kriteria tidak kosong atau nol semua
        $bobot = $criterias->pluck('weight')->toArray();
        if (empty($bobot) || array_sum($bobot) == 0) {
            return redirect()->back()->with('error', 'Bobot kriteria tidak valid atau nol semua.');
        }

        // Matriks Keputusan (sebaiknya ini adalah nilai asli dari penilaian)
        $matriks = [];
        foreach ($alternatifs as $i => $alt) {
            foreach ($criterias as $j => $c) {
                $item = $penilaian->where('id_alternatif', $alt->id)
                    ->where('id_criteria', $c->id)
                    ->first();
                $nilai = $item ? $item->nilai : 0; // Default 0 jika tidak ada penilaian

                $matriks[$i][$j] = $nilai;
            }
        }
        
        // --- Bagian perhitungan ini tampaknya duplikasi dari metode perhitungan VIKOR inti ---
        // Seharusnya ini bisa menggunakan metode helper yang sudah ada di atas
        // Namun, jika ini khusus untuk tampilan utility, kita ikuti alur yang ada.

        // Normalisasi (menggunakan metode yang ada di atas)
        $normalizedValues = $this->getNormalisasi($criterias, $alternatifs, $penilaian);
        
        // Normalisasi Terbobot (menggunakan metode yang ada di atas)
        $weightedNormalization = $this->getNormalisasiTerbobot($criterias, $normalizedValues);

        // Solusi Ideal Positif (menggunakan metode yang ada di atas)
        $ideal = $this->getIdeal($weightedNormalization);

        // Hitung selisih |f* - vij| (ini adalah bagian kunci untuk Si dan Ri)
        $SiRi = $this->getSiRi($criterias, $alternatifs, $weightedNormalization, $ideal);
        $Si = $SiRi['Si'];
        $Ri = $SiRi['Ri'];

        return view('dashboard.hitung.utility', [
            'criteria' => $criterias,
            'alternatif' => $alternatifs,
            'Si' => $Si,
            'Ri' => $Ri,
            'matriks' => $matriks, // Matriks keputusan awal
            'normal' => $normalizedValues, // Matriks normalisasi
            'terbobot' => $weightedNormalization, // Matriks normalisasi terbobot
            'ideal' => $ideal // Tambahkan ideal ke view jika diperlukan
        ]);
    }

    public function tampilKompromi()
{
    // Ambil semua alternatif dan hasil perhitungan dari database hasil_vikor
    $alternatif = Alternatif::all();

    // Ambil hasil VIKOR yang telah disimpan sebelumnya
    $hasil = HasilVikor::with('alternatif')->get();

    // Buat array finalValues dan ranking sesuai index alternatif
    $finalValues = [];
    $ranking = [];

    foreach ($alternatif as $key => $alt) {
        $data = $hasil->firstWhere('id_alternatif', $alt->id);

        $finalValues[$key] = $data ? $data->nilai_q : null;
        $ranking[$key] = $data ? $data->ranking : null;

        // Isi field alternatif_code jika belum ada
        $alt->alternatif_code = $alt->kode ?? 'A' . ($key + 1);
    }

    return view('dashboard.hitung.kompromi', [
        'alternatif' => $alternatif,
        'finalValues' => $finalValues,
        'ranking' => $ranking,
    ]);
}

    public function simpan(Request $request)
    {
        // Validasi dan cegah error array null
        if (!isset($request->finalValues) || !is_array($request->finalValues) ||
            !isset($request->alternatif) || !is_array($request->alternatif) ||
            !isset($request->ranking) || !is_array($request->ranking)) {
            return back()->with('error', 'Data yang diterima tidak valid untuk disimpan.');
        }

        DB::beginTransaction(); // Mulai transaksi database
        try {
            // Hapus data lama (jika ingin menimpa setiap kali simpan)
            // HasilVikor::truncate(); // Ini sudah dilakukan di method index(), jadi mungkin tidak perlu lagi di sini

            foreach ($request->finalValues as $key => $value) {
                // Pastikan indeks ada untuk menghindari error
                $alternatifId = $request->alternatif[$key] ?? null;
                $rankingValue = $request->ranking[$key] ?? null;

                if ($alternatifId !== null) { // Hanya simpan jika alternatif_id ada
                    HasilVikor::updateOrCreate(
                        ['id_alternatif' => $alternatifId], // Kriteria untuk menemukan record yang ada
                        [
                            'nilai_q' => $value,
                            'ranking' => $rankingValue,
                            // Anda mungkin ingin menambahkan nilai_s dan nilai_r juga jika ada di request
                            // 'nilai_s' => $request->nilai_s[$key] ?? 0,
                            // 'nilai_r' => $request->nilai_r[$key] ?? 0,
                            'status' => ($rankingValue !== null && $rankingValue <= 10) ? 'Lulus' : 'Tidak Lulus',
                            'created_at' => now(),
                            'updated_at' => now()
                        ]
                    );
                }
            }

            DB::commit(); // Komit transaksi

            // Buat PDF
            // Pastikan data yang dikirim ke PDF sesuai dengan apa yang diharapkan view pdf.hasil-vikor
            $dataPdf = [
                'alternatif' => Alternatif::whereIn('id', $request->alternatif)->get(), // Ambil objek alternatif
                'finalValues' => $request->finalValues,
                'ranking' => $request->ranking,
                // Mungkin juga perlu nilai S dan R jika PDF menampilkannya
                // 'Si' => $request->Si ?? [],
                // 'Ri' => $request->Ri ?? [],
            ];
            
            // Untuk memastikan data di PDF sama dengan yang baru disimpan
            // Anda bisa mengambil data dari HasilVikor setelah proses updateOrCreate
            $hasilPdf = HasilVikor::with('alternatif')->orderBy('ranking', 'asc')->get();
            $dataPdf['hasilLengkap'] = $hasilPdf;


            $pdf = PDF::loadView('pdf.hasil-vikor', $dataPdf);
            return $pdf->download('hasil-perhitungan-vikor.pdf');

        } catch (\Exception $e) {
            DB::rollBack(); // Rollback transaksi jika ada error
            return back()->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
        }
    }
}