<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  array  ...$roles
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = Auth::user();

        // Check if user is authenticated and has the required role
        if (!$user || !in_array($user->role, $roles)) {
            // Optionally redirect to an error page or a different route
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return $next($request);
    }
}
