<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureKaryawanAktif
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();
            $karyawan = $user->karyawan;

            if ($karyawan && $karyawan->status !== 'aktif') {
                Auth::logout();

                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()
                    ->route('login')
                    ->withErrors([
                        'email' => 'Sesi berakhir karena status karyawan tidak aktif.',
                    ]);
            }
        }

        return $next($request);
    }
}