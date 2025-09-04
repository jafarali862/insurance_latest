<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        return $request->expectsJson() ? null : route('login');
    }

    /**
     * Handle an unauthenticated user.
     */
    protected function unauthenticated($request, array $guards)
    {
        // If the request is not an API request, redirect to login
        if (!$request->expectsJson()) {
            return redirect()->route('login');
        }

        // For API requests, respond with an unauthorized status
        return response()->json([
            'message' => 'Token has expired or is invalid.'
        ], 300); // 401 for unauthorized
    }
}
