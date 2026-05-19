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

    <?php echo $__env->make('kabid.partials.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <main class="flex-1 flex flex-col h-screen overflow-hidden">
        <header class="bg-white border-b border-gray-100 px-8 py-5 flex justify-between items-center z-10">
            <div class="flex items-center gap-4">
                <a href="<?php echo e(route('kabid.dashboard')); ?>" class="w-10 h-10 flex items-center justify-center bg-gray-50 text-gray-400 rounded-xl hover:bg-indigo-50 hover:text-indigo-600 transition-all border border-gray-100">
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
                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-tighter"><?php echo e(now()->translatedFormat('l, d F Y')); ?></p>
                </div>
                <div class="h-8 w-[1px] bg-gray-100"></div>
                <a href="<?php echo e(route('kabid.profile')); ?>" class="flex items-center gap-3">
                    <div class="text-right">
                        <p class="text-[11px] font-black text-[#1e1b4b] leading-none uppercase"><?php echo e(auth()->user()->name); ?></p>
                        <p class="text-[9px] font-bold text-emerald-500 uppercase mt-1">ONLINE</p>
                    </div>
                    <div class="w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600 border border-indigo-100 overflow-hidden shadow-sm">
                        <?php if(auth()->user()->profile_photo): ?>
                            <img src="<?php echo e(asset('storage/' . auth()->user()->profile_photo)); ?>" class="w-full h-full object-cover">
                        <?php else: ?>
                            <i class="fas fa-user-tie text-xl"></i>
                        <?php endif; ?>
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

            <!-- Stats & Legend UI Bottom Left -->
            <div class="absolute bottom-10 left-6 z-10">
                <div id="condition-card" class="bg-[#1e1b4b]/80 backdrop-blur-xl p-1.5 rounded-[2rem] border border-white/10 shadow-2xl min-w-[160px]">
                    <button onclick="toggleMenu('condition-options')" class="w-full px-4 py-2.5 rounded-[1.5rem] text-[8px] font-black uppercase tracking-widest bg-white/10 text-white flex items-center justify-between shadow-sm hover:bg-white/20 transition-all group border border-white/5">
                        <div class="flex items-center gap-2.5">
                            <div class="w-5 h-5 bg-indigo-500/20 text-indigo-400 rounded flex items-center justify-center">
                                <i class="fas fa-chart-pie text-[9px]"></i>
                            </div>
                            <span>Statistik</span>
                        </div>
                        <i class="fas fa-chevron-up text-[7px]"></i>
                    </button>
                    
                    <div id="condition-options" class="mt-1.5 p-1 flex flex-col gap-0.5">
                        <div class="w-full px-3.5 py-1.5 rounded-xl text-[8px] font-black uppercase tracking-widest text-gray-300 flex items-center justify-between group">
                            <span>Total</span>
                            <span id="stat-total" class="text-[8px] font-black text-blue-400 bg-blue-500/10 px-2 py-0.5 rounded-md border border-blue-400/20">0</span>
                        </div>
                        <div class="w-full px-3.5 py-1.5 rounded-xl text-[8px] font-black uppercase tracking-widest text-gray-400 flex items-center justify-between group">
                            <div class="flex items-center gap-2.5">
                                <div class="w-2 h-2 bg-emerald-500 rounded-full shadow-lg shadow-emerald-500/40"></div>
                                <span>Baik</span>
                            </div>
                            <span id="stat-baik" class="text-[8px] font-black text-emerald-400 bg-emerald-500/10 px-2 py-0.5 rounded-md border border-emerald-400/20">0</span>
                        </div>
                        <div class="w-full px-3.5 py-1.5 rounded-xl text-[8px] font-black uppercase tracking-widest text-gray-400 flex items-center justify-between group">
                            <div class="flex items-center gap-2.5">
                                <div class="w-2 h-2 bg-amber-500 rounded-full shadow-lg shadow-amber-500/40"></div>
                                <span>Ringan</span>
                            </div>
                            <span id="stat-ringan" class="text-[8px] font-black text-amber-400 bg-amber-500/10 px-2 py-0.5 rounded-md border border-amber-400/20">0</span>
                        </div>
                        <div class="w-full px-3.5 py-1.5 rounded-xl text-[8px] font-black uppercase tracking-widest text-gray-400 flex items-center justify-between group">
                            <div class="flex items-center gap-2.5">
                                <div class="w-2 h-2 bg-red-500 rounded-full shadow-lg shadow-red-500/40"></div>
                                <span>Berat</span>
                            </div>
                            <span id="stat-berat" class="text-[8px] font-black text-red-400 bg-red-500/10 px-2 py-0.5 rounded-md border border-red-400/20">0</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Floating Filters Right (Combined) -->
            <div class="absolute top-6 right-6 z-10">
                <div class="bg-[#1e1b4b]/80 backdrop-blur-xl p-1.5 rounded-[2.5rem] border border-white/10 shadow-2xl min-w-[170px]">
                    
                    <!-- Kategori Section -->
                    <div class="p-0.5">
                        <button onclick="toggleMenu('category-options')" class="w-full px-4 py-2.5 rounded-[1.5rem] text-[8px] font-black uppercase tracking-widest bg-indigo-600/90 text-white flex items-center justify-between shadow-lg hover:bg-indigo-600 transition-all group">
                            <div class="flex items-center gap-2.5">
                                <i class="fas fa-layer-group text-[9px] opacity-70"></i>
                                <span id="current-cat-label" class="truncate max-w-[90px]">Kategori Objek</span>
                            </div>
                            <i class="fas fa-chevron-down text-[7px]"></i>
                        </button>
                        <div id="category-options" class="hidden mt-1.5 p-1 flex flex-col gap-0.5">
                            <button onclick="toggleType('Semua')" class="type-btn w-full px-3.5 py-2 rounded-xl text-[7.5px] font-black uppercase tracking-widest text-gray-400 hover:bg-white/10 transition-all flex items-center justify-between group" data-id="Semua">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-3 h-3 rounded border border-white/20 flex items-center justify-center group-hover:border-indigo-400 transition-colors">
                                        <i class="fas fa-check text-[6px] text-indigo-400 check-icon" style="opacity:1"></i>
                                    </div>
                                    <span class="group-hover:text-white transition-colors">Semua Objek</span>
                                </div>
                            </button>
                            <button onclick="toggleType('Jalan')" class="type-btn w-full px-3.5 py-2 rounded-xl text-[7.5px] font-black uppercase tracking-widest text-gray-400 hover:bg-white/10 transition-all flex items-center justify-between group" data-type="Jalan">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-3 h-3 rounded border border-white/20 flex items-center justify-center group-hover:border-blue-400 transition-colors">
                                        <i class="fas fa-check text-[6px] text-blue-400 check-icon" style="opacity:1"></i>
                                    </div>
                                    <span class="group-hover:text-white transition-colors text-left">Infrastruktur Jalan</span>
                                </div>
                                <div class="w-2 h-2 rounded-full bg-blue-500 shadow-lg shadow-blue-500/40"></div>
                            </button>
                            <button onclick="toggleType('Sanitasi')" class="type-btn w-full px-3.5 py-2 rounded-xl text-[7.5px] font-black uppercase tracking-widest text-gray-400 hover:bg-white/10 transition-all flex items-center justify-between group" data-type="Sanitasi">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-3 h-3 rounded border border-white/20 flex items-center justify-center group-hover:border-emerald-400 transition-colors">
                                        <i class="fas fa-check text-[6px] text-emerald-400 check-icon" style="opacity:1"></i>
                                    </div>
                                    <span class="group-hover:text-white transition-colors text-left">Sanitasi / WC</span>
                                </div>
                                <div class="w-2 h-2 rounded-full bg-emerald-500 shadow-lg shadow-emerald-500/40"></div>
                            </button>
                            <button onclick="toggleType('Titian')" class="type-btn w-full px-3.5 py-2 rounded-xl text-[7.5px] font-black uppercase tracking-widest text-gray-400 hover:bg-white/10 transition-all flex items-center justify-between group" data-type="Titian">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-3 h-3 rounded border border-white/20 flex items-center justify-center group-hover:border-amber-400 transition-colors">
                                        <i class="fas fa-check text-[6px] text-amber-400 check-icon" style="opacity:1"></i>
                                    </div>
                                    <span class="group-hover:text-white transition-colors text-left">Titian</span>
                                </div>
                                <div class="w-2 h-2 rounded-full bg-amber-500 shadow-lg shadow-amber-500/40"></div>
                            </button>
                            <div class="h-[1px] bg-white/5 my-1"></div>
                            <button onclick="toggleKelurahanPoints()" class="w-full px-3.5 py-2 rounded-xl text-[7.5px] font-black uppercase tracking-widest text-gray-400 hover:bg-white/10 transition-all flex items-center justify-between group" id="kel-toggle-btn">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-3 h-3 rounded border border-white/20 flex items-center justify-center group-hover:border-emerald-400 transition-colors">
                                        <i class="fas fa-check text-[6px] text-emerald-400" id="kel-check-icon" style="opacity:0"></i>
                                    </div>
                                    <span class="group-hover:text-white transition-colors text-left">Wilayah Kelurahan</span>
                                </div>
                                <i class="fas fa-home text-emerald-500 text-[10px]"></i>
                            </button>
                        </div>
                    </div>

                    <div class="h-[1px] bg-white/5 mx-3 my-0.5"></div>

                    <!-- Kecamatan Section -->
                    <div class="p-0.5">
                        <button onclick="toggleMenu('territory-options')" class="w-full px-4 py-2.5 rounded-[1.5rem] text-[8px] font-black uppercase tracking-widest bg-white/10 text-white flex items-center justify-between shadow-lg hover:bg-white/20 transition-all group border border-white/5">
                            <div class="flex items-center gap-2.5">
                                <i class="fas fa-map-location-dot text-[9px] opacity-70 text-indigo-400"></i>
                                <span id="current-kec-label" class="truncate max-w-[90px]">Wilayah</span>
                            </div>
                            <i class="fas fa-chevron-down text-[7px]"></i>
                        </button>
                        <div id="territory-options" class="hidden mt-1.5 p-1 flex flex-col gap-0.5 max-h-40 overflow-y-auto custom-scrollbar">
                            <!-- Select All Territories -->
                            <button onclick="toggleKecamatan('Semua')" class="w-full px-3.5 py-2 rounded-xl text-[7.5px] font-black uppercase tracking-widest text-emerald-400 hover:bg-white/10 transition-all flex items-center justify-between group border-b border-white/5 mb-1" id="btn-select-all-kec">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-3 h-3 rounded border border-emerald-400/50 flex items-center justify-center group-hover:border-emerald-400 transition-colors">
                                        <i class="fas fa-check text-[6px] text-emerald-400 check-icon" id="icon-select-all-kec" style="opacity:1"></i>
                                    </div>
                                    <span class="group-hover:text-white transition-colors">Pilih Semua Wilayah</span>
                                </div>
                            </button>

                            <button onclick="toggleKecamatan('Semua')" class="kec-btn w-full px-3.5 py-2 rounded-xl text-[7.5px] font-black uppercase tracking-widest text-gray-400 hover:bg-white/10 transition-all flex items-center justify-between group hidden" data-id="Semua">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-3 h-3 rounded border border-white/20 flex items-center justify-center group-hover:border-indigo-400 transition-colors">
                                        <i class="fas fa-check text-[6px] text-indigo-400 check-icon" style="opacity:1"></i>
                                    </div>
                                    <span class="group-hover:text-white transition-colors">Semua Wilayah</span>
                                </div>
                            </button>
                            <?php $__currentLoopData = $kecamatan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kec): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <button onclick="toggleKecamatan('<?php echo e($kec->id_kecamatan); ?>')" class="kec-btn w-full px-3.5 py-2 rounded-xl text-[7.5px] font-black uppercase tracking-widest text-gray-400 hover:bg-white/10 transition-all flex items-center justify-between group" data-id="<?php echo e($kec->id_kecamatan); ?>">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-3 h-3 rounded border border-white/20 flex items-center justify-center group-hover:border-indigo-400 transition-colors">
                                        <i class="fas fa-check text-[6px] text-indigo-400 check-icon" style="opacity:1"></i>
                                    </div>
                                    <span class="truncate max-w-[80px] group-hover:text-white transition-colors text-left"><?php echo e($kec->nama_kecamatan); ?></span>
                                </div>
                                <div class="w-2.5 h-2.5 rounded shadow-sm" style="background-color: <?php echo e($kec->warna ?? '#6366f1'); ?>;"></div>
                            </button>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                        <button onclick="changeBaseLayer('google')" class="flex items-center gap-3 px-4 py-3 rounded-2xl hover:bg-white/10 transition-all group">
                            <i class="fab fa-google text-blue-400 text-[10px]"></i>
                            <span class="text-[9px] font-black uppercase tracking-widest text-gray-300">Google Maps</span>
                        </button>
                        <button onclick="changeBaseLayer('satellite')" class="flex items-center gap-3 px-4 py-3 rounded-2xl hover:bg-white/10 transition-all group">
                            <i class="fas fa-satellite text-emerald-400 text-[10px]"></i>
                            <span class="text-[9px] font-black uppercase tracking-widest text-gray-300">Satelit</span>
                        </button>
                        <button onclick="changeBaseLayer('dark')" class="flex items-center gap-3 px-4 py-3 rounded-2xl hover:bg-white/10 transition-all group">
                            <i class="fas fa-moon text-indigo-400 text-[10px]"></i>
                            <span class="text-[9px] font-black uppercase tracking-widest text-gray-300">Dark Mode</span>
                        </button>
                        <button onclick="changeBaseLayer('greyscale')" class="flex items-center gap-3 px-4 py-3 rounded-2xl hover:bg-white/10 transition-all group">
                            <i class="fas fa-adjust text-gray-300 text-[10px]"></i>
                            <span class="text-[9px] font-black uppercase tracking-widest text-gray-300">Greyscale</span>
                        </button>
                        <button onclick="changeBaseLayer('osm')" class="flex items-center gap-3 px-4 py-3 rounded-2xl hover:bg-white/10 transition-all group">
                            <i class="fas fa-map-marked-alt text-amber-400 text-[10px]"></i>
                            <span class="text-[9px] font-black uppercase tracking-widest text-gray-300">OSM Default</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        const baseLayers = {
            osm: L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png'),
            street: L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png'),
            satellite: L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}'),
            dark: L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png'),
            google: L.tileLayer('http://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}', {maxZoom: 20, subdomains:['mt0','mt1','mt2','mt3']}),
            greyscale: L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png')
        };

        const map = L.map('main-map', { zoomControl: false, attributionControl: false }).setView([-3.316694, 114.590111], 13);
        let currentBaseLayer = baseLayers.osm.addTo(map);

        // Create panes to manage layering
        map.createPane('polygonsPane');
        map.getPane('polygonsPane').style.zIndex = 400;
        map.createPane('markersPane');
        map.getPane('markersPane').style.zIndex = 650;

        const dataPoints = <?php echo json_encode($infrastruktur, 15, 512) ?>;
        const kecamatans = <?php echo json_encode($kecamatan, 15, 512) ?>;
        const kelurahans = <?php echo json_encode($kelurahan, 15, 512) ?>;
        let activeMarkers = [];
        let kelurahanMarkers = [];
        let kelurahanPolygons = [];
        let geoLayers = {};
        let showKelurahan = false;

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
                const kondisiAktual = point.analisis?.label_prioritas || point.kondisi;
                const isBaik = kondisiAktual.toLowerCase().includes('baik');
                const isRingan = kondisiAktual.toLowerCase().includes('ringan') || kondisiAktual.toLowerCase().includes('sedang');
                const color = isBaik ? '#10b981' : (isRingan ? '#f59e0b' : '#ef4444');
                const icon = L.divIcon({
                    html: `<div style="background-color: ${color}; width: 14px; height: 14px; border-radius: 50%; border: 2.5px solid white; box-shadow: 0 4px 10px rgba(0,0,0,0.2);"></div>`,
                    className: '', iconSize: [14, 14], iconAnchor: [7, 7]
                });

                const popupContent = `
                    <div class="p-1" style="min-width: 240px;">
                        <div class="relative h-32 rounded-2xl bg-gray-100 mb-3 overflow-hidden shadow-inner">
                            <img src="/storage/${point.foto_terbaru}" class="w-full h-full object-cover">
                            <div class="absolute top-2 left-2 px-2 py-1 bg-white/90 backdrop-blur-md rounded-lg text-[7px] font-black uppercase tracking-widest text-[#1e1b4b]">
                                ${point.jenis_infrastruktur || point.jenis || '-'}
                            </div>
                        </div>
                        <div class="px-1">
                            <h4 class="text-xs font-black text-[#1e1b4b] mb-1">${point.nama_objek || point.nama_infrastruktur || '-'}</h4>
                            <p class="text-[8px] text-gray-400 font-bold uppercase mb-3">Wilayah: ${point.kelurahan?.nama_kelurahan ?? '-'}</p>
                            
                            <div class="flex items-center gap-2 mb-3">
                                <span class="px-2 py-1 rounded-full text-[7px] font-black uppercase tracking-widest" style="background-color: ${color}15; color: ${color}; border: 1px solid ${color}30;">
                                    ${kondisiAktual}
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
                                weight: 1.5,
                                opacity: 1,
                                color: '#10b981',
                                fillOpacity: 0
                            }
                        }).addTo(map);

                        poly.bindPopup(`<p class="text-[10px] font-black text-[#1e1b4b] uppercase">${kel.nama_kelurahan}</p>`, { 
                            className: 'custom-polygon-popup', 
                            closeButton: false 
                        });

                        poly.on('mouseover', function() { this.setStyle({ color: '#059669', weight: 2.5 }); });
                        poly.on('mouseout', function() { this.setStyle({ color: '#10b981', weight: 1.5 }); });

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
        const allAvailableTypes = ['Jalan', 'Sanitasi', 'Titian'];
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
                const pType = (p.jenis_infrastruktur || p.jenis || '').toLowerCase().trim();
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
                ringan: points.filter(p => { const k = (p.analisis?.label_prioritas || p.kondisi || '').toLowerCase(); return k.includes('ringan') || k.includes('sedang'); }).length,
                berat: points.filter(p => { const k = (p.analisis?.label_prioritas || p.kondisi || '').toLowerCase(); return k.includes('berat'); }).length
            };

            document.getElementById('stat-total').textContent = stats.total;
            document.getElementById('stat-baik').textContent = stats.baik;
            document.getElementById('stat-ringan').textContent = stats.ringan;
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
<?php /**PATH C:\laragon1\laragon\www\geo-sinfra\resources\views/kabid/monitoring.blade.php ENDPATH**/ ?>