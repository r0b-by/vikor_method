<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class alternatif extends Model
{
    use HasFactory;
    protected $table = 'alternatifs';
    public $timestamps = false;

    protected $fillable = [
        'user_id', // Tambahkan ini untuk menghubungkan dengan User
        'no_alternatif',
        'alternatif_code',
        'alternatif_name',
    ];

    public function penilaian()
    {
        return $this->hasMany(penilaian::class, 'id_alternatif');
    }

    /**
     * Relasi ke model User.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}