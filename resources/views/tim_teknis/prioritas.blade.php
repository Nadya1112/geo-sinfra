<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prioritas Penanganan | GEO-SINFRA</title>
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    </style>
<style>
    
    
@media (max-width: 767px) { html { font-size: 12px; } }
</style>
</head>
<body class="bg-slate-50 dark:bg-[#0f0e2c] flex h-screen overflow-hidden text-slate-800 dark:text-white text-left font-sans dark:bg-navy-950 transition-colors duration-300">

    @include('tim_teknis.partials.sidebar')

    <main class="flex-1 flex flex-col h-screen overflow-hidden">
        <header class="bg-white dark:bg-[#1e1b4b] border-b border-slate-100 dark:border-white/10 px-4 pl-16 md:px-8 py-4 flex justify-between items-center z-40 sticky top-0">
            <div class="flex items-center gap-4">
                <a href="{{ route('tim_teknis.dashboard') }}" class="w-10 h-10 flex items-center justify-center bg-slate-50 dark:bg-[#0f0e2c] text-slate-400 rounded-xl hover:bg-gold-50 hover:text-gold-500 transition-all border border-slate-100 dark:border-white/10">
                    <i class="fas fa-arrow-left text-sm"></i>
                </a>
                <div>
                    <p class="text-xs font-extrabold text-gold-500 uppercase tracking-[0.2em] mb-1">Decision Support</p>
                    <h2 class="text-xl font-black text-navy-900 dark:text-white">Rekomendasi Prioritas</h2>
                </div>
            </div>
            
            <div class="flex items-center gap-6">
                <div class="text-right">
                    <p class="text-xs font-black text-navy-900 dark:text-white" id="mini-clock">00:00 WITA</p>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-tighter">{{ now()->translatedFormat('l, d F Y') }}</p>
                </div>
                <div class="h-8 w-[1px] bg-slate-100"></div>
                <a href="{{ route('tim_teknis.profile') }}" class="flex items-center gap-3 group">
                    <div class="text-right">
                        <p class="text-xs md:text-sm font-black text-navy-900 dark:text-white leading-none uppercase group-hover:text-gold-500 transition-colors ] sm:] md: max-w-[100px] sm:max-w-[150px] md:max-w-[300px] truncate">{{ auth()->user()->name }}</p>
                        <p class="text-[10px] md:text-xs font-bold text-emerald-500 uppercase mt-0.5">ONLINE</p>
                    </div>
                    <div class="w-10 h-10 bg-navy-900 rounded-xl flex items-center justify-center text-gold-500 shadow-md group-hover:shadow-lg transition-all overflow-hidden">
                        @if(auth()->user()->profile_photo)
                            <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}" class="w-full h-full object-cover">
                        @else
                            <i class="fas fa-user-circle text-xl"></i>
                        @endif
                    </div>
                </a>
            </div>
        </header>

        <div class="flex-1 overflow-y-auto p-8 custom-scrollbar space-y-8">
            
            <!-- Header Banner -->
            <div class="flex items-center gap-5 bg-rose-50 border border-rose-100 rounded-[2rem] p-8 shadow-sm">
                <div class="w-16 h-16 bg-white dark:bg-[#1e1b4b] rounded-2xl flex items-center justify-center text-rose-500 shadow-sm border border-rose-100 shrink-0">
                    <i class="fas fa-exclamation-triangle text-3xl animate-pulse"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-black text-navy-900 dark:text-white leading-none mb-2">Rekomendasi Prioritas Penanganan</h2>
                    <p class="text-sm font-bold text-slate-500">Daftar infrastruktur dengan tingkat kerusakan <span class="text-rose-500 font-black uppercase tracking-widest px-2 py-0.5 bg-rose-100 rounded-md text-xs">Sangat Berat</span> yang dianalisis oleh AI. Membutuhkan alokasi anggaran dan perbaikan segera.</p>
                </div>
            </div>

            <!-- Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8 pb-10">
                @forelse($prioritas as $index => $item)
                    <!-- Card Item -->
                    <div class="bg-white dark:bg-[#1e1b4b] rounded-[2rem] border border-slate-100 dark:border-white/10 shadow-sm overflow-hidden hover:shadow-2xl transition-all duration-300 group relative">
                        <!-- Ranking Badge -->
                        <div class="absolute top-4 right-4 z-10 w-10 h-10 bg-rose-500 text-white rounded-xl flex items-center justify-center font-black text-lg shadow-lg shadow-rose-500/30">
                            #{{ $index + 1 }}
                        </div>

                        <!-- Image Section -->
                        <div class="relative h-56 bg-slate-100 overflow-hidden">
                            @if($item->foto_terbaru)
                                <img src="{{ str_contains($item->foto_terbaru, 'infrastruktur/') ? asset('storage/' . $item->foto_terbaru) : asset('storage/infrastruktur/' . $item->foto_terbaru) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" onerror="this.src='/test.jpg'">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-slate-300 bg-slate-100">
                                    <i class="fas fa-image text-5xl"></i>
                                </div>
                            @endif
                            <div class="absolute inset-0 bg-gradient-to-t from-navy-900 via-navy-900/40 to-transparent"></div>
                            
                            <div class="absolute bottom-5 left-5 right-5">
                                <span class="px-2.5 py-1 bg-white/20 dark:bg-[#1e1b4b]/20 backdrop-blur-md roun border border-white/20 text-xs font-black uppercase tracking-widest text-white mb-2 inline-block">
                                    {{ ucfirst($item->jenis) ?? 'Infrastruktur' }}
                                </span>
                                <h3 class="text-xl font-black text-white leading-tight line-clamp-2">{{ $item->nama_objek ?? $item->nama_infrastruktur ?? 'Tanpa Nama' }}</h3>
                            </div>
                        </div>

                        <!-- Content Section -->
                        <div class="p-6">
                            <div class="flex items-start gap-3 mb-5 border-b border-slate-50 pb-5">
                                <div class="w-10 h-10 rounded-xl bg-slate-50 dark:bg-[#0f0e2c] text-slate-400 flex items-center justify-center shrink-0 border border-slate-100 dark:border-white/10">
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1">Lokasi Detail</p>
                                    <p class="text-sm font-bold text-navy-900 dark:text-white leading-tight">
                                        {{ $item->kelurahan->nama_kelurahan ?? '-' }}, {{ $item->kelurahan->kecamatan->nama_kecamatan ?? '-' }}
                                    </p>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-4 mb-6">
                                <div>
                                    <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1.5">Status Kondisi</p>
                                    <div class="flex items-center gap-2 px-3 py-2 bg-rose-50 border border-rose-100 rounded-xl">
                                        <div class="w-2 h-2 rounded-full bg-rose-500 animate-pulse"></div>
                                        <span class="text-xs font-black text-rose-600 uppercase tracking-widest whitespace-nowrap">
                                            {{ $item->analisis->label_prioritas ?? 'Rusak Berat' }}
                                        </span>
                                    </div>
                                </div>
                                <div>
                                    <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1.5">Surveyor</p>
                                    <div class="flex items-center gap-2 px-3 py-2 bg-slate-50 dark:bg-[#0f0e2c] border border-slate-100 dark:border-white/10 rounded-xl">
                                        <i class="fas fa-user-circle text-slate-400"></i>
                                        <span class="text-xs font-black text-slate-600 uppercase tracking-widest truncate w-full">
                                            {{ $item->user->name ?? 'Sistem' }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <a href="{{ route('tim_teknis.infrastruktur.show', $item->id ?? $item->id_infrastruktur) }}" class="flex items-center justify-center gap-2 w-full py-3.5 bg-navy-900 text-white rounded-xl text-xs font-black uppercase tracking-widest hover:bg-gold-500 hover:text-white transition-all shadow-lg shadow-navy-900/20 group-hover:shadow-gold-500/30">
                                Lihat Detail Data <i class="fas fa-arrow-right text-xs"></i>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-24 flex flex-col items-center justify-center border-2 border-dashed border-emerald-200 rounded-[3rem] bg-emerald-50/50 dark:bg-[#0f0e2c]">
                        <div class="w-24 h-24 bg-white dark:bg-[#1e1b4b] rounded-full shadow-sm flex items-center justify-center mb-6">
                            <i class="fas fa-shield-alt text-5xl text-emerald-400"></i>
                        </div>
                        <h3 class="text-2xl font-black text-navy-900 dark:text-white mb-2">Semua Aman Terkendali!</h3>
                        <p class="text-slate-500 font-bold text-center max-w-md">Luar biasa! Saat ini tidak ada infrastruktur dengan kondisi rusak berat yang mendesak untuk ditangani.</p>
                    </div>
                @endforelse
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
    </script>
</body>
</html>
