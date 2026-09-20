<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
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
        // 1. Autentikasi user
        $request->authenticate();

        // 2. Regenerasi session untuk keamanan (mencegah session fixation)
        $request->session()->regenerate();

        // 3. ✅ PERBAIKAN: Ambil data user yang baru saja login
        $user = $request->user();

        // 4. Cek role dan redirect ke halaman yang sesuai
        if (in_array($user->role, ['admin', 'employee'])) {
            // Jika Admin atau Employee, arahkan ke dashboard khusus mereka
            return redirect()->intended(route('admin.dashboard'));
        }

        // Jika User biasa, arahkan ke dashboard user
        return redirect()->intended(route('user.dashboard'));
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
