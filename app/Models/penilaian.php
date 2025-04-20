<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class penilaian extends Model
{
    use HasFactory;
    protected $table = 'penilaians';
    public $timestamps = false;

    protected $guarded = [];

    public function criteria()
    {
        return $this->belongsTo(criteria::class, 'id_criteria', 'id');
    }

    public function alternatif()
    {
        return $this->belongsTo(alternatif::class, 'id_alternatif', 'id');
    }

}
