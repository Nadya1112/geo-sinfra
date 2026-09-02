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

    <title>Daftar Akun | GEO-SINFRA</title>
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

        .orb { position: absolute; border-radius: 50%; filter: blur(80px); opacity: 0.35; animation: orbFloat 8s ease-in-out infinite; }
        .orb-1 { width: 300px; height: 300px; background: #c5a059; top: -80px; right: -60px; animation-delay: 0s; }
        .orb-2 { width: 250px; height: 250px; background: #6366f1; bottom: -60px; left: -40px; animation-delay: -4s; }
        @keyframes orbFloat {
            0%, 100% { transform: translate(0, 0) scale(1); }
            50%       { transform: translate(20px, -20px) scale(1.05); }
        }

        /* Step progress */
        .step-badge {
            width: 72px; height: 72px; border-radius: 50%;
            background: linear-gradient(135deg, rgba(197,160,89,0.15) 0%, rgba(99,102,241,0.15) 100%);
            border: 1.5px solid rgba(197,160,89,0.3);
            display: flex; align-items: center; justify-content: center;
            color: #c5a059; font-size: 26px;
        }

        .input-field {
            width: 100%; padding: 11px 16px;
            background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 12px;
            font-size: 13px; font-weight: 600; color: #0f0e2c;
            outline: none; transition: all 0.25s ease;
        }
        .input-field:focus {
            background: #fff; border-color: #c5a059;
            box-shadow: 0 0 0 3px rgba(197,160,89,0.12);
        }
        .input-field::placeholder { color: #94a3b8; font-weight: 500; }

        .label-field {
            display: block; font-size: 10px; font-weight: 800;
            color: #94a3b8; text-transform: uppercase; letter-spacing: 0.18em; margin-bottom: 6px;
        }

        .btn-gold {
            position: relative; overflow: hidden; width: 100%; padding: 13px 24px;
            background: linear-gradient(135deg, #c5a059 0%, #b38f4a 100%);
            border: none; border-radius: 12px; cursor: pointer;
            font-size: 11px; font-weight: 900; color: #fff;
            text-transform: uppercase; letter-spacing: 0.2em;
            box-shadow: 0 8px 32px rgba(197,160,89,0.28); transition: all 0.25s ease;
        }
        .btn-gold:hover { transform: translateY(-1px); box-shadow: 0 12px 40px rgba(197,160,89,0.40); }
        .btn-gold::after {
            content: ''; position: absolute;
            top: -50%; left: -60%; width: 30%; height: 200%;
            background: rgba(255,255,255,0.3); transform: rotate(30deg); transition: none;
        }
        .btn-gold:hover::after { left: 120%; transition: all 0.55s ease-in-out; }

        .alert-error {
            padding: 12px 16px; border-radius: 12px;
            background: #fef2f2; border: 1px solid #fecaca;
            color: #dc2626; font-size: 13px; font-weight: 600;
            display: flex; align-items: center; gap: 8px;
        }
        .fade-in-up { opacity: 0; transform: translateY(20px); animation: fadeInUp 0.5s ease forwards; }
        @keyframes fadeInUp { to { opacity: 1; transform: translateY(0); } }
        .d1{animation-delay:.05s} .d2{animation-delay:.1s} .d3{animation-delay:.15s}
        .d4{animation-delay:.2s} .d5{animation-delay:.25s} .d6{animation-delay:.3s}
        .d7{animation-delay:.35s} .d8{animation-delay:.4s}
    
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
    <div class="hidden lg:flex lg:w-[42%] xl:w-5/12 auth-left transition-colors duration-300 flex-col items-center justify-center relative overflow-hidden p-12">
        <div class="grid-bg"></div>
        <div class="orb orb-1"></div>
        <div class="orb orb-2"></div>

        <a href="{{ route('login') }}" style="color: black !important;" class="absolute top-6 left-6 z-20 flex items-center gap-2 text-black hover:text-black dark:text-navy-900 dark:text-white/50 dark:hover:text-navy-900 dark:text-white transition-all text-xs font-bold uppercase tracking-widest group">
            <span style="background-color: rgba(0,0,0,0.05) !important; border-color: rgba(0,0,0,0.1) !important;" class="w-8 h-8 rounded-xl bg-black/5 dark:bg-white/5 border border-black/10 dark:border-white/10 flex items-center justify-center group-hover:bg-black/10 dark:group-hover:bg-white/10 transition-all">
                <i class="fas fa-arrow-left text-[10px]"></i>
            </span>
            Kembali
        </a>

        <div class="relative z-10 flex flex-col items-center text-center max-w-sm">
            <div class="step-badge mb-8">
                <i class="fas fa-user-plus"></i>
            </div>
            <span class="text-[11px] font-black text-gold-500 uppercase tracking-[0.35em] mb-2 block">Bergabung Sekarang</span>
            <h1 class="text-4xl xl:text-5xl font-black tracking-tight mb-4 leading-none brand-text-glow cursor-default transition-colors duration-300">
                GEO-SINFRA
            </h1>
            <p style="color: black !important;" class="text-black dark:text-white/80 text-sm leading-relaxed font-black max-w-[240px] transition-colors duration-300">
                Daftarkan akun Anda untuk mulai berkontribusi dalam pemetaan infrastruktur Kota Banjarmasin.
            </p>
            <div class="w-12 h-0.5 bg-gradient-to-r from-gold-500 to-indigo-500 rounded-full mx-auto mt-8 opacity-70"></div>

            <div class="mt-8 flex flex-col gap-3 w-full text-left">
                @foreach([['fas fa-check-circle','text-emerald-400','Akun terverifikasi via WhatsApp OTP'],['fas fa-shield-alt','text-gold-500','Data tersimpan aman & terenkripsi'],['fas fa-map-marked-alt','text-indigo-400','Akses peta GIS & dashboard real-time']] as [$icon, $color, $text])
                <div class="flex items-center gap-3 bg-black/5 dark:bg-white/5 border border-black/10 dark:border-white/10 rounded-xl p-3">
                    <i class="{{ $icon }} {{ $color }} text-sm flex-shrink-0"></i>
                    <span style="color: black !important; font-weight: 900;" class="text-black dark:text-white/70 text-xs font-black">{{ $text }}</span>
                </div>
                @endforeach
            </div>
        </div>
        <p style="color: black !important;" class="absolute bottom-6 text-black dark:text-white/20 text-[10px] font-bold uppercase tracking-widest transition-colors duration-300">
            &copy; 2026 Disperkim Banjarmasin
        </p>
    </div>

    {{-- ═══ RIGHT PANEL ═══ --}}
    <div class="flex-1 flex flex-col items-center justify-center bg-white px-6 py-10 overflow-y-auto">

        <div class="w-full max-w-[420px] my-auto py-8">

            <div class="mb-7 fade-in-up d1">
                <p class="text-[10px] font-black text-gold-500 uppercase tracking-[0.3em] mb-2">Buat Akun Baru</p>
                <h2 class="text-2xl font-black text-navy-900 tracking-tight">Lengkapi Data Diri</h2>
                <p class="text-slate-400 text-sm font-medium mt-1">Isi semua field di bawah untuk mendaftar</p>
            </div>

            @if($errors->any())
                <div class="alert-error mb-5 fade-in-up d1">
                    <i class="fas fa-exclamation-circle flex-shrink-0"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <form action="{{ route('register') }}" method="POST" class="space-y-4">
                @csrf

                {{-- Nama --}}
                <div class="fade-in-up d2">
                    <label class="label-field">Nama Lengkap <span class="text-gold-500">*</span></label>
                    <div class="relative">
                        <input type="text" name="name" placeholder="Masukkan nama lengkap" value="{{ old('name') }}" required
                            class="input-field pl-10">
                        <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"><i class="fas fa-id-card"></i></span>
                    </div>
                </div>

                {{-- Email --}}
                <div class="fade-in-up d3">
                    <label class="label-field">Email / NIP <span class="text-gold-500">*</span></label>
                    <div class="relative">
                        <input type="text" name="email" placeholder="nama@disperkim.go.id" value="{{ old('email') }}" required
                            class="input-field pl-10">
                        <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"><i class="fas fa-envelope"></i></span>
                    </div>
                </div>

                {{-- WhatsApp --}}
                <div class="fade-in-up d4">
                    <label class="label-field">Nomor WhatsApp <span class="text-gold-500">*</span></label>
                    <div class="relative">
                        <input type="text" name="no_hp" placeholder="08123456789" value="{{ old('no_hp') }}" required
                            class="input-field pl-10">
                        <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"><i class="fab fa-whatsapp"></i></span>
                    </div>
                </div>

                {{-- Role --}}
                <div class="fade-in-up d5">
                    <label class="label-field">Role Akses <span class="text-gold-500">*</span></label>
                    <div class="relative">
                        <select name="role" required class="input-field pl-10 appearance-none cursor-pointer">
                            <option value="surveyor" @selected(old('role','surveyor')==='surveyor')>Surveyor</option>
                            <option value="admin" @selected(old('role')==='admin')>Admin</option>
                        </select>
                        <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"><i class="fas fa-user-tag"></i></span>
                        <span class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"><i class="fas fa-chevron-down"></i></span>
                    </div>
                </div>

                {{-- Password --}}
                <div class="fade-in-up d6">
                    <label class="label-field">Buat Kata Sandi <span class="text-gold-500">*</span></label>
                    <div class="relative">
                        <input type="password" name="password" id="password" placeholder="Minimal 8 karakter" required
                            class="input-field pl-10 pr-11">
                        <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"><i class="fas fa-lock"></i></span>
                        <button type="button" onclick="togglePass('password','eye1')" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-gold-500 transition text-xs">
                            <i id="eye1" class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                {{-- Confirm Password --}}
                <div class="fade-in-up d7">
                    <label class="label-field">Konfirmasi Kata Sandi <span class="text-gold-500">*</span></label>
                    <div class="relative">
                        <input type="password" name="password_confirmation" id="password_confirm" placeholder="Ulangi kata sandi" required
                            class="input-field pl-10 pr-11">
                        <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"><i class="fas fa-lock"></i></span>
                        <button type="button" onclick="togglePass('password_confirm','eye2')" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-gold-500 transition text-xs">
                            <i id="eye2" class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                {{-- Submit --}}
                <div class="fade-in-up d8 pt-2">
                    <button type="submit" class="btn-gold" id="reg-btn">
                        <i class="fas fa-user-plus mr-2"></i> Daftar Sekarang
                    </button>
                </div>
            </form>

            <div class="mt-6 pt-5 border-t border-slate-100 text-center fade-in-up d8">
                <p class="text-sm text-slate-400 font-medium">
                    Sudah punya akun?
                    <a href="{{ route('login') }}" class="text-gold-500 font-extrabold hover:text-gold-600 transition ml-1 hover:underline">
                        Masuk di sini
                    </a>
                </p>
            </div>

        </div>
    </div>

</div>

<script>
    function togglePass(id, eyeId) {
        const inp = document.getElementById(id);
        const eye = document.getElementById(eyeId);
        inp.type = inp.type === 'password' ? 'text' : 'password';
        eye.classList.toggle('fa-eye');
        eye.classList.toggle('fa-eye-slash');
    }
    document.querySelector('form').addEventListener('submit', function() {
        const btn = document.getElementById('reg-btn');
        btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Memproses...';
        btn.disabled = true; btn.style.opacity = '0.8';
    });
</script>
</body>
</html>
