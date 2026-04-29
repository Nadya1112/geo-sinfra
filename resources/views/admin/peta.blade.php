<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peta Spasial | Admin SINFRA</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        #map { height: calc(100vh - 100px); width: 100%; border-radius: 2rem; z-index: 10; }
        .leaflet-control-zoom a { width: 26px !important; height: 26px !important; line-height: 26px !important; font-size: 14px !important; color: #475569 !important; }
        .leaflet-control-zoom { border: none !important; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1) !important; border-radius: 8px !important; overflow: hidden; margin-top: 20px !important; margin-left: 20px !important; }
        .kecamatan-item { display: flex; align-items: center; gap: 12px; padding: 10px 14px; border-radius: 12px; transition: all 0.2s ease-in-out; margin-bottom: 4px; }
        .kecamatan-item:hover { background: #f1f5f9; }
        .color-box { width: 12px; height: 12px; border-radius: 4px; flex-shrink: 0; border: 2px solid white; box-shadow: 0 0 0 1px rgba(0,0,0,0.1); }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        .custom-div-icon { background: white; border: 2px solid white; border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 10px rgba(0,0,0,0.2); font-size: 14px; line-height: 26px; }
    </style>
</head>
<body class="bg-gray-50 flex h-screen overflow-hidden text-gray-800 text-left">

    @include('admin.partials.sidebar')

    <main class="flex-1 flex flex-col h-screen overflow-hidden text-left">
        <header class="bg-white border-b border-gray-100 px-8 py-5 flex justify-between items-center z-10 text-left">
            <div class="flex items-center gap-4 text-left">
                <a href="{{ route('admin.dashboard') }}" 
                   class="w-10 h-10 bg-white border border-gray-100 rounded-xl flex items-center justify-center text-gray-400 hover:text-blue-600 hover:border-blue-100 hover:shadow-lg hover:shadow-blue-500/5 transition-all group"
                   title="Kembali ke Dashboard">
                    <i class="fas fa-arrow-left text-xs group-hover:-translate-x-1 transition-transform"></i>
                </a>
                <div class="text-left">
                    <p class="text-[10px] font-extrabold text-blue-600 uppercase tracking-[0.2em] mb-1">Administrator Portal</p>
                    <h2 class="text-xl font-black text-[#1e1b4b]">Peta Spasial Wilayah</h2>
                </div>
            </div>
            
            <div class="flex items-center gap-6">
                <div class="text-right hidden sm:block">
                    <p class="text-[11px] font-black text-[#1e1b4b]" id="mini-clock">00:00 WITA</p>
                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-tighter">{{ now()->translatedFormat('l, d F Y') }}</p>
                </div>
                
                <div class="h-8 w-[1px] bg-gray-100"></div>
                
                <div class="flex items-center gap-3 text-left">
                    <div class="text-right text-left">
                        <p class="text-[11px] font-black text-[#1e1b4b] leading-none uppercase">Admin SINFRA</p>
                        <p class="text-[9px] font-bold text-green-500 uppercase mt-1">Online</p>
                    </div>
                    <div class="w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600 border border-indigo-100">
                        <i class="fas fa-user-circle text-xl"></i>
                    </div>
                </div>
            </div>
        </header>

        <div class="flex-1 p-6 relative flex flex-col">
            <div class="relative flex-1 rounded-[2.5rem] overflow-hidden shadow-sm border border-gray-200/60">
                <div id="map" class="absolute inset-0 z-0"></div>

                <!-- Floating Stats Card -->
                <div class="absolute bottom-6 left-6 z-[1000] hidden md:block">
                    <div class="bg-white/95 backdrop-blur-sm p-5 rounded-3xl shadow-xl border border-white/80 w-[280px]">
                        <h3 class="text-[10px] font-black text-[#1e1b4b] uppercase tracking-widest mb-4 border-b border-gray-100 pb-3 flex items-center justify-between">
                            Status Infrastruktur
                            <i class="fas fa-chart-pie text-blue-500"></i>
                        </h3>
                        <div class="space-y-4">
                            @php
                                $baik = $dataInfrastruktur->where('kondisi', 'Baik')->count();
                                $rusakRingan = $dataInfrastruktur->where('kondisi', 'Rusak Ringan')->count();
                                $rusakBerat = $dataInfrastruktur->where('kondisi', 'Rusak Berat')->count();
                                $total = $baik + $rusakRingan + $rusakBerat;
                            @endphp
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-3 h-3 rounded-full bg-emerald-500 shadow-sm ring-4 ring-emerald-50"></div>
                                    <span class="text-[10px] font-extrabold text-gray-500 uppercase tracking-wide">Kondisi Baik</span>
                                </div>
                                <span class="text-xs font-black text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-md">{{ $baik }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-3 h-3 rounded-full bg-yellow-400 shadow-sm ring-4 ring-yellow-50"></div>
                                    <span class="text-[10px] font-extrabold text-gray-500 uppercase tracking-wide">Rusak Ringan</span>
                                </div>
                                <span class="text-xs font-black text-yellow-600 bg-yellow-50 px-2 py-0.5 rounded-md">{{ $rusakRingan }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-3 h-3 rounded-full bg-red-500 shadow-sm ring-4 ring-red-50"></div>
                                    <span class="text-[10px] font-extrabold text-gray-500 uppercase tracking-wide">Rusak Berat</span>
                                </div>
                                <span class="text-xs font-black text-red-600 bg-red-50 px-2 py-0.5 rounded-md">{{ $rusakBerat }}</span>
                            </div>
                            
                            <div class="pt-3 mt-3 border-t border-gray-100 flex items-center justify-between">
                                <span class="text-[10px] font-black text-[#1e1b4b] uppercase tracking-widest">Total Titik</span>
                                <span class="text-xs font-black text-white bg-blue-600 px-2 py-0.5 rounded-md shadow-sm">{{ $total }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="absolute top-6 right-6 z-[1000] w-[240px]">
                    <button onclick="toggleLegend()" class="w-full bg-white/95 backdrop-blur-sm px-5 py-3 rounded-2xl shadow-xl border border-white/80 flex justify-between items-center hover:bg-white transition group focus:outline-none">
                        <span class="text-[10px] font-black text-[#1e1b4b] uppercase tracking-widest flex items-center">
                            <i class="fas fa-layer-group mr-3 text-blue-600 group-hover:scale-110 transition-transform"></i> Layer Peta
                        </span>
                        <i id="legend-icon" class="fas fa-chevron-down text-gray-400 text-xs transition-transform duration-300"></i>
                    </button>

                    <div id="legend-content" class="hidden mt-3 bg-white/95 backdrop-blur-sm p-4 rounded-2xl shadow-xl border border-white/80 text-left">
                        <div class="mb-3 px-2 border-b border-gray-100 pb-2">
                            <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Jenis Infrastruktur</span>
                        </div>
                        <div class="mb-4 space-y-1 pr-1 custom-scrollbar max-h-[150px] overflow-y-auto">
                            @php
                                $jenisList = ['Jalan', 'Jembatan', 'Drainase', 'Titian'];
                            @endphp
                            @foreach($jenisList as $jenis)
                            <div class="kecamatan-item !py-2 !px-3">
                                <input type="checkbox" checked onchange="toggleInfraJenisLayer('{{ $jenis }}', this.checked)" class="w-3.5 h-3.5 text-blue-600 border-gray-300 rounded cursor-pointer">
                                <span class="text-[10px] font-extrabold text-[#1e1b4b] flex-1 uppercase cursor-pointer" onclick="this.previousElementSibling.click()">
                                    {{ $jenis }}
                                </span>
                            </div>
                            @endforeach
                        </div>

                        <div class="mb-3 px-2 border-b border-gray-100 pb-2">
                            <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Batas Kecamatan</span>
                        </div>
                        <div class="max-h-[30vh] overflow-y-auto pr-1 custom-scrollbar">
                            @foreach($semuaWilayah as $wilayah)
                            <div class="kecamatan-item">
                                <input type="checkbox" checked onchange="toggleLayer('{{ $wilayah->id_kecamatan }}', this.checked)" class="w-4 h-4 text-blue-600 border-gray-300 rounded cursor-pointer">
                                <span class="text-[10px] font-extrabold text-[#1e1b4b] flex-1 uppercase cursor-pointer hover:text-blue-600" onclick="zoomKeKecamatan('{{ $wilayah->id_kecamatan }}')">
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

        var map = L.map('map', { zoomControl: false, scrollWheelZoom: true }).setView([-3.316694, 114.590111], 13);
        L.control.zoom({ position: 'topleft' }).addTo(map);
        L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', { attribution: '&copy; CARTO' }).addTo(map);

        // Geometri Wilayah
        var geoLayers = {};
        @foreach($semuaWilayah as $wilayah)
            @if($wilayah->geometri)
                try {
                    var poly = L.geoJSON({!! $wilayah->geometri !!}, { style: { fillColor: "{{ $wilayah->warna ?? '#3b82f6' }}", weight: 2, opacity: 1, color: 'white', fillOpacity: 0.3 } }).addTo(map);
                    geoLayers['{{ $wilayah->id_kecamatan }}'] = poly;
                    poly.bindPopup(`<div class="text-center p-1"><h3 class="text-xs font-black text-[#1e1b4b] uppercase">Kec. {{ $wilayah->nama_kecamatan }}</h3></div>`);
                } catch (e) { console.error("Error geometri"); }
            @endif
        @endforeach

        // Data Infrastruktur
        var infraLayerGroups = {
            'Jalan': L.layerGroup().addTo(map),
            'Jembatan': L.layerGroup().addTo(map),
            'Drainase': L.layerGroup().addTo(map),
            'Titian': L.layerGroup().addTo(map),
            'Lainnya': L.layerGroup().addTo(map)
        };
        var infraData = @json($dataInfrastruktur);
        
        infraData.forEach(function(item) {
            if (item.latitude && item.longitude) {
                var lat = parseFloat(item.latitude);
                var lng = parseFloat(item.longitude);
                
                if (!isNaN(lat) && !isNaN(lng)) {
                    var color = '#10b981'; // emerald
                    var status = 'Kondisi Baik';
                    if (item.kondisi === 'Rusak Ringan') { color = '#facc15'; status = 'Rusak Ringan'; } // yellow
                    if (item.kondisi === 'Rusak Berat') { color = '#ef4444'; status = 'Rusak Berat'; } // red
                    
                    var marker = L.circleMarker([lat, lng], {
                        radius: 7,
                        fillColor: color,
                        color: '#ffffff',
                        weight: 2,
                        opacity: 1,
                        fillOpacity: 0.9
                    });
                    
                    var jenisStr = item.jenis_infrastruktur ? item.jenis_infrastruktur : 'Lainnya';
                    // capitalize first letter to match keys if needed, but DB values should match
                    var layerGroup = infraLayerGroups[jenisStr] || infraLayerGroups['Lainnya'];
                    marker.addTo(layerGroup);
                    
                    var popupContent = `
                        <div class="p-3 min-w-[200px]">
                            <div class="border-b border-gray-100 pb-2 mb-2">
                                <span class="px-2.5 py-1 rounded-lg text-[8px] font-black tracking-widest text-white mb-2 inline-block uppercase shadow-sm" style="background-color: ${color}">
                                    ${status}
                                </span>
                                <h3 class="text-sm font-black text-[#1e1b4b] uppercase leading-tight">${item.nama_infrastruktur}</h3>
                            </div>
                            <div class="space-y-1 mb-3">
                                <p class="text-[9px] font-bold text-gray-500 uppercase flex items-center gap-1"><i class="fas fa-layer-group text-blue-400 w-3"></i> ${jenisStr}</p>
                            </div>
                            <div class="mt-2 text-center bg-blue-50 py-2 rounded-xl border border-blue-100 hover:bg-blue-100 transition">
                                <a href="/admin/infrastruktur/${item.id_infrastruktur}" class="text-[10px] text-blue-600 font-black flex justify-center items-center gap-1 uppercase">Lihat Detail <i class="fas fa-arrow-right"></i></a>
                            </div>
                        </div>
                    `;
                    
                    marker.bindPopup(popupContent, {
                        className: 'custom-popup rounded-2xl overflow-hidden shadow-xl border-0'
                    });
                    
                    marker.bindTooltip(`<span class="font-extrabold text-[10px] uppercase text-[#1e1b4b]">${item.nama_infrastruktur}</span>`, {
                        direction: 'top',
                        className: 'bg-white/95 border border-gray-100 shadow-md rounded-xl px-3 py-1.5 backdrop-blur-sm'
                    });
                }
            }
        });

        // Toggle Functions
        function toggleLayer(id, isChecked) {
            if (geoLayers[id]) { if (isChecked) { map.addLayer(geoLayers[id]); } else { map.removeLayer(geoLayers[id]); } }
        }
        
        function toggleInfraJenisLayer(jenis, isChecked) {
            var group = infraLayerGroups[jenis];
            if (group) {
                if (isChecked) { map.addLayer(group); } else { map.removeLayer(group); }
            }
        }

        function zoomKeKecamatan(id) {
            if (geoLayers[id]) { var layer = geoLayers[id]; map.fitBounds(layer.getBounds(), { padding: [40, 40], maxZoom: 14 }); layer.openPopup(); }
        }
        
        function updateClock() {
            const now = new Date();
            document.getElementById('mini-clock').textContent = `${String(now.getHours()).padStart(2, '0')}:${String(now.getMinutes()).padStart(2, '0')} WITA`;
        }
        setInterval(updateClock, 1000); updateClock();
        setTimeout(function() { map.invalidateSize(); }, 500);
    </script>
</body>
</html>