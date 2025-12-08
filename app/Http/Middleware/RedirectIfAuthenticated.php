<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
  public function handle(Request $request, Closure $next, string|null $guard = null): Response
{
    if (Auth::guard($guard)->check()) {
        $user = Auth::user();
        $role = strtolower(trim((string) $user->role));

        if (in_array($role, ['admin', 'superadmin'])) {
            return redirect()->route('admin.pengajuan.index');
        }

        if ($role === 'peserta') {
            return redirect()->route('peserta.dashboard');
        }

        return redirect('/');
    }

    return $next($request);
}


}
