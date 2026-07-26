<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailUjian extends Model
{
    protected $table = 'detail_ujian';
    protected $primaryKey = 'id_detail_ujian'; // Pastikan ini sesuai dengan di database

    protected $fillable = [
        'id_jadwal_ujian', 
        'tanggal', 
        'jam_mulai', 
        'jam_selesai', 
        'id_mapel', 
        'id_pengawas', 
        'ruangan',
        'hari'
    ];

    /**
     * Relasi ke Mata Pelajaran
     */
    public function mapel(): BelongsTo
    {
        return $this->belongsTo(Mapel::class, 'id_mapel', 'id_mapel');
    }

    /**
     * Relasi ke Guru Pengawas
     */
    public function pengawas(): BelongsTo
    {
        return $this->belongsTo(Guru::class, 'id_pengawas', 'id_guru');
    }

    /**
     * Relasi balik ke Header (JadwalUjian)
     */
    public function jadwalUjian(): BelongsTo
    {
        return $this->belongsTo(JadwalUjian::class, 'id_jadwal_ujian', 'id_jadwal_ujian');
    }
}