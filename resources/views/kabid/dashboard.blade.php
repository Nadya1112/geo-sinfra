<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kabid Dashboard | GEO-SINFRA</title>
    <link rel="icon" href="{{ asset('logo_geo-sinfra.png') }}" type="image/png">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        navy: { 50: '#f0f4f8', 100: '#d9e2ec', 200: '#bcccdc', 300: '#9fb3c8', 400: '#829ab1', 500: '#627d98', 600: '#486581', 700: '#334e68', 800: '#243b53', 900: '#0f0e2c', 950: '#0a091d' },
                        gold: { 50: '#fbf8f1', 100: '#f5ebd9', 200: '#eed9b9', 300: '#e5c292', 400: '#dba665', 500: '#c5a059', 600: '#b48135', 700: '#96652a', 800: '#7c5327', 900: '#644422', 950: '#382310' }
                    },
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style> body { font-family: 'Plus Jakarta Sans', sans-serif; } </style>
</head>
<body class="bg-slate-50 flex h-screen overflow-hidden text-slate-800 text-left">

    @include('kabid.partials.sidebar')

    <main class="flex-1 flex flex-col h-screen overflow-y-auto">
        <header class="bg-white border-b border-slate-100 px-8 py-5 flex justify-between items-center z-10 sticky top-0">
            <div>
                <p class="text-[10px] font-extrabold text-gold-500 uppercase tracking-[0.2em] mb-1">Portal Kepala Bidang</p>
                <h2 class="text-xl font-black text-navy-900">Panel Pengawasan</h2>
            </div>
            
            <div class="flex items-center gap-6">
                <div class="text-right hidden sm:block">
                    <p class="text-[11px] font-black text-navy-900" id="mini-clock">00:00 WITA</p>
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-tighter">{{ now()->translatedFormat('l, d F Y') }}</p>
                </div>
                <div class="h-8 w-[1px] bg-slate-100"></div>
                <a href="{{ route('kabid.profile') }}" class="flex items-center gap-3 group">
                    <div class="text-right">
                        <p class="text-[11px] font-black text-navy-900 leading-none uppercase group-hover:text-gold-500 transition-colors">{{ auth()->user()->name }}</p>
                        <p class="text-[9px] font-bold text-emerald-500 uppercase mt-1">ONLINE</p>
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

        <div class="p-8 space-y-10">

            @if(isset($totalRusakBerat) && $totalRusakBerat > 0)
            <!-- Critical Alert Banner -->
            <div class="relative bg-rose-500 rounded-[2.5rem] p-6 border border-rose-600 shadow-xl shadow-rose-500/30 flex items-center justify-between overflow-hidden group hover:scale-[1.01] transition-transform">
                <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/20 rounded-full blur-2xl animate-pulse"></div>
                <div class="relative z-10 flex items-center gap-6">
                    <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center text-rose-500 shadow-inner">
                        <i class="fas fa-exclamation-triangle text-3xl animate-bounce"></i>
                    </div>
                    <div>
                        <div class="flex items-center gap-3 mb-1">
                            <h3 class="text-xl font-black text-white tracking-tight">PERINGATAN DARURAT</h3>
                            <span class="px-3 py-1 bg-rose-900/50 text-white text-[10px] font-black uppercase tracking-widest rounded-full border border-white/20 animate-pulse">Action Required</span>
                        </div>
                        <p class="text-rose-100 text-sm font-medium">Analisis AI mendeteksi <strong class="text-white text-lg">{{ $totalRusakBerat }} infrastruktur</strong> dalam kondisi kritis (Rusak Berat). Segera lakukan peninjauan dan alokasi anggaran perbaikan.</p>
                    </div>
                </div>
                <div class="relative z-10 hidden md:block">
                    <a href="{{ route('kabid.laporan') }}?kondisi=Berat" class="px-6 py-4 bg-white text-rose-600 rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-rose-50 transition-colors shadow-lg flex items-center gap-3">
                        Tinjau Sekarang <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
            @endif

            <!-- Welcome Section -->
            <div class="relative bg-navy-900 rounded-[3rem] p-10 overflow-hidden shadow-2xl shadow-navy-900/20">
                <div class="absolute -right-20 -top-20 w-80 h-80 bg-gold-500/20 rounded-full blur-[100px]"></div>
                <div class="absolute -left-10 -bottom-10 w-60 h-60 bg-white/5 rounded-full blur-[80px]"></div>
                
                <div class="relative z-10">
                    <h1 class="text-3xl font-black text-white mb-2">Selamat Datang, HIZBULWATHONI, S.T.</h1>
                    <p class="text-slate-300 text-sm font-medium tracking-wide">Berikut ringkasan kondisi infrastruktur Banjarmasin saat ini.</p>
                </div>

                <!-- Stats Bar — sumber data: Analisis AI (bukan input manual) -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6 mt-10">

                    {{-- Total Terdata --}}
                    <div class="bg-white/5 backdrop-blur-md rounded-3xl p-6 border border-white/10 group hover:bg-white/10 transition-all border-l-gold-400/50 border-l-4">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-gold-400/20 rounded-2xl flex items-center justify-center text-gold-400">
                                <i class="fas fa-database text-lg"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-gold-400 uppercase tracking-widest">Total Terdata</p>
                                <h3 class="text-2xl font-black text-white">{{ $totalInfrastruktur ?? 0 }} <span class="text-[10px] font-bold text-gold-400/50 italic ml-1">Objek</span></h3>
                            </div>
                        </div>
                    </div>

                    {{-- Kondisi Baik (AI) --}}
                    <div class="bg-white/5 backdrop-blur-md rounded-3xl p-6 border border-white/10 group hover:bg-white/10 transition-all border-l-emerald-400/50 border-l-4">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-emerald-400/20 rounded-2xl flex items-center justify-center text-emerald-400">
                                <i class="fas fa-check-circle text-lg"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-emerald-400 uppercase tracking-widest">Baik <span class="text-[8px] text-white/40 normal-case font-medium">(AI)</span></p>
                                <h3 class="text-2xl font-black text-white">{{ $totalBaik ?? 0 }} <span class="text-[10px] font-bold text-emerald-400/50 italic ml-1">Lokasi</span></h3>
                            </div>
                        </div>
                    </div>

                    {{-- Rusak Sedang (AI) --}}
                    <div class="bg-white/5 backdrop-blur-md rounded-3xl p-6 border border-white/10 group hover:bg-white/10 transition-all border-l-amber-400/50 border-l-4">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-amber-400/20 rounded-2xl flex items-center justify-center text-amber-400">
                                <i class="fas fa-exclamation-circle text-lg"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-amber-400 uppercase tracking-widest">Rusak Sedang <span class="text-[8px] text-white/40 normal-case font-medium">(AI)</span></p>
                                <h3 class="text-2xl font-black text-white">{{ $totalRusakSedang ?? 0 }} <span class="text-[10px] font-bold text-amber-400/50 italic ml-1">Lokasi</span></h3>
                            </div>
                        </div>
                    </div>

                    {{-- Rusak Berat (AI) --}}
                    <div class="bg-white/5 backdrop-blur-md rounded-3xl p-6 border border-white/10 group hover:bg-white/10 transition-all border-l-rose-400/50 border-l-4">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-rose-400/20 rounded-2xl flex items-center justify-center text-rose-400">
                                <i class="fas fa-triangle-exclamation text-lg"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-rose-400 uppercase tracking-widest">Rusak Berat <span class="text-[8px] text-white/40 normal-case font-medium">(AI)</span></p>
                                <h3 class="text-2xl font-black text-white">{{ $totalRusakBerat ?? 0 }} <span class="text-[10px] font-bold text-rose-400/50 italic ml-1">Lokasi</span></h3>
                            </div>
                        </div>
                    </div>

                    {{-- Antrean Validasi --}}
                    <div class="bg-white/5 backdrop-blur-md rounded-3xl p-6 border border-white/10 group hover:bg-white/10 transition-all border-l-blue-300/50 border-l-4">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-blue-400/20 rounded-2xl flex items-center justify-center text-blue-300">
                                <i class="fas fa-clipboard-check text-lg"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-blue-300 uppercase tracking-widest">Antrean Validasi</p>
                                <h3 class="text-2xl font-black text-white">{{ $totalPending ?? 0 }} <span class="text-[10px] font-bold text-blue-300/50 italic ml-1">Laporan</span></h3>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Main Menu Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <a href="{{ route('kabid.monitoring') }}" class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm hover:shadow-xl hover:-translate-y-2 transition-all group relative overflow-hidden">
                    <div class="absolute -right-6 -top-6 w-24 h-24 bg-navy-50 rounded-full group-hover:scale-150 transition-transform duration-700"></div>
                    <div class="relative z-10 flex flex-col h-full justify-between gap-6">
                        <div class="w-14 h-14 bg-navy-900 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-navy-200">
                            <i class="fas fa-map-location-dot text-xl"></i>
                        </div>
                        <div>
                            <h4 class="font-black text-navy-900 text-sm uppercase tracking-tight mb-2">Monitoring Peta Sebaran</h4>
                            <p class="text-[10px] text-slate-500 font-bold leading-relaxed">Pantau persebaran infrastruktur di seluruh wilayah Banjarmasin secara real-time.</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('kabid.validasi') }}" class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm hover:shadow-xl hover:-translate-y-2 transition-all group relative overflow-hidden">
                    <div class="absolute -right-6 -top-6 w-24 h-24 bg-gold-50 rounded-full group-hover:scale-150 transition-transform duration-700"></div>
                    <div class="relative z-10 flex flex-col h-full justify-between gap-6">
                        <div class="w-14 h-14 bg-gold-500 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-gold-200">
                            <i class="fas fa-clipboard-check text-xl"></i>
                        </div>
                        <div>
                            <h4 class="font-black text-navy-900 text-sm uppercase tracking-tight mb-2">Validasi Usulan Perbaikan</h4>
                            <p class="text-[10px] text-slate-500 font-bold leading-relaxed">Tinjau dan beri persetujuan pada laporan kerusakan dari surveyor lapangan.</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('kabid.statistik.tahunan') }}" class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm hover:shadow-xl hover:-translate-y-2 transition-all group relative overflow-hidden">
                    <div class="absolute -right-6 -top-6 w-24 h-24 bg-navy-50 rounded-full group-hover:scale-150 transition-transform duration-700"></div>
                    <div class="relative z-10 flex flex-col h-full justify-between gap-6">
                        <div class="w-14 h-14 bg-navy-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-navy-200">
                            <i class="fas fa-chart-line text-xl"></i>
                        </div>
                        <div>
                            <h4 class="font-black text-navy-900 text-sm uppercase tracking-tight mb-2">Statistik Laporan</h4>
                            <p class="text-[10px] text-slate-500 font-bold leading-relaxed">Lihat tren kondisi infrastruktur dan capaian perbaikan dalam satu tahun terakhir.</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('kabid.laporan') }}" class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm hover:shadow-xl hover:-translate-y-2 transition-all group relative overflow-hidden">
                    <div class="absolute -right-6 -top-6 w-24 h-24 bg-gold-50 rounded-full group-hover:scale-150 transition-transform duration-700"></div>
                    <div class="relative z-10 flex flex-col h-full justify-between gap-6">
                        <div class="w-14 h-14 bg-gold-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-gold-200">
                            <i class="fas fa-file-pdf text-xl"></i>
                        </div>
                        <div>
                            <h4 class="font-black text-navy-900 text-sm uppercase tracking-tight mb-2">Cetak Laporan Resmi PDF</h4>
                            <p class="text-[10px] text-slate-500 font-bold leading-relaxed">Ekspor ringkasan data pengawasan menjadi dokumen resmi siap cetak.</p>
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
