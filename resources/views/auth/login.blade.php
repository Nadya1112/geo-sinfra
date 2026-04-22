<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk | GEO-SINFRA</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Inter', sans-serif; }
        .bg-government-gradient {
            background: radial-gradient(circle at center, #1e40af 0%, #1e1b4b 100%);
        }
    </style>
</head>
<body class="antialiased bg-gray-50">

    <div class="flex flex-col md:flex-row h-screen overflow-hidden">
        
        <div class="w-full md:w-1/2 bg-government-gradient flex flex-col items-center justify-center p-12 text-center relative">
            <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')]"></div>
            
            <div class="relative z-10">
                <div class="w-20 h-20 bg-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-8 shadow-2xl shadow-blue-900/50 border border-white/20">
                    <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.553-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-1.553-.894L15 9"></path>
                    </svg>
                </div>
                
                <h1 class="text-5xl font-extrabold text-white tracking-tight mb-4 text-center">GEO-SINFRA</h1>
                <p class="text-xl font-light text-blue-100 max-w-sm mx-auto leading-relaxed">
                    Sistem Pemetaan Infrastruktur Permukiman Kota Banjarmasin
                </p>
                <div class="mt-16 w-12 h-1.5 bg-blue-400 rounded-full mx-auto opacity-50"></div>
            </div>
        </div>

        <div class="w-full md:w-1/2 bg-white flex flex-col items-center justify-center p-8 md:p-20">
            <div class="w-full max-w-md">
                
                <div class="mb-10 text-center">
                    <h2 class="text-4xl font-extrabold text-[#1e1b4b] mb-2 tracking-tight">Masuk</h2>
                    <p class="text-gray-500 font-medium">Silakan masukkan akun Anda</p>
                </div>

                @if($errors->any())
                    <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 text-sm">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form action="{{ route('login') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] mb-2 ml-1">
                            Email / NIP <span class="text-red-500">*</span>
                        </label>
                        <input type="email" name="email" placeholder="nama@disperkim.go.id" value="{{ old('email') }}" required
                            class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:bg-white outline-none transition-all text-sm font-medium">
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] mb-2 ml-1">
                            Kata Sandi <span class="text-red-500">*</span>
                        </label>
                        <input type="password" name="password" placeholder="••••••••" required
                            class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:bg-white outline-none transition-all text-sm font-medium">
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] mb-2 ml-1">
                            Verifikasi Keamanan: {{ $n1 }} + {{ $n2 }} <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="captcha" placeholder="Jawaban Anda" required
                            class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:bg-white outline-none transition-all text-sm font-medium">
                    </div>

                    <button type="submit" 
                        class="w-full py-4 bg-[#5c56e1] text-white text-sm font-bold rounded-xl shadow-lg shadow-blue-200 hover:bg-blue-800 transition-all active:scale-[0.98] uppercase tracking-widest text-center">
                        LOGIN SEKARANG
                    </button>

                    <div class="flex items-center justify-between font-semibold text-xs">
                        <label class="flex items-center gap-2 cursor-pointer text-gray-500 hover:text-gray-700 transition">
                            <input type="checkbox" name="remember" class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-0">
                            <span>Ingat Saya</span>
                        </label>
                        <a href="{{ route('password.request') }}" class="text-blue-600 hover:underline">Lupa Password?</a>
                    </div>
                </form>

                <div class="mt-16 pt-8 border-t border-gray-100 text-center">
                    <p class="text-gray-500 text-sm">
                        Belum punya akun? 
                        <a href="{{ route('register') }}" class="text-blue-600 font-bold hover:underline ml-1">Buat Akun Baru</a>
                    </p>
                </div>
            </div>
        </div>

    </div>

</body>
</html>