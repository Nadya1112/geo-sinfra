<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data Lapangan | GEO-SINFRA</title>
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
                    <p class="text-[10px] font-extrabold text-emerald-600 uppercase tracking-[0.2em] mb-1">Edit Data</p>
                    <h2 class="text-xl font-black text-[#1e1b4b]">Perbarui Laporan Lapangan</h2>
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
            <div class="max-w-6xl mx-auto">
                <form id="survey-form" action="<?php echo e(route('surveyor.infrastruktur.update', $infrastruktur->id_infrastruktur)); ?>" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 lg:grid-cols-2 gap-8" onsubmit="disableSubmitButton()">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    
                    <div class="space-y-6">
                        <div class="bg-white rounded-[2.5rem] p-8 border border-gray-100 shadow-sm">
                            <div class="flex items-center justify-between mb-6 border-b border-gray-50 pb-4">
                                <h4 class="font-black text-[#1e1b4b] italic">Status Terkini</h4>
                                <div class="flex flex-col items-end gap-2">
                                    <span class="px-4 py-1.5 rounded-xl text-[10px] font-black tracking-widest border <?php echo e($infrastruktur->kondisi == 'Baik' ? 'bg-emerald-50 text-emerald-600 border-emerald-200' : ($infrastruktur->kondisi == 'Rusak Ringan' ? 'bg-yellow-50 text-yellow-600 border-yellow-200' : ($infrastruktur->kondisi == 'Rusak Berat' ? 'bg-red-50 text-red-600 border-red-200' : 'bg-gray-50 text-gray-500 border-gray-200'))); ?>">
                                        <?php echo e(strtoupper($infrastruktur->kondisi)); ?>

                                    </span>
                                    <?php if($infrastruktur->cnn || $infrastruktur->analisis): ?>
                                    <div class="flex gap-3">
                                        <?php if($infrastruktur->cnn): ?>
                                            <p class="text-[9px] font-bold text-gray-400 uppercase tracking-tighter">CNN: <span class="text-emerald-500"><?php echo e(number_format($infrastruktur->cnn->skor_cnn * 100, 1)); ?>%</span></p>
                                        <?php endif; ?>
                                        <?php if($infrastruktur->analisis): ?>
                                            <p class="text-[9px] font-bold text-gray-400 uppercase tracking-tighter">D-Tree: <span class="text-blue-500"><?php echo e($infrastruktur->analisis->label_prioritas); ?></span></p>
                                        <?php endif; ?>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <h4 class="font-black text-[#1e1b4b] mb-6 border-b border-gray-50 pb-4 italic">Detail Infrastruktur</h4>
                            
                            <div class="space-y-5">
                                <div>
                                    <label class="block text-[10px] font-black text-[#1e1b4b] uppercase tracking-widest mb-2">Nama Infrastruktur / Objek <span class="text-red-500">*</span></label>
                                    <input type="text" name="nama_infrastruktur" value="<?php echo e(old('nama_infrastruktur', $infrastruktur->nama_infrastruktur)); ?>" placeholder="Masukkan nama objek survey" class="w-full px-5 py-3 bg-gray-50 border border-gray-300 rounded-2xl text-sm font-semibold focus:ring-4 focus:ring-emerald-500/5 focus:border-emerald-500 outline-none transition-all" required>
                                </div>

                                <div>
                                    <label class="block text-[10px] font-black text-[#1e1b4b] uppercase tracking-widest mb-2">Jenis <span class="text-red-500">*</span></label>
                                    <div class="grid grid-cols-3 gap-4">
                                        <?php $__currentLoopData = ['Jalan', 'Sanitasi', 'Titian']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <label class="cursor-pointer group">
                                            <input type="radio" name="jenis_infrastruktur" value="<?php echo e($type); ?>" class="peer hidden" <?php echo e($infrastruktur->jenis_infrastruktur == $type ? 'checked' : ''); ?>>
                                            <div class="px-2 py-4 bg-gray-50 border border-gray-100 rounded-2xl text-center transition-all peer-checked:bg-[#1e1b4b] peer-checked:border-[#1e1b4b] peer-checked:text-white shadow-sm hover:bg-emerald-50 group-hover:scale-[1.02]">
                                                <i class="fas fa-<?php echo e($type == 'Jalan' ? 'road' : ($type == 'Sanitasi' ? 'faucet-drip' : 'bridge-water')); ?> text-lg mb-2 block group-hover:animate-bounce"></i>
                                                <span class="text-[9px] font-black uppercase tracking-tighter"><?php echo e($type); ?></span>
                                            </div>
                                        </label>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-[10px] font-black text-[#1e1b4b] uppercase tracking-widest mb-2">
                                            Kecamatan <span class="text-red-500">*</span>
                                            <?php if(auth()->user()->id_kecamatan): ?>
                                                <span class="ml-2 text-[8px] text-emerald-500 bg-emerald-50 px-2 py-0.5 rounded-full font-bold">WILAYAH TUGAS ANDA</span>
                                            <?php endif; ?>
                                        </label>
                                        <div class="relative">
                                            <select name="id_kecamatan" id="id_kecamatan" class="w-full px-5 py-3 bg-gray-50 border border-gray-300 rounded-2xl text-sm font-semibold focus:border-emerald-500 outline-none appearance-none <?php echo e(auth()->user()->id_kecamatan ? 'bg-gray-100 cursor-not-allowed pointer-events-none' : 'cursor-pointer'); ?>" required onchange="filterKelurahan()">
                                                <?php $__currentLoopData = $semuaKecamatan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kec): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <?php if(!auth()->user()->id_kecamatan || auth()->user()->id_kecamatan == $kec->id_kecamatan): ?>
                                                        <option value="<?php echo e($kec->id_kecamatan); ?>" <?php echo e($infrastruktur->id_kecamatan == $kec->id_kecamatan ? 'selected' : ''); ?>><?php echo e($kec->nama_kecamatan); ?></option>
                                                    <?php endif; ?>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                            <i class="fas fa-chevron-down absolute right-5 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none text-xs"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-black text-[#1e1b4b] uppercase tracking-widest mb-2">Kelurahan <span class="text-red-500">*</span></label>
                                        <div class="relative">
                                            <select name="id_kelurahan" id="id_kelurahan" class="w-full px-5 py-3 bg-gray-50 border border-gray-300 rounded-2xl text-sm font-semibold focus:border-emerald-500 outline-none appearance-none cursor-pointer transition-all" required onchange="focusToKelurahan()">
                                                <option value="">Pilih Kelurahan...</option>
                                                <?php $__currentLoopData = $semuaKelurahan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($kel->id_kelurahan); ?>" 
                                                            data-kecamatan="<?php echo e($kel->id_kecamatan); ?>"
                                                            data-lat="<?php echo e($kel->latitude); ?>"
                                                            data-lng="<?php echo e($kel->longitude); ?>"
                                                            <?php echo e($infrastruktur->id_kelurahan == $kel->id_kelurahan ? 'selected' : ''); ?>>
                                                        <?php echo e($kel->nama_kelurahan); ?>

                                                    </option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                            <i class="fas fa-chevron-down absolute right-5 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none text-xs"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-[2.5rem] p-8 border border-gray-100 shadow-sm">
                            <h4 class="font-black text-[#1e1b4b] mb-6 border-b border-gray-50 pb-4 italic">Detail Teknis & Dimensi</h4>
                            <div class="space-y-5">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-[10px] font-black text-[#1e1b4b] uppercase tracking-widest mb-2">Panjang (m) <span class="text-red-500">*</span></label>
                                        <input type="number" step="0.01" name="panjang" value="<?php echo e(old('panjang', $infrastruktur->panjang)); ?>" placeholder="0.00" class="w-full px-5 py-3 bg-gray-50 border border-gray-300 rounded-2xl text-sm font-semibold focus:border-emerald-500 outline-none transition-all" required>
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-black text-[#1e1b4b] uppercase tracking-widest mb-2">Lebar (m) <span class="text-red-500">*</span></label>
                                        <input type="number" step="0.01" name="lebar" value="<?php echo e(old('lebar', $infrastruktur->lebar)); ?>" placeholder="0.00" class="w-full px-5 py-3 bg-gray-50 border border-gray-300 rounded-2xl text-sm font-semibold focus:border-emerald-500 outline-none transition-all" required>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-[10px] font-black text-[#1e1b4b] uppercase tracking-widest mb-2">Material Utama <span class="text-red-500">*</span></label>
                                    <div class="relative group">
                                        <i class="fas fa-layer-group absolute left-5 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-[#1e1b4b] transition-colors z-10"></i>
                                        <select name="material_eksisting" class="w-full pl-12 pr-10 py-3.5 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-semibold focus:border-emerald-500 outline-none appearance-none cursor-pointer transition-all relative z-0" required>
                                            <option value="">Pilih Material...</option>
                                            <?php $__currentLoopData = ['Cor Beton', 'Aspal', 'Paving', 'Tanah Asli', 'Tanah Pemadatan', 'Tanah Lepas', 'Batapres', 'Makadam', 'Titian', 'Titian Ulin', 'Titian Rusak', 'WC', 'Lainnya']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($mat); ?>" <?php echo e((old('material_eksisting', $infrastruktur->material_eksisting) == $mat) ? 'selected' : ''); ?>><?php echo e($mat); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                        <i class="fas fa-chevron-down absolute right-5 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none text-[10px]"></i>
                                    </div>
                                </div>

                                <div class="flex gap-6 pt-2">
                                    <label class="flex items-center gap-3 cursor-pointer group">
                                        <div class="relative">
                                            <input type="checkbox" name="has_drainase" value="1" <?php echo e($infrastruktur->has_drainase == 'ya' ? 'checked' : ''); ?> class="peer hidden">
                                            <div class="w-5 h-5 border-2 border-gray-300 rounded-lg peer-checked:bg-emerald-500 peer-checked:border-emerald-500 transition-all flex items-center justify-center">
                                                <i class="fas fa-check text-[10px] text-white opacity-0 peer-checked:opacity-100"></i>
                                            </div>
                                        </div>
                                        <span class="text-[10px] font-black text-[#1e1b4b] uppercase tracking-widest">Ada Drainase</span>
                                    </label>
                                    <label class="flex items-center gap-3 cursor-pointer group">
                                        <div class="relative">
                                            <input type="checkbox" name="has_gorong_gorong" value="1" <?php echo e($infrastruktur->has_gorong_gorong == 'ya' ? 'checked' : ''); ?> class="peer hidden">
                                            <div class="w-5 h-5 border-2 border-gray-300 rounded-lg peer-checked:bg-emerald-500 peer-checked:border-emerald-500 transition-all flex items-center justify-center">
                                                <i class="fas fa-check text-[10px] text-white opacity-0 peer-checked:opacity-100"></i>
                                            </div>
                                        </div>
                                        <span class="text-[10px] font-black text-[#1e1b4b] uppercase tracking-widest">Ada Gorong-gorong</span>
                                    </label>
                                </div>

                                <div>
                                    <label class="block text-[10px] font-black text-[#1e1b4b] uppercase tracking-widest mb-2">Deskripsi Kondisi Fisik / Kerusakan <span class="text-red-500">*</span></label>
                                    <textarea name="kondisi" id="kondisi-textarea" rows="3" placeholder="Contoh kata kunci AI: titian ulin retak, cor beton amblas, hancur, putus..." class="w-full px-5 py-4 bg-gray-50 border border-gray-300 rounded-2xl text-sm font-semibold focus:ring-4 focus:ring-emerald-500/5 focus:border-emerald-500 outline-none transition-all" required><?php echo e(old('kondisi', $infrastruktur->kondisi)); ?></textarea>
                                    <div class="mt-3 flex flex-wrap gap-2">
                                        <span class="text-[8px] font-black text-gray-400 uppercase tracking-widest mr-2 py-1">Saran Kata Kunci AI:</span>
                                        <?php $__currentLoopData = ['Putus', 'Hancur', 'Amblas', 'Retak', 'Lubang', 'Goyang', 'Total', 'Parah']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $keyword): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <button type="button" onclick="addKeyword('<?php echo e($keyword); ?>')" class="px-2.5 py-1 bg-white border border-gray-100 rounded-lg text-[9px] font-bold text-gray-500 hover:bg-[#1e1b4b] hover:text-white hover:border-[#1e1b4b] transition-all shadow-sm active:scale-95">
                                                + <?php echo e($keyword); ?>

                                            </button>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                    <p class="text-[9px] text-gray-400 mt-2 italic font-medium">* Perbaruan deskripsi kerusakan teks ini akan otomatis mengalkulasi ulang skor prioritas AI saat disimpan.</p>
                                </div>

                                <div>
                                    <label class="block text-[10px] font-black text-[#1e1b4b] uppercase tracking-widest mb-2">Tanggal Survey</label>
                                    <input type="date" name="tgl_survey" value="<?php echo e(old('tgl_survey', $infrastruktur->tgl_survey)); ?>" class="w-full px-5 py-3 bg-gray-50 border border-gray-300 rounded-2xl text-sm font-semibold focus:border-emerald-500 outline-none transition-all">
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-[2.5rem] p-8 border border-gray-100 shadow-sm">
                            <h4 class="text-xs font-black text-[#1e1b4b] mb-6 border-b border-gray-50 pb-4 uppercase tracking-widest italic">Dokumentasi Visual (Terkunci)</h4>
                            <div class="relative rounded-[2rem] overflow-hidden border border-gray-100 shadow-inner bg-gray-50 h-52 flex items-center justify-center">
                                <?php if($infrastruktur->foto_terbaru): ?>
                                    <img src="<?php echo e(asset('storage/infrastruktur/' . $infrastruktur->foto_terbaru)); ?>" class="absolute inset-0 w-full h-full object-cover">
                                <?php else: ?>
                                    <div class="text-center">
                                        <i class="fas fa-image text-3xl text-gray-300 mb-2"></i>
                                        <p class="text-[10px] font-bold text-gray-400 uppercase">Tidak ada foto</p>
                                    </div>
                                <?php endif; ?>
                                <div class="absolute inset-0 bg-black/5 flex items-center justify-center">
                                    <span class="px-4 py-2 bg-white/90 backdrop-blur-md rounded-xl text-[9px] font-black text-gray-500 uppercase tracking-widest border border-gray-200">
                                        <i class="fas fa-lock mr-2"></i> Foto Tidak Dapat Diubah
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <div class="bg-white rounded-[2.5rem] p-8 border border-gray-100 shadow-sm">
                            <div class="flex justify-between items-center mb-6 border-b border-gray-50 pb-4">
                                <h4 class="font-black text-[#1e1b4b] italic">Titik Koordinat</h4>
                                <button type="button" onclick="getLocation()" class="px-4 py-2 bg-emerald-100 text-emerald-700 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-emerald-200 transition-all shadow-sm shadow-emerald-900/5">
                                    <i class="fas fa-crosshairs mr-2"></i> Sinkron GPS
                                </button>
                            </div>

                            <div class="grid grid-cols-2 gap-4 mb-6">
                                <div>
                                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Latitude</label>
                                    <input type="text" name="latitude" id="lat-input" value="<?php echo e(old('latitude', $infrastruktur->latitude)); ?>" placeholder="-3.31..." class="w-full px-5 py-3 bg-gray-50 border border-gray-300 rounded-2xl text-sm font-semibold outline-none focus:border-emerald-500" required>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Longitude</label>
                                    <input type="text" name="longitude" id="lng-input" value="<?php echo e(old('longitude', $infrastruktur->longitude)); ?>" placeholder="114.59..." class="w-full px-5 py-3 bg-gray-50 border border-gray-300 rounded-2xl text-sm font-semibold outline-none focus:border-emerald-500" required>
                                </div>
                            </div>

                            <div id="map" class="h-[200px] w-full rounded-xl border border-gray-200 shadow-inner z-0 mb-8"></div>
                            
                            <button type="submit" id="btn-submit" class="w-full bg-[#1e1b4b] text-white py-4 rounded-2xl font-black shadow-xl shadow-indigo-100 hover:bg-emerald-600 transition-all tracking-widest text-xs uppercase flex items-center justify-center gap-3">
                                <span id="btn-text"><i class="fas fa-sync mr-2"></i> Proses Update Data</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <script>
        function addKeyword(word) {
            const textarea = document.getElementById('kondisi-textarea');
            const currentVal = textarea.value.trim();
            if (currentVal === "") {
                textarea.value = word;
            } else {
                textarea.value = currentVal + ", " + word;
            }
            textarea.focus();
        }

        function updateClock() {
            const now = new Date();
            document.getElementById('mini-clock').textContent = `${String(now.getHours()).padStart(2, '0')}:${String(now.getMinutes()).padStart(2, '0')} WITA`;
        }
        setInterval(updateClock, 1000); updateClock();

        const initialLat = <?php echo e($infrastruktur->latitude ?? -3.316694); ?>;
        const initialLng = <?php echo e($infrastruktur->longitude ?? 114.590111); ?>;

        const map = L.map('map').setView([initialLat, initialLng], 15);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

        let marker = L.marker([initialLat, initialLng]).addTo(map);

        map.on('click', function(e) {
            updateMarker(e.latlng.lat, e.latlng.lng);
        });

        function updateMarker(lat, lng) {
            if (marker) map.removeLayer(marker);
            marker = L.marker([lat, lng]).addTo(map);
            document.getElementById('lat-input').value = lat.toFixed(8);
            document.getElementById('lng-input').value = lng.toFixed(8);
        }

        function getLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;
                    map.setView([lat, lng], 17);
                    updateMarker(lat, lng);
                }, function() {
                    alert('Gagal mendapatkan lokasi. Pastikan GPS aktif.');
                });
            }
        }

        function disableSubmitButton() {
            const btn = document.getElementById('btn-submit');
            const text = document.getElementById('btn-text');
            btn.disabled = true;
            btn.classList.add('opacity-50', 'cursor-not-allowed');
            text.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Menyimpan...';
        }

        function filterKelurahan() {
            const idKecamatan = document.getElementById('id_kecamatan').value.trim();
            const kelurahanSelect = document.getElementById('id_kelurahan');
            const options = kelurahanSelect.querySelectorAll('option');
            
            const currentSelected = kelurahanSelect.value;
            let currentStillVisible = false;

            options.forEach(opt => {
                const optKecId = opt.getAttribute('data-kecamatan');

                if (opt.value === "") {
                    opt.style.display = "block";
                    opt.disabled = false;
                    return;
                }

                if (idKecamatan && optKecId === idKecamatan) {
                    opt.style.display = "block";
                    opt.disabled = false;
                    opt.hidden = false;
                    if (opt.value === currentSelected) currentStillVisible = true;
                } else {
                    opt.style.display = "none";
                    opt.disabled = true;
                    opt.hidden = true;
                }
            });
            
            if (!currentStillVisible && idKecamatan !== "") {
                kelurahanSelect.value = "";
            }
        }

        function focusToKelurahan() {
            const kelurahanSelect = document.getElementById('id_kelurahan');
            const selectedOption = kelurahanSelect.options[kelurahanSelect.selectedIndex];
            
            if (selectedOption && selectedOption.value !== "") {
                const lat = parseFloat(selectedOption.getAttribute('data-lat'));
                const lng = parseFloat(selectedOption.getAttribute('data-lng'));
                
                if (!isNaN(lat) && !isNaN(lng)) {
                    map.setView([lat, lng], 16);
                    updateMarker(lat, lng);
                }
            }
        }

        document.getElementById('lat-input').addEventListener('input', updateMapFromInput);
        document.getElementById('lng-input').addEventListener('input', updateMapFromInput);

        function updateMapFromInput() {
            const latStr = document.getElementById('lat-input').value.replace(',', '.');
            const lngStr = document.getElementById('lng-input').value.replace(',', '.');
            const lat = parseFloat(latStr);
            const lng = parseFloat(lngStr);
            
            if (!isNaN(lat) && !isNaN(lng)) {
                if (marker) map.removeLayer(marker);
                marker = L.marker([lat, lng]).addTo(map);
                map.setView([lat, lng]);
            }
        }

        window.addEventListener('DOMContentLoaded', () => {
            filterKelurahan();
        });
    </script>
</body>
</html><?php /**PATH C:\laragon1\laragon\www\geo-sinfra\resources\views/surveyor/edit.blade.php ENDPATH**/ ?>