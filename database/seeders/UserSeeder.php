<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Data Admin
        User::create([
            'nama'     => 'Administrator',
            'username' => 'admin',
            'email'    => 'admin@mail.com',
            'password' => Hash::make('password123'),
            'role'     => 'admin',
        ]);

        // Data Guru
        User::create([
            'nama'     => 'Pak Guru',
            'username' => 'guru123',
            'email'    => 'guru@mail.com',
            'password' => Hash::make('password123'),
            'role'     => 'guru',
        ]);

        // Data Siswa
        User::create([
            'nama'     => 'Siswa Teladan',
            'username' => 'siswa123',
            'email'    => 'siswa@mail.com',
            'password' => Hash::make('password123'),
            'role'     => 'siswa',
        ]);
        
        // Data Wali Kelas
        User::create([
            'nama'     => 'Ibu Wali',
            'username' => 'walikelas123',
            'email'    => 'walikelas@mail.com',
            'password' => Hash::make('password123'),
            'role'     => 'walikelas',
        ]);
    }
}