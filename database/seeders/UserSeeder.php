<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema; // Import Schema facade
use Spatie\Permission\Models\Role; // Import Role model
use App\Models\User; // Import User model
use App\Models\Alternatif; // Import Alternatif model

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Disable foreign key checks before truncating tables
        Schema::disableForeignKeyConstraints();

        // Clear existing data from related tables to prevent duplicates on re-seeding
        // Order matters: truncate child tables before parent tables
        DB::table('model_has_roles')->truncate();
        DB::table('model_has_permissions')->truncate();
        DB::table('alternatifs')->truncate(); // Clear alternativs as well if they are dependent
        DB::table('users')->truncate();
        DB::table('role_has_permissions')->truncate();
        DB::table('roles')->truncate();
        DB::table('permissions')->truncate();
        

        // Enable foreign key checks after truncating
        Schema::enableForeignKeyConstraints();

        // Create roles
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $guruRole = Role::firstOrCreate(['name' => 'guru']);
        $siswaRole = Role::firstOrCreate(['name' => 'siswa']);

        $students = [
            ['Ahmad Fauzan', 'ahmad.fauzan@example.com', 'S1A', 'XII', 'Teknik Komputer dan Jaringan', 'Jl. Siswa No. 1', '2024/2025', 'Ganjil'],
            ['Budi Santoso', 'budi.santoso@example.com', 'S1B', 'XII', 'Teknik Komputer dan Jaringan', 'Jl. Siswa No. 2', '2024/2025', 'Ganjil'],
            ['Citra Dewi', 'citra.dewi@example.com', 'S1C', 'XI', 'Teknik Komputer dan Jaringan', 'Jl. Siswa No. 3', '2024/2025', 'Ganjil'],
            ['Dian Purnama', 'dian.purnama@example.com', 'S1D', 'XI', 'Teknik Komputer dan Jaringan', 'Jl. Siswa No. 4', '2024/2025', 'Ganjil'],
            ['Eka Ramadhan', 'eka.ramadhan@example.com', 'S1E', 'X', 'Teknik Komputer dan Jaringan', 'Jl. Siswa No. 5', '2024/2025', 'Ganjil'],
            ['Farah Aulia', 'farah.aulia@example.com', 'S1F', 'X', 'Teknik Komputer dan Jaringan', 'Jl. Siswa No. 6', '2024/2025', 'Ganjil'],
            ['Gilang Saputra', 'gilang.saputra@example.com', 'S1G', 'XII', 'Multimedia', 'Jl. Siswa No. 7', '2024/2025', 'Ganjil'],
            ['Hana Salsabila', 'hana.salsabila@example.com', 'S1H', 'XII', 'Multimedia', 'Jl. Siswa No. 8', '2024/2025', 'Ganjil'],
            ['Indra Wijaya', 'indra.wijaya@example.com', 'S1I', 'XI', 'Multimedia', 'Jl. Siswa No. 9', '2024/2025', 'Ganjil'],
            ['Joko Prasetyo', 'joko.prasetyo@example.com', 'S1J', 'XI', 'Multimedia', 'Jl. Siswa No. 10', '2024/2025', 'Ganjil'],
            ['Kurniawan Hidayat', 'kurniawan.hidayat@example.com', 'S1K', 'X', 'Multimedia', 'Jl. Siswa No. 11', '2024/2025', 'Ganjil'],
            ['Lestari Dewi', 'lestari.dewi@example.com', 'S1L', 'X', 'Multimedia', 'Jl. Siswa No. 12', '2024/2025', 'Ganjil'],
            ['Mahmud Risky', 'mahmud.risky@example.com', 'S1M', 'XII', 'Akuntansi', 'Jl. Siswa No. 13', '2024/2025', 'Ganjil'],
            ['Nadya Amelia', 'nadya.amelia@example.com', 'S1N', 'XII', 'Akuntansi', 'Jl. Siswa No. 14', '2024/2025', 'Ganjil'],
            ['Omar Zaki', 'omar.zaki@example.com', 'S1O', 'XI', 'Akuntansi', 'Jl. Siswa No. 15', '2024/2025', 'Ganjil'],
            ['Putri Ayu', 'putri.ayu@example.com', 'S1P', 'XI', 'Akuntansi', 'Jl. Siswa No. 16', '2024/2025', 'Ganjil'],
            ['Qori Rahma', 'qori.rahma@example.com', 'S1Q', 'X', 'Akuntansi', 'Jl. Siswa No. 17', '2024/2025', 'Ganjil'],
            ['Rizky Fadilah', 'rizky.fadilah@example.com', 'S1R', 'X', 'Akuntansi', 'Jl. Siswa No. 18', '2024/2025', 'Ganjil'],
            ['Siti Nurhaliza', 'siti.nurhaliza@example.com', 'S1S', 'XII', 'Perkantoran', 'Jl. Siswa No. 19', '2024/2025', 'Ganjil'],
            ['Taufik Hidayat', 'taufik.hidayat@example.com', 'S1T', 'XII', 'Perkantoran', 'Jl. Siswa No. 20', '2024/2025', 'Ganjil'],
            ['Umar Alfaruq', 'umar.alfaruq@example.com', 'S1U', 'XI', 'Perkantoran', 'Jl. Siswa No. 21', '2024/2025', 'Ganjil'],
            ['Vina Maharani', 'vina.maharani@example.com', 'S1V', 'XI', 'Perkantoran', 'Jl. Siswa No. 22', '2024/2025', 'Ganjil'],
            ['Wahyu Pradana', 'wahyu.pradana@example.com', 'S1W', 'X', 'Perkantoran', 'Jl. Siswa No. 23', '2024/2025', 'Ganjil'],
            ['Xavier Muhammad', 'xavier.muhammad@example.com', 'S1X', 'X', 'Perkantoran', 'Jl. Siswa No. 24', '2024/2025', 'Ganjil'],
            ['Yusuf Kurnia', 'yusuf.kurnia@example.com', 'S1Y', 'XII', 'Pemasaran', 'Jl. Siswa No. 25', '2024/2025', 'Ganjil'],
            ['Zahra Melati', 'zahra.melati@example.com', 'S1Z', 'XII', 'Pemasaran', 'Jl. Siswa No. 26', '2024/2025', 'Ganjil'],
            ['Agus Saputra', 'agus.saputra@example.com', 'S2A', 'XI', 'Pemasaran', 'Jl. Siswa No. 27', '2024/2025', 'Ganjil'],
            ['Bella Safira', 'bella.safira@example.com', 'S2B', 'XI', 'Pemasaran', 'Jl. Siswa No. 28', '2024/2025', 'Ganjil'],
            ['Dedy Firmansyah', 'dedy.firmansyah@example.com', 'S2D', 'X', 'Pemasaran', 'Jl. Siswa No. 29', '2024/2025', 'Ganjil'],
            ['Erika Putri', 'erika.putri@example.com', 'S2E', 'X', 'Pemasaran', 'Jl. Siswa No. 30', '2024/2025', 'Ganjil'],
        ];

        $teachers = [
            ['Guru Matematika', 'guru.matematika@example.com', 'G001', 'Matematika', '2024/2025', 'Ganjil'],
            ['Guru Bahasa', 'guru.bahasa@example.com', 'G002', 'Bahasa Indonesia', '2024/2025', 'Ganjil'],
            ['Guru IPA', 'guru.ipa@example.com', 'G003', 'IPA', '2024/2025', 'Ganjil'],
            ['Guru IPS', 'guru.ips@example.com', 'G004', 'IPS', '2024/2025', 'Ganjil'],
            ['Guru BK', 'guru.bk@example.com', 'G005', 'Bimbingan Konseling', '2024/2025', 'Ganjil'],
        ];

        // Create admin user
        $adminUser = User::create([ // Use Eloquent create
            'name' => 'Admin',
            'email' => 'robby.admin@vikor.com',
            'password' => Hash::make('password'),
            'status' => 'active',
            'nis' => 'ADM001',
            'kelas' => 'Admin',
            'jurusan' => 'Administrator',
            'alamat' => 'Jl. Admin No. 1',
            'tahun_ajaran' => '2024/2025',
            'semester' => 'Ganjil',
            'email_verified_at' => now(),
            'approved_by' => null,
            'approved_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $adminUser->assignRole($adminRole);


        // Create teacher users
        foreach ($teachers as $teacher) {
            $user = User::create([ // Use Eloquent create
                'name' => $teacher[0],
                'email' => $teacher[1],
                'password' => Hash::make('password'),
                'status' => 'active',
                'nis' => $teacher[2],
                'kelas' => 'Guru',
                'jurusan' => $teacher[3],
                'alamat' => 'Jl. Guru No. ' . substr($teacher[2], 1),
                'tahun_ajaran' => $teacher[4],
                'semester' => $teacher[5],
                'email_verified_at' => now(),
                'approved_by' => $adminUser->id,
                'approved_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $user->assignRole($guruRole);
        }

        // Create student users
        foreach ($students as $index => $student) {
            $user = User::create([ // Use Eloquent create
                'name' => $student[0],
                'email' => $student[1],
                'password' => Hash::make('password'),
                'status' => 'active',
                'nis' => $student[2],
                'kelas' => $student[3],
                'jurusan' => $student[4],
                'alamat' => $student[5],
                'tahun_ajaran' => $student[6],
                'semester' => $student[7],
                'email_verified_at' => now(),
                'approved_by' => $adminUser->id,
                'approved_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $user->assignRole($siswaRole);

            // Create Alternatif for each student
            Alternatif::create([ // Use Eloquent create
                'user_id' => $user->id,
                'alternatif_code' => 'ALT-' . str_pad($user->id, 4, '0', STR_PAD_LEFT),
                'alternatif_name' => $user->name,
                'tahun_ajaran' => $user->tahun_ajaran,
                'semester' => $user->semester,
                'status_perhitungan' => 'pending',
            ]);
        }
    }
}
