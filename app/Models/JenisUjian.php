<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisUjian extends Model
{
    use HasFactory;

    // Nama tabel di database
    protected $table = 'jenis_ujian';

    // Nama primary key-nya (karena kamu pakai id_jenis_ujian, bukan id)
    protected $primaryKey = 'id_jenis_ujian';

    // Kolom yang boleh diisi
    protected $fillable = [
        'nama_ujian',
    ];

    /**
     * Relasi ke Jadwal Ujian
     * Satu jenis ujian (misal: UAS) bisa punya banyak jadwal
     */
    public function jadwalUjian()
    {
        return $this->hasMany(JadwalUjian::class, 'id_jenis_ujian', 'id_jenis_ujian');
    }
}