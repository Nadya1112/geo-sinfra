<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=1280">
    <title>Peta Sebaran Saya | GEO-SINFRA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        navy: { 50: '#f4f4fa', 100: '#e9e9f3', 200: '#c7c8e3', 400: '#818cf8', 500: '#6366f1', 600: '#4f46e5', 800: '#1e1b4b', 900: '#0f0e2c', 950: '#070617' },
                        gold: { 50: '#fdfbf7', 100: '#fbf7ed', 400: '#fbbf24', 500: '#c5a059', 600: '#b38f4a', 700: '#9d7c3d' }
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
    <style> body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 flex h-screen overflow-hidden text-gray-800 text-left dark:bg-navy-950 dark:text-white transition-colors duration-300">

    @include('surveyor.partials.sidebar')

    <main class="flex-1 flex flex-col h-screen overflow-hidden">
        <header class="bg-white dark:bg-[#1e1b4b] border-b border-navy-50 px-8 py-5 flex justify-between items-center z-10 shadow-sm relative">
            <div class="flex items-center gap-4">
                <a href="{{ route('surveyor.dashboard') }}" class="w-10 h-10 flex items-center justify-center bg-navy-50 dark:bg-navy-900 text-navy-400 rounded-xl hover:bg-gold-50 hover:text-gold-600 transition-all border border-navy-100">
                    <i class="fas fa-arrow-left text-sm"></i>
                </a>
                <div>
                    <p class="text-[10px] font-extrabold text-gold-500 uppercase tracking-[0.2em] mb-1">Visualisasi Geografis</p>
                    <h2 class="text-xl font-black text-navy-900 dark:text-white leading-none">Peta Sebaran Laporan Saya</h2>
                </div>
            </div>
            
            <div class="flex items-center gap-6">
                <div class="text-right hidden sm:block">
                    <p class="text-[11px] font-black text-navy-900 dark:text-white" id="mini-clock">00:00 WITA</p>
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-tighter">{{ now()->translatedFormat('l, d F Y') }}</p>
                </div>
                <div class="h-8 w-[1px] bg-navy-100"></div>
                <a href="{{ route('surveyor.profile') }}" class="flex items-center gap-3 group">
                    <div class="text-right">
                        <p class="text-[11px] font-black text-navy-900 dark:text-white leading-none uppercase group-hover:text-gold-500 transition-all">{{ auth()->user()->name }}</p>
                        <p class="text-[9px] font-bold text-emerald-500 uppercase mt-1">ONLINE</p>
                    </div>
                    <div class="w-10 h-10 bg-navy-900 rounded-xl flex items-center justify-center text-gold-500 border border-white/10 overflow-hidden hover:shadow-lg hover:shadow-navy-950/20 transition-all shadow-md">
                        @if(auth()->user()->profile_photo)
                            <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}" class="w-full h-full object-cover">
                        @else
                            <i class="fas fa-user-circle text-xl"></i>
                        @endif
                    </div>
                </a>
            </div>
        </header>

        <div class="flex-1 relative">
            <div id="main-map" class="absolute inset-0 z-0"></div>
            


            <!-- Custom Zoom Controls Top Left -->
            <div class="absolute top-4 left-4 z-[9999] flex flex-col gap-1.5 pointer-events-auto">
                <button onclick="map.zoomIn()" class="w-8 h-8 bg-[#1e1b4b]/80 backdrop-blur-xl rounded-xl border border-white/10 shadow-2xl flex items-center justify-center text-white hover:bg-[#1e1b4b] transition-all group">
                    <i class="fas fa-plus text-[9px] group-hover:scale-110 transition-transform"></i>
                </button>
                <button onclick="map.zoomOut()" class="w-8 h-8 bg-[#1e1b4b]/80 backdrop-blur-xl rounded-xl border border-white/10 shadow-2xl flex items-center justify-center text-white hover:bg-[#1e1b4b] transition-all group">
                    <i class="fas fa-minus text-[9px] group-hover:scale-110 transition-transform"></i>
                </button>
            </div>

            <!-- Map Overlay UI Bottom Left -->
            <div class="absolute bottom-6 left-4 z-[9999] pointer-events-auto">
                <div id="condition-card" class="bg-navy-900/90 backdrop-blur-xl p-1 rounded-2xl border border-white/10 shadow-2xl min-w-[140px] transition-all duration-300">
                    <button onclick="toggleConditionMenu()" class="w-full px-3 py-2 rounded-xl text-[8px] font-black uppercase tracking-wider bg-white/5 text-white flex items-center justify-between hover:bg-white/10 transition-all group border border-white/5">
                        <div class="flex items-center gap-1.5">
                            <div class="w-4 h-4 bg-gold-500/20 text-gold-400 rounded flex items-center justify-center">
                                <i class="fas fa-list-check text-[8px]"></i>
                            </div>
                            <span id="current-cond-label">Kondisi</span>
                        </div>
                        <i id="cond-chevron" class="fas fa-chevron-up text-[6px] transition-transform duration-300"></i>
                    </button>
                    
                    <div id="condition-options" class="hidden mt-0.5 p-0.5 flex flex-col">
                        <button onclick="handleConditionSelect('Semua')" class="w-full px-3 py-1.5 rounded-lg text-[7px] font-black uppercase tracking-wider text-gray-300 hover:bg-white/10 transition-all flex items-center justify-between group">
                            <span class="group-hover:text-white transition-colors">Semua</span>
                            <span class="text-[7px] font-black text-blue-400 bg-blue-500/10 px-1.5 py-0.5 rounded border border-blue-400/20">{{ $dataMap->count() }}</span>
                        </button>
                        <button onclick="handleConditionSelect('Baik')" class="w-full px-3 py-1.5 rounded-lg text-[7px] font-black uppercase tracking-wider text-gray-400 hover:bg-[#059669]/10 hover:text-[#059669] transition-all flex items-center justify-between group">
                            <div class="flex items-center gap-1.5">
                                <div class="w-1.5 h-1.5 bg-[#059669] rounded-full"></div>
                                <span class="group-hover:text-[#059669] transition-colors">Baik</span>
                            </div>
                            <span class="text-[7px] font-black text-[#059669] bg-white/5 px-1.5 py-0.5 rounded border border-[#059669]/20">{{ $dataMap->where('kondisi', 'Baik')->count() }}</span>
                        </button>
                        <button onclick="handleConditionSelect('Rusak Sedang')" class="w-full px-3 py-1.5 rounded-lg text-[7px] font-black uppercase tracking-wider text-gray-400 hover:bg-[#d97706]/10 hover:text-[#d97706] transition-all flex items-center justify-between group">
                            <div class="flex items-center gap-1.5">
                                <div class="w-1.5 h-1.5 bg-[#d97706] rounded-full"></div>
                                <span class="group-hover:text-[#d97706] transition-colors">Sedang</span>
                            </div>
                            <span class="text-[7px] font-black text-[#d97706] bg-white/5 px-1.5 py-0.5 rounded border border-[#d97706]/20">{{ $dataMap->where('kondisi', 'Rusak Sedang')->count() }}</span>
                        </button>
                        <button onclick="handleConditionSelect('Rusak Berat')" class="w-full px-3 py-1.5 rounded-lg text-[7px] font-black uppercase tracking-wider text-gray-400 hover:bg-[#be123c]/10 hover:text-[#be123c] transition-all flex items-center justify-between group">
                            <div class="flex items-center gap-1.5">
                                <div class="w-1.5 h-1.5 bg-[#be123c] rounded-full"></div>
                                <span class="group-hover:text-[#be123c] transition-colors">Berat</span>
                            </div>
                            <span class="text-[7px] font-black text-[#be123c] bg-white/5 px-1.5 py-0.5 rounded border border-[#be123c]/20">{{ $dataMap->where('kondisi', 'Rusak Berat')->count() }}</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Floating Filters Right (Combined) -->
            <div class="absolute top-4 right-4 z-[9999] pointer-events-auto">
                <div class="bg-navy-900/90 backdrop-blur-xl p-1 rounded-2xl border border-white/10 shadow-2xl min-w-[150px] transition-all duration-300 max-h-[80vh] flex flex-col">
                    <!-- Category Section -->
                    <div id="category-card" class="p-0.5">
                        <button onclick="toggleCategoryMenu()" class="w-full px-3 py-2 rounded-xl text-[8px] font-black uppercase tracking-wider bg-white/5 text-white flex items-center justify-between hover:bg-white/10 transition-all group border border-white/5">
                            <div class="flex items-center gap-1.5">
                                <i class="fas fa-layer-group text-[8px] opacity-70 text-gold-400"></i>
                                <span id="current-cat-label" class="truncate max-w-[80px]">Semua Kategori</span>
                            </div>
                            <i id="cat-chevron" class="fas fa-chevron-down text-[6px] transition-transform duration-300"></i>
                        </button>
                        
                        <div id="category-options" class="hidden mt-0.5 p-0.5 flex flex-col">
                            <button onclick="handleCategorySelect('Semua')" data-type="Semua" class="cat-opt-btn w-full px-3 py-1.5 rounded-lg text-[7px] font-black uppercase tracking-wider text-gray-400 hover:bg-white/10 transition-all flex items-center justify-between group">
                                <div class="flex items-center gap-1.5">
                                    <div class="w-2.5 h-2.5 rounded border border-white/20 flex items-center justify-center group-hover:border-blue-400 transition-colors">
                                        <i class="fas fa-check text-[5px] text-blue-400 opacity-0 transition-opacity"></i>
                                    </div>
                                    <span class="group-hover:text-white transition-colors">Semua</span>
                                </div>
                                <div class="w-2.5 h-2.5 rounded bg-gray-500/30"></div>
                            </button>
                            <button onclick="handleCategorySelect('Jalan')" data-type="Jalan" class="cat-opt-btn w-full px-3 py-1.5 rounded-lg text-[7px] font-black uppercase tracking-wider text-gray-400 hover:bg-white/10 transition-all flex items-center justify-between group">
                                <div class="flex items-center gap-1.5">
                                    <div class="w-2.5 h-2.5 rounded border border-white/20 flex items-center justify-center group-hover:border-blue-400 transition-colors">
                                        <i class="fas fa-check text-[5px] text-blue-400 opacity-0 transition-opacity"></i>
                                    </div>
                                    <span class="group-hover:text-white transition-colors">Jalan</span>
                                </div>
                                <div class="w-2.5 h-2.5 rounded bg-blue-500"></div>
                            </button>
                            <button onclick="handleCategorySelect('Jembatan')" data-type="Jembatan" class="cat-opt-btn w-full px-3 py-1.5 rounded-lg text-[7px] font-black uppercase tracking-wider text-gray-400 hover:bg-white/10 transition-all flex items-center justify-between group">
                                <div class="flex items-center gap-1.5">
                                    <div class="w-2.5 h-2.5 rounded border border-white/20 flex items-center justify-center group-hover:border-blue-400 transition-colors">
                                        <i class="fas fa-check text-[5px] text-blue-400 opacity-0 transition-opacity"></i>
                                    </div>
                                    <span class="group-hover:text-white transition-colors">Jembatan</span>
                                </div>
                                <div class="w-2.5 h-2.5 rounded bg-emerald-500"></div>
                            </button>

                            <button onclick="handleCategorySelect('Titian')" data-type="Titian" class="cat-opt-btn w-full px-3 py-1.5 rounded-lg text-[7px] font-black uppercase tracking-wider text-gray-400 hover:bg-white/10 transition-all flex items-center justify-between group">
                                <div class="flex items-center gap-1.5">
                                    <div class="w-2.5 h-2.5 rounded border border-white/20 flex items-center justify-center group-hover:border-purple-400 transition-colors">
                                        <i class="fas fa-check text-[5px] text-purple-400 opacity-0 transition-opacity"></i>
                                    </div>
                                    <span class="group-hover:text-white transition-colors">Titian</span>
                                </div>
                                <div class="w-2.5 h-2.5 rounded bg-purple-500"></div>
                            </button>
                            <div class="h-[1px] bg-white/5 my-0.5"></div>
                            <button onclick="toggleKelurahanPoints()" class="w-full px-3 py-1.5 rounded-lg text-[7px] font-black uppercase tracking-wider text-gray-400 hover:bg-white/10 transition-all flex items-center justify-between group" id="kel-toggle-btn">
                                <div class="flex items-center gap-1.5">
                                    <div class="w-2.5 h-2.5 rounded border border-white/20 flex items-center justify-center group-hover:border-emerald-400 transition-colors">
                                        <i class="fas fa-check text-[5px] text-emerald-400" id="kel-check-icon" style="opacity:1"></i>
                                    </div>
                                    <span class="group-hover:text-white transition-colors">Kelurahan</span>
                                </div>
                                <i class="fas fa-home text-emerald-500 text-[8px]"></i>
                            </button>
                        </div>
                    </div>

                    <div class="h-[1px] bg-white/5 mx-2 my-0.5"></div>

                    <!-- Territory Section -->
                    <div id="territory-card" class="p-0.5 flex flex-col overflow-hidden">
                        <button onclick="toggleTerritoryMenu()" class="w-full px-3 py-2 rounded-xl text-[8px] font-black uppercase tracking-wider bg-white/5 text-white flex items-center justify-between hover:bg-white/10 transition-all group border border-white/5 shrink-0">
                            <div class="flex items-center gap-1.5">
                                <i class="fas fa-map-location-dot text-[8px] opacity-70 text-gold-400"></i>
                                <span id="current-territory-label" class="truncate max-w-[80px]">Semua Wilayah</span>
                            </div>
                            <i id="territory-chevron" class="fas fa-chevron-down text-[6px] transition-transform duration-300"></i>
                        </button>
                        
                        <div id="territory-options" class="hidden mt-0.5 p-0.5 flex-col overflow-y-auto custom-scrollbar" style="max-height: 20vh;">
                            <button onclick="handleTerritorySelect('Semua')" data-id="Semua" class="territory-opt-btn w-full px-3 py-1.5 rounded-lg text-[7px] font-black uppercase tracking-wider text-gray-400 hover:bg-white/10 transition-all flex items-center justify-between group shrink-0">
                                <div class="flex items-center gap-1.5">
                                    <div class="w-2.5 h-2.5 rounded border border-white/20 flex items-center justify-center group-hover:border-emerald-400 transition-colors">
                                        <i class="fas fa-check text-[5px] text-emerald-400 opacity-0 transition-opacity"></i>
                                    </div>
                                    <span class="group-hover:text-white transition-colors">Semua Wilayah</span>
                                </div>
                                <div class="w-2.5 h-2.5 rounded bg-gray-500/30 opacity-50"></div>
                            </button>
                            @foreach($myKecamatans as $kec)
                            <button onclick="handleTerritorySelect('{{ $kec->id_kecamatan }}', '{{ $kec->nama_kecamatan }}')" data-id="{{ $kec->id_kecamatan }}" class="territory-opt-btn w-full px-3 py-1.5 rounded-lg text-[7px] font-black uppercase tracking-wider text-gray-400 hover:bg-white/10 transition-all flex items-center justify-between group shrink-0">
                                <div class="flex items-center gap-1.5">
                                    <div class="w-2.5 h-2.5 rounded border border-white/20 flex items-center justify-center group-hover:border-emerald-400 transition-colors">
                                        <i class="fas fa-check text-[5px] text-emerald-400 opacity-0 transition-opacity"></i>
                                    </div>
                                    <span class="truncate max-w-[75px] group-hover:text-white transition-colors">{{ $kec->nama_kecamatan }}</span>
                                </div>
                                <div class="w-2.5 h-2.5 rounded border border-white/10" style="background-color: {{ $kec->warna ?? '#cbd5e1' }};"></div>
                            </button>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <!-- Map Style Switcher Bottom Right -->
            <div class="absolute bottom-6 right-4 z-[9999] pointer-events-auto">
                <div id="layer-card" class="bg-[#1e1b4b]/80 backdrop-blur-xl p-1.5 rounded-2xl border border-white/10 shadow-2xl transition-all duration-300">
                    <button onclick="toggleLayerMenu()" class="w-9 h-9 rounded-full bg-white/10 text-white flex items-center justify-center hover:bg-white/20 transition-all group border border-white/5">
                        <i class="fas fa-layer-group text-xs group-hover:scale-110 transition-transform"></i>
                    </button>
                    
                    <div id="layer-options" class="hidden absolute bottom-full right-0 mb-2 p-1.5 bg-[#1e1b4b]/90 backdrop-blur-xl rounded-2xl border border-white/10 shadow-2xl flex flex-col gap-1 min-w-[120px]">
                        <button onclick="changeBaseLayer('greyscale')" class="flex items-center gap-2 px-3 py-2 rounded-xl hover:bg-white/10 transition-all group">
                            <div class="w-6 h-6 rounded-md bg-gray-500/20 flex items-center justify-center text-gray-400 group-hover:bg-gray-500 group-hover:text-white transition-all">
                                <i class="fas fa-adjust text-[8px]"></i>
                            </div>
                            <span class="text-[7px] font-black uppercase tracking-wider text-gray-300 group-hover:text-white">Greyscale</span>
                        </button>
                        <button onclick="changeBaseLayer('satellite')" class="flex items-center gap-2 px-3 py-2 rounded-xl hover:bg-white/10 transition-all group">
                            <div class="w-6 h-6 rounded-md bg-emerald-500/20 flex items-center justify-center text-emerald-400 group-hover:bg-emerald-500 group-hover:text-white transition-all">
                                <i class="fas fa-satellite text-[8px]"></i>
                            </div>
                            <span class="text-[7px] font-black uppercase tracking-wider text-gray-300 group-hover:text-white">Satelit</span>
                        </button>
                        <button onclick="changeBaseLayer('osm')" class="flex items-center gap-2 px-3 py-2 rounded-xl hover:bg-white/10 transition-all group">
                            <div class="w-6 h-6 rounded-md bg-amber-500/20 flex items-center justify-center text-amber-400 group-hover:bg-amber-500 group-hover:text-white transition-all">
                                <i class="fas fa-map-marked-alt text-[8px]"></i>
                            </div>
                            <span class="text-[7px] font-black uppercase tracking-wider text-gray-300 group-hover:text-white">OSM</span>
                        </button>
                        <button onclick="changeBaseLayer('dark')" class="flex items-center gap-2 px-3 py-2 rounded-xl hover:bg-white/10 transition-all group">
                            <div class="w-6 h-6 rounded-md bg-indigo-500/20 flex items-center justify-center text-indigo-400 group-hover:bg-indigo-500 group-hover:text-white transition-all">
                                <i class="fas fa-moon text-[8px]"></i>
                            </div>
                            <span class="text-[7px] font-black uppercase tracking-wider text-gray-300 group-hover:text-white">Gelap</span>
                        </button>
                        <button onclick="changeBaseLayer('street')" class="flex items-center gap-2 px-3 py-2 rounded-xl hover:bg-white/10 transition-all group">
                            <div class="w-6 h-6 rounded-md bg-blue-500/20 flex items-center justify-center text-blue-400 group-hover:bg-blue-500 group-hover:text-white transition-all">
                                <i class="fas fa-road text-[8px]"></i>
                            </div>
                            <span class="text-[7px] font-black uppercase tracking-wider text-gray-300 group-hover:text-white">Jalan</span>
                        </button>
                        <div class="h-[1px] bg-white/10 my-0.5 mx-1"></div>
                        <button onclick="toggleFloodLayer()" class="flex items-center justify-between px-3 py-2 rounded-xl hover:bg-white/10 transition-all group w-full text-left">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-water text-blue-400 text-[8px]"></i>
                                <span class="text-[7px] font-black uppercase tracking-wider text-slate-300 group-hover:text-white transition-colors">Banjir</span>
                            </div>
                            <div class="w-5 h-2.5 rounded-full bg-slate-700 relative border border-white/10 transition-colors" id="flood-toggle-bg">
                                <div id="flood-toggle-dot" class="absolute left-[2px] top-[1px] w-1.5 h-1.5 bg-slate-400 rounded-full transition-all"></div>
                            </div>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        let currentBaseLayer;
        const baseLayers = {
            greyscale: L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png'),
            satellite: L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}'),
            osm: L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png'),
            dark: L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png'),
            street: L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png')
        };
        function updateClock() {
            const now = new Date();
            document.getElementById('mini-clock').textContent = `${String(now.getHours()).padStart(2, '0')}:${String(now.getMinutes()).padStart(2, '0')} WITA`;
        }
        setInterval(updateClock, 1000); updateClock();

        const map = L.map('main-map', {
            zoomControl: false,
            attributionControl: false
        }).setView([-3.316694, 114.590111], 13);
        
        // Set Initial Base Layer
        currentBaseLayer = baseLayers.street;
        currentBaseLayer.addTo(map);

        function toggleLayerMenu() {
            const menu = document.getElementById('layer-options');
            menu.classList.toggle('hidden');
        }

        function changeBaseLayer(type) {
            map.removeLayer(currentBaseLayer);
            currentBaseLayer = baseLayers[type];
            currentBaseLayer.addTo(map);
            toggleLayerMenu();
        }

        const dataPoints = @json($dataMap);
        const myKecamatans = @json($myKecamatans);
        const kelurahans = @json($allKelurahans);
        let activeMarkers = [];
        let kelurahanMarkers = [];
        let kelurahanPolygons = [];
        let geoLayers = {};
        let showKelurahan = true;

        // --- Layer Rawan Banjir (Mock) ---
        let showFloodLayer = false;
        const floodLayer = L.layerGroup([
            L.circle([-3.315, 114.590], { color: '#ef4444', fillColor: '#ef4444', fillOpacity: 0.2, weight: 1, radius: 800 }).bindPopup('<div class="text-center"><p class="text-[10px] font-black text-red-500 uppercase">Zona Merah</p><p class="text-xs">Rawan Banjir Tinggi</p></div>'),
            L.circle([-3.325, 114.598], { color: '#f59e0b', fillColor: '#f59e0b', fillOpacity: 0.2, weight: 1, radius: 1200 }).bindPopup('<div class="text-center"><p class="text-[10px] font-black text-orange-500 uppercase">Zona Kuning</p><p class="text-xs">Rawan Banjir Sedang</p></div>'),
            L.circle([-3.295, 114.580], { color: '#ef4444', fillColor: '#ef4444', fillOpacity: 0.2, weight: 1, radius: 900 }).bindPopup('<div class="text-center"><p class="text-[10px] font-black text-red-500 uppercase">Zona Merah</p><p class="text-xs">Rawan Banjir Tinggi</p></div>'),
            L.circle([-3.330, 114.570], { color: '#f59e0b', fillColor: '#f59e0b', fillOpacity: 0.2, weight: 1, radius: 1000 }).bindPopup('<div class="text-center"><p class="text-[10px] font-black text-orange-500 uppercase">Zona Kuning</p><p class="text-xs">Rawan Banjir Sedang</p></div>')
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

        // Render Polygons (Territories)
        myKecamatans.forEach(kec => {
            if (kec.geometri) {
                try {
                    // Handle both casted objects and raw strings
                    const geoData = typeof kec.geometri === 'string' ? JSON.parse(kec.geometri) : kec.geometri;
                    
                    const poly = L.geoJSON(geoData, {
                        style: {
                            fillColor: kec.warna || '#3b82f6',
                            weight: 2,
                            opacity: 1,
                            color: 'white',
                            fillOpacity: 0.2
                        }
                    }).addTo(map);

                    poly.bindPopup(`
                        <div class="px-2 py-0.5 text-center">
                            <p class="text-[9px] font-black uppercase tracking-widest text-[#1e1b4b]">${kec.nama_kecamatan}</p>
                        </div>
                    `, {
                        className: 'custom-polygon-popup',
                        closeButton: false,
                        offset: [0, -10]
                    });

                    poly.on('mouseover', function() {
                        this.setStyle({ fillOpacity: 0.4, weight: 3 });
                    });
                    poly.on('mouseout', function() {
                        this.setStyle({ fillOpacity: 0.2, weight: 2 });
                    });

                    geoLayers[kec.id_kecamatan] = poly;
                } catch (e) { 
                    console.error("Error rendering Kecamatan: " + kec.nama_kecamatan, e); 
                }
            }
        });

        function renderMarkers(points) {
            activeMarkers.forEach(m => map.removeLayer(m));
            activeMarkers = [];

            points.forEach(point => {
                const rawJenis = point.jenis || 'jalan';
                const prioritas = point.analisis && point.analisis.label_prioritas ? point.analisis.label_prioritas : (point.kondisi || 'Baik');
                
                let color = '#3b82f6';
                if (prioritas === 'Baik') color = '#059669';
                else if (prioritas === 'Rusak Sedang') color = '#d97706';
                else if (prioritas === 'Rusak Berat') color = '#be123c';
                
                const icon = L.divIcon({
                    html: `
                        <div class="relative group">
                            <div class="absolute -inset-2 bg-white/50 rounded-full blur-sm opacity-0 group-hover:opacity-100 transition-opacity"></div>
                            <div style="background-color: ${color}; width: 16px; height: 16px; border-radius: 50%; border: 3px solid white; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -1px rgba(0,0,0,0.06);" class="relative z-10"></div>
                        </div>
                    `,
                    className: '',
                    iconSize: [16, 16],
                    iconAnchor: [8, 8]
                });

                let imagePath = point.foto_terbaru || '';
                if(imagePath && !imagePath.includes('infrastruktur/')) {
                    imagePath = 'infrastruktur/' + imagePath;
                }
                imagePath = imagePath.replace(/\\/g, '/');
                
                let finalUrl = '';
                if (imagePath) {
                    finalUrl = `/storage/${imagePath}`;
                } else {
                    const type = rawJenis.toLowerCase();
                    let typeStr = 'jalan';
                    if (type.includes('titian')) typeStr = 'titian';
                    else if (type.includes('jembatan')) typeStr = 'jembatan';
                    
                    let condStr = 'baik';
                    const pLower = prioritas.toLowerCase();
                    if (pLower.includes('berat')) condStr = 'rusak_berat';
                    else if (pLower.includes('sedang') || pLower.includes('ringan')) condStr = 'rusak_sedang';

                    finalUrl = `/dummy_${typeStr}_${condStr}.jpg`;
                }

                let conditionColor = 'bg-emerald-500 text-white shadow-emerald-500/20';
                if (prioritas === 'Rusak Sedang') conditionColor = 'bg-amber-500 text-white shadow-amber-500/20';
                if (prioritas === 'Rusak Berat') conditionColor = 'bg-red-500 text-white shadow-red-500/20';

                const popupContent = `
                    <div class="p-1.5 min-w-[260px] font-sans">
                        <img src="${finalUrl}" class="w-full h-36 object-cover rounded-xl shadow-md mb-3.5" onerror="this.style.display='none'">
                        
                        <div class="mb-3">
                            <h6 class="text-white font-extrabold text-base uppercase leading-tight truncate max-w-[250px] mb-1" title="${point.nama_infrastruktur || point.nama_objek || 'Tanpa Nama'}">${point.nama_infrastruktur || point.nama_objek || 'Tanpa Nama'}</h6>
                            <p class="text-gold-500 text-[11px] font-bold uppercase tracking-widest">${rawJenis || 'Infrastruktur'}</p>
                        </div>
                        
                        <div class="space-y-2 mb-3.5">
                            <div class="flex items-start gap-2.5">
                                <i class="fas fa-map-marker-alt text-slate-400 text-xs mt-0.5 w-4 text-center"></i>
                                <span class="text-slate-300 text-[11px] leading-relaxed flex-1">${point.kelurahan && point.kelurahan.kecamatan ? point.kelurahan.kecamatan.nama_kecamatan : 'Lokasi tidak diketahui'}</span>
                            </div>
                            <div class="flex items-start gap-2.5">
                                <i class="fas fa-clock text-slate-400 text-xs mt-0.5 w-4 text-center"></i>
                                <span class="text-slate-300 text-[11px] leading-relaxed flex-1">Update: ${new Date(point.updated_at).toLocaleDateString('id-ID', {day:'2-digit', month:'long', year:'numeric'})}</span>
                            </div>
                            <div class="flex items-start gap-2.5">
                                <i class="fas fa-robot text-slate-400 text-xs mt-0.5 w-4 text-center"></i>
                                <span class="text-slate-300 text-[11px] leading-relaxed flex-1">CNN Score: ${point.cnn ? (point.cnn.skor_cnn * 100).toFixed(1) + '%' : 'N/A'}</span>
                            </div>
                        </div>

                        <div class="border-t border-white/10 pt-3.5 flex items-center justify-between gap-3 mt-3 mb-3.5">
                            <span class="text-slate-400 text-[10px] font-bold uppercase tracking-widest">Kondisi:</span>
                            <div class="px-3.5 py-1.5 ${conditionColor} text-[10px] font-black uppercase tracking-wider rounded-lg shadow-md">
                                ${prioritas}
                            </div>
                        </div>
                        
                        <div class="flex gap-2">
                            <a href="/surveyor/infrastruktur/${point.id_infrastruktur}/edit" class="flex-1 bg-navy-900 border border-gold-500/30 hover:bg-gold-500 hover:border-gold-500 text-gold-500 hover:text-white font-bold text-[10px] py-2.5 rounded-lg transition-all shadow-sm uppercase tracking-widest flex justify-center items-center gap-2">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <a href="/surveyor/infrastruktur/${point.id_infrastruktur}" class="w-10 flex justify-center items-center bg-white/10 hover:bg-white/20 text-white font-bold text-[10px] py-2.5 rounded-lg transition-all shadow-sm">
                                <i class="fas fa-eye text-xs"></i>
                            </a>
                        </div>
                    </div>
                `;

                const marker = L.marker([point.latitude, point.longitude], {icon: icon})
                    .addTo(map)
                    .bindPopup(popupContent, {
                        maxWidth: 300,
                        className: 'custom-leaflet-popup'
                    });
                
                activeMarkers.push(marker);
            });

            if (points.length > 0) {
                const group = new L.featureGroup(activeMarkers);
                map.fitBounds(group.getBounds().pad(0.2));
            }
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
                            filter: function(feature) {
                                // Hanya tampilkan jika bukan titik (Point)
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
                                <p class="text-[8px] font-black text-slate-500 uppercase tracking-[0.2em] mb-0.5">Kelurahan</p>
                                <p class="text-[10px] font-black uppercase tracking-widest text-[#1e1b4b]">${kel.nama_kelurahan}</p>
                            </div>
                        `, {
                            className: 'custom-polygon-popup',
                            closeButton: false,
                            offset: [0, -5]
                        });

                        poly.on('mouseover', function() {
                            this.setStyle({ color: '#f1f5f9', weight: 3, opacity: 1, dashArray: '' });
                        });
                        poly.on('mouseout', function() {
                            this.setStyle({ color: '#94a3b8', weight: 2, opacity: 0.8, dashArray: '5, 5' });
                        });

                        kelurahanPolygons.push(poly);
                    } catch (e) {
                        console.error("Error rendering Kelurahan Polygon: " + kel.nama_kelurahan, e);
                    }
                }
            });
        }

        function toggleKelurahanPoints() {
            showKelurahan = !showKelurahan;
            document.getElementById('kel-check-icon').style.opacity = showKelurahan ? '1' : '0';
            document.getElementById('kel-toggle-btn').classList.toggle('text-white', showKelurahan);
            renderKelurahanData();
        }

        let activeTypes = ['Jalan', 'Jembatan', 'Titian'];
        let activeTerritories = myKecamatans.map(k => k.id_kecamatan.toString());

        function applyFilters() {
            let filteredMarkers = dataPoints.filter(p => {
                // Normalisasi kategori (handle null & case insensitive)
                const rawType = p.jenis || 'Lainnya';
                const normalizedType = rawType.charAt(0).toUpperCase() + rawType.slice(1).toLowerCase();
                
                // Safe kecamatan ID extraction
                const kecId = p.id_kecamatan || (p.kelurahan ? p.kelurahan.id_kecamatan : null) || '';
                
                return (activeTypes.includes(normalizedType) || activeTypes.includes(rawType)) && 
                       activeTerritories.includes(kecId.toString());
            });
            renderMarkers(filteredMarkers);

            // Filter Polygons
            Object.keys(geoLayers).forEach(id => {
                if (activeTerritories.includes(id.toString())) {
                    if (!map.hasLayer(geoLayers[id])) geoLayers[id].addTo(map);
                } else {
                    if (map.hasLayer(geoLayers[id])) map.removeLayer(geoLayers[id]);
                }
            });

            updateUIState();
        }

        function updateUIState() {
            // Update Category Checkboxes
            document.querySelectorAll('.cat-opt-btn').forEach(btn => {
                const type = btn.getAttribute('data-type');
                const check = btn.querySelector('.fa-check');
                if (type === 'Semua') {
                    const isAll = activeTypes.length === 3;
                    check.style.opacity = isAll ? '1' : '0';
                } else {
                    check.style.opacity = activeTypes.includes(type) ? '1' : '0';
                }
            });

            // Update Territory Checkboxes
            document.querySelectorAll('.territory-opt-btn').forEach(btn => {
                const id = btn.getAttribute('data-id');
                const check = btn.querySelector('.fa-check');
                if (id === 'Semua') {
                    const isAll = activeTerritories.length === myKecamatans.length;
                    check.style.opacity = isAll ? '1' : '0';
                } else {
                    check.style.opacity = activeTerritories.includes(id) ? '1' : '0';
                }
            });
        }

        function toggleCategoryMenu() {
            const menu = document.getElementById('category-options');
            const chevron = document.getElementById('cat-chevron');
            menu.classList.toggle('hidden');
            chevron.classList.toggle('rotate-180');
        }

        function handleCategorySelect(type) {
            if (type === 'Semua') {
                if (activeTypes.length === 3) activeTypes = [];
                else activeTypes = ['Jalan', 'Jembatan', 'Titian'];
            } else {
                if (activeTypes.includes(type)) {
                    activeTypes = activeTypes.filter(t => t !== type);
                } else {
                    activeTypes.push(type);
                }
            }
            applyFilters();
        }

        function toggleTerritoryMenu() {
            const menu = document.getElementById('territory-options');
            const chevron = document.getElementById('territory-chevron');
            menu.classList.toggle('hidden');
            chevron.classList.toggle('rotate-180');
        }

        function handleTerritorySelect(id, name) {
            if (id === 'Semua') {
                if (activeTerritories.length === myKecamatans.length) activeTerritories = [];
                else activeTerritories = myKecamatans.map(k => k.id_kecamatan.toString());
            } else {
                id = id.toString();
                if (activeTerritories.includes(id)) {
                    activeTerritories = activeTerritories.filter(t => t !== id);
                } else {
                    activeTerritories.push(id);
                    // Zoom ke Wilayah yang baru dipilih jika hanya satu yang dipilih atau baru ditambah
                    if (geoLayers[id]) map.fitBounds(geoLayers[id].getBounds(), { padding: [50, 50], maxZoom: 15 });
                }
            }
            applyFilters();
        }

        function toggleConditionMenu() {
            const menu = document.getElementById('condition-options');
            const chevron = document.getElementById('cond-chevron');
            menu.classList.toggle('hidden');
            chevron.classList.toggle('rotate-180');
        }

        function handleConditionSelect(cond) {
            toggleConditionMenu();
            if (cond === 'Semua') {
                applyFilters();
            } else {
                // Condition filter still behaves as a quick filter on top of current view
                let filtered = dataPoints.filter(p => {
                    const kecId = p.id_kecamatan || (p.kelurahan ? p.kelurahan.id_kecamatan : null) || '';
                    return p.kondisi === cond && 
                           activeTypes.includes(p.jenis) && 
                           activeTerritories.includes(kecId.toString());
                });
                renderMarkers(filtered);
            }
        }

        applyFilters();
        renderKelurahanData();
    </script>

    <style>
        .leaflet-popup-content-wrapper { border-radius: 1.25rem !important; padding: 4px !important; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); border: 1px solid rgba(255,255,255,0.2); }
        .leaflet-popup-tip-container { display: none; }
        
        .custom-leaflet-popup .leaflet-popup-content-wrapper {
            background: #0f0e2c !important;
            color: #ffffff !important;
            border-radius: 1.25rem !important;
            padding: 4px !important;
            border: 1px solid rgba(255,255,255,0.1) !important;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.5) !important;
        }
        .custom-leaflet-popup .leaflet-popup-tip {
            background: #0f0e2c !important;
            border: 1px solid rgba(255,255,255,0.1) !important;
        }
        .custom-leaflet-popup .leaflet-popup-close-button {
            color: #c5a059 !important;
            padding: 8px !important;
        }

        .custom-polygon-popup .leaflet-popup-content-wrapper {
            background: rgba(255, 255, 255, 0.8) !important;
            backdrop-filter: blur(10px) !important;
            border-radius: 12px !important;
            border: 1px solid rgba(255, 255, 255, 0.3) !important;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1) !important;
            padding: 0 !important;
        }
        .custom-polygon-popup .leaflet-popup-tip {
            background: rgba(255, 255, 255, 0.8) !important;
            backdrop-filter: blur(10px) !important;
        }
        .custom-polygon-popup .leaflet-popup-content {
            margin: 8px !important;
        }
    </style>
</body>
</html>
