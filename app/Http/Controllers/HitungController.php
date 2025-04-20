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
    public function index()
    {
        $criterias = criteria::all();
        $alternatifs = alternatif::all();
        $penilaians = penilaian::with(['alternatif', 'criteria'])->get();

        $normalizedValues = [];
        $weightedNormalization = [];
        $desiredDecimalPlaces = 3;

        // NORMALISASI & TERBOBOT
        foreach ($criterias as $keyColumn => $c) {
            $values = $penilaians->where('id_criteria', $c->id);
            $nilai = $values->pluck('nilai')->toArray();
            $maxVal = max($nilai);
            $minVal = min($nilai);
            $range = ($maxVal - $minVal) == 0 ? 1 : $maxVal - $minVal;

            foreach ($alternatifs as $keyRow => $a) {
                $value = $values->where('id_alternatif', $a->id)->first();

                if (!$value) {
                    $normalizedValues[$keyRow][$keyColumn] = 0;
                    $weightedNormalization[$keyRow][$keyColumn] = 0;
                    continue;
                }

                $temp = ($value->criteria->criteria_type == 'Cost')
                    ? ($maxVal - $value->nilai) / $range
                    : ($value->nilai - $minVal) / $range;

                // Jangan dibulatkan di sini
$normalized = $temp;
$weighted = $value->criteria->weight * $normalized;

// Simpan bulatan hanya saat ditampilkan
$normalizedValues[$keyRow][$keyColumn] = round($normalized, $desiredDecimalPlaces);
$weightedNormalization[$keyRow][$keyColumn] = round($weighted, $desiredDecimalPlaces);

            }
        }

        // NILAI IDEAL f*
        $ideal = [];
        foreach (array_keys($criterias->toArray()) as $keyColumn) {
            $ideal[$keyColumn] = max(array_column($weightedNormalization, $keyColumn));
        }

        // HITUNG S_i dan R_i
        $sum = [];
        $max = [];
        foreach ($alternatifs as $keyRow => $a) {
            $selisih = [];
            foreach ($criterias as $keyColumn => $c) {
                $vij = $weightedNormalization[$keyRow][$keyColumn] ?? 0;
                $selisih[] = round($ideal[$keyColumn] - $vij, 6);
            }
            $sum[$keyRow] = round(array_sum($selisih), 6);     // S_i
            $max[$keyRow] = round(max($selisih), 6);           // R_i
        }

        // HITUNG Q_i
        $Smax = max($sum);
        $Smin = min($sum);
        $Rmax = max($max);
        $Rmin = min($max);
        $V = 0.5;

        $finalValues = [];
        foreach ($alternatifs as $key => $value) {
            $val1 = ($Smax - $Smin) == 0 ? 0 : ($sum[$key] - $Smin) / ($Smax - $Smin);
            $val2 = ($Rmax - $Rmin) == 0 ? 0 : ($max[$key] - $Rmin) / ($Rmax - $Rmin);
            $Qi = round($V * $val1 + (1 - $V) * $val2, $desiredDecimalPlaces);
            $finalValues[$key] = $Qi;
        }

        // RANKING
        $sortedFinal = $finalValues;
        asort($sortedFinal);
        $ranking = [];
        $rank = 1;
        foreach (array_keys($sortedFinal) as $key) {
            $ranking[$key] = $rank++;
        }

        // Simpan ke DB
        HasilVikor::truncate();
        foreach ($alternatifs as $key => $alt) {
            HasilVikor::create([
                'id_alternatif' => $alt->id,
                'nilai_s' => $sum[$key],
                'nilai_r' => $max[$key],
                'nilai_q' => $finalValues[$key],
                'ranking' => $ranking[$key],
                'status' => $ranking[$key] <= 10 ? 'Lulus' : 'Tidak Lulus',
            ]);
        }

        return view('dashboard.hitung', [
            'criteria' => $criterias,
            'alternatif' => $alternatifs,
            'penilaian' => $penilaians,
            'normalisasi' => $normalizedValues,
            'weightedNormalization' => $weightedNormalization,
            'ideal' => $ideal,
            'Si' => $sum,
            'Ri' => $max,
            'finalValues' => $finalValues,
            'ranking' => $ranking,
        ]);
    }

    public function simpan(Request $request)
    {
        // Validasi dan cegah error array null
        if (!isset($request->finalValues) || !is_array($request->finalValues)) {
            return back()->with('error', 'Data tidak valid.');
        }

        foreach ($request->finalValues as $key => $value) {
            DB::table('hasil_vikor')->insert([
                'alternatif_id' => $request->alternatif[$key] ?? null,
                'qi' => $value,
                'ranking' => $request->ranking[$key] ?? null,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // Buat PDF
        $data = [
            'alternatif' => $request->alternatif,
            'finalValues' => $request->finalValues,
            'ranking' => $request->ranking,
        ];

        $pdf = PDF::loadView('pdf.hasil-vikor', $data);
        return $pdf->download('hasil-perhitungan-vikor.pdf');
    }
}
