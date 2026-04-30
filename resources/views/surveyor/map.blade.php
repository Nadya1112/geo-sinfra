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

    @include('surveyor.partials.sidebar')

    <main class="flex-1 flex flex-col h-screen overflow-hidden">
        <header class="bg-white border-b border-gray-100 px-8 py-5 flex justify-between items-center z-10">
            <div class="flex items-center gap-4">
                <a href="{{ route('surveyor.dashboard') }}" class="w-10 h-10 flex items-center justify-center bg-gray-50 text-gray-400 rounded-xl hover:bg-emerald-50 hover:text-emerald-600 transition-all border border-gray-100">
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
                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-tighter">{{ now()->translatedFormat('l, d F Y') }}</p>
                </div>
                <div class="h-8 w-[1px] bg-gray-100"></div>
                <a href="{{ route('surveyor.profile') }}" class="flex items-center gap-3">
                    <div class="text-right">
                        <p class="text-[11px] font-black text-[#1e1b4b] leading-none uppercase">{{ auth()->user()->name }}</p>
                        <p class="text-[9px] font-bold text-emerald-500 uppercase mt-1">ONLINE</p>
                    </div>
                    <div class="w-10 h-10 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-600 border border-emerald-100 overflow-hidden">
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

                <!-- Total Stats -->
                <div class="bg-[#1e1b4b]/95 backdrop-blur-xl p-5 rounded-[2.5rem] border border-white/10 shadow-2xl text-white overflow-hidden relative group flex flex-col justify-center min-w-[140px]">
                    <div class="absolute -right-4 -bottom-4 w-16 h-16 bg-blue-500/10 rounded-full blur-xl group-hover:scale-150 transition-transform duration-700"></div>
                    <p class="text-[8px] font-black text-blue-300 uppercase tracking-widest mb-1 relative z-10">Total Terdata</p>
                    <h5 class="text-xl font-black relative z-10"><span id="total-points">{{ $dataMap->count() }}</span> <span class="text-[9px] font-medium text-blue-300">Titik</span></h5>
                </div>
            </div>

            <!-- Floating Type Filters Right -->
            <div class="absolute top-24 right-6 z-10">
                <div class="bg-white/90 backdrop-blur-xl p-6 rounded-[2.5rem] border border-white shadow-2xl flex flex-col gap-3 min-w-[160px]">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 border-b border-gray-50 pb-3">Kategori</p>
                    <div class="flex flex-col gap-2">
                        <button onclick="filterByType('Semua')" class="filter-btn active w-full px-5 py-3 rounded-2xl text-[10px] font-black uppercase tracking-widest bg-[#1e1b4b] text-white transition-all shadow-xl shadow-indigo-900/20 text-left flex items-center justify-between">
                            <span>Semua</span>
                            <i class="fas fa-layer-group opacity-30"></i>
                        </button>
                        <button onclick="filterByType('Jalan')" class="filter-btn w-full px-5 py-3 rounded-2xl text-[10px] font-black uppercase tracking-widest bg-gray-50 text-gray-400 hover:bg-emerald-50 hover:text-emerald-700 transition-all text-left flex items-center justify-between">
                            <span>Jalan</span>
                            <i class="fas fa-road opacity-30"></i>
                        </button>
                        <button onclick="filterByType('Jembatan')" class="filter-btn w-full px-5 py-3 rounded-2xl text-[10px] font-black uppercase tracking-widest bg-gray-50 text-gray-400 hover:bg-emerald-50 hover:text-emerald-700 transition-all text-left flex items-center justify-between">
                            <span>Jembatan</span>
                            <i class="fas fa-bridge opacity-30"></i>
                        </button>
                        <button onclick="filterByType('Drainase')" class="filter-btn w-full px-5 py-3 rounded-2xl text-[10px] font-black uppercase tracking-widest bg-gray-50 text-gray-400 hover:bg-emerald-50 hover:text-emerald-700 transition-all text-left flex items-center justify-between">
                            <span>Drainase</span>
                            <i class="fas fa-water opacity-30"></i>
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

        const dataPoints = @json($dataMap);
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

            document.getElementById('total-points').textContent = points.length;

            if (points.length > 0) {
                const group = new L.featureGroup(activeMarkers);
                map.fitBounds(group.getBounds().pad(0.2));
            }
        }

        function filterByType(type) {
            document.querySelectorAll('.filter-btn').forEach(btn => {
                btn.classList.remove('active', 'bg-[#1e1b4b]', 'text-white', 'shadow-xl', 'shadow-indigo-900/20');
                btn.classList.add('bg-gray-50', 'text-gray-400');
            });
            event.currentTarget.classList.add('active', 'bg-[#1e1b4b]', 'text-white', 'shadow-xl', 'shadow-indigo-900/20');
            event.currentTarget.classList.remove('bg-gray-50', 'text-gray-400');

            if (type === 'Semua') {
                renderMarkers(dataPoints);
            } else {
                const filtered = dataPoints.filter(p => p.jenis_infrastruktur === type);
                renderMarkers(filtered);
            }
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
