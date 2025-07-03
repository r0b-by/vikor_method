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

    protected $appends = ['total_certificate_points'];
    
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


    public function getTotalCertificatePointsAttribute(): int
    {
        if (empty($this->certificate_details)) {
            return 0;
        }

        $total = 0;
        foreach ($this->certificate_details as $cert) {
            $total += ($cert['point'] ?? 0) * ($cert['count'] ?? 1);
        }
        
        return $total;
    }
}
