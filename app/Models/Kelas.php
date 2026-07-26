<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Kelas extends Model
{
    use HasFactory;

    protected $table = 'kelas';

    // TAMBAHKAN BARIS INI (Sangat Penting!)
    protected $primaryKey = 'id_kelas'; 

    protected $fillable = ['nama_kelas',
    'id_guru',
    'id_program_keahlian', // WAJIB ADA DI SINI
    'tingkat',
    'status',
    'id_tahun_ajaran',     // WAJIB ADA DI SINI
    'id_semester',];

    // RELASI KE GURU (Wali Kelas)
    public function guru()
    {
        return $this->belongsTo(Guru::class, 'id_guru', 'id_guru');
    }
    

    // RELASI KE PROGRAM KEAHLIAN (INI YANG TADI ERROR)
    public function programKeahlian()
    {
        return $this->belongsTo(ProgramKeahlian::class, 'id_program_keahlian', 'id_program_keahlian');
    }

    // RELASI KE SISWA
    public function siswa()
    {
        return $this->hasMany(Siswa::class, 'id_kelas', 'id_kelas');
    }

    // RELASI KE TAHUN AJARAN
    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class, 'id_tahun_ajaran', 'id_tahun_ajaran');
    }

    // app/Models/Kelas.php

public function semester()
{
    // Pastikan 'id_semester' adalah nama kolom di tabel kelas
    return $this->belongsTo(Semester::class, 'id_semester', 'id_semester');
}
public function jadwalPelajaran()
{
    // Pastikan 'id_kelas' adalah nama foreign key di tabel jadwal_pelajaran
    return $this->hasMany(JadwalPelajaran::class, 'id_kelas', 'id_kelas');
}

public function jadwalUjian()
{
    // Jika Anda ingin hal yang sama untuk jadwal ujian nanti
    return $this->hasMany(JadwalUjian::class, 'id_kelas', 'id_kelas');
}
// Fungsi otomatis untuk menghapus anak (Siswa) saat induk (Kelas) dihapus
    protected static function boot()
    {
        parent::boot();

        static::deleting(function($kelas) {
            // Hapus semua siswa yang terhubung dengan kelas ini
            $kelas->siswa()->delete();
        });
    }

}