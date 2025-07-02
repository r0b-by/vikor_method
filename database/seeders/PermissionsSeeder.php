<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionsSeeder extends Seeder
{
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

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
            Permission::create(['name' => $permission]);
        }

        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo(Permission::all());

        $teacherRole = Role::create(['name' => 'guru']);
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

        $studentRole = Role::create(['name' => 'siswa']);
        $studentRole->givePermissionTo([
            'penilaian-list',
            'penilaian-create',
            'penilaian-edit',
            'hasil-vikor-list',
        ]);

        $admin = \App\Models\User::where('email', 'robby.admin@vikor.com')->first();
        $admin->assignRole('admin');

        $teachers = \App\Models\User::where('kelas', 'Guru')->get();
        foreach ($teachers as $teacher) {
            $teacher->assignRole('guru');
        }

        $students = \App\Models\User::where('kelas', '!=', 'Guru')
                      ->where('email', '!=', 'admin@example.com')
                      ->get();
        foreach ($students as $student) {
            $student->assignRole('siswa');
        }
    }
}