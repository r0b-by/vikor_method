<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Admin',
            'email' => 'robby.admin@vikor.com',
            'password' => Hash::make('password'), // ganti password sesuai kebutuhan
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
    }
}
