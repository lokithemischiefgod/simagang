<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
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

    if (!in_array($role, ['admin', 'superadmin'])) {
        abort(403, 'Anda tidak memiliki akses ke halaman ini.');
    }

    return $next($request);
}

}
