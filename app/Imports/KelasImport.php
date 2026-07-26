<?php

namespace App\Imports;

use App\Models\Kelas;
use App\Models\Guru;
use App\Models\ProgramKeahlian;
use App\Models\TahunAjaran;
use App\Models\Semester;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class KelasImport implements ToModel, WithStartRow
{
    public function model(array $row)
    {
        // 1. Validasi: Jika Nama Kelas kosong, lewati
        if (!isset($row[0]) || empty($row[0])) {
            return null;
        }

        // 2. OTOMATISASI: Cari ID Guru berdasarkan nama (Kolom C)
        $guru = Guru::whereHas('user', function($query) use ($row) {
            $query->where('nama', 'LIKE', '%' . $row[2] . '%');
        })->first();

        // 3. OTOMATISASI: Cari ID Jurusan berdasarkan nama (Kolom D)
        $jurusan = ProgramKeahlian::where('nama_program', 'LIKE', '%' . $row[3] . '%')->first();

        // Jika data relasi tidak ditemukan di database, lewati baris ini
        if (!$guru || !$jurusan) {
            return null; 
        }

        // 4. Ambil data tahun & semester aktif
        $ta = TahunAjaran::where('status', 'aktif')->first();
        $sem = Semester::first();

        return new Kelas([
            'nama_kelas'          => ucwords(strtolower($row[0])), // Format: Budi Santoso
            'tingkat'             => strtoupper($row[1]),         // Format: X, XI, XII
            'id_guru'             => $guru->id_guru,              // ID otomatis didapat
            'id_program_keahlian' => $jurusan->id_program_keahlian, // ID otomatis didapat
            'status'              => 'aktif',
            'id_tahun_ajaran'     => $ta->id_tahun_ajaran ?? 1,
            'id_semester'         => $sem->id_semester ?? 1,
        ]);
    }

    public function startRow(): int
    {
        return 2; // Lewati baris header
    }
}