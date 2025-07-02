<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penilaian extends Model
{
    protected $table = 'penilaians';
   protected $fillable = [
        'id_alternatif',
        'id_criteria',
        'nilai',
        'certificate_details',
        'tahun_ajaran', // Tambahkan ini
        'semester',     // Tambahkan ini
        'tanggal_penilaian', // Tambahkan ini
        'jam_penilaian', // Tambahkan ini
        'academic_period_id',
    ];
    protected $casts = [
        'certificate_details' => 'array'
    ];

    public function alternatif()
    {
        return $this->belongsTo(Alternatif::class, 'id_alternatif');
    }

    public function criteria()
    {
        return $this->belongsTo(Criteria::class, 'id_criteria');
    }

    public function academicPeriod()
    {
        return $this->belongsTo(AcademicPeriod::class, 'academic_period_id');
    }

    // Helper untuk menghitung total poin dari detail sertifikat
    public function calculateCertificatePoints()
    {
        if (empty($this->certificate_details)) {
            return 0;
        }

        $pointValues = [
            'Nasional' => 10,
            'Provinsi' => 8,
            'Kabupaten/Kota' => 6,
            'Sekolah' => 4,
            'Partisipasi' => 2
        ];

        $total = 0;
        foreach ($this->certificate_details as $cert) {
            $level = $cert['level'];
            $count = $cert['count'];
            $total += ($pointValues[$level] ?? 0) * $count;
        }

        return $total;
    }
}