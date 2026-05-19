<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Infrastruktur | GEO-SINFRA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <style> 
        body { font-family: 'Plus Jakarta Sans', sans-serif; } 
        /* Kustomisasi tombol zoom peta agar melengkung dan lebih kecil */
        .leaflet-bar { border: none !important; box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1) !important; border-radius: 8px !important; overflow: hidden; }
        .leaflet-bar a { width: 26px !important; height: 26px !important; line-height: 26px !important; font-size: 14px !important; }
        .leaflet-bar a:first-child { border-top-left-radius: 8px !important; border-top-right-radius: 8px !important; }
        .leaflet-bar a:last-child { border-bottom-left-radius: 8px !important; border-bottom-right-radius: 8px !important; border-bottom: none !important; }
    </style>
</head>
<body class="bg-gray-50 flex h-screen overflow-hidden text-gray-800 text-left">

    <?php echo $__env->make('surveyor.partials.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <main class="flex-1 flex flex-col h-screen overflow-y-auto">
        <header class="bg-white border-b border-gray-100 px-8 py-5 flex justify-between items-center z-10">
            <div class="flex items-center gap-4">
                <a href="<?php echo e(route('surveyor.history')); ?>" class="w-10 h-10 flex items-center justify-center bg-gray-50 text-gray-400 rounded-xl hover:bg-emerald-50 hover:text-emerald-600 transition-all border border-gray-100">
                    <i class="fas fa-arrow-left text-sm"></i>
                </a>
                <div>
                    <p class="text-[10px] font-extrabold text-emerald-600 uppercase tracking-[0.2em] mb-1">Laporan Detail</p>
                    <h2 class="text-xl font-black text-[#1e1b4b]">Informasi Infrastruktur</h2>
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

        <div class="p-8">
            <div class="max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <div class="lg:col-span-1 space-y-6">
                    <div class="bg-white rounded-[2.5rem] p-4 border border-gray-100 shadow-sm overflow-hidden">
                        <div class="relative h-64 rounded-[2rem] overflow-hidden bg-[#0f172a] group flex items-center justify-center">
                            <?php $cleanPath = str_replace('\\', '/', $infrastruktur->foto_terbaru); ?>
                            <img src="<?php echo e(asset('storage/' . (str_contains($cleanPath, 'infrastruktur/') ? $cleanPath : 'infrastruktur/' . $cleanPath))); ?>" class="max-w-full max-h-full object-contain transition-transform duration-500 group-hover:scale-105">
                            
                            
                            <?php 
                                $hasilAi = $infrastruktur->analisis;
                                $hasilCnn = $infrastruktur->cnn;
                            ?>

                            <?php if(strtolower($infrastruktur->kondisi) != 'baik' && strtolower($infrastruktur->kondisi) != 'menunggu ai'): ?>
                                <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                                    <div class="relative w-1/2 h-1/2 border-2 border-red-500/50 bg-red-500/5 animate-pulse">
                                        <div class="absolute -top-1 -left-1 w-3 h-3 border-t-2 border-l-2 border-red-500"></div>
                                        <div class="absolute -top-1 -right-1 w-3 h-3 border-t-2 border-r-2 border-red-500"></div>
                                        <div class="absolute -bottom-1 -left-1 w-3 h-3 border-b-2 border-l-2 border-red-500"></div>
                                        <div class="absolute -bottom-1 -right-1 w-3 h-3 border-b-2 border-r-2 border-red-500"></div>
                                        
                                        <div class="absolute -top-6 left-0 bg-red-600 text-white text-[7px] font-black px-2 py-0.5 rounded shadow-lg">
                                            DETEKSI VISUAL (<?php echo e(round(($hasilCnn->skor_cnn ?? 0) * 100)); ?>%)
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all">
                                <a href="<?php echo e(asset('storage/' . (str_contains($cleanPath, 'infrastruktur/') ? $cleanPath : 'infrastruktur/' . $cleanPath))); ?>" target="_blank" class="bg-white text-[#1e1b4b] px-4 py-2 rounded-xl text-[8px] font-black uppercase tracking-widest hover:scale-105 transition-all">Buka Foto Asli</a>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-[#1e1b4b] to-[#25215e] rounded-[2.5rem] p-8 text-white shadow-xl relative overflow-hidden">
                        <div class="absolute top-0 right-0 -mt-8 -mr-8 w-32 h-32 bg-white/5 rounded-full blur-2xl"></div>
                        <h4 class="text-[10px] font-black text-blue-300 uppercase tracking-[0.2em] mb-8 flex items-center gap-3">
                            <i class="fas fa-microchip"></i> Hybrid AI Analysis
                        </h4>
                        
                        <div class="space-y-8">
                            <div class="relative">
                                <div class="flex justify-between items-end mb-2">
                                    <div class="flex items-center gap-2">
                                        <p class="text-[9px] font-black text-blue-200/50 uppercase tracking-widest">Vision (CNN)</p>
                                        <?php if(!$hasilCnn): ?>
                                            <form action="<?php echo e(route('admin.infrastruktur.analisis-ai', $infrastruktur->id_infrastruktur)); ?>" method="POST">
                                                <?php echo csrf_field(); ?>
                                                <button type="submit" class="text-[7px] bg-blue-500/20 hover:bg-blue-500/40 text-blue-200 px-1.5 py-0.5 rounded transition-all">
                                                    <i class="fas fa-sync-alt"></i> Scan
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                    <p class="text-xl font-black text-white"><?php echo e($hasilCnn ? round($hasilCnn->skor_cnn * 100) : '0'); ?>%</p>
                                </div>
                                <div class="w-full bg-white/5 h-1.5 rounded-full overflow-hidden">
                                    <div class="bg-gradient-to-r from-blue-500 to-indigo-400 h-full" style="width: <?php echo e($hasilCnn ? ($hasilCnn->skor_cnn * 100) : '0'); ?>%"></div>
                                </div>
                                <p class="text-[8px] font-bold text-blue-300/60 mt-2 italic text-right"><?php echo e($hasilCnn->label_kondisi ?? 'Scanning visual...'); ?></p>
                            </div>
                            
                            <div class="relative">
                                <div class="flex justify-between items-end mb-2">
                                    <p class="text-[9px] font-black text-blue-200/50 uppercase tracking-widest">Logic (DT)</p>
                                    <p class="text-xl font-black text-white"><?php echo e($infrastruktur->analisis->skor_dt ?? '0'); ?><span class="text-xs text-blue-300/50 ml-0.5">/100</span></p>
                                </div>
                                <div class="w-full bg-white/5 h-1.5 rounded-full overflow-hidden">
                                    <div class="bg-gradient-to-r from-emerald-500 to-blue-400 h-full" style="width: <?php echo e($infrastruktur->analisis->skor_dt ?? '0'); ?>%"></div>
                                </div>
                                <p class="text-[8px] font-bold <?php echo e(($infrastruktur->analisis->label_prioritas ?? '') == 'Rusak Berat' ? 'text-red-400' : (($infrastruktur->analisis->label_prioritas ?? '') == 'Rusak Sedang' ? 'text-amber-400' : 'text-emerald-400')); ?> mt-2 italic text-right">
                                    <?php echo e($infrastruktur->analisis->label_prioritas ?? 'Calculating logic...'); ?>

                                </p>
                            </div>

                            <div class="pt-4 border-t border-white/10 space-y-1.5">
                                <p class="text-[9px] font-black text-blue-200/50 uppercase tracking-widest">Rekomendasi Tindakan (AI)</p>
                                <p class="text-xs font-semibold text-gray-200 leading-relaxed bg-white/5 p-4 rounded-xl border border-white/5">
                                    <?php echo e($infrastruktur->analisis->rekomendasi ?? 'Menunggu kalkulasi model pohon keputusan.'); ?>

                                </p>
                            </div>

                            <div class="pt-4 border-t border-white/5">
                                <div class="flex items-center justify-between mb-2">
                                    <p class="text-[9px] font-black text-blue-200/50 uppercase">Status Verifikasi Dinas</p>
                                    <span class="px-3 py-1 bg-white/5 border border-white/10 rounded-lg text-[8px] font-black uppercase tracking-widest <?php echo e($infrastruktur->status_verifikasi == 'Verified' ? 'text-emerald-400' : 'text-amber-400'); ?>">
                                        <?php echo e($infrastruktur->status_verifikasi ?? 'Pending'); ?>

                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white rounded-[2.5rem] p-8 border border-gray-100 shadow-sm space-y-6">
                        <div class="flex justify-between items-start border-b border-gray-50 pb-5">
                            <div>
                                <p class="text-[10px] font-black text-emerald-600 uppercase tracking-widest mb-1"><?php echo e($infrastruktur->jenis_infrastruktur); ?></p>
                                <h3 class="text-2xl font-black text-[#1e1b4b]"><?php echo e($infrastruktur->nama_objek ?? $infrastruktur->nama_infrastruktur); ?></h3>
                            </div>
                            <div class="text-right">
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter">Diinput Pada</p>
                                <p class="text-xs font-black text-[#1e1b4b]"><?php echo e($infrastruktur->created_at->translatedFormat('d F Y, H:i')); ?> WITA</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-2xl bg-gray-50 flex items-center justify-center text-[#1e1b4b] border border-gray-100">
                                        <i class="fas fa-map-marked-alt text-sm"></i>
                                    </div>
                                    <div>
                                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Kecamatan</p>
                                        <p class="text-sm font-bold text-[#1e1b4b]"><?php echo e($infrastruktur->kelurahan->kecamatan->nama_kecamatan ?? '-'); ?></p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-2xl bg-gray-50 flex items-center justify-center text-[#1e1b4b] border border-gray-100">
                                        <i class="fas fa-building text-sm"></i>
                                    </div>
                                    <div>
                                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Kelurahan</p>
                                        <p class="text-sm font-bold text-[#1e1b4b]"><?php echo e($infrastruktur->kelurahan->nama_kelurahan ?? '-'); ?></p>
                                    </div>
                                </div>
                            </div>
                            <div class="space-y-4">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-2xl bg-gray-50 flex items-center justify-center text-[#1e1b4b] border border-gray-100">
                                        <i class="fas fa-location-arrow text-sm"></i>
                                    </div>
                                    <div>
                                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Koordinat Geospasial</p>
                                        <p class="text-xs font-bold text-[#1e1b4b]"><?php echo e($infrastruktur->latitude); ?>, <?php echo e($infrastruktur->longitude); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="pt-6 border-t border-gray-100">
                            <h4 class="text-xs font-black text-[#1e1b4b] uppercase tracking-widest mb-4 italic flex items-center gap-2">
                                <i class="fas fa-ruler"></i> Spesifikasi Dimensi & Eksisting Lapangan
                            </h4>
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                                <div class="bg-gray-50/50 p-4 rounded-2xl border border-gray-100">
                                    <span class="block text-[8px] font-black text-gray-400 uppercase tracking-wider mb-1">Panjang</span>
                                    <span class="text-xs font-black text-[#1e1b4b]"><?php echo e($infrastruktur->panjang ?? '0'); ?> Meter</span>
                                </div>
                                <div class="bg-gray-50/50 p-4 rounded-2xl border border-gray-100">
                                    <span class="block text-[8px] font-black text-gray-400 uppercase tracking-wider mb-1">Lebar</span>
                                    <span class="text-xs font-black text-[#1e1b4b]"><?php echo e($infrastruktur->lebar ?? '0'); ?> Meter</span>
                                </div>
                                <div class="bg-gray-50/50 p-4 rounded-2xl border border-gray-100">
                                    <span class="block text-[8px] font-black text-gray-400 uppercase tracking-wider mb-1">Material Utama</span>
                                    <span class="text-xs font-black text-[#1e1b4b]"><?php echo e($infrastruktur->material_eksisting ?? '-'); ?></span>
                                </div>
                                <div class="bg-gray-50/50 p-4 rounded-2xl border border-gray-100">
                                    <span class="block text-[8px] font-black text-gray-400 uppercase tracking-wider mb-1">Infrastruktur Drainase</span>
                                    <span class="text-xs font-black <?php echo e($infrastruktur->has_drainase == 'ya' ? 'text-emerald-600' : 'text-red-500'); ?>">
                                        <?php echo e($infrastruktur->has_drainase == 'ya' ? 'Tersedia' : 'Tidak Ada'); ?>

                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="pt-6 border-t border-gray-100 space-y-2">
                            <h4 class="text-xs font-black text-[#1e1b4b] uppercase tracking-widest italic flex items-center gap-2">
                                <i class="fas fa-comment-alt"></i> Catatan Deskripsi Kerusakan Lapangan
                            </h4>
                            <p class="text-sm font-semibold text-gray-600 bg-gray-50 p-4 rounded-2xl border border-gray-100 leading-relaxed">
                                "<?php echo e($infrastruktur->kondisi); ?>"
                            </p>
                        </div>

                        <div class="relative rounded-[2rem] border border-gray-100 shadow-inner overflow-hidden pt-2">
                            <div id="map" class="h-[250px] w-full z-0 rounded-[2rem]"></div>
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

        const lat = <?php echo e($infrastruktur->latitude); ?>;
        const lng = <?php echo e($infrastruktur->longitude); ?>;
        const map = L.map('map', {
            zoomControl: true,
            scrollWheelZoom: false
        }).setView([lat, lng], 16);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href=\"https://www.openstreetmap.org/copyright\">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Menentukan warna Pin berdasarkan hasil AI yang akurat (Hybrid)
        const labelAi = "<?php echo e($infrastruktur->analisis->label_prioritas ?? $infrastruktur->kondisi); ?>";
        const color = labelAi === 'Baik' ? '#10b981' : (labelAi === 'Rusak Sedang' ? '#f59e0b' : (labelAi === 'Rusak Berat' ? '#ef4444' : '#6b7280'));
        
        const markerHtml = `
            <div style="background-color: ${color}; width: 18px; height: 18px; border-radius: 50%; border: 4px solid white; box-shadow: 0 0 15px rgba(0,0,0,0.2);"></div>
        `;

        const icon = L.divIcon({
            html: markerHtml,
            className: '',
            iconSize: [18, 18],
            iconAnchor: [9, 9]
        });

        L.marker([lat, lng], {icon: icon}).addTo(map);
    </script>
</body>
</html><?php /**PATH C:\laragon1\laragon\www\geo-sinfra\resources\views/surveyor/show.blade.php ENDPATH**/ ?>