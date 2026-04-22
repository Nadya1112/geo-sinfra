<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // 1. Cek apakah user sudah login?
        // 2. Cek apakah role user sama dengan role yang dibutuhkan pintu ini?
        if (!Auth::check() || Auth::user()->role !== $role) {
            // Kalau tidak cocok, tendang ke halaman login dengan pesan error
            return redirect('/login')->with('error', 'Maaf, halaman ini khusus untuk ' . $role);
        }

        // Kalau cocok, silakan lewat!
        return $next($request);
    }
}