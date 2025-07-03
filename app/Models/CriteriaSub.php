<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CriteriaSub extends Model
{
    use HasFactory;

    protected $table = 'criteria_subs'; // atau sesuaikan dengan nama tabel kamu

    protected $fillable = [
        'criteria_id',
        'label',
        'point',
    ];

    public function criteria()
    {
        return $this->belongsTo(criteria::class, 'criteria_id');
    }
}
