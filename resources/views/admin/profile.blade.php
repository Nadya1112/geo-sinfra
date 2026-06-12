<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya | Admin SINFRA</title>
    <link rel="icon" href="{{ asset('logo_geo-sinfra.png') }}" type="image/png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Plus Jakarta Sans', 'sans-serif'] },
                    colors: {
                        navy: { 50:'#f4f4fa', 100:'#e9e9f3', 500:'#6366f1', 800:'#1e1b4b', 900:'#0f0e2c', 950:'#070617' },
                        gold: { 50:'#fdfbf7', 100:'#fbf7ed', 500:'#c5a059', 600:'#b38f4a', 700:'#9d7c3d' }
                    }
                }
            }
        }
    </script>
    <style> 
        body { font-family: 'Plus Jakarta Sans', sans-serif; } 
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    </style>
</head>
<body class="bg-slate-50 flex h-screen overflow-hidden text-slate-800 font-sans">

    @include('admin.partials.sidebar')

    <main class="flex-1 flex flex-col h-screen overflow-hidden">
        {{-- ── Header ── --}}
        <header class="bg-white/80 backdrop-blur-xl border-b border-slate-100 px-8 py-5 flex justify-between items-center z-40 shrink-0">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.dashboard') }}" class="w-10 h-10 flex items-center justify-center bg-white border border-slate-200 text-slate-400 rounded-xl hover:bg-gold-50 hover:text-gold-600 hover:border-gold-200 transition-all shadow-sm">
                    <i class="fas fa-arrow-left text-sm"></i>
                </a>
                <div>
                    <p class="text-[10px] font-black text-gold-500 uppercase tracking-[0.2em] mb-1">Administrator Portal</p>
                    <h2 class="text-xl font-black text-navy-900 leading-none">Profil Saya</h2>
                </div>
            </div>
            
            <div class="flex items-center gap-6">
                <div class="text-right hidden sm:block">
                    <p class="text-[11px] font-black text-navy-900" id="mini-clock">00:00 WITA</p>
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-tighter">{{ now()->translatedFormat('l, d F Y') }}</p>
                </div>
            </div>
        </header>

        <div class="flex-1 overflow-y-auto custom-scrollbar p-8 pb-16">
            <div class="max-w-4xl mx-auto">
                @if(session('success'))
                <div class="mb-6 px-6 py-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-2xl flex items-center gap-3 shadow-sm">
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

                <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    @csrf
                    @method('PUT')

                    {{-- Foto Profil --}}
                    <div class="lg:col-span-1">
                        <div class="bg-white rounded-[2.5rem] p-8 border border-slate-100 shadow-sm text-center relative overflow-hidden">
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
                            
                            <h4 class="font-black text-navy-900 text-lg uppercase tracking-wider">{{ auth()->user()->name }}</h4>
                            <div class="inline-flex items-center gap-1.5 px-3 py-1 bg-navy-50 border border-navy-100 rounded-lg mt-2">
                                <i class="fas fa-shield-alt text-gold-500 text-[10px]"></i>
                                <p class="text-[10px] font-black text-navy-900 uppercase tracking-widest">{{ auth()->user()->role }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Detail Akun --}}
                    <div class="lg:col-span-2">
                        <div class="bg-white rounded-[2.5rem] p-10 border border-slate-100 shadow-sm">
                            <div class="mb-8 border-b border-slate-100 pb-5">
                                <h3 class="text-lg font-black text-navy-900 uppercase tracking-wider">Informasi Pribadi</h3>
                                <p class="text-xs text-slate-400 font-semibold mt-1">Perbarui informasi dasar dan kredensial akun Administrator Anda</p>
                            </div>

                            <div class="space-y-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-[10px] font-black text-navy-900 uppercase tracking-widest mb-2">Nama Lengkap</label>
                                        <input type="text" name="name" value="{{ auth()->user()->name }}" 
                                               class="w-full px-5 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-semibold outline-none focus:border-gold-500 focus:ring-4 focus:ring-gold-500/10 transition-all text-navy-900" required>
                                    </div>

                                    <div>
                                        <label class="block text-[10px] font-black text-navy-900 uppercase tracking-widest mb-2">Alamat Email</label>
                                        <input type="email" name="email" value="{{ auth()->user()->email }}" 
                                               class="w-full px-5 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-semibold outline-none focus:border-gold-500 focus:ring-4 focus:ring-gold-500/10 transition-all text-navy-900" required>
                                    </div>
                                </div>

                                <div class="pt-6 border-t border-slate-100">
                                    <div class="flex items-center gap-3 mb-5">
                                        <div class="w-8 h-8 bg-slate-100 rounded-lg flex items-center justify-center text-slate-400">
                                            <i class="fas fa-lock text-xs"></i>
                                        </div>
                                        <div>
                                            <h4 class="text-sm font-black text-navy-900 uppercase tracking-wider">Keamanan Akun</h4>
                                            <p class="text-[10px] font-bold text-slate-400 italic">Kosongkan jika tidak ingin mengubah kata sandi</p>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label class="block text-[10px] font-black text-navy-900 uppercase tracking-widest mb-2">Kata Sandi Baru</label>
                                            <input type="password" name="password" 
                                                   class="w-full px-5 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-semibold outline-none focus:border-gold-500 focus:ring-4 focus:ring-gold-500/10 transition-all text-navy-900 placeholder:text-slate-300" placeholder="••••••••">
                                        </div>
                                        <div>
                                            <label class="block text-[10px] font-black text-navy-900 uppercase tracking-widest mb-2">Konfirmasi Sandi</label>
                                            <input type="password" name="password_confirmation" 
                                                   class="w-full px-5 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-semibold outline-none focus:border-gold-500 focus:ring-4 focus:ring-gold-500/10 transition-all text-navy-900 placeholder:text-slate-300" placeholder="••••••••">
                                        </div>
                                    </div>
                                </div>

                                <div class="pt-8">
                                    <button type="submit" class="w-full bg-navy-900 text-white py-4 rounded-2xl font-black shadow-xl shadow-navy-900/10 hover:bg-gold-500 hover:shadow-gold-500/20 transition-all tracking-widest text-[11px] uppercase group flex items-center justify-center gap-3">
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
