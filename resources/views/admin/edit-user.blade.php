<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pengguna | Admin SINFRA</title>
    <link rel="icon" href="{{ asset('logo_geo-sinfra.png') }}" type="image/png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            darkMode: 'class',
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
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    </style>
<style>
    @media (min-width: 768px) { html { font-size: 14px; } }
    @media (max-width: 767px) { html { font-size: 12px; } }
</style>
</head>
<body class="bg-navy-50 dark:bg-navy-950 text-slate-800 dark:text-slate-200 antialiased flex overflow-hidden h-screen transition-colors duration-300">

        @include('admin.partials.sidebar')

    <main class="flex-1 flex flex-col h-screen overflow-hidden text-left font-sans">
        <header class="bg-white/85 dark:bg-navy-950/80 backdrop-blur-xl border-b border-slate-100 dark:border-white/5 px-4  md:px-8 py-4 flex justify-between items-center z-40 text-left transition-colors duration-300">
            <div class="flex items-center gap-4 text-left">
                <a href="{{ route('admin.users') }}" 
                   class="hidden md:flex w-10 h-10 bg-white border border-slate-100 rounded-xl flex items-center justify-center text-slate-400 hover:text-gold-500 hover:border-gold-500/20 hover:shadow-lg hover:shadow-gold-500/5 transition-all group"
                   title="Kembali ke Daftar">
                    <i class="fas fa-arrow-left text-xs group-hover:-translate-x-1 transition-transform"></i>
                </a>
                <div class="text-left">
                    <p class="text-xs font-black text-gold-500 uppercase tracking-wider mb-1">Administrator Portal</p>
                    <h2 class="text-xl font-black text-navy-900 dark:text-white leading-none">Edit Data Pengguna</h2>
                </div>
            </div>

            <div class="flex items-center gap-6 text-left">
                <div class="text-right">
                    <p class="text-xs font-black text-navy-900 dark:text-white" id="mini-clock">00:00 WITA</p>
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-tighter">{{ now()->translatedFormat('d M Y') }}</p>
                </div>
                <div class="h-8 w-[1px] bg-slate-100"></div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.profile') }}" class="text-right group hidden md:block">
                        <p class="text-sm font-black text-navy-900 dark:text-white leading-none uppercase group-hover:text-gold-500 transition-all max-w-[100px] sm:max-w-[150px] md:max-w-[300px] truncate">{{ auth()->user()->name }}</p>
                        <p class="text-[10px] md:text-xs font-bold text-emerald-500 uppercase mt-0.5">Online</p>
                    </a>
                    <a href="{{ route('admin.profile') }}" class="w-10 h-10 bg-navy-900 rounded-xl flex items-center justify-center text-gold-500 border border-white/10 overflow-hidden hover:shadow-lg hover:shadow-navy-950/20 transition-all shadow-md">
                        @if(auth()->user()->profile_photo)
                            <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}" class="w-full h-full object-cover">
                        @else
                            <i class="fas fa-user-circle text-xl"></i>
                        @endif
                    </a>
                </div>
            </div>
        </header>

        <div class="flex-1 p-8 overflow-y-auto custom-scrollbar">
            <div class="max-w-4xl bg-white rounded-[2.5rem] p-10 border border-slate-100 shadow-sm mx-auto text-left">
                <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    @csrf
                    @method('PUT')
                    
                    <div class="space-y-6">
                        <div>
                            <label class="block text-xs font-black text-navy-900 uppercase tracking-widest mb-2">Nama Pengguna <span class="text-red-500">*</span></label>
                            <input type="text" name="name" value="{{ $user->name }}" class="w-full px-5 py-3 bg-slate-50 border border-slate-100 rounded-2xl text-sm font-semibold focus:ring-4 focus:ring-gold-500/10 focus:border-gold-500 outline-none transition-all" required>
                        </div>

                        <div>
                            <label class="block text-xs font-black text-navy-900 uppercase tracking-widest mb-2">Email <span class="text-red-500">*</span></label>
                            <input type="email" name="email" value="{{ $user->email }}" class="w-full px-5 py-3 bg-slate-50 border border-slate-100 rounded-2xl text-sm font-semibold focus:ring-4 focus:ring-gold-500/10 focus:border-gold-500 outline-none transition-all" required>
                        </div>

                        <div>
                            <label class="block text-xs font-black text-navy-900 uppercase tracking-widest mb-2">Nomor WhatsApp / HP <span class="text-red-500">*</span></label>
                            <input type="text" name="no_hp" value="{{ $user->no_hp }}" class="w-full px-5 py-3 bg-slate-50 border border-slate-100 rounded-2xl text-sm font-semibold focus:ring-4 focus:ring-gold-500/10 focus:border-gold-500 outline-none transition-all" required>
                        </div>

                        <div>
                            <label class="block text-xs font-black text-navy-900 uppercase tracking-widest mb-2">Ganti Password (Kosongkan jika tetap)</label>
                            <div class="relative">
                                <input type="password" name="password" placeholder="••••••••" class="w-full px-5 py-3 bg-slate-50 border border-slate-100 rounded-2xl text-sm font-semibold focus:ring-4 focus:ring-gold-500/10 focus:border-gold-500 outline-none transition-all">
                                <i class="fas fa-lock absolute right-5 top-1/2 -translate-y-1/2 text-slate-300"></i>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <div>
                            <label class="block text-xs font-black text-navy-900 uppercase tracking-widest mb-2">Role Akses <span class="text-red-500">*</span></label>
                            <select id="role-select" name="role" class="w-full px-5 py-3 bg-slate-50 border border-slate-100 rounded-2xl text-sm font-semibold focus:ring-4 focus:ring-gold-500/10 focus:border-gold-500 outline-none transition-all" onchange="toggleWilayah()">
                                <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>ADMIN</option>
                                <option value="surveyor" {{ $user->role == 'surveyor' ? 'selected' : '' }}>SURVEYOR</option>
                            </select>
                        </div>

                        <div id="wilayah-container" class="{{ $user->role == 'admin' ? 'hidden' : '' }}">
                            <label class="block text-xs font-black text-navy-900 uppercase tracking-widest mb-2">
                                Wilayah Tugas <span class="text-slate-400 font-medium">(Opsional)</span>
                            </label>
                            <select name="id_kecamatan" class="w-full px-5 py-3 bg-slate-50 border border-slate-100 rounded-2xl text-sm font-semibold focus:ring-4 focus:ring-gold-500/10 focus:border-gold-500 outline-none transition-all">
                                <option value="">Pilih Wilayah (Boleh Dikosongkan)...</option>
                                @foreach($semuaWilayah as $wilayah)
                                    <option value="{{ $wilayah->id_kecamatan }}" {{ $user->id_kecamatan == $wilayah->id_kecamatan ? 'selected' : '' }}>
                                        Kec. {{ $wilayah->nama_kecamatan }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="pt-10 flex gap-3">
                            <button type="submit" class="flex-1 bg-gold-500 text-white text-xs px-4 py-3 rounded-2xl font-bold shadow-lg shadow-gold-500/10 hover:bg-gold-600 transition tracking-widest uppercase">Simpan Perubahan</button>
                            <a href="{{ route('admin.users') }}" class="flex-1 bg-slate-100 text-slate-500 text-xs px-4 py-3 rounded-2xl font-bold hover:bg-slate-200 transition text-center flex items-center justify-center gap-2 tracking-widest uppercase">
                                <i class="fas fa-times-circle text-xs"></i> Batal
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <script>
        // Fungsi Update Jam
        function updateClock() {
            const now = new Date();
            const options = { timeZone: 'Asia/Makassar', hour: '2-digit', minute: '2-digit', hour12: false };
            const timeString = new Intl.DateTimeFormat('id-ID', options).format(now);
            const el = document.getElementById('mini-clock');
            if (el) el.textContent = timeString.replace('.', ':') + ' WITA';
        }
        setInterval(updateClock, 1000); updateClock();

        // Fungsi Sembunyikan/Tampilkan Wilayah Tugas
        function toggleWilayah() {
            const roleSelect = document.getElementById('role-select');
            const wilayahContainer = document.getElementById('wilayah-container');
            
            if (roleSelect.value === 'admin') {
                wilayahContainer.classList.add('hidden');
            } else {
                wilayahContainer.classList.remove('hidden');
            }
        }
        
        // Panggil fungsi saat pertama kali dimuat agar sesuai dengan role awal
        window.onload = toggleWilayah;
    </script>
</body>
</html>

