<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * Menampilkan Halaman Login
     */
    public function showLogin()
    {
        // Refresh captcha setiap kali halaman dibuka
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

        // Cek Captcha
        if ($request->captcha != session('captcha_result')) {
            return back()->withErrors(['captcha' => 'Jawaban keamanan salah.'])->withInput();
        }

        // Proses Autentikasi
        $credentials = $request->only('email', 'password');
        
        if (Auth::attempt($credentials, $request->has('remember'))) {
            $request->session()->regenerate();
            
            $user = Auth::user();

            // Redirection Berdasarkan Role (Disesuaikan dengan folder dashboard kamu)
            if ($user->role == 'admin') {
                return redirect()->intended('/admin/dashboard');
            } elseif ($user->role == 'surveyor') {
                return redirect()->intended('/surveyor/dashboard');
            } elseif ($user->role == 'kabid') {
                return redirect()->intended('/kabid/dashboard');
            }

            return redirect()->intended('/');
        }

        // Jika Gagal, beri pesan yang jelas
        return back()->withErrors([
            'email' => 'Email atau password tidak sesuai dengan data kami.',
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
            'email' => 'required|string|email|max:255|unique:users',
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
     * PROSES KIRIM LINK (Durasi 5 Menit)
     */
    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Alamat email tidak terdaftar di sistem kami.']);
        }

        $token = Str::random(60);

        // Gunakan properti primaryKey yang sudah kita atur di Model User
        $user->remember_token = $token;
        $user->updated_at = now();
        $user->save(); 

        Mail::send('auth.emails.reset', ['token' => $token, 'name' => $user->name], function($message) use($request){
            $message->to($request->email);
            $message->subject('Pemulihan Kata Sandi - GEO-SINFRA');
        });

        return back()->with('status', 'Link reset password telah dikirim! Berlaku selama 5 menit.');
    }

    /**
     * Menampilkan Form Reset Password
     */
    public function showResetPassword($token)
    {
        $user = User::where('remember_token', $token)->first();

        if (!$user) {
            return redirect()->route('password.request')->withErrors(['email' => 'Token tidak valid. Silakan minta link baru.']);
        }

        $detikLalu = now()->diffInSeconds($user->updated_at);
        $sisaWaktu = 300 - $detikLalu;

        // Pagar keamanan agar waktu tidak minus atau lebih dari 5 menit
        if ($sisaWaktu > 300) $sisaWaktu = 300;
        if ($sisaWaktu <= 0) {
            return redirect()->route('password.request')->withErrors(['email' => 'Link sudah kedaluwarsa.']);
        }

        return view('auth.reset-password', [
            'token' => $token,
            'sisaWaktu' => (int) $sisaWaktu
        ]);
    }

    /**
     * Proses Update Password Baru
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::where('email', $request->email)
                    ->where('remember_token', $request->token)
                    ->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Email atau token tidak cocok.']);
        }

        // Cek lagi durasi 5 menit (300 detik)
        if (now()->diffInSeconds($user->updated_at) > 300) {
            return redirect()->route('password.request')->withErrors(['email' => 'Waktu habis! Silakan minta link baru.']);
        }

        $user->password = Hash::make($request->password);
        $user->remember_token = null; 
        $user->save();

        return redirect('/login')->with('success', 'Sandi berhasil diperbarui. Silakan masuk.');
    }

    /**
     * Proses Logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/login');
    }
}