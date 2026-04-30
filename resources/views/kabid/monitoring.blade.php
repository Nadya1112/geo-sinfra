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

            <!-- Floating Filters Right (Combined) -->
            <div class="absolute top-6 right-6 z-10">
                <div class="bg-[#1e1b4b]/80 backdrop-blur-xl p-2 rounded-[2.8rem] border border-white/10 shadow-2xl min-w-[220px]">
                    
                    <!-- Kategori Section -->
                    <div class="p-1">
                        <button onclick="toggleMenu('category-options')" class="w-full px-5 py-3.5 rounded-[2rem] text-[9px] font-black uppercase tracking-widest bg-indigo-600/90 text-white flex items-center justify-between shadow-lg hover:bg-indigo-600 transition-all group">
                            <div class="flex items-center gap-3">
                                <i class="fas fa-layer-group text-[10px] opacity-70"></i>
                                <span id="current-cat-label">Kategori Objek</span>
                            </div>
                            <i class="fas fa-chevron-down text-[7px]"></i>
                        </button>
                        <div id="category-options" class="hidden mt-2 p-1 flex flex-col gap-1">
                            <button onclick="toggleType('Jalan')" class="type-btn w-full px-5 py-3 rounded-xl text-[8px] font-black uppercase tracking-widest text-gray-400 hover:bg-white/10 transition-all flex items-center justify-between group" data-type="Jalan">
                                <div class="flex items-center gap-3">
                                    <div class="w-3.5 h-3.5 rounded border border-white/20 flex items-center justify-center group-hover:border-blue-400 transition-colors">
                                        <i class="fas fa-check text-[7px] text-blue-400 check-icon" style="opacity:0"></i>
                                    </div>
                                    <span class="group-hover:text-white transition-colors">Infrastruktur Jalan</span>
                                </div>
                                <div class="w-2.5 h-2.5 rounded-full bg-blue-500 shadow-lg shadow-blue-500/40"></div>
                            </button>
                            <button onclick="toggleType('Jembatan')" class="type-btn w-full px-5 py-3 rounded-xl text-[8px] font-black uppercase tracking-widest text-gray-400 hover:bg-white/10 transition-all flex items-center justify-between group" data-type="Jembatan">
                                <div class="flex items-center gap-3">
                                    <div class="w-3.5 h-3.5 rounded border border-white/20 flex items-center justify-center group-hover:border-emerald-400 transition-colors">
                                        <i class="fas fa-check text-[7px] text-emerald-400 check-icon" style="opacity:0"></i>
                                    </div>
                                    <span class="group-hover:text-white transition-colors">Jembatan</span>
                                </div>
                                <div class="w-2.5 h-2.5 rounded-full bg-emerald-500 shadow-lg shadow-emerald-500/40"></div>
                            </button>
                            <button onclick="toggleType('Drainase')" class="type-btn w-full px-5 py-3 rounded-xl text-[8px] font-black uppercase tracking-widest text-gray-400 hover:bg-white/10 transition-all flex items-center justify-between group" data-type="Drainase">
                                <div class="flex items-center gap-3">
                                    <div class="w-3.5 h-3.5 rounded border border-white/20 flex items-center justify-center group-hover:border-amber-400 transition-colors">
                                        <i class="fas fa-check text-[7px] text-amber-400 check-icon" style="opacity:0"></i>
                                    </div>
                                    <span class="group-hover:text-white transition-colors">Drainase</span>
                                </div>
                                <div class="w-2.5 h-2.5 rounded-full bg-amber-500 shadow-lg shadow-amber-500/40"></div>
                            </button>
                        </div>
                    </div>

                    <div class="h-[1px] bg-white/5 mx-4 my-1"></div>

                    <!-- Kecamatan Section -->
                    <div class="p-1">
                        <button onclick="toggleMenu('territory-options')" class="w-full px-5 py-3.5 rounded-[2rem] text-[9px] font-black uppercase tracking-widest bg-white/10 text-white flex items-center justify-between shadow-lg hover:bg-white/20 transition-all group border border-white/5">
                            <div class="flex items-center gap-3">
                                <i class="fas fa-map-location-dot text-[10px] opacity-70 text-indigo-400"></i>
                                <span id="current-kec-label">Filter Kecamatan</span>
                            </div>
                            <i class="fas fa-chevron-down text-[7px]"></i>
                        </button>
                        <div id="territory-options" class="hidden mt-2 p-1 flex flex-col gap-1 max-h-48 overflow-y-auto custom-scrollbar">
                            <button onclick="toggleKecamatan('Semua')" class="kec-btn w-full px-5 py-3 rounded-xl text-[8px] font-black uppercase tracking-widest text-gray-400 hover:bg-white/10 transition-all flex items-center justify-between group" data-id="Semua">
                                <div class="flex items-center gap-3">
                                    <div class="w-3.5 h-3.5 rounded border border-white/20 flex items-center justify-center group-hover:border-indigo-400 transition-colors">
                                        <i class="fas fa-check text-[7px] text-indigo-400 check-icon" style="opacity:1"></i>
                                    </div>
                                    <span class="group-hover:text-white transition-colors">Semua Wilayah</span>
                                </div>
                            </button>
                            @foreach($kecamatan as $kec)
                            <button onclick="toggleKecamatan('{{ $kec->id_kecamatan }}')" class="kec-btn w-full px-5 py-3 rounded-xl text-[8px] font-black uppercase tracking-widest text-gray-400 hover:bg-white/10 transition-all flex items-center justify-between group" data-id="{{ $kec->id_kecamatan }}">
                                <div class="flex items-center gap-3">
                                    <div class="w-3.5 h-3.5 rounded border border-white/20 flex items-center justify-center group-hover:border-indigo-400 transition-colors">
                                        <i class="fas fa-check text-[7px] text-indigo-400 check-icon" style="opacity:1"></i>
                                    </div>
                                    <span class="truncate max-w-[100px] group-hover:text-white transition-colors">{{ $kec->nama_kecamatan }}</span>
                                </div>
                                <div class="w-3 h-3 rounded flex-shrink-0" style="background-color: {{ $kec->warna ?? '#6366f1' }}; box-shadow: 0 0 8px {{ $kec->warna ?? '#6366f1' }}60;"></div>
                            </button>
                            @endforeach
                        </div>
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
                        interactive: true
                    }); // Do NOT add to map yet — applyFilters() controls visibility

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

        // Kategori: empty by default (user must select)
        // Wilayah: ALL selected by default (like Surveyor)
        let activeTypes = [];
        let activeKecs = kecamatans.map(k => k.id_kecamatan.toString());
        const totalKec = kecamatans.length;

        function applyFilters() {
            // 1. Wilayah HANYA mengontrol Warna Area (Poligon)
            Object.keys(geoLayers).forEach(id => {
                if (activeKecs.includes(id.toString())) {
                    if (!map.hasLayer(geoLayers[id])) geoLayers[id].addTo(map);
                } else {
                    if (map.hasLayer(geoLayers[id])) map.removeLayer(geoLayers[id]);
                }
            });

            // 2. Kategori mengontrol Titik Objek (Marker) di SELURUH peta
            if (activeTypes.length === 0) {
                renderMarkers([]);
                return;
            }

            const normalisedActiveTypes = activeTypes.map(t => t.toLowerCase().trim());

            let filtered = dataPoints.filter(p => {
                const pType = (p.jenis_infrastruktur || '').toLowerCase().trim();
                // Marker tetap muncul selama kategorinya cocok (tidak peduli wilayah dicentang atau tidak)
                return normalisedActiveTypes.some(type => pType.includes(type));
            });
            
            console.log(`Filter Debug: Found ${filtered.length} markers.`);
            renderMarkers(filtered);
        }

        function toggleType(type) {
            const normalizedType = type.toLowerCase().trim();
            if (activeTypes.includes(type)) {
                activeTypes = activeTypes.filter(t => t !== type);
            } else {
                activeTypes.push(type);
            }
            
            document.querySelectorAll('.type-btn').forEach(btn => {
                const btnType = btn.getAttribute('data-type').toLowerCase().trim();
                const isActive = activeTypes.some(t => t.toLowerCase().trim() === btnType);
                const icon = btn.querySelector('.check-icon');
                if (icon) icon.style.opacity = isActive ? '1' : '0';
                btn.classList.toggle('text-white', isActive);
            });

            const label = activeTypes.length === 0 ? 'Kategori Objek' :
                          activeTypes.length === 3 ? 'Semua Kategori' :
                          activeTypes.join(', ');
            document.getElementById('current-cat-label').textContent = label;
            applyFilters();
        }

        function toggleKecamatan(kecId) {
            if (kecId === 'Semua') {
                // Toggle all
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
            // Update all checkboxes
            const allChecked = activeKecs.length === totalKec;
            document.querySelectorAll('.kec-btn').forEach(btn => {
                const id = btn.getAttribute('data-id');
                const isActive = id === 'Semua' ? allChecked : activeKecs.includes(id);
                const icon = btn.querySelector('.check-icon');
                if (icon) icon.style.opacity = isActive ? '1' : '0';
                btn.classList.toggle('text-white', isActive);
            });
            // Update label
            const label = activeKecs.length === 0 ? 'Filter Kecamatan' :
                          activeKecs.length === totalKec ? 'Semua Wilayah' :
                          activeKecs.length + ' Wilayah Dipilih';
            document.getElementById('current-kec-label').textContent = label;
            applyFilters();
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
