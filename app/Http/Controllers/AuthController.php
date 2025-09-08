<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials))
        {
            $request->session()->regenerate();

            $role = Auth::user()->role;
            return match ($role) {
                'admin' => redirect()->route('admin.dashboard'),
                'cashier' => redirect()->route('cashier.dashboard'),
                'owner' => redirect()->route('owner.dashboard'),
                default => abort(403, 'Unauthorized Action'),
            };
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
            'password' => 'The provided password is incorrect.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Logout berhasil, sampai jumpa lagi!');
    }
}
