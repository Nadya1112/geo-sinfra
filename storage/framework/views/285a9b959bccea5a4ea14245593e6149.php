<!DOCTYPE html>
<html lang="id" class="scroll-smooth"> 
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GEO-SINFRA | Kota Banjarmasin</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <style>
        body { font-family: 'Inter', sans-serif; }
        .bg-government-gradient {
            background: linear-gradient(135deg, #1e3a8a 0%, #1e1b4b 100%);
        }
        
        #map { height: 550px; border-radius: 1rem; z-index: 10; }

        .leaflet-control-zoom a {
            width: 26px !important;
            height: 26px !important;
            line-height: 26px !important;
            font-size: 14px !important;
        }
        .leaflet-control-zoom {
            border: none !important;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1) !important;
            margin-top: 20px !important;
            margin-left: 20px !important;
        }

        /* Style Item Wilayah Baru */
        .kecamatan-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 12px;
            border-radius: 10px;
            transition: all 0.2s;
        }
        .kecamatan-item:hover { background: #f8fafc; }
        .color-box { width: 10px; height: 10px; border-radius: 3px; flex-shrink: 0; }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 antialiased">

    <div class="bg-[#1e1b4b] text-white text-[10px] md:text-xs py-2 px-4 text-center font-medium tracking-wide">
        Pemerintah Kota Banjarmasin | Dinas Perumahan Rakyat dan Kawasan Permukiman
    </div>

    <nav class="bg-white sticky top-0 z-50 shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.553-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-1.553-.894L15 9"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-lg font-extrabold text-[#1e1b4b] leading-tight">GEO-SINFRA</h1>
                        <p class="text-[8px] text-gray-500 uppercase tracking-widest font-bold">Sistem Pemetaan Infrastruktur</p>
                    </div>
                </div>
                
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#" class="text-gray-600 hover:text-blue-700 font-semibold transition text-sm">Data Statistik</a>
                    <a href="#peta" class="text-gray-600 hover:text-blue-700 font-semibold transition text-sm">Peta Sebaran</a>
                    <a href="<?php echo e(url('/login')); ?>" class="inline-flex items-center px-8 py-2.5 bg-[#5c56e1] text-white text-sm font-bold rounded-xl hover:bg-blue-800 shadow-lg shadow-blue-200 transition-all active:scale-95 uppercase tracking-wider">LOGIN</a>
                </div>
            </div>
        </div>
    </nav>

    <section class="bg-government-gradient relative overflow-hidden py-24 md:py-32 flex items-center justify-center text-center">
        <div class="max-w-5xl mx-auto px-4 relative z-10">
            <h2 class="text-white text-5xl md:text-6xl font-extrabold tracking-tight mb-4">
                Selamat Datang di <br>
                <span class="text-blue-400 uppercase">GEO-SINFRA</span>
            </h2>
            <p class="text-xl md:text-2xl font-light text-blue-100 mb-12">
                Sistem Analisis Infrastruktur Permukiman Berbasis AI
            </p>
            
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="#peta" class="px-8 py-4 bg-white text-[#5c56e1] font-bold rounded-xl shadow-xl hover:scale-105 transition duration-300">
                    Jelajahi Peta Publik
                </a>
                <a href="#" class="px-8 py-4 bg-transparent border-2 border-white text-white font-bold rounded-xl hover:bg-white/10 transition duration-300">
                    Pelajari Lebih Lanjut
                </a>
            </div>
        </div>
    </section>

    <main id="peta" class="max-w-7xl mx-auto px-4 py-20 text-center">
        <h3 class="text-3xl font-extrabold text-[#1e1b4b] mb-2">Peta Sebaran Infrastruktur</h3>
        <p class="text-gray-500 mb-10">Visualisasi data spasial permukiman Kota Banjarmasin secara real-time.</p>
        
        <div class="relative">
            <div id="map" class="shadow-2xl border-[10px] border-white ring-1 ring-gray-200"></div>

            <div class="absolute top-8 right-8 z-[1000] w-[220px]">
                <button onclick="toggleLegend()" class="w-full bg-white/95 backdrop-blur-sm px-4 py-2.5 rounded-xl shadow-lg border border-gray-100 flex justify-between items-center hover:bg-white transition focus:outline-none">
                    <span class="text-[10px] font-black text-[#1e1b4b] uppercase tracking-wider">Daftar Wilayah</span>
                    <i id="legend-icon" class="fas fa-chevron-down text-gray-400 text-xs transition-transform duration-300"></i>
                </button>

                <div id="legend-content" class="hidden mt-2 bg-white/95 backdrop-blur-sm p-3 rounded-xl shadow-xl border border-gray-100 text-left">
                    <div class="mb-3 px-2 border-b border-gray-100 pb-2">
                        <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Kecamatan</span>
                    </div>
                    
                    <div class="max-h-[30vh] overflow-y-auto pr-1 custom-scrollbar">
                        <?php $__currentLoopData = $semuaWilayah; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $wilayah): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="kecamatan-item">
                            <input type="checkbox" checked 
                                onchange="toggleLayer('<?php echo e($wilayah->id_kecamatan); ?>', this.checked)"
                                class="w-3.5 h-3.5 text-blue-600 border-gray-300 rounded cursor-pointer">
                            
                            <span class="text-[10px] font-bold text-[#1e1b4b] flex-1 cursor-pointer hover:text-blue-600 transition" 
                                onclick="zoomKeKecamatan('<?php echo e($wilayah->id_kecamatan); ?>')">
                                <?php echo e($wilayah->nama_kecamatan); ?>

                            </span>
                            
                            <div class="color-box" style="background-color: <?php echo e($wilayah->warna); ?>"></div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer class="bg-white border-t border-gray-200 py-10 text-center">
        <p class="text-gray-500 text-sm italic">&copy; 2026 GEO-SINFRA - Pemerintah Kota Banjarmasin.</p>
    </footer>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        function toggleLegend() {
            const content = document.getElementById('legend-content');
            const icon = document.getElementById('legend-icon');
            content.classList.toggle('hidden');
            icon.classList.toggle('rotate-180');
        }

        var map = L.map('map', { zoomControl: false, scrollWheelZoom: true }).setView([-3.316694, 114.590111], 12);
        L.control.zoom({ position: 'topleft' }).addTo(map);
        
        L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; OpenStreetMap contributors &copy; CARTO'
        }).addTo(map);

        var geoLayers = {};

        <?php $__currentLoopData = $semuaWilayah; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $wilayah): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if($wilayah->geometri): ?>
                try {
                    var poly = L.geoJSON(<?php echo $wilayah->geometri; ?>, {
                        style: {
                            fillColor: "<?php echo e($wilayah->warna ?? '#3b82f6'); ?>",
                            weight: 1.5, opacity: 1, color: 'white', fillOpacity: 0.2
                        }
                    }).addTo(map);

                    geoLayers['<?php echo e($wilayah->id_kecamatan); ?>'] = poly;

                    poly.bindPopup("<div class='text-center p-1 font-bold text-[10px] uppercase'>Kec. <?php echo e($wilayah->nama_kecamatan); ?></div>");
                    
                    poly.on('mouseover', function() { this.setStyle({ fillOpacity: 0.4 }); });
                    poly.on('mouseout', function() { this.setStyle({ fillOpacity: 0.2 }); });
                } catch (e) { console.error("Error Geometri"); }
            <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        <?php $__currentLoopData = $dataInfrastruktur; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $infra): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if($infra->latitude && $infra->longitude): ?>
                L.circleMarker([<?php echo e($infra->latitude); ?>, <?php echo e($infra->longitude); ?>], {
                    radius: 6, fillColor: "#3b82f6", color: "#ffffff", weight: 2, opacity: 1, fillOpacity: 0.9
                }).addTo(map).bindPopup(`
                    <div class="p-2 w-40 text-center">
                        <img src="<?php echo e(asset('storage/' . $infra->foto_terbaru)); ?>" class="rounded-lg mb-2 w-full h-20 object-cover border" onerror="this.src='https://via.placeholder.com/150?text=No+Image'">
                        <h4 class="text-[10px] font-black text-slate-800 uppercase leading-tight"><?php echo e($infra->nama_objek); ?></h4>
                        <p class="text-[9px] text-blue-600 font-bold uppercase mt-1"><?php echo e($infra->jenis); ?></p>
                    </div>
                `);
            <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        // Fungsi Baru: Sembunyikan/Munculkan Layer
        function toggleLayer(id, isChecked) {
            if (geoLayers[id]) {
                if (isChecked) {
                    map.addLayer(geoLayers[id]);
                } else {
                    map.removeLayer(geoLayers[id]);
                }
            }
        }

        function zoomKeKecamatan(id) {
            if (geoLayers[id]) {
                var layer = geoLayers[id];
                map.fitBounds(layer.getBounds(), { padding: [30, 30], maxZoom: 14 });
                layer.openPopup();
            }
        }

        setTimeout(function() { map.invalidateSize(); }, 500);
    </script>
</body>
</html><?php /**PATH C:\laragon1\laragon\www\geo-sinfra\resources\views/dashboard.blade.php ENDPATH**/ ?>