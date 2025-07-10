<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Criteria extends Model
{
    use HasFactory;

    protected $table = 'criterias';
    public $timestamps = true; // Ini sudah benar

    protected $fillable = [
        'no',
        'criteria_code',
        'criteria_name',
        'criteria_type',
        'weight',
        'input_type',
        // --- PERBAIKAN PENTING DI SINI ---
        'created_by', // <-- TAMBAHKAN INI
        'updated_by', // <-- TAMBAHKAN INI (jika Anda sudah menambahkan kolom ini di migrasi)
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

    /**
     * Relasi untuk user pembuat (creator)
     */
    public function creator() // <-- TAMBAHKAN INI
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Relasi untuk user pengubah terakhir (editor)
     */
    public function editor() // <-- TAMBAHKAN INI (jika Anda sudah menambahkan kolom ini di migrasi)
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Boot the model
     * Menambahkan global scope untuk mengurutkan berdasarkan no
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('order', function ($builder) {
            $builder->orderBy('no', 'asc');
        });
    }
}