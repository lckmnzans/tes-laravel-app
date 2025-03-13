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
        $request->authenticate();
        $request->session()->regenerate();

        // Mapping role ke URL dashboard
        $roleToDashboard = [
            'manager' => 'manager/dashboard',
            'exim' => 'exim/dashboard',
            'ppic' => 'ppic/dashboard',
            'gudang' => 'gudang/dashboard',
            'purchasing' => 'purchasing/dashboard',
            'operator' => 'ppic/production/index'
        ];

        // Dapatkan URL berdasarkan role user
        $role = $request->user()->role;
        if (!array_key_exists($role, $roleToDashboard)) {
            return redirect('/')->with('error', 'Role tidak dikenali.');
        }
        $url = $roleToDashboard[$role] ?? '/'; // Redirect ke '/' jika role tidak dikenali

        return redirect()->intended($url);
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
