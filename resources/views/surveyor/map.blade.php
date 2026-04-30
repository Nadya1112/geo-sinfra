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
            
            <!-- Map Overlay UI -->
            <div class="absolute top-6 left-6 z-10 space-y-4 max-w-xs">
                <div class="bg-white/90 backdrop-blur-md p-6 rounded-[2rem] border border-gray-100 shadow-xl">
                    <h4 class="text-xs font-black text-[#1e1b4b] uppercase tracking-widest mb-4">Legenda Kondisi</h4>
                    <div class="space-y-3">
                        <div class="flex items-center gap-3 cursor-pointer hover:bg-gray-50 p-1 rounded-lg transition-all" onclick="filterByCondition('Baik')">
                            <div class="w-3 h-3 bg-emerald-500 rounded-full shadow-lg shadow-emerald-500/20"></div>
                            <span class="text-[10px] font-bold text-gray-600">Baik / Normal</span>
                        </div>
                        <div class="flex items-center gap-3 cursor-pointer hover:bg-gray-50 p-1 rounded-lg transition-all" onclick="filterByCondition('Rusak Ringan')">
                            <div class="w-3 h-3 bg-yellow-500 rounded-full shadow-lg shadow-yellow-500/20"></div>
                            <span class="text-[10px] font-bold text-gray-600">Rusak Ringan</span>
                        </div>
                        <div class="flex items-center gap-3 cursor-pointer hover:bg-gray-50 p-1 rounded-lg transition-all" onclick="filterByCondition('Rusak Berat')">
                            <div class="w-3 h-3 bg-red-500 rounded-full shadow-lg shadow-red-500/20"></div>
                            <span class="text-[10px] font-bold text-gray-600">Rusak Berat</span>
                        </div>
                    </div>
                </div>

                <div class="bg-[#1e1b4b]/90 backdrop-blur-md p-6 rounded-[2rem] border border-white/10 shadow-xl text-white">
                    <p class="text-[9px] font-bold text-blue-200 uppercase tracking-widest mb-1">Ringkasan Lokasi</p>
                    <h5 class="text-lg font-black"><span id="total-points">{{ $dataMap->count() }}</span> <span class="text-xs font-medium text-blue-300">Titik Laporan</span></h5>
                </div>
            </div>

            <!-- Floating Type Filters -->
            <div class="absolute top-6 right-6 z-10">
                <div class="bg-white/90 backdrop-blur-md px-6 py-3 rounded-2xl border border-gray-100 shadow-xl flex items-center gap-4">
                    <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest border-r border-gray-100 pr-4">Tipe</p>
                    <div class="flex gap-2">
                        <button onclick="filterByType('Semua')" class="filter-btn active px-4 py-2 rounded-xl text-[9px] font-black uppercase tracking-widest bg-[#1e1b4b] text-white transition-all shadow-lg shadow-indigo-900/20">Semua</button>
                        <button onclick="filterByType('Jalan')" class="filter-btn px-4 py-2 rounded-xl text-[9px] font-black uppercase tracking-widest bg-gray-50 text-gray-400 hover:bg-emerald-50 hover:text-emerald-600 transition-all">Jalan</button>
                        <button onclick="filterByType('Jembatan')" class="filter-btn px-4 py-2 rounded-xl text-[9px] font-black uppercase tracking-widest bg-gray-50 text-gray-400 hover:bg-emerald-50 hover:text-emerald-600 transition-all">Jembatan</button>
                        <button onclick="filterByType('Drainase')" class="filter-btn px-4 py-2 rounded-xl text-[9px] font-black uppercase tracking-widest bg-gray-50 text-gray-400 hover:bg-emerald-50 hover:text-emerald-600 transition-all">Drainase</button>
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

        const map = L.map('main-map').setView([-3.316694, 114.590111], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        const dataPoints = @json($dataMap);
        let activeMarkers = [];

        function renderMarkers(points) {
            // Hapus marker lama
            activeMarkers.forEach(m => map.removeLayer(m));
            activeMarkers = [];

            points.forEach(point => {
                const color = point.kondisi == 'Baik' ? '#10b981' : (point.kondisi == 'Rusak Ringan' ? '#f59e0b' : '#ef4444');
                
                const markerHtml = `
                    <div style="background-color: ${color}; width: 14px; height: 14px; border-radius: 50%; border: 3px solid white; box-shadow: 0 0 10px rgba(0,0,0,0.1);"></div>
                `;

                const icon = L.divIcon({
                    html: markerHtml,
                    className: '',
                    iconSize: [14, 14],
                    iconAnchor: [7, 7]
                });

                const popupContent = `
                    <div class="p-2 text-left" style="min-width: 220px;">
                        <div class="w-full h-32 rounded-lg bg-gray-100 mb-3 overflow-hidden border border-gray-100 shadow-inner">
                            <img src="/storage/${point.foto_terbaru}" class="w-full h-full object-cover">
                        </div>
                        <div class="flex justify-between items-start mb-1">
                            <p class="text-[9px] font-black text-emerald-600 uppercase tracking-widest">${point.jenis_infrastruktur}</p>
                            <span class="text-[8px] font-bold text-gray-400 uppercase tracking-tighter">${new Date(point.updated_at).toLocaleDateString('id-ID', {day:'numeric', month:'short', year:'numeric'})}</span>
                        </div>
                        <h4 class="text-sm font-black text-[#1e1b4b] mb-2 leading-tight">${point.nama_infrastruktur}</h4>
                        <div class="flex items-center gap-2 mb-2">
                            <span class="px-2 py-0.5 rounded text-[8px] font-black uppercase tracking-tighter" style="background-color: ${color}20; color: ${color}; border: 1px solid ${color}40;">
                                ${point.kondisi}
                            </span>
                            <span class="px-2 py-0.5 rounded text-[8px] font-black uppercase tracking-tighter ${point.status_verifikasi == 'Verified' ? 'bg-blue-50 text-blue-600 border border-blue-200' : 'bg-amber-50 text-amber-600 border border-amber-200'}">
                                ${point.status_verifikasi ?? 'Pending'}
                            </span>
                        </div>
                        <div class="flex flex-col gap-1 mb-3 bg-gray-50 p-2 rounded-lg border border-gray-100 shadow-sm">
                            <div class="flex justify-between items-center">
                                <span class="text-[7px] font-black text-gray-400 uppercase tracking-widest">CNN Score</span>
                                <span class="text-[8px] font-bold text-emerald-600">${point.cnn ? (point.cnn.skor_cnn * 100).toFixed(1) + '%' : '-'}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-[7px] font-black text-gray-400 uppercase tracking-widest">Decision Tree</span>
                                <span class="text-[8px] font-bold text-blue-600">${point.analisis ? point.analisis.label_prioritas : '-'}</span>
                            </div>
                        </div>
                        <div class="pt-2 border-t border-gray-50 flex justify-between items-center">
                            <p class="text-[9px] text-gray-400 italic font-medium">Klik marker untuk detail</p>
                            <a href="/surveyor/infrastruktur/${point.id_infrastruktur}/edit" class="px-3 py-1 bg-gray-100 text-[#1e1b4b] rounded-lg text-[9px] font-black uppercase tracking-widest hover:bg-[#1e1b4b] hover:text-white transition-all shadow-sm">Edit</a>
                        </div>
                    </div>
                `;

                const marker = L.marker([point.latitude, point.longitude], {icon: icon})
                    .addTo(map)
                    .bindPopup(popupContent);
                
                activeMarkers.push(marker);
            });

            document.getElementById('total-points').textContent = points.length;

            if (points.length > 0) {
                const group = new L.featureGroup(activeMarkers);
                map.fitBounds(group.getBounds().pad(0.1));
            }
        }

        function filterByType(type) {
            // Update UI buttons
            document.querySelectorAll('.filter-btn').forEach(btn => {
                btn.classList.remove('active', 'bg-[#1e1b4b]', 'text-white', 'shadow-lg', 'shadow-indigo-900/20');
                btn.classList.add('bg-gray-50', 'text-gray-400');
            });
            event.currentTarget.classList.add('active', 'bg-[#1e1b4b]', 'text-white', 'shadow-lg', 'shadow-indigo-900/20');
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

        // Initial render
        renderMarkers(dataPoints);
    </script>

    <style>
        .leaflet-popup-content-wrapper { border-radius: 1.5rem; padding: 5px; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); }
        .leaflet-popup-tip-container { display: none; }
    </style>
</body>
</html>
