<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\HistoryLog;
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

        // Catat log login
        HistoryLog::create([
            'user_id'     => Auth::id(),
            'action'      => 'login',
            'description' => 'User berhasil login',
            'ip_address'  => $request->ip(),
            'user_agent'  => $request->userAgent(),
            'status'      => 'success',
        ]);

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $userId = Auth::id();

        // Catat log logout sebelum session diinvalidate
        HistoryLog::create([
            'user_id'     => $userId,
            'action'      => 'logout',
            'description' => 'User berhasil logout',
            'ip_address'  => $request->ip(),
            'user_agent'  => $request->userAgent(),
            'status'      => 'success',
        ]);

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
