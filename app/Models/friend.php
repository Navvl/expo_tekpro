<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Friend extends Model
{
    protected $table = 'friend';
    protected $primaryKey = 'id_friend';

    protected $fillable = [
        'id_user',
        'id_user_friended'
    ];
}
