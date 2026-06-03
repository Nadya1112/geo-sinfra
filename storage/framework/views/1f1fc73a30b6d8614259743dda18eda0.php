<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GEO-SINFRA - Sistem Pemetaan Infrastruktur Permukiman Kota Banjarmasin</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                    },
                    colors: {
                        navy: {
                            50: '#f4f4fa',
                            100: '#e9e9f3',
                            200: '#c7c8e3',
                            500: '#6366f1',
                            800: '#1e1b4b',
                            900: '#0f0e2c',
                            950: '#070617',
                        },
                        gold: {
                            50: '#fdfbf7',
                            100: '#fbf7ed',
                            500: '#c5a059',
                            600: '#b38f4a',
                            700: '#9d7c3d',
                        }
                    }
                }
            }
        }
    </script>

    <style>
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background-color: #f8fafc; 
            color: #0f172a; 
            opacity: 0; 
            transition: opacity 0.8s ease-in; 
        }
        body.loaded { opacity: 1; }

        /* Preloader Styles */
        #preloader {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: radial-gradient(circle at center, #141332 0%, #070617 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            z-index: 99999;
            transition: all 0.6s cubic-bezier(0.85, 0, 0.15, 1);
        }
        #preloader.fade-out {
            opacity: 0;
            visibility: hidden;
            transform: translateY(-100%);
        }
        
        .loader-glow {
            position: relative;
            width: 100px;
            height: 100px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .loader-glow::before {
            content: '';
            position: absolute;
            width: 80px;
            height: 80px;
            border-radius: 50%;
            border: 3px solid transparent;
            border-top-color: #c5a059;
            border-bottom-color: #6366f1;
            animation: spin 1.5s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Hero Section Premium Mesh */
        .hero-premium {
            position: relative;
            background: radial-gradient(circle at 80% 20%, rgba(99, 102, 241, 0.15) 0%, transparent 50%),
                        radial-gradient(circle at 20% 80%, rgba(197, 160, 89, 0.12) 0%, transparent 50%),
                        #070617;
            overflow: hidden;
        }
        .hero-premium::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, transparent 50%, rgba(7, 6, 23, 0.9) 100%);
            pointer-events: none;
        }
        
        /* Grid background pattern */
        .grid-pattern {
            position: absolute;
            inset: 0;
            background-image: linear-gradient(to right, rgba(255,255,255,0.03) 1px, transparent 1px),
                              linear-gradient(to bottom, rgba(255,255,255,0.03) 1px, transparent 1px);
            background-size: 40px 40px;
            mask-image: radial-gradient(ellipse at center, black, transparent 80%);
            pointer-events: none;
        }

        #map { 
            height: 100%; 
            width: 100%; 
            border-radius: 2.5rem; 
            border: none !important; 
            box-shadow: none !important;
            z-index: 10; 
        }

        /* Custom Scrollbar for list and dropdowns */
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: rgba(255,255,255,0.03); border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(197, 160, 89, 0.3); border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: rgba(197, 160, 89, 0.6); }

        /* Premium Scroll Reveal */
        .reveal-up {
            opacity: 0;
            transform: translateY(40px);
            transition: all 0.8s cubic-bezier(0.215, 0.610, 0.355, 1);
        }
        .reveal-up.active {
            opacity: 1;
            transform: translateY(0);
        }

        /* Shine effect for buttons */
        .btn-shine {
            position: relative;
            overflow: hidden;
        }
        .btn-shine::after {
            content: '';
            position: absolute;
            top: -50%; left: -60%; width: 30%; height: 200%;
            background: rgba(255, 255, 255, 0.2);
            transform: rotate(30deg);
            transition: none;
        }
        .btn-shine:hover::after {
            left: 120%;
            transition: all 0.6s ease-in-out;
        }

        /* Leaflet Customizations for Dark Premium Theme */
        .custom-leaflet-popup .leaflet-popup-content-wrapper {
            background: #0f0e2c !important;
            color: #ffffff !important;
            border-radius: 1.25rem !important;
            padding: 4px !important;
            border: 1px solid rgba(255,255,255,0.1) !important;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.5) !important;
        }
        .custom-leaflet-popup .leaflet-popup-tip {
            background: #0f0e2c !important;
            border: 1px solid rgba(255,255,255,0.1) !important;
        }
        .custom-leaflet-popup .leaflet-popup-close-button {
            color: #c5a059 !important;
            padding: 8px !important;
        }
        /* Remove Leaflet's default divIcon black box border and background */
        .leaflet-div-icon {
            background: transparent !important;
            border: none !important;
        }
        
        /* Enforce elimination of any thick black rectangular border or outline around the map */
        #map, 
        #map *,
        .leaflet-container, 
        .leaflet-container *,
        .leaflet-map-pane, 
        .leaflet-pane, 
        .leaflet-wrapper,
        .leaflet-interactive {
            border: none !important;
            outline: none !important;
            outline-width: 0 !important;
            box-shadow: none !important;
        }
        
        #map:focus,
        #map:active,
        #map:focus-visible,
        #map:focus-within,
        .leaflet-container:focus,
        .leaflet-container:active,
        .leaflet-container:focus-visible,
        .leaflet-container:focus-within {
            border: none !important;
            outline: none !important;
            outline-width: 0 !important;
            box-shadow: none !important;
        }

        /* Style the Leaflet Attribution text to be clean, premium and highly visible */
        .leaflet-control-attribution {
            background: rgba(15, 14, 44, 0.8) !important;
            backdrop-filter: blur(10px) !important;
            color: rgba(255, 255, 255, 0.6) !important;
            border-radius: 1rem 0 0 0 !important;
            border-left: 1px solid rgba(255, 255, 255, 0.1) !important;
            border-top: 1px solid rgba(255, 255, 255, 0.1) !important;
            padding: 6px 12px !important;
            font-size: 9px !important;
            font-family: 'Plus Jakarta Sans', sans-serif !important;
            font-weight: 600 !important;
            letter-spacing: 0.025em !important;
            box-shadow: -5px -5px 15px rgba(0, 0, 0, 0.2) !important;
        }
        .leaflet-control-attribution a {
            color: #c5a059 !important;
            font-weight: 700 !important;
            text-decoration: none !important;
            transition: color 0.2s ease !important;
        }
        .leaflet-control-attribution a:hover {
            color: #ffffff !important;
            text-decoration: underline !important;
        }
    </style>
</head>
<body class="antialiased font-sans bg-slate-50 text-slate-800">

    <!-- Preloader -->
    <div id="preloader">
        <div class="loader-glow mb-4">
            <i class="fas fa-globe-asia text-[#c5a059] text-4xl animate-pulse"></i>
        </div>
        <h2 class="text-white font-extrabold tracking-[0.6em] uppercase text-xs">GEO-SINFRA AI</h2>
        <p class="text-slate-400 text-[9px] tracking-widest mt-2 uppercase">Sistem Pemetaan Infrastruktur Permukiman Kota Banjarmasin</p>
    </div>
 
    <!-- Header / Navbar -->
    <nav class="bg-white/80 backdrop-blur-xl border-b border-slate-100 h-24 flex items-center sticky top-0 z-[5000] transition-all duration-300">
        <div class="max-w-7xl mx-auto px-6 md:px-8 w-full flex justify-between items-center">
            <!-- Brand -->
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-navy-900 rounded-2xl flex items-center justify-center text-gold-500 shadow-lg shadow-navy-950/20">
                    <i class="fas fa-globe-asia text-xl"></i>
                </div>
                <div>
                    <h2 class="text-xl font-extrabold text-navy-900 tracking-tighter uppercase leading-none">GEO-SINFRA</h2>
                </div>
            </div>
            
            <!-- Navigation Links -->
            <div class="hidden md:flex items-center gap-8 font-semibold text-xs uppercase tracking-wider text-slate-600">
                <a href="#" class="hover:text-gold-500 transition-colors">Beranda</a>
                <a href="#statistik" class="hover:text-gold-500 transition-colors">Statistik</a>
                <a href="#peta" class="hover:text-gold-500 transition-colors">Peta Sebaran</a>
                <a href="<?php echo e(url('/login')); ?>" class="btn-shine bg-navy-900 text-gold-500 hover:bg-gold-500 hover:text-white px-6 py-3 rounded-xl shadow-md shadow-navy-900/10 font-bold transition-all duration-300 text-center flex items-center gap-2">
                    <i class="fas fa-sign-in-alt"></i> Login
                </a>
            </div>

            <!-- Mobile Hamburger Menu Button -->
            <button class="md:hidden text-navy-900 focus:outline-none" onclick="toggleMobileMenu()">
                <i class="fas fa-bars text-2xl"></i>
            </button>
        </div>
    </nav>

    <!-- Mobile Navigation Overlay -->
    <div id="mobile-menu" class="hidden fixed inset-0 z-[4999] bg-navy-950/95 backdrop-blur-xl flex flex-col justify-center items-center gap-8 text-white text-lg font-bold uppercase tracking-widest transition-all">
        <button class="absolute top-8 right-8 text-3xl" onclick="toggleMobileMenu()">
            <i class="fas fa-times"></i>
        </button>
        <a href="#" class="hover:text-gold-500" onclick="toggleMobileMenu()">Beranda</a>
        <a href="#statistik" class="hover:text-gold-500" onclick="toggleMobileMenu()">Statistik</a>
        <a href="#peta" class="hover:text-gold-500" onclick="toggleMobileMenu()">Peta Sebaran</a>
        <a href="<?php echo e(url('/login')); ?>" class="bg-gold-500 text-white px-8 py-3 rounded-full shadow-lg" onclick="toggleMobileMenu()">Login</a>
    </div>

    <!-- Hero Section -->
    <section class="hero-premium py-24 md:py-32 flex items-center min-h-[580px]">
        <div class="grid-pattern"></div>
        <div class="max-w-7xl mx-auto px-6 md:px-8 w-full relative z-10">
            <div class="max-w-3xl">
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/5 border border-white/10 text-gold-500 text-xs font-bold tracking-wider mb-6 animate-pulse">
                    <i class="fas fa-brain text-[10px]"></i>
                    <span>POWERED BY DEEP LEARNING & GIS</span>
                </div>
                <h3 class="text-4xl md:text-6xl font-black text-white tracking-tight leading-[1.08] mb-4">
                    Selamat Datang di
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-gold-500 via-yellow-400 to-[#6366f1]"> GEO-SINFRA</span>
                </h3>
                <p class="text-slate-300 font-semibold text-lg md:text-2xl leading-relaxed mb-10 max-w-2xl">
                    Sistem Informasi Pemetaan Infrastruktur Permukiman Kota Banjarmasin
                </p>
                <div class="flex flex-wrap gap-4">
                    <a href="#peta" class="btn-shine bg-gradient-to-r from-gold-500 to-gold-600 text-white px-8 py-4.5 rounded-2xl font-bold text-xs uppercase tracking-widest hover:scale-105 transition-all shadow-xl shadow-gold-500/20">
                        <i class="fas fa-map mr-2"></i> Eksplorasi Peta GIS
                    </a>
                    <a href="#statistik" class="bg-white/10 backdrop-blur-md text-white border border-white/20 px-8 py-4.5 rounded-2xl font-bold text-xs uppercase tracking-widest hover:bg-white hover:text-navy-950 transition-all">
                        Analisis Statistik
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Key Metrics / Feature Highlight Cards -->
    <section class="py-12 bg-transparent relative -mt-16 z-20 max-w-7xl mx-auto px-6 md:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white p-8 rounded-3xl shadow-xl shadow-slate-900/5 border border-slate-100 flex gap-5 items-start hover:-translate-y-2 transition-all duration-300">
                <div class="w-14 h-14 bg-[#6366f1]/10 rounded-2xl flex items-center justify-center text-navy-500 shrink-0">
                    <i class="fas fa-map-location-dot text-2xl"></i>
                </div>
                <div>
                    <h5 class="text-sm font-extrabold text-navy-900 uppercase tracking-wider mb-2">Interaktif GIS</h5>
                    <p class="text-xs text-slate-500 leading-relaxed">Visualisasi geospasial real-time yang membagi sebaran aset infrastruktur per kelurahan secara presisi.</p>
                </div>
            </div>
            
            <div class="bg-white p-8 rounded-3xl shadow-xl shadow-slate-900/5 border border-slate-100 flex gap-5 items-start hover:-translate-y-2 transition-all duration-300">
                <div class="w-14 h-14 bg-emerald-500/10 rounded-2xl flex items-center justify-center text-emerald-500 shrink-0">
                    <i class="fas fa-brain text-2xl"></i>
                </div>
                <div>
                    <h5 class="text-sm font-extrabold text-navy-900 uppercase tracking-wider mb-2">Analisis AI Prediktif</h5>
                    <p class="text-xs text-slate-500 leading-relaxed">Klasifikasi otomatis jenis dan kondisi kerusakan menggunakan model CNN & Decision Tree hybrid.</p>
                </div>
            </div>

            <div class="bg-white p-8 rounded-3xl shadow-xl shadow-slate-900/5 border border-slate-100 flex gap-5 items-start hover:-translate-y-2 transition-all duration-300">
                <div class="w-14 h-14 bg-amber-500/10 rounded-2xl flex items-center justify-center text-amber-500 shrink-0">
                    <i class="fas fa-file-shield text-2xl"></i>
                </div>
                <div>
                    <h5 class="text-sm font-extrabold text-navy-900 uppercase tracking-wider mb-2">Pengambilan Keputusan</h5>
                    <p class="text-xs text-slate-500 leading-relaxed">Penentuan prioritas perbaikan berbasis bobot skor kerusakan teknis untuk efisiensi anggaran daerah.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Statistik Section -->
    <section id="statistik" class="py-24 bg-white relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-6 md:px-8">
            <div class="text-center mb-20 reveal-up">
                <span class="text-gold-500 font-extrabold text-xs uppercase tracking-[0.3em] mb-3 block">RINGKASAN INFRASTRUKTUR</span>
                <h4 class="text-navy-900 font-black text-4xl md:text-5xl tracking-tight mb-4">Statistik GEO-SINFRA</h4>
                <div class="w-16 h-1.5 bg-gold-500 mx-auto rounded-full"></div>
            </div>
            
            <!-- Cards Grid -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 reveal-up">
                <!-- Card 1 -->
                <div class="bg-slate-50 p-8 rounded-3xl border border-slate-100 shadow-sm hover:shadow-md hover:bg-white transition-all duration-300 text-center relative group">
                    <div class="w-14 h-14 bg-navy-900/5 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:bg-navy-900 group-hover:text-gold-500 transition-all">
                        <i class="fas fa-database text-navy-800 text-xl group-hover:text-gold-500"></i>
                    </div>
                    <p class="text-4xl md:text-5xl font-black text-navy-900 leading-none mb-2"><?php echo e(number_format($stats['total'] ?? 0)); ?></p>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Total Titik Terdata</p>
                </div>

                <!-- Card 2 -->
                <div class="bg-slate-50 p-8 rounded-3xl border border-slate-100 shadow-sm hover:shadow-md hover:bg-white transition-all duration-300 text-center relative group">
                    <div class="w-14 h-14 bg-gold-500/5 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:bg-gold-500 group-hover:text-white transition-all">
                        <i class="fas fa-map-marked-alt text-gold-500 text-xl group-hover:text-white"></i>
                    </div>
                    <p class="text-4xl md:text-5xl font-black text-navy-900 leading-none mb-2"><?php echo e(number_format($stats['kecamatan'] ?? 0)); ?></p>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Kecamatan</p>
                </div>

                <!-- Card 3 -->
                <div class="bg-slate-50 p-8 rounded-3xl border border-slate-100 shadow-sm hover:shadow-md hover:bg-white transition-all duration-300 text-center relative group">
                    <div class="w-14 h-14 bg-red-500/5 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:bg-red-500 group-hover:text-white transition-all">
                        <i class="fas fa-exclamation-triangle text-red-500 text-xl group-hover:text-white"></i>
                    </div>
                    <p class="text-4xl md:text-5xl font-black text-navy-900 leading-none mb-2"><?php echo e(number_format($stats['rusak_berat'] ?? 0)); ?></p>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Kerusakan Berat</p>
                </div>

                <!-- Card 4 (AI Accent) -->
                <div class="bg-navy-900 text-white p-8 rounded-3xl shadow-xl shadow-navy-900/20 text-center relative overflow-hidden group hover:scale-[1.02] transition-all duration-300">
                    <div class="relative z-10">
                        <div class="w-14 h-14 bg-white/10 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:rotate-12 transition-all">
                            <i class="fas fa-robot text-gold-500 text-xl"></i>
                        </div>
                        <p class="text-4xl md:text-5xl font-black text-gold-500 leading-none mb-2"><?php echo e($stats['akurasi_ai'] ?? 0); ?>%</p>
                        <p class="text-[10px] font-bold text-slate-300 uppercase tracking-widest">Akurasi Model AI</p>
                    </div>
                    <i class="fas fa-brain absolute -right-6 -bottom-6 text-white/5 text-[120px]"></i>
                </div>
            </div>

            <!-- Detailed Grid Statistics -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mt-12 reveal-up">
                <!-- Sebaran Kecamatan -->
                <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-xl shadow-slate-900/5">
                    <div class="flex items-center gap-3 mb-8">
                        <div class="w-2.5 h-6 bg-gold-500 rounded-full"></div>
                        <h5 class="text-sm font-extrabold text-navy-900 uppercase tracking-wider">Kepadatan Titik Data per Wilayah</h5>
                    </div>
                    <div class="space-y-5">
                        <?php $__currentLoopData = $sebaranKecamatan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $nama => $count): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div>
                                <div class="flex justify-between text-xs font-bold uppercase tracking-wider mb-2">
                                    <span class="text-slate-500"><?php echo e($nama ?: 'Wilayah Tidak Diketahui'); ?></span>
                                    <span class="text-navy-900 font-extrabold"><?php echo e($count); ?> Titik</span>
                                </div>
                                <div class="w-full bg-slate-100 h-3 rounded-full overflow-hidden">
                                    <div class="bg-gradient-to-r from-navy-800 to-gold-500 h-full rounded-full" style="width: <?php echo e($stats['total'] > 0 ? ($count / $stats['total'] * 100) : 0); ?>%"></div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>

                <!-- Highlight Card -->
                <div class="bg-[#0f0e2c] p-10 rounded-3xl shadow-2xl relative overflow-hidden flex flex-col justify-between text-white border border-white/5">
                    <div class="grid-pattern"></div>
                    <div class="relative z-10">
                        <span class="text-gold-500 font-extrabold text-[10px] uppercase tracking-[0.3em] mb-2 block">KATEGORI DOMINAN</span>
                        <h5 class="text-4xl font-black uppercase tracking-tight mb-4"><?php echo e($topKategori); ?></h5>
                        <p class="text-slate-400 text-xs leading-relaxed max-w-sm mb-6">
                            Kategori infrastruktur ini memiliki jumlah laporan tertinggi di sistem GIS dan menjadi perhatian utama dalam proses monitoring pemeliharaan.
                        </p>
                    </div>
                    <div class="relative z-10 flex items-baseline gap-2">
                        <span class="text-6xl font-black text-gold-500"><?php echo e(number_format($topKategoriCount)); ?></span>
                        <span class="text-slate-300 font-extrabold text-xs uppercase tracking-wider">Aset Teridentifikasi</span>
                    </div>
                    <i class="fas fa-chart-pie absolute -right-6 -bottom-6 text-white/5 text-[150px]"></i>
                </div>
            </div>

            <!-- Table Ringkasan Wilayah -->
            <div class="mt-12 bg-white rounded-3xl border border-slate-100 shadow-xl shadow-slate-900/5 overflow-hidden reveal-up">
                <div class="p-8 border-b border-slate-50 flex items-center gap-3">
                    <div class="w-2.5 h-6 bg-navy-900 rounded-full"></div>
                    <h5 class="text-sm font-extrabold text-navy-900 uppercase tracking-wider">Ringkasan Keparahan Kondisi</h5>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-100">
                                <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Kecamatan</th>
                                <th class="px-8 py-5 text-[10px] font-black text-navy-900 uppercase tracking-widest text-center">Total Aset</th>
                                <th class="px-8 py-5 text-[10px] font-black text-emerald-500 uppercase tracking-widest text-center">Kondisi Baik</th>
                                <th class="px-8 py-5 text-[10px] font-black text-amber-500 uppercase tracking-widest text-center">Kondisi Sedang</th>
                                <th class="px-8 py-5 text-[10px] font-black text-red-500 uppercase tracking-widest text-center">Rusak Berat</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <?php $__currentLoopData = $kondisiWilayah; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="hover:bg-slate-50/50 transition-all">
                                    <td class="px-8 py-5 text-sm font-bold text-navy-900"><?php echo e($item['nama'] ?: 'Lainnya'); ?></td>
                                    <td class="px-8 py-5 text-center text-sm font-black text-navy-900"><?php echo e($item['total']); ?></td>
                                    <td class="px-8 py-5 text-center text-sm font-semibold text-emerald-600"><?php echo e($item['baik']); ?></td>
                                    <td class="px-8 py-5 text-center text-sm font-semibold text-amber-600"><?php echo e($item['rusak_sedang']); ?></td>
                                    <td class="px-8 py-5 text-center">
                                        <span class="inline-block px-3.5 py-1.5 bg-red-500/10 text-red-500 rounded-full text-[10px] font-black tracking-wide"><?php echo e($item['rusak_berat']); ?></span>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    <!-- Map Section -->
    <section id="peta" class="py-24 bg-slate-100 border-t border-slate-200/50 relative overflow-hidden">
        <div class="w-full px-4 md:px-12">
            <div class="max-w-7xl mx-auto mb-10 flex flex-col md:flex-row md:items-end justify-between gap-4 px-2">
                <div>
                    <span class="text-gold-500 font-extrabold text-xs uppercase tracking-[0.3em] mb-2 block">PETA INTERAKTIF</span>
                    <h4 class="text-navy-900 font-black text-3xl tracking-tight">Peta Sebaran</h4>
                </div>
                <p class="text-slate-500 text-sm max-w-md">
                    Gunakan peta GIS interaktif di bawah ini untuk melihat titik lokasi dan tingkat kerusakan infrastruktur permukiman secara real-time.
                </p>
            </div>

            <div class="relative bg-white rounded-[2.5rem] shadow-2xl overflow-hidden w-full" style="height: 850px;">
                <!-- Map Container -->
                <div id="map" class="absolute inset-0 z-0"></div>

                <!-- Custom Zoom Controls -->
                <div class="absolute top-6 left-6 z-[9999] flex flex-col gap-2 pointer-events-auto">
                    <button onclick="map.zoomIn()" class="w-12 h-12 bg-[#0f0e2c]/90 backdrop-blur-xl rounded-2xl border border-white/10 shadow-2xl flex items-center justify-center text-white hover:text-gold-500 hover:bg-[#1e1b4b] transition-all group">
                        <i class="fas fa-plus text-xs group-hover:scale-110 transition-transform"></i>
                    </button>
                    <button onclick="map.zoomOut()" class="w-12 h-12 bg-[#0f0e2c]/90 backdrop-blur-xl rounded-2xl border border-white/10 shadow-2xl flex items-center justify-center text-white hover:text-gold-500 hover:bg-[#1e1b4b] transition-all group">
                        <i class="fas fa-minus text-xs group-hover:scale-110 transition-transform"></i>
                    </button>
                </div>

                <!-- FLOATING UI WIDGETS -->

                <!-- Top Right Dropdowns (Category & District Filter) -->
                <div class="absolute top-6 right-6 z-[9999] flex flex-col gap-3 w-64 pointer-events-auto">
                    <!-- Category Selector -->
                    <div class="relative w-full z-50">
                        <button onclick="toggleMenu('kategori-menu')" class="w-full bg-[#0f0e2c]/90 backdrop-blur-xl border border-white/10 text-white px-5 py-4 rounded-2xl flex justify-between items-center shadow-2xl hover:bg-[#1e1b4b] transition-all">
                            <div class="flex items-center gap-3">
                                <i class="fas fa-layer-group text-xs text-gold-500"></i>
                                <span class="text-[10px] font-bold uppercase tracking-wider">Kategori Objek</span>
                            </div>
                            <i class="fas fa-chevron-down text-xs text-slate-400"></i>
                        </button>
                        <div id="kategori-menu" class="hidden absolute top-full mt-2 w-full bg-[#0f0e2c]/95 backdrop-blur-2xl rounded-2xl p-2.5 shadow-2xl border border-white/10">
                            <!-- Select All Category -->
                            <label class="flex items-center justify-between p-3 hover:bg-white/5 rounded-xl cursor-pointer transition-all border-b border-white/5 mb-1.5 pb-2.5">
                                <span class="text-[10px] font-black text-gold-500 uppercase tracking-wider">Pilih Semua</span>
                                <input type="checkbox" id="check-all-categories" class="w-4.5 h-4.5 rounded border-slate-600 bg-transparent text-gold-500 focus:ring-0" checked>
                            </label>
                            <label class="flex items-center justify-between p-3 hover:bg-white/5 rounded-xl cursor-pointer transition-all">
                                <span class="text-[10px] font-bold text-slate-200 uppercase tracking-wider">Jalan</span>
                                <input type="checkbox" class="filter-category w-4.5 h-4.5 rounded border-slate-600 bg-transparent text-gold-500 focus:ring-0" value="jalan" checked>
                            </label>
                            <label class="flex items-center justify-between p-3 hover:bg-white/5 rounded-xl cursor-pointer transition-all">
                                <span class="text-[10px] font-bold text-slate-200 uppercase tracking-wider">Titian</span>
                                <input type="checkbox" class="filter-category w-4.5 h-4.5 rounded border-slate-600 bg-transparent text-gold-500 focus:ring-0" value="titian" checked>
                            </label>
                            <label class="flex items-center justify-between p-3 hover:bg-white/5 rounded-xl cursor-pointer transition-all">
                                <span class="text-[10px] font-bold text-slate-200 uppercase tracking-wider">Jembatan</span>
                                <input type="checkbox" class="filter-category w-4.5 h-4.5 rounded border-slate-600 bg-transparent text-gold-500 focus:ring-0" value="jembatan" checked>
                            </label>
                            <label class="flex items-center justify-between p-3 hover:bg-white/5 rounded-xl cursor-pointer transition-all">
                                <span class="text-[10px] font-bold text-slate-200 uppercase tracking-wider">Sanitasi</span>
                                <input type="checkbox" class="filter-category w-4.5 h-4.5 rounded border-slate-600 bg-transparent text-gold-500 focus:ring-0" value="sanitasi" checked>
                            </label>
                        </div>
                    </div>

                    <!-- District Selector -->
                    <div class="relative w-full z-40">
                        <button onclick="toggleMenu('wilayah-menu')" class="w-full bg-[#0f0e2c]/90 backdrop-blur-xl border border-white/10 text-white px-5 py-4 rounded-2xl flex justify-between items-center shadow-2xl hover:bg-[#1e1b4b] transition-all">
                            <div class="flex items-center gap-3">
                                <i class="fas fa-map-location-dot text-xs text-gold-500"></i>
                                <span class="text-[10px] font-bold uppercase tracking-wider">Pilih Kecamatan</span>
                            </div>
                            <i class="fas fa-chevron-down text-xs text-slate-400"></i>
                        </button>
                        <div id="wilayah-menu" class="hidden absolute top-full mt-2 w-full bg-[#0f0e2c]/95 backdrop-blur-2xl rounded-2xl p-2.5 shadow-2xl border border-white/10 max-h-[300px] overflow-y-auto custom-scrollbar">
                            <!-- Select All Districts -->
                            <label class="flex items-center justify-between p-3 hover:bg-white/5 rounded-xl cursor-pointer transition-all border-b border-white/5 mb-1.5 pb-2.5">
                                <span class="text-[10px] font-black text-gold-500 uppercase tracking-wider">Pilih Semua</span>
                                <input type="checkbox" id="check-all-districts" class="w-4.5 h-4.5 rounded border-slate-600 bg-transparent text-gold-500 focus:ring-0" checked>
                            </label>
                            <?php $kecColors = ['#6366f1', '#10b981', '#f59e0b', '#ef4444', '#06b6d4']; ?>
                            <?php $__currentLoopData = $semuaWilayah; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $wil): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <label class="flex items-center justify-between p-3 hover:bg-white/5 rounded-xl cursor-pointer transition-all group">
                                <div class="flex items-center gap-3">
                                    <input type="checkbox" class="filter-district w-4.5 h-4.5 rounded border-slate-600 bg-transparent text-gold-500 focus:ring-0" value="<?php echo e($wil->id_kecamatan); ?>" checked>
                                    <span class="text-[10px] font-bold text-slate-200 uppercase tracking-wider"><?php echo e($wil->nama_kecamatan); ?></span>
                                </div>
                                <div class="w-2.5 h-2.5 rounded-full" style="background: <?php echo e($kecColors[$index % count($kecColors)]); ?>"></div>
                            </label>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>

                    <!-- Kelurahan Toggle Card -->
                    <div class="relative w-full z-30">
                        <label class="w-full bg-[#0f0e2c]/90 backdrop-blur-xl border border-white/10 text-white px-5 py-4 rounded-2xl flex justify-between items-center shadow-2xl hover:bg-[#1e1b4b] cursor-pointer transition-all">
                            <div class="flex items-center gap-3">
                                <input type="checkbox" id="toggle-kelurahan-lines" class="w-4.5 h-4.5 rounded border-slate-600 bg-transparent text-gold-500 focus:ring-0" checked>
                                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-200">Kelurahan</span>
                            </div>
                            <i class="fas fa-home text-gold-500 text-xs"></i>
                        </label>
                    </div>
                </div>

                <!-- Bottom Left Stats Box Widget (Glassmorphic Dark UI) -->
                <div class="absolute bottom-6 left-6 z-[9999] bg-[#0f0e2c]/90 backdrop-blur-xl rounded-3xl p-6 text-white w-72 shadow-2xl border border-white/10 pointer-events-auto">
                    <div class="flex justify-between items-center bg-white/5 -mt-3 -mx-3 mb-5 p-3 rounded-2xl">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-chart-pie text-gold-500 text-xs"></i>
                            <span class="text-[10px] font-extrabold uppercase tracking-widest text-slate-200">Statistik Filter</span>
                        </div>
                        <i class="fas fa-chevron-up text-slate-400 text-xs"></i>
                    </div>
                    
                    <div class="space-y-3.5">
                        <div class="flex justify-between items-center">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Total Titik</span>
                            <span id="stat-total" class="bg-indigo-500/20 text-[#6366f1] px-3.5 py-1 rounded-xl text-xs font-black min-w-[55px] text-center border border-indigo-500/20">0</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <div class="flex items-center gap-2.5">
                                <div class="w-3 h-3 rounded-full bg-emerald-500 shadow-md shadow-emerald-500/20"></div>
                                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-300">Baik</span>
                            </div>
                            <span id="stat-baik" class="bg-emerald-500/20 text-emerald-400 px-3.5 py-1 rounded-xl text-xs font-black min-w-[55px] text-center border border-emerald-500/20">0</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <div class="flex items-center gap-2.5">
                                <div class="w-3 h-3 rounded-full bg-amber-500 shadow-md shadow-amber-500/20"></div>
                                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-300">Sedang</span>
                            </div>
                            <span id="stat-sedang" class="bg-amber-500/20 text-amber-400 px-3.5 py-1 rounded-xl text-xs font-black min-w-[55px] text-center border border-amber-500/20">0</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <div class="flex items-center gap-2.5">
                                <div class="w-3 h-3 rounded-full bg-red-500 shadow-md shadow-red-500/20"></div>
                                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-300">Rusak Berat</span>
                            </div>
                            <span id="stat-berat" class="bg-red-500/20 text-red-400 px-3.5 py-1 rounded-xl text-xs font-black min-w-[55px] text-center border border-red-500/20">0</span>
                        </div>
                    </div>
                </div>

                <!-- Bottom Right Basemap Selector (Circular Glassmorphic UI) -->
                <div class="absolute bottom-6 right-6 z-[9999] pointer-events-auto">
                    <button onclick="toggleMenu('layer-options')" class="w-14 h-14 bg-[#0f0e2c]/95 backdrop-blur-xl rounded-full flex items-center justify-center text-white/80 hover:text-gold-500 shadow-2xl border border-white/10 hover:scale-105 transition-all">
                        <i class="fas fa-layer-group text-xl"></i>
                    </button>
                    <div id="layer-options" class="hidden absolute bottom-[4.5rem] right-0 w-48 bg-[#0f0e2c]/95 backdrop-blur-2xl rounded-2xl p-2.5 shadow-2xl border border-white/10 flex flex-col gap-1.5">
                        <span class="text-[8px] font-bold text-slate-400 uppercase tracking-widest px-3.5 mb-1">Gaya Basemap</span>
                        <button onclick="setBasemap('google')" class="basemap-btn bg-white/10 text-white w-full px-4 py-3 rounded-xl text-[9px] font-black uppercase tracking-wider hover:bg-white/20 transition-all text-left">Default</button>
                        <button onclick="setBasemap('satelit')" class="basemap-btn text-slate-400 w-full px-4 py-3 rounded-xl text-[9px] font-black uppercase tracking-wider hover:bg-white/10 hover:text-white transition-all text-left">Satelit</button>
                        <button onclick="setBasemap('dark')" class="basemap-btn text-slate-400 w-full px-4 py-3 rounded-xl text-[9px] font-black uppercase tracking-wider hover:bg-white/10 hover:text-white transition-all text-left">Gelap</button>
                        <button onclick="setBasemap('greyscale')" class="basemap-btn text-slate-400 w-full px-4 py-3 rounded-xl text-[9px] font-black uppercase tracking-wider hover:bg-white/10 hover:text-white transition-all text-left">Abu-abu</button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-navy-950 py-16 text-white relative">
        <div class="max-w-7xl mx-auto px-6 md:px-8 flex flex-col md:flex-row justify-between items-center gap-8 relative z-10">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-white/5 rounded-2xl flex items-center justify-center text-gold-500 shadow-md">
                    <i class="fas fa-globe-asia text-xl"></i>
                </div>
                <div>
                    <h4 class="text-xl font-black uppercase tracking-tight text-white leading-none mb-1">GEO-SINFRA</h4>
                    <span class="text-[9px] font-bold tracking-widest text-slate-500 uppercase">Sistem Pemetaan Infrastruktur Permukiman Kota Banjarmasin</span>
                </div>
            </div>
            <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">&copy; <?php echo e(date('Y')); ?> DISPERKIM KOTA BANJARMASIN. ALL RIGHTS RESERVED.</p>
        </div>
    </footer>

    <!-- Back to Top Button -->
    <div id="backToTop" class="fixed bottom-6 right-6 w-12 h-12 bg-gold-500 text-white rounded-full flex items-center justify-center cursor-pointer shadow-lg z-[4998] hover:scale-105 hover:bg-gold-600 transition-all opacity-0 invisible" onclick="window.scrollTo({top: 0, behavior: 'smooth'})">
        <i class="fas fa-chevron-up"></i>
    </div>

    <!-- Scripts -->
    <script>
        // Preloader Logic
        window.addEventListener('load', () => {
            const preloader = document.getElementById('preloader');
            setTimeout(() => {
                preloader.classList.add('fade-out');
                document.body.classList.add('loaded');
            }, 1000);
        });

        // Mobile Menu Trigger
        function toggleMobileMenu() {
            const el = document.getElementById('mobile-menu');
            el.classList.toggle('hidden');
        }

        let activeKelurahanId = null;

        const dataInfra = <?php echo json_encode($dataInfrastruktur, 15, 512) ?>;
        const dataWilayah = <?php echo json_encode($semuaWilayah, 15, 512) ?>;
        const dataKelurahan = <?php echo json_encode($dataKelurahan, 15, 512) ?>;
        const map = L.map('map', { zoomControl: false }).setView([-3.316694, 114.590111], 13);
        
        const googleStreets = L.tileLayer('http://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}', { maxZoom: 20, subdomains:['mt0','mt1','mt2','mt3'] }).addTo(map);
        const satelit = L.tileLayer('http://{s}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}', { maxZoom: 20, subdomains:['mt0','mt1','mt2','mt3'] });
        const darkMap = L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', { maxZoom: 20 });
        const greyMap = L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', { maxZoom: 20 });
        
        const markersLayer = L.layerGroup().addTo(map);
        const polygonsLayer = L.layerGroup().addTo(map);

        // Geocoding Colors
        const kecColors = ['#6366f1', '#10b981', '#f59e0b', '#ef4444', '#06b6d4'];

        // Custom Dot Marker styles
        const createIcon = (type, prioritas) => {
            let color = '#3b82f6';
            if (prioritas === 'Baik') color = '#10b981';
            else if (prioritas === 'Rusak Sedang') color = '#f59e0b';
            else if (prioritas === 'Rusak Berat') color = '#ef4444';
            
            return L.divIcon({
                className: 'custom-dot-marker',
                html: `
                    <div class="w-5 h-5 rounded-full border-3 border-white shadow-xl transition-all hover:scale-150 relative flex items-center justify-center" style="background-color: ${color}">
                        <div class="w-1.5 h-1.5 rounded-full bg-white/40"></div>
                    </div>
                `,
                iconSize: [20, 20],
                iconAnchor: [10, 10],
                popupAnchor: [0, -10]
            });
        };

        // Custom Kelurahan Summary Icon
        // Custom Kelurahan Summary Icon
        const createKelurahanSummaryIcon = (count) => {
            return L.divIcon({
                className: 'custom-kelurahan-summary-marker',
                html: `
                    <div class="flex flex-col items-center justify-center cursor-pointer group">
                        <div class="h-10 w-10 rounded-full bg-[#0f0e2c]/90 backdrop-blur-md border-2 border-gold-500 text-gold-500 font-black text-xs flex items-center justify-center shadow-2xl hover:scale-110 hover:bg-[#1e1b4b] hover:border-white transition-all">
                            ${count}
                        </div>
                    </div>
                `,
                iconSize: [40, 40],
                iconAnchor: [20, 20],
                popupAnchor: [0, -20]
            });
        };

        function applyFilters() {
            markersLayer.clearLayers();
            polygonsLayer.clearLayers();

            const checkedCategories = Array.from(document.querySelectorAll('.filter-category:checked')).map(el => el.value);
            const checkedDistricts = Array.from(document.querySelectorAll('.filter-district:checked')).map(el => el.value);
            const showKelurahan = document.getElementById('toggle-kelurahan-lines').checked;
            
            // Filter active data
            const filteredInfra = dataInfra.filter(item => {
                const jenisLower = (item.jenis || '').toLowerCase().trim();
                const idKecamatanStr = (item.id_kecamatan || '').toString().trim();
                return jenisLower && item.latitude && item.longitude && 
                       checkedCategories.includes(jenisLower) && 
                       checkedDistricts.includes(idKecamatanStr);
            });

            // 1. Draw Kecamatan Polygons
            let singleDistrictLayer = null;
            dataWilayah.forEach((wil, index) => {
                if (checkedDistricts.includes(wil.id_kecamatan.toString()) && wil.geometri) {
                    try {
                        const geoData = typeof wil.geometri === 'string' ? JSON.parse(wil.geometri) : wil.geometri;
                        const color = wil.warna || kecColors[index % kecColors.length];
                        
                        const layer = L.geoJSON(geoData, {
                            style: {
                                color: color,
                                weight: 2,
                                fillOpacity: 0.08,
                                dashArray: '4'
                            }
                        }).bindTooltip(wil.nama_kecamatan, { sticky: true }).addTo(polygonsLayer);

                        if (checkedDistricts.length === 1 && !activeKelurahanId) {
                            singleDistrictLayer = layer;
                        }
                    } catch (e) {
                        console.error("Error parsing geometry for " + wil.nama_kecamatan, e);
                    }
                }
            });

            if (singleDistrictLayer) {
                map.fitBounds(singleDistrictLayer.getBounds(), { padding: [40, 40], maxZoom: 15 });
            }

            // 1.5 Draw Kelurahan Polygons/Boundaries
            dataKelurahan.forEach(kel => {
                if (kel.geometri) {
                    // Kelurahan boundaries are shown if check-box is checked OR if it's currently drilled down
                    if (showKelurahan || activeKelurahanId == kel.id_kelurahan) {
                        try {
                            const geoData = typeof kel.geometri === 'string' ? JSON.parse(kel.geometri) : kel.geometri;
                            
                            const layer = L.geoJSON(geoData, {
                                filter: function(feature) {
                                    return feature.geometry.type !== 'Point';
                                },
                                style: {
                                    color: activeKelurahanId == kel.id_kelurahan ? '#c5a059' : '#ffffff',
                                    weight: activeKelurahanId == kel.id_kelurahan ? 2.5 : 1.5,
                                    opacity: activeKelurahanId == kel.id_kelurahan ? 0.95 : 0.55,
                                    fillOpacity: activeKelurahanId == kel.id_kelurahan ? 0.12 : 0.04,
                                    fillColor: activeKelurahanId == kel.id_kelurahan ? '#c5a059' : '#ffffff',
                                    dashArray: activeKelurahanId == kel.id_kelurahan ? '0' : '3, 6'
                                }
                            }).bindTooltip(`<div class="text-[9px] font-bold text-navy-900 leading-none">Kel. ${kel.nama_kelurahan}</div>`, { sticky: true }).addTo(polygonsLayer);

                            if (activeKelurahanId == kel.id_kelurahan) {
                                map.fitBounds(layer.getBounds(), { padding: [50, 50], maxZoom: 16 });
                            }
                        } catch (e) {
                            console.error("Error parsing geometry for kelurahan " + kel.nama_kelurahan, e);
                        }
                    }
                }
            });

            // 2. Draw Aset Markers (Grouping or Individual)
            let countTotal = 0;
            let countBaik = 0;
            let countSedang = 0;
            let countBerat = 0;

            if (activeKelurahanId) {
                const activeAssets = filteredInfra.filter(i => i.id_kelurahan == activeKelurahanId);
                countTotal = activeAssets.length;
                countBaik = activeAssets.filter(i => i.label_prioritas === 'Baik').length;
                countSedang = activeAssets.filter(i => i.label_prioritas === 'Rusak Sedang').length;
                countBerat = activeAssets.filter(i => i.label_prioritas === 'Rusak Berat').length;
            } else {
                countTotal = filteredInfra.length;
                countBaik = filteredInfra.filter(i => i.label_prioritas === 'Baik').length;
                countSedang = filteredInfra.filter(i => i.label_prioritas === 'Rusak Sedang').length;
                countBerat = filteredInfra.filter(i => i.label_prioritas === 'Rusak Berat').length;
            }

            dataKelurahan.forEach(kel => {
                if (checkedDistricts.includes(kel.id_kecamatan?.toString()) && kel.geometri) {
                    const kelAssets = filteredInfra.filter(i => i.id_kelurahan == kel.id_kelurahan);
                    if (kelAssets.length > 0) {
                        if (activeKelurahanId == kel.id_kelurahan) {
                            // RENDER DETAILED INDIVIDUAL MARKERS FOR THIS ACTIVE KELURAHAN
                            kelAssets.forEach(item => {
                                const marker = L.marker([parseFloat(item.latitude), parseFloat(item.longitude)], { 
                                    icon: createIcon(item.jenis || 'Infrastruktur', item.label_prioritas) 
                                });
                                
                                let imagePath = item.foto_terbaru || '';
                                if(imagePath && !imagePath.includes('infrastruktur/')) {
                                    imagePath = 'infrastruktur/' + imagePath;
                                }
                                imagePath = imagePath.replace(/\\/g, '/');
                                
                                let finalUrl = '';
                                if (imagePath) {
                                    finalUrl = `<?php echo e(asset('storage')); ?>/${imagePath}`;
                                } else {
                                    const type = item.jenis ? item.jenis.toLowerCase() : 'jalan';
                                    let typeStr = 'jalan';
                                    if (type.includes('titian')) typeStr = 'titian';
                                    else if (type.includes('jembatan')) typeStr = 'jembatan';
                                    
                                    let condStr = 'baik';
                                    const prioritas = item.label_prioritas ? item.label_prioritas.toLowerCase() : 'baik';
                                    if (prioritas.includes('berat')) condStr = 'rusak_berat';
                                    else if (prioritas.includes('sedang')) condStr = 'rusak_sedang';

                                    finalUrl = `<?php echo e(asset('')); ?>dummy_${typeStr}_${condStr}.jpg`;
                                }

                                const imgTag = `<img src="${finalUrl}" class="w-full h-24 object-cover rounded-xl shadow-sm mb-2" onerror="this.style.display='none'">`;
                                
                                const popupContent = `
                                    <div class="p-1 min-w-[180px] font-sans">
                                        ${imgTag}
                                        <div class="flex items-center gap-1.5 mb-1.5">
                                            <div class="px-2 py-0.5 bg-gold-500 text-white text-[8px] font-bold uppercase rounded-md">${item.jenis || 'Infrastruktur'}</div>
                                            <div class="px-2 py-0.5 bg-white/10 text-slate-300 text-[8px] font-medium uppercase rounded-md">${item.label_prioritas || 'N/A'}</div>
                                        </div>
                                        <h6 class="text-white font-extrabold text-xs uppercase mb-1 leading-tight truncate max-w-[170px]">${item.nama_objek || 'Aset Tanpa Nama'}</h6>
                                        <p class="text-slate-400 text-[9px] mb-2 truncate max-w-[170px]"><i class="fas fa-map-marker-alt mr-1 text-gold-500"></i> ${item.nama_kecamatan || '-'}</p>
                                        <div class="grid grid-cols-2 gap-2 border-t pt-2 border-white/10">
                                            <div>
                                                <p class="text-[7px] font-bold text-slate-500 uppercase leading-none mb-0.5">Skor AI</p>
                                                <p class="text-xs font-black text-[#6366f1] leading-none">${item.skor_dt ? item.skor_dt + '%' : '-'}</p>
                                            </div>
                                            <div>
                                                <p class="text-[7px] font-bold text-slate-500 uppercase leading-none mb-0.5">Metode</p>
                                                <p class="text-[9px] font-black text-emerald-500 uppercase leading-none">Hybrid SPK</p>
                                            </div>
                                        </div>
                                    </div>
                                `;
                                
                                marker.bindPopup(popupContent, {
                                    className: 'custom-leaflet-popup'
                                }).addTo(markersLayer);
                            });
                        } else {
                            // RENDER SUMMARY BADGES FOR ALL OTHER KELURAHANS
                            try {
                                const geoData = typeof kel.geometri === 'string' ? JSON.parse(kel.geometri) : kel.geometri;
                                const center = L.geoJSON(geoData).getBounds().getCenter();

                                const summaryMarker = L.marker(center, {
                                    icon: createKelurahanSummaryIcon(kelAssets.length)
                                });

                                summaryMarker.on('click', () => {
                                    activeKelurahanId = kel.id_kelurahan;
                                    applyFilters();
                                }).addTo(markersLayer);
                            } catch(e) {
                                console.error("Error drawing summary marker for " + kel.nama_kelurahan, e);
                            }
                        }
                    }
                }
            });

            // Dom updates
            document.getElementById('stat-total').innerText = countTotal;
            document.getElementById('stat-baik').innerText = countBaik;
            document.getElementById('stat-sedang').innerText = countSedang;
            document.getElementById('stat-berat').innerText = countBerat;

            // Update Select All Checkbox state
            const checkAllCategories = document.getElementById('check-all-categories');
            const allCats = document.querySelectorAll('.filter-category');
            const allCatsChecked = document.querySelectorAll('.filter-category:checked');
            if (checkAllCategories) {
                checkAllCategories.checked = allCats.length === allCatsChecked.length;
            }

            // Update Select All Districts state
            const checkAllDistricts = document.getElementById('check-all-districts');
            const allDists = document.querySelectorAll('.filter-district');
            const allDistsChecked = document.querySelectorAll('.filter-district:checked');
            if (checkAllDistricts) {
                checkAllDistricts.checked = allDists.length === allDistsChecked.length;
            }
        }

        // Event listeners are registered below

        // Select All Category Listener
        const checkAllCategories = document.getElementById('check-all-categories');
        if (checkAllCategories) {
            checkAllCategories.addEventListener('change', function() {
                const checked = this.checked;
                document.querySelectorAll('.filter-category').forEach(el => {
                    el.checked = checked;
                });
                applyFilters();
            });
        }

        // Select All Districts Listener
        const checkAllDistricts = document.getElementById('check-all-districts');
        if (checkAllDistricts) {
            checkAllDistricts.addEventListener('change', function() {
                const checked = this.checked;
                document.querySelectorAll('.filter-district').forEach(el => {
                    el.checked = checked;
                });
                applyFilters();
            });
        }

        // Listen for all input filters including the single Kelurahan toggle checkbox
        document.querySelectorAll('.filter-category, .filter-district').forEach(el => el.addEventListener('change', applyFilters));
        const toggleKelurahanLines = document.getElementById('toggle-kelurahan-lines');
        if (toggleKelurahanLines) {
            toggleKelurahanLines.addEventListener('change', applyFilters);
        }

        function setBasemap(type) {
            document.querySelectorAll('.basemap-btn').forEach(btn => {
                btn.classList.remove('bg-white/10', 'text-white');
                btn.classList.add('text-slate-400');
            });
            event.target.classList.add('bg-white/10', 'text-white');
            event.target.classList.remove('text-slate-400');

            map.removeLayer(googleStreets);
            map.removeLayer(satelit);
            map.removeLayer(darkMap);
            map.removeLayer(greyMap);

            if (type === 'satelit') { 
                map.addLayer(satelit); 
            } else if (type === 'dark') {
                map.addLayer(darkMap);
            } else if (type === 'greyscale') {
                map.addLayer(greyMap);
            } else { 
                map.addLayer(googleStreets); 
            }
        }

        function toggleMenu(id) {
            if (id !== 'layer-options') document.getElementById('layer-options').classList.add('hidden');
            if (id !== 'kategori-menu') document.getElementById('kategori-menu').classList.add('hidden');
            if (id !== 'wilayah-menu') document.getElementById('wilayah-menu').classList.add('hidden');
            
            const panel = document.getElementById(id);
            panel.classList.toggle('hidden');
        }

        // Scroll reveal animation triggers
        function reveal() {
            var reveals = document.querySelectorAll(".reveal-up");
            for (var i = 0; i < reveals.length; i++) {
                var windowHeight = window.innerHeight;
                var elementTop = reveals[i].getBoundingClientRect().top;
                var elementVisible = 100;
                if (elementTop < windowHeight - elementVisible) {
                    reveals[i].classList.add("active");
                }
            }
        }
        window.addEventListener("scroll", reveal);

        // Back to Top trigger
        window.addEventListener('scroll', () => {
            const btt = document.getElementById('backToTop');
            if (window.scrollY > 400) {
                btt.classList.remove('opacity-0', 'invisible');
                btt.classList.add('opacity-100', 'visible');
            } else {
                btt.classList.remove('opacity-100', 'visible');
                btt.classList.add('opacity-0', 'invisible');
            }
        });

        applyFilters();
        reveal();
    </script>
</body>
</html><?php /**PATH C:\laragon1\laragon\www\geo-sinfra\resources\views/landing.blade.php ENDPATH**/ ?>