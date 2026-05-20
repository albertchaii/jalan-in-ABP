<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OperatorMiddleware
{
    /**
     * Handle an incoming request.
     * Hanya mengizinkan user dengan role 'operator'.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user() && $request->user()->role === 'operator') {
            return $next($request);
        }

        abort(403, 'Akses ditolak. Halaman ini hanya untuk Operator.');
    }
}
