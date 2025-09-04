<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckToken
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::guard('api')->check()) {
            return $next($request);
        }

        return response()->json(['message' => 'Token expired or invalid'], 300);
    }
}
