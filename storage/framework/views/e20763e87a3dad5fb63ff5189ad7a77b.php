<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peta Sebaran Saya | GEO-SINFRA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <style> body { font-family: 'Plus Jakarta Sans', sans-serif; } </style>
</head>
<body class="bg-gray-50 flex h-screen overflow-hidden text-gray-800 text-left">

    <?php echo $__env->make('surveyor.partials.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <main class="flex-1 flex flex-col h-screen overflow-hidden">
        <header class="bg-white border-b border-gray-100 px-8 py-5 flex justify-between items-center z-10">
            <div class="flex items-center gap-4">
                <a href="<?php echo e(route('surveyor.dashboard')); ?>" class="w-10 h-10 flex items-center justify-center bg-gray-50 text-gray-400 rounded-xl hover:bg-emerald-50 hover:text-emerald-600 transition-all border border-gray-100">
                    <i class="fas fa-arrow-left text-sm"></i>
                </a>
                <div>
                    <p class="text-[10px] font-extrabold text-emerald-600 uppercase tracking-[0.2em] mb-1">Visualisasi Geografis</p>
                    <h2 class="text-xl font-black text-[#1e1b4b]">Peta Sebaran Laporan Saya</h2>
                </div>
            </div>
            
            <div class="flex items-center gap-6">
                <div class="text-right hidden sm:block">
                    <p class="text-[11px] font-black text-[#1e1b4b]" id="mini-clock">00:00 WITA</p>
                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-tighter"><?php echo e(now()->translatedFormat('l, d F Y')); ?></p>
                </div>
                <div class="h-8 w-[1px] bg-gray-100"></div>
                <a href="<?php echo e(route('surveyor.profile')); ?>" class="flex items-center gap-3">
                    <div class="text-right">
                        <p class="text-[11px] font-black text-[#1e1b4b] leading-none uppercase"><?php echo e(auth()->user()->name); ?></p>
                        <p class="text-[9px] font-bold text-emerald-500 uppercase mt-1">ONLINE</p>
                    </div>
                    <div class="w-10 h-10 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-600 border border-emerald-100 overflow-hidden">
                        <?php if(auth()->user()->profile_photo): ?>
                            <img src="<?php echo e(asset('storage/' . auth()->user()->profile_photo)); ?>" class="w-full h-full object-cover">
                        <?php else: ?>
                            <i class="fas fa-user-circle text-xl"></i>
                        <?php endif; ?>
                    </div>
                </a>
            </div>
        </header>

        <div class="flex-1 relative">
            <div id="main-map" class="absolute inset-0 z-0"></div>
            


            <!-- Map Overlay UI Bottom Left -->
            <div class="absolute bottom-10 left-6 z-10">
                <div id="condition-card" class="bg-[#1e1b4b]/80 backdrop-blur-xl p-2 rounded-[2.5rem] border border-white/10 shadow-2xl min-w-[200px] transition-all duration-300">
                    <button onclick="toggleConditionMenu()" class="w-full px-6 py-4 rounded-[1.8rem] text-[10px] font-black uppercase tracking-widest bg-white/10 text-white flex items-center justify-between shadow-sm hover:bg-white/20 transition-all group border border-white/5">
                        <div class="flex items-center gap-3">
                            <div class="w-6 h-6 bg-emerald-500/20 text-emerald-400 rounded-lg flex items-center justify-center">
                                <i class="fas fa-list-check text-[10px]"></i>
                            </div>
                            <span id="current-cond-label">Kondisi Objek</span>
                        </div>
                        <i id="cond-chevron" class="fas fa-chevron-up text-[8px] transition-transform duration-300"></i>
                    </button>
                    
                    <div id="condition-options" class="hidden mt-2 p-2 flex flex-col gap-1">
                        <button onclick="handleConditionSelect('Semua')" class="w-full px-5 py-3 rounded-2xl text-[9px] font-black uppercase tracking-widest text-gray-300 hover:bg-white/10 transition-all flex items-center justify-between group">
                            <span class="group-hover:text-white transition-colors">Semua Kondisi</span>
                            <span class="text-[9px] font-black text-blue-400 bg-blue-500/10 px-2.5 py-1 rounded-lg border border-blue-400/20 shadow-sm"><?php echo e($dataMap->count()); ?></span>
                        </button>
                        <button onclick="handleConditionSelect('Baik')" class="w-full px-5 py-3 rounded-2xl text-[9px] font-black uppercase tracking-widest text-gray-400 hover:bg-emerald-500/10 hover:text-emerald-400 transition-all flex items-center justify-between group">
                            <div class="flex items-center gap-3">
                                <div class="w-2.5 h-2.5 bg-emerald-500 rounded-full shadow-lg shadow-emerald-500/40"></div>
                                <span class="group-hover:text-emerald-400 transition-colors">Kondisi Baik</span>
                            </div>
                            <span class="text-[9px] font-black text-emerald-400 bg-white/5 px-2.5 py-1 rounded-lg border border-emerald-400/20 shadow-sm"><?php echo e($dataMap->where('kondisi', 'Baik')->count()); ?></span>
                        </button>
                        <button onclick="handleConditionSelect('Rusak Ringan')" class="w-full px-5 py-3 rounded-2xl text-[9px] font-black uppercase tracking-widest text-gray-400 hover:bg-amber-500/10 hover:text-amber-400 transition-all flex items-center justify-between group">
                            <div class="flex items-center gap-3">
                                <div class="w-2.5 h-2.5 bg-amber-500 rounded-full shadow-lg shadow-amber-500/40"></div>
                                <span class="group-hover:text-amber-400 transition-colors">Rusak Ringan</span>
                            </div>
                            <span class="text-[9px] font-black text-amber-400 bg-white/5 px-2.5 py-1 rounded-lg border border-amber-400/20 shadow-sm"><?php echo e($dataMap->where('kondisi', 'Rusak Ringan')->count()); ?></span>
                        </button>
                        <button onclick="handleConditionSelect('Rusak Berat')" class="w-full px-5 py-3 rounded-2xl text-[9px] font-black uppercase tracking-widest text-gray-400 hover:bg-red-500/10 hover:text-red-400 transition-all flex items-center justify-between group">
                            <div class="flex items-center gap-3">
                                <div class="w-2.5 h-2.5 bg-red-500 rounded-full shadow-lg shadow-red-500/40"></div>
                                <span class="group-hover:text-red-400 transition-colors">Rusak Berat</span>
                            </div>
                            <span class="text-[9px] font-black text-red-400 bg-white/5 px-2.5 py-1 rounded-lg border border-red-400/20 shadow-sm"><?php echo e($dataMap->where('kondisi', 'Rusak Berat')->count()); ?></span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Floating Filters Right (Combined) -->
            <div class="absolute top-6 right-6 z-10">
                <div class="bg-[#1e1b4b]/80 backdrop-blur-xl p-2 rounded-[2.8rem] border border-white/10 shadow-2xl min-w-[220px] transition-all duration-300">
                    <!-- Category Section -->
                    <div id="category-card" class="p-1">
                        <button onclick="toggleCategoryMenu()" class="w-full px-5 py-3.5 rounded-[2rem] text-[9px] font-black uppercase tracking-widest bg-emerald-600/90 text-white flex items-center justify-between shadow-lg hover:bg-emerald-600 transition-all group">
                            <div class="flex items-center gap-3">
                                <i class="fas fa-layer-group text-[10px] opacity-70"></i>
                                <span id="current-cat-label" class="truncate max-w-[100px]">Semua Kategori</span>
                            </div>
                            <i id="cat-chevron" class="fas fa-chevron-down text-[7px] transition-transform duration-300"></i>
                        </button>
                        
                        <div id="category-options" class="hidden mt-2 p-1 flex flex-col gap-1">
                            <button onclick="handleCategorySelect('Semua')" class="cat-opt-btn w-full px-4 py-2.5 rounded-xl text-[8px] font-black uppercase tracking-widest text-gray-400 hover:bg-white/10 hover:text-white transition-all text-left flex items-center gap-3">Semua</button>
                            <button onclick="handleCategorySelect('Jalan')" class="cat-opt-btn w-full px-4 py-2.5 rounded-xl text-[8px] font-black uppercase tracking-widest text-gray-400 hover:bg-white/10 hover:text-white transition-all text-left flex items-center gap-3">Jalan</button>
                            <button onclick="handleCategorySelect('Jembatan')" class="cat-opt-btn w-full px-4 py-2.5 rounded-xl text-[8px] font-black uppercase tracking-widest text-gray-400 hover:bg-white/10 hover:text-white transition-all text-left flex items-center gap-3">Jembatan</button>
                            <button onclick="handleCategorySelect('Drainase')" class="cat-opt-btn w-full px-4 py-2.5 rounded-xl text-[8px] font-black uppercase tracking-widest text-gray-400 hover:bg-white/10 hover:text-white transition-all text-left flex items-center gap-3">Drainase</button>
                        </div>
                    </div>

                    <div class="h-[1px] bg-white/5 mx-4 my-1"></div>

                    <!-- Territory Section -->
                    <div id="territory-card" class="p-1">
                        <button onclick="toggleTerritoryMenu()" class="w-full px-5 py-3.5 rounded-[2rem] text-[9px] font-black uppercase tracking-widest bg-white/10 text-white flex items-center justify-between shadow-lg hover:bg-white/20 transition-all group border border-white/5">
                            <div class="flex items-center gap-3">
                                <i class="fas fa-map-location-dot text-[10px] opacity-70 text-emerald-400"></i>
                                <span id="current-territory-label" class="truncate max-w-[100px]">Semua Wilayah</span>
                            </div>
                            <i id="territory-chevron" class="fas fa-chevron-down text-[7px] transition-transform duration-300"></i>
                        </button>
                        
                        <div id="territory-options" class="hidden mt-2 p-1 flex flex-col gap-1 max-h-48 overflow-y-auto custom-scrollbar">
                            <button onclick="handleTerritorySelect('Semua')" class="territory-opt-btn w-full px-5 py-3 rounded-xl text-[8px] font-black uppercase tracking-widest text-gray-400 hover:bg-white/10 transition-all flex items-center justify-between group">
                                <div class="flex items-center gap-3">
                                    <div class="w-3.5 h-3.5 rounded border border-white/20 flex items-center justify-center group-hover:border-emerald-400 transition-colors">
                                        <i class="fas fa-check text-[7px] text-emerald-400 opacity-0 group-hover:opacity-100"></i>
                                    </div>
                                    <span class="group-hover:text-white transition-colors">Semua Wilayah</span>
                                </div>
                                <div class="w-3.5 h-3.5 rounded bg-gray-500/30 shadow-inner"></div>
                            </button>
                            <?php $__currentLoopData = $myKecamatans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kec): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <button onclick="handleTerritorySelect('<?php echo e($kec->id_kecamatan); ?>', '<?php echo e($kec->nama_kecamatan); ?>')" class="territory-opt-btn w-full px-5 py-3 rounded-xl text-[8px] font-black uppercase tracking-widest text-gray-400 hover:bg-white/10 transition-all flex items-center justify-between group">
                                <div class="flex items-center gap-3">
                                    <div class="w-3.5 h-3.5 rounded border border-white/20 flex items-center justify-center group-hover:border-emerald-400 transition-colors">
                                        <i class="fas fa-check text-[7px] text-emerald-400 opacity-0 group-hover:opacity-100"></i>
                                    </div>
                                    <span class="truncate max-w-[110px] group-hover:text-white transition-colors"><?php echo e($kec->nama_kecamatan); ?></span>
                                </div>
                                <div class="w-3.5 h-3.5 rounded shadow-lg border border-white/10" style="background-color: <?php echo e($kec->warna ?? '#cbd5e1'); ?>; box-shadow: 0 0 10px <?php echo e(($kec->warna ?? '#cbd5e1')); ?>40;"></div>
                            </button>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        function updateClock() {
            const now = new Date();
            document.getElementById('mini-clock').textContent = `${String(now.getHours()).padStart(2, '0')}:${String(now.getMinutes()).padStart(2, '0')} WITA`;
        }
        setInterval(updateClock, 1000); updateClock();

        const map = L.map('main-map', {
            zoomControl: false,
            attributionControl: false
        }).setView([-3.316694, 114.590111], 13);
        
        // Premium Tile Layer
        L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png').addTo(map);

        const dataPoints = <?php echo json_encode($dataMap, 15, 512) ?>;
        const myKecamatans = <?php echo json_encode($myKecamatans, 15, 512) ?>;
        let activeMarkers = [];
        let geoLayers = {};

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
                const color = point.kondisi == 'Baik' ? '#10b981' : (point.kondisi == 'Rusak Ringan' ? '#f59e0b' : '#ef4444');
                
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

                const popupContent = `
                    <div class="p-1" style="min-width: 240px;">
                        <div class="relative h-36 rounded-2xl bg-gray-100 mb-4 overflow-hidden shadow-inner">
                            <img src="/storage/${point.foto_terbaru}" class="w-full h-full object-cover">
                            <div class="absolute top-3 left-3 px-3 py-1 bg-white/90 backdrop-blur-md rounded-lg text-[8px] font-black uppercase tracking-widest text-[#1e1b4b] shadow-sm">
                                ${point.jenis_infrastruktur}
                            </div>
                        </div>
                        
                        <div class="px-2">
                            <h4 class="text-sm font-black text-[#1e1b4b] mb-1 leading-tight">${point.nama_infrastruktur}</h4>
                            <p class="text-[9px] text-gray-400 font-bold uppercase tracking-tighter mb-4">${new Date(point.updated_at).toLocaleDateString('id-ID', {day:'numeric', month:'long', year:'numeric'})}</p>
                            
                            <div class="flex items-center gap-2 mb-4">
                                <span class="px-3 py-1 rounded-full text-[8px] font-black uppercase tracking-widest" style="background-color: ${color}15; color: ${color}; border: 1px solid ${color}30;">
                                    ${point.kondisi}
                                </span>
                                <span class="px-3 py-1 rounded-full text-[8px] font-black uppercase tracking-widest ${point.status_verifikasi == 'Verified' ? 'bg-blue-50 text-blue-600 border border-blue-100' : 'bg-amber-50 text-amber-600 border border-amber-100'}">
                                    ${point.status_verifikasi ?? 'Pending'}
                                </span>
                            </div>

                            <div class="grid grid-cols-2 gap-2 mb-4 bg-gray-50/50 p-3 rounded-2xl border border-gray-100">
                                <div>
                                    <p class="text-[7px] font-black text-gray-400 uppercase tracking-widest mb-0.5">CNN Score</p>
                                    <p class="text-[10px] font-bold text-emerald-600">${point.cnn ? (point.cnn.skor_cnn * 100).toFixed(1) + '%' : '-'}</p>
                                </div>
                                <div>
                                    <p class="text-[7px] font-black text-gray-400 uppercase tracking-widest mb-0.5">Priority</p>
                                    <p class="text-[10px] font-bold text-blue-600">${point.analisis ? point.analisis.label_prioritas : '-'}</p>
                                </div>
                            </div>

                            <div class="flex gap-2 pt-2 border-t border-gray-50">
                                <a href="/surveyor/infrastruktur/${point.id_infrastruktur}/edit" class="flex-1 py-2.5 bg-[#1e1b4b] text-white rounded-xl text-[9px] font-black uppercase tracking-widest text-center hover:bg-emerald-600 transition-all shadow-lg shadow-indigo-900/10">Edit Data</a>
                                <a href="/surveyor/infrastruktur/${point.id_infrastruktur}" class="w-10 h-10 flex items-center justify-center bg-gray-100 text-gray-400 rounded-xl hover:bg-emerald-50 hover:text-emerald-600 transition-all">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                `;

                const marker = L.marker([point.latitude, point.longitude], {icon: icon})
                    .addTo(map)
                    .bindPopup(popupContent, {
                        maxWidth: 300,
                        className: 'premium-popup'
                    });
                
                activeMarkers.push(marker);
            });

            if (points.length > 0) {
                const group = new L.featureGroup(activeMarkers);
                map.fitBounds(group.getBounds().pad(0.2));
            }
        }

        let activeType = 'Semua';
        let activeTerritory = 'Semua';

        function applyFilters() {
            let filtered = dataPoints;
            
            // Filter by Type
            if (activeType !== 'Semua') {
                filtered = filtered.filter(p => p.jenis_infrastruktur === activeType);
            }
            
            // Filter by Territory
            if (activeTerritory !== 'Semua') {
                filtered = filtered.filter(p => p.id_kecamatan == activeTerritory);
            }
            
            renderMarkers(filtered);
        }

        function toggleCategoryMenu() {
            const menu = document.getElementById('category-options');
            const chevron = document.getElementById('cat-chevron');
            menu.classList.toggle('hidden');
            chevron.classList.toggle('rotate-180');
        }

        function handleCategorySelect(type) {
            document.getElementById('current-cat-label').textContent = type === 'Semua' ? 'Semua Kategori' : type;
            toggleCategoryMenu();
            activeType = type;
            applyFilters();
        }

        function toggleTerritoryMenu() {
            const menu = document.getElementById('territory-options');
            const chevron = document.getElementById('territory-chevron');
            menu.classList.toggle('hidden');
            chevron.classList.toggle('rotate-180');
        }

        function handleTerritorySelect(id, name) {
            document.getElementById('current-territory-label').textContent = id === 'Semua' ? 'Semua Wilayah' : name;
            toggleTerritoryMenu();
            activeTerritory = id;
            applyFilters();

            // Zoom ke Wilayah yang dipilih
            if (id !== 'Semua' && geoLayers[id]) {
                map.fitBounds(geoLayers[id].getBounds(), { padding: [50, 50], maxZoom: 15 });
            }
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
                let filtered = dataPoints.filter(p => p.kondisi === cond);
                renderMarkers(filtered);
            }
        }

        renderMarkers(dataPoints);
    </script>

    <style>
        .leaflet-popup-content-wrapper { border-radius: 2rem; padding: 5px; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); border: 1px solid rgba(255,255,255,0.2); }
        .leaflet-popup-tip-container { display: none; }
    </style>
</body>
</html>
<?php /**PATH C:\laragon1\laragon\www\geo-sinfra\resources\views/surveyor/map.blade.php ENDPATH**/ ?>