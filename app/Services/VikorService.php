<?php

namespace App\Services;

class VikorService
{
    /**
     * Hitung nilai Si dan Ri berdasarkan selisih dari nilai ideal
     */
    public function hitungSiRi(array $weightedNormalization, array $ideal): array
    {
        $Si = [];
        $Ri = [];

        foreach ($weightedNormalization as $i => $row) {
            $selisih = [];

            foreach ($row as $j => $value) {
                $selisih[$j] = abs($ideal[$j] - $value);
            }

            $Si[$i] = array_sum($selisih);
            $Ri[$i] = max($selisih);
        }

        return [
            'Si' => $Si,
            'Ri' => $Ri,
        ];
    }
}
