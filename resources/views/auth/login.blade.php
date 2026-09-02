<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script>
        if (localStorage.getItem('geo-theme') === 'dark' || (!('geo-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    <title>Masuk | GEO-SINFRA</title>
    <meta name="description" content="Login ke sistem GEO-SINFRA - Sistem Pemetaan Infrastruktur Permukiman Kota Banjarmasin">
    <link rel="icon" href="{{ asset('logo_geo-sinfra.png') }}" type="image/png">

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Plus Jakarta Sans', 'sans-serif'] },
                    colors: {
                        navy: { 50:'#f4f4fa', 100:'#e9e9f3', 200:'#c7c8e3', 500:'#6366f1', 800:'#1e1b4b', 900:'#0f0e2c', 950:'#070617' },
                        gold: { 50:'#fdfbf7', 100:'#fbf7ed', 500:'#c5a059', 600:'#b38f4a', 700:'#9d7c3d' }
                    }
                }
            }
        }
    </script>

    <style>
        *, *::before, *::after { box-sizing: border-box; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; }

                /* ── Left Panel ── */
        .auth-left {
            background:
                radial-gradient(ellipse at 70% 10%, rgba(197,160,89,0.1) 0%, transparent 55%),
                radial-gradient(ellipse at 20% 90%, rgba(99,102,241,0.1) 0%, transparent 55%),
                radial-gradient(ellipse at 50% 50%, rgba(255,255,255,0.8) 0%, transparent 80%),
                #f8fafc;
        }
        
        

        /* Animated grid */
        .grid-bg {
            position: absolute; inset: 0; pointer-events: none;
            background-image:
                linear-gradient(rgba(15,14,44,0.035) 1px, transparent 1px),
                linear-gradient(90deg, rgba(15,14,44,0.035) 1px, transparent 1px);
            background-size: 44px 44px;
            mask-image: radial-gradient(ellipse 80% 80% at 50% 50%, black 40%, transparent 100%);
            animation: gridDrift 20s linear infinite;
        }
        
        
        @keyframes gridDrift {
            0% { background-position: 0 0; }
            100% { background-position: 44px 44px; }
        }

        /* Floating orbs */
        .orb {
            position: absolute; border-radius: 50%; filter: blur(80px); opacity: 0.35;
            animation: orbFloat 8s ease-in-out infinite;
        }
        .orb-1 { width: 300px; height: 300px; background: #c5a059; top: -80px; right: -60px; animation-delay: 0s; }
        .orb-2 { width: 250px; height: 250px; background: #6366f1; bottom: -60px; left: -40px; animation-delay: -4s; }
        @keyframes orbFloat {
            0%, 100% { transform: translate(0, 0) scale(1); }
            50%       { transform: translate(20px, -20px) scale(1.05); }
        }

        /* Logo glow ring */
        .logo-ring {
            position: relative; width: 88px; height: 88px;
            border-radius: 50%;
            background: linear-gradient(135deg, #c5a059 0%, #6366f1 100%);
            padding: 2px;
            box-shadow: 0 0 40px rgba(197,160,89,0.2), 0 0 80px rgba(197,160,89,0.1);
            animation: ringPulse 3s ease-in-out infinite;
        }
        .dark .logo-ring {
            box-shadow: 0 0 40px rgba(197,160,89,0.4), 0 0 80px rgba(197,160,89,0.15);
        }
        .logo-ring-inner { width: 100%; height: 100%; border-radius: 50%; background: #ffffff; overflow: hidden;
            display: flex; align-items: center; justify-content: center;
        }
        .dark .logo-ring-inner { background: #0f0e2c; }

        @keyframes ringPulse {
            0%, 100% { box-shadow: 0 0 30px rgba(197,160,89,0.2), 0 0 60px rgba(197,160,89,0.1); }
            50%       { box-shadow: 0 0 50px rgba(197,160,89,0.4), 0 0 100px rgba(197,160,89,0.15); }
        }

        /* Stats pill */
        .stat-pill {
            display: inline-flex; align-items: center; gap: 8px;
            background: rgba(15,14,44,0.08); border: 1px solid rgba(15,14,44,0.15);
            backdrop-filter: blur(12px); border-radius: 100px;
            padding: 8px 16px; font-size: 11px; font-weight: 700;
            color: #000000; font-weight: 900; text-transform: uppercase; letter-spacing: 0.12em;
        }
        .dark .stat-pill {
            background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1);
            color: rgba(255,255,255,0.7);
        }

        /* ── Right Panel / Form ── */
        .input-field {
            width: 100%; padding: 13px 18px;
            background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 14px;
            font-size: 14px; font-weight: 600; color: #0f0e2c;
            outline: none; transition: all 0.25s ease;
        }
        
        .input-field:focus {
            background: #fff; border-color: #c5a059;
            box-shadow: 0 0 0 3px rgba(197,160,89,0.12);
        }
        
        .input-field::placeholder { color: #94a3b8; font-weight: 500; }
        

        .label-field {
            display: block; font-size: 10px; font-weight: 800;
            color: #94a3b8; text-transform: uppercase; letter-spacing: 0.18em;
            margin-bottom: 8px;
        }
        

        /* CTA Button */
        .btn-gold {
            position: relative; overflow: hidden; width: 100%;
            padding: 14px 24px;
            background: linear-gradient(135deg, #c5a059 0%, #b38f4a 100%);
            border: none; border-radius: 14px; cursor: pointer;
            font-size: 11px; font-weight: 900; color: #fff;
            text-transform: uppercase; letter-spacing: 0.2em;
            box-shadow: 0 8px 32px rgba(197,160,89,0.28);
            transition: all 0.25s ease;
        }
        .btn-gold:hover {
            transform: translateY(-1px);
            box-shadow: 0 12px 40px rgba(197,160,89,0.40);
        }
        .btn-gold:active { transform: translateY(0); }
        .btn-gold::after {
            content: ''; position: absolute;
            top: -50%; left: -60%; width: 30%; height: 200%;
            background: rgba(255,255,255,0.3);
            transform: rotate(30deg); transition: none;
        }
        .btn-gold:hover::after { left: 120%; transition: all 0.55s ease-in-out; }

        /* Divider */
        .divider { position: relative; text-align: center; margin: 20px 0; }
        .divider::before {
            content: ''; position: absolute; top: 50%; left: 0; right: 0;
            height: 1px; background: #e2e8f0;
        }
        
        .divider span {
            position: relative; background: #fff;
            padding: 0 12px; font-size: 10px; font-weight: 700;
            color: #94a3b8; text-transform: uppercase; letter-spacing: 0.15em;
        }
        

        /* Alert variants */
        .alert-error {
            padding: 12px 16px; border-radius: 12px;
            background: #fef2f2; border: 1px solid #fecaca;
            color: #dc2626; font-size: 13px; font-weight: 600;
            display: flex; align-items: center; gap: 8px;
        }
        .alert-success {
            padding: 12px 16px; border-radius: 12px;
            background: #f0fdf4; border: 1px solid #bbf7d0;
            color: #16a34a; font-size: 13px; font-weight: 600;
            display: flex; align-items: center; gap: 8px;
        }

        /* Password toggle */
        .pass-toggle {
            position: absolute; right: 0; top: 0; bottom: 0;
            display: flex; align-items: center; padding: 0 16px;
            background: none; border: none; cursor: pointer;
            color: #94a3b8; transition: color 0.2s;
        }
        .pass-toggle:hover { color: #c5a059; }

        /* Fade-in animation */
        .fade-in-up {
            opacity: 0; transform: translateY(24px);
            animation: fadeInUp 0.6s ease forwards;
        }
        @keyframes fadeInUp {
            to { opacity: 1; transform: translateY(0); }
        }
        .delay-1 { animation-delay: 0.1s; }
        .delay-2 { animation-delay: 0.2s; }
        .delay-3 { animation-delay: 0.3s; }
        .delay-4 { animation-delay: 0.4s; }
        .delay-5 { animation-delay: 0.5s; }
    
        /* Brand Glow Animation */
        @keyframes shimmerFlow {
            0%   { background-position: -200% center; }
            100% { background-position: 200% center; }
        }
        @keyframes glowPulse {
            0%, 100% { filter: drop-shadow(0 0 6px rgba(223,167,42,0.4)) drop-shadow(0 0 12px rgba(117,116,216,0.3)); }
            50%       { filter: drop-shadow(0 0 14px rgba(223,167,42,0.8)) drop-shadow(0 0 28px rgba(117,116,216,0.7)); }
        }
        .brand-text-glow {
            background: linear-gradient(to right, #DFA72A, #C4AE7D, #7574D8, #DFA72A, #C4AE7D);
            background-size: 300% auto;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            display: inline-block;
            animation: shimmerFlow 4s linear infinite, glowPulse 2.5s ease-in-out infinite;
        }
        .brand-text-glow:hover {
            animation-duration: 1.5s, 1s;
        }

    
        .dark .grid-bg {
            background-image:
                linear-gradient(rgba(255,255,255,0.035) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.035) 1px, transparent 1px);
        }
        .dark .auth-left {
            background:
                radial-gradient(ellipse at 70% 10%, rgba(197,160,89,0.18) 0%, transparent 55%),
                radial-gradient(ellipse at 20% 90%, rgba(99,102,241,0.20) 0%, transparent 55%),
                radial-gradient(ellipse at 50% 50%, rgba(14,14,40,0.6) 0%, transparent 80%),
                #070617;
        }
        
        
        
    
        /* Forced text colors for dynamic switching */
        .auth-dynamic-text { color: #000000 !important; }
        .dark .auth-dynamic-text { color: rgba(255,255,255,0.85) !important; }
        
        .auth-dynamic-bg { background-color: rgba(0,0,0,0.05) !important; border-color: rgba(0,0,0,0.1) !important; }
        .dark .auth-dynamic-bg { background-color: rgba(255,255,255,0.05) !important; border-color: rgba(255,255,255,0.1) !important; }
    </style>
</head>
<body class="antialiased bg-slate-50">

<div class="flex min-h-screen">

        {{-- ═══════════════════════════════════════════════════
        LEFT PANEL — Branding
    ═══════════════════════════════════════════════════ --}}
    <div class="hidden lg:flex lg:w-[46%] xl:w-5/12 auth-left flex-col items-center justify-center relative overflow-hidden p-12 transition-colors duration-300">
        <div class="grid-bg"></div>
        <div class="orb orb-1"></div>
        <div class="orb orb-2"></div>

        {{-- Back to home --}}
        <a href="{{ url('/') }}" class="auth-dynamic-text absolute top-6 left-6 z-20 flex items-center gap-2 text-black hover:text-black dark:text-white/50 dark:hover:text-white transition-all text-xs font-bold uppercase tracking-widest group">
            <span class="auth-dynamic-bg w-8 h-8 rounded-xl bg-black/5 dark:bg-white/5 border border-black/10 dark:border-white/10 flex items-center justify-center group-hover:bg-black/10 dark:group-hover:bg-white/10 transition-all">
                <i class="fas fa-arrow-left text-[10px]"></i>
            </span>
            Kembali
        </a>

        {{-- Content --}}
        <div class="relative z-10 flex flex-col items-center text-center max-w-sm">

            {{-- Logo --}}
            <div class="logo-ring mb-8">
                <div class="logo-ring-inner">
                    <img src="{{ asset('logo_geo-sinfra.png') }}" alt="GEO-SINFRA" class="w-16 h-16 object-contain">
                </div>
            </div>

            {{-- Brand name --}}
            <div class="mb-2">
                <span class="text-[11px] font-black text-gold-500 uppercase tracking-[0.35em]">Sistem Pemetaan</span>
            </div>
            <h1 class="text-4xl xl:text-5xl font-black tracking-tight mb-4 leading-none brand-text-glow cursor-default transition-colors duration-300">
                GEO-SINFRA
            </h1>
            <p class="auth-text-dark text-black dark:text-white/80 text-sm leading-relaxed font-black max-w-[260px] transition-colors duration-300">
                Infrastruktur Permukiman Kota Banjarmasin berbasis Web GIS & AI
            </p>

            {{-- Divider --}}
            <div class="w-12 h-0.5 bg-gradient-to-r from-gold-500 to-indigo-500 rounded-full mx-auto my-8 opacity-70"></div>

            {{-- Stats pills --}}
            <div class="flex flex-col gap-3 w-full">
                <div class="stat-pill">
                    <i class="fas fa-map-marked-alt text-gold-500"></i>
                    Pemetaan GIS Interaktif
                </div>
                <div class="stat-pill">
                    <i class="fas fa-brain text-indigo-400"></i>
                    Analisis AI Otomatis
                </div>
                <div class="stat-pill">
                    <i class="fas fa-shield-check text-emerald-400"></i>
                    Sistem Keamanan Berlapis
                </div>
            </div>
        </div>

        {{-- Bottom copyright --}}
        <p class="absolute bottom-6 auth-footer-text text-black dark:text-white/50 text-[10px] font-black uppercase tracking-widest transition-colors duration-300">
            &copy; 2026 GEO-SINFRA
        </p>
    </div>

    {{-- ═══════════════════════════════════════════════════
        RIGHT PANEL — Form Login
    ═══════════════════════════════════════════════════ --}}
    <div class="flex-1 flex flex-col items-center justify-center bg-white px-6 py-10 relative min-h-screen">

        {{-- Mobile back button --}}
        <a href="{{ url('/') }}" class="lg:hidden absolute top-6 left-6 w-10 h-10 bg-black/5 hover:bg-black/10 border border-black/10 rounded-xl flex items-center justify-center text-black hover:text-black transition-all shadow-sm">
            <i class="fas fa-arrow-left text-xs"></i>
        </a>

        {{-- Mobile logo --}}
        <div class="lg:hidden flex flex-col items-center mb-10">
            <div class="w-16 h-16 rounded-2xl overflow-hidden border-2 border-slate-100 shadow-lg mb-3">
                <img src="{{ asset('logo_geo-sinfra.png') }}" alt="Logo" class="w-full h-full object-contain">
            </div>
            <p class="text-xs font-black text-gold-500 uppercase tracking-widest">GEO-SINFRA</p>
        </div>

        <div class="w-full max-w-[400px]">

            {{-- Header --}}
            <div class="mb-8 fade-in-up">
                <p class="text-[10px] font-black text-gold-500 uppercase tracking-[0.3em] mb-2">Portal Akses</p>
                <h2 class="text-2xl font-black text-navy-900 tracking-tight">Selamat Datang</h2>
                <p class="text-slate-400 text-sm font-medium mt-1">Masuk untuk mengakses sistem pemetaan infrastruktur</p>
            </div>

            {{-- Alerts --}}
            @if($errors->any())
                <div class="alert-error mb-5 fade-in-up delay-1">
                    <i class="fas fa-exclamation-circle flex-shrink-0"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif
            @if(session('error'))
                <div class="alert-error mb-5 fade-in-up delay-1">
                    <i class="fas fa-exclamation-circle flex-shrink-0"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif
            @if(session('success'))
                <div class="alert-success mb-5 fade-in-up delay-1">
                    <i class="fas fa-check-circle flex-shrink-0"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            {{-- Form --}}
            <form action="{{ route('login') }}" method="POST" class="space-y-5">
                @csrf

                {{-- Email / WA --}}
                <div class="fade-in-up delay-2">
                    <label class="label-field">
                        Email / Nomor WhatsApp <span class="text-gold-500">*</span>
                    </label>
                    <div class="relative">
                        <input
                            type="text" name="login" id="login-input"
                            placeholder="Email atau 0812xxxx"
                            value="{{ old('login') }}" required autocomplete="username"
                            class="input-field pl-11"
                        >
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm">
                            <i class="fas fa-user"></i>
                        </span>
                    </div>
                </div>

                {{-- Password --}}
                <div class="fade-in-up delay-3">
                    <label class="label-field">
                        Kata Sandi <span class="text-gold-500">*</span>
                    </label>
                    <div class="relative">
                        <input
                            type="password" name="password" id="password"
                            placeholder="••••••••" required autocomplete="current-password"
                            class="input-field pl-11 pr-12"
                        >
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm">
                            <i class="fas fa-lock"></i>
                        </span>
                        <button type="button" class="pass-toggle" onclick="togglePassword()" aria-label="Toggle password visibility">
                            <i id="eye-icon" class="fas fa-eye text-sm"></i>
                        </button>
                    </div>
                </div>

                {{-- CAPTCHA --}}
                <div class="fade-in-up delay-4">
                    <label class="label-field">
                        Verifikasi: {{ $n1 ?? 3 }} + {{ $n2 ?? 5 }} = ? <span class="text-gold-500">*</span>
                    </label>
                    <div class="relative">
                        <input
                            type="number" name="captcha"
                            placeholder="Jawaban Anda" required
                            class="input-field pl-11"
                        >
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm">
                            <i class="fas fa-calculator"></i>
                        </span>
                    </div>
                </div>

                {{-- Remember + Forgot --}}
                <div class="flex items-center justify-between fade-in-up delay-4">
                    <label class="flex items-center gap-2 cursor-pointer group">
                        <input type="checkbox" name="remember" id="remember"
                            class="w-4 h-4 rounded-md border-slate-300 text-gold-500 focus:ring-gold-500 cursor-pointer">
                        <span class="text-[11px] font-bold text-slate-400 group-hover:text-navy-900 transition uppercase tracking-wide">
                            Ingat Saya
                        </span>
                    </label>
                    <a href="{{ route('password.request') }}"
                        class="text-[11px] font-black text-gold-500 hover:text-gold-600 transition uppercase tracking-wide">
                        Lupa Sandi?
                    </a>
                </div>

                {{-- Submit --}}
                <div class="fade-in-up delay-5">
                    <button type="submit" class="btn-gold" id="login-btn">
                        <i class="fas fa-sign-in-alt mr-2"></i> Masuk
                    </button>
                </div>
            </form>

            {{-- Divider --}}
            <div class="divider fade-in-up delay-5">
                <span>Atau</span>
            </div>

            {{-- Register link --}}
            <div class="text-center fade-in-up delay-5">
                <p class="text-sm text-slate-400 font-medium">
                    Belum punya akun?
                    <a href="{{ route('register') }}" class="text-gold-500 font-extrabold hover:text-gold-600 transition ml-1 hover:underline">
                        Daftar Sekarang
                    </a>
                </p>
            </div>

        </div>

        {{-- Bottom note --}}
        <p class="absolute bottom-6 text-slate-400 text-[10px] font-bold uppercase tracking-widest text-center">
            &copy; 2026 GEO-SINFRA
        </p>
    </div>

</div>

<script>
    function togglePassword() {
        const input = document.getElementById('password');
        const icon  = document.getElementById('eye-icon');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    }

    // Loading state on submit
    document.querySelector('form').addEventListener('submit', function() {
        const btn = document.getElementById('login-btn');
        btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Memverifikasi...';
        btn.disabled = true;
        btn.style.opacity = '0.8';
    });
</script>

</body>
</html>
