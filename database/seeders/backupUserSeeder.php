<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class BackupUserSeeder extends Seeder
{
    
    public function run()
    {
        $students = [
            ['Ahmad Fauzan', 'ahmad.fauzan@example.com', 'S1A'],
            ['Budi Santoso', 'budi.santoso@example.com', 'S1B'],
            ['Citra Dewi', 'citra.dewi@example.com', 'S1C'],
            ['Dian Purnama', 'dian.purnama@example.com', 'S1D'],
            ['Eka Ramadhan', 'eka.ramadhan@example.com', 'S1E'],
            ['Farah Aulia', 'farah.aulia@example.com', 'S1F'],
            ['Gilang Saputra', 'gilang.saputra@example.com', 'S1G'],
            ['Hana Salsabila', 'hana.salsabila@example.com', 'S1H'],
            ['Indra Wijaya', 'indra.wijaya@example.com', 'S1I'],
            ['Joko Prasetyo', 'joko.prasetyo@example.com', 'S1J'],
            ['Kurniawan Hidayat', 'kurniawan.hidayat@example.com', 'S1K'],
            ['Lestari Dewi', 'lestari.dewi@example.com', 'S1L'],
            ['Mahmud Risky', 'mahmud.risky@example.com', 'S1M'],
            ['Nadya Amelia', 'nadya.amelia@example.com', 'S1N'],
            ['Omar Zaki', 'omar.zaki@example.com', 'S1O'],
            ['Putri Ayu', 'putri.ayu@example.com', 'S1P'],
            ['Qori Rahma', 'qori.rahma@example.com', 'S1Q'],
            ['Rizky Fadilah', 'rizky.fadilah@example.com', 'S1R'],
            ['Siti Nurhaliza', 'siti.nurhaliza@example.com', 'S1S'],
            ['Taufik Hidayat', 'taufik.hidayat@example.com', 'S1T'],
            ['Umar Alfaruq', 'umar.alfaruq@example.com', 'S1U'],
            ['Vina Maharani', 'vina.maharani@example.com', 'S1V'],
            ['Wahyu Pradana', 'wahyu.pradana@example.com', 'S1W'],
            ['Xavier Muhammad', 'xavier.muhammad@example.com', 'S1X'],
            ['Yusuf Kurnia', 'yusuf.kurnia@example.com', 'S1Y'],
            ['Zahra Melati', 'zahra.melati@example.com', 'S1Z'],
            ['Agus Saputra', 'agus.saputra@example.com', 'S2A'],
            ['Bella Safira', 'bella.safira@example.com', 'S2B'],
            ['Dedy Firmansyah', 'dedy.firmansyah@example.com', 'S2D'],
            ['Erika Putri', 'erika.putri@example.com', 'S2E'],
        ];

        $teachers = [
            ['Guru Matematika', 'guru.matematika@example.com', 'G001', 'Matematika'],
            ['Guru Bahasa', 'guru.bahasa@example.com', 'G002', 'Bahasa Indonesia'],
            ['Guru IPA', 'guru.ipa@example.com', 'G003', 'IPA'],
            ['Guru IPS', 'guru.ips@example.com', 'G004', 'IPS'],
            ['Guru BK', 'guru.bk@example.com', 'G005', 'Bimbingan Konseling'],
        ];

        // Create admin user
        DB::table('users')->insert([
            'name' => 'Admin',
            'email' => 'robby.admin@vikor.com',
            'password' => Hash::make('password'),
            'status' => 'active',
            'nis' => 'ADM001',
            'kelas' => 'Admin',
            'jurusan' => 'Administrator',
            'alamat' => 'Jl. Admin No. 1',
            'email_verified_at' => now(),
            'approved_by' => 1,
            'approved_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create teacher users
        foreach ($teachers as $teacher) {
            DB::table('users')->insert([
                'name' => $teacher[0],
                'email' => $teacher[1],
                'password' => Hash::make('password'),
                'status' => 'active',
                'nis' => $teacher[2],
                'kelas' => 'Guru',
                'jurusan' => $teacher[3],
                'alamat' => 'Jl. Guru No. ' . substr($teacher[2], 1),
                'email_verified_at' => now(),
                'approved_by' => 1,
                'approved_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Create student users
        foreach ($students as $index => $student) {
            DB::table('users')->insert([
                'name' => $student[0],
                'email' => $student[1],
                'password' => Hash::make('password'),
                'status' => 'active',
                'nis' => $student[2],
                'kelas' => 'XII',
                'jurusan' => 'Teknik Komputer dan Jaringan',
                'alamat' => 'Jl. Siswa No. ' . ($index + 1),
                'email_verified_at' => now(),
                'approved_by' => 1,
                'approved_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}