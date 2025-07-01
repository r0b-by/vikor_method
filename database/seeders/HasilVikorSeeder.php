<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class HasilVikorSeeder extends Seeder
{
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('hasil_vikor')->truncate();
        Schema::enableForeignKeyConstraints();

        $alternatifs = DB::table('alternatifs')->get();
        $penilaians = DB::table('penilaians')
            ->join('criterias', 'penilaians.id_criteria', '=', 'criterias.id')
            ->select('penilaians.*', 'criterias.weight', 'criterias.criteria_type')
            ->get()
            ->groupBy('id_alternatif');

        $results = [];
        foreach ($alternatifs as $alternatif) {
            $alternatifPenilaians = $penilaians[$alternatif->id] ?? [];
            
            $S = 0;
            $R = 0;
            
            foreach ($alternatifPenilaians as $penilaian) {
                $weightedValue = $penilaian->nilai * $penilaian->weight;
                $S += $weightedValue;
                $R = max($R, $weightedValue);
            }
            
            $Q = 0.5 * ($S / 100) + 0.5 * ($R / 100); // Contoh perhitungan Q
            
            $results[] = [
                'id_alternatif' => $alternatif->id,
                'nilai_s' => $S,
                'nilai_r' => $R,
                'nilai_q' => $Q,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Urutkan berdasarkan nilai Q (ascending untuk benefit criteria)
        usort($results, function($a, $b) {
            return $a['nilai_q'] <=> $b['nilai_q'];
        });

        // Tambahkan ranking
        foreach ($results as $index => &$result) {
            $result['ranking'] = $index + 1;
            $result['status'] = $result['ranking'] <= 15 ? 'Lulus' : 'Tidak Lulus';
        }

        DB::table('hasil_vikor')->insert($results);
    }
}