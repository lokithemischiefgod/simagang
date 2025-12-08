<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
public function store(LoginRequest $request): RedirectResponse
{
    $request->authenticate();

    $request->session()->regenerate();

    $user = $request->user();
    $role = strtolower(trim((string) $user->role));

    // admin & superadmin → ke halaman admin pengajuan
    if (in_array($role, ['admin', 'superadmin'])) {
        return redirect()->route('admin.pengajuan.index');
    }

    // peserta → ke dashboard peserta
    if ($role === 'peserta') {
        return redirect()->route('peserta.dashboard');
    }

    // fallback kalau role aneh / belum di-set
    return redirect('/');
}



    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
