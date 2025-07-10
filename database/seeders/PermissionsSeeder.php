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
            'edit-own-criteria',  // Penting untuk kebijakan 'update' dan 'edit' kriteria milik sendiri
            'delete-own-criteria',
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
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // --- INI ADALAH BAGIAN KRUSIAL YANG HARUS MENGGUNAKAN firstOrCreate ---
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $teacherRole = Role::firstOrCreate(['name' => 'guru', 'guard_name' => 'web']);
        $studentRole = Role::firstOrCreate(['name' => 'siswa', 'guard_name' => 'web']);

        $adminRole->givePermissionTo(Permission::all());

        // Assign specific permissions to the teacher role
        $teacherRole->givePermissionTo([
            'criteria-list',
            'criteria-create',
            'criteria-edit',
            'criteria-delete',
            'edit-own-criteria',
            'delete-own-criteria',
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

        $studentRole->givePermissionTo([
            'penilaian-list',
            'penilaian-create',
            'penilaian-edit',
            'hasil-vikor-list',
        ]);

        $admin = \App\Models\User::where('email', 'robby.admin@vikor.com')->first();
        if ($admin) {
            $admin->assignRole('admin');
        }

        $teachers = \App\Models\User::whereHas('roles', function($query) {
            $query->where('name', 'guru');
        }, '=', 0)->where('kelas', 'Guru')->get();
        foreach ($teachers as $teacher) {
            $teacher->assignRole('guru');
        }

        $students = \App\Models\User::whereHas('roles', function($query) {
            $query->where('name', 'siswa');
        }, '=', 0)->where('kelas', '!=', 'Guru')
                                        ->where('email', '!=', 'robby.admin@vikor.com')
                                        ->get();
        foreach ($students as $student) {
            $student->assignRole('siswa');
        }
    }
}