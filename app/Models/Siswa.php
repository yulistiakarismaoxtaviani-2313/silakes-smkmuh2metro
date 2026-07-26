<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Siswa extends Model
{
    protected $table = 'siswa';
    protected $primaryKey = 'id_siswa';
    
    public $timestamps = true; 

    protected $fillable = [
        'id_user',
        'nis',
        'jenis_kelamin',
        'status',
        'id_kelas',
        'foto',
        'id_tahun_ajaran'
    ];

    /**
     * Relasi ke tabel User (mengambil nama)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    /**
     * RELASI INI YANG KURANG: Ke tabel Kelas
     */
    public function kelas(): BelongsTo
    {
        // Pastikan foreign key-nya 'id_kelas'
        return $this->belongsTo(Kelas::class, 'id_kelas', 'id_kelas');
    }

    public function profil(): HasOne
    {
        return $this->hasOne(ProfilSiswa::class, 'id_siswa', 'id_siswa');
    }
    // app/Models/Siswa.php
public function tahunAjaran()
{
    return $this->belongsTo(TahunAjaran::class, 'id_tahun_ajaran', 'id_tahun_ajaran');
}
public function detail_presensi()
{
    return $this->hasMany(DetailPresensi::class, 'id_siswa', 'id_siswa');
}

}