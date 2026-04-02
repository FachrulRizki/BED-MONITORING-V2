<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@hospital.com'],
            [
                'name' => 'Admin Petugas',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        User::firstOrCreate(
            ['email' => 'petugas@hospital.com'],
            [
                'name' => 'Petugas Ruangan',
                'password' => Hash::make('password'),
                'role' => 'petugas',
            ]
        );
    }
}
