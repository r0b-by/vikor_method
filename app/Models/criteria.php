<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class criteria extends Model
{
    use HasFactory;

    protected $table = 'criterias';
    public $timestamps = true;

    protected $fillable = [
        'no',
        'criteria_code',
        'criteria_name',
        'criteria_type',
        'weight',
        'input_type', // Tambahkan ini agar bisa disimpan dari form
    ];

    /**
     * Relasi ke penilaian (nilai yang diberikan terhadap kriteria ini)
     */
    public function penilaian()
    {
        return $this->hasMany(Penilaian::class, 'id_criteria');
    }

    /**
     * Relasi ke sub-kriteria jika input_type = 'poin'
     */
    public function subs()
    {
        return $this->hasMany(CriteriaSub::class, 'criteria_id');
    }
}
