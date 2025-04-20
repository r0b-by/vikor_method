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
        'no_alternatif',
        'alternatif_code',
        'alternatif_name',
    ];

    public function penilaian()
    {
        return $this->hasMany(penilaian::class, 'id_alternatif');
    }
}
