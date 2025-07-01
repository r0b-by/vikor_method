<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HasilVikor extends Model
{
    protected $table = 'hasil_vikor';
    protected $fillable = ['id_alternatif', 'nilai_s', 'nilai_r', 'nilai_q', 'ranking', 'status'];

    public function alternatif()
    {
        return $this->belongsTo(Alternatif::class, 'id_alternatif');
    }
}
