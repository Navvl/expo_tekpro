<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory, SoftDeletes;

    protected $table = 'user'; // Menetapkan nama tabel jika tidak sesuai dengan konvensi
    protected $primaryKey = 'id_user'; // Menetapkan primary key yang benar

    // Jika menggunakan timestamps, pastikan ini diset sesuai dengan kolom di tabel
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    // Daftar kolom yang dapat diisi massal
    protected $fillable = [
        'username',
        'password',
        'created_at',
        'updated_at',
        'level',
        'email',
        'id_friend',
        'foto',
    ];

    protected $hidden = [
        'password',
    ];
}
