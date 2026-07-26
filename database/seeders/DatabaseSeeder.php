<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash; // Penting untuk enkripsi password

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Membuat Akun Admin Utama
        User::create([
            'nama'     => 'Admin Kesiswaan',
            'username' => 'admin',
            'email'    => 'admin@sekolah.com',
            'password' => Hash::make('admin123'), // Ganti sesuai keinginanmu
            'role'     => 'admin',
        ]);
    }
}