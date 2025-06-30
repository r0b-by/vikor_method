<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles; // Import trait Spatie

class User extends Authenticatable implements MustVerifyEmail // Jika Anda menggunakan verifikasi email
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles; // Tambahkan HasRoles

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    // app/Models/User.php
    protected $fillable = [
        'name', 'email', 'password', 'nis', 'kelas', 'jurusan', 'alamat',
        'status', // <<< Pastikan ini ada
        'approved_by',
        'approved_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'approved_at' => 'datetime', // Cast ini juga
    ];

    // Relasi ke admin yang menyetujui
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}