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

    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required',
            'password' => 'required',
            'captcha' => 'required|numeric'
        ]);

        // Cek Captcha
        if ($request->captcha != session('captcha_result')) {
            return back()->withErrors(['captcha' => 'Jawaban keamanan salah.'])->withInput();
        }

        // Cari user berdasarkan email atau nomor WA
        $loginType = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'no_hp';
        
        $user = User::where($loginType, $request->login)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            // Langsung login tanpa OTP
            Auth::login($user, $request->has('remember'));
            $request->session()->regenerate();

            if ($user->role == 'admin') return redirect()->intended('/admin/dashboard');
            if ($user->role == 'surveyor') return redirect()->intended('/surveyor/dashboard');
            if ($user->role == 'tim_teknis') return redirect()->intended('/tim-teknis/dashboard');

            return redirect()->intended('/');
        }

        // Jika Gagal
        return back()->withErrors([
            'login' => 'Email/No.WA atau password tidak sesuai.',
        ])->withInput();
    }

    /**
     * Menampilkan Halaman OTP Pendaftaran
     */
    public function showOtp()
    {
        if (!session('register_otp_user_id')) {
            return redirect()->route('login');
        }
        return view('auth.otp');
    }

    /**
     * Memverifikasi Kode OTP Pendaftaran
     */
    public function verifyRegistrationOtp(Request $request)
    {
        $request->validate(['otp_code' => 'required|numeric']);

        $userId = session('register_otp_user_id');
        if (!$userId) return redirect()->route('login');

        $user = User::find($userId);

        if (!$user || $user->otp_code !== $request->otp_code || now()->greaterThan($user->otp_expires_at)) {
            return back()->withErrors(['otp_code' => 'Kode OTP salah atau sudah kedaluwarsa.']);
        }

        // OTP Valid, Bersihkan dan Login
        $user->otp_code = null;
        $user->otp_expires_at = null;
        $user->save();

        Auth::login($user);
        session()->forget(['register_otp_user_id', 'demo_otp']);
        $request->session()->regenerate();

        if ($user->role == 'admin') return redirect()->intended('/admin/dashboard');
        if ($user->role == 'surveyor') return redirect()->intended('/surveyor/dashboard');
        if ($user->role == 'tim_teknis') return redirect()->intended('/tim-teknis/dashboard');

        return redirect()->intended('/');
    }

    /**
     * Mengirim Ulang Kode OTP Pendaftaran
     */
    public function resendRegistrationOtp(Request $request)
    {
        $userId = session('register_otp_user_id');
        if (!$userId) return redirect()->route('login');

        $user = User::find($userId);
        if ($user) {
            $otp = rand(100000, 999999);
            $user->otp_code = $otp;
            $user->otp_expires_at = now()->addMinutes(5);
            $user->save();

            if ($request->input('method') === 'call') {
                // SIMULASI PANGGILAN TELEPON (VOICE OTP)
                \Illuminate\Support\Facades\Log::info("SIMULASI: Panggilan Suara OTP ke {$user->no_hp} berisi kode {$otp}.");
                return back()->with('success', 'Kami sedang memanggil nomor Anda. Silakan angkat telepon untuk mendengar kode OTP.');
            } else {
                // PENGIRIMAN VIA WHATSAPP (FONNTE)
                $this->sendWhatsAppOtp($user->no_hp, $otp);
                \Illuminate\Support\Facades\Log::info("OTP Pendaftaran Ulang dikirim ke {$user->no_hp} via Fonnte.");
                return back()->with('success', 'Kode OTP pendaftaran baru telah dikirim via WhatsApp!');
            }
        }

        return redirect()->route('login');
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
            'no_hp' => 'required|string|max:20|unique:users',
            'role' => 'required|in:surveyor,tim_teknis',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'role' => $request->role,
            'password' => Hash::make($request->password),
        ]);

        $otp = rand(100000, 999999);
        $user->otp_code = $otp;
        $user->otp_expires_at = now()->addMinutes(5);
        $user->save();

        session([
            'register_otp_user_id' => $user->id,
        ]);

        $this->sendWhatsAppOtp($user->no_hp, $otp);
        \Illuminate\Support\Facades\Log::info("OTP Pendaftaran dikirim ke {$user->no_hp} via Fonnte.");

        return redirect()->route('register.otp')->with('success', 'Kode OTP pendaftaran telah dikirim ke WhatsApp Anda.');
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
    /**
     * Helper untuk Mengirim Pesan via Fonnte API
     */
    private function sendWhatsAppOtp($target, $otp)
    {
        $message = "🔒 *GEO-SINFRA - Keamanan Ganda*\n\nKode OTP Anda adalah: *{$otp}*\n\nKode ini berlaku selama 5 menit. _JANGAN BERIKAN KODE INI KEPADA SIAPAPUN_.";

        try {
            $response = \Illuminate\Support\Facades\Http::withHeaders([
                'Authorization' => 'xLU1pfsHFEazhMLvZrmRWRxfSK2BEBt3LSELqHVEjxpa'
            ])->post('https://api.fonnte.com/send', [
                'target' => $target,
                'message' => $message,
                'countryCode' => '62', // Opsional, sesuaikan dengan target API
            ]);

            $body = $response->body();
            \Illuminate\Support\Facades\Log::info('Response dari Fonnte: ' . $body);

            return $body;
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Fonnte API Error: ' . $e->getMessage());
            return false;
        }
    }
}