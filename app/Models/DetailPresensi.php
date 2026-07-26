<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPresensi extends Model
{
    use HasFactory;

    protected $table = 'detail_presensi';
    protected $primaryKey = 'id_detail_presensi';
    public $incrementing = true;

    protected $fillable = [
        'id_presensi',
        'id_siswa',
        'status',
        'keterangan',
        'file_bukti',
    ];

    public function presensi()
    {
        return $this->belongsTo(Presensi::class, 'id_presensi', 'id_presensi');
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'id_siswa', 'id_siswa');
    }
}