<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    use HasFactory;

    protected $table = 'guru';
    
    // Sesuaikan Primary Key sesuai screenshot
    protected $primaryKey = 'id_guru'; 

    // WAJIB: Masukkan kolom yang benar-benar ada di tabel 'guru' (lihat screenshot 3)
    protected $fillable = [
        'id_user',       // Ini penting untuk relasi ke tabel users
        'nip', 
        'jenis_kelamin', 
        'status',
        'foto',
        'is_walikelas'
    ];

    // Relasi ke User (untuk ambil Nama)
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
    // TAMBAHKAN INI: Relasi ke tabel Kelas (Cek apakah dia Wali Kelas)
    public function kelas()
    {
        // 'id_guru' pertama adalah foreign key di tabel kelas
        // 'id_guru' kedua adalah primary key di tabel guru
        return $this->hasOne(Kelas::class, 'id_guru', 'id_guru');
    }
      // TAMBAHKAN INI: Relasi ke tabel Kelas (Cek apakah dia Wali Kelas)
    public function profilGuru()
    {
        // 'id_guru' pertama adalah foreign key di tabel kelas
        // 'id_guru' kedua adalah primary key di tabel guru
        return $this->hasOne(ProfilGuru::class, 'id_guru', 'id_guru');
    }
    public function waliKelas()
    {
        return $this->hasOne(Kelas::class, 'id_guru', 'id_guru');
    }
    public function mapel() // Ganti dari mapels ke mapel
{
    return $this->belongsToMany(Mapel::class, 'guru_mapel', 'id_guru', 'id_mapel');
}
}

