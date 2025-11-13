<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Note extends Authenticatable
{
    use HasFactory;

    protected $table = 'note'; // Menetapkan nama tabel jika tidak sesuai dengan konvensi
    protected $primaryKey = 'id_note'; // Menetapkan primary key yang benar

    // Jika menggunakan timestamps, pastikan ini diset sesuai dengan kolom di tabel
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    // Daftar kolom yang dapat diisi massal
    protected $fillable = [
        'note_title',
        'id_page',
        'created_at',
        'updated_at',
        'id_room',
    ];

    public function page()
    {
        return $this->belongsTo(User::class, 'pages_code', 'pages_code');
    }

    public function room()
    {
        return $this->belongsTo(Room::class, 'id_room', 'id_room');
    }

}