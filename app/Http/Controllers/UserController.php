<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function index()
    {
        $x['cashiers'] = User::where('role', 'cashier')->get();

        return view('admin.contents.employee-management.employee', $x);
    }

    public function createCashier()
    {
        return view('admin.contents.employee-management.employee-create');
    }

    public function storeCashier(Request $request)
    {
        // Validasi input
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => [
                'required',
                'string',
                'confirmed', // otomatis validasi password_confirm
                Password::min(8)           // minimal 8 karakter
                    ->letters()            // harus ada huruf
                    ->numbers()            // harus ada angka
            ],
        ], [
            'name.required' => 'Nama harus diisi',
            'name.max' => 'Nama maksimal 255 karakter',
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah digunakan',
            'password.required' => 'Password harus diisi',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
            'password.min' => 'Password minimal 8 karakter',
            'password.letters' => 'Password harus mengandung huruf',
            'password.numbers' => 'Password harus mengandung angka',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'cashier',
        ]);

        return redirect()->route('admin.employee')->with('success', 'Kasir berhasil ditambahkan');
    }

    public function editCashier($id)
    {
        $x['cashier'] = User::where('role', 'cashier')->findOrFail($id);

        return view('admin.contents.employee-management.employee-edit', $x);
    }

    public function updateCashier(Request $request, $id)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'unique:users,email,' . $id],
            'password' => ['nullable', 'min:8', 'confirmed'],
        ]);

        $user = User::where('role', 'cashier')->findOrFail($id);
        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()
            ->route('admin.employee')
            ->with('success', 'Data kasir berhasil diperbarui.');
    }

    public function destroyCashier($id)
    {
        $user = User::where('role', 'cashier')->findOrFail($id);

        if ($user->transactions()->count() > 0) {
            return redirect()
                ->route('admin.employee')
                ->withErrors(['error' => 'Kasir tidak dapat dihapus karena memiliki transaksi.']);
        }

        $user->delete();

        return redirect()
            ->route('admin.employee')
            ->with('success', 'Kasir berhasil dihapus.');
    }
}
