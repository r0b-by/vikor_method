<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User; // Import model User
use Illuminate\Support\Facades\Hash; // Untuk hashing password
use Spatie\Permission\Models\Role; // Import model Role dari Spatie

class UserSeeder extends Seeder
{
    /**
     * Jalankan seed database.
     */
    public function run(): void
    {
        // Pastikan peran 'admin', 'guru', dan 'siswa' sudah ada di database.
        // Jika belum, pertimbangkan untuk menjalankan RolesAndPermissionsSeeder terlebih dahulu.

        // Membuat user Admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'], // Coba temukan user berdasarkan email
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'), // Ganti dengan password yang kuat di produksi!
                'email_verified_at' => now(),
                'status' => 'active', // Status langsung aktif untuk admin
                'nis' => 'ADMIN001', // Data dummy untuk NIS
                'kelas' => 'XII', // Data dummy untuk Kelas
                'jurusan' => 'Administrasi', // Data dummy untuk Jurusan
                'alamat' => 'Jl. Admin No. 123', // Data dummy untuk Alamat
            ]
        );
        $admin->assignRole('admin'); // Tetapkan peran 'admin'

        // Membuat user Guru
        $guru = User::firstOrCreate(
            ['email' => 'guru@example.com'],
            [
                'name' => 'Guru User',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'status' => 'active', // Status langsung aktif untuk guru
                'nis' => 'GURU001', // Data dummy untuk NIS
                'kelas' => 'X-XII', // Data dummy untuk Kelas (rentang)
                'jurusan' => 'Informatika', // Data dummy untuk Jurusan
                'alamat' => 'Jl. Guru No. 45', // Data dummy untuk Alamat
            ]
        );
        $guru->assignRole('guru'); // Tetapkan peran 'guru'

        // Membuat user Siswa
        $siswa = User::firstOrCreate(
            ['email' => 'siswa@example.com'],
            [
                'name' => 'Siswa User',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'status' => 'active', // Status langsung aktif untuk siswa
                'nis' => 'NIS12345', // Data dummy untuk NIS
                'kelas' => 'XI A', // Data dummy untuk Kelas
                'jurusan' => 'Rekayasa Perangkat Lunak', // Data dummy untuk Jurusan
                'alamat' => 'Jl. Siswa No. 78', // Data dummy untuk Alamat
            ]
        );
        $siswa->assignRole('siswa'); // Tetapkan peran 'siswa'

        $this->command->info('Contoh user (Admin, Guru, Siswa) berhasil dibuat dan peran ditetapkan.');
    }
}

