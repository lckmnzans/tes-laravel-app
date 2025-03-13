<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        $users = User::orderBy('created_at', 'desc')->get();
        return view('auth.register', compact('users'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
{
    $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
        'password' => ['required', 'confirmed', 'min:6'],
        //'password' => ['required', 'confirmed', Rules\Password::defaults()],
        'role' => ['required', 'in:manager,exim,ppic,gudang,purchasing,operator'],
    ]);
    
    // Buat user baru
    User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role' => $request->role,
    ]);
    
    // Kembali ke halaman form dengan pesan sukses
    return redirect()->route('auth.register')->with('success', 'User berhasil ditambahkan.');
}

}
