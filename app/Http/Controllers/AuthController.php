<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Menampilkan Halaman Login
     */
    public function showLogin()
    {
        $n1 = rand(1, 9);
        $n2 = rand(1, 9);
        session(['captcha_result' => $n1 + $n2]);

        return view('auth.login', compact('n1', 'n2'));
    }

    /**
     * Proses Login
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'captcha' => 'required|numeric'
        ]);

        // 1. Cek CAPTCHA
        if ($request->captcha != session('captcha_result')) {
            return back()->withErrors(['captcha' => 'Jawaban keamanan salah.'])->withInput();
        }

        // 2. Cek Kredensial
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials, $request->has('remember'))) {
            $request->session()->regenerate();
            
            $user = Auth::user();

            // 3. Pengalihan berdasarkan Role
            if ($user->role == 'admin') {
                return redirect()->intended('/admin/dashboard');
            } elseif ($user->role == 'surveyor') {
                return redirect()->intended('/surveyor/dashboard');
            } elseif ($user->role == 'kabid') {
                return redirect()->intended('/kabid/dashboard');
            }

            return redirect()->intended('/');
        }

        return back()->withErrors([
            'email' => 'Email atau password tidak terdaftar di sistem kami.',
        ])->withInput();
    }

    /**
     * Menampilkan Halaman Registrasi
     */
    public function showRegister()
    {
        $n1 = rand(1, 9);
        $n2 = rand(1, 9);
        session(['captcha_register' => $n1 + $n2]);

        return view('auth.register', compact('n1', 'n2'));
    }

    /**
     * Proses Registrasi Akun Baru
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'role' => 'required|in:surveyor,kabid',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($request->captcha != session('captcha_register')) {
            return back()->withErrors(['captcha' => 'Jawaban keamanan salah.'])->withInput();
        }

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'password' => Hash::make($request->password),
        ]);

        return redirect('/login')->with('success', 'Akun berhasil dibuat! Silakan masuk.');
    }

    /**
     * Menampilkan Halaman Lupa Password
     */
    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    /**
     * Proses Keluar (Logout)
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/login');
    }
}