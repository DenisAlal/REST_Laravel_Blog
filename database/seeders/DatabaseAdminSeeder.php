<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DatabaseAdminSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'name' => "admin",
            'email' => "admin@mail.ru",
            'email_verified_at' => now(),
            'password' => 'admin',
            'remember_token' => Str::random(10),
            'role' => 'admin ',
        ]);
    }
}
