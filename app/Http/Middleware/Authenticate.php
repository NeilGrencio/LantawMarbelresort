<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Authenticate
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, $guard = null)
    {
        // For API, we simply allow the request to continue.
        // Later you can add JWT, token, or session-based authentication here.
        return $next($request);
    }
}
