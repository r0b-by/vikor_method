<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'name' => 'robby',
            'email' => 'robby@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'), // password = password
            'remember_token' => Str::random(10),
        ]);
    }
}
