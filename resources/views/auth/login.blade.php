<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk | GEO-SINFRA</title>
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

    <div class="flex flex-col md:flex-row h-screen overflow-hidden">
        
        <!-- Left Banner (Premium Dark UI) -->
        <div class="w-full md:w-1/2 bg-premium-mesh flex flex-col items-center justify-center p-12 text-center relative overflow-hidden">
            <div class="grid-pattern"></div>
            
            <!-- Floating Back Button -->
            <a href="{{ url('/') }}" class="absolute top-6 left-6 z-50 w-12 h-12 bg-white/5 hover:bg-white/15 backdrop-blur-md rounded-2xl border border-white/10 flex items-center justify-center text-white hover:text-gold-500 hover:scale-105 transition-all shadow-xl">
                <i class="fas fa-arrow-left text-sm"></i>
            </a>
            
            <div class="relative z-10 max-w-md">
                <div class="w-24 h-24 mx-auto mb-8 shadow-2xl shadow-navy-950/50 rounded-full overflow-hidden border-2 border-white/10 bg-white">
                    <img src="{{ asset('logo_geo-sinfra.png') }}" class="w-full h-full object-contain" alt="Logo">
                </div>
                
                <h1 class="text-4xl md:text-5xl font-black text-white tracking-tight mb-4 text-center uppercase">
                    GEO-SINFRA
                </h1>
                <p class="text-slate-300 font-medium text-base md:text-lg leading-relaxed max-w-sm mx-auto">
                    Sistem Pemetaan Infrastruktur Permukiman Kota Banjarmasin
                </p>
                <div class="mt-16 w-16 h-1.5 bg-gold-500 rounded-full mx-auto opacity-75"></div>
            </div>
        </div>

        <!-- Right Login Form -->
        <div class="w-full md:w-1/2 bg-white flex flex-col items-center justify-center p-8 md:p-20 overflow-y-auto">
            <div class="w-full max-w-md">
                
                <div class="mb-10 text-center">
                    <h2 class="text-4xl font-black text-navy-900 mb-2 tracking-tight">Masuk</h2>
                    <p class="text-slate-400 font-semibold text-xs uppercase tracking-widest">Silakan masukkan akun Anda</p>
                </div>

                @if($errors->any())
                    <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 text-sm font-semibold rounded-r-xl">
                        <i class="fas fa-exclamation-circle mr-2"></i>{{ $errors->first() }}
                    </div>
                @endif

                <form action="{{ route('login') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2.5 ml-1">
                            Email / Nomor WhatsApp <span class="text-gold-500">*</span>
                        </label>
                        <input type="text" name="login" placeholder="Email atau 0812xxxx" value="{{ old('login') }}" required autocomplete="username"
                            class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-gold-500 focus:border-gold-500 focus:bg-white outline-none transition-all text-sm font-semibold text-navy-900">
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2.5 ml-1">
                            Kata Sandi <span class="text-gold-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="password" name="password" id="password" placeholder="••••••••" required autocomplete="current-password"
                                class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-gold-500 focus:border-gold-500 focus:bg-white outline-none transition-all text-sm font-semibold text-navy-900 pr-12">
                            
                            <button type="button" onclick="togglePassword()" 
                                class="absolute inset-y-0 right-0 pr-5 flex items-center text-slate-400 hover:text-gold-500 transition-colors">
                                <i id="eye-icon" class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2.5 ml-1">
                            Verifikasi Keamanan: {{ $n1 ?? 3 }} + {{ $n2 ?? 5 }} <span class="text-gold-500">*</span>
                        </label>
                        <input type="number" name="captcha" placeholder="Jawaban Anda" required
                            class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-gold-500 focus:border-gold-500 focus:bg-white outline-none transition-all text-sm font-semibold text-navy-900">
                    </div>

                    <button type="submit" 
                        class="btn-shine w-full py-4.5 bg-gradient-to-r from-gold-500 to-gold-600 hover:from-gold-600 hover:to-gold-700 text-white text-xs font-black rounded-2xl shadow-xl shadow-gold-500/10 hover:shadow-gold-500/20 hover:scale-[1.01] transition-all active:scale-[0.98] uppercase tracking-[0.2em] text-center block">
                        MASUK
                    </button>

                    <div class="flex items-center justify-between font-bold text-xs uppercase tracking-wider">
                        <label class="flex items-center gap-2.5 cursor-pointer text-slate-400 hover:text-navy-900 transition">
                            <input type="checkbox" name="remember" class="w-4.5 h-4.5 rounded border-slate-300 text-gold-500 focus:ring-0 cursor-pointer">
                            <span class="text-[10px]">Ingat Saya</span>
                        </label>
                        <a href="{{ route('password.request') }}" class="text-gold-500 hover:text-gold-600 transition-colors text-[10px]">Lupa Password?</a>
                    </div>
                </form>

                <div class="mt-16 pt-8 border-t border-slate-100 text-center font-bold text-xs uppercase tracking-wider">
                    <p class="text-slate-400 text-[10px]">
                        Belum punya akun? 
                        <a href="{{ route('register') }}" class="text-gold-500 font-extrabold hover:text-gold-600 transition-colors ml-1">Buat Akun Baru</a>
                    </p>
                </div>
            </div>
        </div>

    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        }
    </script>

</body>
</html>
