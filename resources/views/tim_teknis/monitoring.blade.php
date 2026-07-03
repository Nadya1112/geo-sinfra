<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitoring Peta | GEO-SINFRA</title>
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
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <style> body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
<style>
    
    
@media (max-width: 767px) { html { font-size: 12px; } }
</style>
</head>
<body class="bg-slate-50 dark:bg-[#0f0e2c] flex h-screen overflow-hidden text-slate-800 dark:text-white text-left font-sans dark:bg-navy-950 transition-colors duration-300">

    @include('tim_teknis.partials.sidebar')

    <main class="flex-1 flex flex-col h-screen overflow-hidden">
        <header class="bg-white dark:bg-[#1e1b4b] border-b border-slate-100 dark:border-white/10 px-4 pl-14 md:pl-4 md:px-8 py-3 md:py-4 flex justify-between items-center z-40 sticky top-0">
            <div class="flex items-center gap-2 md:gap-4 min-w-0">
                <a href="{{ route('tim_teknis.dashboard') }}" class="w-10 h-10 flex items-center justify-center bg-slate-50 dark:bg-[#0f0e2c] text-slate-400 rounded-xl hover:bg-gold-50 hover:text-gold-500 transition-all border border-slate-100 dark:border-white/10 hidden md:flex flex-shrink-0">
                    <i class="fas fa-arrow-left text-sm"></i>
                </a>
                <div class="min-w-0">
                    <p class="text-[9px] md:text-xs font-extrabold text-rose-500 uppercase tracking-[0.15em] md:tracking-[0.2em] mb-0.5 md:mb-1"><i class="fas fa-satellite-dish mr-1 animate-pulse"></i> Executive WebGIS</p>
                    <h2 class="text-sm md:text-xl font-black text-navy-900 dark:text-white leading-tight truncate">Peta Sebaran</h2>
                </div>
            </div>
            
            <div class="flex items-center gap-2 md:gap-6 flex-shrink-0">
                <div class="text-right hidden md:block">
                    <p class="text-xs font-black text-navy-900 dark:text-white" id="mini-clock">00:00 WITA</p>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-tighter">{{ now()->translatedFormat('l, d F Y') }}</p>
                </div>
                <div class="h-8 w-[1px] bg-slate-100 hidden md:block"></div>
                <a href="{{ route('tim_teknis.profile') }}" class="flex items-center gap-2 md:gap-3 group">
                    <div class="text-right hidden md:block">
                        <p class="text-sm font-black text-navy-900 dark:text-white leading-none uppercase group-hover:text-gold-500 transition-colors max-w-[200px] truncate">{{ auth()->user()->name }}</p>
                        <p class="text-xs font-bold text-emerald-500 uppercase mt-0.5">ONLINE</p>
                    </div>
                    <div class="w-9 h-9 md:w-10 md:h-10 bg-navy-900 rounded-xl flex items-center justify-center text-gold-500 shadow-md group-hover:shadow-lg transition-all overflow-hidden flex-shrink-0">
                        @if(auth()->user()->profile_photo)
                            <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}" class="w-full h-full object-cover">
                        @else
                            <i class="fas fa-user-circle text-lg md:text-xl"></i>
                        @endif
                    </div>
                </a>
            </div>
        </header>

        <div class="flex-1 relative">
            <div id="main-map" class="absolute inset-0 z-0"></div>

            <!-- Custom Zoom Controls -->
            <div class="absolute top-4 left-4 z-10 flex flex-col gap-1.5">
                <button onclick="map.zoomIn()" class="w-8 h-8 bg-navy-900/80 backdrop-blur-xl rounded-xl border border-white/10 shadow-2xl flex items-center justify-center text-white hover:bg-gold-500 transition-all group">
                    <i class="fas fa-plus text-xs group-hover:scale-110 transition-transform"></i>
                </button>
                <button onclick="map.zoomOut()" class="w-8 h-8 bg-navy-900/80 backdrop-blur-xl rounded-xl border border-white/10 shadow-2xl flex items-center justify-center text-white hover:bg-gold-500 transition-all group">
                    <i class="fas fa-minus text-xs group-hover:scale-110 transition-transform"></i>
                </button>
            </div>

            <!-- Stats & Legend UI Bottom Left -->
            <div class="absolute bottom-28 md:bottom-6 left-4 z-10">
                <div id="condition-card" class="bg-navy-900/90 backdrop-blur-xl p-1 rounded-2xl border border-white/10 shadow-2xl min-w-[140px] transition-all duration-300">
                    <button onclick="toggleMenu('condition-options')" class="w-full px-3 py-2 rounded-xl text-xs font-black uppercase tracking-wider bg-white/5 text-white flex items-center justify-between hover:bg-white/10 transition-all group border border-white/5">
                        <div class="flex items-center gap-1.5">
                            <div class="w-4 h-4 bg-gold-500/20 text-gold-400 rounded flex items-center justify-center">
                                <i class="fas fa-chart-pie text-xs"></i>
                            </div>
                            <span id="current-cond-label">Statistik</span>
                        </div>
                        <i class="fas fa-chevron-up text-[6px]"></i>
                    </button>
                    
                    <div id="condition-options" class="mt-0.5 p-0.5 flex flex-col">
                        <div class="w-full px-3 py-1.5 rounded-lg text-[7px] font-black uppercase tracking-wider text-gray-300 flex items-center justify-between">
                            <span>Total</span>
                            <span id="stat-total" class="text-[7px] font-black text-blue-400 bg-blue-500/10 px-1.5 py-0.5 rounded border border-blue-400/20">0</span>
                        </div>
                        <div class="w-full px-3 py-1.5 rounded-lg text-[7px] font-black uppercase tracking-wider text-gray-400 flex items-center justify-between">
                            <div class="flex items-center gap-1.5">
                                <div class="w-1.5 h-1.5 bg-[#059669] rounded-full"></div>
                                <span>Baik</span>
                            </div>
                            <span id="stat-baik" class="text-[7px] font-black text-[#059669] bg-[#059669]/10 px-1.5 py-0.5 rounded border border-[#059669]/20">0</span>
                        </div>
                        <div class="w-full px-3 py-1.5 rounded-lg text-[7px] font-black uppercase tracking-wider text-gray-400 flex items-center justify-between">
                            <div class="flex items-center gap-1.5">
                                <div class="w-1.5 h-1.5 bg-[#d97706] rounded-full"></div>
                                <span>Sedang</span>
                            </div>
                            <span id="stat-sedang" class="text-[7px] font-black text-[#d97706] bg-[#d97706]/10 px-1.5 py-0.5 rounded border border-[#d97706]/20">0</span>
                        </div>
                        <div class="w-full px-3 py-1.5 rounded-lg text-[7px] font-black uppercase tracking-wider text-gray-400 flex items-center justify-between">
                            <div class="flex items-center gap-1.5">
                                <div class="w-1.5 h-1.5 bg-[#be123c] rounded-full"></div>
                                <span>Berat</span>
                            </div>
                            <span id="stat-berat" class="text-[7px] font-black text-[#be123c] bg-[#be123c]/10 px-1.5 py-0.5 rounded border border-[#be123c]/20">0</span>
                        </div>
                    </div>
            </div>

            <!-- Floating Filters Right (Combined) -->
            <div class="absolute top-4 right-4 z-10">
                <div class="bg-[#1e1b4b]/95 backdrop-blur-xl p-2 rounded-2xl border border-white/10 shadow-2xl w-40 flex flex-col gap-1.5">
                    
                    <!-- Kategori Section -->
                    <div id="category-card" class="w-full relative">
                        <button onclick="toggleMenu('category-options')" class="w-full px-3 py-2 rounded-xl text-[10px] font-black uppercase tracking-wider bg-white/5 text-white flex items-center justify-between hover:bg-white/10 transition-all border border-white/5 shadow-inner">
                            <div class="flex items-center gap-1.5">
                                <i class="fas fa-layer-group text-xs opacity-90 text-gold-500"></i>
                                <span id="current-cat-label" class="truncate max-w-[80px] text-[9px]">SEMUA KATEGORI</span>
                            </div>
                            <i class="fas fa-chevron-down text-[8px] text-slate-400"></i>
                        </button>
                        <div id="category-options" class="hidden mt-2 p-1.5 bg-[#0f0e2c]/90 rounded-xl border border-white/5 flex flex-col gap-1 max-h-[40vh] overflow-y-auto custom-scrollbar">
                            <button onclick="toggleType('Semua')" class="type-btn w-full px-3 py-1.5 rounded-lg text-[7px] font-black uppercase tracking-wider text-gray-400 hover:bg-white/10 transition-all flex items-center justify-between group" data-id="Semua">
                                <div class="flex items-center gap-1.5">
                                    <div class="w-2.5 h-2.5 rounded border border-white/20 flex items-center justify-center group-hover:border-gold-400 transition-colors">
                                        <i class="fas fa-check text-[5px] text-gold-400 check-icon" style="opacity:1"></i>
                                    </div>
                                    <span class="group-hover:text-white transition-colors">Semua Objek</span>
                                </div>
                                <div class="w-2.5 h-2.5 rounded bg-gray-500/30"></div>
                            </button>
                            <button onclick="toggleType('Jalan')" class="type-btn w-full px-3 py-1.5 rounded-lg text-[7px] font-black uppercase tracking-wider text-gray-400 hover:bg-white/10 transition-all flex items-center justify-between group" data-type="Jalan">
                                <div class="flex items-center gap-1.5">
                                    <div class="w-2.5 h-2.5 rounded border border-white/20 flex items-center justify-center group-hover:border-blue-400 transition-colors">
                                        <i class="fas fa-check text-[5px] text-blue-400 check-icon" style="opacity:1"></i>
                                    </div>
                                    <span class="group-hover:text-white transition-colors">Jalan</span>
                                </div>
                                <div class="w-2.5 h-2.5 rounded bg-blue-500"></div>
                            </button>
                            <button onclick="toggleType('Jembatan')" class="type-btn w-full px-3 py-1.5 rounded-lg text-[7px] font-black uppercase tracking-wider text-gray-400 hover:bg-white/10 transition-all flex items-center justify-between group" data-type="Jembatan">
                                <div class="flex items-center gap-1.5">
                                    <div class="w-2.5 h-2.5 rounded border border-white/20 flex items-center justify-center group-hover:border-indigo-400 transition-colors">
                                        <i class="fas fa-check text-[5px] text-indigo-400 check-icon" style="opacity:1"></i>
                                    </div>
                                    <span class="group-hover:text-white transition-colors">Jembatan</span>
                                </div>
                                <div class="w-2.5 h-2.5 rounded bg-indigo-500"></div>
                            </button>
                            <button onclick="toggleType('Titian')" class="type-btn w-full px-3 py-1.5 rounded-lg text-[7px] font-black uppercase tracking-wider text-gray-400 hover:bg-white/10 transition-all flex items-center justify-between group" data-type="Titian">
                                <div class="flex items-center gap-1.5">
                                    <div class="w-2.5 h-2.5 rounded border border-white/20 flex items-center justify-center group-hover:border-amber-400 transition-colors">
                                        <i class="fas fa-check text-[5px] text-amber-400 check-icon" style="opacity:1"></i>
                                    </div>
                                    <span class="group-hover:text-white transition-colors">Titian</span>
                                </div>
                                <div class="w-2.5 h-2.5 rounded bg-amber-500"></div>
                            </button>
                            <div class="h-[1px] bg-white/5 my-0.5"></div>
                            <button onclick="toggleKelurahanPoints()" class="w-full px-3 py-1.5 rounded-lg text-[7px] font-black uppercase tracking-wider text-gray-400 hover:bg-white/10 transition-all flex items-center justify-between group" id="kel-toggle-btn">
                                <div class="flex items-center gap-1.5">
                                    <div class="w-2.5 h-2.5 rounded border border-white/20 flex items-center justify-center group-hover:border-emerald-400 transition-colors">
                                        <i class="fas fa-check text-[5px] text-emerald-400" id="kel-check-icon" style="opacity:0"></i>
                                    </div>
                                    <span class="group-hover:text-white transition-colors">Kelurahan</span>
                                </div>
                                <i class="fas fa-home text-emerald-500 text-xs"></i>
                            </button>
                        </div>
                    </div>

                    <div class="h-[1px] bg-white/5 w-full"></div>

                    <!-- Kecamatan Section -->
                    <div id="territory-card" class="w-full relative">
                        <button onclick="toggleMenu('territory-options')" class="w-full px-3 py-2 rounded-xl text-[10px] font-black uppercase tracking-wider bg-white/5 text-white flex items-center justify-between hover:bg-white/10 transition-all border border-white/5 shadow-inner">
                            <div class="flex items-center gap-1.5">
                                <i class="fas fa-map-location-dot text-xs opacity-90 text-gold-500"></i>
                                <span id="current-kec-label" class="truncate max-w-[80px] text-[9px]">SEMUA WILAYAH</span>
                            </div>
                            <i class="fas fa-chevron-down text-[8px] text-slate-400"></i>
                        </button>
                        <div id="territory-options" class="hidden mt-0.5 p-0.5 flex-col overflow-y-auto custom-scrollbar" style="max-height: 20vh;">
                            <!-- Select All Territories -->
                            <button onclick="toggleKecamatan('Semua')" class="w-full px-3 py-1.5 rounded-lg text-[7px] font-black uppercase tracking-wider text-emerald-400 hover:bg-white/10 transition-all flex items-center justify-between group border-b border-white/5 mb-1" id="btn-select-all-kec">
                                <div class="flex items-center gap-1.5">
                                    <div class="w-2.5 h-2.5 rounded border border-emerald-400/50 flex items-center justify-center group-hover:border-emerald-400 transition-colors">
                                        <i class="fas fa-check text-[5px] text-emerald-400 check-icon" id="icon-select-all-kec" style="opacity:1"></i>
                                    </div>
                                    <span class="group-hover:text-white transition-colors">Pilih Semua</span>
                                </div>
                            </button>

                            <button onclick="toggleKecamatan('Semua')" class="kec-btn w-full px-3 py-1.5 rounded-lg text-[7px] font-black uppercase tracking-wider text-slate-400 hover:bg-white/10 transition-all flex items-center justify-between group hidden" data-id="Semua">
                                <div class="flex items-center gap-1.5">
                                    <div class="w-2.5 h-2.5 rounded border border-white/20 flex items-center justify-center group-hover:border-gold-400 transition-colors">
                                        <i class="fas fa-check text-[5px] text-gold-400 check-icon" style="opacity:1"></i>
                                    </div>
                                    <span class="group-hover:text-white transition-colors">Semua Wilayah</span>
                                </div>
                            </button>
                            @foreach($kecamatan as $kec)
                            <button onclick="toggleKecamatan('{{ $kec->id_kecamatan }}')" class="kec-btn w-full px-3 py-1.5 rounded-lg text-[7px] font-black uppercase tracking-wider text-slate-400 hover:bg-white/10 transition-all flex items-center justify-between group" data-id="{{ $kec->id_kecamatan }}">
                                <div class="flex items-center gap-1.5">
                                    <div class="w-2.5 h-2.5 rounded border border-white/20 flex items-center justify-center group-hover:border-gold-400 transition-colors">
                                        <i class="fas fa-check text-[5px] text-gold-400 check-icon" style="opacity:1"></i>
                                    </div>
                                    <span class="truncate max-w-[75px] group-hover:text-white transition-colors text-left">{{ $kec->nama_kecamatan }}</span>
                                </div>
                                <div class="w-2.5 h-2.5 rounded border border-white/10" style="background-color: {{ $kec->warna ?? '#6366f1' }};"></div>
                            </button>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <!-- Layer Switcher -->
            <div class="absolute bottom-28 md:bottom-6 right-4 z-10">
                <div id="layer-card" class="bg-[#1e1b4b]/80 backdrop-blur-xl p-1.5 rounded-2xl border border-white/10 shadow-2xl transition-all duration-300">
                    <button onclick="toggleMenu('layer-options')" class="w-9 h-9 rounded-full bg-white/10 text-white flex items-center justify-center hover:bg-white/20 transition-all group border border-white/5">
                        <i class="fas fa-layer-group text-xs group-hover:scale-110 transition-transform"></i>
                    </button>
                    <div id="layer-options" class="hidden absolute bottom-full right-0 mb-2 p-1.5 bg-[#1e1b4b]/90 backdrop-blur-xl rounded-2xl border border-white/10 shadow-2xl flex flex-col gap-1 min-w-[120px]">
                        <button onclick="changeBaseLayer('greyscale')" class="flex items-center gap-2 px-3 py-2 rounded-xl hover:bg-white/10 transition-all group">
                            <div class="w-6 h-6 rounded-md bg-gray-500/20 flex items-center justify-center text-gray-400 group-hover:bg-gray-500 group-hover:text-white transition-all">
                                <i class="fas fa-adjust text-xs"></i>
                            </div>
                            <span class="text-[7px] font-black uppercase tracking-wider text-gray-300 group-hover:text-white">Greyscale</span>
                        </button>
                        <button onclick="changeBaseLayer('satellite')" class="flex items-center gap-2 px-3 py-2 rounded-xl hover:bg-white/10 transition-all group">
                            <div class="w-6 h-6 rounded-md bg-emerald-500/20 flex items-center justify-center text-emerald-400 group-hover:bg-emerald-500 group-hover:text-white transition-all">
                                <i class="fas fa-satellite text-xs"></i>
                            </div>
                            <span class="text-[7px] font-black uppercase tracking-wider text-gray-300 group-hover:text-white">Satelit</span>
                        </button>
                        <button onclick="changeBaseLayer('osm')" class="flex items-center gap-2 px-3 py-2 rounded-xl hover:bg-white/10 transition-all group">
                            <div class="w-6 h-6 rounded-md bg-amber-500/20 flex items-center justify-center text-amber-400 group-hover:bg-amber-500 group-hover:text-white transition-all">
                                <i class="fas fa-map-marked-alt text-xs"></i>
                            </div>
                            <span class="text-[7px] font-black uppercase tracking-wider text-gray-300 group-hover:text-white">OSM Default</span>
                        </button>
                        <button onclick="changeBaseLayer('dark')" class="flex items-center gap-2 px-3 py-2 rounded-xl hover:bg-white/10 transition-all group">
                            <div class="w-6 h-6 rounded-md bg-indigo-500/20 flex items-center justify-center text-indigo-400 group-hover:bg-indigo-500 group-hover:text-white transition-all">
                                <i class="fas fa-moon text-xs"></i>
                            </div>
                            <span class="text-[7px] font-black uppercase tracking-wider text-gray-300 group-hover:text-white">Gelap</span>
                        </button>
                        <button onclick="changeBaseLayer('street')" class="flex items-center gap-2 px-3 py-2 rounded-xl hover:bg-white/10 transition-all group">
                            <div class="w-6 h-6 rounded-md bg-blue-500/20 flex items-center justify-center text-blue-400 group-hover:bg-blue-500 group-hover:text-white transition-all">
                                <i class="fas fa-road text-xs"></i>
                            </div>
                            <span class="text-[7px] font-black uppercase tracking-wider text-gray-300 group-hover:text-white">Jalan</span>
                        </button>
                        <div class="h-[1px] bg-white/10 my-1 mx-2"></div>
                        <button onclick="toggleFloodLayer()" class="flex items-center justify-between px-3 py-2 rounded-xl hover:bg-white/10 transition-all group w-full text-left">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-water text-blue-400 text-xs"></i>
                                <span class="text-[7px] font-black uppercase tracking-wider text-slate-300 group-hover:text-white transition-colors">Rawan Banjir</span>
                            </div>
                            <div class="w-6 h-3 rounded-full bg-slate-700 relative border border-white/10 transition-colors" id="flood-toggle-bg">
                                <div id="flood-toggle-dot" class="absolute left-[2px] top-[2px] w-2 h-2 bg-slate-400 rounded-full transition-all"></div>
                            </div>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        const baseLayers = {
            greyscale: L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png'),
            satellite: L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}'),
            osm: L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png'),
            dark: L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png'),
            street: L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png')
        };

        const map = L.map('main-map', { zoomControl: false, attributionControl: false }).setView([-3.316694, 114.590111], 13);
        let currentBaseLayer = baseLayers.osm.addTo(map);

        // Create panes to manage layering
        map.createPane('polygonsPane');
        map.getPane('polygonsPane').style.zIndex = 400;
        map.createPane('markersPane');
        map.getPane('markersPane').style.zIndex = 650;

        const dataPoints = @json($infrastruktur);
        const kecamatans = @json($kecamatan);
        const kelurahans = @json($kelurahan);
        let activeMarkers = [];
        let kelurahanMarkers = [];
        let kelurahanPolygons = [];
        let showKelurahan = false;
        const geoLayers = {};
        
        // --- Layer Rawan Banjir (Mock) ---
        let showFloodLayer = false;
        const floodLayer = L.layerGroup([
            L.circle([-3.315, 114.590], { color: '#ef4444', fillColor: '#ef4444', fillOpacity: 0.2, weight: 1, radius: 800 }).bindPopup('<div class="text-center"><p class="text-xs font-black text-red-500 uppercase">Zona Merah</p><p class="text-xs">Rawan Banjir Tinggi</p></div>'),
            L.circle([-3.325, 114.598], { color: '#f59e0b', fillColor: '#f59e0b', fillOpacity: 0.2, weight: 1, radius: 1200 }).bindPopup('<div class="text-center"><p class="text-xs font-black text-orange-500 uppercase">Zona Kuning</p><p class="text-xs">Rawan Banjir Sedang</p></div>'),
            L.circle([-3.295, 114.580], { color: '#ef4444', fillColor: '#ef4444', fillOpacity: 0.2, weight: 1, radius: 900 }).bindPopup('<div class="text-center"><p class="text-xs font-black text-red-500 uppercase">Zona Merah</p><p class="text-xs">Rawan Banjir Tinggi</p></div>'),
            L.circle([-3.330, 114.570], { color: '#f59e0b', fillColor: '#f59e0b', fillOpacity: 0.2, weight: 1, radius: 1000 }).bindPopup('<div class="text-center"><p class="text-xs font-black text-orange-500 uppercase">Zona Kuning</p><p class="text-xs">Rawan Banjir Sedang</p></div>')
        ]);

        function toggleFloodLayer() {
            showFloodLayer = !showFloodLayer;
            const bg = document.getElementById('flood-toggle-bg');
            const dot = document.getElementById('flood-toggle-dot');
            
            if(showFloodLayer) {
                map.addLayer(floodLayer);
                bg.classList.replace('bg-slate-700', 'bg-blue-500');
                dot.classList.replace('bg-slate-400', 'bg-white dark:bg-[#1e1b4b]');
                dot.classList.replace('left-[2px]', 'left-[14px]');
            } else {
                map.removeLayer(floodLayer);
                bg.classList.replace('bg-blue-500', 'bg-slate-700');
                dot.classList.replace('bg-white dark:bg-[#1e1b4b]', 'bg-slate-400');
                dot.classList.replace('left-[14px]', 'left-[2px]');
            }
        }
        // ---------------------------------

        // Render Polygons first in lower pane
        kecamatans.forEach(kec => {
            if (kec.geometri) {
                try {
                    const geoData = typeof kec.geometri === 'string' ? JSON.parse(kec.geometri) : kec.geometri;
                    const poly = L.geoJSON(geoData, {
                        pane: 'polygonsPane',
                        style: {
                            fillColor: kec.warna || '#6366f1',
                            weight: 2.5,
                            opacity: 1,
                            color: 'white',
                            fillOpacity: 0.35
                        },
                        interactive: true
                    });

                    poly.on('mouseover', function() {
                        this.setStyle({ fillOpacity: 0.6, weight: 4 });
                    });
                    poly.on('mouseout', function() {
                        this.setStyle({ fillOpacity: 0.35, weight: 2.5 });
                    }); // Do NOT add to map yet — applyFilters() controls visibility

                    poly.bindPopup(`<p class="text-xs font-black text-navy-900 dark:text-white uppercase">${kec.nama_kecamatan}</p>`, { 
                        className: 'custom-polygon-popup', 
                        closeButton: false 
                    });
                    
                    poly.on('mouseover', function() { this.setStyle({ fillOpacity: 0.3, weight: 3 }); });
                    poly.on('mouseout', function() { this.setStyle({ fillOpacity: 0.15, weight: 2 }); });

                    geoLayers[kec.id_kecamatan] = poly;
                } catch (e) { console.error(e); }
            }
        });

        function renderMarkers(points) {
            activeMarkers.forEach(m => map.removeLayer(m));
            activeMarkers = [];

            points.forEach(point => {
                const kondisiAktual = point.analisis?.label_prioritas || point.kondisi;
                let isBaik = kondisiAktual.toLowerCase().includes('baik');
                let isRingan = kondisiAktual.toLowerCase().includes('ringan') || kondisiAktual.toLowerCase().includes('sedang');
                let isBerat = !isBaik && !isRingan;
                
                // Override jika sudah selesai diperbaiki
                const isSelesai = point.status_perbaikan === 'Selesai';
                if (isSelesai) {
                    isBaik = true;
                    isRingan = false;
                    isBerat = false;
                }

                const color = isBaik ? '#10b981' : (isRingan ? '#f59e0b' : '#ef4444');
                const pulseHtml = isBerat ? `<div class="absolute inset-0 rounded-full animate-ping bg-rose-500 opacity-75"></div>` : '';
                
                const icon = L.divIcon({
                    html: `<div class="relative w-[16px] h-[16px] group">
                               ${pulseHtml}
                               <div class="absolute inset-0 rounded-full border-[2.5px] border-white shadow-lg flex items-center justify-center" style="background-color: ${color}; z-index: 10;">
                                    ${isSelesai ? '<i class="fas fa-check text-[7px] text-white"></i>' : ''}
                               </div>
                           </div>`,
                    className: '', iconSize: [16, 16], iconAnchor: [8, 8]
                });
                let imagePath = point.foto_terbaru || '';
                if(imagePath && !imagePath.includes('infrastruktur/')) {
                    imagePath = 'infrastruktur/' + imagePath;
                }
                imagePath = imagePath.replace(/\\/g, '/');
                
                let finalUrl = '';
                const rawJenis = point.jenis || '-';
                if (imagePath) {
                    finalUrl = `/storage/${imagePath}`;
                } else {
                    const type = rawJenis.toLowerCase();
                    let typeStr = 'jalan';
                    if (type.includes('titian')) typeStr = 'titian';
                    else if (type.includes('jembatan')) typeStr = 'jembatan';
                    
                    let condStr = 'baik';
                    const pLower = kondisiAktual.toLowerCase();
                    if (pLower.includes('berat')) condStr = 'rusak_berat';
                    else if (pLower.includes('sedang') || pLower.includes('ringan')) condStr = 'rusak_sedang';

                    finalUrl = `/dummy_${typeStr}_${condStr}.jpg`;
                }

                const popupContent = `
                    <div class="p-1" style="min-width: 240px;">
                        <div class="relative h-32 rounded-2xl bg-slate-100 mb-3 overflow-hidden shadow-inner">
                            <img src="${finalUrl}" class="w-full h-full object-cover" onerror="this.style.display='none'">
                            <div class="absolute top-2 left-2 px-2 py-1 bg-white/90 dark:bg-[#1e1b4b]/90 backdrop-blur-md rounded-lg text-[7px] font-black uppercase tracking-widest text-navy-900 dark:text-white">
                                ${rawJenis}
                            </div>
                        </div>
                        <div class="px-1">
                            <h4 class="text-xs font-black text-navy-900 dark:text-white mb-1">${point.nama_objek || point.nama_infrastruktur || '-'}</h4>
                            <p class="text-xs text-slate-400 font-bold uppercase mb-3">Wilayah: ${point.kelurahan?.nama_kelurahan ?? '-'}</p>
                            
                            <div class="flex items-center gap-2 mb-2">
                                <span class="px-2 py-1 rounded-full text-[7px] font-black uppercase tracking-widest" style="background-color: ${color}15; color: ${color}; border: 1px solid ${color}30;">
                                    ${isSelesai ? 'SUDAH DIPERBAIKI' : kondisiAktual}
                                </span>
                                <span class="px-2 py-1 bg-navy-50 text-navy-600 rounded-full text-[7px] font-black uppercase border border-navy-100">
                                    By: ${point.user?.name ?? 'Surveyor'}
                                </span>
                            </div>

                            <div class="mb-3 flex items-center gap-1.5 text-[8px] font-bold text-slate-400 uppercase">
                                <i class="fas fa-clock"></i>
                                Update: ${new Date(point.updated_at).toLocaleDateString('id-ID', {day: 'numeric', month: 'short', year: 'numeric'})}
                            </div>

                            <a href="/tim-teknis/infrastruktur/${point.id}" class="block w-full py-2 bg-navy-900 text-white rounded-xl text-xs font-black uppercase tracking-widest text-center hover:bg-gold-500 transition-all shadow-lg shadow-navy-900/10">Lihat Detail</a>
                        </div>
                    </div>
                `;

                const marker = L.marker([point.latitude, point.longitude], {
                    icon: icon,
                    pane: 'markersPane'
                }).addTo(map).bindPopup(popupContent, { className: 'premium-popup', maxWidth: 300 });
                activeMarkers.push(marker);
            });
        }

        function renderKelurahanData() {
            kelurahanMarkers.forEach(m => map.removeLayer(m));
            kelurahanPolygons.forEach(p => map.removeLayer(p));
            kelurahanMarkers = [];
            kelurahanPolygons = [];

            if (!showKelurahan) return;

            kelurahans.forEach(kel => {
                // 1. Render Poligon Kelurahan (Hanya Garis Tepi)
                if (kel.geometri) {
                    try {
                        const geoData = typeof kel.geometri === 'string' ? JSON.parse(kel.geometri) : kel.geometri;
                        const poly = L.geoJSON(geoData, {
                            pane: 'polygonsPane',
                            filter: function(feature) {
                                return feature.geometry.type !== 'Point';
                            },
                            style: {
                                fillColor: 'transparent',
                                weight: 2,
                                opacity: 0.8,
                                color: '#94a3b8',
                                fillOpacity: 0,
                                dashArray: '5, 5' // Tetap putus-putus
                            }
                        }).addTo(map);

                        poly.bindPopup(`
                            <div class="px-2 py-0.5 text-center">
                                <p class="text-xs font-black text-slate-500 uppercase tracking-[0.2em] mb-0.5">Kelurahan</p>
                                <p class="text-xs font-black uppercase tracking-widest text-navy-900 dark:text-white">${kel.nama_kelurahan}</p>
                            </div>
                        `, { 
                            className: 'custom-polygon-popup', 
                            closeButton: false,
                            offset: [0, -5]
                        });

                        poly.on('mouseover', function() { this.setStyle({ color: '#f1f5f9', weight: 3, opacity: 1, dashArray: '' }); });
                        poly.on('mouseout', function() { this.setStyle({ color: '#94a3b8', weight: 2, opacity: 0.8, dashArray: '5, 5' }); });

                        kelurahanPolygons.push(poly);
                    } catch (e) { console.error(e); }
                }
            });
        }

        function toggleKelurahanPoints() {
            showKelurahan = !showKelurahan;
            document.getElementById('kel-check-icon').style.opacity = showKelurahan ? '1' : '0';
            document.getElementById('kel-toggle-btn').classList.toggle('text-white', showKelurahan);
            renderKelurahanData();
        }

        // 1. Inisialisasi: Semua Objek & Wilayah Aktif by Default
        const allAvailableTypes = ['Jalan', 'Jembatan', 'Titian'];
        let activeTypes = [...allAvailableTypes];
        let activeKecs = kecamatans.map(k => k.id_kecamatan.toString());
        const totalKec = kecamatans.length;

        function applyFilters() {
            // ... (logika poligon tetap sama)
            Object.keys(geoLayers).forEach(id => {
                if (activeKecs.includes(id.toString())) {
                    if (!map.hasLayer(geoLayers[id])) geoLayers[id].addTo(map);
                } else {
                    if (map.hasLayer(geoLayers[id])) map.removeLayer(geoLayers[id]);
                }
            });

            if (activeTypes.length === 0) {
                renderMarkers([]);
                return;
            }

            const normalisedActiveTypes = activeTypes.map(t => t.toLowerCase().trim());
            let filtered = dataPoints.filter(p => {
                const pType = (p.jenis || '').toLowerCase().trim();
                const typeMatch = normalisedActiveTypes.some(type => pType.includes(type));
                const kecId = p.kelurahan?.id_kecamatan?.toString() || p.id_kecamatan?.toString();
                const kecMatch = !kecId || activeKecs.includes(kecId);
                return typeMatch && kecMatch;
            });
            
            renderMarkers(filtered);
            updateStats(filtered);
        }

        function updateStats(points) {
            const stats = {
                total: points.length,
                baik: points.filter(p => { const k = (p.analisis?.label_prioritas || p.kondisi || '').toLowerCase(); return k.includes('baik'); }).length,
                sedang: points.filter(p => { const k = (p.analisis?.label_prioritas || p.kondisi || '').toLowerCase(); return k.includes('ringan') || k.includes('sedang'); }).length,
                berat: points.filter(p => { const k = (p.analisis?.label_prioritas || p.kondisi || '').toLowerCase(); return k.includes('berat'); }).length
            };

            document.getElementById('stat-total').textContent = stats.total;
            document.getElementById('stat-baik').textContent = stats.baik;
            document.getElementById('stat-sedang').textContent = stats.sedang;
            document.getElementById('stat-berat').textContent = stats.berat;
        }

        function toggleType(type) {
            if (type === 'Semua') {
                activeTypes = activeTypes.length === allAvailableTypes.length ? [] : [...allAvailableTypes];
            } else {
                if (activeTypes.includes(type)) {
                    activeTypes = activeTypes.filter(t => t !== type);
                } else {
                    activeTypes.push(type);
                }
            }

            // Update UI
            const allChecked = activeTypes.length === allAvailableTypes.length;
            document.querySelectorAll('.type-btn').forEach(btn => {
                const bId = btn.getAttribute('data-id');
                const bType = btn.getAttribute('data-type');
                const isActive = bId === 'Semua' ? allChecked : activeTypes.includes(bType);
                const icon = btn.querySelector('.check-icon');
                if (icon) icon.style.opacity = isActive ? '1' : '0';
                btn.classList.toggle('text-white', isActive);
            });

            const label = activeTypes.length === 0 ? 'Kategori Objek' :
                          activeTypes.length === allAvailableTypes.length ? 'Semua Kategori' :
                          activeTypes.join(', ');
            document.getElementById('current-cat-label').textContent = label;
            applyFilters();
        }

        function toggleKecamatan(kecId) {
            if (kecId === 'Semua') {
                if (activeKecs.length === totalKec) {
                    activeKecs = [];
                } else {
                    activeKecs = kecamatans.map(k => k.id_kecamatan.toString());
                }
            } else {
                kecId = kecId.toString();
                if (activeKecs.includes(kecId)) {
                    activeKecs = activeKecs.filter(k => k !== kecId);
                } else {
                    activeKecs.push(kecId);
                    if (geoLayers[kecId]) map.fitBounds(geoLayers[kecId].getBounds(), { padding: [50, 50] });
                }
            }
            applyFilters();
            updateSelectAllStatus();
        }

        function updateSelectAllStatus() {
            const btnAll = document.getElementById('btn-select-all-kec');
            const iconAll = document.getElementById('icon-select-all-kec');
            const isAllSelected = activeKecs.length === totalKec && totalKec > 0;
            
            if (iconAll) iconAll.style.opacity = isAllSelected ? '1' : '0.2';
            if (btnAll) btnAll.classList.toggle('text-emerald-400', isAllSelected);

            document.querySelectorAll('.kec-btn').forEach(btn => {
                const id = btn.getAttribute('data-id');
                if (id === 'Semua') return;
                const isActive = activeKecs.includes(id);
                const icon = btn.querySelector('.check-icon');
                if (icon) icon.style.opacity = isActive ? '1' : '0';
                btn.classList.toggle('text-white', isActive);
            });

            // Update label
            const label = activeKecs.length === 0 ? 'Filter Kecamatan' :
                          activeKecs.length === totalKec ? 'Semua Wilayah' :
                          activeKecs.length + ' Wilayah Dipilih';
            document.getElementById('current-kec-label').textContent = label;
        }

        function toggleMenu(id) { document.getElementById(id).classList.toggle('hidden'); }
        function changeBaseLayer(type) {
            map.removeLayer(currentBaseLayer);
            currentBaseLayer = baseLayers[type].addTo(map);
            toggleMenu('layer-options');
        }

        // Auto-close dropdowns when clicking outside
        document.addEventListener('click', function(e) {
            const filterPanel = document.querySelector('.absolute.top-6.right-6');
            const layerPanel = document.querySelector('.absolute.bottom-10.right-6');
            
            if (filterPanel && !filterPanel.contains(e.target)) {
                document.getElementById('category-options').classList.add('hidden');
                document.getElementById('territory-options').classList.add('hidden');
            }
            if (layerPanel && !layerPanel.contains(e.target)) {
                document.getElementById('layer-options').classList.add('hidden');
            }
        });

        applyFilters(); // Initialize map state correctly
        renderKelurahanData(); // Render kelurahan data initially

        function updateClock() {
            const now = new Date();
            document.getElementById('mini-clock').textContent = `${String(now.getHours()).padStart(2, '0')}:${String(now.getMinutes()).padStart(2, '0')} WITA`;
        }
        setInterval(updateClock, 1000); updateClock();
    </script>

    <style>
        .active-filter { background: rgba(255,255,255,0.15) !important; color: white !important; }
        .active-kec { background: rgba(255,255,255,0.15) !important; color: white !important; }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 10px; }
        .premium-popup .leaflet-popup-content-wrapper { border-radius: 1.5rem; padding: 5px; }
        .premium-popup .leaflet-popup-tip-container { display: none; }
        .custom-polygon-popup .leaflet-popup-content-wrapper { background: rgba(255,255,255,0.9) !important; backdrop-filter: blur(4px); border-radius: 8px !important; padding: 2px !important; }
    </style>
</body>
</html>
