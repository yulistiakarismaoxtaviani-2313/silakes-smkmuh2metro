<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TahunAjaran extends Model
{
    use HasFactory;

    // Nama tabel di database kamu
    protected $table = 'tahun_ajaran';

    // Primary key bukan 'id'
    protected $primaryKey = 'id_tahun_ajaran';

    protected $fillable = [
        'tahun_ajaran',
        'status',
    ];

    /**
     * Relasi ke Pengumuman
     */
    public function pengumuman(): HasMany
    {
        return $this->hasMany(Pengumuman::class, 'id_tahun_ajaran');
    }

    public function kelas()
{
    // Sesuaikan 'id_tahun_ajaran' dengan nama kolom foreign key di tabel kelas
    return $this->hasMany(Kelas::class, 'id_tahun_ajaran');
}
}