<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presensi extends Model
{
    use HasFactory;

    protected $table = 'presensi';
    protected $primaryKey = 'id_presensi';

    protected $fillable = [
        'id_jam',
        'jam_pelajaran', // Pastikan kolom FK ini ada di database
        'tanggal',
        'waktu_dibuka',
        'waktu_ditutup',
        'status_sesi',
        'created_by',
    ];


    // Relasi ke Detail
    public function details()
    {
        return $this->hasMany(DetailPresensi::class, 'id_presensi', 'id_presensi');
    }
    public function getStatusAsliAttribute()
{
    $sekarang = \Carbon\Carbon::now(); // Sekarang sudah otomatis WIB jika .env benar

    // Ambil tanggal dan jam secara terpisah agar tidak dobel
    $tgl = \Carbon\Carbon::parse($this->tanggal)->format('Y-m-d');
    $jamTutup = \Carbon\Carbon::parse($this->waktu_ditutup)->format('H:i:s');

    // Gabungkan dengan format yang pasti
    $waktuTutupLengkap = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $tgl . ' ' . $jamTutup);

    if ($this->status_sesi === 'dibuka' && $sekarang->greaterThan($waktuTutupLengkap)) {
        return 'ditutup';
    }

    return $this->status_sesi;
}
public function jamPelajaran()
{
    return $this->belongsTo(JamPelajaran::class, 'id_jam', 'id_jam');
}
}