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

    <title>Atur Ulang Sandi | GEO-SINFRA</title>
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
                        navy: { 900:'#0f0e2c', 950:'#070617' },
                        gold: { 500:'#c5a059', 600:'#b38f4a' }
                    }
                }
            }
        }
    </script>

    <style>
        *, *::before, *::after { box-sizing: border-box; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; }

        .auth-left {
            background:
                radial-gradient(ellipse at 70% 10%, rgba(197,160,89,0.1) 0%, transparent 55%),
                radial-gradient(ellipse at 20% 90%, rgba(99,102,241,0.1) 0%, transparent 55%),
                radial-gradient(ellipse at 50% 50%, rgba(255,255,255,0.8) 0%, transparent 80%),
                #f8fafc;
        }
        .dark .auth-left {
            background:
                radial-gradient(ellipse at 70% 10%, rgba(197,160,89,0.18) 0%, transparent 55%),
                radial-gradient(ellipse at 20% 90%, rgba(99,102,241,0.20) 0%, transparent 55%),
                radial-gradient(ellipse at 50% 50%, rgba(14,14,40,0.6) 0%, transparent 80%),
                #070617;
        }
        .grid-bg {
            position: absolute; inset: 0; pointer-events: none;
            background-image:
                linear-gradient(rgba(15,14,44,0.035) 1px, transparent 1px),
                linear-gradient(90deg, rgba(15,14,44,0.035) 1px, transparent 1px);
            background-size: 44px 44px;
            mask-image: radial-gradient(ellipse 80% 80% at 50% 50%, black 40%, transparent 100%);
            animation: gridDrift 20s linear infinite;
        }
        .dark .grid-bg {
            background-image:
                linear-gradient(rgba(255,255,255,0.035) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.035) 1px, transparent 1px);
        }
        @keyframes gridDrift { 0% { background-position: 0 0; } 100% { background-position: 44px 44px; } }

        .input-field { width: 100%; padding: 13px 46px 13px 18px; background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 14px; font-size: 14px; font-weight: 600; color: #0f0e2c; outline: none; transition: all 0.25s ease; }
        .input-field:focus { background: #fff; border-color: #c5a059; box-shadow: 0 0 0 3px rgba(197,160,89,0.12); }
        .input-field::placeholder { color: #94a3b8; font-weight: 500; }

        /* Password strength bar */
        .strength-bar { height: 4px; border-radius: 4px; background: #e2e8f0; overflow: hidden; transition: all 0.3s ease; }
        .strength-fill { height: 100%; border-radius: 4px; width: 0%; transition: all 0.4s ease; }

        .btn-gold { position: relative; overflow: hidden; width: 100%; padding: 14px 24px; background: linear-gradient(135deg, #c5a059 0%, #b38f4a 100%); border: none; border-radius: 14px; cursor: pointer; font-size: 11px; font-weight: 900; color: #fff; text-transform: uppercase; letter-spacing: 0.2em; box-shadow: 0 8px 32px rgba(197,160,89,0.28); transition: all 0.25s ease; }
        .btn-gold:hover { transform: translateY(-1px); box-shadow: 0 12px 40px rgba(197,160,89,0.40); }
        .btn-gold:disabled { opacity: 0.5; cursor: not-allowed; transform: none; }
        .btn-gold::after { content: ''; position: absolute; top: -50%; left: -60%; width: 30%; height: 200%; background: rgba(255,255,255,0.3); transform: rotate(30deg); transition: none; }
        .btn-gold:hover::after { left: 120%; transition: all 0.55s ease-in-out; }

        .alert-error { padding: 12px 16px; border-radius: 12px; background: #fef2f2; border: 1px solid #fecaca; color: #dc2626; font-size: 13px; font-weight: 600; display: flex; align-items: flex-start; gap: 8px; }

        /* Session timer badge */
        .timer-badge { display: inline-flex; align-items: center; gap: 8px; padding: 6px 14px; border-radius: 100px; font-size: 11px; font-weight: 800; background: rgba(239,68,68,0.08); border: 1px solid rgba(239,68,68,0.2); color: #dc2626; }

        .fade-in-up { opacity: 0; transform: translateY(20px); animation: fadeInUp 0.5s ease forwards; }
        @keyframes fadeInUp { to { opacity: 1; transform: translateY(0); } }
        .d1{animation-delay:.1s} .d2{animation-delay:.2s} .d3{animation-delay:.3s} .d4{animation-delay:.4s} .d5{animation-delay:.5s}

        .pass-toggle { position: absolute; right: 0; top: 0; bottom: 0; display: flex; align-items: center; padding: 0 16px; background: none; border: none; cursor: pointer; color: #94a3b8; transition: color 0.2s; }
        .pass-toggle:hover { color: #c5a059; }
    
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

    
        /* Forced text colors */
        .auth-text-dark { color: #000000 !important; }
        .dark .auth-text-dark { color: rgba(255,255,255,0.8) !important; }
        
        .auth-footer-text { color: #000000 !important; }
        .dark .auth-footer-text { color: rgba(255,255,255,0.5) !important; }
        
    </style>
</head>
<body class="antialiased bg-slate-50">

<div class="flex min-h-screen">

    {{-- ═══ LEFT PANEL ═══ --}}
    <div class="hidden lg:flex lg:w-[44%] auth-left transition-colors duration-300 flex-col items-center justify-center relative overflow-hidden p-12">
        <div class="grid-bg"></div>

        <a href="{{ route('login') }}" style="color: black !important;" class="absolute top-6 left-6 z-20 flex items-center gap-2 text-black hover:text-black dark:text-navy-900 dark:text-white/50 dark:hover:text-navy-900 dark:text-white transition-all text-xs font-bold uppercase tracking-widest group">
            <span style="background-color: rgba(0,0,0,0.05) !important; border-color: rgba(0,0,0,0.1) !important;" class="w-8 h-8 rounded-xl bg-black/5 dark:bg-white/5 border border-black/10 dark:border-white/10 flex items-center justify-center group-hover:bg-black/10 dark:group-hover:bg-white/10 transition-all">
                <i class="fas fa-arrow-left text-[10px]"></i>
            </span>
            Login
        </a>

        <div class="relative z-10 flex flex-col items-center text-center max-w-sm">
            <div class="w-20 h-20 rounded-2xl bg-emerald-500/10 border border-emerald-500/25 flex items-center justify-center mx-auto mb-8">
                <i class="fas fa-lock-open text-emerald-400 text-3xl"></i>
            </div>
            <span class="text-[11px] font-black text-gold-500 uppercase tracking-[0.35em] mb-3 block">Keamanan Akun</span>
            <h1 style="color: black !important;" class="text-4xl font-black text-black dark:text-white tracking-tight mb-4 leading-none transition-colors duration-300">Buat Sandi<br><span class="text-emerald-400">Baru</span></h1>
            <p style="color: black !important;" class="text-black dark:text-white/80 text-sm leading-relaxed font-black max-w-[240px] transition-colors duration-300">
                Buat kata sandi baru yang kuat untuk mengamankan akun GEO-SINFRA Anda.
            </p>
            <div class="w-12 h-0.5 bg-gradient-to-r from-emerald-500 to-gold-500 rounded-full mx-auto my-8 opacity-70"></div>

            {{-- Tips --}}
            <div class="flex flex-col gap-3 w-full text-left">
                <p style="color: black !important; font-weight: 900;" class="text-black dark:text-white/40 text-[10px] font-black uppercase tracking-widest px-1 mb-1">Tips Sandi Kuat</p>
                @foreach([
                    ['fas fa-check','text-emerald-400','Minimal 8 karakter'],
                    ['fas fa-check','text-emerald-400','Kombinasi huruf besar & kecil'],
                    ['fas fa-check','text-emerald-400','Sertakan angka & simbol'],
                    ['fas fa-times','text-red-400','Jangan gunakan tanggal lahir'],
                ] as [$icon, $color, $tip])
                <div class="flex items-center gap-3 bg-white/4 border border-white/6 rounded-xl px-3.5 py-2.5">
                    <i class="{{ $icon }} {{ $color }} text-xs flex-shrink-0"></i>
                    <span style="color: black !important; font-weight: 900;" class="text-black dark:text-white/60 text-xs font-black">{{ $tip }}</span>
                </div>
                @endforeach
            </div>
        </div>

        <p style="color: black !important;" class="absolute bottom-6 text-black dark:text-white/20 text-[10px] font-bold uppercase tracking-widest transition-colors duration-300">
            &copy; 2026 GEO-SINFRA
        </p>
    </div>

    {{-- ═══ RIGHT PANEL ═══ --}}
    <div class="flex-1 flex flex-col items-center justify-center bg-white px-6 py-10 relative">

        <a href="{{ route('login') }}" class="lg:hidden absolute top-6 left-6 w-10 h-10 bg-black/5 hover:bg-black/10 border border-black/10 rounded-xl flex items-center justify-center text-slate-500 transition shadow-sm">
            <i class="fas fa-arrow-left text-xs"></i>
        </a>

        <div class="w-full max-w-[400px]">

            {{-- Header --}}
            <div class="mb-7 fade-in-up d1">
                <p class="text-[10px] font-black text-gold-500 uppercase tracking-[0.3em] mb-2">Atur Ulang Password</p>
                <h2 class="text-2xl font-black text-navy-900 tracking-tight">Buat Kata Sandi Baru</h2>
                <p class="text-slate-400 text-sm font-medium mt-1">Sandi baru harus berbeda dari sandi sebelumnya</p>
            </div>

            {{-- Session Timer --}}
            <div class="mb-6 fade-in-up d1">
                <div class="timer-badge">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-red-500"></span>
                    </span>
                    <span>Sesi berakhir: <strong id="countdown">05:00</strong></span>
                </div>
            </div>

            {{-- Errors --}}
            @if($errors->any())
                <div class="alert-error mb-5 fade-in-up d1">
                    <i class="fas fa-exclamation-circle flex-shrink-0 mt-0.5"></i>
                    <ul class="list-none space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form id="resetForm" action="{{ route('password.update') }}" method="POST" class="space-y-5">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                {{-- Email --}}
                <div class="fade-in-up d2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.18em] mb-2">
                        Email Konfirmasi <span class="text-gold-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="nama@geo-sinfra.co.id" required class="input-field" style="padding-left:44px">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm"><i class="fas fa-envelope"></i></span>
                    </div>
                </div>

                {{-- New Password --}}
                <div class="fade-in-up d3">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.18em] mb-2">
                        Kata Sandi Baru <span class="text-gold-500">*</span>
                    </label>
                    <div class="relative mb-2">
                        <input type="password" name="password" id="password" placeholder="Minimal 8 karakter" required class="input-field" oninput="checkStrength(this.value)">
                        <button type="button" class="pass-toggle" onclick="togglePass('password','eye1')">
                            <i id="eye1" class="fas fa-eye text-sm"></i>
                        </button>
                    </div>
                    {{-- Strength bar --}}
                    <div class="strength-bar"><div class="strength-fill" id="strength-fill"></div></div>
                    <p id="strength-text" class="text-[10px] font-bold mt-1 text-slate-400"></p>
                </div>

                {{-- Confirm Password --}}
                <div class="fade-in-up d4">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.18em] mb-2">
                        Konfirmasi Sandi <span class="text-gold-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="password" name="password_confirmation" id="password_confirm" placeholder="Ulangi kata sandi" required class="input-field">
                        <button type="button" class="pass-toggle" onclick="togglePass('password_confirm','eye2')">
                            <i id="eye2" class="fas fa-eye text-sm"></i>
                        </button>
                    </div>
                </div>

                {{-- Submit --}}
                <div class="fade-in-up d5 pt-1">
                    <button type="submit" class="btn-gold" id="submitBtn">
                        <i class="fas fa-save mr-2"></i> Simpan Kata Sandi Baru
                    </button>
                </div>
            </form>

        </div>
    </div>

</div>

<script>
    // ── Countdown Timer ──
    let timeLeft = Math.min(300, Math.floor({{ $sisaWaktu ?? 300 }}));
    const display = document.getElementById('countdown');
    const submitBtn = document.getElementById('submitBtn');

    const timer = setInterval(() => {
        if (timeLeft <= 0) {
            clearInterval(timer);
            display.textContent = '00:00';
            submitBtn.disabled = true;
            submitBtn.innerHTML = 'WAKTU HABIS';
            setTimeout(() => { window.location.href = "{{ route('login') }}"; }, 2000);
        } else {
            const m = Math.floor(timeLeft / 60);
            const s = Math.floor(timeLeft % 60);
            display.textContent = `${m < 10 ? '0' : ''}${m}:${s < 10 ? '0' : ''}${s}`;
        }
        timeLeft--;
    }, 1000);

    // ── Toggle Password Visibility ──
    function togglePass(id, eyeId) {
        const inp = document.getElementById(id);
        const eye = document.getElementById(eyeId);
        inp.type = inp.type === 'password' ? 'text' : 'password';
        eye.classList.toggle('fa-eye');
        eye.classList.toggle('fa-eye-slash');
    }

    // ── Password Strength ──
    function checkStrength(val) {
        const fill = document.getElementById('strength-fill');
        const text = document.getElementById('strength-text');
        let score = 0;
        if (val.length >= 8) score++;
        if (/[A-Z]/.test(val)) score++;
        if (/[0-9]/.test(val)) score++;
        if (/[^A-Za-z0-9]/.test(val)) score++;

        const levels = [
            { pct: '0%',   color: '', label: '' },
            { pct: '25%',  color: '#ef4444', label: 'Sangat Lemah' },
            { pct: '50%',  color: '#f59e0b', label: 'Cukup' },
            { pct: '75%',  color: '#3b82f6', label: 'Kuat' },
            { pct: '100%', color: '#10b981', label: 'Sangat Kuat' },
        ];
        fill.style.width  = levels[score].pct;
        fill.style.background = levels[score].color;
        text.textContent  = levels[score].label;
        text.style.color  = levels[score].color;
    }

    // Loading state
    document.getElementById('resetForm').addEventListener('submit', () => {
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Menyimpan...';
        submitBtn.disabled = true; submitBtn.style.opacity = '0.8';
    });
</script>
</body>
</html>
