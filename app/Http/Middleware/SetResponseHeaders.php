<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetResponseHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if the request is an API request
        if ($request->is('api/*') || $request->expectsJson()) {
            // Add the Accept header for API requests
            $request->headers->set('Accept', 'application/json');
        }

        return $next($request);
    }
}
