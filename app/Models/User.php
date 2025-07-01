<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name', 'email', 'password', 'nis', 'kelas', 'jurusan', 'alamat',
        'status',
        'approved_by',
        'approved_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'approved_at' => 'datetime',
    ];

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Relasi ke model Alternatif.
     * Asumsi satu user (siswa) memiliki satu alternatif.
     */
    public function alternatif()
    {
        return $this->hasOne(Alternatif::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
