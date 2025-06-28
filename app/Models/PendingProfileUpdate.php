<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PendingProfileUpdate extends Model
{
    use HasFactory;

    // Nama tabel di database
    protected $table = 'pending_profile_updates';

    // Kolom yang dapat diisi secara massal
    protected $fillable = [
        'user_id',
        'original_data',
        'proposed_data',
        'reason',
        'status',
        'approved_by',
        'approved_at',
    ];

    // Mengubah atribut tertentu menjadi array/JSON secara otomatis
    protected $casts = [
        'original_data' => 'array',
        'proposed_data' => 'array',
        'approved_at' => 'datetime',
    ];

    /**
     * Get the user that owns the pending profile update.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the admin user who approved the update.
     */
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
