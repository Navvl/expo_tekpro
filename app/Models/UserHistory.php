<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class UserHistory extends Authenticatable
{


    protected $table = 'user_history'; // Menetapkan nama tabel jika tidak sesuai dengan konvensi
    protected $primaryKey = 'id_user_history'; // Menetapkan primary key yang benar

    // Jika menggunakan timestamps, pastikan ini diset sesuai dengan kolom di tabel
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    // Daftar kolom yang dapat diisi massal
    protected $fillable = [
        'id_user',
        'username',
        'password',
        'created_at',
        'updated_at',
        'level',
        'email',
    ];

    protected $hidden = [
        'password',
    ];
}
