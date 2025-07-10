<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CriteriaSub extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terkait dengan model
     *
     * @var string
     */
    protected $table = 'criteria_subs';

    /**
     * Kolom yang dapat diisi secara massal
     *
     * @var array
     */
    protected $fillable = [
        'criteria_id',
        'label', 
        'point',
    ];

    /**
     * Relasi many-to-one ke model Criteria
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function criteria(): BelongsTo
    {
        return $this->belongsTo(Criteria::class, 'criteria_id');
    }

    /**
     * The "booted" method of the model
     *
     * @return void
     */
    protected static function booted()
    {
        static::creating(function ($criteriaSub) {
            // Validasi atau logika tambahan saat membuat sub kriteria
            if ($criteriaSub->point < 0) {
                throw new \Exception("Point tidak boleh negatif");
            }
        });
    }
}