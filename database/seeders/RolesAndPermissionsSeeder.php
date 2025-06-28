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
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Buat roles
        Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'guru']);
        Role::firstOrCreate(['name' => 'siswa']);

        // Contoh: jika Anda ingin menambahkan izin
        // Permission::firstOrCreate(['name' => 'manage users']);
        // Permission::firstOrCreate(['name' => 'view dashboard']);

        // Contoh: Menetapkan izin ke peran (opsional)
        // $adminRole = Role::findByName('admin');
        // $adminRole->givePermissionTo('manage users');
    }
}