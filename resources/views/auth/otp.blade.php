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

    <title>Verifikasi OTP | GEO-SINFRA</title>
    <link rel="icon" href="{{ asset('logo_geo-sinfra.png') }}" type="image/png">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    

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

        /* OTP Box inputs */
        .otp-box {
            width: 52px; height: 60px;
            background: #f8fafc; border: 2px solid #e2e8f0; border-radius: 14px;
            font-size: 24px; font-weight: 900; color: #0f0e2c;
            text-align: center; outline: none; transition: all 0.2s ease;
            caret-color: #c5a059;
        }
        .otp-box:focus {
            border-color: #c5a059; background: #fff;
            box-shadow: 0 0 0 4px rgba(197,160,89,0.15);
            transform: scale(1.05);
        }
        .otp-box.filled {
            border-color: #c5a059; background: #fdfbf7;
            color: #c5a059;
        }

        .btn-gold {
            position: relative; overflow: hidden; width: 100%; padding: 14px 24px;
            background: linear-gradient(135deg, #c5a059 0%, #b38f4a 100%);
            border: none; border-radius: 14px; cursor: pointer;
            font-size: 11px; font-weight: 900; color: #fff;
            text-transform: uppercase; letter-spacing: 0.2em;
            box-shadow: 0 8px 32px rgba(197,160,89,0.28); transition: all 0.25s ease;
        }
        .btn-gold:hover { transform: translateY(-1px); box-shadow: 0 12px 40px rgba(197,160,89,0.40); }
        .btn-gold:disabled { opacity: 0.6; cursor: not-allowed; transform: none; }

        /* Shield icon animation */
        .shield-icon {
            width: 80px; height: 80px; border-radius: 22px;
            background: linear-gradient(135deg, rgba(99,102,241,0.15) 0%, rgba(197,160,89,0.15) 100%);
            border: 1.5px solid rgba(197,160,89,0.3);
            display: flex; align-items: center; justify-center; justify-content: center;
            font-size: 32px; color: #c5a059; margin: 0 auto;
            animation: shieldPulse 2.5s ease-in-out infinite;
        }
        @keyframes shieldPulse {
            0%, 100% { box-shadow: 0 0 0 0 rgba(197,160,89,0); }
            50%       { box-shadow: 0 0 0 12px rgba(197,160,89,0.1); }
        }

        .alert-error { padding: 12px 16px; border-radius: 12px; background: #fef2f2; border: 1px solid #fecaca; color: #dc2626; font-size: 13px; font-weight: 600; display: flex; align-items: center; gap: 8px; }
        .alert-success { padding: 12px 16px; border-radius: 12px; background: #f0fdf4; border: 1px solid #bbf7d0; color: #16a34a; font-size: 13px; font-weight: 600; display: flex; align-items: center; gap: 8px; }
        .alert-demo { padding: 16px; border-radius: 12px; background: linear-gradient(135deg, #fdfbf7, #fbf7ed); border: 1px solid rgba(197,160,89,0.3); }

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

    
        
        
    
        /* Forced text colors for dynamic switching */
        .auth-dynamic-text { color: #000000 !important; }
        .dark .auth-dynamic-text { color: rgba(255,255,255,0.85) !important; }
        
        .auth-dynamic-bg { background-color: rgba(0,0,0,0.05) !important; border-color: rgba(0,0,0,0.1) !important; }
        .dark .auth-dynamic-bg { background-color: rgba(255,255,255,0.05) !important; border-color: rgba(255,255,255,0.1) !important; }
    </style>
</head>
<body class="antialiased bg-slate-50">

<div class="flex min-h-screen">

    {{-- ═══ LEFT PANEL ═══ --}}
    <div class="hidden lg:flex lg:w-[44%] auth-left transition-colors duration-300 flex-col items-center justify-center relative overflow-hidden p-12">
        <div class="grid-bg"></div>

        <div class="relative z-10 flex flex-col items-center text-center max-w-sm">
            <div class="shield-icon mb-8">
                <i class="fas fa-shield-alt"></i>
            </div>

            <span class="text-[11px] font-black text-gold-500 uppercase tracking-[0.35em] mb-3 block">Keamanan Akun</span>
            <h1 class="auth-dynamic-text text-4xl font-black text-black dark:text-white tracking-tight mb-4 leading-none transition-colors duration-300">
                Verifikasi <span class="text-gold-500">OTP</span>
            </h1>
            <p class="auth-dynamic-text text-black dark:text-white/80 text-sm leading-relaxed font-black max-w-[250px]">
                Kami telah mengirimkan kode 6-digit ke nomor WhatsApp yang Anda daftarkan.
            </p>

            <div class="w-12 h-0.5 bg-gradient-to-r from-gold-500 to-indigo-500 rounded-full mx-auto my-8 opacity-70"></div>

            {{-- Steps --}}
            <div class="flex flex-col gap-4 w-full text-left">
                @foreach([
                    ['1', 'Daftar', 'Isi data akun Anda', true],
                    ['2', 'Verifikasi', 'Masukkan kode OTP', true],
                    ['3', 'Akses', 'Mulai gunakan sistem', false],
                ] as [$step, $title, $desc, $done])
                <div class="flex items-center gap-4 bg-white/{{ $done ? '8' : '3' }} border border-white/{{ $done ? '12' : '5' }} rounded-xl p-3.5">
                    <div class="w-8 h-8 rounded-full {{ $done ? 'bg-gold-500' : 'bg-slate-200 dark:bg-white/5 border border-white/15' }} flex items-center justify-center flex-shrink-0">
                        @if($done)
                            <i class="fas fa-check text-navy-900 dark:text-white text-xs"></i>
                        @else
                            <span class="text-navy-900 dark:text-white/30 text-xs font-black">{{ $step }}</span>
                        @endif
                    </div>
                    <div>
                        <p class="text-navy-900 dark:text-white/{{ $done ? '90' : '40' }} text-xs font-black uppercase tracking-wide">{{ $title }}</p>
                        <p class="text-navy-900 dark:text-white/{{ $done ? '50' : '25' }} text-[10px] font-medium">{{ $desc }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <p class="auth-dynamic-text absolute bottom-6 text-black dark:text-white/20 text-[10px] font-bold uppercase tracking-widest transition-colors duration-300">
            &copy; 2026 GEO-SINFRA
        </p>
    </div>

    {{-- ═══ RIGHT PANEL ═══ --}}
    <div class="flex-1 flex flex-col items-center justify-center bg-white px-6 py-10">
        <div class="w-full max-w-[400px]">

            {{-- Header --}}
            <div class="mb-8 text-center fade-in-up d1">
                <div class="w-16 h-16 rounded-2xl bg-gold-500/10 border border-gold-500/20 flex items-center justify-center mx-auto mb-5">
                    <i class="fas fa-mobile-alt text-gold-500 text-2xl"></i>
                </div>
                <p class="text-[10px] font-black text-gold-500 uppercase tracking-[0.3em] mb-2">Langkah 2 dari 2</p>
                <h2 class="text-2xl font-black text-navy-900 tracking-tight">Kode Verifikasi</h2>
                <p class="text-slate-400 text-sm font-medium mt-2 leading-relaxed">
                    Masukkan 6-digit kode OTP yang dikirim ke WhatsApp Anda
                </p>
            </div>

            {{-- Alerts --}}
            @if($errors->any())
                <div class="alert-error mb-5 fade-in-up d1">
                    <i class="fas fa-exclamation-circle flex-shrink-0"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif
            @if(session('success'))
                <div class="alert-success mb-5 fade-in-up d1">
                    <i class="fas fa-check-circle flex-shrink-0"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif
            @if(session('demo_otp'))
                <div class="alert-demo mb-5 fade-in-up d1">
                    <div class="flex items-center gap-2 mb-2">
                        <i class="fab fa-whatsapp text-green-500"></i>
                        <p class="text-xs font-black text-navy-900 uppercase tracking-wide">Simulasi Pesan WhatsApp</p>
                    </div>
                    <p class="text-slate-500 text-xs mb-1 font-medium">Kode OTP Anda:</p>
                    <p class="text-3xl font-black text-navy-900 tracking-[0.35em]">{{ session('demo_otp') }}</p>
                </div>
            @endif

            {{-- OTP Form --}}
            <form action="{{ route('register.verifyOtp') }}" method="POST" id="otp-form">
                @csrf

                {{-- 6-box OTP input --}}
                <div class="flex justify-center gap-2.5 mb-8 fade-in-up d2" id="otp-boxes">
                    @for($i = 0; $i < 6; $i++)
                    <input
                        type="text" maxlength="1" inputmode="numeric" pattern="[0-9]"
                        class="otp-box" id="otp-{{ $i }}"
                        autocomplete="one-time-code"
                        aria-label="Digit OTP ke-{{ $i + 1 }}"
                    >
                    @endfor
                    <input type="hidden" name="otp_code" id="otp-hidden">
                </div>

                {{-- Submit --}}
                <div class="fade-in-up d3">
                    <button type="submit" class="btn-gold" id="verify-btn">
                        <i class="fas fa-shield-check mr-2"></i> Verifikasi & Selesai
                    </button>
                </div>
            </form>

            {{-- Resend section --}}
            <div class="mt-8 pt-6 border-t border-slate-100 text-center fade-in-up d4">
                <p class="text-slate-400 text-xs font-medium mb-4">Belum menerima kode?</p>

                {{-- Timer --}}
                <div id="timer-block" class="flex items-center justify-center gap-2 mb-4">
                    <div class="flex items-center gap-1.5 bg-amber-50 border border-amber-200 rounded-xl px-4 py-2.5">
                        <i class="fas fa-clock text-amber-500 text-xs"></i>
                        <span class="text-amber-600 font-black text-sm tracking-widest" id="countdown">01:00</span>
                        <span class="text-amber-500 text-xs font-bold">tersisa</span>
                    </div>
                </div>

                {{-- Resend buttons --}}
                <div id="resend-options" class="hidden flex-col gap-3">
                    <form action="{{ route('register.resendOtp') }}" method="POST">
                        @csrf
                        <input type="hidden" name="method" value="wa">
                        <button type="submit" class="w-full py-3 rounded-xl border-2 border-gold-500 text-gold-600 hover:bg-gold-50 font-black text-xs uppercase tracking-wide transition flex items-center justify-center gap-2">
                            <i class="fab fa-whatsapp text-sm"></i> Kirim Ulang via WhatsApp
                        </button>
                    </form>
                    <form action="{{ route('register.resendOtp') }}" method="POST">
                        @csrf
                        <input type="hidden" name="method" value="call">
                        <button type="submit" class="w-full py-3 rounded-xl border-2 border-slate-200 text-slate-500 hover:bg-slate-50 font-black text-xs uppercase tracking-wide transition flex items-center justify-center gap-2">
                            <i class="fas fa-phone-alt text-xs"></i> Panggil via Telepon
                        </button>
                    </form>
                </div>

                <a href="{{ route('register') }}" class="inline-flex items-center gap-2 text-slate-400 hover:text-navy-900 text-xs uppercase tracking-wider font-bold transition mt-5">
                    <i class="fas fa-arrow-left text-[10px]"></i> Kembali ke Pendaftaran
                </a>
            </div>

        </div>
    </div>

</div>

<script>
    // ── OTP Box auto-advance ──
    const boxes = document.querySelectorAll('.otp-box');
    const hidden = document.getElementById('otp-hidden');

    boxes.forEach((box, idx) => {
        box.addEventListener('input', e => {
            const val = e.target.value.replace(/\D/g, '');
            e.target.value = val;
            if (val) {
                box.classList.add('filled');
                if (idx < boxes.length - 1) boxes[idx + 1].focus();
            } else {
                box.classList.remove('filled');
            }
            collectOtp();
        });
        box.addEventListener('keydown', e => {
            if (e.key === 'Backspace' && !box.value && idx > 0) {
                boxes[idx - 1].focus();
                boxes[idx - 1].value = '';
                boxes[idx - 1].classList.remove('filled');
                collectOtp();
            }
        });
        box.addEventListener('paste', e => {
            e.preventDefault();
            const paste = (e.clipboardData || window.clipboardData).getData('text').replace(/\D/g, '').slice(0, 6);
            paste.split('').forEach((ch, i) => {
                if (boxes[i]) { boxes[i].value = ch; boxes[i].classList.add('filled'); }
            });
            boxes[Math.min(paste.length, boxes.length - 1)].focus();
            collectOtp();
        });
    });

    function collectOtp() {
        hidden.value = Array.from(boxes).map(b => b.value).join('');
    }

    // Auto-submit when all 6 filled
    document.getElementById('otp-form').addEventListener('input', () => {
        if (hidden.value.length === 6) {
            setTimeout(() => document.getElementById('otp-form').submit(), 200);
        }
    });

    // Focus first box
    boxes[0].focus();

    // ── Countdown Timer ──
    let timeLeft = 60;
    const countdownEl = document.getElementById('countdown');
    const timerBlock  = document.getElementById('timer-block');
    const resendOpts  = document.getElementById('resend-options');

    const timerId = setInterval(() => {
        if (timeLeft <= 0) {
            clearInterval(timerId);
            timerBlock.classList.add('hidden');
            resendOpts.classList.remove('hidden');
            resendOpts.classList.add('flex');
        } else {
            timeLeft--;
            const m = Math.floor(timeLeft / 60);
            const s = timeLeft % 60;
            countdownEl.textContent = `0${m}:${s < 10 ? '0' : ''}${s}`;
        }
    }, 1000);
</script>
</body>
</html>
