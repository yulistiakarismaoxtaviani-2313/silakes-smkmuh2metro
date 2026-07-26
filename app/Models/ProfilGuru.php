<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProfilGuru extends Model
{
    protected $table = 'profil_guru';
    protected $primaryKey = 'id_profil_guru';
    
    // Karena di migrasi kamu ada $table->timestamps(), maka ini harus true
    public $timestamps = true; 

    protected $fillable = [
        'id_guru',
        'no_hp',
        'mapel',
        'status',
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
   
    }
