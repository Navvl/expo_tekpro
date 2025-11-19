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
        
        return $next($request);
    }
}
