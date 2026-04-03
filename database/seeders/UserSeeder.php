<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@hospital.com'],
            [
                'name' => 'Admin Petugas',
                'username' => 'admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        User::updateOrCreate(
            ['email' => 'petugas@hospital.com'],
            [
                'name' => 'Petugas Ruangan',
                'username' => 'petugas',
                'password' => Hash::make('password'),
                'role' => 'petugas',
            ]
        );
    }
}
