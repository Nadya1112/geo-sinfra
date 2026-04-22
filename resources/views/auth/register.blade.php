<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun | GEO-SINFRA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style> 
        body { font-family: 'Inter', sans-serif; } 
        .bg-government-gradient { background: radial-gradient(circle at center, #1e40af 0%, #1e1b4b 100%); } 
    </style>
</head>
<body class="antialiased bg-gray-50">
    <div class="flex flex-col md:flex-row h-screen overflow-hidden">
        
        <div class="w-full md:w-1/2 bg-government-gradient flex flex-col items-center justify-center p-12 text-center relative">
            <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')]"></div>
            <div class="relative z-10 text-white">
                <h1 class="text-5xl font-extrabold tracking-tight mb-4 text-center">GEO-SINFRA</h1>
                <p class="text-xl font-light text-blue-100 max-w-sm mx-auto leading-relaxed text-center">Bergabunglah untuk Berkontribusi dalam Pemetaan Kota</p>
                <div class="mt-16 w-12 h-1.5 bg-blue-400 rounded-full mx-auto opacity-50"></div>
            </div>
        </div>

        <div class="w-full md:w-1/2 bg-white flex flex-col items-center justify-center p-8 md:p-12 overflow-y-auto">
            <div class="w-full max-w-md">
                <div class="mb-8 text-center">
                    <h2 class="text-3xl font-extrabold text-[#1e1b4b] mb-2 tracking-tight">Buat Akun</h2>
                    <p class="text-gray-500 text-sm">Lengkapi data untuk akses sistem pemetaan</p>
                </div>

                @if($errors->any())
                    <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 text-sm">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form action="{{ route('register') }}" method="POST" class="space-y-5">
                    @csrf
                    
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" name="name" placeholder="Masukkan nama lengkap" value="{{ old('name') }}" required 
                            class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:bg-white outline-none transition-all text-sm font-medium">
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">Email / NIP <span class="text-red-500">*</span></label>
                        <input type="text" name="email" placeholder="nama@disperkim.go.id atau NIP" value="{{ old('email') }}" required 
                            class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:bg-white outline-none transition-all text-sm font-medium">
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">Pilih Jabatan <span class="text-red-500">*</span></label>
                        <select name="role" required 
                            class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:bg-white outline-none transition-all text-sm font-medium appearance-none">
                            <option value="" disabled selected>Pilih akses peran</option>
                            <option value="surveyor">Surveyor Lapangan</option>
                            <option value="kabid">Kepala Bidang (Kabid)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">Buat Sandi <span class="text-red-500">*</span></label>
                        <input type="password" name="password" placeholder="Minimal 8 karakter" required 
                            class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:bg-white outline-none transition-all text-sm font-medium">
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">Konfirmasi Kata Sandi <span class="text-red-500">*</span></label>
                        <input type="password" name="password_confirmation" placeholder="Ulangi kata sandi" required 
                            class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:bg-white outline-none transition-all text-sm font-medium">
                    </div>

                    <button type="submit" class="w-full py-4 bg-[#5c56e1] text-white text-sm font-bold rounded-xl shadow-lg shadow-blue-100 hover:bg-blue-800 transition-all active:scale-[0.98] uppercase tracking-widest">
                        DAFTAR SEKARANG
                    </button>
                </form>

                <p class="mt-8 text-center text-sm text-gray-500">
                    Sudah punya akun? <a href="{{ route('login') }}" class="text-blue-600 font-bold hover:underline">Masuk di sini</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>