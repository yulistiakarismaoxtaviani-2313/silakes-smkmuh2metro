<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JadwalPelajaran extends Model
{
    use HasFactory;

    protected $table = 'jadwal_pelajaran'; // pastikan nama tabel sesuai
    protected $primaryKey = 'id_jadwal_pelajaran'; // pastikan PK sesuai

    // PERBAIKAN: Masukkan jenis dan kegiatan_kustom ke dalam fillable
    protected $fillable = [
        'id_kelas',
        'id_tahun_ajaran',
        'id_semester',
        'hari',
        'jam_mulai',
        'jam_selesai',
        'jenis',            // <-- WAJIB ADA
        'kegiatan_kustom',  // <-- WAJIB ADA
        'id_mapel',
        'id_guru',
    ];

    // Relasi ke Semester
    public function semester()
    {
        return $this->belongsTo(Semester::class, 'id_semester', 'id_semester');
    }

    // Relasi ke Tahun Ajaran
    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class, 'id_tahun_ajaran', 'id_tahun_ajaran');
    }

    // Relasi ke Kelas
    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'id_kelas', 'id_kelas');
    }

    // PERBAIKAN RELASI: Tambahkan withDefault agar tidak crash jika id_mapel null
    public function mapel()
    {
        return $this->belongsTo(Mapel::class, 'id_mapel', 'id_mapel')->withDefault([
            'nama_mapel' => '-'
        ]);
    }

    // PERBAIKAN RELASI: Tambahkan withDefault agar tidak crash jika id_guru null
    public function guru()
    {
        return $this->belongsTo(Guru::class, 'id_guru', 'id_guru')->withDefault();
    }
}