<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password | GEO-SINFRA</title>
    
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
            
            <div class="relative z-10 text-white">
                <div class="w-20 h-20 bg-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-8 shadow-2xl shadow-blue-900/50 border border-white/20">
                    <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                </div>
                
                <h1 class="text-5xl font-extrabold tracking-tight mb-4 text-center">GEO-SINFRA</h1>
                <p class="text-xl font-light text-blue-100 max-w-sm mx-auto leading-relaxed">
                    Pemulihan Akses Sistem Pemetaan Infrastruktur
                </p>
                <div class="mt-16 w-12 h-1.5 bg-blue-400 rounded-full mx-auto opacity-50"></div>
            </div>
        </div>

        <div class="w-full md:w-1/2 bg-white flex flex-col items-center justify-center p-8 md:p-20">
            <div class="w-full max-w-md text-center">
                
                <div class="mb-10">
                    <h2 class="text-4xl font-extrabold text-[#1e1b4b] mb-2 tracking-tight">Lupa Password?</h2>
                </div>

                @if (session('status'))
                    <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 text-sm text-left">
                        Link reset password telah dikirim ke email Anda.
                    </div>
                @endif

                <form action="#" method="POST" class="space-y-6">
                    @csrf
                    <div class="text-left">
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] mb-2 ml-1">
                            Email Terdaftar <span class="text-red-500">*</span>
                        </label>
                        <input type="email" name="email" placeholder="nama@disperkim.go.id" required
                            class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:bg-white outline-none transition-all text-sm font-medium">
                    </div>

                    <button type="submit" 
                        class="w-full py-4 bg-[#5c56e1] text-white text-sm font-bold rounded-xl shadow-lg shadow-blue-200 hover:bg-blue-800 transition-all active:scale-[0.98] uppercase tracking-widest">
                        KIRIM LINK PEMULIHAN
                    </button>

                    <div class="mt-8">
                        <a href="{{ route('login') }}" class="inline-flex items-center text-sm font-bold text-blue-600 hover:underline gap-2 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Kembali ke Halaman Masuk
                        </a>
                    </div>
                </form>

                <div class="mt-16 pt-8 border-t border-gray-100">
                    <p class="text-xs text-gray-400">
                        &copy; 2026 Dinas Perumahan dan Kawasan Permukiman Kota Banjarmasin.
                    </p>
                </div>
            </div>
        </div>

    </div>

</body>
</html>