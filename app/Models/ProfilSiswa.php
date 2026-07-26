<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProfilSiswa extends Model
{
    protected $table = 'profil_siswa';
    protected $primaryKey = 'id_profil_siswa';
    
    // Karena di migrasi kamu ada $table->timestamps(), maka ini harus true
    public $timestamps = true; 

    protected $fillable = [
        'id_siswa',
        'id_program_keahlian',
        'konsentrasi_keahlian',
        'nik',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat_siswa', // Tambahkan ini
        'agama',
        'no_hp',
        'nama_ayah',
        'pekerjaan_ayah',
        'nama_ibu',
        'pekerjaan_ibu',
        'status_akun',
        'created_at',
        'update_at'
    ];

    /**
     * Relasi ke tabel User untuk mengambil Nama Siswa
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');

    }
    public function programKeahlian()
{
    // Ganti 'id_program_keahlian' dengan nama kolom di tabel profil_siswas
    // Ganti 'id' dengan primary key di tabel program_keahlian
    return $this->belongsTo(ProgramKeahlian::class, 'id_program_keahlian', 'id_program_keahlian');
}}
