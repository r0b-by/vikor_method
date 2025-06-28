<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User; // Import model User
use Illuminate\Support\Facades\Hash; // Untuk hashing password
use Spatie\Permission\Models\Role; // Import model Role dari Spatie

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan peran 'admin', 'guru', dan 'siswa' sudah ada di database.
        // Jika belum, jalankan php artisan db:seed --class=RolesAndPermissionsSeeder terlebih dahulu.

        // Membuat user Admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'], // Coba temukan user berdasarkan email
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'), // Ganti dengan password yang kuat di produksi!
                'email_verified_at' => now(),
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
            ]
        );
        $siswa->assignRole('siswa'); // Tetapkan peran 'siswa'

        $this->command->info('Contoh user (Admin, Guru, Siswa) berhasil dibuat dan peran ditetapkan.');
    }
}
