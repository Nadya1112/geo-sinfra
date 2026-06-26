<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Tim Teknis | GEO-SINFRA</title>
    <link rel="icon" href="{{ asset('logo_geo-sinfra.png') }}" type="image/png">
    <script src="https://cdn.tailwindcss.com"></script>
            <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: { sans: ['Plus Jakarta Sans', 'sans-serif'] },
                    colors: {
                        navy: { 50:'#f4f4fa', 100:'#e9e9f3', 200:'#c7c8e3', 300:'#9fb3c8', 400:'#829ab1', 500:'#6366f1', 600:'#486581', 700:'#334e68', 800:'#1e1b4b', 900:'#0f0e2c', 950:'#070617' },
                        gold: { 50:'#fdfbf7', 100:'#fbf7ed', 200:'#eed9b9', 300:'#e5c292', 400:'#dba665', 500:'#c5a059', 600:'#b38f4a', 700:'#9d7c3d', 800:'#7c5327', 900:'#644422', 950:'#382310' }
                    }
                }
            }
        }
    </script>
    <script>
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
    <script>
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style> body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
<style>
    @media (min-width: 768px) { html { zoom: 0.9 !important; } }
    @media (max-width: 767px) { html { zoom: 0.5 !important; } }
</style>
</head>
<body class="bg-slate-50 dark:bg-[#0f0e2c] flex h-screen overflow-hidden text-slate-800 dark:text-white text-left font-sans dark:bg-navy-950 transition-colors duration-300">

    @include('tim_teknis.partials.sidebar')

    <main class="flex-1 flex flex-col h-screen overflow-hidden">
        {{-- ── Header ── --}}
        <header class="bg-white/80 dark:bg-[#1e1b4b]/80 backdrop-blur-xl border-b border-slate-100 dark:border-white/10 px-8 py-5 flex justify-between items-center z-40 shrink-0">
            <div class="flex items-center gap-4">
                <a href="{{ route('tim_teknis.dashboard') }}" class="w-10 h-10 flex items-center justify-center bg-white dark:bg-[#1e1b4b] border border-slate-200 dark:border-white/20 text-slate-400 rounded-xl hover:bg-gold-50 hover:text-gold-600 hover:border-gold-200 transition-all shadow-sm">
                    <i class="fas fa-arrow-left text-sm"></i>
                </a>
                <div>
                    <p class="text-xs font-black text-gold-500 uppercase tracking-[0.2em] mb-1">Pengaturan Akun</p>
                    <h2 class="text-xl font-black text-navy-900 dark:text-white leading-none">Profil Saya</h2>
                </div>
            </div>
            
            <div class="flex items-center gap-6">
                <div class="text-right hidden sm:block">
                    <p class="text-sm font-black text-navy-900 dark:text-white" id="mini-clock">00:00 WITA</p>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-tighter">{{ now()->translatedFormat('l, d F Y') }}</p>
                </div>
            </div>
        </header>

        <div class="flex-1 overflow-y-auto custom-scrollbar p-8 pb-16">
            <div class="max-w-4xl mx-auto">
                @if(session('success'))
                <div class="mb-6 px-6 py-4 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-100 dark:border-emerald-500/20 text-emerald-700 dark:text-emerald-400 rounded-2xl flex items-center gap-3 shadow-sm">
                    <i class="fas fa-check-circle"></i>
                    <p class="text-xs font-bold">{{ session('success') }}</p>
                </div>
                @endif

                @if($errors->any())
                <div class="mb-6 px-6 py-4 bg-red-50 border border-red-100 text-red-700 rounded-2xl shadow-sm">
                    <ul class="list-disc list-inside text-xs font-bold">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <form action="{{ route('tim_teknis.profile.update') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    @csrf
                    @method('PUT')

                    {{-- Foto Profil --}}
                    <div class="lg:col-span-1">
                        <div class="bg-white dark:bg-[#1e1b4b] rounded-[2.5rem] p-8 border border-slate-100 dark:border-white/10 shadow-sm text-center relative overflow-hidden">
                            <div class="absolute top-0 left-0 w-full h-24 bg-navy-900 rounded-t-[2.5rem]"></div>
                            
                            <div class="relative w-32 h-32 mx-auto mb-6 mt-4">
                                <div class="w-full h-full rounded-full bg-gold-50 border-4 border-white shadow-xl overflow-hidden flex items-center justify-center relative z-10">
                                    @if(auth()->user()->profile_photo)
                                        <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}" id="preview-photo" class="w-full h-full object-cover">
                                    @else
                                        <i class="fas fa-camera text-4xl text-gold-300" id="placeholder-icon"></i>
                                        <img id="preview-photo" class="w-full h-full object-cover hidden">
                                    @endif
                                </div>
                                <label for="profile_photo" class="absolute bottom-0 right-0 z-20 w-10 h-10 bg-gold-500 text-white rounded-full flex items-center justify-center shadow-lg cursor-pointer hover:bg-gold-600 transition-all border-4 border-white">
                                    <i class="fas fa-camera text-xs"></i>
                                    <input type="file" name="profile_photo" id="profile_photo" class="hidden" accept="image/*" onchange="previewImage(this)">
                                </label>
                            </div>
                            
                            <h4 class="font-black text-navy-900 dark:text-white text-lg uppercase tracking-wider">{{ auth()->user()->name }}</h4>
                            <div class="inline-flex items-center gap-1.5 px-3 py-1 bg-navy-50 border border-navy-100 rounded-lg mt-2">
                                <i class="fas fa-shield-alt text-gold-500 text-xs"></i>
                                <p class="text-xs font-black text-navy-900 dark:text-white uppercase tracking-widest">{{ auth()->user()->role }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Detail Akun --}}
                    <div class="lg:col-span-2">
                        <div class="bg-white dark:bg-[#1e1b4b] rounded-[2.5rem] p-10 border border-slate-100 dark:border-white/10 shadow-sm">
                            <div class="mb-8 border-b border-slate-100 dark:border-white/10 pb-5">
                                <h3 class="text-lg font-black text-navy-900 dark:text-white uppercase tracking-wider">Informasi Pribadi</h3>
                                <p class="text-xs text-slate-400 font-semibold mt-1">Perbarui informasi dasar dan kredensial akun Pengawas Anda</p>
                            </div>

                            <div class="space-y-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-xs font-black text-navy-900 dark:text-white uppercase tracking-widest mb-2">Nama Lengkap</label>
                                        <input type="text" name="name" value="{{ auth()->user()->name }}" 
                                               class="w-full px-5 py-3.5 bg-slate-50 dark:bg-[#0f0e2c] border border-slate-200 dark:border-white/20 rounded-2xl text-sm font-semibold outline-none focus:border-gold-500 focus:ring-4 focus:ring-gold-500/10 transition-all text-navy-900 dark:text-white" required>
                                    </div>

                                    <div>
                                        <label class="block text-xs font-black text-navy-900 dark:text-white uppercase tracking-widest mb-2">Alamat Email</label>
                                        <input type="email" name="email" value="{{ auth()->user()->email }}" 
                                               class="w-full px-5 py-3.5 bg-slate-50 dark:bg-[#0f0e2c] border border-slate-200 dark:border-white/20 rounded-2xl text-sm font-semibold outline-none focus:border-gold-500 focus:ring-4 focus:ring-gold-500/10 transition-all text-navy-900 dark:text-white" required>
                                    </div>
                                </div>

                                <div class="pt-6 border-t border-slate-100 dark:border-white/10">
                                    <div class="flex items-center gap-3 mb-5">
                                        <div class="w-8 h-8 bg-slate-100 rounded-lg flex items-center justify-center text-slate-400">
                                            <i class="fas fa-lock text-xs"></i>
                                        </div>
                                        <div>
                                            <h4 class="text-sm font-black text-navy-900 dark:text-white uppercase tracking-wider">Keamanan Akun</h4>
                                            <p class="text-xs font-bold text-slate-400 italic">Kosongkan jika tidak ingin mengubah kata sandi</p>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label class="block text-xs font-black text-navy-900 dark:text-white uppercase tracking-widest mb-2">Kata Sandi Baru</label>
                                            <input type="password" name="password" 
                                                   class="w-full px-5 py-3.5 bg-slate-50 dark:bg-[#0f0e2c] border border-slate-200 dark:border-white/20 rounded-2xl text-sm font-semibold outline-none focus:border-gold-500 focus:ring-4 focus:ring-gold-500/10 transition-all text-navy-900 dark:text-white placeholder:text-slate-300" placeholder="••••••••">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-black text-navy-900 dark:text-white uppercase tracking-widest mb-2">Konfirmasi Sandi</label>
                                            <input type="password" name="password_confirmation" 
                                                   class="w-full px-5 py-3.5 bg-slate-50 dark:bg-[#0f0e2c] border border-slate-200 dark:border-white/20 rounded-2xl text-sm font-semibold outline-none focus:border-gold-500 focus:ring-4 focus:ring-gold-500/10 transition-all text-navy-900 dark:text-white placeholder:text-slate-300" placeholder="••••••••">
                                        </div>
                                    </div>
                                </div>

                                <div class="pt-8">
                                    <button type="submit" class="w-full bg-navy-900 text-white py-4 rounded-2xl font-black shadow-xl shadow-navy-900/10 hover:bg-gold-500 hover:shadow-gold-500/20 transition-all tracking-widest text-sm uppercase group flex items-center justify-center gap-3">
                                        Simpan Perubahan <i class="fas fa-save group-hover:scale-110 transition-transform"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <script>
        function updateClock() {
            const now = new Date();
            const options = { timeZone: 'Asia/Makassar', hour: '2-digit', minute: '2-digit', hour12: false };
            const timeString = new Intl.DateTimeFormat('id-ID', options).format(now);
            const el = document.getElementById('mini-clock');
            if (el) el.textContent = timeString.replace('.', ':') + ' WITA';
        }
        setInterval(updateClock, 1000); updateClock();

        function previewImage(input) {
            const preview = document.getElementById('preview-photo');
            const placeholder = document.getElementById('placeholder-icon');
            
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    if (placeholder) placeholder.classList.add('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</body>
</html>
