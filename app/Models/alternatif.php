<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alternatif extends Model
{
    use HasFactory;
    protected $table = 'alternatifs';
    public $timestamps = true; 

    protected $fillable = [
        'user_id',
        'alternatif_code',
        'alternatif_name',
        'tahun_ajaran', 
        'semester',
        'status_perhitungan',
    ];

    public function penilaian()
    {
        return $this->hasMany(Penilaian::class, 'id_alternatif');
    }

    /**
     * Relasi ke model User.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ke model HasilVikor.
     * Asumsi satu alternatif memiliki satu hasil VIKOR (misalnya, yang terakhir dihitung).
     */
    public function hasilVikor()
    {
        return $this->hasOne(HasilVikor::class, 'id_alternatif');
    }
}
