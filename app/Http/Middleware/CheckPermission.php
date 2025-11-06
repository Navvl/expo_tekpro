<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Permission;

class CheckPermission
{
    public function handle(Request $request, Closure $next, $permission)
    {
        $userLevel = session()->get('level');
        if (!Permission::hasAccess($userLevel, $permission)) {
            abort(403, 'Unauthorized action.');
        }
        return $next($request);
    }
}
