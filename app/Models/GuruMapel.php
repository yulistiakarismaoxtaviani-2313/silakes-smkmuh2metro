<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GuruMapel extends Model
{
    // Beritahu Laravel nama tabelnya
    protected $table = 'guru_mapel';
    protected $primaryKey = 'id_guru_mapel';

    // Jika Anda tidak menggunakan kolom created_at/updated_at di tabel ini, tambahkan ini:
    public $timestamps = false;

    // Definisikan kolom yang bisa diisi (Mass Assignment)
    protected $fillable = [
        'id_guru', 
        'id_mapel'
    ];
    
}