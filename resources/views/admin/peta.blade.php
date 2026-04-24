<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peta Spasial | GEO-SINFRA</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        
        #map { 
            height: calc(100vh - 100px); 
            width: 100%; 
            border-radius: 2rem; 
            z-index: 10;
        }

        .leaflet-control-zoom a {
            width: 26px !important;
            height: 26px !important;
            line-height: 26px !important;
            font-size: 14px !important;
            color: #475569 !important;
        }
        .leaflet-control-zoom {
            border: none !important;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06) !important;
            border-radius: 8px !important;
            overflow: hidden;
            margin-top: 20px !important;
            margin-left: 20px !important;
        }

        .kecamatan-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 14px;
            border-radius: 12px;
            transition: all 0.2s ease-in-out;
            margin-bottom: 4px;
        }
        .kecamatan-item:hover { background: #f1f5f9; }

        .color-box {
            width: 12px;
            height: 12px;
            border-radius: 4px;
            flex-shrink: 0;
            border: 2px solid white;
            box-shadow: 0 0 0 1px rgba(0,0,0,0.1);
        }
        
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }

        .custom-div-icon {
            background: white;
            border: 2px solid white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
            font-size: 14px;
            text-align: center;
            line-height: 26px;
        }
    </style>
</head>
<body class="bg-gray-50 flex h-screen overflow-hidden text-gray-800">

    <aside class="w-64 bg-[#1e1b4b] text-white flex flex-col hidden md:flex shadow-2xl z-20">
        <div class="p-6 flex-1">
            <div class="flex items-center gap-3 mb-10">
                <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center">
                    <i class="fas fa-city text-xs"></i>
                </div>
                <span class="font-extrabold text-xl tracking-tighter">GEO-SINFRA</span>
            </div>
            
            <nav class="space-y-1">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 text-gray-400 hover:text-white hover:bg-white/5 rounded-xl text-sm font-semibold transition">
                    <i class="fas fa-th-large"></i> Dashboard
                </a>
                <a href="#" class="flex items-center gap-3 px-4 py-3 bg-blue-600 rounded-xl text-sm font-bold transition shadow-lg shadow-blue-900/20">
                    <i class="fas fa-map-marked-alt"></i> Peta Spasial
                </a>
                <a href="#" class="flex items-center gap-3 px-4 py-3 text-gray-400 hover:text-white hover:bg-white/5 rounded-xl text-sm font-semibold transition group">
                    <i class="fas fa-database group-hover:text-blue-400"></i> Data Infrastruktur
                </a>
                <a href="#" class="flex items-center gap-3 px-4 py-3 text-gray-400 hover:text-white hover:bg-white/5 rounded-xl text-sm font-semibold transition group">
                    <i class="fas fa-brain group-hover:text-blue-400"></i> Analisis Hybrid AI
                </a>
            </nav>
        </div>

        <div class="p-6 border-t border-white/5">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="flex items-center gap-3 px-4 py-3 text-red-400 hover:text-red-300 w-full text-left text-sm font-bold transition group">
                    <i class="fas fa-sign-out-alt group-hover:-translate-x-1 transition-transform"></i> Keluar Sistem
                </button>
            </form>
        </div>
    </aside>

    <main class="flex-1 flex flex-col h-screen overflow-hidden">
        <header class="bg-white border-b border-gray-100 px-8 py-5 flex justify-between items-center z-10">
            <div>
                <h2 class="text-xl font-black text-[#1e1b4b]">Peta Spasial Wilayah</h2>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1">Kota Banjarmasin</p>
            </div>
        </header>

        <div class="flex-1 p-6 relative flex flex-col">
            <div class="relative flex-1 rounded-[2.5rem] overflow-hidden shadow-sm border border-gray-200/60">
                <div id="map" class="absolute inset-0 z-0"></div>

                <div class="absolute top-6 right-6 z-[1000] w-[240px]">
                    <button onclick="toggleLegend()" class="w-full bg-white/95 backdrop-blur-sm px-5 py-3 rounded-2xl shadow-xl border border-white/80 flex justify-between items-center hover:bg-white transition group focus:outline-none">
                        <span class="text-[10px] font-black text-[#1e1b4b] uppercase tracking-widest flex items-center">
                            <i class="fas fa-list-ul mr-3 text-blue-600 group-hover:scale-110 transition-transform"></i> Wilayah
                        </span>
                        <i id="legend-icon" class="fas fa-chevron-down text-gray-400 text-xs transition-transform duration-300"></i>
                    </button>

                    <div id="legend-content" class="hidden mt-3 bg-white/95 backdrop-blur-sm p-4 rounded-2xl shadow-xl border border-white/80 transition-all text-left">
                        <div class="mb-3 px-2 border-b border-gray-100 pb-2">
                            <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Kecamatan</span>
                        </div>

                        <div class="max-h-[40vh] overflow-y-auto pr-1 custom-scrollbar">
                            @foreach($semuaWilayah as $wilayah)
                            <div class="kecamatan-item">
                                <input type="checkbox" checked 
                                    onchange="toggleLayer('{{ $wilayah->id_kecamatan }}', this.checked)"
                                    class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 cursor-pointer">

                                <span class="text-[10px] font-extrabold text-[#1e1b4b] flex-1 uppercase tracking-tight leading-none cursor-pointer hover:text-blue-600" 
                                      onclick="zoomKeKecamatan('{{ $wilayah->id_kecamatan }}')">
                                    {{ $wilayah->nama_kecamatan }}
                                </span>
                                
                                <div class="color-box" style="background-color: {{ $wilayah->warna }}"></div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        function toggleLegend() {
            const content = document.getElementById('legend-content');
            const icon = document.getElementById('legend-icon');
            content.classList.toggle('hidden');
            icon.classList.toggle('rotate-180');
        }

        // Peta Berwarna & Scroll Zoom Aktif
        var map = L.map('map', { zoomControl: false, scrollWheelZoom: true }).setView([-3.316694, 114.590111], 13);
        L.control.zoom({ position: 'topleft' }).addTo(map);
        L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', { attribution: '&copy; CARTO' }).addTo(map);

        var geoLayers = {};

        @foreach($semuaWilayah as $wilayah)
            @if($wilayah->geometri)
                try {
                    var poly = L.geoJSON({!! $wilayah->geometri !!}, {
                        style: { fillColor: "{{ $wilayah->warna ?? '#3b82f6' }}", weight: 2, opacity: 1, color: 'white', fillOpacity: 0.5 }
                    }).addTo(map);
                    geoLayers['{{ $wilayah->id_kecamatan }}'] = poly;
                    poly.on('mouseover', function() { this.setStyle({ fillOpacity: 0.7, weight: 3, color: '#4f46e5' }); });
                    poly.on('mouseout', function() { this.setStyle({ fillOpacity: 0.5, weight: 2, color: 'white' }); });
                    poly.bindPopup(`<div class="text-center p-1"><h3 class="text-xs font-black text-[#1e1b4b] uppercase">Kec. {{ $wilayah->nama_kecamatan }}</h3></div>`);
                } catch (e) { console.error("Error geometri"); }
            @endif
        @endforeach

        function toggleLayer(id, isChecked) {
            if (geoLayers[id]) {
                if (isChecked) { map.addLayer(geoLayers[id]); } 
                else { map.removeLayer(geoLayers[id]); }
            }
        }

        var icons = {
            'jalan': L.divIcon({ html: '<i class="fas fa-road text-blue-600"></i>', className: 'custom-div-icon', iconSize: [30, 30] }),
            'jembatan': L.divIcon({ html: '<i class="fas fa-archway text-orange-600"></i>', className: 'custom-div-icon', iconSize: [30, 30] }),
            'drainase': L.divIcon({ html: '<i class="fas fa-water text-cyan-600"></i>', className: 'custom-div-icon', iconSize: [30, 30] }),
            'pju': L.divIcon({ html: '<i class="fas fa-lightbulb text-yellow-500"></i>', className: 'custom-div-icon', iconSize: [30, 30] })
        };

        @if(isset($dataInfrastruktur))
            @foreach($dataInfrastruktur as $infra)
                @if($infra->latitude && $infra->longitude)
                    var iconPilihan = icons['{{ $infra->jenis }}'] || icons['jalan'];
                    L.marker([{{ $infra->latitude }}, {{ $infra->longitude }}], {icon: iconPilihan}).addTo(map).bindPopup(`
                        <div class="p-2 w-48 text-center">
                            <img src="{{ asset('storage/' . $infra->foto_terbaru) }}" class="rounded-lg mb-2 w-full h-24 object-cover border" onerror="this.src='https://via.placeholder.com/150?text=No+Image'">
                            <p class="text-[9px] font-bold text-blue-600 uppercase">{{ $infra->jenis }}</p>
                            <h4 class="text-xs font-black text-slate-800 uppercase mb-1">{{ $infra->nama_objek }}</h4>
                            <p class="text-[9px] text-slate-500 italic">{{ $infra->alamat }}</p>
                        </div>
                    `);
                @endif
            @endforeach
        @endif

        function zoomKeKecamatan(id) {
            if (geoLayers[id]) {
                var layer = geoLayers[id];
                map.fitBounds(layer.getBounds(), { padding: [40, 40], maxZoom: 14 });
                layer.openPopup();
            }
        }
        setTimeout(function() { map.invalidateSize(); }, 500);
    </script>
</body>
</html>