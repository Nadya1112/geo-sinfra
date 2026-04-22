<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail; // WAJIB ADA
use Illuminate\Support\Str;          // WAJIB ADA

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
            'email' => 'required',
            'password' => 'required',
            'captcha' => 'required|numeric'
        ]);

        if ($request->captcha != session('captcha_result')) {
            return back()->withErrors(['captcha' => 'Jawaban keamanan salah.'])->withInput();
        }

        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials, $request->has('remember'))) {
            $request->session()->regenerate();
            
            $user = Auth::user();

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
            'email' => 'Email/NIP atau password tidak sesuai.',
        ])->withInput();
    }

    /**
     * Menampilkan Halaman Registrasi
     */
    public function showRegister()
    {
        return view('auth.register');
    }

    /**
     * Proses Registrasi Akun Baru
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|max:255|unique:users',
            'role' => 'required|in:surveyor,kabid',
            'password' => 'required|string|min:8|confirmed',
        ]);

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
     * PROSES KIRIM LINK (Sudah Diperbaiki Agar Mengirim Email Asli)
     */
    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Alamat email tidak terdaftar di sistem kami.']);
        }

        // Membuat token rahasia sementara
        $token = Str::random(60);

        // PERINTAH KIRIM EMAIL
        Mail::send('auth.emails.reset', ['token' => $token, 'name' => $user->name], function($message) use($request){
            $message->to($request->email);
            $message->subject('Pemulihan Kata Sandi - GEO-SINFRA');
        });

        return back()->with('status', 'Link reset password telah dikirim ke email Anda. Silakan cek kotak masuk atau folder spam.');
    }

    /**
     * Menampilkan Halaman Form Reset Password Baru
     */
    public function showResetPassword($token)
    {
        return view('auth.reset-password', ['token' => $token]);
    }

    /**
     * Proses Update Password Baru ke Database
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Email tidak terdaftar.']);
        }

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return redirect('/login')->with('success', 'Sandi berhasil diperbarui. Silakan login.');
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