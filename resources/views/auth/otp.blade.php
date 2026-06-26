<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=1280">
    <title>Verifikasi OTP | GEO-SINFRA</title>
    <link rel="icon" href="{{ asset('logo_geo-sinfra.png') }}" type="image/png">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                    },
                    colors: {
                        navy: {
                            50: '#f4f4fa',
                            100: '#e9e9f3',
                            200: '#c7c8e3',
                            500: '#6366f1',
                            800: '#1e1b4b',
                            900: '#0f0e2c',
                            950: '#070617',
                        },
                        gold: {
                            50: '#fdfbf7',
                            100: '#fbf7ed',
                            500: '#c5a059',
                            600: '#b38f4a',
                            700: '#9d7c3d',
                        }
                    }
                }
            }
        }
    </script>
    
    <style>
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
        }
        .bg-premium-mesh {
            background: radial-gradient(circle at 80% 20%, rgba(99, 102, 241, 0.12) 0%, transparent 50%),
                        radial-gradient(circle at 20% 80%, rgba(197, 160, 89, 0.1) 0%, transparent 50%),
                        #070617;
        }
        /* Grid background pattern */
        .grid-pattern {
            position: absolute;
            inset: 0;
            background-image: linear-gradient(to right, rgba(255,255,255,0.02) 1px, transparent 1px),
                              linear-gradient(to bottom, rgba(255,255,255,0.02) 1px, transparent 1px);
            background-size: 40px 40px;
            mask-image: radial-gradient(ellipse at center, black, transparent 80%);
            pointer-events: none;
        }
        /* Shine effect for button */
        .btn-shine {
            position: relative;
            overflow: hidden;
        }
        .btn-shine::after {
            content: '';
            position: absolute;
            top: -50%; left: -60%; width: 30%; height: 200%;
            background: rgba(255, 255, 255, 0.25);
            transform: rotate(30deg);
            transition: none;
        }
        .btn-shine:hover::after {
            left: 120%;
            transition: all 0.6s ease-in-out;
        }
    </style>
</head>
<body class="antialiased bg-slate-50 font-sans">

    <div class="flex flex-col md:flex-row min-h-screen">
        
        <!-- Left Banner (Premium Dark UI) -->
        <div class="w-full md:w-1/2 bg-premium-mesh flex flex-col items-center justify-center p-12 text-center relative overflow-hidden">
            <div class="grid-pattern"></div>
            
            <div class="relative z-10 max-w-md">
                <div class="w-20 h-20 bg-navy-900 rounded-2xl flex items-center justify-center mx-auto mb-8 shadow-2xl shadow-navy-950/50 border border-white/10 text-gold-500">
                    <i class="fas fa-shield-alt text-3xl"></i>
                </div>
                
                <h1 class="text-4xl font-black text-white tracking-tight mb-4 text-center uppercase">
                    Verifikasi Akun
                </h1>
                <p class="text-slate-300 font-medium text-sm md:text-base leading-relaxed max-w-sm mx-auto">
                    Untuk menyelesaikan pendaftaran, kami telah mengirimkan kode OTP ke WhatsApp Anda.
                </p>
                <div class="mt-16 w-16 h-1.5 bg-gold-500 rounded-full mx-auto opacity-75"></div>
            </div>
        </div>

        <!-- Right OTP Form -->
        <div class="w-full md:w-1/2 bg-white flex flex-col items-center justify-center p-8 md:p-20 ">
            <div class="w-full max-w-md">
                
                <div class="mb-10 text-center">
                    <h2 class="text-4xl font-black text-navy-900 mb-2 tracking-tight">Verifikasi Pendaftaran</h2>
                    <p class="text-slate-400 font-semibold text-xs uppercase tracking-widest">Masukkan 6-digit kode OTP Anda</p>
                </div>

                @if($errors->any())
                    <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 text-sm font-semibold rounded-r-xl">
                        <i class="fas fa-exclamation-circle mr-2"></i>{{ $errors->first() }}
                    </div>
                @endif
                
                @if(session('success'))
                    <div class="mb-6 p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 text-sm font-semibold rounded-r-xl">
                        <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                    </div>
                @endif

                @if(session('demo_otp'))
                    <div class="mb-6 p-5 bg-gold-50 border border-gold-200 text-gold-700 text-sm rounded-xl shadow-inner text-center">
                        <p class="font-bold mb-1"><i class="fas fa-mobile-alt mr-2"></i> Simulasi Pesan WhatsApp</p>
                        <p>Kode OTP Anda adalah: <span class="text-2xl font-black text-navy-900 tracking-widest block mt-2">{{ session('demo_otp') }}</span></p>
                    </div>
                @endif

                <form action="{{ route('register.verifyOtp') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2.5 ml-1 text-center">
                            Kode OTP <span class="text-gold-500">*</span>
                        </label>
                        <input type="text" name="otp_code" placeholder="------" required maxlength="6" autofocus
                            class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-gold-500 focus:border-gold-500 focus:bg-white outline-none transition-all text-2xl tracking-[0.5em] text-center font-black text-navy-900">
                    </div>

                    <button type="submit" 
                        class="btn-shine w-full py-4.5 bg-gradient-to-r from-gold-500 to-gold-600 hover:from-gold-600 hover:to-gold-700 text-white text-xs font-black rounded-2xl shadow-xl shadow-gold-500/10 hover:shadow-gold-500/20 hover:scale-[1.01] transition-all active:scale-[0.98] uppercase tracking-[0.2em] text-center block">
                        VERIFIKASI & SELESAI
                    </button>
                </form>

                <div class="mt-8 pt-6 border-t border-slate-100 text-center">
                    <p class="text-slate-400 text-xs font-medium mb-3">
                        Belum menerima kode OTP?
                    </p>
                    
                    <!-- Timer Block -->
                    <div id="otp-timer-container" class="text-gold-600 font-bold text-sm tracking-widest mb-4">
                        Mohon tunggu <span id="countdown">01:00</span>
                    </div>

                    <!-- Resend Options (Hidden Initially) -->
                    <div id="resend-options" class="hidden flex-col gap-3 items-center justify-center">
                        <form action="{{ route('register.resendOtp') }}" method="POST" class="w-full">
                            @csrf
                            <input type="hidden" name="method" value="wa">
                            <button type="submit" class="w-full py-3 border-2 border-gold-500 text-gold-600 hover:bg-gold-50 hover:text-gold-700 font-extrabold rounded-xl transition-all text-[10px] uppercase tracking-wider flex items-center justify-center gap-2">
                                <i class="fab fa-whatsapp text-sm"></i> Kirim Ulang via WhatsApp
                            </button>
                        </form>
                        
                        <form action="{{ route('register.resendOtp') }}" method="POST" class="w-full">
                            @csrf
                            <input type="hidden" name="method" value="call">
                            <button type="submit" class="w-full py-3 border-2 border-slate-200 text-slate-500 hover:border-slate-300 hover:bg-slate-50 hover:text-navy-900 font-extrabold rounded-xl transition-all text-[10px] uppercase tracking-wider flex items-center justify-center gap-2">
                                <i class="fas fa-phone-alt text-sm"></i> Panggil via Telepon
                            </button>
                        </form>
                    </div>
                    
                    <div class="mt-6">
                        <a href="{{ route('register') }}" class="text-slate-400 hover:text-navy-900 transition-colors text-[10px] uppercase tracking-wider font-bold">
                            <i class="fas fa-arrow-left mr-1"></i> Kembali ke Pendaftaran
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Script Timer -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let timeLeft = 60; // 60 detik (1 menit)
            const countdownEl = document.getElementById('countdown');
            const timerContainer = document.getElementById('otp-timer-container');
            const resendOptions = document.getElementById('resend-options');

            const timerId = setInterval(function() {
                if (timeLeft <= 0) {
                    clearInterval(timerId);
                    timerContainer.classList.add('hidden');
                    resendOptions.classList.remove('hidden');
                    resendOptions.classList.add('flex');
                } else {
                    timeLeft--;
                    const minutes = Math.floor(timeLeft / 60);
                    const seconds = timeLeft % 60;
                    countdownEl.textContent = `0${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;
                }
            }, 1000);
        });
    </script>
</body>
</html>
