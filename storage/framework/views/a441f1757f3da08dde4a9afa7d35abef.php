<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Infrastruktur | Admin SINFRA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <style> body { font-family: 'Plus Jakarta Sans', sans-serif; } </style>
</head>
<body class="bg-gray-50 flex h-screen overflow-hidden text-gray-800 text-left">

    <?php echo $__env->make('admin.partials.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <main class="flex-1 flex flex-col h-screen overflow-y-auto">
        <header class="bg-white border-b border-gray-100 px-8 py-5 flex justify-between items-center z-10">
            <div class="flex items-center gap-4">
                <a href="<?php echo e(route('admin.infrastruktur')); ?>" class="w-10 h-10 bg-white border border-gray-100 rounded-xl flex items-center justify-center text-gray-400 hover:text-blue-600 transition-all">
                    <i class="fas fa-arrow-left text-xs"></i>
                </a>
                <div>
                    <p class="text-[10px] font-extrabold text-blue-600 tracking-[0.2em] mb-1">Administrator Portal</p>
                    <h2 class="text-xl font-black text-[#1e1b4b]">Detail Data Infrastruktur</h2>
                </div>
            </div>
            <div class="flex items-center gap-6">
                <div class="text-right hidden sm:block">
                    <p class="text-[11px] font-black text-[#1e1b4b]" id="mini-clock">00:00 WITA</p>
                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-tighter"><?php echo e(now()->translatedFormat('l, d F Y')); ?></p>
                </div>
                
                <div class="flex items-center gap-3">
                    <?php if(($inf->status_verifikasi ?? 'Pending') != 'Verified'): ?>
                        <form action="<?php echo e(route('admin.infrastruktur.verifikasi', $inf->id_infrastruktur)); ?>" method="POST" class="inline">
                            <?php echo csrf_field(); ?>
                            <button type="submit" onclick="return confirm('Verifikasi aset ini?')" class="bg-emerald-500 text-white px-4 py-2 rounded-xl text-[10px] font-black hover:bg-emerald-600 transition shadow-sm flex items-center gap-1">
                                <i class="fas fa-check"></i> Verifikasi
                            </button>
                        </form>
                    <?php else: ?>
                        <span class="bg-gray-100 text-gray-500 px-4 py-2 rounded-xl text-[10px] font-black flex items-center gap-1 border border-gray-200">
                            <i class="fas fa-check-double"></i> Verified
                        </span>
                    <?php endif; ?>

                    <div class="h-8 w-[1px] bg-gray-100"></div>
                    <div class="text-right">
                        <p class="text-[11px] font-black text-[#1e1b4b] leading-none uppercase">Admin SINFRA</p>
                        <p class="text-[9px] font-bold text-green-500 uppercase mt-1">Online</p>
                    </div>
                    <div class="w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600 border border-indigo-100">
                        <i class="fas fa-user-circle text-xl"></i>
                    </div>
                </div>
            </div>
        </header>

        <div class="p-8 pb-20">
            <?php if(session('success')): ?>
            <div class="max-w-4xl mx-auto mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-6 py-4 rounded-2xl flex items-center gap-3 shadow-sm">
                <i class="fas fa-check-circle text-xl"></i>
                <div>
                    <h4 class="font-bold text-sm">Berhasil!</h4>
                    <p class="text-xs font-medium"><?php echo e(session('success')); ?></p>
                </div>
            </div>
            <?php endif; ?>

            <div class="max-w-4xl bg-white rounded-[2.5rem] p-10 border border-gray-100 shadow-sm mx-auto">
                <div class="mb-10 border-b border-gray-50 pb-5 flex justify-between items-end">
                    <div>
                        <h3 class="text-lg font-black text-[#1e1b4b] tracking-tight">Identitas Objek</h3>
                        <p class="text-xs text-gray-400 font-medium tracking-tighter">Detail Informasi Aset SINFRA</p>
                    </div>
                    <div class="flex gap-3">
                        <span class="px-4 py-2 rounded-xl text-[10px] font-black tracking-widest border <?php echo e(strtolower($inf->kondisi) == 'baik' ? 'bg-green-50 text-green-600 border-green-200' : (str_contains(strtolower($inf->kondisi), 'ringan') ? 'bg-yellow-50 text-yellow-600 border-yellow-200' : 'bg-red-50 text-red-600 border-red-200')); ?>">
                            <?php echo e(strtoupper($inf->kondisi)); ?>

                        </span>
                    </div>
                </div>

                <div class="space-y-8">
                    
                    <div class="space-y-6">
                        <div class="border-l-4 border-blue-500 pl-4 mb-4">
                            <h4 class="text-sm font-black text-[#1e1b4b] uppercase tracking-wider">1. Identitas & Lokasi</h4>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div>
                                <label class="block text-[10px] font-black text-[#1e1b4b] tracking-widest mb-2">Nama Infrastruktur</label>
                                <div class="w-full px-5 py-3 bg-gray-50 border border-gray-100 rounded-2xl text-sm font-semibold text-gray-600">
                                    <?php echo e($inf->nama_objek ?? $inf->nama_infrastruktur); ?>

                                </div>
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-[#1e1b4b] uppercase mb-2">Jenis Infrastruktur</label>
                                <div class="w-full px-5 py-3 bg-gray-50 border border-gray-100 rounded-2xl text-sm font-semibold text-gray-600 uppercase">
                                    <?php echo e($inf->jenis ?? $inf->jenis_infrastruktur); ?>

                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div>
                                <label class="block text-[10px] font-black text-[#1e1b4b] uppercase mb-2">Kecamatan</label>
                                <div class="w-full px-5 py-3 bg-gray-50 border border-gray-100 rounded-2xl text-sm font-semibold text-gray-600">
                                    <?php echo e($inf->nama_kecamatan ?? '-'); ?>

                                </div>
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-[#1e1b4b] uppercase mb-2">Kelurahan</label>
                                <div class="w-full px-5 py-3 bg-gray-50 border border-gray-100 rounded-2xl text-sm font-semibold text-gray-600">
                                    <?php echo e($inf->nama_kelurahan ?? '-'); ?>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-6 pt-6 border-t border-gray-100">
                        <div class="border-l-4 border-indigo-500 pl-4 mb-4">
                            <h4 class="text-sm font-black text-[#1e1b4b] uppercase tracking-wider">2. SIG (Sistem Informasi Geografis)</h4>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div>
                                <label class="block text-[10px] font-black text-[#1e1b4b] uppercase mb-2">Latitude</label>
                                <div class="w-full px-5 py-3 bg-gray-50 border border-gray-100 rounded-2xl text-sm font-semibold text-gray-600">
                                    <?php echo e($inf->latitude); ?>

                                </div>
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-[#1e1b4b] uppercase mb-2">Longitude</label>
                                <div class="w-full px-5 py-3 bg-gray-50 border border-gray-100 rounded-2xl text-sm font-semibold text-gray-600">
                                    <?php echo e($inf->longitude); ?>

                                </div>
                            </div>
                        </div>

                        <div class="mt-6">
                            <label class="block text-[10px] font-black text-[#1e1b4b] uppercase mb-3">Visualisasi Lokasi (Mini Map)</label>
                            <div id="mini-map" class="w-full h-48 rounded-3xl border border-gray-100 shadow-inner z-0"></div>
                        </div>
                    </div>

                    <div class="space-y-6 pt-6 border-t border-gray-100">
                        <div class="border-l-4 border-purple-500 pl-4 mb-4">
                            <h4 class="text-sm font-black text-[#1e1b4b] uppercase tracking-wider">3. Analisis Cerdas (AI)</h4>
                        </div>

                        <?php
                            // Ambil data hasil DT
                            $hasilAi = \Illuminate\Support\Facades\DB::table('analisis_ai')
                                        ->where('id_infrastruktur', $inf->id_infrastruktur)
                                        ->first();
                            
                            // Ambil data hasil CNN
                            $hasilCnn = \Illuminate\Support\Facades\DB::table('citra_cnn')
                                        ->where('id_infrastruktur', $inf->id_infrastruktur)
                                        ->first();
                        ?>

                        <?php if($hasilAi || $hasilCnn): ?>
                        <div class="bg-gradient-to-br from-[#1e1b4b] to-[#312e81] rounded-[2.5rem] p-8 text-white shadow-2xl relative overflow-hidden">
                            <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-white/5 rounded-full blur-3xl"></div>
                            
                            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-8">
                                <div class="flex items-center gap-4">
                                    <div class="w-14 h-14 bg-white/10 rounded-2xl flex items-center justify-center backdrop-blur-xl border border-white/20">
                                        <i class="fas fa-brain text-yellow-400 text-2xl"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-black tracking-widest text-lg uppercase text-white">Hybrid AI Analytics</h4>
                                        <p class="text-xs text-indigo-200 font-medium opacity-80">Decision Tree + CNN Vision Integration</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3">
                                    <span class="px-4 py-2 bg-emerald-500/20 text-emerald-400 rounded-xl border border-emerald-500/30 text-[10px] font-black uppercase tracking-widest">
                                        <i class="fas fa-shield-alt mr-2"></i>Sistem Terverifikasi
                                    </span>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                                <!-- Panel CNN -->
                                <div class="bg-white/5 border border-white/10 rounded-3xl p-6 backdrop-blur-sm">
                                    <div class="flex justify-between items-center mb-4">
                                        <p class="text-[10px] font-black text-indigo-300 uppercase tracking-widest">Visual Analysis (CNN)</p>
                                        <i class="fas fa-eye text-indigo-300/50"></i>
                                    </div>
                                    <div class="flex items-end gap-3 mb-2">
                                        <p class="text-4xl font-black text-white"><?php echo e($hasilCnn ? round($hasilCnn->skor_cnn * 100) : '0'); ?><span class="text-sm font-bold text-indigo-300 ml-1">%</span></p>
                                        <p class="text-xs font-bold text-emerald-400 mb-2"><?php echo e($hasilCnn->label_kondisi ?? 'Scanning...'); ?></p>
                                        <?php if(!$hasilCnn): ?>
                                            <form action="<?php echo e(route('admin.infrastruktur.analisis-ai', $inf->id_infrastruktur)); ?>" method="POST" class="mb-2">
                                                <?php echo csrf_field(); ?>
                                                <button type="submit" class="text-[8px] bg-white/20 hover:bg-white/30 text-white px-2 py-1 rounded-md transition-all flex items-center gap-1">
                                                    <i class="fas fa-sync-alt"></i> Re-Scan Visual
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                    <div class="w-full bg-white/10 h-2 rounded-full overflow-hidden">
                                        <div class="bg-gradient-to-r from-indigo-500 to-emerald-400 h-full transition-all duration-1000" style="width: <?php echo e($hasilCnn ? ($hasilCnn->skor_cnn * 100) : '0'); ?>%"></div>
                                    </div>
                                </div>

                                <!-- Panel DT -->
                                <div class="bg-white/5 border border-white/10 rounded-3xl p-6 backdrop-blur-sm">
                                    <div class="flex justify-between items-center mb-4">
                                        <p class="text-[10px] font-black text-indigo-300 uppercase tracking-widest">Structural Logic (DT)</p>
                                        <i class="fas fa-project-diagram text-indigo-300/50"></i>
                                    </div>
                                    <div class="flex items-end gap-3 mb-2">
                                        <p class="text-4xl font-black text-white"><?php echo e($hasilAi->skor_dt ?? '0'); ?><span class="text-sm font-bold text-indigo-300 ml-1">/100</span></p>
                                        <p class="text-xs font-bold <?php echo e(($hasilAi->label_prioritas ?? '') == 'Rusak Berat' ? 'text-red-400' : 'text-yellow-400'); ?> mb-2">
                                            <?php echo e($hasilAi->label_prioritas ?? 'Pending'); ?>

                                        </p>
                                    </div>
                                    <div class="w-full bg-white/10 h-2 rounded-full overflow-hidden">
                                        <div class="bg-gradient-to-r from-indigo-500 to-yellow-400 h-full transition-all duration-1000" style="width: <?php echo e($hasilAi->skor_dt ?? '0'); ?>%"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-indigo-500/20 border border-indigo-400/30 rounded-2xl p-5">
                                <div class="flex items-start gap-4">
                                    <div class="w-10 h-10 bg-indigo-500/30 rounded-xl flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-lightbulb text-yellow-300"></i>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-black text-indigo-200 uppercase tracking-widest mb-1">AI Recommendation</p>
                                        <p class="text-sm font-medium leading-relaxed text-indigo-50 italic">
                                            "<?php echo e($hasilAi->rekomendasi ?? 'Melakukan kalkulasi aturan Decision Tree...'); ?>"
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php else: ?>
                        <div class="bg-gray-50 border border-dashed border-gray-300 rounded-2xl p-8 text-center">
                            <div class="w-14 h-14 bg-indigo-50 text-indigo-400 rounded-full flex items-center justify-center mx-auto mb-3 animate-pulse">
                                <i class="fas fa-microchip text-2xl"></i>
                            </div>
                            <h4 class="font-bold text-[#1e1b4b] text-sm">Sedang Sinkronisasi AI...</h4>
                            <p class="text-xs text-gray-500 mt-2 max-w-sm mx-auto leading-relaxed">Sistem sedang melakukan sinkronisasi antara visual CNN dan logika Decision Tree. Silakan muat ulang halaman ini dalam beberapa saat.</p>
                        </div>
                        <?php endif; ?>
                    </div>

                    <div class="space-y-6 pt-6 border-t border-gray-100">
                        <div class="border-l-4 border-emerald-500 pl-4 mb-4">
                            <h4 class="text-sm font-black text-[#1e1b4b] uppercase tracking-wider">4. Dokumentasi Visual</h4>
                        </div>
                        
                        <div class="w-full max-w-2xl mx-auto relative rounded-2xl overflow-hidden border border-gray-100 shadow-sm bg-[#0f172a] aspect-video flex items-center justify-center group">
                            <?php if($inf->foto_terbaru && $inf->foto_terbaru != 'default.jpg'): ?>
                                <?php $cleanPath = str_replace('\\', '/', $inf->foto_terbaru); ?>
                                <img src="<?php echo e(asset('storage/' . (str_contains($cleanPath, 'infrastruktur/') ? $cleanPath : 'infrastruktur/' . $cleanPath))); ?>" alt="Foto Infrastruktur" class="max-w-full max-h-full object-contain transition-transform duration-500 group-hover:scale-105">
                                
                                
                                <?php if(strtolower($inf->kondisi) != 'baik' && strtolower($inf->kondisi) != 'menunggu ai'): ?>
                                    <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                                        
                                        <div class="relative w-1/2 h-1/2 border-2 border-red-500/50 bg-red-500/5 shadow-[0_0_20px_rgba(239,68,68,0.3)] animate-pulse">
                                            
                                            <div class="absolute -top-1 -left-1 w-4 h-4 border-t-4 border-l-4 border-red-500"></div>
                                            <div class="absolute -top-1 -right-1 w-4 h-4 border-t-4 border-r-4 border-red-500"></div>
                                            <div class="absolute -bottom-1 -left-1 w-4 h-4 border-b-4 border-l-4 border-red-500"></div>
                                            <div class="absolute -bottom-1 -right-1 w-4 h-4 border-b-4 border-r-4 border-red-500"></div>
                                            
                                            
                                            <div class="absolute -top-8 left-0 bg-red-600 text-white text-[9px] font-black px-3 py-1 rounded-md shadow-lg flex items-center gap-2">
                                                <i class="fas fa-exclamation-triangle animate-bounce"></i>
                                                <span>AREA KERUSAKAN TERDETEKSI (<?php echo e(round(($hasilCnn->skor_cnn ?? 0) * 100)); ?>%)</span>
                                            </div>

                                            
                                            <div class="absolute inset-0 bg-gradient-to-b from-transparent via-red-500/20 to-transparent h-1/4 w-full animate-[scan_2s_linear_infinite]"></div>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                    <a href="<?php echo e(asset('storage/' . (str_contains($cleanPath, 'infrastruktur/') ? $cleanPath : 'infrastruktur/' . $cleanPath))); ?>" target="_blank" class="bg-white text-[#1e1b4b] px-6 py-3 rounded-2xl text-xs font-black shadow-2xl uppercase tracking-widest hover:scale-110 transition-all flex items-center gap-2">
                                        <i class="fas fa-expand"></i> Lihat Foto Resolusi Penuh
                                    </a>
                                </div>
                            <?php else: ?>
                                <div class="text-center text-gray-500">
                                    <i class="fas fa-image text-5xl mb-3 opacity-20"></i>
                                    <p class="text-xs font-bold uppercase tracking-widest">Tidak Ada Foto Dokumentasi</p>
                                </div>
                            <?php endif; ?>
                        </div>

                        <style>
                            @keyframes scan {
                                0% { transform: translateY(-100%); }
                                100% { transform: translateY(400%); }
                            }
                        </style>
                        
                        <div class="mt-3 text-center">
                            <p class="text-[9px] font-bold text-gray-400 italic normal-case"><?php echo e($inf->foto_terbaru ?? 'tidak_ada_foto.jpg'); ?> - Diupload oleh <?php echo e($inf->nama_user ?? 'Surveyor'); ?></p>
                        </div>
                    </div>

                </div>

                <div class="flex flex-col sm:flex-row gap-3 pt-8 mt-10 border-t border-gray-100">


                    <a href="<?php echo e(route('admin.infrastruktur.pdf', $inf->id_infrastruktur)); ?>" class="flex-1 bg-yellow-400 text-white py-3.5 rounded-2xl font-black text-[11px] tracking-widest hover:bg-yellow-500 shadow-lg shadow-yellow-100 transition-all flex justify-center items-center gap-2 uppercase">
                        <i class="fas fa-file-pdf text-sm"></i> Export PDF
                    </a>

                    <a href="<?php echo e(route('admin.infrastruktur.edit', $inf->id_infrastruktur)); ?>" class="flex-1 bg-white text-[#1e1b4b] border-2 border-gray-100 py-3.5 rounded-2xl font-black text-[11px] tracking-widest hover:border-indigo-500 hover:text-indigo-600 transition-all flex justify-center items-center gap-2 uppercase">
                        <i class="fas fa-edit text-sm"></i> Edit Manual
                    </a>
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

        // Initialize Mini Map
        const lat = <?php echo e($inf->latitude ?? '-3.316694'); ?>;
        const lng = <?php echo e($inf->longitude ?? '114.590111'); ?>;
        const map = L.map('mini-map').setView([lat, lng], 15);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        L.marker([lat, lng]).addTo(map)
            .bindPopup('<?php echo e($inf->nama_objek ?? $inf->nama_infrastruktur); ?>')
            .openPopup();
    </script>
</body>
</html><?php /**PATH C:\laragon1\laragon\www\geo-sinfra\resources\views/admin/detail-infrastruktur.blade.php ENDPATH**/ ?>