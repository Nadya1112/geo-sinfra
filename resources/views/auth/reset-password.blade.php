<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atur Ulang Sandi | GEO-SINFRA</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Inter', sans-serif; }
        .bg-government-gradient {
            background: radial-gradient(circle at center, #1e40af 0%, #1e1b4b 100%);
        }
        .timer-pulse {
            animation: pulse-red 2s infinite;
        }
        @keyframes pulse-red {
            0% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.4); }
            70% { box-shadow: 0 0 0 10px rgba(239, 68, 68, 0); }
            100% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0); }
        }
    </style>
</head>
<body class="antialiased bg-gray-50">

    <div class="flex flex-col md:flex-row h-screen overflow-hidden">
        
        <div class="w-full md:w-1/2 bg-government-gradient flex flex-col items-center justify-center p-12 text-center relative text-white">
            <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')]"></div>
            
            <div class="relative z-10">
                <div class="w-20 h-20 bg-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-8 shadow-2xl border border-white/20">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                </div>
                
                <h1 class="text-5xl font-extrabold tracking-tight mb-4">GEO-SINFRA</h1>
                <p class="text-xl font-light text-blue-100 max-w-sm mx-auto leading-relaxed">
                    Sistem Pemetaan Infrastruktur Dinas Perumahan dan Kawasan Permukiman.
                </p>
            </div>
        </div>

        <div class="w-full md:w-1/2 bg-white flex flex-col items-center justify-center p-8 md:p-20">
            <div class="w-full max-w-md">
                
                <div class="mb-8 text-center">
                    <h2 class="text-4xl font-extrabold text-[#1e1b4b] mb-2 tracking-tight">Sandi Baru</h2>
                    <p class="text-gray-500 font-medium text-sm">Masukan sandi baru untuk mengamankan akun Anda.</p>
                </div>

                <div class="mb-8 flex flex-col items-center justify-center py-4 bg-red-50 border border-red-100 rounded-2xl text-red-600 shadow-sm timer-pulse">
                    <div class="flex items-center space-x-2 mb-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="text-[10px] font-bold uppercase tracking-[0.2em]">Batas Waktu Sesi</span>
                    </div>
                    <div id="countdown" class="text-3xl font-black font-mono tracking-tighter text-red-600">05:00</div>
                </div>

                @if($errors->any())
                    <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 text-xs rounded-r-lg shadow-sm font-medium">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form id="resetForm" action="{{ route('password.update') }}" method="POST" class="space-y-5">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">

                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1 text-left">Email Konfirmasi <span class="text-red-500">*</span></label>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="admin@disperkim.go.id" required 
                            class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition-all text-sm font-medium text-left">
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1 text-left">Buat Sandi Baru <span class="text-red-500">*</span></label>
                        <input type="password" name="password" placeholder="Minimal 8 karakter" required 
                            class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition-all text-sm font-medium text-left">
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1 text-left">Ulangi Sandi <span class="text-red-500">*</span></label>
                        <input type="password" name="password_confirmation" placeholder="Ulangi sandi baru" required 
                            class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition-all text-sm font-medium text-left">
                    </div>

                    <button type="submit" id="submitBtn"
                        class="w-full py-4 bg-[#5c56e1] text-white text-sm font-bold rounded-xl shadow-lg shadow-blue-100 hover:bg-blue-800 transition-all active:scale-[0.98] uppercase tracking-widest">
                        SIMPAN PERUBAHAN
                    </button>
                </form>

            </div>
        </div>
    </div>

    <script>
        // PERBAIKAN: Gunakan Math.min agar tidak lebih dari 300, dan Math.floor agar angka bulat
        let timeLeft = Math.min(300, Math.floor({{ $sisaWaktu ?? 300 }})); 
        
        const display = document.querySelector('#countdown');
        const submitBtn = document.querySelector('#submitBtn');

        const timer = setInterval(function() {
            if (timeLeft <= 0) {
                clearInterval(timer);
                display.innerHTML = "00:00";
                
                if(submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = "WAKTU HABIS";
                    submitBtn.classList.replace('bg-[#5c56e1]', 'bg-gray-400');
                    submitBtn.classList.add('cursor-not-allowed');
                }

                // Redirect otomatis ke LOGIN
                setTimeout(() => {
                    window.location.href = "{{ route('login') }}";
                }, 1500);
            } else {
                // Menghitung Menit dan Detik (MM:SS) secara bulat
                let minutes = Math.floor(timeLeft / 60);
                let seconds = Math.floor(timeLeft % 60);
                
                // Menampilkan format MM:SS yang rapi tanpa desimal
                display.innerHTML = `${minutes < 10 ? '0' : ''}${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;
                
                if (timeLeft <= 30) {
                    display.classList.add('animate-pulse');
                }
            }
            timeLeft -= 1;
        }, 1000);
    </script>

</body>
</html>