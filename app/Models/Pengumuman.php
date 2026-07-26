<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pengumuman extends Model
{
    use HasFactory;

    // Tentukan nama tabel secara eksplisit jika tidak menggunakan plural 'pengumumans'
    protected $table = 'pengumuman';

    // Tentukan Primary Key karena kamu menggunakan 'id_pengumuman' (bukan default 'id')
    protected $primaryKey = 'id_pengumuman';

    /**
     * Kolom yang dapat diisi (Mass Assignable)
     * Disesuaikan persis dengan gambar database kamu.
     */
    protected $fillable = [
        'judul',
        'kategori',
        'isi',
        'tanggal_dibuat',
        'tanggal_tayang',
        'target',
        'id_kelas',
        'file_lampiran',
        'status',
        'id_tahun_ajaran',
    ];

    /**
     * Casting tipe data (Opsional tapi disarankan untuk Date)
     */
    protected $casts = [
        'tanggal_dibuat' => 'date',
        'tanggal_tayang' => 'date',
    ];

    // --- RELASI ---

    /**
     * Relasi ke model TahunAjaran (Pastikan model TahunAjaran sudah ada)
     */
    public function tahunAjaran(): BelongsTo
    {
        return $this->belongsTo(TahunAjaran::class, 'id_tahun_ajaran');
    }

    /**
     * Relasi ke model Kelas (Hanya jika pengumuman ditargetkan ke kelas tertentu)
     */
    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class, 'id_kelas');
    }
}