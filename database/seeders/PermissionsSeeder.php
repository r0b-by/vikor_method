<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Schema; // Import Schema facade
use Illuminate\Support\Facades\DB; // Import DB facade

class PermissionsSeeder extends Seeder
{
    public function run()
    {
        // Forget cached permissions to ensure fresh data
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // No need to truncate roles/permissions tables here if UserSeeder already does it.
        // If this seeder is run independently, you might need to add:
        /*
        Schema::disableForeignKeyConstraints();
        DB::table('model_has_roles')->truncate();
        DB::table('model_has_permissions')->truncate();
        DB::table('role_has_permissions')->truncate();
        DB::table('roles')->truncate();
        DB::table('permissions')->truncate();
        Schema::enableForeignKeyConstraints();
        */

        $permissions = [
            'user-list',
            'user-create',
            'user-edit',
            'user-delete',
            'role-list',
            'role-create',
            'role-edit',
            'role-delete',
            'criteria-list',
            'criteria-create',
            'criteria-edit',
            'criteria-delete',
            'alternatif-list',
            'alternatif-create',
            'alternatif-edit',
            'alternatif-delete',
            'penilaian-list',
            'penilaian-create',
            'penilaian-edit',
            'penilaian-delete',
            'hasil-vikor-list',
            'hasil-vikor-calculate',
            'approval-list',
            'approval-approve',
            'approval-reject',
            'laporan-view',
            'nilai-edit',
            'rekomendasi-buat',
        ];

        foreach ($permissions as $permission) {
            // Use firstOrCreate to prevent "PermissionAlreadyExists" if run multiple times
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // Create roles or retrieve existing ones if UserSeeder already created them
        // This is the key change: Use firstOrCreate instead of create
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $teacherRole = Role::firstOrCreate(['name' => 'guru', 'guard_name' => 'web']);
        $studentRole = Role::firstOrCreate(['name' => 'siswa', 'guard_name' => 'web']);

        // Assign all permissions to the admin role
        $adminRole->givePermissionTo(Permission::all());

        // Assign specific permissions to the teacher role
        $teacherRole->givePermissionTo([
            'penilaian-list',
            'penilaian-edit',
            'hasil-vikor-list',
            'hasil-vikor-calculate',
            'laporan-view',
            'nilai-edit',
            'rekomendasi-buat',
            'approval-list',
            'approval-approve',
            'approval-reject',
        ]);

        // Assign specific permissions to the student role
        $studentRole->givePermissionTo([
            'penilaian-list',
            'penilaian-create',
            'penilaian-edit',
            'hasil-vikor-list',
        ]);

        // Assign roles to existing users
        // Ensure these users exist before assigning roles.
        // It's generally better to assign roles in UserSeeder if users are created there.
        // However, if you run PermissionsSeeder separately or after UserSeeder,
        // this block ensures roles are assigned.
        $admin = \App\Models\User::where('email', 'robby.admin@vikor.com')->first();
        if ($admin) {
            $admin->assignRole('admin');
        }

        $teachers = \App\Models\User::whereHas('roles', function($query) {
            $query->where('name', 'guru');
        }, '=', 0)->where('kelas', 'Guru')->get(); // Only assign if they don't already have the 'guru' role
        foreach ($teachers as $teacher) {
            $teacher->assignRole('guru');
        }

        $students = \App\Models\User::whereHas('roles', function($query) {
            $query->where('name', 'siswa');
        }, '=', 0)->where('kelas', '!=', 'Guru')
                                   ->where('email', '!=', 'robby.admin@vikor.com') // Corrected admin email
                                   ->get(); // Only assign if they don't already have the 'siswa' role
        foreach ($students as $student) {
            $student->assignRole('siswa');
        }
    }
}
