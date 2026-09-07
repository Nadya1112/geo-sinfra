import re

#########################################
# 1. LANDING PAGE
#########################################
with open('resources/views/landing.blade.php', 'r', encoding='utf-8') as f:
    landing_text = f.read()

# Replace Kelurahan coloring logic
old_kelurahan_coloring = """                            if (showBanjir) {
                                const riskLevel = kel.id_kelurahan % 3; 
                                if (riskLevel === 0) { // Tinggi
                                    polygonColor = '#ef4444'; fillColor = '#ef4444'; fillOpacity = 0.5;
                                } else if (riskLevel === 1) { // Sedang
                                    polygonColor = '#f59e0b'; fillColor = '#f59e0b'; fillOpacity = 0.4;
                                } else { // Aman
                                    polygonColor = '#3b82f6'; fillColor = '#3b82f6'; fillOpacity = 0.2;
                                }
                                weight = 1.0;
                            }"""

landing_text = landing_text.replace(old_kelurahan_coloring, "")

# Replace dashArray and Tooltip
old_dash = """                                    dashArray: (activeKelurahanId == kel.id_kelurahan || showBanjir) ? '0' : '5, 5'
                                }
                            }).bindTooltip(`<div class="text-xs font-bold text-navy-900 leading-none">Kel. ${kel.nama_kelurahan}${showBanjir ? ' (Simulasi Banjir)' : ''}</div>`, { sticky: true }).addTo(polygonsLayer);"""

new_dash = """                                    dashArray: (activeKelurahanId == kel.id_kelurahan) ? '0' : '5, 5'
                                }
                            }).bindTooltip(`<div class="text-xs font-bold text-navy-900 leading-none">Kel. ${kel.nama_kelurahan}</div>`, { sticky: true }).addTo(polygonsLayer);"""

landing_text = landing_text.replace(old_dash, new_dash)

# Add flood points
old_markers_cluster = """            // 2. Draw Aset Markers (Semua marker akan di-cluster otomatis)"""

new_markers_cluster = """            // 1.9 Draw Titik Rawan Banjir (Mock Data)
            if (showBanjir) {
                L.circle([-3.315, 114.590], { color: '#ef4444', fillColor: '#ef4444', fillOpacity: 0.2, weight: 1, radius: 800 }).bindPopup('<div class="text-center"><p class="text-xs font-black text-red-500 uppercase">Zona Merah</p><p class="text-xs">Rawan Banjir Tinggi</p></div>').addTo(polygonsLayer);
                L.circle([-3.325, 114.598], { color: '#f59e0b', fillColor: '#f59e0b', fillOpacity: 0.2, weight: 1, radius: 1200 }).bindPopup('<div class="text-center"><p class="text-xs font-black text-orange-500 uppercase">Zona Kuning</p><p class="text-xs">Rawan Banjir Sedang</p></div>').addTo(polygonsLayer);
                L.circle([-3.295, 114.580], { color: '#ef4444', fillColor: '#ef4444', fillOpacity: 0.2, weight: 1, radius: 900 }).bindPopup('<div class="text-center"><p class="text-xs font-black text-red-500 uppercase">Zona Merah</p><p class="text-xs">Rawan Banjir Tinggi</p></div>').addTo(polygonsLayer);
                L.circle([-3.330, 114.570], { color: '#f59e0b', fillColor: '#f59e0b', fillOpacity: 0.2, weight: 1, radius: 1000 }).bindPopup('<div class="text-center"><p class="text-xs font-black text-orange-500 uppercase">Zona Kuning</p><p class="text-xs">Rawan Banjir Sedang</p></div>').addTo(polygonsLayer);
            }

            // 2. Draw Aset Markers (Semua marker akan di-cluster otomatis)"""

landing_text = landing_text.replace(old_markers_cluster, new_markers_cluster)

with open('resources/views/landing.blade.php', 'w', encoding='utf-8') as f:
    f.write(landing_text)


#########################################
# 2. SURVEYOR MAP
#########################################
with open('resources/views/surveyor/map.blade.php', 'r', encoding='utf-8') as f:
    surv_text = f.read()

# Replace toggle button with basemap button
old_toggle_btn = """                        <div class="h-[1px] bg-white/10 my-0.5 mx-1"></div>
                        <button onclick="toggleFloodLayer()" class="flex items-center justify-between px-3 py-2 rounded-xl hover:bg-white/10 transition-all group w-full text-left">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-water text-blue-400 text-[10px]"></i>
                                <span class="text-[10px] font-black uppercase tracking-wider text-slate-300 group-hover:text-white transition-colors">Banjir</span>
                            </div>
                            <div class="w-5 h-2.5 rounded-full bg-slate-700 relative border border-white/10 transition-colors" id="flood-toggle-bg">
                                <div id="flood-toggle-dot" class="absolute left-[2px] top-[1px] w-1.5 h-1.5 bg-slate-400 rounded-full transition-all"></div>
                            </div>
                        </button>"""

new_basemap_btn = """                        <button onclick="changeBaseLayer('banjir')" class="flex items-center gap-2 px-3 py-2 rounded-xl hover:bg-white/10 transition-all group">
                            <div class="w-6 h-6 rounded-md bg-cyan-500/20 flex items-center justify-center text-cyan-400 group-hover:bg-cyan-500 group-hover:text-white transition-all"><i class="fas fa-water text-[10px]"></i></div>
                            <span class="text-[10px] font-black uppercase tracking-wider text-gray-300 group-hover:text-white">Banjir</span>
                        </button>"""

surv_text = surv_text.replace(old_toggle_btn, new_basemap_btn)

# Remove toggleFloodLayer function entirely (not needed)
old_toggle_func = """        function toggleFloodLayer() {
            showFloodLayer = !showFloodLayer;
            const bg = document.getElementById('flood-toggle-bg');
            const dot = document.getElementById('flood-toggle-dot');
            
            if(showFloodLayer) {
                map.addLayer(floodLayer);
                bg.classList.replace('bg-slate-700', 'bg-blue-500');
                dot.classList.replace('bg-slate-400', 'bg-white ');
                dot.classList.replace('left-[2px]', 'left-[14px]');
            } else {
                map.removeLayer(floodLayer);
                bg.classList.replace('bg-blue-500', 'bg-slate-700');
                dot.classList.replace('bg-white ', 'bg-slate-400');
                dot.classList.replace('left-[14px]', 'left-[2px]');
            }
        }"""
surv_text = surv_text.replace(old_toggle_func, "")

# Modify changeBaseLayer
old_change_base = """        function changeBaseLayer(type) {
            if (activeBasemap) {
                map.removeLayer(activeBasemap);
            }
            if (type === 'satellite') {
                activeBasemap = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', { maxZoom: 19 }).addTo(map);
            } else if (type === 'osm') {
                activeBasemap = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19 }).addTo(map);
            } else if (type === 'dark') {
                activeBasemap = L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', { maxZoom: 19 }).addTo(map);
            } else if (type === 'greyscale') {
                activeBasemap = L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', { maxZoom: 19 }).addTo(map);
            } else {
                activeBasemap = L.tileLayer('https://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}', { maxZoom: 20, subdomains:['mt0','mt1','mt2','mt3'] }).addTo(map);
            }
            
            document.getElementById('layer-options-desktop').classList.add('hidden');
        }"""

new_change_base = """        function changeBaseLayer(type) {
            if (activeBasemap) {
                map.removeLayer(activeBasemap);
            }
            if (map.hasLayer(floodLayer)) {
                map.removeLayer(floodLayer);
            }
            if (type === 'satellite') {
                activeBasemap = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', { maxZoom: 19 }).addTo(map);
            } else if (type === 'osm') {
                activeBasemap = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19 }).addTo(map);
            } else if (type === 'dark') {
                activeBasemap = L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', { maxZoom: 19 }).addTo(map);
            } else if (type === 'greyscale') {
                activeBasemap = L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', { maxZoom: 19 }).addTo(map);
            } else if (type === 'banjir') {
                activeBasemap = L.tileLayer('https://{s}.tile.openstreetmap.fr/hot/{z}/{x}/{y}.png', { maxZoom: 19 }).addTo(map);
                map.addLayer(floodLayer);
            } else {
                activeBasemap = L.tileLayer('https://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}', { maxZoom: 20, subdomains:['mt0','mt1','mt2','mt3'] }).addTo(map);
            }
            
            document.getElementById('layer-options-desktop').classList.add('hidden');
        }"""

surv_text = surv_text.replace(old_change_base, new_change_base)

with open('resources/views/surveyor/map.blade.php', 'w', encoding='utf-8') as f:
    f.write(surv_text)

print("Done Surveyor")


#########################################
# 3. TIM TEKNIS MAP
#########################################
with open('resources/views/tim_teknis/monitoring.blade.php', 'r', encoding='utf-8') as f:
    tim_text = f.read()

# Same replacements for tim teknis
tim_text = tim_text.replace(old_toggle_btn, new_basemap_btn)
tim_text = tim_text.replace(old_toggle_func, "")
tim_text = tim_text.replace(old_change_base, new_change_base)

with open('resources/views/tim_teknis/monitoring.blade.php', 'w', encoding='utf-8') as f:
    f.write(tim_text)

print("Done Tim Teknis")
