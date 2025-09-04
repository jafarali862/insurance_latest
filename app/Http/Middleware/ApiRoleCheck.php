<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ApiRoleCheck
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // Check if the user is authenticated
        if (!$request->user()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Check if the user has one of the specified roles
        if (!$request->user()->hasRole($roles)) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        return $next($request);
    }
}
