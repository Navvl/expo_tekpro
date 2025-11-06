<?php

if (!function_exists('hasAccess')) {
    function hasAccess($userLevel, $menu)
    {
        $permission = \App\Models\Permission::where('user_level', $userLevel)
            ->where('menu', $menu)
            ->first();

        return $permission ? $permission->has_access : false;
    }
}
