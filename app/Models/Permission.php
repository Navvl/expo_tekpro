<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    protected $fillable = ['user_level', 'menu', 'has_access'];

    public static function hasAccess($userLevel, $menu)
    {
        $permission = self::where('user_level', $userLevel)
            ->where('menu', $menu)
            ->first();

        return $permission ? $permission->has_access : false;
    }
}
