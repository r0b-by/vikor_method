<?php

namespace App\Services;

use InvalidArgumentException;

class VikorService
{
    /**
     * Menghitung nilai Utility Measure (Si) dan Regret Measure (Ri) untuk metode VIKOR.
     *
     * Si adalah jumlah tertimbang dari selisih nilai ideal dengan nilai normalisasi terbobot.
     * Ri adalah nilai maksimum dari selisih nilai ideal dengan nilai normalisasi terbobot.
     *
     * @param array $weightedNormalization Array 2D berisi nilai normalisasi terbobot
     *        Format: [alternatif_index][criteria_index] => nilai
     * @param array $ideal Array berisi nilai ideal untuk setiap kriteria
     *        Format: [criteria_index] => nilai_ideal
     * 
     * @return array Mengembalikan array dengan keys 'Si' dan 'Ri'
     *         'Si' => [alternatif_index] => nilai_Si
     *         'Ri' => [alternatif_index] => nilai_Ri
     *
     * @throws InvalidArgumentException Jika input tidak valid
     */
    public function hitungSiRi(array $weightedNormalization, array $ideal): array
    {
        // Validasi input dasar
        if (empty($weightedNormalization) || empty($ideal)) {
            throw new InvalidArgumentException('Input array tidak boleh kosong');
        }

        $Si = [];
        $Ri = [];

        foreach ($weightedNormalization as $i => $row) {
            // Pastikan setiap baris adalah array
            if (!is_array($row)) {
                throw new InvalidArgumentException('Format weightedNormalization tidak valid');
            }

            $selisih = [];

            foreach ($row as $j => $value) {
                // Pastikan nilai ideal untuk kriteria ini ada
                if (!array_key_exists($j, $ideal)) {
                    throw new InvalidArgumentException('Indeks kriteria tidak konsisten antara weightedNormalization dan ideal');
                }

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