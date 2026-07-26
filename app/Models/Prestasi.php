<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prestasi extends Model
{
    use HasFactory;

    // Paksa Laravel menggunakan nama tabel 'prestasi'
    protected $table = 'prestasi';

    // Definisikan Primary Key karena kamu tidak menggunakan nama default 'id'
    protected $primaryKey = 'id_prestasi';

    // Daftarkan semua kolom yang bisa diisi (Mass Assignment)
    protected $fillable = [ 
        'id_siswa', 
        'nama_lomba', 
        'cabang_lomba', 
        'penyelenggara_lomba', 
        'tingkat', 
        'kategori', 
        'peringkat', 
        'tanggal', 
        'file_bukti', 
        'status_validasi',
        'divalidasi_oleh',
        'bebas_spp',
        'keterangan'
    ];

    /**
     * Relasi ke model Siswa
     */
    public function siswa()
    {
        // Parameter kedua adalah foreign key di tabel prestasi
        // Parameter ketiga adalah owner key di tabel siswa
        return $this->belongsTo(Siswa::class, 'id_siswa', 'id_siswa');
    }
}