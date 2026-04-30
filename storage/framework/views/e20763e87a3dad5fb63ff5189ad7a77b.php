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
            <div class="absolute bottom-10 left-6 z-10 flex items-stretch gap-4">
                <!-- Legend -->
                <div class="bg-white/90 backdrop-blur-xl p-5 rounded-[2.5rem] border border-white shadow-2xl min-w-[220px]">
                    <div class="flex items-center gap-3 mb-4 border-b border-gray-50 pb-3">
                        <div class="w-7 h-7 bg-emerald-50 text-emerald-600 rounded-lg flex items-center justify-center">
                            <i class="fas fa-list-check text-[10px]"></i>
                        </div>
                        <h4 class="text-[9px] font-black text-[#1e1b4b] uppercase tracking-widest">Kondisi Objek</h4>
                    </div>
                    <div class="flex flex-col gap-3">
                        <button onclick="filterByCondition('Baik')" class="flex items-center justify-between group">
                            <div class="flex items-center gap-3">
                                <div class="w-2 h-2 bg-emerald-500 rounded-full"></div>
                                <span class="text-[10px] font-bold text-gray-500 group-hover:text-[#1e1b4b] transition-colors">Baik / Normal</span>
                            </div>
                            <i class="fas fa-chevron-right text-[7px] text-gray-300 group-hover:translate-x-1 transition-all"></i>
                        </button>
                        <button onclick="filterByCondition('Rusak Ringan')" class="flex items-center justify-between group">
                            <div class="flex items-center gap-3">
                                <div class="w-2 h-2 bg-amber-500 rounded-full"></div>
                                <span class="text-[10px] font-bold text-gray-500 group-hover:text-[#1e1b4b] transition-colors">Rusak Ringan</span>
                            </div>
                            <i class="fas fa-chevron-right text-[7px] text-gray-300 group-hover:translate-x-1 transition-all"></i>
                        </button>
                        <button onclick="filterByCondition('Rusak Berat')" class="flex items-center justify-between group">
                            <div class="flex items-center gap-3">
                                <div class="w-2 h-2 bg-red-500 rounded-full"></div>
                                <span class="text-[10px] font-bold text-gray-500 group-hover:text-[#1e1b4b] transition-colors">Rusak Berat</span>
                            </div>
                            <i class="fas fa-chevron-right text-[7px] text-gray-300 group-hover:translate-x-1 transition-all"></i>
                        </button>
                    </div>
                </div>

            </div>

            <!-- Floating Type Filters Right -->
            <div class="absolute top-6 right-6 z-10">
                <div id="category-card" class="bg-white/90 backdrop-blur-xl p-2 rounded-[2rem] border border-white shadow-2xl min-w-[180px] transition-all duration-300">
                    <button onclick="toggleCategoryMenu()" class="w-full px-6 py-4 rounded-[1.8rem] text-[10px] font-black uppercase tracking-widest bg-[#1e1b4b] text-white flex items-center justify-between shadow-xl hover:bg-[#2d2a6e] transition-all group">
                        <div class="flex items-center gap-3">
                            <div class="w-6 h-6 bg-white/10 rounded-lg flex items-center justify-center">
                                <i class="fas fa-layer-group text-[10px]"></i>
                            </div>
                            <span id="current-cat-label">Semua Kategori</span>
                        </div>
                        <i id="cat-chevron" class="fas fa-chevron-down text-[8px] transition-transform duration-300"></i>
                    </button>
                    
                    <div id="category-options" class="hidden mt-2 p-2 flex flex-col gap-1 overflow-hidden">
                        <button onclick="handleCategorySelect('Semua')" class="cat-opt-btn w-full px-5 py-3 rounded-2xl text-[9px] font-black uppercase tracking-widest text-gray-400 hover:bg-emerald-50 hover:text-emerald-700 transition-all text-left flex items-center gap-3 group">
                            <div class="w-1.5 h-1.5 rounded-full bg-gray-200 group-hover:bg-emerald-500 transition-colors"></div>
                            Semua
                        </button>
                        <button onclick="handleCategorySelect('Jalan')" class="cat-opt-btn w-full px-5 py-3 rounded-2xl text-[9px] font-black uppercase tracking-widest text-gray-400 hover:bg-emerald-50 hover:text-emerald-700 transition-all text-left flex items-center gap-3 group">
                            <div class="w-1.5 h-1.5 rounded-full bg-gray-200 group-hover:bg-emerald-500 transition-colors"></div>
                            Jalan
                        </button>
                        <button onclick="handleCategorySelect('Jembatan')" class="cat-opt-btn w-full px-5 py-3 rounded-2xl text-[9px] font-black uppercase tracking-widest text-gray-400 hover:bg-emerald-50 hover:text-emerald-700 transition-all text-left flex items-center gap-3 group">
                            <div class="w-1.5 h-1.5 rounded-full bg-gray-200 group-hover:bg-emerald-500 transition-colors"></div>
                            Jembatan
                        </button>
                        <button onclick="handleCategorySelect('Drainase')" class="cat-opt-btn w-full px-5 py-3 rounded-2xl text-[9px] font-black uppercase tracking-widest text-gray-400 hover:bg-emerald-50 hover:text-emerald-700 transition-all text-left flex items-center gap-3 group">
                            <div class="w-1.5 h-1.5 rounded-full bg-gray-200 group-hover:bg-emerald-500 transition-colors"></div>
                            Drainase
                        </button>
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
        let activeMarkers = [];

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

        function toggleCategoryMenu() {
            const menu = document.getElementById('category-options');
            const chevron = document.getElementById('cat-chevron');
            menu.classList.toggle('hidden');
            chevron.classList.toggle('rotate-180');
        }

        function handleCategorySelect(type) {
            // Update Label
            document.getElementById('current-cat-label').textContent = type === 'Semua' ? 'Semua Kategori' : type;
            
            // Close Menu
            toggleCategoryMenu();

            // Filter logic
            if (type === 'Semua') {
                renderMarkers(dataPoints);
            } else {
                const filtered = dataPoints.filter(p => p.jenis_infrastruktur === type);
                renderMarkers(filtered);
            }

            // Update UI Active State
            document.querySelectorAll('.cat-opt-btn').forEach(btn => {
                if (btn.innerText.includes(type)) {
                    btn.classList.add('bg-emerald-50', 'text-emerald-700');
                    btn.classList.remove('text-gray-400');
                } else {
                    btn.classList.remove('bg-emerald-50', 'text-emerald-700');
                    btn.classList.add('text-gray-400');
                }
            });
        }

        function filterByCondition(condition) {
            const filtered = dataPoints.filter(p => p.kondisi === condition);
            renderMarkers(filtered);
        }

        renderMarkers(dataPoints);
    </script>

    <style>
        .leaflet-popup-content-wrapper { border-radius: 1.5rem; padding: 5px; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); }
        .leaflet-popup-tip-container { display: none; }
    </style>
</body>
</html>
<?php /**PATH C:\laragon1\laragon\www\geo-sinfra\resources\views/surveyor/map.blade.php ENDPATH**/ ?>