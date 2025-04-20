<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class criteria extends Model
{
    use HasFactory;
    protected $table = 'criterias';
    public $timestamps = false;

    protected $fillable = [
        'no',
        'criteria_code',
        'criteria_name',
        'criteria_type',
        'weight',
    ];
    public function penilaian()
    {
        return $this->hasMany(penilaian::class, 'id_criteria');
    }
}
