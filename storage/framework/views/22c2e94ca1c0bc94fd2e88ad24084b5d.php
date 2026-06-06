<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Data Lapangan | GEO-SINFRA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Plus Jakarta Sans', 'sans-serif'] },
                    colors: {
                        navy: { 50:'#f4f4fa', 100:'#e9e9f3', 500:'#6366f1', 800:'#1e1b4b', 900:'#0f0e2c', 950:'#070617' },
                        gold: { 50:'#fdfbf7', 100:'#fbf7ed', 500:'#c5a059', 600:'#b38f4a', 700:'#9d7c3d' }
                    }
                }
            }
        }
    </script>
    <style> 
        body { font-family: 'Plus Jakarta Sans', sans-serif; } 
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        .leaflet-container { font-family: inherit; }
    </style>
</head>
<body class="bg-slate-50 flex h-screen overflow-hidden text-slate-800 text-left font-sans">

    <?php echo $__env->make('surveyor.partials.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <main class="flex-1 flex flex-col h-screen overflow-y-auto custom-scrollbar bg-slate-50">
        
        <header class="bg-white/80 backdrop-blur-xl border-b border-slate-100 px-8 py-5 flex justify-between items-center sticky top-0 z-[1000] shadow-sm">
            <div class="flex items-center gap-4">
                <a href="<?php echo e(route('surveyor.dashboard')); ?>" class="w-10 h-10 flex items-center justify-center bg-white text-slate-400 rounded-xl hover:bg-gold-50 hover:text-gold-600 transition-all border border-slate-200 hover:border-gold-200">
                    <i class="fas fa-arrow-left text-sm"></i>
                </a>
                <div>
                    <p class="text-[9px] font-black text-gold-500 uppercase tracking-[0.2em] mb-0.5">Sistem Input Geospasial</p>
                    <h2 class="text-xl font-black text-navy-900 tracking-tight">Input Data Lapangan</h2>
                </div>
            </div>
            
            <div class="flex items-center gap-6">
                <div class="text-right hidden sm:block">
                    <p class="text-[11px] font-black text-navy-900" id="mini-clock">00:00 WITA</p>
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-tighter"><?php echo e(now()->translatedFormat('l, d F Y')); ?></p>
                </div>
                <div class="h-8 w-[1px] bg-slate-100"></div>
                <div class="flex items-center gap-3">
                    <div class="text-right">
                        <p class="text-[10px] font-black text-navy-900 leading-none uppercase"><?php echo e(auth()->user()->name); ?></p>
                        <p class="text-[8px] font-bold text-emerald-500 uppercase mt-1">Online</p>
                    </div>
                    <div class="w-10 h-10 bg-navy-900 rounded-xl flex items-center justify-center text-gold-500 border border-navy-800 overflow-hidden shadow-md">
                        <?php if(auth()->user()->profile_photo): ?>
                            <img src="<?php echo e(asset('storage/' . auth()->user()->profile_photo)); ?>" class="w-full h-full object-cover">
                        <?php else: ?>
                            <i class="fas fa-user-circle text-xl text-gold-500"></i>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </header>

        <div class="p-8">
            <div class="max-w-7xl mx-auto">
                <?php if($errors->any()): ?>
                <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-2xl flex items-center gap-4 animate-pulse">
                    <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-red-500 shadow-sm border border-red-100">
                        <i class="fas fa-exclamation-circle"></i>
                    </div>
                    <div>
                        <h4 class="text-xs font-black text-red-900 uppercase tracking-tighter">Gagal Memproses Laporan!</h4>
                        <p class="text-[10px] text-red-700 font-medium mt-1">Harap periksa kembali semua isian yang wajib diisi (bertanda <span class="text-red-500">*</span>), termasuk foto dokumentasi.</p>
                    </div>
                </div>
                <?php endif; ?>

                <form id="survey-form" action="<?php echo e(route('surveyor.store')); ?>" method="POST" enctype="multipart/form-data" onsubmit="disableSubmitButton()">
                    <?php echo csrf_field(); ?>
                    
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                        
                        
                        <div class="lg:col-span-7 space-y-8">
                            
                            
                            <div class="bg-white rounded-[2.5rem] p-8 border border-slate-100 shadow-sm hover:shadow-md transition-shadow">
                                <div class="flex items-center gap-4 mb-8 pb-6 border-b border-slate-50">
                                    <div class="w-12 h-12 rounded-2xl bg-navy-50 flex items-center justify-center text-gold-500 border border-navy-100">
                                        <i class="fas fa-file-signature text-xl"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-black text-navy-900 uppercase tracking-tight text-lg">Identitas Laporan</h4>
                                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">Klasifikasi Objek Infrastruktur</p>
                                    </div>
                                </div>

                                <div class="space-y-6">
                                <div class="grid grid-cols-1 gap-5">
                                    <div>
                                        <label class="block text-[10px] font-black text-navy-900 uppercase tracking-widest mb-3 ml-1">Nama Infrastruktur / Ruas Jalan <span class="text-red-500">*</span></label>
                                        <div class="relative group">
                                            <i class="fas fa-tag absolute left-5 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-gold-500 transition-colors"></i>
                                            <input type="text" name="nama_infrastruktur" placeholder="Contoh: Jalan Hasan Basry" class="w-full pl-12 pr-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-semibold focus:ring-4 focus:ring-gold-500/10 focus:border-gold-500 outline-none transition-all text-navy-900" required>
                                        </div>
                                    </div>
                                </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                        <div>
                                            <label class="block text-[10px] font-black text-navy-900 uppercase tracking-widest mb-3 ml-1">Kecamatan Wilayah <span class="text-red-500">*</span></label>
                                            <div class="relative group">
                                                <i class="fas fa-map-location-dot absolute left-5 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-gold-500 transition-colors z-10"></i>
                                                <select name="id_kecamatan" id="id_kecamatan" class="w-full pl-12 pr-10 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-semibold focus:ring-4 focus:ring-gold-500/10 focus:border-gold-500 outline-none appearance-none cursor-pointer transition-all relative z-0 text-navy-900" required onchange="filterKelurahan()">
                                                    <option value="">Pilih Kecamatan...</option>
                                                    <?php $__currentLoopData = $semuaKecamatan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kec): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($kec->id_kecamatan); ?>" <?php echo e(count($semuaKecamatan) == 1 ? 'selected' : ''); ?>><?php echo e($kec->nama_kecamatan); ?></option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                                <i class="fas fa-chevron-down absolute right-5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-[10px]"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <label class="block text-[10px] font-black text-navy-900 uppercase tracking-widest mb-3 ml-1">Kelurahan / Desa <span class="text-red-500">*</span></label>
                                            <div class="relative group">
                                                <i class="fas fa-city absolute left-5 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-gold-500 transition-colors z-10"></i>
                                                <select name="id_kelurahan" id="id_kelurahan" class="w-full pl-12 pr-10 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-semibold focus:ring-4 focus:ring-gold-500/10 focus:border-gold-500 outline-none appearance-none cursor-pointer transition-all relative z-0 text-navy-900" required onchange="focusToKelurahan()">
                                                    <option value="">Pilih Kelurahan...</option>
                                                    <?php $__currentLoopData = $semuaKelurahan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($kel->id_kelurahan); ?>" 
                                                                data-kecamatan="<?php echo e($kel->id_kecamatan); ?>"
                                                                data-lat="<?php echo e($kel->latitude); ?>"
                                                                data-lng="<?php echo e($kel->longitude); ?>">
                                                            <?php echo e($kel->nama_kelurahan); ?>

                                                        </option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                                <i class="fas fa-chevron-down absolute right-5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-[10px]"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            
                            <div class="bg-white rounded-[2.5rem] p-8 border border-slate-100 shadow-sm hover:shadow-md transition-shadow">
                                <div class="flex items-center gap-4 mb-8 pb-6 border-b border-slate-50">
                                    <div class="w-12 h-12 rounded-2xl bg-navy-50 flex items-center justify-center text-gold-500 border border-navy-100">
                                        <i class="fas fa-ruler-combined text-xl"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-black text-navy-900 uppercase tracking-tight text-lg">Spesifikasi Teknis</h4>
                                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">Dimensi & Material Data DED</p>
                                    </div>
                                </div>

                                <div class="space-y-6">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                        <div class="space-y-6">
                                            <div class="grid grid-cols-2 gap-4">
                                                <div>
                                                    <label class="block text-[10px] font-black text-navy-900 uppercase tracking-widest mb-3 ml-1">Panjang (m) <span class="text-red-500">*</span></label>
                                                    <div class="relative group">
                                                        <i class="fas fa-arrows-left-right absolute left-5 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-gold-500 transition-colors"></i>
                                                        <input type="number" step="0.01" name="panjang" placeholder="0.00" class="w-full pl-12 pr-5 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-semibold focus:ring-4 focus:ring-gold-500/10 focus:border-gold-500 outline-none transition-all text-navy-900" required>
                                                    </div>
                                                </div>
                                                <div>
                                                    <label class="block text-[10px] font-black text-navy-900 uppercase tracking-widest mb-3 ml-1">Lebar (m) <span class="text-red-500">*</span></label>
                                                    <div class="relative group">
                                                        <i class="fas fa-arrows-up-down absolute left-5 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-gold-500 transition-colors"></i>
                                                        <input type="number" step="0.01" name="lebar" placeholder="0.00" class="w-full pl-12 pr-5 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-semibold focus:ring-4 focus:ring-gold-500/10 focus:border-gold-500 outline-none transition-all text-navy-900" required>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            
                                            <div>
                                                <label class="block text-[10px] font-black text-navy-900 uppercase tracking-widest mb-3 ml-1">Material Utama (Sesuai DED) <span class="text-red-500">*</span></label>
                                                <div class="relative group">
                                                    <i class="fas fa-layer-group absolute left-5 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-gold-500 transition-colors z-10"></i>
                                                    <select name="material_eksisting" class="w-full pl-12 pr-10 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-semibold focus:ring-4 focus:ring-gold-500/10 focus:border-gold-500 outline-none appearance-none cursor-pointer transition-all relative z-0 text-navy-900" required>
                                                        <option value="" disabled selected>Pilih Material Utama...</option>
                                                        <option value="Cor Beton">Cor Beton</option>
                                                        <option value="Titian (Kayu Ulin)">Titian (Kayu Ulin)</option>
                                                        <option value="Tanah Asli">Tanah Asli</option>
                                                        <option value="Tanah Pemadatan">Tanah Pemadatan</option>
                                                        <option value="Tanah Lepas">Tanah Lepas</option>
                                                        <option value="Paving Block">Paving Block</option>
                                                        <option value="Aspal">Aspal</option>
                                                        <option value="Bata Press">Bata Press</option>
                                                    </select>
                                                    <i class="fas fa-chevron-down absolute right-5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-[10px]"></i>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="space-y-4">
                                            <label class="block text-[10px] font-black text-navy-900 uppercase tracking-widest mb-3 ml-1">Ketersediaan (Sesuai DED)</label>
                                            <div class="space-y-3">
                                                <label class="flex items-center gap-4 p-4 bg-slate-50 rounded-2xl border border-slate-200 cursor-pointer hover:bg-gold-50 hover:border-gold-200 transition-all group">
                                                    <input type="checkbox" name="has_drainase" value="1" class="peer hidden">
                                                    <div class="w-6 h-6 rounded-lg border-2 border-slate-300 peer-checked:bg-gold-500 peer-checked:border-gold-500 transition-all flex items-center justify-center">
                                                        <i class="fas fa-check text-xs text-white opacity-0 peer-checked:opacity-100"></i>
                                                    </div>
                                                    <span class="text-xs font-black text-navy-900 uppercase tracking-widest">Saluran Drainase</span>
                                                </label>
                                                <label class="flex items-center gap-4 p-4 bg-slate-50 rounded-2xl border border-slate-200 cursor-pointer hover:bg-gold-50 hover:border-gold-200 transition-all group">
                                                    <input type="checkbox" name="has_gorong_gorong" value="1" class="peer hidden">
                                                    <div class="w-6 h-6 rounded-lg border-2 border-slate-300 peer-checked:bg-gold-500 peer-checked:border-gold-500 transition-all flex items-center justify-center">
                                                        <i class="fas fa-check text-xs text-white opacity-0 peer-checked:opacity-100"></i>
                                                    </div>
                                                    <span class="text-xs font-black text-navy-900 uppercase tracking-widest">Gorong-gorong</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="pt-4 border-t border-slate-50">
                                        <label class="block text-[10px] font-black text-navy-900 uppercase tracking-widest mb-3 ml-1">Deskripsi Kondisi Fisik Lapangan <span class="text-slate-400 font-medium">(Opsional)</span></label>
                                        <div class="relative group">
                                            <textarea name="kondisi" id="kondisi-textarea" rows="3" placeholder="Deskripsikan kerusakan spesifik (Contoh: retak dan berlubang akibat genangan air)..." class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-semibold focus:ring-4 focus:ring-gold-500/10 focus:border-gold-500 outline-none transition-all text-navy-900"></textarea>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        
                        <div class="lg:col-span-5 space-y-8">
                            
                            
                            <div class="bg-white rounded-[2.5rem] p-8 border border-slate-100 shadow-sm hover:shadow-md transition-shadow">
                                <div class="flex items-center justify-between mb-8 pb-4 border-b border-slate-50">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 rounded-2xl bg-navy-50 flex items-center justify-center text-gold-500 border border-navy-100">
                                            <i class="fas fa-location-crosshairs text-xl"></i>
                                        </div>
                                        <div>
                                            <h4 class="font-black text-navy-900 uppercase tracking-tight text-lg">Titik Lokasi</h4>
                                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">Akurasi Geospasial</p>
                                        </div>
                                    </div>
                                    <button type="button" id="btn-gps" onclick="getLocation(this)" class="px-4 py-3 bg-navy-900 hover:bg-gold-500 hover:text-navy-900 text-white rounded-xl text-[9px] font-black uppercase tracking-widest flex items-center gap-2 transition-all shadow-md active:scale-95 border border-white/10">
                                        <i class="fas fa-crosshairs"></i>
                                        Sync GPS
                                    </button>
                                </div>

                                <div class="relative rounded-[2rem] border-[6px] border-slate-50 shadow-inner overflow-hidden mb-6 h-[260px]">
                                    <div id="map" class="absolute inset-0 z-0 bg-slate-100"></div>
                                    <div class="absolute top-4 right-4 z-10">
                                        <button type="button" onclick="toggleFloodLayer()" id="btn-flood-layer" class="w-10 h-10 bg-white/90 backdrop-blur-md rounded-xl shadow-lg border border-slate-100 text-slate-400 hover:text-blue-500 hover:border-blue-200 transition-all flex items-center justify-center group" title="Tampilkan Area Rawan Banjir">
                                            <i class="fas fa-water text-sm group-hover:scale-110 transition-transform"></i>
                                        </button>
                                    </div>
                                    <div class="absolute bottom-4 left-4 right-4 z-10 pointer-events-none">
                                        <div class="bg-white/90 backdrop-blur-md px-4 py-3 rounded-xl shadow-lg border border-slate-100 text-center flex items-center justify-center gap-2 pointer-events-none">
                                            <div class="w-2 h-2 rounded-full bg-gold-500 animate-pulse"></div>
                                            <p class="text-[9px] font-black uppercase tracking-widest text-navy-900">Klik Pada Peta Untuk Geser Pin</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200">
                                        <label class="block text-[8px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">Latitude</label>
                                        <input type="text" id="lat-input" name="latitude" readonly class="w-full bg-transparent border-none p-0 text-xs font-black text-navy-900 outline-none cursor-default">
                                    </div>
                                    <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200">
                                        <label class="block text-[8px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">Longitude</label>
                                        <input type="text" id="lng-input" name="longitude" readonly class="w-full bg-transparent border-none p-0 text-xs font-black text-navy-900 outline-none cursor-default">
                                    </div>
                                </div>
                            </div>

                            
                            <div class="bg-white rounded-[2.5rem] p-8 border border-slate-100 shadow-sm hover:shadow-md transition-shadow">
                                <div class="flex items-center gap-4 mb-8 pb-4 border-b border-slate-50">
                                    <div class="w-12 h-12 rounded-2xl bg-navy-50 flex items-center justify-center text-gold-500 border border-navy-100">
                                        <i class="fas fa-camera text-xl"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-black text-navy-900 uppercase tracking-tight text-lg">Dokumentasi <span class="text-red-500">*</span></h4>
                                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">Foto Visual Lapangan</p>
                                    </div>
                                </div>

                                <div class="space-y-6">
                                    <div class="relative group cursor-pointer h-72">
                                        <input type="file" name="foto" id="foto-input" accept="image/*" capture="environment" class="absolute inset-0 opacity-0 z-10 cursor-pointer" required onchange="previewImage(event)">
                                        <div id="foto-preview-container" class="absolute inset-0 border-[3px] border-dashed border-slate-200 rounded-[2rem] flex flex-col items-center justify-center gap-4 group-hover:bg-gold-50/50 group-hover:border-gold-300 transition-all overflow-hidden bg-slate-50">
                                            <div id="placeholder-elements" class="flex flex-col items-center text-center px-6">
                                                <div class="w-16 h-16 bg-white rounded-2xl shadow-sm flex items-center justify-center mb-3 group-hover:scale-110 transition-transform border border-slate-100">
                                                    <i class="fas fa-camera text-2xl text-gold-500"></i>
                                                </div>
                                                <p class="text-xs font-black text-navy-900 uppercase tracking-widest mb-1">Ambil Foto Langsung</p>
                                                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider text-center">Tap area ini untuk upload bukti visual</p>
                                            </div>
                                            <img id="image-preview" src="#" alt="Preview" class="hidden absolute inset-0 w-full h-full object-cover">
                                        </div>
                                    </div>
                                    
                                    <div class="bg-navy-900 p-4 rounded-2xl flex gap-4 items-start shadow-inner border border-navy-800">
                                        <i class="fas fa-robot text-gold-500 mt-1 text-lg"></i>
                                        <p class="text-[9px] text-slate-300 font-medium leading-relaxed">
                                            <strong class="font-black uppercase tracking-widest text-[10px] text-white">ANALISIS AI:</strong><br>
                                            Ambil foto secara jelas. Foto ini akan otomatis dianalisis oleh AI *Convolutional Neural Network* untuk mengklasifikasi kondisi infrastruktur.
                                        </p>
                                    </div>

                                    <div class="grid grid-cols-1 gap-4 pt-4 border-t border-slate-100">
                                        <div class="flex items-center justify-between px-2 mb-2">
                                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Tgl Survey: <span class="text-navy-900"><?php echo e(date('d M Y')); ?></span></span>
                                            <input type="hidden" name="tgl_survey" value="<?php echo e(date('Y-m-d')); ?>">
                                        </div>
                                        <button type="submit" id="btn-submit" class="w-full py-5 bg-gold-500 hover:bg-gold-600 text-white rounded-[2rem] font-black text-[11px] uppercase tracking-[0.2em] transition-all shadow-xl shadow-gold-500/20 active:scale-95 flex items-center justify-center gap-3">
                                            <span id="btn-text">Proses</span>
                                            <i class="fas fa-arrow-right group-hover:translate-x-2 transition-transform"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </main>

    <script>
        function updateClock() {
            const now = new Date();
            document.getElementById('mini-clock').textContent = `${String(now.getHours()).padStart(2, '0')}:${String(now.getMinutes()).padStart(2, '0')} WITA`;
        }
        setInterval(updateClock, 1000); updateClock();

        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function(){
                const output = document.getElementById('image-preview');
                const placeholder = document.getElementById('placeholder-elements');
                output.src = reader.result;
                output.classList.remove('hidden');
                placeholder.classList.add('hidden');
            };
            reader.readAsDataURL(event.target.files[0]);
        }

        const map = L.map('map', {
            zoomControl: false,
            attributionControl: false
        }).setView([-3.316694, 114.590111], 13);
        
        L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png').addTo(map);

        let showFloodLayer = false;
        const floodLayer = L.layerGroup([
            L.circle([-3.315, 114.590], { color: '#ef4444', fillColor: '#ef4444', fillOpacity: 0.2, weight: 1, radius: 800 }).bindPopup('<div class="text-[10px] font-black text-red-500 text-center">Zona Merah (Rawan Tinggi)</div>'),
            L.circle([-3.325, 114.598], { color: '#f59e0b', fillColor: '#f59e0b', fillOpacity: 0.2, weight: 1, radius: 1200 }).bindPopup('<div class="text-[10px] font-black text-orange-500 text-center">Zona Kuning (Rawan Sedang)</div>'),
            L.circle([-3.295, 114.580], { color: '#ef4444', fillColor: '#ef4444', fillOpacity: 0.2, weight: 1, radius: 900 }).bindPopup('<div class="text-[10px] font-black text-red-500 text-center">Zona Merah (Rawan Tinggi)</div>'),
            L.circle([-3.330, 114.570], { color: '#f59e0b', fillColor: '#f59e0b', fillOpacity: 0.2, weight: 1, radius: 1000 }).bindPopup('<div class="text-[10px] font-black text-orange-500 text-center">Zona Kuning (Rawan Sedang)</div>')
        ]);

        function toggleFloodLayer() {
            showFloodLayer = !showFloodLayer;
            const btn = document.getElementById('btn-flood-layer');
            if(showFloodLayer) {
                map.addLayer(floodLayer);
                btn.classList.replace('text-slate-400', 'text-blue-500');
                btn.classList.replace('border-slate-100', 'border-blue-200');
                btn.classList.add('bg-blue-50');
            } else {
                map.removeLayer(floodLayer);
                btn.classList.replace('text-blue-500', 'text-slate-400');
                btn.classList.replace('border-blue-200', 'border-slate-100');
                btn.classList.remove('bg-blue-50');
            }
        }

        let marker;

        map.on('click', function(e) {
            updateMarker(e.latlng.lat, e.latlng.lng);
        });

        function updateMarker(lat, lng) {
            if (marker) map.removeLayer(marker);
            marker = L.marker([lat, lng], {
                icon: L.divIcon({
                    className: '',
                    html: `<div class="w-8 h-8 bg-gold-500 rounded-full border-[3px] border-white shadow-lg flex items-center justify-center text-white"><i class="fas fa-location-dot"></i></div>`,
                    iconSize: [32, 32],
                    iconAnchor: [16, 32]
                })
            }).addTo(map);
            document.getElementById('lat-input').value = lat.toFixed(8);
            document.getElementById('lng-input').value = lng.toFixed(8);
        }

        function getLocation(btn) {
            const originalHtml = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mencari...';

            // Fungsi fallback (Simulasi GPS / IP API) jika akses ditolak karena HTTP
            const fallbackLocation = () => {
                console.log("Menggunakan Fallback Lokasi (HTTP/Izin Ditolak)");
                // Mengambil lokasi dari IP (atau menggunakan lokasi default Banjarmasin)
                fetch('http://ip-api.com/json/')
                    .then(response => response.json())
                    .then(data => {
                        if(data.status === 'success') {
                            map.setView([data.lat, data.lon], 17);
                            updateMarker(data.lat, data.lon);
                        } else {
                            // Default Banjarmasin jika API gagal
                            const defLat = -3.316694 + (Math.random() * 0.01 - 0.005);
                            const defLng = 114.590111 + (Math.random() * 0.01 - 0.005);
                            map.setView([defLat, defLng], 17);
                            updateMarker(defLat, defLng);
                        }
                        btn.innerHTML = '<i class="fas fa-check"></i> Sukses (Fallback)';
                        setTimeout(() => { btn.innerHTML = originalHtml; }, 2000);
                    })
                    .catch(() => {
                        // Default Banjarmasin
                        const defLat = -3.316694;
                        const defLng = 114.590111;
                        map.setView([defLat, defLng], 17);
                        updateMarker(defLat, defLng);
                        btn.innerHTML = '<i class="fas fa-check"></i> Sukses (Simulasi)';
                        setTimeout(() => { btn.innerHTML = originalHtml; }, 2000);
                    });
            };

            if (navigator.geolocation) {
                // Coba panggil GPS Asli
                navigator.geolocation.getCurrentPosition(function(position) {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;
                    map.setView([lat, lng], 17);
                    updateMarker(lat, lng);
                    btn.innerHTML = '<i class="fas fa-check"></i> Sukses';
                    setTimeout(() => { btn.innerHTML = originalHtml; }, 2000);
                }, function(error) {
                    // Jika gagal (karena HTTP atau izin ditolak), gunakan fallback
                    fallbackLocation();
                }, {
                    enableHighAccuracy: true,
                    timeout: 5000, // Timeout dipercepat 5 detik agar cepat pindah ke fallback
                    maximumAge: 0
                });
            } else {
                fallbackLocation();
            }
        }

        function disableSubmitButton() {
            const btn = document.getElementById('btn-submit');
            const text = document.getElementById('btn-text');
            btn.disabled = true;
            btn.classList.add('opacity-75', 'cursor-not-allowed');
            text.innerHTML = 'Memproses...';
        }

        function filterKelurahan() {
            const idKecamatan = document.getElementById('id_kecamatan').value.trim();
            const kelurahanSelect = document.getElementById('id_kelurahan');
            const options = kelurahanSelect.querySelectorAll('option');
            
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
                } else {
                    opt.style.display = "none";
                    opt.disabled = true;
                    opt.hidden = true;
                }
            });
            kelurahanSelect.value = "";
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

        window.addEventListener('DOMContentLoaded', () => {
            filterKelurahan();
        });
    </script>
</body>
</html><?php /**PATH C:\laragon1\laragon\www\geo-sinfra\resources\views/surveyor/input.blade.php ENDPATH**/ ?>