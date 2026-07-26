<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProgramKeahlian extends Model
{
    protected $table = 'program_keahlian';
    protected $primaryKey = 'id_program_keahlian';
    
    // Karena di migrasi kamu ada $table->timestamps(), maka ini harus true
    public $timestamps = true; 

    protected $fillable = [
        'nama_program',
        'kode_program',
        'konsentrasi_keahlian',
        'status',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'konsentrasi_keahlian' => 'array',
    ];

    /**
     * Relasi ke tabel User untuk mengambil Nama Siswa
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');

    }}