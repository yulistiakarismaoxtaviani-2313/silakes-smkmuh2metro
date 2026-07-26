<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    // Beritahu Laravel bahwa nama kolom ID-nya adalah id_user
    protected $primaryKey = 'id_user';

    protected $fillable = [
        'nama',
        'email',
        'username',
        'password',
        'role',
        'terakhir_login',
        'photo'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function siswa()
{
    // Parameter 2: Nama foreign key di tabel siswas (id_user)
    // Parameter 3: Nama primary key di tabel users (id_user)
    return $this->hasOne(Siswa::class, 'id_user', 'id_user');
}
 public function guru()
{
    // Parameter 2: Nama foreign key di tabel siswas (id_user)
    // Parameter 3: Nama primary key di tabel users (id_user)
    return $this->hasOne(Guru::class, 'id_user', 'id_user');
}
}