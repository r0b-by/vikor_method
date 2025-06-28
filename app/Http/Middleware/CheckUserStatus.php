<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Pastikan pengguna sudah login
        if (Auth::check()) {
            $user = Auth::user();

            // Jika pengguna memiliki status 'pending', arahkan mereka ke halaman notifikasi
            if ($user->status === 'pending') {
                // Logout pengguna untuk memastikan mereka tidak dapat mengakses rute yang dilindungi
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                // Arahkan ke halaman pendaftaran menunggu konfirmasi
                return redirect()->route('registration.pending')->with('message', 'Akun Anda sedang menunggu konfirmasi admin.');
            }
        }

        return $next($request);
    }
}

