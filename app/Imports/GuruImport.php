<?php

namespace App\Imports;

use App\Models\Guru;
use App\Models\User;
use App\Models\ProfilGuru;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class GuruImport implements ToModel, WithChunkReading
{
    private $headerRow = null;

    public function model(array $row)
{
    $row = array_change_key_case($row, CASE_LOWER);
    $nbm = $row['nbm'] ?? null; 

    if (!$nbm) return null;

    if (Guru::where('nip', $nbm)->exists()) return null;

    // 1. Buat User dengan memberikan nilai default untuk photo
    $user = User::create([
        'nama'     => strtoupper($row['nama'] ?? 'TANPA NAMA'),
        'username' => (string)$nbm,
        'email'    => $nbm . '@guru.id',
        'password' => Hash::make((string)$nbm),
        'role'     => 'guru',
        'photo'    => 'default.png', // Tambahkan ini
    ]);

    // 2. Buat Guru dengan memastikan jenis_kelamin hanya 1 karakter
    $jk = strtoupper($row['jenis kelamin'] ?? 'L');
    
    $guru = Guru::create([
        'id_user'       => $user->id_user ?? $user->id,
        'nip'           => (string)$nbm,
        'jenis_kelamin' => substr($jk, 0, 1), // Memotong agar hanya 1 huruf (L/P)
        'status'        => 'aktif',
        'foto'          => 'default.png', // Tambahkan ini
    ]);

    return new ProfilGuru([
        'id_guru'     => $guru->id_guru,
        'mapel'       => '-',
        'status_akun' => 'aktif',
    ]);
}

    public function chunkSize(): int
    {
        return 1000;
    }
}