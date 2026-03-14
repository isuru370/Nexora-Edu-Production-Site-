<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;

class CheckPermission
{
    public function handle($request, Closure $next)
    {
        // Check if user is authenticated
        if (!auth()->check()) {
            abort(403, 'Unauthorized');
        }

        $user = auth()->user();

        // Super Admin Bypass
        if ($user->user_type == 1) {
            return $next($request);
        }

        $route = $request->route();
        $routeName = $route ? $route->getName() : null;

        // If route has no name, skip permission check
        if (!$routeName) {
            return $next($request);
        }

        // Check if user_type has permission for this route
        $hasPermission = DB::table('permissions')
            ->join('pages', 'permissions.page_id', '=', 'pages.id')
            ->where('permissions.user_type_id', $user->user_type)
            ->where('pages.route_name', $routeName)
            ->exists();

        if (!$hasPermission) {
            abort(403, 'Access Denied');
        }

        return $next($request);
    }
}