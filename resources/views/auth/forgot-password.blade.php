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

    <title>Lupa Kata Sandi | GEO-SINFRA</title>
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

        .input-field { width: 100%; padding: 13px 18px 13px 44px; background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 14px; font-size: 14px; font-weight: 600; color: #0f0e2c; outline: none; transition: all 0.25s ease; }
        .input-field:focus { background: #fff; border-color: #c5a059; box-shadow: 0 0 0 3px rgba(197,160,89,0.12); }
        .input-field::placeholder { color: #94a3b8; font-weight: 500; }

        .btn-gold { position: relative; overflow: hidden; width: 100%; padding: 14px 24px; background: linear-gradient(135deg, #c5a059 0%, #b38f4a 100%); border: none; border-radius: 14px; cursor: pointer; font-size: 11px; font-weight: 900; color: #fff; text-transform: uppercase; letter-spacing: 0.2em; box-shadow: 0 8px 32px rgba(197,160,89,0.28); transition: all 0.25s ease; }
        .btn-gold:hover { transform: translateY(-1px); box-shadow: 0 12px 40px rgba(197,160,89,0.40); }
        .btn-gold::after { content: ''; position: absolute; top: -50%; left: -60%; width: 30%; height: 200%; background: rgba(255,255,255,0.3); transform: rotate(30deg); transition: none; }
        .btn-gold:hover::after { left: 120%; transition: all 0.55s ease-in-out; }

        .alert-success { padding: 14px 16px; border-radius: 12px; background: #f0fdf4; border: 1px solid #bbf7d0; color: #16a34a; font-size: 13px; font-weight: 600; display: flex; align-items: flex-start; gap: 10px; }
        .alert-error { padding: 12px 16px; border-radius: 12px; background: #fef2f2; border: 1px solid #fecaca; color: #dc2626; font-size: 13px; font-weight: 600; display: flex; align-items: center; gap: 8px; }

        .fade-in-up { opacity: 0; transform: translateY(20px); animation: fadeInUp 0.5s ease forwards; }
        @keyframes fadeInUp { to { opacity: 1; transform: translateY(0); } }
        .d1{animation-delay:.1s} .d2{animation-delay:.2s} .d3{animation-delay:.3s} .d4{animation-delay:.4s}
    
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
            Kembali
        </a>

        <div class="relative z-10 flex flex-col items-center text-center max-w-sm">
            <div class="w-20 h-20 rounded-2xl bg-gold-500/10 border border-gold-500/25 flex items-center justify-center mx-auto mb-8" style="animation: shieldPulse 3s ease-in-out infinite;">
                <i class="fas fa-key text-gold-500 text-3xl"></i>
            </div>

            <span class="text-[11px] font-black text-gold-500 uppercase tracking-[0.35em] mb-3 block">Pemulihan Akun</span>
            <h1 style="color: black !important;" class="text-4xl font-black text-black dark:text-white tracking-tight mb-4 leading-none transition-colors duration-300">Lupa Kata<br><span class="text-gold-500">Sandi?</span></h1>
            <p style="color: black !important;" class="text-black dark:text-white/80 text-sm leading-relaxed font-black max-w-[240px] transition-colors duration-300">
                Jangan khawatir. Kami akan mengirimkan link pemulihan ke email Anda.
            </p>
            <div class="w-12 h-0.5 bg-gradient-to-r from-gold-500 to-indigo-500 rounded-full mx-auto my-8 opacity-70"></div>

            {{-- How it works --}}
            <div class="flex flex-col gap-4 w-full text-left">
                @foreach([
                    ['1', 'fas fa-envelope', 'Masukkan Email', 'Ketik email akun Anda di form'],
                    ['2', 'fas fa-paper-plane', 'Cek Email', 'Kami kirim link reset ke email'],
                    ['3', 'fas fa-lock-open', 'Buat Sandi Baru', 'Klik link dan atur sandi baru'],
                ] as [$num, $icon, $title, $desc])
                <div class="flex items-start gap-3 bg-black/5 dark:bg-white/5 border border-black/10 dark:border-white/10 rounded-xl p-3.5">
                    <div class="w-7 h-7 rounded-full bg-gold-500/20 border border-gold-500/30 flex items-center justify-center flex-shrink-0 mt-0.5">
                        <i class="{{ $icon }} text-gold-500 text-[10px]"></i>
                    </div>
                    <div>
                        <p style="color: black !important; font-weight: 900;" class="text-black dark:text-white/80 text-xs font-black uppercase tracking-wide">{{ $title }}</p>
                        <p style="color: black !important; font-weight: 700;" class="text-black dark:text-white/40 text-[10px] font-medium mt-0.5">{{ $desc }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <p style="color: black !important;" class="absolute bottom-6 text-black dark:text-white/20 text-[10px] font-bold uppercase tracking-widest transition-colors duration-300">
            &copy; 2026 GEO-SINFRA
        </p>
    </div>

    {{-- ═══ RIGHT PANEL ═══ --}}
    <div class="flex-1 flex flex-col items-center justify-center bg-white px-6 py-10">

        {{-- Mobile back --}}
        <a href="{{ route('login') }}" class="lg:hidden absolute top-6 left-6 w-10 h-10 bg-black/5 hover:bg-black/10 border border-black/10 rounded-xl flex items-center justify-center text-slate-500 transition shadow-sm">
            <i class="fas fa-arrow-left text-xs"></i>
        </a>

        <div class="w-full max-w-[400px]">

            <div class="mb-8 fade-in-up d1">
                <p class="text-[10px] font-black text-gold-500 uppercase tracking-[0.3em] mb-2">Reset Kata Sandi</p>
                <h2 class="text-2xl font-black text-navy-900 tracking-tight">Pemulihan Akun</h2>
                <p class="text-slate-400 text-sm font-medium mt-1">Masukkan email terdaftar Anda untuk menerima link reset</p>
            </div>

            @if(session('status'))
                <div class="alert-success mb-6 fade-in-up d1">
                    <i class="fas fa-check-circle flex-shrink-0 mt-0.5 text-lg"></i>
                    <div>
                        <p class="font-black text-sm mb-1">Email Terkirim!</p>
                        <p class="text-emerald-700 text-xs font-medium">{{ session('status') }}</p>
                    </div>
                </div>
            @endif

            @error('email')
                <div class="alert-error mb-5 fade-in-up d1">
                    <i class="fas fa-exclamation-circle flex-shrink-0"></i>
                    <span>{{ $message }}</span>
                </div>
            @enderror

            <form action="{{ route('password.email') }}" method="POST" class="space-y-5" id="forgot-form">
                @csrf

                <div class="fade-in-up d2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.18em] mb-2">
                        Alamat Email <span class="text-gold-500">*</span>
                    </label>
                    <div class="relative">
                        <input
                            type="email" name="email" id="email-input"
                            placeholder="nama@geo-sinfra.co.id"
                            value="{{ old('email') }}" required autofocus
                            class="input-field"
                        >
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm">
                            <i class="fas fa-envelope"></i>
                        </span>
                    </div>
                </div>

                <div class="fade-in-up d3">
                    <button type="submit" class="btn-gold" id="forgot-btn">
                        <i class="fas fa-paper-plane mr-2"></i> Kirim Link Reset
                    </button>
                </div>
            </form>

            <div class="mt-8 pt-6 border-t border-slate-100 text-center fade-in-up d4">
                <a href="{{ route('login') }}" class="inline-flex items-center gap-2 text-slate-400 hover:text-navy-900 text-xs uppercase tracking-wider font-black transition">
                    <i class="fas fa-arrow-left text-[10px]"></i>
                    Kembali ke Halaman Login
                </a>
            </div>

        </div>
    </div>

</div>

<script>
    document.getElementById('forgot-form').addEventListener('submit', function() {
        const btn = document.getElementById('forgot-btn');
        btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Mengirim...';
        btn.disabled = true; btn.style.opacity = '0.8';
    });
</script>
</body>
</html>
