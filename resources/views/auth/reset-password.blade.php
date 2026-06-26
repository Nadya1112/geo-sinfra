<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=1280">
    <title>Atur Ulang Sandi | GEO-SINFRA</title>
    <link rel="icon" href="{{ asset('logo_geo-sinfra.png') }}" type="image/png">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        body { font-family: 'Inter', sans-serif; }
        .bg-government-gradient {
            background: radial-gradient(circle at center, #1e40af 0%, #1e1b4b 100%);
        }
    </style>
</head>
<body class="antialiased bg-gray-50">

    <div class="flex flex-col md:flex-row min-h-screen">
        
        <div class="w-full md:w-1/2 bg-government-gradient flex flex-col items-center justify-center p-12 text-center relative text-white">
            <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')]"></div>
            
            <div class="relative z-10">
                <div class="w-20 h-20 bg-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-8 shadow-2xl border border-white/20">
                    <i class="fas fa-shield-alt text-3xl text-white"></i>
                </div>
                
                <h1 class="text-5xl font-extrabold tracking-tight mb-4 text-center">GEO-SINFRA</h1>
                <p class="text-xl font-light text-blue-100 max-w-sm mx-auto leading-relaxed">
                    Perbarui kata sandi Anda untuk melanjutkan akses sistem.
                </p>
                <div class="mt-16 w-12 h-1.5 bg-blue-400 rounded-full mx-auto opacity-30"></div>
            </div>
        </div>

        <div class="w-full md:w-1/2 bg-white flex flex-col items-center justify-center p-8 md:p-20 relative">
            
            <div class="absolute top-10 left-10 md:left-20">
                <a href="{{ route('login') }}" class="text-gray-300 hover:text-blue-600 transition-all text-2xl">
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>

            <div class="w-full max-w-md">
                
                <div class="mb-6 text-center">
                    <h2 class="text-4xl font-extrabold text-[#1e1b4b] mb-2 tracking-tight">Sandi Baru</h2>
                    
                    <div class="inline-flex items-center gap-2 mt-4 px-3 py-1 bg-red-50 rounded-full border border-red-100">
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-red-500"></span>
                        </span>
                        <span class="text-[9px] font-bold text-red-600 uppercase tracking-widest">
                            Sesi Berakhir: <span id="countdown" class="font-black">05:00</span>
                        </span>
                    </div>
                </div>

                @if($errors->any())
                    <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 text-[10px] font-bold rounded-r-lg shadow-sm">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form id="resetForm" action="{{ route('password.update') }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">

                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">Email Konfirmasi</label>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="admin@disperkim.go.id" required 
                            class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition-all text-sm font-medium">
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">Sandi Baru</label>
                        <div class="relative">
                            <input type="password" name="password" id="password" placeholder="Minimal 8 karakter" required 
                                class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition-all text-sm font-medium pr-12">
                            <button type="button" onclick="togglePass('password', 'eye1')" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400">
                                <i id="eye1" class="fas fa-eye text-xs"></i>
                            </button>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">Ulangi Sandi</label>
                        <div class="relative">
                            <input type="password" name="password_confirmation" id="password_confirm" placeholder="Ulangi sandi baru" required 
                                class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition-all text-sm font-medium pr-12">
                            <button type="button" onclick="togglePass('password_confirm', 'eye2')" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400">
                                <i id="eye2" class="fas fa-eye text-xs"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit" id="submitBtn"
                        class="w-full py-4 bg-[#5c56e1] text-white text-xs font-bold rounded-xl shadow-lg shadow-blue-100 hover:bg-blue-800 transition-all active:scale-[0.98] uppercase tracking-[0.2em]">
                        SIMPAN PERUBAHAN
                    </button>
                </form>

            </div>
        </div>
    </div>

    <script>
        // SCRIPT TIMER
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
                setTimeout(() => { window.location.href = "{{ route('login') }}"; }, 1500);
            } else {
                let minutes = Math.floor(timeLeft / 60);
                let seconds = Math.floor(timeLeft % 60);
                display.innerHTML = `${minutes < 10 ? '0' : ''}${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;
            }
            timeLeft -= 1;
        }, 1000);

        // SCRIPT INTIP PASSWORD
        function togglePass(id, eyeId) {
            const input = document.getElementById(id);
            const eye = document.getElementById(eyeId);
            if (input.type === "password") {
                input.type = "text";
                eye.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                input.type = "password";
                eye.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }
    </script>
</body>
</html>
