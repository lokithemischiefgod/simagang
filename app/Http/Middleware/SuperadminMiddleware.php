<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SuperadminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
{
    if (!auth()->check()) {
        return redirect()->route('login');
    }

    $role = strtolower(trim((string) auth()->user()->role));

    if ($role !== 'superadmin') {
        abort(403, 'Halaman ini hanya untuk superadmin.');
    }

    return $next($request);
}

}
