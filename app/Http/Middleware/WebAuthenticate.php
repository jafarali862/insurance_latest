<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class WebAuthenticate
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if the user is authenticated
        if (!auth()->check()) {
            // Redirect to login if not authenticated
            return redirect()->route('login');
        }

        return $next($request);
    }
}
