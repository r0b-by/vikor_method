<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission; // Opsional jika Anda akan menggunakan permissions

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions (PENTING: Buat semua izin yang akan digunakan di sini)
        Permission::firstOrCreate(['name' => 'approve registrations', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'reject registrations', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'manage users', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'manage criterias', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'manage alternatifs', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'manage penilaians', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'perform hitung', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'view dashboard', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'view hasil vikor', 'guard_name' => 'web']);
        // Menambahkan permission 'view all users' yang menyebabkan error sebelumnya
        Permission::firstOrCreate(['name' => 'view all users', 'guard_name' => 'web']);


        // Create roles
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $guruRole = Role::firstOrCreate(['name' => 'guru', 'guard_name' => 'web']);
        $siswaRole = Role::firstOrCreate(['name' => 'siswa', 'guard_name' => 'web']);

        // Assign permissions to roles
        // Admin bisa melakukan semuanya
        $adminRole->givePermissionTo(Permission::all());

        // Guru memiliki izin yang spesifik
        $guruRole->givePermissionTo([
            'view dashboard',
            'manage criterias',
            'manage alternatifs',
            'manage penilaians',
            'perform hitung',
            'view hasil vikor',
            'view all users', // Sekarang permission ini sudah dibuat
        ]);

        // Siswa memiliki izin yang spesifik
        $siswaRole->givePermissionTo([
            'view dashboard',
            'view hasil vikor',
        ]);

        // Create a default admin user (if not exists) and assign role
        $admin = \App\Models\User::firstOrCreate(
            ['email' => 'admin@example.com'], // Ganti dengan email admin Anda
            [
                'name' => 'Administrator',
                'password' => bcrypt('password'), // Ganti dengan password kuat di produksi
                'status' => 'active', // Admin langsung aktif
            ]
        );
        $admin->assignRole('admin');
    }
}