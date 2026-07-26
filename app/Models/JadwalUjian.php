<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JadwalUjian extends Model
{
    protected $table = 'jadwal_ujian';
    protected $primaryKey = 'id_jadwal_ujian';
    protected $fillable = ['id_kelas', 'id_tahun_ajaran', 'id_semester', 'id_jenis_ujian', 'judul'];

    // Relasi ke detail_ujian
    public function details() {
        return $this->hasMany(DetailUjian::class, 'id_jadwal_ujian', 'id_jadwal_ujian');
    }

   public function semester(): BelongsTo
    {
        return $this->belongsTo(Semester::class, 'id_semester', 'id_semester');
    }

    public function tahunAjaran(): BelongsTo
    {
        return $this->belongsTo(TahunAjaran::class, 'id_tahun_ajaran', 'id_tahun_ajaran');
    }

    public function jenisUjian(): BelongsTo
    {
        return $this->belongsTo(JenisUjian::class, 'id_jenis_ujian', 'id_jenis_ujian');
    }

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class, 'id_kelas', 'id_kelas');
    }

    /**
     * Relasi ke Anak (Detail Ujian)
     */
    public function detail(): HasMany
    {
        return $this->hasMany(DetailUjian::class, 'id_jadwal_ujian', 'id_jadwal_ujian');
    }
}