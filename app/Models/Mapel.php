<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mapel extends Model
{
    use HasFactory;

    // Karena nama tabelmu 'mapel', bukan 'mapels'
    protected $table = 'mapel';

    // Karena primary key kamu 'id_mapel', bukan 'id'
    protected $primaryKey = 'id_mapel';

    // Kolom yang boleh diisi (mass assignment)
    protected $fillable = [
        'nama_mapel',
    ];

    /**
     * Relasi ke Jadwal Pelajaran
     */
    public function jadwalPelajaran()
    {
        return $this->hasMany(JadwalPelajaran::class, 'id_mapel', 'id_mapel');
    }
    public function guru() // Ganti dari gurus ke guru
{
    return $this->belongsToMany(Guru::class, 'guru_mapel', 'id_mapel', 'id_guru');
}
}