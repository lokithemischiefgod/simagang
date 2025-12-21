<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForceChangePassword
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->must_change_password) {

            // Izinkan halaman profile, endpoint update password, dan logout
            if (
                $request->is('profile') ||
                $request->is('password') ||
                $request->is('logout') ||
                $request->routeIs('profile.*') ||
                $request->routeIs('password.*')
            ) {
                return $next($request);
            }

            return redirect('/profile')
                ->with('error', 'Silakan ganti password Anda terlebih dahulu.');
        }

        return $next($request);
    }
}
