<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Infrastruktur | GEO-SINFRA</title>
    <link rel="icon" href="{{ asset('logo_geo-sinfra.png') }}" type="image/png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
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
    <script>
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
    <style> 
        body { font-family: 'Plus Jakarta Sans', sans-serif; } 
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        .leaflet-bar { border: none !important; box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1) !important; border-radius: 8px !important; overflow: hidden; }
        .leaflet-bar a { width: 26px !important; height: 26px !important; line-height: 26px !important; font-size: 14px !important; color: #1e1b4b !important; }
    </style>
</head>
<body class="bg-slate-50 dark:bg-[#0f0e2c] flex h-screen overflow-hidden text-slate-800 font-sans dark:bg-navy-950 dark:text-white transition-colors duration-300">

    @include('surveyor.partials.sidebar')

    <main class="flex-1 flex flex-col h-screen overflow-hidden">
        {{-- ── Header ── --}}
        <header class="bg-white/80 dark:bg-[#1e1b4b]/80 backdrop-blur-xl border-b border-slate-100 dark:border-white/10 px-8 py-5 flex justify-between items-center z-40 shrink-0 shadow-sm">
            <div class="flex items-center gap-4">
                <a href="{{ route('surveyor.history') }}" class="w-10 h-10 flex items-center justify-center bg-white dark:bg-[#1e1b4b] text-slate-400 rounded-xl hover:bg-gold-50 hover:text-gold-600 transition-all border border-slate-200 dark:border-white/20 hover:border-gold-200">
                    <i class="fas fa-arrow-left text-sm"></i>
                </a>
                <div>
                    <p class="text-[9px] font-black text-gold-500 uppercase tracking-[0.2em] mb-0.5">Laporan Detail</p>
                    <h2 class="text-xl font-black text-navy-900 dark:text-white tracking-tight">Informasi Infrastruktur</h2>
                </div>
            </div>
            
            <div class="flex items-center gap-6">
                <div class="text-right hidden sm:block">
                    <p class="text-[11px] font-black text-navy-900 dark:text-white" id="mini-clock">00:00 WITA</p>
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-tighter">{{ now()->translatedFormat('l, d F Y') }}</p>
                </div>
                <div class="h-8 w-[1px] bg-slate-100"></div>
                <a href="{{ route('surveyor.profile') }}" class="flex items-center gap-3">
                    <div class="text-right">
                        <p class="text-[10px] font-black text-navy-900 dark:text-white leading-none uppercase">{{ auth()->user()->name }}</p>
                        <p class="text-[8px] font-bold text-emerald-500 uppercase mt-1">Online</p>
                    </div>
                    <div class="w-10 h-10 bg-navy-900 rounded-xl flex items-center justify-center text-gold-500 border border-navy-800 overflow-hidden shadow-md">
                        @if(auth()->user()->profile_photo)
                            <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}" class="w-full h-full object-cover">
                        @else
                            <i class="fas fa-user-circle text-xl text-gold-500"></i>
                        @endif
                    </div>
                </a>
            </div>
        </header>

        <div class="flex-1 overflow-y-auto custom-scrollbar p-8 pb-16">
            <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                {{-- KOLOM KIRI (Visual & AI) --}}
                <div class="lg:col-span-1 space-y-6">
                    
                    {{-- Preview Gambar --}}
                    <div class="bg-white dark:bg-[#1e1b4b] rounded-[2.5rem] p-4 border border-slate-100 dark:border-white/10 shadow-sm overflow-hidden hover:shadow-md transition-shadow">
                        <div class="relative aspect-[3/4] w-full rounded-[2rem] overflow-hidden bg-navy-950 group flex items-center justify-center">
                            @php $cleanPath = str_replace('\\', '/', $infrastruktur->foto_terbaru); @endphp
                            <img src="{{ asset('storage/' . (str_contains($cleanPath, 'infrastruktur/') ? $cleanPath : 'infrastruktur/' . $cleanPath)) }}" class="max-w-full max-h-full object-contain transition-transform duration-500 group-hover:scale-105">
                            
                            {{-- Overlay Deteksi AI --}}
                            @php 
                                $hasilAi = $infrastruktur->analisis;
                                $hasilCnn = $infrastruktur->cnn;
                            @endphp

                            @if(strtolower($infrastruktur->kondisi) != 'baik' && strtolower($infrastruktur->kondisi) != 'menunggu ai')
                                <div class="absolute inset-0 flex items-center justify-center pointer-events-none p-6">
                                    <div class="relative w-[50%] h-[50%] border-2 border-red-500/50 bg-red-500/5 animate-pulse">
                                        <div class="absolute -top-1 -left-1 w-3 h-3 border-t-[3px] border-l-[3px] border-red-500"></div>
                                        <div class="absolute -top-1 -right-1 w-3 h-3 border-t-[3px] border-r-[3px] border-red-500"></div>
                                        <div class="absolute -bottom-1 -left-1 w-3 h-3 border-b-[3px] border-l-[3px] border-red-500"></div>
                                        <div class="absolute -bottom-1 -right-1 w-3 h-3 border-b-[3px] border-r-[3px] border-red-500"></div>
                                        
                                        <div class="absolute -top-6 left-0 bg-red-500 text-white text-[8px] font-black px-2 py-0.5 rounded-md shadow-lg tracking-widest">
                                            KERUSAKAN TERDETEKSI ({{ round(($hasilCnn->skor_cnn ?? 0) * 100) }}%)
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div class="absolute inset-0 bg-navy-900/60 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all backdrop-blur-sm">
                                <a href="{{ asset('storage/' . (str_contains($cleanPath, 'infrastruktur/') ? $cleanPath : 'infrastruktur/' . $cleanPath)) }}" target="_blank" class="bg-gold-500 text-white px-5 py-2.5 rounded-xl text-[9px] font-black uppercase tracking-widest hover:bg-gold-600 hover:scale-105 transition-all shadow-xl">
                                    <i class="fas fa-external-link-alt mr-2"></i> Buka Resolusi Penuh
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- Panel Hybrid AI --}}
                    <div class="bg-navy-900 rounded-[2.5rem] p-8 text-white shadow-xl relative overflow-hidden border border-navy-800">
                        <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-gold-500/10 rounded-full blur-3xl"></div>
                        <h4 class="text-[10px] font-black text-gold-500 uppercase tracking-[0.2em] mb-8 flex items-center gap-3">
                            <i class="fas fa-clipboard-check text-sm"></i> Status Kondisi
                        </h4>
                        
                        <div class="space-y-8 relative z-10">
                            {{-- CNN --}}
                            <div class="relative">
                                <div class="flex justify-between items-end mb-2">
                                    <div class="flex items-center gap-2">
                                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Analisis Visual Foto</p>
                                    </div>
                                    <p class="text-xl font-black text-gold-500">{{ $hasilCnn ? round($hasilCnn->skor_cnn * 100) : '0' }}%</p>
                                </div>
                                <div class="w-full bg-white/5 dark:bg-[#1e1b4b]/5 h-2 rounded-full overflow-hidden border border-white/5">
                                    <div class="bg-gold-500 h-full shadow-[0_0_10px_rgba(197,160,89,0.5)]" style="width: {{ $hasilCnn ? ($hasilCnn->skor_cnn * 100) : '0' }}%"></div>
                                </div>
                                <p class="text-[8px] font-bold text-slate-400 mt-2 italic text-right">{{ $hasilCnn->label_kondisi ?? 'Memeriksa visual lapangan...' }}</p>
                            </div>
                            
                            {{-- D-Tree --}}
                            <div class="relative">
                                <div class="flex justify-between items-end mb-2">
                                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Skor Prioritas Teknis</p>
                                    <p class="text-xl font-black text-white">{{ $infrastruktur->analisis->skor_dt ?? '0' }}<span class="text-xs text-slate-500 ml-0.5">/100</span></p>
                                </div>
                                <div class="w-full bg-white/5 dark:bg-[#1e1b4b]/5 h-2 rounded-full overflow-hidden border border-white/5">
                                    @php $dtColor = ($infrastruktur->analisis->label_prioritas ?? '') == 'Rusak Berat' ? 'bg-red-500' : (($infrastruktur->analisis->label_prioritas ?? '') == 'Rusak Sedang' ? 'bg-amber-500' : 'bg-emerald-500'); @endphp
                                    <div class="{{ $dtColor }} h-full" style="width: {{ $infrastruktur->analisis->skor_dt ?? '0' }}%"></div>
                                </div>
                                <p class="text-[8px] font-bold mt-2 italic text-right {{ ($infrastruktur->analisis->label_prioritas ?? '') == 'Rusak Berat' ? 'text-red-400' : (($infrastruktur->analisis->label_prioritas ?? '') == 'Rusak Sedang' ? 'text-amber-400' : 'text-emerald-400') }}">
                                    Label: {{ $infrastruktur->analisis->label_prioritas ?? 'Menunggu Status...' }}
                                </p>
                            </div>

                            <div class="pt-4 border-t border-white/10 space-y-2">
                                <p class="text-[9px] font-black text-gold-500 uppercase tracking-widest">Rekomendasi Penanganan</p>
                                <p class="text-[11px] font-medium text-slate-300 leading-relaxed bg-white/5 dark:bg-[#1e1b4b]/5 p-4 rounded-2xl border border-white/5">
                                    {{ $infrastruktur->analisis->rekomendasi ?? 'Tindakan rekomendasi belum tersedia.' }}
                                </p>
                            </div>

                            <div class="pt-4 border-t border-white/5">
                                <div class="flex items-center justify-between mb-1">
                                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Status Laporan</p>
                                    @if($infrastruktur->status_validasi == 'Rejected')
                                        <span class="px-3 py-1 bg-red-500/10 border border-red-500/20 rounded-lg text-[8px] font-black uppercase tracking-widest text-red-400">Ditolak</span>
                                    @elseif($infrastruktur->status_validasi == 'Validated')
                                        <span class="px-3 py-1 bg-emerald-500/10 border border-emerald-500/20 rounded-lg text-[8px] font-black uppercase tracking-widest text-emerald-400">Di-ACC Tim Teknis</span>
                                    @elseif($infrastruktur->status_verifikasi == 'Verified')
                                        <span class="px-3 py-1 bg-blue-500/10 border border-blue-500/20 rounded-lg text-[8px] font-black uppercase tracking-widest text-blue-400">Terverifikasi Admin</span>
                                    @else
                                        <span class="px-3 py-1 bg-white/5 dark:bg-[#1e1b4b]/5 border border-white/10 rounded-lg text-[8px] font-black uppercase tracking-widest text-slate-400">Pending</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Catatan Eksekutif --}}
                @if($infrastruktur->alasan_penolakan)
                <div class="bg-amber-50 rounded-[2.5rem] p-6 border border-amber-100 shadow-sm relative overflow-hidden lg:col-span-1">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-amber-500/5 rounded-bl-full"></div>
                    <h4 class="text-[10px] font-black text-amber-900 uppercase tracking-widest mb-3 flex items-center gap-2">
                        <i class="fas fa-comment-dots text-amber-500"></i> Catatan Eksekutif (Tim Teknis)
                    </h4>
                    <div class="p-4 bg-white/60 dark:bg-[#1e1b4b]/60 rounded-2xl border border-amber-200/50">
                        <p class="text-xs font-bold text-slate-600 dark:text-slate-400 leading-relaxed">{{ $infrastruktur->alasan_penolakan }}</p>
                    </div>
                </div>
                @endif

                {{-- KOLOM KANAN (Detail Informasi) --}}
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white dark:bg-[#1e1b4b] rounded-[2.5rem] p-8 lg:p-10 border border-slate-100 dark:border-white/10 shadow-sm hover:shadow-md transition-shadow space-y-8">
                        
                        {{-- Header Judul --}}
                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-4 border-b border-slate-50 pb-6">
                            <div>
                                <div class="inline-flex items-center gap-2 px-3 py-1 bg-navy-50 dark:bg-navy-900 border border-navy-100 rounded-lg mb-3">
                                    <i class="fas fa-layer-group text-[9px] text-gold-500"></i>
                                    <p class="text-[9px] font-black text-navy-900 dark:text-white uppercase tracking-widest">{{ ucfirst($infrastruktur->jenis) }}</p>
                                </div>
                                <h3 class="text-2xl lg:text-3xl font-black text-navy-900 dark:text-white leading-tight">{{ $infrastruktur->nama_objek ?? $infrastruktur->nama_infrastruktur }}</h3>
                            </div>
                            <div class="sm:text-right shrink-0">
                                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Diinput Pada</p>
                                <p class="text-xs font-black text-navy-900 dark:text-white mt-1">{{ $infrastruktur->created_at->translatedFormat('d M Y, H:i') }}</p>
                            </div>
                        </div>

                        {{-- Info Geospasial --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div class="bg-slate-50 dark:bg-[#0f0e2c] p-5 rounded-[1.5rem] border border-slate-200 dark:border-white/20 flex items-center gap-4 hover:border-gold-300 transition-colors">
                                <div class="w-12 h-12 rounded-2xl bg-white dark:bg-[#1e1b4b] flex items-center justify-center text-gold-500 shadow-sm">
                                    <i class="fas fa-map text-lg"></i>
                                </div>
                                <div>
                                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Kecamatan</p>
                                    <p class="text-sm font-black text-navy-900 dark:text-white mt-0.5">{{ $infrastruktur->kelurahan->kecamatan->nama_kecamatan ?? '-' }}</p>
                                </div>
                            </div>
                            <div class="bg-slate-50 dark:bg-[#0f0e2c] p-5 rounded-[1.5rem] border border-slate-200 dark:border-white/20 flex items-center gap-4 hover:border-gold-300 transition-colors">
                                <div class="w-12 h-12 rounded-2xl bg-white dark:bg-[#1e1b4b] flex items-center justify-center text-gold-500 shadow-sm">
                                    <i class="fas fa-city text-lg"></i>
                                </div>
                                <div>
                                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Kelurahan</p>
                                    <p class="text-sm font-black text-navy-900 dark:text-white mt-0.5">{{ $infrastruktur->kelurahan->nama_kelurahan ?? '-' }}</p>
                                </div>
                            </div>
                        </div>

                        {{-- Spesifikasi Teknis Infrastruktur --}}
                        <div class="pt-2">
                            <h4 class="text-[11px] font-black text-navy-900 dark:text-white uppercase tracking-widest mb-4 flex items-center gap-2">
                                <i class="fas fa-ruler-combined text-gold-500"></i> Spesifikasi & Dimensi Lapangan
                            </h4>
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                                <div class="bg-white dark:bg-[#1e1b4b] p-4 rounded-[1.5rem] border border-slate-200 dark:border-white/20 shadow-sm text-center">
                                    <span class="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Panjang</span>
                                    <span class="text-xs font-black text-navy-900 dark:text-white">{{ $infrastruktur->panjang ?? '0' }} m</span>
                                </div>
                                <div class="bg-white dark:bg-[#1e1b4b] p-4 rounded-[1.5rem] border border-slate-200 dark:border-white/20 shadow-sm text-center">
                                    <span class="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Lebar</span>
                                    <span class="text-xs font-black text-navy-900 dark:text-white">{{ $infrastruktur->lebar ?? '0' }} m</span>
                                </div>
                                <div class="bg-white dark:bg-[#1e1b4b] p-4 rounded-[1.5rem] border border-slate-200 dark:border-white/20 shadow-sm text-center">
                                    <span class="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Material</span>
                                    <span class="text-[11px] font-black text-navy-900 dark:text-white">{{ $infrastruktur->material_eksisting ?? '-' }}</span>
                                </div>
                                <div class="bg-white dark:bg-[#1e1b4b] p-4 rounded-[1.5rem] border border-slate-200 dark:border-white/20 shadow-sm text-center">
                                    <span class="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Drainase</span>
                                    @if($infrastruktur->has_drainase == 'ya')
                                        <span class="inline-flex px-2 py-0.5 bg-emerald-50 text-emerald-600 rounded text-[10px] font-black uppercase">Tersedia</span>
                                    @else
                                        <span class="inline-flex px-2 py-0.5 bg-slate-50 dark:bg-[#0f0e2c] text-slate-500 rounded text-[10px] font-black uppercase">Tidak</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Deskripsi Lapangan --}}
                        <div class="pt-2">
                            <h4 class="text-[11px] font-black text-navy-900 dark:text-white uppercase tracking-widest mb-4 flex items-center gap-2">
                                <i class="fas fa-comment-dots text-gold-500"></i> Catatan Deskripsi Kerusakan
                            </h4>
                            <div class="bg-slate-50 dark:bg-[#0f0e2c] p-5 rounded-[1.5rem] border border-slate-200 dark:border-white/20 relative">
                                <i class="fas fa-quote-left absolute top-4 right-5 text-2xl text-slate-200"></i>
                                <p class="text-sm font-medium text-slate-700 dark:text-slate-300 leading-relaxed pr-8">
                                    "{{ $infrastruktur->kondisi }}"
                                </p>
                            </div>
                        </div>

                        {{-- Peta Lokasi --}}
                        <div class="pt-2">
                            <h4 class="text-[11px] font-black text-navy-900 dark:text-white uppercase tracking-widest mb-4 flex items-center gap-2">
                                <i class="fas fa-satellite text-gold-500"></i> Titik Koordinat Geospasial
                            </h4>
                            <div class="relative rounded-[2rem] border-[6px] border-slate-50 shadow-inner overflow-hidden mb-2">
                                <div id="map" class="h-[280px] w-full z-0 bg-slate-100"></div>
                            </div>
                            <p class="text-[10px] font-black text-slate-400 text-center tracking-widest mt-3">
                                LAT: <span class="text-navy-900 dark:text-white">{{ $infrastruktur->latitude }}</span> &nbsp;|&nbsp; LNG: <span class="text-navy-900 dark:text-white">{{ $infrastruktur->longitude }}</span>
                            </p>
                        </div>

                    </div>
                </div>

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

        const lat = {{ $infrastruktur->latitude }};
        const lng = {{ $infrastruktur->longitude }};
        
        // Disable scroll wheel agar halaman tidak tergulung tak sengaja
        const map = L.map('map', {
            zoomControl: true,
            scrollWheelZoom: false 
        }).setView([lat, lng], 16);

        L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
            attribution: ''
        }).addTo(map);

        // Marker elegan (Gold) untuk titik lokasi Surveyor
        const markerHtml = `
            <div class="w-8 h-8 bg-gold-500 rounded-full border-[3px] border-white shadow-lg flex items-center justify-center text-white relative">
                <div class="absolute inset-0 rounded-full border border-gold-200 animate-ping"></div>
                <i class="fas fa-location-dot text-sm drop-shadow-md"></i>
            </div>
        `;

        const icon = L.divIcon({
            html: markerHtml,
            className: '',
            iconSize: [32, 32],
            iconAnchor: [16, 32]
        });

        L.marker([lat, lng], {icon: icon}).addTo(map);
    </script>
</body>
</html>
