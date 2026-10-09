<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (!Auth::validate($credentials)) {
            return back()->withErrors([
                'email' => 'Email atau password salah.',
            ])->onlyInput('email');
        }

        $user = \App\Models\User::where('email', $credentials['email'])
            ->with('karyawan')
            ->first();

        // Tolak login jika user memiliki relasi karyawan
        // dengan status selain aktif.
        if ($user->karyawan && $user->karyawan->status !== 'aktif') {
            return back()->withErrors([
                'email' => 'Akun tidak dapat login karena status karyawan tidak aktif.',
            ])->onlyInput('email');
        }

        Auth::login($user, $request->boolean('remember'));

        $request->session()->regenerate();

        return redirect()->route('dashboard.index');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
