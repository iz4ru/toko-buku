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
        $credentials = $request->validate(
            [
                'email' => 'required|email',
                'password' => 'required',
            ],
            [
                'email.required' => 'Email dibutuhkan.',
                'email.email' => 'Silakan masukkan email dengan benar.',
                'password.required' => 'Password dibutuhkan.',
            ],
        );

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $role = Auth::user()->role;
            return match ($role) {
                'admin' => redirect()
                    ->route('admin.dashboard')
                    ->with('success', 'Login berhasil, selamat datang kembali ' . Auth::user()->name . ' !'),
                'cashier' => redirect()
                    ->route('cashier.dashboard')
                    ->with('success', 'Login berhasil, selamat datang kembali ' . Auth::user()->name . ' !'),
                'owner' => redirect()
                    ->route('owner.dashboard')
                    ->with('success', 'Login berhasil, selamat datang kembali ' . Auth::user()->name . ' !'),
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
