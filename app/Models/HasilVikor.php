<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HasilVikor extends Model
{
    protected $table = 'hasil_vikor';
    protected $fillable = [
        'id_alternatif',
        'nilai_s',
        'nilai_r',
        'nilai_q',
        'ranking',
        'status',
        'tahun_ajaran',         // Tambahkan ini
        'semester',             // Tambahkan ini
        'tanggal_perhitungan',  // Tambahkan ini
        'jam_perhitungan'       // Tambahkan ini
    ];

    protected $casts = [
        'tanggal_perhitungan' => 'date',
        'jam_perhitungan' => 'datetime',
    ];
    public function alternatif()
    {
        return $this->belongsTo(Alternatif::class, 'id_alternatif');
    }
    
    // Anda mungkin juga ingin menambahkan relasi ke penilaian jika diperlukan untuk melacak dari mana hasil ini berasal
    // public function penilaians()
    // {
    //     return $this->hasMany(Penilaian::class, 'id_alternatif', 'id_alternatif')
    //                 ->where('tahun_ajaran', $this->tahun_ajaran) // Contoh, sesuaikan logika
    //                 ->where('semester', $this->semester);
    // }
}
