<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function index()
    {
        if (Auth::user()->role === 'owner') {
            $x['users'] = User::whereIn('role', ['cashier', 'admin'])->get();
            return view('owner.contents.employee-management.employee', $x);
        } elseif (Auth::user()->role === 'admin') {
            $x['cashiers'] = User::where('role', 'cashier')->get();
            return view('admin.contents.employee-management.employee', $x);
        }

        abort(403, 'Unauthorized');
    }

    public function create()
    {
        if (Auth::user()->role === 'owner') {
            return view('owner.contents.employee-management.employee-create');
        } elseif (Auth::user()->role === 'admin') {
            return view('admin.contents.employee-management.employee-create');
        }

        abort(403, 'Unauthorized');
    }

    public function store(Request $request)
    {
        if (Auth::user()->role === 'admin') {
            $role = 'cashier';
        } elseif (Auth::user()->role === 'owner') {
            $request->validate([
                'role' => ['required', 'in:admin,cashier'],
                [
                    'role.required' => 'Role harus diisi',
                    'role.in' => 'Role tidak valid',
                ],
            ]);
            $role = $request->role;
        } else {
            abort(403, 'Unauthorized');
        }

        $request->validate(
            [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'max:255', 'unique:users,email'],
                'password' => ['required', 'string', 'confirmed', Password::min(8)->letters()->numbers()],
            ],
            [
                'name.required' => 'Nama harus diisi',
                'email.required' => 'Email harus diisi',
                'email.email' => 'Format email tidak valid',
                'email.unique' => 'Email sudah digunakan',
                'password.required' => 'Password harus diisi',
                'password.confirmed' => 'Konfirmasi password tidak cocok',
                'password.min' => 'Password minimal 8 karakter',
                'password.letters' => 'Password harus mengandung huruf',
                'password.numbers' => 'Password harus mengandung angka',
            ],
        );

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $role,
        ]);

        if (Auth::user()->role === 'owner') {
            return redirect()->route('owner.employee')->with('success', 'Akun berhasil dibuat.');
        }

        return redirect()->route('admin.employee')->with('success', 'Akun berhasil dibuat.');
    }

    public function edit($id)
    {
        if (Auth::user()->role === 'owner') {
            $x['user'] = User::findOrFail($id);
            return view('owner.contents.employee-management.employee-edit', $x);
        } elseif (Auth::user()->role === 'admin') {
            $x['cashier'] = User::where('role', 'cashier')->findOrFail($id);
            return view('admin.contents.employee-management.employee-edit', $x);
        }

        abort(403, 'Unauthorized');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'unique:users,email,' . $id],
            'password' => ['nullable', 'min:8', 'confirmed'],
        ]);

        $user = User::findOrFail($id);

        if (Auth::user()->role === 'admin' && $user->role !== 'cashier') {
            abort(403, 'Unauthorized');
        }

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        if (Auth::user()->role === 'owner') {
            return redirect()->route('owner.employee')->with('success', 'Data berhasil diperbarui.');
        }

        return redirect()->route('admin.employee')->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if (Auth::user()->role === 'admin' && $user->role !== 'cashier') {
            abort(403, 'Unauthorized');
        }

        if ($user->transactions()->count() > 0) {
            return redirect()
                ->back()
                ->withErrors(['error' => 'Akun tidak dapat dihapus karena memiliki transaksi.']);
        }

        $user->delete();

        return redirect()->back()->with('success', 'Akun berhasil dihapus.');
    }
}
