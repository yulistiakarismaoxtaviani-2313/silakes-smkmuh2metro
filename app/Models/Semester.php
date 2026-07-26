<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Semester extends Model
{
    // Karena nama tabel kamu 'semester' (bukan semesters), definisikan manual
    protected $table = 'semester';

    // Karena primary key kamu 'id_semester' (bukan id), definisikan manual
    protected $primaryKey = 'id_semester';

    // Jika id_semester adalah auto-increment
    public $incrementing = true;

    // Masukkan kolom yang bisa diisi
    protected $fillable = [
        'id_tahun_ajaran',
        'nama_semester',
        'status'
    ];
}