<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitoring Peta | GEO-SINFRA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <style> body { font-family: 'Plus Jakarta Sans', sans-serif; } </style>
</head>
<body class="bg-gray-50 flex h-screen overflow-hidden text-gray-800 text-left">

    @include('kabid.partials.sidebar')

    <main class="flex-1 flex flex-col h-screen overflow-hidden">
        <header class="bg-white border-b border-gray-100 px-8 py-5 flex justify-between items-center z-10">
            <div class="flex items-center gap-4">
                <a href="{{ route('kabid.dashboard') }}" class="w-10 h-10 flex items-center justify-center bg-gray-50 text-gray-400 rounded-xl hover:bg-indigo-50 hover:text-indigo-600 transition-all border border-gray-100">
                    <i class="fas fa-arrow-left text-sm"></i>
                </a>
                <div>
                    <p class="text-[10px] font-extrabold text-indigo-600 uppercase tracking-[0.2em] mb-1">Visualisasi Geografis</p>
                    <h2 class="text-xl font-black text-[#1e1b4b]">Monitoring Peta Sebaran</h2>
                </div>
            </div>
            
            <div class="flex items-center gap-6">
                <div class="text-right hidden sm:block">
                    <p class="text-[11px] font-black text-[#1e1b4b]" id="mini-clock">00:00 WITA</p>
                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-tighter">{{ now()->translatedFormat('l, d F Y') }}</p>
                </div>
                <div class="h-8 w-[1px] bg-gray-100"></div>
                <a href="{{ route('kabid.profile') }}" class="flex items-center gap-3">
                    <div class="text-right">
                        <p class="text-[11px] font-black text-[#1e1b4b] leading-none uppercase">{{ auth()->user()->name }}</p>
                        <p class="text-[9px] font-bold text-emerald-500 uppercase mt-1">ONLINE</p>
                    </div>
                    <div class="w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600 border border-indigo-100 overflow-hidden shadow-sm">
                        @if(auth()->user()->profile_photo)
                            <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}" class="w-full h-full object-cover">
                        @else
                            <i class="fas fa-user-tie text-xl"></i>
                        @endif
                    </div>
                </a>
            </div>
        </header>

        <div class="flex-1 relative">
            <div id="main-map" class="absolute inset-0 z-0"></div>

            <!-- Custom Zoom Controls -->
            <div class="absolute top-6 left-6 z-10 flex flex-col gap-2">
                <button onclick="map.zoomIn()" class="w-10 h-10 bg-[#1e1b4b]/80 backdrop-blur-xl rounded-2xl border border-white/10 shadow-2xl flex items-center justify-center text-white hover:bg-[#1e1b4b] transition-all group">
                    <i class="fas fa-plus text-[10px] group-hover:scale-110 transition-transform"></i>
                </button>
                <button onclick="map.zoomOut()" class="w-10 h-10 bg-[#1e1b4b]/80 backdrop-blur-xl rounded-2xl border border-white/10 shadow-2xl flex items-center justify-center text-white hover:bg-[#1e1b4b] transition-all group">
                    <i class="fas fa-minus text-[10px] group-hover:scale-110 transition-transform"></i>
                </button>
            </div>

            <!-- Floating Filters Right -->
            <div class="absolute top-6 right-6 z-10 w-64 space-y-3">
                <!-- Filter Kategori -->
                <div class="bg-[#1e1b4b]/80 backdrop-blur-xl p-2 rounded-[2.5rem] border border-white/10 shadow-2xl">
                    <button onclick="toggleMenu('category-options')" class="w-full px-5 py-3.5 rounded-[2rem] text-[9px] font-black uppercase tracking-widest bg-indigo-600 text-white flex items-center justify-between shadow-lg hover:bg-indigo-500 transition-all group">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-layer-group text-[10px] opacity-70"></i>
                            <span>Kategori Objek</span>
                        </div>
                        <i class="fas fa-chevron-down text-[7px]"></i>
                    </button>
                    <div id="category-options" class="hidden mt-2 p-2 flex flex-col gap-1">
                        <button onclick="handleTypeFilter('Semua')" class="filter-btn w-full px-5 py-3 rounded-xl text-[8px] font-black uppercase tracking-widest text-gray-300 hover:bg-white/10 transition-all flex items-center justify-between group active-filter" data-type="Semua">
                            <span>Semua Kategori</span>
                            <div class="w-2 h-2 rounded-full bg-indigo-400"></div>
                        </button>
                        <button onclick="handleTypeFilter('Jalan')" class="filter-btn w-full px-5 py-3 rounded-xl text-[8px] font-black uppercase tracking-widest text-gray-400 hover:bg-white/10 transition-all flex items-center justify-between group" data-type="Jalan">
                            <span>Infrastruktur Jalan</span>
                            <div class="w-2 h-2 rounded-full bg-blue-500"></div>
                        </button>
                        <button onclick="handleTypeFilter('Jembatan')" class="filter-btn w-full px-5 py-3 rounded-xl text-[8px] font-black uppercase tracking-widest text-gray-400 hover:bg-white/10 transition-all flex items-center justify-between group" data-type="Jembatan">
                            <span>Jembatan</span>
                            <div class="w-2 h-2 rounded-full bg-emerald-500"></div>
                        </button>
                        <button onclick="handleTypeFilter('Drainase')" class="filter-btn w-full px-5 py-3 rounded-xl text-[8px] font-black uppercase tracking-widest text-gray-400 hover:bg-white/10 transition-all flex items-center justify-between group" data-type="Drainase">
                            <span>Drainase & Sanitasi</span>
                            <div class="w-2 h-2 rounded-full bg-amber-500"></div>
                        </button>
                    </div>
                </div>

                <!-- Filter Wilayah -->
                <div class="bg-[#1e1b4b]/80 backdrop-blur-xl p-2 rounded-[2.5rem] border border-white/10 shadow-2xl">
                    <button onclick="toggleMenu('territory-options')" class="w-full px-5 py-3.5 rounded-[2rem] text-[9px] font-black uppercase tracking-widest bg-white/10 text-white flex items-center justify-between shadow-lg hover:bg-white/20 transition-all group border border-white/5">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-map-location-dot text-[10px] opacity-70 text-indigo-400"></i>
                            <span>Filter Kecamatan</span>
                        </div>
                        <i class="fas fa-chevron-down text-[7px]"></i>
                    </button>
                    <div id="territory-options" class="hidden mt-2 p-2 flex flex-col gap-1 max-h-48 overflow-y-auto custom-scrollbar">
                        <button onclick="handleKecamatanFilter('Semua')" class="kec-btn w-full px-5 py-3 rounded-xl text-[8px] font-black uppercase tracking-widest text-gray-300 hover:bg-white/10 transition-all flex items-center justify-between group active-kec" data-id="Semua">
                            <span>Semua Wilayah</span>
                        </button>
                        @foreach($kecamatan as $kec)
                        <button onclick="handleKecamatanFilter('{{ $kec->id_kecamatan }}')" class="kec-btn w-full px-5 py-3 rounded-xl text-[8px] font-black uppercase tracking-widest text-gray-400 hover:bg-white/10 transition-all flex items-center justify-between group" data-id="{{ $kec->id_kecamatan }}">
                            <span class="truncate max-w-[120px]">{{ $kec->nama_kecamatan }}</span>
                            <div class="w-3 h-3 rounded shadow-inner" style="background-color: {{ $kec->warna ?? '#6366f1' }}"></div>
                        </button>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Layer Switcher -->
            <div class="absolute bottom-10 right-6 z-10">
                <div class="bg-[#1e1b4b]/80 backdrop-blur-xl p-2 rounded-[2.5rem] border border-white/10 shadow-2xl">
                    <button onclick="toggleMenu('layer-options')" class="w-12 h-12 rounded-full bg-white/10 text-white flex items-center justify-center hover:bg-white/20 transition-all group border border-white/5">
                        <i class="fas fa-layer-group text-sm"></i>
                    </button>
                    <div id="layer-options" class="hidden absolute bottom-full right-0 mb-3 p-2 bg-[#1e1b4b]/90 backdrop-blur-xl rounded-3xl border border-white/10 shadow-2xl flex flex-col gap-2 min-w-[140px]">
                        <button onclick="changeBaseLayer('street')" class="flex items-center gap-3 px-4 py-3 rounded-2xl hover:bg-white/10 transition-all group">
                            <i class="fas fa-road text-blue-400 text-[10px]"></i>
                            <span class="text-[9px] font-black uppercase tracking-widest text-gray-300">Jalan</span>
                        </button>
                        <button onclick="changeBaseLayer('satellite')" class="flex items-center gap-3 px-4 py-3 rounded-2xl hover:bg-white/10 transition-all group">
                            <i class="fas fa-satellite text-emerald-400 text-[10px]"></i>
                            <span class="text-[9px] font-black uppercase tracking-widest text-gray-300">Satelit</span>
                        </button>
                        <button onclick="changeBaseLayer('dark')" class="flex items-center gap-3 px-4 py-3 rounded-2xl hover:bg-white/10 transition-all group">
                            <i class="fas fa-moon text-gray-400 text-[10px]"></i>
                            <span class="text-[9px] font-black uppercase tracking-widest text-gray-300">Gelap</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        const baseLayers = {
            street: L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png'),
            satellite: L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}'),
            dark: L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png')
        };

        const map = L.map('main-map', { zoomControl: false, attributionControl: false }).setView([-3.316694, 114.590111], 13);
        let currentBaseLayer = baseLayers.street.addTo(map);

        // Create panes to manage layering
        map.createPane('polygonsPane');
        map.getPane('polygonsPane').style.zIndex = 400;
        map.createPane('markersPane');
        map.getPane('markersPane').style.zIndex = 650;

        const dataPoints = @json($infrastruktur);
        const kecamatans = @json($kecamatan);
        let activeMarkers = [];
        let geoLayers = {};

        // Render Polygons first in lower pane
        kecamatans.forEach(kec => {
            if (kec.geometri) {
                try {
                    const geoData = typeof kec.geometri === 'string' ? JSON.parse(kec.geometri) : kec.geometri;
                    const poly = L.geoJSON(geoData, {
                        pane: 'polygonsPane',
                        style: {
                            fillColor: kec.warna || '#6366f1',
                            weight: 2,
                            opacity: 1,
                            color: 'white',
                            fillOpacity: 0.15
                        },
                        interactive: true // Keep true for kecamatan popup
                    }).addTo(map);

                    poly.bindPopup(`<p class="text-[10px] font-black text-[#1e1b4b] uppercase">${kec.nama_kecamatan}</p>`, { 
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
                const color = point.kondisi == 'Baik' ? '#10b981' : (point.kondisi == 'Rusak Ringan' ? '#f59e0b' : '#ef4444');
                const icon = L.divIcon({
                    html: `<div style="background-color: ${color}; width: 14px; height: 14px; border-radius: 50%; border: 2.5px solid white; box-shadow: 0 4px 10px rgba(0,0,0,0.2);"></div>`,
                    className: '', iconSize: [14, 14], iconAnchor: [7, 7]
                });

                const popupContent = `
                    <div class="p-1" style="min-width: 240px;">
                        <div class="relative h-32 rounded-2xl bg-gray-100 mb-3 overflow-hidden shadow-inner">
                            <img src="/storage/${point.foto_terbaru}" class="w-full h-full object-cover">
                            <div class="absolute top-2 left-2 px-2 py-1 bg-white/90 backdrop-blur-md rounded-lg text-[7px] font-black uppercase tracking-widest text-[#1e1b4b]">
                                ${point.jenis_infrastruktur}
                            </div>
                        </div>
                        <div class="px-1">
                            <h4 class="text-xs font-black text-[#1e1b4b] mb-1">${point.nama_infrastruktur}</h4>
                            <p class="text-[8px] text-gray-400 font-bold uppercase mb-3">Wilayah: ${point.kelurahan?.nama_kelurahan ?? '-'}</p>
                            
                            <div class="flex items-center gap-2 mb-3">
                                <span class="px-2 py-1 rounded-full text-[7px] font-black uppercase tracking-widest" style="background-color: ${color}15; color: ${color}; border: 1px solid ${color}30;">
                                    ${point.kondisi}
                                </span>
                                <span class="px-2 py-1 bg-indigo-50 text-indigo-600 rounded-full text-[7px] font-black uppercase border border-indigo-100">
                                    By: ${point.user?.name ?? 'Surveyor'}
                                </span>
                            </div>

                            <a href="#" class="block w-full py-2 bg-[#1e1b4b] text-white rounded-xl text-[8px] font-black uppercase tracking-widest text-center hover:bg-indigo-600 transition-all shadow-lg shadow-indigo-900/10">Detail Laporan</a>
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

        let filterType = 'Semua';
        let filterKec = 'Semua';

        function applyFilters() {
            let filtered = dataPoints;
            if (filterType !== 'Semua') filtered = filtered.filter(p => p.jenis_infrastruktur === filterType);
            if (filterKec !== 'Semua') filtered = filtered.filter(p => p.id_kecamatan.toString() === filterKec.toString());
            
            renderMarkers(filtered);
        }

        function handleTypeFilter(type) {
            filterType = type;
            document.querySelectorAll('.filter-btn').forEach(btn => {
                btn.classList.toggle('active-filter', btn.getAttribute('data-type') === type);
            });
            applyFilters();
            toggleMenu('category-options');
        }

        function handleKecamatanFilter(kecId) {
            filterKec = kecId;
            document.querySelectorAll('.kec-btn').forEach(btn => {
                btn.classList.toggle('active-kec', btn.getAttribute('data-id') === kecId.toString());
            });
            applyFilters();
            if (kecId !== 'Semua' && geoLayers[kecId]) {
                map.fitBounds(geoLayers[kecId].getBounds(), { padding: [50, 50] });
            }
            toggleMenu('territory-options');
        }

        function toggleMenu(id) { document.getElementById(id).classList.toggle('hidden'); }
        function changeBaseLayer(type) {
            map.removeLayer(currentBaseLayer);
            currentBaseLayer = baseLayers[type].addTo(map);
            toggleMenu('layer-options');
        }

        renderMarkers(dataPoints);

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
