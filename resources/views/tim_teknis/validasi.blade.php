<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script>
        if (localStorage.getItem('geo-theme') === 'dark' || (!('geo-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    <title>Validasi Usulan | GEO-SINFRA</title>
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .glass-card { background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.2); }
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    </style>
<style>
    
    
@media (max-width: 767px) { html { font-size: 12px; } }
</style>
    @livewireStyles
</head>
<body class="bg-slate-50 dark:bg-[#0f0e2c] flex h-screen overflow-hidden text-slate-800 dark:text-white text-left font-sans dark:bg-navy-950 transition-colors duration-300">

    @include('tim_teknis.partials.sidebar')

    <main class="flex-1 flex flex-col h-screen overflow-y-auto custom-scrollbar">
        <!-- HEADER -->
        <header class="bg-white dark:bg-[#1e1b4b] border-b border-slate-100 dark:border-white/10 px-4 pl-20 md:px-8 py-4 flex justify-between items-center z-40 sticky top-0">
            <div class="flex items-center gap-4 min-w-0">
                <a href="{{ route('tim_teknis.dashboard') }}" class="w-10 h-10 flex items-center justify-center bg-slate-50 dark:bg-[#0f0e2c] text-slate-400 rounded-xl hover:bg-gold-50 hover:text-gold-500 transition-all border border-slate-100 dark:border-white/10 hidden md:flex">
                    <i class="fas fa-arrow-left text-sm"></i>
                </a>
                <div class="min-w-0">
                    <p class="text-[9px] md:text-xs font-extrabold text-gold-500 uppercase tracking-[0.2em] mb-0.5 md:mb-1 truncate">Manajemen Validasi</p>
                    <h2 class="text-sm md:text-xl font-black text-navy-900 dark:text-white leading-tight whitespace-normal">Validasi Usulan</h2>
                </div>
            </div>
            
            <div class="flex items-center gap-3 md:gap-6 flex-shrink-0">
                <div class="text-right">
                    <p class="text-[10px] md:text-xs font-black text-navy-900 dark:text-white mt-1" id="mini-clock">00:00 WITA</p>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-tighter hidden md:block">{{ now()->translatedFormat('l, d F Y') }}</p>
                </div>
                <div class="h-6 md:h-8 w-[1px] bg-slate-200 dark:bg-white/10"></div>
                <a href="{{ route('tim_teknis.profile') }}" class="flex items-center gap-2 md:gap-3 group">
                    <div class="text-right">
                        <p class="text-sm font-black text-navy-900 dark:text-white leading-none uppercase group-hover:text-gold-500 transition-colors max-w-[200px] truncate hidden md:block">{{ auth()->user()->name }}</p>
                        <p class="text-[8px] md:text-xs font-bold text-emerald-500 uppercase md:mt-0.5">Aktif</p>
                    </div>
                    <div class="w-8 h-8 md:w-10 md:h-10 bg-navy-900 rounded-xl flex items-center justify-center text-gold-500 shadow-md group-hover:shadow-lg transition-all overflow-hidden shrink-0">
                        @if(auth()->user()->profile_photo)
                            <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}" class="w-full h-full object-cover">
                        @else
                            <i class="fas fa-user-circle text-lg md:text-xl"></i>
                        @endif
                    </div>
                </a>
            </div>
        </header>

        <!-- MAIN CONTENT -->
        <div class="p-4 md:p-8 space-y-6 md:space-y-8">
            
            @if(session('success'))
                <div class="p-4 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-100 dark:border-emerald-500/20 rounded-2xl flex items-center gap-3 text-emerald-600 dark:text-emerald-400 shadow-sm animate-pulse">
                    <i class="fas fa-check-circle"></i>
                    <p class="text-sm font-bold">{{ session('success') }}</p>
                </div>
            @endif

            <!-- STATS SUMMARY CARDS -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Menunggu -->
                <div class="bg-gradient-to-br from-amber-500 to-orange-600 p-6 rounded-[2.5rem] shadow-lg shadow-amber-500/30 relative overflow-hidden group border border-amber-400/50">
                    <div class="absolute -right-4 -top-4 w-32 h-32 bg-white/20 dark:bg-[#1e1b4b]/20 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-500"></div>
                    <div class="absolute right-4 bottom-4 text-white/10 group-hover:scale-110 transition-transform duration-500">
                        <i class="fas fa-clock text-6xl"></i>
                    </div>
                    <div class="relative z-10">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 bg-white/20 dark:bg-[#1e1b4b]/20 backdrop-blur-sm text-white rounded-xl flex items-center justify-center border border-white/30 shadow-inner">
                                <i class="fas fa-clock text-sm"></i>
                            </div>
                            <p class="text-xs font-black text-white/90 uppercase tracking-widest drop-shadow-sm">Menunggu Validasi</p>
                        </div>
                        <div class="flex items-end gap-2">
                            <h3 class="text-4xl font-black text-white leading-none drop-shadow-md">{{ $counts['pending'] }}</h3>
                            <span class="text-xs font-bold text-amber-100 mb-1 uppercase tracking-wider">Usulan</span>
                        </div>
                    </div>
                </div>

                <!-- Diterima -->
                <div class="bg-gradient-to-br from-[#059669] to-emerald-700 p-6 rounded-[2.5rem] shadow-lg shadow-[#059669]/30 relative overflow-hidden group border border-[#059669]/50">
                    <div class="absolute -right-4 -top-4 w-32 h-32 bg-white/20 dark:bg-[#1e1b4b]/20 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-500"></div>
                    <div class="absolute right-4 bottom-4 text-white/10 group-hover:scale-110 transition-transform duration-500">
                        <i class="fas fa-check-double text-6xl"></i>
                    </div>
                    <div class="relative z-10">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 bg-white/20 dark:bg-[#1e1b4b]/20 backdrop-blur-sm text-white rounded-xl flex items-center justify-center border border-white/30 shadow-inner">
                                <i class="fas fa-check-double text-sm"></i>
                            </div>
                            <p class="text-xs font-black text-white/90 uppercase tracking-widest drop-shadow-sm">Telah Diterima</p>
                        </div>
                        <div class="flex items-end gap-2">
                            <h3 class="text-4xl font-black text-white leading-none drop-shadow-md">{{ $counts['verified'] }}</h3>
                            <span class="text-xs font-bold text-emerald-100 mb-1 uppercase tracking-wider">Terverifikasi</span>
                        </div>
                    </div>
                </div>

                <!-- Ditolak -->
                <div class="bg-gradient-to-br from-rose-500 to-red-600 p-6 rounded-[2.5rem] shadow-lg shadow-rose-500/30 relative overflow-hidden group border border-rose-400/50">
                    <div class="absolute -right-4 -top-4 w-32 h-32 bg-white/20 dark:bg-[#1e1b4b]/20 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-500"></div>
                    <div class="absolute right-4 bottom-4 text-white/10 group-hover:scale-110 transition-transform duration-500">
                        <i class="fas fa-times-circle text-6xl"></i>
                    </div>
                    <div class="relative z-10">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 bg-white/20 dark:bg-[#1e1b4b]/20 backdrop-blur-sm text-white rounded-xl flex items-center justify-center border border-white/30 shadow-inner">
                                <i class="fas fa-times-circle text-sm"></i>
                            </div>
                            <p class="text-xs font-black text-white/90 uppercase tracking-widest drop-shadow-sm">Ditolak / Perbaikan</p>
                        </div>
                        <div class="flex items-end gap-2">
                            <h3 class="text-4xl font-black text-white leading-none drop-shadow-md">{{ $counts['rejected'] }}</h3>
                            <span class="text-xs font-bold text-rose-100 mb-1 uppercase tracking-wider">Ditolak</span>
                        </div>
                    </div>
                </div>
            </div>

            @livewire('tim-teknis.validasi-table')
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

        // Bulk Validation Logic
        const selectAll = document.getElementById('selectAll');
        const rowCheckboxes = document.querySelectorAll('.row-checkbox');
        const selectedCountEl = document.getElementById('selectedCount');
        const bulkActionBar = document.getElementById('bulkActionBar');

        function updateBulkState() {
            const checkedCount = document.querySelectorAll('.row-checkbox:checked').length;
            selectedCountEl.textContent = checkedCount;
            
            if (checkedCount > 0) {
                bulkActionBar.classList.remove('hidden');
                bulkActionBar.classList.add('flex');
            } else {
                bulkActionBar.classList.add('hidden');
                bulkActionBar.classList.remove('flex');
            }
            
            selectAll.checked = checkedCount === rowCheckboxes.length && rowCheckboxes.length > 0;
        }

        selectAll.addEventListener('change', function() {
            rowCheckboxes.forEach(cb => {
                if (!cb.disabled) cb.checked = selectAll.checked;
            });
            updateBulkState();
        });

        rowCheckboxes.forEach(cb => {
            cb.addEventListener('change', updateBulkState);
        });

    </script>
    @livewireScripts
</body>
</html>
