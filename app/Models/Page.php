<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Page extends Authenticatable
{
    use HasFactory;

    protected $table = 'page'; // Menetapkan nama tabel jika tidak sesuai dengan konvensi
    protected $primaryKey = 'id_page'; // Menetapkan primary key yang benar

    // Jika menggunakan timestamps, pastikan ini diset sesuai dengan kolom di tabel
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    // Daftar kolom yang dapat diisi massal
    protected $fillable = [
        'page_title',
        'page_field',
        'created_at',
        'updated_at',
        'id_user',
        'id_note',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function note()
    {
        return $this->belongsTo(Note::class, 'id_note', 'id_note');
    }
    
}