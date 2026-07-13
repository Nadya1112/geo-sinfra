<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beranda Tim Teknis | GEO-SINFRA</title>
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
    <style> body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
<style>
    @media (max-width: 767px) { html { font-size: 12px; } }
</style>
</head>
<body class="bg-slate-50 dark:bg-[#0f0e2c] flex h-screen overflow-hidden text-slate-800 dark:text-white text-left font-sans dark:bg-navy-950 transition-colors duration-300">

    @include('tim_teknis.partials.sidebar')

    <main class="flex-1 flex flex-col h-screen overflow-y-auto">
        <header class="bg-white dark:bg-[#1e1b4b] border-b border-slate-100 dark:border-white/10 px-4 pl-20 md:px-8 py-3 md:py-4 flex justify-between items-center z-40 sticky top-0">
            <div class="min-w-0">
                <p class="text-[9px] md:text-xs font-extrabold text-gold-500 uppercase tracking-[0.2em] mb-0.5 truncate">Portal Tim Teknis</p>
                <h2 class="text-sm md:text-xl font-black text-navy-900 dark:text-white leading-tight whitespace-normal">Panel Pengawasan</h2>
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
                    <div class="w-8 h-8 md:w-10 md:h-10 bg-navy-900 rounded-xl flex items-center justify-center text-gold-500 shadow-md group-hover:shadow-lg transition-all overflow-hidden flex-shrink-0">
                        @if(auth()->user()->profile_photo)
                            <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}" class="w-full h-full object-cover">
                        @else
                            <i class="fas fa-user-circle text-lg md:text-xl"></i>
                        @endif
                    </div>
                </a>
            </div>
        </header>

        <div class="p-4 md:p-6 space-y-4 md:space-y-6">

            @if(isset($totalRusakBerat) && $totalRusakBerat > 0)
            <!-- Critical Alert Banner -->
            <div class="relative bg-rose-500 rounded-2xl md:rounded-[2.5rem] p-4 md:p-6 border border-rose-600 shadow-xl shadow-rose-500/30 overflow-hidden">
                <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/20 rounded-full blur-2xl animate-pulse"></div>
                <div class="relative z-10 flex items-center justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 md:w-16 md:h-16 bg-white/20 rounded-xl md:rounded-2xl flex items-center justify-center text-white flex-shrink-0">
                            <i class="fas fa-exclamation-triangle text-lg md:text-3xl animate-bounce"></i>
                        </div>
                        <div>
                            <div class="flex flex-wrap items-center gap-2 mb-0.5">
                                <h3 class="text-sm md:text-xl font-black text-white tracking-tight">PERINGATAN DARURAT</h3>
                                <span class="px-2 py-0.5 bg-rose-900/50 text-white text-[9px] md:text-xs font-black uppercase tracking-widest rounded-full border border-white/20 animate-pulse">Perlu Tindakan</span>
                            </div>
                            <p class="text-rose-100 text-xs md:text-sm font-medium">AI mendeteksi <strong class="text-white">{{ $totalRusakBerat }} infrastruktur</strong> kondisi kritis. Segera tinjau!</p>
                        </div>
                    </div>
                    <a href="{{ route('tim_teknis.laporan') }}?kondisi=Berat" class="flex-shrink-0 px-3 py-2 md:px-6 md:py-4 bg-white text-rose-600 rounded-xl md:rounded-2xl text-[10px] md:text-xs font-black uppercase tracking-widest hover:bg-rose-50 transition-colors shadow-lg flex items-center gap-1.5">
                        Tinjau <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
            @endif

            <!-- Welcome Section -->
            <div class="relative bg-navy-900 rounded-2xl md:rounded-3xl p-5 md:p-8 overflow-hidden shadow-2xl shadow-navy-900/20">
                <div class="absolute -right-20 -top-20 w-80 h-80 bg-gold-500/20 rounded-full blur-[100px]"></div>
                <div class="absolute -left-10 -bottom-10 w-60 h-60 bg-white/5 rounded-full blur-[80px]"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
                    <div>
                        <h1 class="text-lg md:text-2xl font-black text-white mb-1">Selamat Datang, {{ auth()->user()->name }}</h1>
                        <p class="text-slate-300 text-xs md:text-sm font-medium tracking-wide">Berikut ringkasan kondisi infrastruktur Banjarmasin saat ini.</p>
                    </div>
                    <div class="hidden md:flex items-center gap-2">
                        <span class="px-3 py-1.5 bg-emerald-500/20 text-emerald-400 text-xs font-black uppercase tracking-widest rounded-full border border-emerald-500/30">
                            <i class="fas fa-circle text-[6px] mr-1 animate-pulse"></i> Sistem Aktif
                        </span>
                        <span class="px-3 py-1.5 bg-gold-500/20 text-gold-400 text-xs font-black uppercase tracking-widest rounded-full border border-gold-500/30">
                            <i class="fas fa-robot text-[10px] mr-1"></i> AI Aktif
                        </span>
                    </div>
                </div>

                <!-- Stats Bar — sumber data: Analisis AI (bukan input manual) -->
                <div class="relative z-10 grid grid-cols-2 md:grid-cols-5 gap-3 md:gap-4">

                    <div class="bg-white/5 backdrop-blur-md rounded-2xl p-3 md:p-5 border border-white/10 hover:bg-white/10 transition-all border-l-gold-400/50 border-l-4">
                        <div class="flex md:flex-col gap-3 md:gap-2">
                            <div class="w-8 h-8 md:w-10 md:h-10 bg-gold-400/20 rounded-xl flex-shrink-0 flex items-center justify-center text-gold-400">
                                <i class="fas fa-database text-sm"></i>
                            </div>
                            <div>
                                <p class="text-[9px] md:text-[10px] font-black text-gold-400 uppercase tracking-wider">Total Terdata</p>
                                <h3 class="text-xl md:text-3xl font-black text-white leading-none">{{ $totalInfrastruktur ?? 0 }}</h3>
                                <span class="text-[9px] font-bold text-gold-400/60 italic">Objek</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white/5 backdrop-blur-md rounded-2xl p-3 md:p-5 border border-white/10 hover:bg-white/10 transition-all border-l-emerald-400/50 border-l-4">
                        <div class="flex md:flex-col gap-3 md:gap-2">
                            <div class="w-8 h-8 md:w-10 md:h-10 bg-emerald-400/20 rounded-xl flex-shrink-0 flex items-center justify-center text-emerald-400">
                                <i class="fas fa-check-circle text-sm"></i>
                            </div>
                            <div>
                                <p class="text-[9px] md:text-[10px] font-black text-emerald-400 uppercase tracking-wider">Baik <span class="text-white/40 normal-case font-medium">(AI)</span></p>
                                <h3 class="text-xl md:text-3xl font-black text-white leading-none">{{ $totalBaik ?? 0 }}</h3>
                                <span class="text-[9px] font-bold text-emerald-400/60 italic">Lokasi</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white/5 backdrop-blur-md rounded-2xl p-3 md:p-5 border border-white/10 hover:bg-white/10 transition-all border-l-amber-400/50 border-l-4">
                        <div class="flex md:flex-col gap-3 md:gap-2">
                            <div class="w-8 h-8 md:w-10 md:h-10 bg-amber-400/20 rounded-xl flex-shrink-0 flex items-center justify-center text-amber-400">
                                <i class="fas fa-exclamation-circle text-sm"></i>
                            </div>
                            <div>
                                <p class="text-[9px] md:text-[10px] font-black text-amber-400 uppercase tracking-wider">Rusak Sedang <span class="text-white/40 normal-case font-medium">(AI)</span></p>
                                <h3 class="text-xl md:text-3xl font-black text-white leading-none">{{ $totalRusakSedang ?? 0 }}</h3>
                                <span class="text-[9px] font-bold text-amber-400/60 italic">Lokasi</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white/5 backdrop-blur-md rounded-2xl p-3 md:p-5 border border-white/10 hover:bg-white/10 transition-all border-l-rose-400/50 border-l-4">
                        <div class="flex md:flex-col gap-3 md:gap-2">
                            <div class="w-8 h-8 md:w-10 md:h-10 bg-rose-400/20 rounded-xl flex-shrink-0 flex items-center justify-center text-rose-400">
                                <i class="fas fa-triangle-exclamation text-sm"></i>
                            </div>
                            <div>
                                <p class="text-[9px] md:text-[10px] font-black text-rose-400 uppercase tracking-wider">Rusak Berat <span class="text-white/40 normal-case font-medium">(AI)</span></p>
                                <h3 class="text-xl md:text-3xl font-black text-white leading-none">{{ $totalRusakBerat ?? 0 }}</h3>
                                <span class="text-[9px] font-bold text-rose-400/60 italic">Lokasi</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white/5 backdrop-blur-md rounded-2xl p-3 md:p-5 border border-white/10 hover:bg-white/10 transition-all border-l-blue-300/50 border-l-4">
                        <div class="flex md:flex-col gap-3 md:gap-2">
                            <div class="w-8 h-8 md:w-10 md:h-10 bg-blue-400/20 rounded-xl flex-shrink-0 flex items-center justify-center text-blue-300">
                                <i class="fas fa-clipboard-check text-sm"></i>
                            </div>
                            <div>
                                <p class="text-[9px] md:text-[10px] font-black text-blue-300 uppercase tracking-wider">Antrean Validasi</p>
                                <h3 class="text-xl md:text-3xl font-black text-white leading-none">{{ $totalPending ?? 0 }}</h3>
                                <span class="text-[9px] font-bold text-blue-300/60 italic">Laporan</span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Main Menu Cards -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-5">
                <a href="{{ route('tim_teknis.monitoring') }}" class="bg-white dark:bg-[#1e1b4b] p-4 md:p-8 rounded-2xl md:rounded-[2.5rem] border border-slate-100 dark:border-white/10 shadow-sm hover:shadow-xl hover:-translate-y-1 md:hover:-translate-y-2 transition-all group relative overflow-hidden">
                    <div class="absolute -right-6 -top-6 w-24 h-24 bg-navy-50 rounded-full group-hover:scale-150 transition-transform duration-700"></div>
                    <div class="relative z-10 flex flex-col h-full justify-between gap-3 md:gap-6">
                        <div class="w-10 h-10 md:w-14 md:h-14 bg-navy-900 rounded-xl md:rounded-2xl flex items-center justify-center text-white shadow-lg">
                            <i class="fas fa-map-location-dot text-base md:text-xl"></i>
                        </div>
                        <div>
                            <h4 class="font-black text-navy-900 dark:text-white text-xs md:text-sm uppercase tracking-tight mb-1 md:mb-2">Monitoring Peta</h4>
                            <p class="text-[10px] md:text-xs text-slate-500 font-bold leading-relaxed hidden md:block">Pantau persebaran infrastruktur di seluruh wilayah Banjarmasin secara real-time.</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('tim_teknis.validasi') }}" class="bg-white dark:bg-[#1e1b4b] p-4 md:p-8 rounded-2xl md:rounded-[2.5rem] border border-slate-100 dark:border-white/10 shadow-sm hover:shadow-xl hover:-translate-y-1 md:hover:-translate-y-2 transition-all group relative overflow-hidden">
                    <div class="absolute -right-6 -top-6 w-24 h-24 bg-gold-50 rounded-full group-hover:scale-150 transition-transform duration-700"></div>
                    <div class="relative z-10 flex flex-col h-full justify-between gap-3 md:gap-6">
                        <div class="w-10 h-10 md:w-14 md:h-14 bg-gold-500 rounded-xl md:rounded-2xl flex items-center justify-center text-white shadow-lg">
                            <i class="fas fa-clipboard-check text-base md:text-xl"></i>
                        </div>
                        <div>
                            <h4 class="font-black text-navy-900 dark:text-white text-xs md:text-sm uppercase tracking-tight mb-1 md:mb-2">Validasi Laporan</h4>
                            <p class="text-[10px] md:text-xs text-slate-500 font-bold leading-relaxed hidden md:block">Tinjau dan beri persetujuan pada laporan kerusakan dari surveyor lapangan.</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('tim_teknis.prioritas') }}" class="bg-white dark:bg-[#1e1b4b] p-4 md:p-8 rounded-2xl md:rounded-[2.5rem] border border-slate-100 dark:border-white/10 shadow-sm hover:shadow-xl hover:-translate-y-1 md:hover:-translate-y-2 transition-all group relative overflow-hidden">
                    <div class="absolute -right-6 -top-6 w-24 h-24 bg-amber-50 rounded-full group-hover:scale-150 transition-transform duration-700"></div>
                    <div class="relative z-10 flex flex-col h-full justify-between gap-3 md:gap-6">
                        <div class="w-10 h-10 md:w-14 md:h-14 bg-amber-500 rounded-xl md:rounded-2xl flex items-center justify-center text-white shadow-lg">
                            <i class="fas fa-trophy text-base md:text-xl"></i>
                        </div>
                        <div>
                            <h4 class="font-black text-navy-900 dark:text-white text-xs md:text-sm uppercase tracking-tight mb-1 md:mb-2">Prioritas</h4>
                            <p class="text-[10px] md:text-xs text-slate-500 font-bold leading-relaxed hidden md:block">Tentukan urutan prioritas perbaikan berdasarkan tingkat kerusakan infrastruktur.</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('tim_teknis.laporan') }}" class="bg-white dark:bg-[#1e1b4b] p-4 md:p-8 rounded-2xl md:rounded-[2.5rem] border border-slate-100 dark:border-white/10 shadow-sm hover:shadow-xl hover:-translate-y-1 md:hover:-translate-y-2 transition-all group relative overflow-hidden">
                    <div class="absolute -right-6 -top-6 w-24 h-24 bg-gold-50 rounded-full group-hover:scale-150 transition-transform duration-700"></div>
                    <div class="relative z-10 flex flex-col h-full justify-between gap-3 md:gap-6">
                        <div class="w-10 h-10 md:w-14 md:h-14 bg-gold-600 rounded-xl md:rounded-2xl flex items-center justify-center text-white shadow-lg">
                            <i class="fas fa-file-pdf text-base md:text-xl"></i>
                        </div>
                        <div>
                            <h4 class="font-black text-navy-900 dark:text-white text-xs md:text-sm uppercase tracking-tight mb-1 md:mb-2">Laporan PDF</h4>
                            <p class="text-[10px] md:text-xs text-slate-500 font-bold leading-relaxed hidden md:block">Ekspor ringkasan data pengawasan menjadi dokumen resmi siap cetak.</p>
                        </div>
                    </div>
                </a>
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
