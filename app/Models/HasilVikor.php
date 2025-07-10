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
        // Tambahkan kolom baru ini ke fillable
        'tahun_ajaran',
        'semester',
        'tanggal_penilaian', // Mengikuti penamaan dari deskripsi Anda di awal
        'jam_penilaian',     // Mengikuti penamaan dari deskripsi Anda di awal
        'academic_period_id', // Jika Anda memutuskan menggunakan relasi dengan tabel periodik
    ];

    protected $casts = [
        'tanggal_penilaian' => 'date',
        // Untuk jam_penilaian, jika hanya waktu, Anda bisa biarkan string atau gunakan 'datetime'
        // Jika hanya ingin menyimpan waktu tanpa tanggal, sebaiknya simpan sebagai string (VARCHAR)
        // atau gunakan Carbon untuk parse dan format jika perlu.
        // Jika jam_penilaian datang sebagai 'HH:MM:SS', Anda mungkin tidak perlu cast ini.
        // Jika Anda menyimpannya sebagai datetime lengkap (misal 'YYYY-MM-DD HH:MM:SS'), maka 'datetime' tepat.
        'jam_penilaian'     => 'datetime', // Akan mengonversi ke objek Carbon
    ];

    public function alternatif()
    {
        return $this->belongsTo(Alternatif::class, 'id_alternatif');
    }

    // Jika Anda memiliki model AcademicPeriod untuk mengelola tahun ajaran dan semester
    public function academicPeriod()
    {
        return $this->belongsTo(AcademicPeriod::class, 'academic_period_id');
    }

     public function penilaians()
     {
         return $this->hasMany(Penilaian::class, 'id_alternatif', 'id_alternatif')
                        ->where('tahun_ajaran', $this->tahun_ajaran) // Filter jika HasilVikor menyimpan ini
                        ->where('semester', $this->semester); // Filter jika HasilVikor menyimpan ini
     }
}