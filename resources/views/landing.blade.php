<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GEO-SINFRA</title>
    <meta name="description" content="GEO-SINFRA adalah Sistem Pemetaan Infrastruktur Permukiman Kota Banjarmasin berbasis Web GIS dan Kecerdasan Buatan (AI) untuk monitoring, pelaporan, dan klasifikasi kerusakan infrastruktur.">
    <meta name="keywords" content="GIS, Pemetaan, Infrastruktur, Banjarmasin, Artificial Intelligence, SINFRA, Dinas PUPR, Jalan, Jembatan">
    <meta name="author" content="Pemerintah Kota Banjarmasin">
    <link rel="icon" href="{{ asset('logo_geo-sinfra.png') }}" type="image/png">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    
    <!-- MarkerCluster CSS & JS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.Default.css" />
    <script src="https://unpkg.com/leaflet.markercluster@1.5.3/dist/leaflet.markercluster.js"></script>

    <!-- Leaflet Heatmap -->
    <script src="https://unpkg.com/leaflet.heat@0.2.0/dist/leaflet-heat.js"></script>

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
        *, *::before, *::after { box-sizing: border-box; }
        html { overflow-x: hidden; width: 100%; scroll-behavior: smooth; }
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background-color: #f8fafc; 
            color: #0f172a; 
            opacity: 0; 
            transition: opacity 0.8s ease-in;
            overflow-x: hidden;
            width: 100%;
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
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
            background-image: url('{{ asset('gambar_landing_page.jpeg') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            overflow: hidden;
        }
        .hero-premium::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at 80% 20%, rgba(99, 102, 241, 0.6) 0%, transparent 50%),
                        radial-gradient(circle at 20% 80%, rgba(197, 160, 89, 0.6) 0%, transparent 50%),
                        rgba(7, 6, 23, 0.75);
            pointer-events: none;
            z-index: 0;
        }
        .hero-premium::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, transparent 50%, rgba(7, 6, 23, 0.95) 100%);
            pointer-events: none;
            z-index: 1;
        }
        .hero-premium > * {
            position: relative;
            z-index: 2;
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
        .custom-leaflet-popup .leaflet-popup-content {
            margin: 0 !important;
            width: auto !important;
        }
        .custom-leaflet-popup .leaflet-popup-close-button {
            color: #ffffff !important;
            background: #ef4444 !important;
            border-radius: 50% !important;
            width: 24px !important;
            height: 24px !important;
            line-height: 24px !important;
            text-align: center !important;
            padding: 0 !important;
            top: -8px !important;
            right: -8px !important;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.5) !important;
            z-index: 100 !important;
            font-weight: bold !important;
        }
        .custom-leaflet-popup .leaflet-popup-close-button:hover {
            background: #dc2626 !important;
            color: white !important;
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
            padding: 4px 12px !important;
            font-size: 8px !important;
            font-family: 'Plus Jakarta Sans', sans-serif !important;
            font-weight: 500 !important;
            letter-spacing: 0.025em !important;
            box-shadow: -5px -5px 15px rgba(0, 0, 0, 0.2) !important;
            max-width: 65vw !important;
            white-space: normal !important;
            word-wrap: break-word !important;
            line-height: 1.4 !important;
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
        /* Dynamic Navbar Styles */
        #navbar {
            transition: all 0.4s ease-in-out;
        }
        
        #navbar.nav-transparent {
            background: rgba(15, 14, 44, 0.2);
            backdrop-filter: blur(8px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            height: 80px;
        }
        #navbar.nav-transparent .nav-brand, 
        #navbar.nav-transparent .nav-link {
            color: #ffffff;
        }
        #navbar.nav-transparent .nav-link:hover {
            color: #c5a059;
        }
        #navbar.nav-transparent .nav-divider {
            background: rgba(255, 255, 255, 0.2);
        }
        
        #navbar.nav-scrolled {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(226, 232, 240, 1);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
            height: 70px;
        }
        #navbar.nav-scrolled .nav-brand {
            color: #0f0e2c;
        }
        #navbar.nav-scrolled .nav-link {
            color: #475569;
        }
        #navbar.nav-scrolled .nav-link:hover {
            color: #c5a059;
        }
        #navbar.nav-scrolled .nav-divider {
            background: #cbd5e1;
        }

        /* Desktop responsive font improvements */
        @media (min-width: 1024px) {
            .hero-premium { min-height: 90vh; }
        }
        @media (min-width: 1280px) {
            .hero-premium { min-height: 85vh; }
        }
        
        /* Ensure all sections stay within viewport width */
        section, footer, nav {
            max-width: 100vw;
        }
    </style>
</head>
<body class="antialiased font-sans bg-slate-50 text-slate-800">

    <!-- Preloader -->
    <div id="preloader">
        <div class="loader-glow mb-4">
            <i class="fas fa-globe-asia text-[#c5a059] text-4xl animate-pulse"></i>
        </div>
        <h2 class="text-white font-extrabold tracking-[0.6em] uppercase text-sm">GEO-SINFRA</h2>
        <p class="text-slate-400 text-xs tracking-widest mt-2 uppercase">Sistem Pemetaan Infrastruktur Permukiman Kota Banjarmasin</p>
    </div>
 
    <!-- Header / Navbar -->
    <nav id="navbar" class="fixed top-0 left-0 w-full flex items-center z-[5000] nav-transparent">
        <div class="max-w-7xl mx-auto px-6 md:px-8 w-full flex justify-between items-center">
            <!-- Brand -->
            <div class="flex items-center">
                <a href="#" class="h-16 flex items-center gap-2.5">
                    <img src="{{ asset('logo_geo-sinfra.png') }}" class="h-9 md:h-10 w-auto object-contain drop-shadow-md transition-all duration-300" alt="Logo Geo-Sinfra">
                    <h2 class="nav-brand text-lg font-extrabold tracking-tighter uppercase leading-none transition-colors duration-300">GEO-SINFRA</h2>
                </a>
            </div>
            
            <!-- Navbar Actions -->
            <div class="flex items-center gap-2 md:gap-4">
                <a href="#peta" class="nav-link text-xs font-bold transition-colors uppercase tracking-wider hidden md:block">Peta</a>
                <a href="#statistik" class="nav-link text-xs font-bold transition-colors uppercase tracking-wider hidden md:block">Statistik</a>
                
                <button onclick="document.getElementById('modal-lapor').classList.remove('hidden')" class="bg-gradient-to-r from-red-600 to-red-500 hover:from-red-500 hover:to-red-400 text-white px-3 md:px-4 py-2 rounded-lg text-[10px] md:text-xs font-black transition-all shadow-lg shadow-red-500/30 hover:shadow-red-500/50 hover:-translate-y-0.5 flex items-center gap-2 uppercase tracking-wider border border-red-500/50">
                    <i class="fas fa-bullhorn animate-pulse"></i> <span class="hidden sm:inline">Lapor Kerusakan</span><span class="sm:hidden">Lapor</span>
                </button>

                <div class="nav-divider w-px h-5 mx-1 md:mx-2 hidden md:block transition-colors duration-300"></div>
                
                <a href="{{ url('/login') }}" class="bg-navy-900 text-gold-500 hover:bg-gold-500 hover:text-white px-3 md:px-4 py-2 rounded-lg text-[10px] md:text-xs font-bold transition-all shadow-sm hidden md:flex items-center gap-2 uppercase tracking-wider">
                    <i class="fas fa-lock"></i> <span>Login</span>
                </a>

                <!-- Mobile Hamburger Menu -->
                <div class="relative md:hidden ml-1">
                    <button onclick="document.getElementById('mobile-dropdown').classList.toggle('hidden')" class="bg-[#0f0e2c] text-gold-500 hover:bg-gold-500 hover:text-white w-9 h-9 flex items-center justify-center rounded-xl text-sm transition-all shadow-md border border-white/10">
                        <i class="fas fa-bars"></i>
                    </button>
                    <!-- Dropdown -->
                    <div id="mobile-dropdown" class="hidden absolute right-0 mt-3 w-48 bg-white rounded-xl shadow-2xl py-2 border border-slate-100 z-[6000]">
                        <a href="#peta" onclick="document.getElementById('mobile-dropdown').classList.add('hidden')" class="flex items-center px-4 py-3 text-xs font-black text-navy-900 hover:bg-slate-50 border-b border-slate-100 uppercase tracking-widest">
                            <i class="fas fa-map-marked-alt w-6 text-gold-500 text-center"></i> Peta
                        </a>
                        <a href="#statistik" onclick="document.getElementById('mobile-dropdown').classList.add('hidden')" class="flex items-center px-4 py-3 text-xs font-black text-navy-900 hover:bg-slate-50 border-b border-slate-100 uppercase tracking-widest">
                            <i class="fas fa-chart-pie w-6 text-gold-500 text-center"></i> Statistik
                        </a>
                        <a href="{{ url('/login') }}" class="flex items-center px-4 py-3 text-xs font-black text-navy-900 hover:bg-slate-50 uppercase tracking-widest">
                            <i class="fas fa-lock w-6 text-gold-500 text-center"></i> Login
                        </a>
                    </div>
                </div>
                
                <!-- Click outside listener for dropdown -->
                <script>
                    document.addEventListener('click', function(event) {
                        const dropdown = document.getElementById('mobile-dropdown');
                        if (dropdown) {
                            const button = dropdown.previousElementSibling;
                            if (!dropdown.contains(event.target) && !button.contains(event.target) && !dropdown.classList.contains('hidden')) {
                                dropdown.classList.add('hidden');
                            }
                        }
                    });
                </script>
            </div>
        </div>
    </nav>


    <!-- Hero Section -->
    <section class="hero-premium py-16 md:py-36 lg:py-40 flex items-center min-h-[500px] md:min-h-[600px]">
        <div class="grid-pattern"></div>
        <div class="max-w-7xl mx-auto px-6 md:px-8 w-full relative z-10">
            <div class="flex flex-col md:flex-row items-center justify-between gap-6 md:gap-12">
                <div class="max-w-2xl text-center md:text-left">
                    <h3 class="text-3xl sm:text-3xl md:text-4xl lg:text-5xl font-black text-white tracking-tight leading-[1.1] mb-3 md:mb-5">
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-gold-500 via-yellow-400 to-[#6366f1] block md:inline whitespace-nowrap">GEO-SINFRA</span>
                    </h3>
                    <p class="text-slate-300 font-semibold text-base sm:text-lg md:text-xl lg:text-2xl leading-relaxed mb-4 md:mb-5 max-w-xl mx-auto md:mx-0">
                        Sistem Informasi Pemetaan Infrastruktur Permukiman 
                        <br class="hidden md:block" />
                        Kota Banjarmasin
                    </p>
                    <p class="text-slate-400 font-medium text-xs sm:text-sm md:text-base leading-relaxed mb-8 md:mb-10 max-w-xl mx-auto md:mx-0">
                        Sebuah platform cerdas berbasis WebGIS dan Kecerdasan Buatan (AI) yang dirancang untuk memantau, melaporkan, dan menganalisis kondisi infrastruktur di seluruh wilayah Kota Banjarmasin secara real-time.
                    </p>
                    <div class="flex flex-col sm:flex-row justify-center md:justify-start gap-3 md:gap-4 max-w-xs mx-auto sm:max-w-none">
                        <a href="#peta" class="btn-shine w-full sm:w-auto bg-gradient-to-r from-gold-500 to-gold-600 text-white px-6 py-3.5 md:px-8 md:py-4 rounded-xl md:rounded-2xl font-bold text-xs md:text-sm uppercase tracking-wider hover:scale-105 transition-all shadow-xl shadow-gold-500/20 text-center flex items-center justify-center">
                            <i class="fas fa-map mr-2"></i> Eksplorasi Peta GIS
                        </a>
                        <a href="#statistik" class="w-full sm:w-auto bg-white/10 backdrop-blur-md text-white border border-white/20 px-6 py-3.5 md:px-8 md:py-4 rounded-xl md:rounded-2xl font-bold text-xs md:text-sm uppercase tracking-wider hover:bg-white hover:text-navy-950 transition-all text-center flex items-center justify-center">
                            Analisis Statistik
                        </a>
                    </div>
                </div>
                
                <!-- Bouncing Logo -->
                <div class="hidden md:flex justify-center items-center w-56 h-56 lg:w-72 lg:h-72 animate-bounce">
                    <img src="{{ asset('logo_geo-sinfra.png') }}" alt="Logo Geo-Sinfra" class="w-full h-full object-contain drop-shadow-[0_10px_20px_rgba(255,255,255,0.2)]">
                </div>
            </div>
        </div>
    </section>

    <!-- Key Metrics / Feature Highlight Cards -->
    <section class="py-12 bg-transparent relative -mt-16 z-20 max-w-7xl mx-auto px-6 md:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 reveal-up">
            <div class="bg-white p-6 rounded-3xl shadow-xl shadow-slate-900/5 border border-slate-100 flex gap-5 items-start hover:-translate-y-2 transition-all duration-300">
                <div class="w-14 h-14 bg-[#6366f1]/10 rounded-2xl flex items-center justify-center text-navy-500 shrink-0">
                    <i class="fas fa-map-location-dot text-2xl"></i>
                </div>
                <div>
                    <h5 class="text-base font-extrabold text-navy-900 uppercase tracking-wider mb-2">Interaktif GIS</h5>
                    <p class="text-sm text-slate-500 leading-relaxed">Visualisasi geospasial real-time yang membagi sebaran aset infrastruktur per kelurahan secara presisi.</p>
                </div>
            </div>
            
            <div class="bg-white p-6 rounded-3xl shadow-xl shadow-slate-900/5 border border-slate-100 flex gap-5 items-start hover:-translate-y-2 transition-all duration-300">
                <div class="w-14 h-14 bg-emerald-500/10 rounded-2xl flex items-center justify-center text-emerald-500 shrink-0">
                    <i class="fas fa-brain text-2xl"></i>
                </div>
                <div>
                    <h5 class="text-base font-extrabold text-navy-900 uppercase tracking-wider mb-2">Analisis AI Prediktif</h5>
                    <p class="text-sm text-slate-500 leading-relaxed">Klasifikasi otomatis jenis dan kondisi kerusakan menggunakan model CNN & Decision Tree hybrid.</p>
                </div>
            </div>

            <div class="bg-white p-6 rounded-3xl shadow-xl shadow-slate-900/5 border border-slate-100 flex gap-5 items-start hover:-translate-y-2 transition-all duration-300">
                <div class="w-14 h-14 bg-amber-500/10 rounded-2xl flex items-center justify-center text-amber-500 shrink-0">
                    <i class="fas fa-file-shield text-2xl"></i>
                </div>
                <div>
                    <h5 class="text-base font-extrabold text-navy-900 uppercase tracking-wider mb-2">Pengambilan Keputusan</h5>
                    <p class="text-sm text-slate-500 leading-relaxed">Penentuan prioritas perbaikan berbasis bobot skor kerusakan teknis untuk efisiensi anggaran daerah.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Statistik Section -->
    <section id="statistik" class="py-16 lg:py-20 bg-[#0a091d] relative overflow-hidden">
        <!-- Background Accents -->
        <div class="absolute top-0 left-0 w-full h-full overflow-hidden pointer-events-none">
            <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] rounded-full bg-gold-500/5 blur-[120px]"></div>
            <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] rounded-full bg-blue-500/5 blur-[120px]"></div>
        </div>

        <div class="max-w-7xl mx-auto px-6 md:px-8 relative z-10">
            <div class="text-center mb-20 reveal-up">
                <span class="text-gold-500 font-extrabold text-sm uppercase tracking-[0.3em] mb-3 block">RINGKASAN INFRASTRUKTUR</span>
                <h4 class="text-white font-black text-3xl md:text-4xl lg:text-5xl tracking-tight mb-4">Statistik GEO-SINFRA</h4>
                <div class="w-16 h-1.5 bg-gold-500 mx-auto rounded-full"></div>
            </div>
            
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 reveal-up">
                <!-- Card 1 -->
                <div class="bg-[#0f0e2c] text-white p-6 rounded-3xl border border-blue-500/50 shadow-2xl hover:border-blue-500 transition-all duration-300 text-center relative overflow-hidden group hover:scale-[1.02]">
                    <div class="relative z-10">
                        <div class="w-14 h-14 bg-blue-500/10 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:bg-blue-500 group-hover:text-white transition-all">
                            <i class="fas fa-database text-blue-500 text-xl group-hover:text-white"></i>
                        </div>
                        <p class="text-3xl md:text-4xl font-black text-blue-500 leading-none mb-2">{{ number_format($stats['total'] ?? 0) }}</p>
                        <p class="text-xs font-bold text-slate-300 uppercase tracking-widest">Total Titik Terdata</p>
                    </div>
                </div>

                <!-- Card 2 -->
                <div class="bg-[#0f0e2c] text-white p-6 rounded-3xl border border-emerald-500/50 shadow-2xl hover:border-emerald-500 transition-all duration-300 text-center relative overflow-hidden group hover:scale-[1.02]">
                    <div class="relative z-10">
                        <div class="w-14 h-14 bg-emerald-500/10 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:bg-emerald-500 group-hover:text-white transition-all">
                            <i class="fas fa-map-marked-alt text-emerald-500 text-xl group-hover:text-white"></i>
                        </div>
                        <p class="text-3xl md:text-4xl font-black text-emerald-500 leading-none mb-2">{{ number_format($stats['kecamatan'] ?? 0) }}</p>
                        <p class="text-xs font-bold text-slate-300 uppercase tracking-widest">Kecamatan</p>
                    </div>
                </div>

                <!-- Card 3 -->
                <div class="bg-[#0f0e2c] text-white p-6 rounded-3xl border border-red-500/50 shadow-2xl hover:border-red-500 transition-all duration-300 text-center relative overflow-hidden group hover:scale-[1.02]">
                    <div class="relative z-10">
                        <div class="w-14 h-14 bg-red-500/10 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:bg-red-500 group-hover:text-white transition-all">
                            <i class="fas fa-exclamation-triangle text-red-500 text-xl group-hover:text-white"></i>
                        </div>
                        <p class="text-3xl md:text-4xl font-black text-red-500 leading-none mb-2">{{ number_format($stats['rusak_berat'] ?? 0) }}</p>
                        <p class="text-xs font-bold text-slate-300 uppercase tracking-widest">Kerusakan Berat</p>
                    </div>
                </div>

                <!-- Card 4 (AI Accent) -->
                <div class="bg-[#0f0e2c] text-white p-6 rounded-3xl shadow-2xl border border-gold-500/60 text-center relative overflow-hidden group hover:scale-[1.02] hover:border-gold-500 transition-all duration-300">
                    <div class="relative z-10">
                        <div class="w-14 h-14 bg-gold-500/10 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:rotate-12 transition-all">
                            <i class="fas fa-robot text-gold-500 text-xl"></i>
                        </div>
                        <p class="text-3xl md:text-4xl font-black text-gold-500 leading-none mb-2">{{ $stats['akurasi_ai'] ?? 0 }}%</p>
                        <p class="text-xs font-bold text-slate-300 uppercase tracking-widest">Cakupan Analisis AI</p>
                    </div>
                    <i class="fas fa-brain absolute -right-6 -bottom-6 text-white/5 text-[120px]"></i>
                </div>
            </div>

            <!-- Detailed Grid Statistics -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-12 reveal-up">
                <!-- Sebaran Kecamatan -->
                <div class="bg-[#0f0e2c]/60 backdrop-blur-xl p-6 rounded-3xl border border-white/5 shadow-2xl">
                    <div class="flex items-center gap-3 mb-8">
                        <div class="w-2.5 h-6 bg-gold-500 rounded-full"></div>
                        <h5 class="text-base font-extrabold text-white uppercase tracking-wider">Kepadatan Titik Data per Wilayah</h5>
                    </div>
                    <div class="space-y-5">
                        @foreach($sebaranKecamatan as $nama => $count)
                            <div>
                                <div class="flex justify-between text-sm font-bold uppercase tracking-wider mb-2">
                                    <span class="text-slate-400">{{ $nama ?: 'Wilayah Tidak Diketahui' }}</span>
                                    <span class="text-white font-extrabold">{{ $count }} Titik</span>
                                </div>
                                <div class="w-full bg-white/5 h-3 rounded-full overflow-hidden">
                                    <div class="bg-gradient-to-r from-blue-500 to-gold-500 h-full rounded-full" style="width: {{ $stats['total'] > 0 ? ($count / $stats['total'] * 100) : 0 }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Highlight Card -->
                <div class="bg-[#0f0e2c] p-8 rounded-3xl shadow-2xl relative overflow-hidden flex flex-col justify-between text-white border border-white/5">
                    <div class="grid-pattern"></div>
                    <div class="relative z-10">
                        <span class="text-gold-500 font-extrabold text-xs uppercase tracking-[0.3em] mb-2 block">KATEGORI DOMINAN</span>
                        <h5 class="text-4xl font-black uppercase tracking-tight mb-4">{{ $topKategori }}</h5>
                        <p class="text-slate-400 text-sm leading-relaxed max-w-sm mb-6">
                            Kategori infrastruktur ini memiliki jumlah laporan tertinggi di sistem GIS dan menjadi perhatian utama dalam proses monitoring pemeliharaan.
                        </p>
                    </div>
                    <div class="relative z-10 flex items-baseline gap-2">
                        <span class="text-4xl lg:text-5xl font-black text-gold-500">{{ number_format($topKategoriCount) }}</span>
                        <span class="text-slate-300 font-extrabold text-sm uppercase tracking-wider">Aset Teridentifikasi</span>
                    </div>
                    <i class="fas fa-chart-pie absolute -right-6 -bottom-6 text-white/5 text-[150px]"></i>
                </div>
            </div>

            <!-- Table Ringkasan Wilayah -->
            <div class="mt-12 bg-[#0f0e2c]/60 backdrop-blur-xl rounded-3xl border border-white/5 shadow-2xl overflow-hidden reveal-up">
                <div class="p-6 border-b border-white/5 flex items-center gap-3">
                    <div class="w-2.5 h-6 bg-gold-500 rounded-full"></div>
                    <h5 class="text-base font-extrabold text-white uppercase tracking-wider">Ringkasan Keparahan Kondisi</h5>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-white/5 border-b border-white/10">
                                <th class="px-8 py-5 text-xs font-black text-slate-400 uppercase tracking-widest">Kecamatan</th>
                                <th class="px-8 py-5 text-xs font-black text-slate-300 uppercase tracking-widest text-center">Total Aset</th>
                                <th class="px-8 py-5 text-xs font-black text-emerald-500 uppercase tracking-widest text-center">Kondisi Baik</th>
                                <th class="px-8 py-5 text-xs font-black text-amber-500 uppercase tracking-widest text-center">Kondisi Sedang</th>
                                <th class="px-8 py-5 text-xs font-black text-red-500 uppercase tracking-widest text-center">Rusak Berat</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @foreach($kondisiWilayah as $item)
                                <tr class="hover:bg-white/5 transition-all group border-l-4 border-transparent hover:border-gold-500 cursor-default">
                                    <td class="px-8 py-5 text-sm font-bold text-slate-200 group-hover:text-white group-hover:translate-x-1 transition-transform">{{ $item['nama'] ?: 'Lainnya' }}</td>
                                    <td class="px-8 py-5 text-center text-sm font-black text-white">{{ $item['total'] }}</td>
                                    <td class="px-8 py-5 text-center text-sm font-semibold text-emerald-400">{{ $item['baik'] }}</td>
                                    <td class="px-8 py-5 text-center text-sm font-semibold text-amber-400">{{ $item['rusak_sedang'] }}</td>
                                    <td class="px-8 py-5 text-center">
                                        <span class="inline-block px-3.5 py-1.5 bg-red-500/20 text-red-400 rounded-full text-xs font-black tracking-wide group-hover:bg-red-500 group-hover:text-white transition-all shadow-sm">{{ $item['rusak_berat'] }}</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    <!-- Map Section -->
    <section id="peta" class="py-16 lg:py-20 bg-slate-100 border-t border-slate-200/50 relative overflow-hidden">
        <div class="w-full px-4 md:px-12">
            <div class="max-w-7xl mx-auto mb-10 flex flex-col md:flex-row md:items-end justify-between gap-4 px-2">
                <div>
                    <span class="text-gold-500 font-extrabold text-sm uppercase tracking-[0.3em] mb-2 block">PETA INTERAKTIF</span>
                    <h4 class="text-navy-900 font-black text-3xl lg:text-4xl tracking-tight">Peta Sebaran</h4>
                </div>
                <p class="text-slate-500 text-sm lg:text-base max-w-md">
                    Gunakan peta GIS interaktif di bawah ini untuk melihat titik lokasi dan tingkat kerusakan infrastruktur permukiman secara real-time.
                </p>
            </div>

            <div class="relative bg-white rounded-[2.5rem] shadow-2xl overflow-hidden w-full h-[550px] md:h-[750px] lg:h-[850px] z-10">
                <!-- Map Container -->
                <div id="map" class="absolute inset-0 z-0"></div>

                <!-- Custom Zoom Controls & GPS -->
                <div class="absolute top-20 md:top-6 left-2 md:left-6 z-[9999] flex flex-col gap-2 pointer-events-auto">
                    <button onclick="map.zoomIn()" class="w-10 h-10 bg-[#0f0e2c]/90 backdrop-blur-xl rounded-xl border border-white/10 shadow-2xl flex items-center justify-center text-white hover:text-gold-500 hover:bg-[#1e1b4b] transition-all group" title="Zoom In" aria-label="Zoom In Peta">
                        <i class="fas fa-plus text-xs group-hover:scale-110 transition-transform" aria-hidden="true"></i>
                    </button>
                    <button onclick="map.zoomOut()" class="w-10 h-10 bg-[#0f0e2c]/90 backdrop-blur-xl rounded-xl border border-white/10 shadow-2xl flex items-center justify-center text-white hover:text-gold-500 hover:bg-[#1e1b4b] transition-all group" title="Zoom Out" aria-label="Zoom Out Peta">
                        <i class="fas fa-minus text-xs group-hover:scale-110 transition-transform" aria-hidden="true"></i>
                    </button>
                    <button onclick="locateUser()" class="w-10 h-10 mt-2 bg-[#0f0e2c]/90 backdrop-blur-xl rounded-xl border border-white/10 shadow-2xl flex items-center justify-center text-white hover:text-blue-400 hover:bg-[#1e1b4b] transition-all group" title="Lokasi Saya" aria-label="Gunakan Lokasi Saat Ini">
                        <i class="fas fa-crosshairs text-xs group-hover:scale-110 transition-transform" aria-hidden="true"></i>
                    </button>
                    <!-- Heatmap Toggle Button -->
                    <button id="toggle-heatmap" onclick="toggleHeatmap()" class="w-10 h-10 mt-2 bg-[#0f0e2c]/90 backdrop-blur-xl rounded-xl border border-white/10 shadow-2xl flex items-center justify-center text-slate-400 hover:text-red-500 hover:bg-[#1e1b4b] transition-all group relative" title="Aktifkan Heatmap Kerusakan" aria-label="Toggle Heatmap Kerusakan">
                        <i class="fas fa-fire text-xs group-hover:scale-110 transition-transform" aria-hidden="true"></i>
                        <span id="heatmap-indicator" class="absolute -top-1 -right-1 w-2.5 h-2.5 bg-red-500 rounded-full border-2 border-[#0f0e2c] hidden"></span>
                    </button>
                </div>



                <!-- Mini Legend (Removed) -->

                <!-- FLOATING UI WIDGETS -->

                <!-- Top Right Dropdowns (Unified Filter) -->
                <div class="absolute top-4 right-2 md:top-6 md:right-6 z-[9999] flex flex-col gap-2 w-[30%] md:w-48 pointer-events-auto">
                    <!-- Main Filter Button -->
                    <div class="relative w-full z-50">
                        <button onclick="toggleMenu('filter-utama')" class="w-full bg-[#0f0e2c]/90 backdrop-blur-xl border border-white/10 text-white px-2 py-2 md:px-3.5 md:py-3 rounded-xl flex justify-between items-center shadow-2xl hover:bg-[#1e1b4b] transition-all">
                            <div class="flex items-center gap-1 md:gap-2">
                                <i class="fas fa-filter text-xs text-gold-500"></i>
                                <span class="text-xs md:text-xs font-bold uppercase tracking-wider hidden md:inline">Filter Peta</span>
                                <span class="text-xs font-bold uppercase tracking-wider md:hidden">Filter</span>
                            </div>
                            <i class="fas fa-chevron-down text-xs md:text-xs text-slate-400"></i>
                        </button>
                        
                        <div id="filter-utama" class="hidden absolute top-full mt-2 right-0 w-56 bg-[#0f0e2c]/95 backdrop-blur-2xl rounded-xl p-3 shadow-2xl border border-white/10 max-h-[60vh] overflow-y-auto custom-scrollbar flex flex-col gap-3">
                            
                            <!-- SECTION: Kategori Objek -->
                            <div>
                                <span class="text-xs font-bold text-slate-400 uppercase tracking-widest px-1 mb-1 block">Kategori Objek</span>
                                <label class="flex items-center justify-between p-2 hover:bg-white/5 rounded-lg cursor-pointer transition-all border-b border-white/5 mb-1 pb-2">
                                    <span class="text-xs font-black text-gold-500 uppercase tracking-wider">Pilih Semua</span>
                                    <input type="checkbox" id="check-all-categories" class="w-3.5 h-3.5 rounded border-slate-600 bg-transparent text-gold-500 focus:ring-0" checked>
                                </label>
                                <label class="flex items-center justify-between p-2 hover:bg-white/5 rounded-lg cursor-pointer transition-all">
                                    <span class="text-xs font-bold text-slate-200 uppercase tracking-wider">Jalan</span>
                                    <input type="checkbox" class="filter-category w-3.5 h-3.5 rounded border-slate-600 bg-transparent text-gold-500 focus:ring-0" value="jalan" checked>
                                </label>
                                <label class="flex items-center justify-between p-2 hover:bg-white/5 rounded-lg cursor-pointer transition-all">
                                    <span class="text-xs font-bold text-slate-200 uppercase tracking-wider">Titian</span>
                                    <input type="checkbox" class="filter-category w-3.5 h-3.5 rounded border-slate-600 bg-transparent text-gold-500 focus:ring-0" value="titian" checked>
                                </label>
                                <label class="flex items-center justify-between p-2 hover:bg-white/5 rounded-lg cursor-pointer transition-all">
                                    <span class="text-xs font-bold text-slate-200 uppercase tracking-wider">Jembatan</span>
                                    <input type="checkbox" class="filter-category w-3.5 h-3.5 rounded border-slate-600 bg-transparent text-gold-500 focus:ring-0" value="jembatan" checked>
                                </label>
                            </div>

                            <!-- SECTION: Pilih Kecamatan -->
                            <div class="border-t border-white/10 pt-2">
                                <span class="text-xs font-bold text-slate-400 uppercase tracking-widest px-1 mb-1 block">Pilih Kecamatan</span>
                                <label class="flex items-center justify-between p-2 hover:bg-white/5 rounded-lg cursor-pointer transition-all border-b border-white/5 mb-1 pb-2">
                                    <span class="text-xs font-black text-gold-500 uppercase tracking-wider">Pilih Semua</span>
                                    <input type="checkbox" id="check-all-districts" class="w-3.5 h-3.5 rounded border-slate-600 bg-transparent text-gold-500 focus:ring-0" checked>
                                </label>
                                @php $kecColors = ['#6366f1', '#10b981', '#f59e0b', '#ef4444', '#06b6d4']; @endphp
                                @foreach($semuaWilayah as $index => $wil)
                                <label class="flex items-center justify-between p-2 hover:bg-white/5 rounded-lg cursor-pointer transition-all group">
                                    <div class="flex items-center gap-2">
                                        <input type="checkbox" class="filter-district w-3.5 h-3.5 rounded border-slate-600 bg-transparent text-gold-500 focus:ring-0" value="{{ $wil->id_kecamatan }}" checked>
                                        <span class="text-xs font-bold text-slate-200 uppercase tracking-wider">{{ $wil->nama_kecamatan }}</span>
                                    </div>
                                    <div class="w-2 h-2 rounded-full" style="background: {{ $kecColors[$index % count($kecColors)] }}"></div>
                                </label>
                                @endforeach
                            </div>

                            <!-- SECTION: Filter Tahun (Waktu) -->
                            <div class="border-t border-white/10 pt-2 flex flex-col gap-1.5 mb-2">
                                <span class="text-xs font-bold text-slate-400 uppercase tracking-widest px-1 mb-1 block">Periode Waktu</span>
                                <div class="relative">
                                    <select id="filter-tahun" onchange="fetchMapData()" class="w-full bg-[#0f0e2c] border border-white/10 text-slate-200 text-xs font-bold rounded-lg px-3 py-2.5 appearance-none cursor-pointer focus:outline-none focus:border-gold-500/50 transition-all">
                                        <option value="all">Semua Tahun</option>
                                        @php
                                            // Get distinct years from database to populate dropdown
                                            $years = \Illuminate\Support\Facades\DB::table('infrastruktur')
                                                ->select(\Illuminate\Support\Facades\DB::raw('YEAR(created_at) as year'))
                                                ->whereNull('deleted_at')
                                                ->distinct()
                                                ->orderBy('year', 'desc')
                                                ->pluck('year');
                                        @endphp
                                        @foreach($years as $year)
                                            <option value="{{ $year }}">{{ $year }}</option>
                                        @endforeach
                                    </select>
                                    <i class="fas fa-calendar-alt absolute right-3 top-1/2 -translate-y-1/2 text-gold-500 text-xs pointer-events-none"></i>
                                </div>
                            </div>

                            <!-- SECTION: Layer Tambahan -->
                            <div class="border-t border-white/10 pt-2 flex flex-col gap-1.5">
                                <span class="text-xs font-bold text-slate-400 uppercase tracking-widest px-1 mb-1 block">Layer Tambahan</span>
                                <label class="flex items-center justify-between p-2 hover:bg-white/5 rounded-lg cursor-pointer transition-all border border-white/5 bg-white/5">
                                    <div class="flex items-center gap-2">
                                        <input type="checkbox" id="toggle-kelurahan-lines" class="w-3.5 h-3.5 rounded border-slate-600 bg-transparent text-gold-500 focus:ring-0" checked>
                                        <span class="text-xs font-bold text-slate-200 uppercase tracking-wider">Batas Kelurahan</span>
                                    </div>
                                    <i class="fas fa-home text-gold-500 text-xs"></i>
                                </label>
                                <label class="flex items-center justify-between p-2 hover:bg-white/5 rounded-lg cursor-pointer transition-all border border-white/5 bg-white/5">
                                    <div class="flex items-center gap-2">
                                        <input type="checkbox" id="toggle-banjir-lines" class="w-3.5 h-3.5 rounded border-slate-600 bg-transparent text-blue-500 focus:ring-blue-500">
                                        <span class="text-xs font-bold text-slate-200 uppercase tracking-wider">Kerawanan Banjir</span>
                                    </div>
                                    <i class="fas fa-water text-blue-500 text-xs"></i>
                                </label>
                            </div>

                        </div>
                    </div>
                </div>



                <!-- Floating Lapor Button has been moved to header -->

                <!-- Bottom Left Stats Box Widget (Glassmorphic Dark UI) -->
                <div class="absolute bottom-8 left-2 md:bottom-6 md:left-6 z-[9999] bg-[#0f0e2c]/90 backdrop-blur-xl rounded-xl md:rounded-2xl p-1.5 text-white w-32 md:w-48 shadow-2xl border border-white/10 pointer-events-auto transition-all duration-300">
                    <div class="flex justify-between items-center bg-white/5 p-1.5 md:p-2 rounded-lg md:rounded-xl cursor-pointer hover:bg-white/10 transition-all" onclick="document.getElementById('stats-body').classList.toggle('hidden'); document.getElementById('stats-chevron').classList.toggle('rotate-180');">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-chart-pie text-gold-500 text-xs"></i>
                            <span class="text-xs font-extrabold uppercase tracking-widest text-slate-200">Statistik Filter</span>
                        </div>
                        <i id="stats-chevron" class="fas fa-chevron-down text-slate-400 text-xs transition-transform duration-300"></i>
                    </div>
                    
                    <div id="stats-body" class="space-y-3 pt-3 pb-2 px-2 hidden transition-all duration-300">
                        <div class="flex justify-between items-center">
                            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Titik</span>
                            <span id="stat-total" class="bg-indigo-500/20 text-[#6366f1] px-2 py-0.5 rounded-lg text-xs font-black min-w-[45px] text-center border border-indigo-500/20">0</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <div class="flex items-center gap-2">
                                <div class="w-2 h-2 rounded-full bg-emerald-500 shadow-md shadow-emerald-500/20"></div>
                                <span class="text-xs font-bold uppercase tracking-wider text-slate-300">Baik</span>
                            </div>
                            <span id="stat-baik" class="bg-emerald-500/20 text-emerald-400 px-2 py-0.5 rounded-lg text-xs font-black min-w-[45px] text-center border border-emerald-500/20">0</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <div class="flex items-center gap-2">
                                <div class="w-2 h-2 rounded-full bg-amber-500 shadow-md shadow-amber-500/20"></div>
                                <span class="text-xs font-bold uppercase tracking-wider text-slate-300">Sedang</span>
                            </div>
                            <span id="stat-sedang" class="bg-amber-500/20 text-amber-400 px-2 py-0.5 rounded-lg text-xs font-black min-w-[45px] text-center border border-amber-500/20">0</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <div class="flex items-center gap-2">
                                <div class="w-2 h-2 rounded-full bg-red-500 shadow-md shadow-red-500/20"></div>
                                <span class="text-xs font-bold uppercase tracking-wider text-slate-300">Berat</span>
                            </div>
                            <span id="stat-berat" class="bg-red-500/20 text-red-400 px-2 py-0.5 rounded-lg text-xs font-black min-w-[45px] text-center border border-red-500/20">0</span>
                        </div>
                    </div>
                </div>


                <!-- Bottom Right Basemap Selector (Circular Glassmorphic UI) -->
                <div class="absolute bottom-6 right-6 z-[9999] pointer-events-auto">
                    <button onclick="toggleMenu('layer-options')" class="w-14 h-14 bg-[#0f0e2c]/95 backdrop-blur-xl rounded-full flex items-center justify-center text-white/80 hover:text-gold-500 shadow-2xl border border-white/10 hover:scale-105 transition-all">
                        <i class="fas fa-layer-group text-xl"></i>
                    </button>
                    <div id="layer-options" class="hidden absolute bottom-[4.5rem] right-0 w-48 bg-[#0f0e2c]/95 backdrop-blur-2xl rounded-2xl p-2.5 shadow-2xl border border-white/10 flex flex-col gap-1.5">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-widest px-3.5 mb-1">Gaya Basemap</span>
                        <button onclick="setBasemap('google')" class="basemap-btn bg-white/10 text-white w-full px-4 py-3 rounded-xl text-xs font-black uppercase tracking-wider hover:bg-white/20 transition-all text-left">Default</button>
                        <button onclick="setBasemap('satelit')" class="basemap-btn text-slate-400 w-full px-4 py-3 rounded-xl text-xs font-black uppercase tracking-wider hover:bg-white/10 hover:text-white transition-all text-left">Satelit</button>
                        <button onclick="setBasemap('dark')" class="basemap-btn text-slate-400 w-full px-4 py-3 rounded-xl text-xs font-black uppercase tracking-wider hover:bg-white/10 hover:text-white transition-all text-left">Gelap</button>
                        <button onclick="setBasemap('greyscale')" class="basemap-btn text-slate-400 w-full px-4 py-3 rounded-xl text-xs font-black uppercase tracking-wider hover:bg-white/10 hover:text-white transition-all text-left">Abu-abu</button>
                        <button onclick="setBasemap('osm')" class="basemap-btn text-slate-400 w-full px-4 py-3 rounded-xl text-xs font-black uppercase tracking-wider hover:bg-white/10 hover:text-white transition-all text-left">OSM</button>
                        <button onclick="setBasemap('banjir')" class="basemap-btn text-slate-400 w-full px-4 py-3 rounded-xl text-xs font-black uppercase tracking-wider hover:bg-white/10 hover:text-white transition-all text-left text-blue-400">Peta Banjir</button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-navy-950 pt-20 pb-10 text-white relative border-t border-white/5">
        <div class="max-w-7xl mx-auto px-6 md:px-8 relative z-10">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12 mb-16">
                <!-- Brand -->
                <div>
                    <div class="mb-6">
                        <img src="{{ asset('logo_dinas.jpeg') }}" alt="Logo Banjarmasin" class="w-40 md:w-56 h-auto drop-shadow-xl rounded-xl" onerror="this.style.display='none'">
                    </div>
                </div>
                
                <!-- Contact -->
                <div>
                    <h5 class="text-base font-black text-white uppercase tracking-wider mb-6">Hubungi Kami</h5>
                    <ul class="space-y-4">
                        <li class="flex items-start gap-4">
                            <i class="fas fa-map-marker-alt text-gold-500 mt-1"></i>
                            <span class="text-sm text-slate-400 leading-relaxed"><strong class="text-white">Dinas Perumahan Rakyat dan Kawasan Permukiman Kota Banjarmasin</strong><br>Jalan R.E Martadinata No. 1 Blok B Lantai 2 Kec. Banjarmasin Tengah, Kota Banjarmasin Kalimantan Selatan - 70111</span>
                        </li>
                        <li class="flex items-center gap-4">
                            <i class="fas fa-envelope text-gold-500"></i>
                            <span class="text-sm text-slate-400">ampihkumuh@gmail.com</span>
                        </li>
                        <li class="flex items-center gap-4">
                            <i class="fas fa-phone-alt text-gold-500"></i>
                            <span class="text-sm text-slate-400">(0511) 3365592</span>
                        </li>

                    </ul>
                </div>
                
                <!-- Links -->
                <div>
                    <h5 class="text-base font-black text-white uppercase tracking-wider mb-6">Tautan Penting</h5>
                    <ul class="space-y-3">
                        <li><a href="#peta" class="text-sm text-slate-400 hover:text-gold-500 transition-colors flex items-center gap-2"><i class="fas fa-angle-right text-xs"></i> Peta Sebaran</a></li>
                        <li><a href="#statistik" class="text-sm text-slate-400 hover:text-gold-500 transition-colors flex items-center gap-2"><i class="fas fa-angle-right text-xs"></i> Statistik Data</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-white/10 pt-8 flex flex-col md:flex-row items-center gap-6 relative">
                <p class="text-xs font-bold text-slate-500 uppercase tracking-widest md:absolute md:left-1/2 md:-translate-x-1/2 text-center">&copy; Developed by NADYA Kota Banjarmasin 2026</p>
                <div class="flex gap-4 md:ml-auto">
                    <a href="https://www.instagram.com/disperkim.banjarmasin?utm_source=ig_web_button_share_sheet&igsh=ZDNlZDc0MzIxNw==" target="_blank" class="w-8 h-8 rounded-full bg-white/5 flex items-center justify-center text-slate-400 hover:bg-gold-500 hover:text-white transition-all shadow-sm hover:shadow-gold-500/50" title="Instagram"><i class="fab fa-instagram"></i></a>
                    <span class="w-8 h-8 rounded-full bg-white/5 flex items-center justify-center text-slate-500 cursor-default" title="YouTube (Belum Tersedia)"><i class="fab fa-youtube"></i></span>
                </div>
            </div>
        </div>
    </footer>

    <!-- Back to Top Button -->
    <div id="backToTop" class="fixed bottom-6 right-6 w-12 h-12 bg-gold-500 text-white rounded-full flex items-center justify-center cursor-pointer shadow-lg z-[4998] hover:scale-105 hover:bg-gold-600 transition-all opacity-0 invisible" onclick="window.scrollTo({top: 0, behavior: 'smooth'})">
        <i class="fas fa-chevron-up"></i>
    </div>

    <!-- Scripts -->
    <script>
        // Preloader Logic
        document.addEventListener('DOMContentLoaded', () => {
            const preloader = document.getElementById('preloader');
            if(preloader) {
                setTimeout(() => {
                    preloader.classList.add('fade-out');
                    document.body.classList.add('loaded');
                }, 500);
            }
        });

        // Mobile Menu Trigger
        function toggleMobileMenu() {
            const el = document.getElementById('mobile-menu');
            el.classList.toggle('hidden');
        }

        let activeKelurahanId = null;

        let dataInfra = @json($dataInfrastruktur);
        const dataWilayah = @json($semuaWilayah);
        const dataKelurahan = @json($dataKelurahan);
        const map = L.map('map', { zoomControl: false }).setView([-3.316694, 114.590111], 13);
        map.attributionControl.setPrefix('<a href="https://leafletjs.com" target="_blank">Leaflet</a>');
        
        const googleStreets = L.tileLayer('https://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}', { maxZoom: 20, subdomains:['mt0','mt1','mt2','mt3'], attribution: 'Map data &copy; <a href="https://maps.google.com">Google Maps</a>' }).addTo(map);
        const satelit = L.tileLayer('https://{s}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}', { maxZoom: 20, subdomains:['mt0','mt1','mt2','mt3'], attribution: 'Map data &copy; <a href="https://maps.google.com">Google Satellite</a>' });
        const darkMap = L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', { maxZoom: 20, attribution: 'Map tiles by &copy; <a href="https://carto.com/attributions">CARTO</a>' });
        const greyMap = L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', { maxZoom: 20, attribution: 'Map tiles by &copy; <a href="https://carto.com/attributions">CARTO</a>' });
        const osmMap = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19, attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors' });
        const petaBanjirMap = L.tileLayer('https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png', { maxZoom: 17, attribution: 'Simulasi Peta Banjir &copy; <a href="https://opentopomap.org">OpenTopoMap</a>' });
        
        // Inisialisasi MarkerCluster Group
        const markersLayer = L.markerClusterGroup({
            spiderfyOnMaxZoom: true,
            showCoverageOnHover: false,
            zoomToBoundsOnClick: true,
            maxClusterRadius: 50,
            iconCreateFunction: function(cluster) {
                var count = cluster.getChildCount();
                return L.divIcon({
                    className: 'custom-kelurahan-summary-marker',
                    html: `
                        <div class="flex flex-col items-center justify-center cursor-pointer group">
                            <div class="h-12 w-12 rounded-full bg-[#0f0e2c]/90 backdrop-blur-md border-2 border-gold-500 text-gold-500 font-black text-sm flex items-center justify-center shadow-2xl hover:scale-110 hover:bg-[#1e1b4b] hover:border-white transition-all">
                                ${count}
                            </div>
                        </div>
                    `,
                    iconSize: [48, 48],
                    iconAnchor: [24, 24]
                });
            }
        }).addTo(map);

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

        // Fungsi manual summary marker sudah diganti dengan MarkerCluster

        function applyFilters() {
            try {
                markersLayer.clearLayers();
                polygonsLayer.clearLayers();

                const checkedCategories = Array.from(document.querySelectorAll('.filter-category:checked')).map(el => el.value);
            const checkedDistricts = Array.from(document.querySelectorAll('.filter-district:checked')).map(el => el.value);
            const showKelurahan = document.getElementById('toggle-kelurahan-lines').checked;
            const toggleBanjir = document.getElementById('toggle-banjir-lines');
            const showBanjir = toggleBanjir ? toggleBanjir.checked : false;
            
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
                    if (showKelurahan || showBanjir || activeKelurahanId == kel.id_kelurahan) {
                        try {
                            const geoData = typeof kel.geometri === 'string' ? JSON.parse(kel.geometri) : kel.geometri;
                            
                            let polygonColor = '#94a3b8'; // Abu-abu netral (Slate-400)
                            let fillColor = '#94a3b8';
                            let fillOpacity = 0.01;
                            let weight = showKelurahan ? 1.2 : 0.0;

                            if (showBanjir) {
                                const riskLevel = kel.id_kelurahan % 3; 
                                if (riskLevel === 0) { // Tinggi
                                    polygonColor = '#ef4444'; fillColor = '#ef4444'; fillOpacity = 0.5;
                                } else if (riskLevel === 1) { // Sedang
                                    polygonColor = '#f59e0b'; fillColor = '#f59e0b'; fillOpacity = 0.4;
                                } else { // Aman
                                    polygonColor = '#3b82f6'; fillColor = '#3b82f6'; fillOpacity = 0.2;
                                }
                                weight = 1.0;
                            }

                            if (activeKelurahanId == kel.id_kelurahan) {
                                polygonColor = '#c5a059'; fillColor = '#c5a059'; fillOpacity = 0.2; weight = 3.0;
                            }
                            
                            const layer = L.geoJSON(geoData, {
                                filter: function(feature) {
                                    return feature.geometry.type !== 'Point';
                                },
                                style: {
                                    color: polygonColor,
                                    weight: weight,
                                    opacity: 0.95,
                                    fillOpacity: fillOpacity,
                                    fillColor: fillColor,
                                    dashArray: (activeKelurahanId == kel.id_kelurahan || showBanjir) ? '0' : '4, 4'
                                }
                            }).bindTooltip(`<div class="text-xs font-bold text-navy-900 leading-none">Kel. ${kel.nama_kelurahan}${showBanjir ? ' (Simulasi Banjir)' : ''}</div>`, { sticky: true }).addTo(polygonsLayer);

                            // Event saat poligon kelurahan diklik
                            layer.on('click', function(e) {
                                // Jika sudah aktif, nonaktifkan (toggle)
                                if (activeKelurahanId == kel.id_kelurahan) {
                                    activeKelurahanId = null;
                                } else {
                                    activeKelurahanId = kel.id_kelurahan;
                                }
                                applyFilters();
                                L.DomEvent.stopPropagation(e);
                            });

                            if (activeKelurahanId == kel.id_kelurahan) {
                                map.fitBounds(layer.getBounds(), { padding: [50, 50], maxZoom: 16 });
                            }
                        } catch (e) {
                            console.error("Error parsing geometry for kelurahan " + kel.nama_kelurahan, e);
                        }
                    }
                }
            });

            // 2. Draw Aset Markers (Semua marker akan di-cluster otomatis)
            let countTotal = filteredInfra.length;
            let countBaik = filteredInfra.filter(i => i.label_prioritas === 'Baik').length;
            let countSedang = filteredInfra.filter(i => i.label_prioritas === 'Rusak Sedang').length;
            let countBerat = filteredInfra.filter(i => i.label_prioritas === 'Rusak Berat').length;

            filteredInfra.forEach(item => {
                const lat = parseFloat(item.latitude);
                const lng = parseFloat(item.longitude);
                if (isNaN(lat) || isNaN(lng)) return; // Skip invalid coords

                const marker = L.marker([lat, lng], { 
                    icon: createIcon(item.jenis || 'Infrastruktur', item.label_prioritas) 
                });
                
                let imagePath = item.foto_terbaru || '';
                if(imagePath && !imagePath.includes('infrastruktur/')) {
                    imagePath = 'infrastruktur/' + imagePath;
                }
                imagePath = imagePath.replace(/\\/g, '/');
                
                let finalUrl = '';
                if (imagePath) {
                    finalUrl = `{{ asset('storage') }}/${imagePath}`;
                } else {
                    const type = item.jenis ? item.jenis.toLowerCase() : 'jalan';
                    let typeStr = 'jalan';
                    if (type.includes('titian')) typeStr = 'titian';
                    else if (type.includes('jembatan')) typeStr = 'jembatan';
                    
                    let condStr = 'baik';
                    const prioritas = item.label_prioritas ? item.label_prioritas.toLowerCase() : 'baik';
                    if (prioritas.includes('berat')) condStr = 'rusak_berat';
                    else if (prioritas.includes('sedang')) condStr = 'rusak_sedang';

                    finalUrl = `{{ asset('') }}dummy_${typeStr}_${condStr}.jpg`;
                }

                const imgTag = `<img src="${finalUrl}" class="w-full h-36 object-cover rounded-xl shadow-md mb-3.5" onerror="this.style.display='none'">`;
                
                // Format the update date
                let lastUpdateStr = '-';
                if (item.updated_at || item.created_at) {
                    const dateObj = new Date(item.updated_at || item.created_at);
                    lastUpdateStr = dateObj.toLocaleDateString('id-ID', {day: '2-digit', month: 'long', year: 'numeric'});
                }

                // Determine condition color
                let conditionColor = 'bg-emerald-500 text-white shadow-emerald-500/20';
                if (item.label_prioritas === 'Rusak Sedang') conditionColor = 'bg-amber-500 text-white shadow-amber-500/20';
                if (item.label_prioritas === 'Rusak Berat') conditionColor = 'bg-red-500 text-white shadow-red-500/20';

                const popupContent = `
                    <div class="p-3 min-w-[260px] font-sans">
                        ${imgTag}
                        
                        <div class="mb-3">
                            <h6 class="text-white font-extrabold text-base uppercase leading-tight truncate max-w-[250px] mb-1" title="${item.nama_objek || 'Aset Tanpa Nama'}">${item.nama_objek || 'Aset Tanpa Nama'}</h6>
                            <p class="text-gold-500 text-sm font-bold uppercase tracking-widest">${item.jenis || 'Infrastruktur'}</p>
                        </div>
                        
                        <div class="space-y-2 mb-3.5">
                            <div class="flex items-start gap-2.5">
                                <i class="fas fa-map-marker-alt text-slate-400 text-xs mt-0.5 w-4 text-center"></i>
                                <span class="text-slate-300 text-sm leading-relaxed flex-1">${item.nama_kecamatan || 'Lokasi tidak diketahui'}</span>
                            </div>
                            <div class="flex items-start gap-2.5">
                                <i class="fas fa-clock text-slate-400 text-xs mt-0.5 w-4 text-center"></i>
                                <span class="text-slate-300 text-sm leading-relaxed flex-1">Update: ${lastUpdateStr}</span>
                            </div>
                        </div>

                        <div class="border-t border-white/10 pt-3.5 flex items-center justify-between gap-3 mt-3 mb-2.5">
                            <span class="text-slate-400 text-xs font-bold uppercase tracking-widest">Kondisi:</span>
                            <div class="px-3.5 py-1.5 ${conditionColor} text-xs font-black uppercase tracking-wider rounded-lg shadow-md">
                                ${item.label_prioritas || 'N/A'}
                            </div>
                        </div>
                        
                        <button onclick="openDetailModal(${item.id_infrastruktur})" class="w-full bg-white/10 hover:bg-gold-500 text-white font-bold text-xs py-2.5 rounded-lg transition-all shadow-sm uppercase tracking-widest flex justify-center items-center gap-2">
                            <i class="fas fa-expand-arrows-alt"></i> Lihat Detail
                        </button>
                    </div>
                `;
                
                marker.bindPopup(popupContent, {
                    className: 'custom-leaflet-popup'
                });
                
                markersLayer.addLayer(marker);
            });

            // Dom updates
            document.getElementById('stat-total').innerText = countTotal;
            document.getElementById('stat-baik').innerText = countBaik;
            document.getElementById('stat-sedang').innerText = countSedang;
            document.getElementById('stat-berat').innerText = countBerat;
            } catch (err) {
                console.error("Critical error in applyFilters:", err);
            }

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
        const toggleBanjirLines = document.getElementById('toggle-banjir-lines');
        if (toggleBanjirLines) {
            toggleBanjirLines.addEventListener('change', applyFilters);
        }

        const filterCategories = document.querySelectorAll('.filter-category');

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
            map.removeLayer(osmMap);
            map.removeLayer(petaBanjirMap);

            if (type === 'satelit') { 
                map.addLayer(satelit); 
            } else if (type === 'dark') {
                map.addLayer(darkMap);
            } else if (type === 'greyscale') {
                map.addLayer(greyMap);
            } else if (type === 'osm') {
                map.addLayer(osmMap);
            } else if (type === 'banjir') {
                map.addLayer(petaBanjirMap);
            } else { 
                map.addLayer(googleStreets); 
            }
        }

        function toggleMenu(id) {
            const menus = ['layer-options', 'filter-utama'];
            
            menus.forEach(menuId => {
                const el = document.getElementById(menuId);
                if (el && menuId !== id) {
                    el.classList.add('hidden');
                }
            });
            
            const panel = document.getElementById(id);
            if (panel) {
                panel.classList.toggle('hidden');
            }
        }

        // --- Detail Modal Functions ---
        function openDetailModal(id) {
            const item = dataInfra.find(i => i.id_infrastruktur == id);
            if(!item) return;

            // Isi Data Modal
            document.getElementById('modal-title').innerText = item.nama_objek || 'Aset Tanpa Nama';
            document.getElementById('modal-kecamatan').innerText = item.nama_kecamatan || '-';
            document.getElementById('modal-kelurahan').innerText = item.nama_kelurahan || '-';
            document.getElementById('modal-jenis').innerText = item.jenis || 'Infrastruktur';
            // Hanya tampilkan Rute Navigasi
            document.getElementById('modal-action-btn').innerHTML = `
                <a href="https://www.google.com/maps/dir/?api=1&destination=${item.latitude},${item.longitude}" target="_blank" class="w-full bg-navy-900 border border-gold-500/50 text-gold-500 text-sm font-bold py-4 px-6 rounded-2xl hover:bg-gold-500 hover:text-navy-900 shadow-xl hover:shadow-gold-500/30 transition-all text-center flex items-center justify-center gap-3">
                    <i class="fas fa-directions text-lg"></i> Rute Navigasi ke Lokasi
                </a>
            `;
            
            // Tentukan status badge
            let conditionColor = 'bg-emerald-500 text-white';
            if (item.label_prioritas === 'Rusak Sedang') conditionColor = 'bg-amber-500 text-white';
            if (item.label_prioritas === 'Rusak Berat') conditionColor = 'bg-red-500 text-white';
            
            const badge = document.getElementById('modal-badge');
            badge.className = `px-4 py-1.5 rounded-lg text-xs font-black uppercase tracking-wider shadow-md ${conditionColor}`;
            badge.innerText = item.label_prioritas || 'N/A';

            // Set Gambar Modal
            let imagePath = item.foto_terbaru || '';
            if(imagePath && !imagePath.includes('infrastruktur/')) imagePath = 'infrastruktur/' + imagePath;
            imagePath = imagePath.replace(/\\/g, '/');
            
            let finalUrl = '';
            if (imagePath) {
                finalUrl = `{{ asset('storage') }}/${imagePath}`;
            } else {
                const type = item.jenis ? item.jenis.toLowerCase() : 'jalan';
                let typeStr = 'jalan';
                if (type.includes('titian')) typeStr = 'titian';
                else if (type.includes('jembatan')) typeStr = 'jembatan';
                let condStr = 'baik';
                const prioritas = item.label_prioritas ? item.label_prioritas.toLowerCase() : 'baik';
                if (prioritas.includes('berat')) condStr = 'rusak_berat';
                else if (prioritas.includes('sedang')) condStr = 'rusak_sedang';
                finalUrl = `{{ asset('') }}dummy_${typeStr}_${condStr}.jpg`;
            }
            document.getElementById('modal-img').src = finalUrl;

            // Tampilkan Modal dengan Animasi Fade-In
            const overlay = document.getElementById('detail-modal-overlay');
            const content = document.getElementById('detail-modal-content');
            overlay.classList.remove('hidden');
            overlay.classList.add('flex');
            
            setTimeout(() => {
                overlay.classList.remove('opacity-0');
                content.classList.remove('scale-95');
                content.classList.add('scale-100');
            }, 10);
        }

        function closeDetailModal() {
            const overlay = document.getElementById('detail-modal-overlay');
            const content = document.getElementById('detail-modal-content');
            
            // Animasi Fade-Out
            overlay.classList.add('opacity-0');
            content.classList.remove('scale-100');
            content.classList.add('scale-95');
            
            setTimeout(() => {
                overlay.classList.remove('flex');
                overlay.classList.add('hidden');
            }, 300);
        }

        // Tutup semua modal jika klik di luar
        window.onclick = function(event) {
            const mod1 = document.getElementById('detail-modal-overlay');
            if (event.target == mod1) {
                closeDetailModal();
            }
            
            // Close search results if clicked outside
            if (!event.target.closest('#search-infra') && !event.target.closest('#search-results')) {
                const results = document.getElementById('search-results');
                if(results) results.classList.add('hidden');
            }
        }

        // --- Fitur Peta Panas (Heatmap) ---
        let heatmapLayer = null;
        let isHeatmapActive = false;

        function toggleHeatmap() {
            isHeatmapActive = !isHeatmapActive;
            const btn = document.getElementById('toggle-heatmap');
            const indicator = document.getElementById('heatmap-indicator');
            
            if(isHeatmapActive) {
                btn.classList.add('text-red-500');
                btn.classList.remove('text-slate-400');
                indicator.classList.remove('hidden');
                
                // Kumpulkan titik kerusakan
                const heatPoints = [];
                dataInfra.forEach(item => {
                    const lat = parseFloat(item.latitude);
                    const lng = parseFloat(item.longitude);
                    if(isNaN(lat) || isNaN(lng)) return;
                    
                    let intensity = 0;
                    if(item.label_prioritas === 'Rusak Berat') intensity = 1.0;
                    else if(item.label_prioritas === 'Rusak Sedang') intensity = 0.5;
                    
                    if(intensity > 0) {
                        heatPoints.push([lat, lng, intensity]);
                    }
                });
                
                if(heatmapLayer) {
                    map.removeLayer(heatmapLayer);
                }
                
                heatmapLayer = L.heatLayer(heatPoints, {
                    radius: 35,
                    blur: 35,
                    maxZoom: 16,
                    gradient: {0.4: 'blue', 0.6: 'cyan', 0.7: 'lime', 0.8: 'yellow', 1.0: 'red'}
                }).addTo(map);
                
                // Sembunyikan marker biasa agar heatmap terlihat jelas
                map.removeLayer(markersLayer);
                
            } else {
                btn.classList.remove('text-red-500');
                btn.classList.add('text-slate-400');
                indicator.classList.add('hidden');
                
                if(heatmapLayer) {
                    map.removeLayer(heatmapLayer);
                }
                
                // Tampilkan marker kembali
                map.addLayer(markersLayer);
            }
        }

        // --- Fitur Pencarian (Search) ---
        const searchInput = document.getElementById('search-infra');
        const searchResults = document.getElementById('search-results');
        
        if(searchInput) {
            searchInput.addEventListener('input', function() {
                const query = this.value.toLowerCase();
                searchResults.innerHTML = '';
                
                if (query.length < 2) {
                    searchResults.classList.add('hidden');
                    return;
                }
                
                const results = dataInfra.filter(i => 
                    (i.nama_objek && i.nama_objek.toLowerCase().includes(query)) ||
                    (i.nama_kelurahan && i.nama_kelurahan.toLowerCase().includes(query))
                ).slice(0, 5); // Batasi 5 hasil
                
                if (results.length > 0) {
                    searchResults.classList.remove('hidden');
                    results.forEach(i => {
                        const div = document.createElement('div');
                        div.className = 'p-3 border-b border-white/5 hover:bg-white/10 cursor-pointer transition-colors';
                        div.innerHTML = `
                            <p class="text-xs font-bold text-white mb-0.5">${i.nama_objek || 'Tanpa Nama'}</p>
                            <p class="text-xs text-slate-400"><i class="fas fa-map-marker-alt text-gold-500 mr-1"></i>Kel. ${i.nama_kelurahan}</p>
                        `;
                        div.onclick = () => {
                            map.setView([parseFloat(i.latitude), parseFloat(i.longitude)], 18);
                            openDetailModal(i.id_infrastruktur);
                            searchResults.classList.add('hidden');
                            searchInput.value = i.nama_objek;
                        };
                        searchResults.appendChild(div);
                    });
                } else {
                    searchResults.classList.remove('hidden');
                    searchResults.innerHTML = '<div class="p-4 text-xs text-slate-400 text-center">Tidak ada hasil ditemukan</div>';
                }
            });
        }

        // --- Fitur GPS Lokasi Saya ---
        let userMarker = null;
        function locateUser() {
            if (!navigator.geolocation) {
                alert("Browser Anda tidak mendukung geolokasi.");
                return;
            }
            // Ubah ikon tombol sementara
            const btnIcon = document.querySelector('button[title="Lokasi Saya"] i');
            if(btnIcon) {
                btnIcon.className = 'fas fa-spinner fa-spin text-sm';
            }
            
            map.locate({setView: true, maxZoom: 16});
        }

        map.on('locationfound', function(e) {
            const btnIcon = document.querySelector('button[title="Lokasi Saya"] i');
            if(btnIcon) btnIcon.className = 'fas fa-crosshairs text-sm';
            
            if (userMarker) {
                userMarker.setLatLng(e.latlng);
            } else {
                userMarker = L.marker(e.latlng, {
                    icon: L.divIcon({
                        className: 'custom-user-marker',
                        html: '<div class="w-4 h-4 bg-blue-500 border-2 border-white rounded-full shadow-[0_0_15px_rgba(59,130,246,0.8)] animate-pulse"></div>',
                        iconSize: [16, 16],
                        iconAnchor: [8, 8]
                    })
                }).addTo(map);
            }
            userMarker.bindPopup('<div class="text-xs font-bold text-center">Lokasi Anda Saat Ini</div>').openPopup();
        });

        map.on('locationerror', function(e) {
            const btnIcon = document.querySelector('button[title="Lokasi Saya"] i');
            if(btnIcon) btnIcon.className = 'fas fa-crosshairs text-sm text-red-500';
            alert("Gagal menemukan lokasi Anda. Pastikan GPS aktif dan izin diberikan.");
        });

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

        // Pastikan filter dan map dirender setelah DOM stabil
        setTimeout(() => {
            applyFilters();
            reveal();
            // Paksa Leaflet untuk me-render ulang ukuran container jika ada perubahan DOM
            map.invalidateSize();
        }, 100);
    </script>
    <!-- Detail Modal Overlay (Hidden by Default) -->
    <div id="detail-modal-overlay" class="fixed inset-0 z-[100000] bg-[#0f0e2c]/80 backdrop-blur-md hidden items-center justify-center p-4 opacity-0 transition-opacity duration-300">
        <div id="detail-modal-content" class="bg-white rounded-[2rem] w-full max-w-3xl max-h-[90vh] overflow-y-auto shadow-2xl transform scale-95 transition-transform duration-300">
            <!-- Header -->
            <div class="sticky top-0 bg-white/95 backdrop-blur-xl z-20 px-4 md:px-8 py-4 md:py-5 border-b border-slate-100 flex flex-wrap gap-2 justify-between items-center">
                <div class="flex items-center gap-2 md:gap-4">
                    <div class="hidden md:flex w-10 h-10 rounded-full bg-navy-50 items-center justify-center text-gold-500">
                        <i class="fas fa-map-marked-alt"></i>
                    </div>
                    <div>
                        <h3 class="font-black text-navy-900 text-base md:text-lg uppercase tracking-tight leading-none mb-1" id="modal-title">Detail Aset</h3>
                        <p class="text-xs md:text-xs font-bold text-slate-400 uppercase tracking-widest" id="modal-jenis">Infrastruktur</p>
                    </div>
                </div>
                <div class="flex items-center gap-2 md:gap-4">
                    <span id="modal-badge" class="px-3 py-1 md:px-4 md:py-1.5 rounded-lg text-xs md:text-xs font-black uppercase tracking-wider bg-emerald-500 text-white shadow-md">Baik</span>
                    <button onclick="closeDetailModal()" class="w-8 h-8 md:w-10 md:h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 hover:bg-red-50 hover:text-red-500 transition-colors">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            
            <!-- Body -->
            <div class="p-4 md:p-6">
                <img id="modal-img" src="" class="w-full h-48 md:h-80 object-cover rounded-2xl md:rounded-3xl shadow-md mb-6 md:mb-8 bg-slate-100">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6 items-center bg-slate-50 p-4 md:p-6 rounded-2xl md:rounded-3xl border border-slate-100">
                    <!-- Left Col: Lokasi -->
                    <div class="space-y-6">
                        <div>
                            <span class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-1"><i class="fas fa-map mr-1"></i> Kecamatan</span>
                            <p id="modal-kecamatan" class="font-black text-navy-900 text-xl">Banjarmasin Tengah</p>
                        </div>
                        
                        <div>
                            <span class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-1"><i class="fas fa-map-pin mr-1"></i> Kelurahan</span>
                            <p id="modal-kelurahan" class="font-black text-navy-900 text-xl">Kertak Baru</p>
                        </div>
                    </div>

                    <!-- Right Col: Aksi -->
                    <div id="modal-action-btn" class="flex justify-end">
                        <!-- Tombol Rute Navigasi akan di-inject lewat JS -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Lapor Warga -->
    <div id="modal-lapor" class="hidden fixed inset-0 z-[10000] flex items-center justify-center p-4 sm:p-6 backdrop-blur-xl bg-[#0a091d]/80 transition-all duration-300">
        <div class="bg-[#0f0e2c]/95 backdrop-blur-3xl w-full max-w-2xl rounded-[2.5rem] shadow-2xl border border-white/10 overflow-hidden flex flex-col max-h-[90vh]">
            <!-- Header -->
            <div class="p-6 flex justify-between items-center text-white shrink-0 border-b border-white/10">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-gold-500/10 rounded-2xl flex items-center justify-center border border-gold-500/20">
                        <i class="fas fa-bullhorn text-xl text-gold-500"></i>
                    </div>
                    <div>
                        <h3 class="font-black text-xl tracking-tight leading-none mb-1">Lapor Kerusakan</h3>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Partisipasi Warga Banjarmasin</p>
                    </div>
                </div>
                <button onclick="document.getElementById('modal-lapor').classList.add('hidden')" class="w-10 h-10 bg-white/5 hover:bg-red-500 hover:text-white text-slate-400 rounded-xl flex items-center justify-center transition-all border border-white/10 hover:border-red-500">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>

            <!-- Body -->
            <div class="p-6 overflow-y-auto custom-scrollbar flex-1">
                <form action="{{ route('lapor.warga') }}" method="POST" enctype="multipart/form-data" id="form-lapor-warga">
                    @csrf
                    
                    <div class="space-y-6">
                        <!-- Nama & HP -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-xs font-black text-slate-300 uppercase tracking-widest mb-2 ml-1">Nama Pelapor <span class="text-red-500">*</span></label>
                                <input type="text" name="nama_pelapor" required placeholder="Nama Anda" class="w-full px-5 py-3.5 bg-white/5 border border-white/10 rounded-2xl text-sm font-semibold text-white focus:ring-4 focus:ring-gold-500/10 focus:border-gold-500 outline-none transition-all placeholder-slate-500">
                            </div>
                            <div>
                                <label class="block text-xs font-black text-slate-300 uppercase tracking-widest mb-2 ml-1">No. WhatsApp <span class="text-red-500">*</span></label>
                                <input type="text" name="no_hp" required placeholder="Nomor WhatsApp (Agar tim bisa menghubungi)" class="w-full px-5 py-3.5 bg-white/5 border border-white/10 rounded-2xl text-sm font-semibold text-white focus:ring-4 focus:ring-gold-500/10 focus:border-gold-500 outline-none transition-all placeholder-slate-500">
                            </div>
                        </div>

                        <!-- Deskripsi -->
                        <div>
                            <label class="block text-xs font-black text-slate-300 uppercase tracking-widest mb-2 ml-1">Deskripsi Kerusakan <span class="text-red-500">*</span></label>
                            <textarea name="deskripsi" required rows="3" placeholder="Contoh: Jalan berlubang cukup dalam dan sering digenangi air saat hujan..." class="w-full px-5 py-3.5 bg-white/5 border border-white/10 rounded-2xl text-sm font-semibold text-white focus:ring-4 focus:ring-gold-500/10 focus:border-gold-500 outline-none transition-all resize-none placeholder-slate-500"></textarea>
                        </div>

                        <!-- Lokasi GPS & Map Picker -->
                        <div class="p-5 bg-white/5 rounded-2xl border border-white/10">
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <h4 class="font-black text-white text-sm">Titik Lokasi <span class="text-red-500">*</span></h4>
                                    <p class="text-xs text-slate-400 font-medium">Geser pin pada peta atau klik tombol GPS.</p>
                                </div>
                                <button type="button" onclick="getWargaLocation(this)" class="px-4 py-2 bg-blue-500/20 hover:bg-blue-500 border border-blue-500/50 hover:border-blue-500 text-blue-400 hover:text-white rounded-xl font-black text-xs uppercase tracking-widest transition-all flex items-center gap-2">
                                    <i class="fas fa-crosshairs"></i> Ambil GPS
                                </button>
                            </div>
                            
                            <!-- Mini Map for Picking Location -->
                            <div id="warga-map" class="w-full h-48 rounded-xl z-10 mb-3 border border-white/20"></div>

                            <div class="grid grid-cols-2 gap-3">
                                <input type="text" id="warga-lat" name="latitude" required readonly placeholder="Latitude" class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-xs font-bold text-slate-400 outline-none focus:ring-2 focus:ring-blue-500/50 transition-all cursor-not-allowed">
                                <input type="text" id="warga-lng" name="longitude" required readonly placeholder="Longitude" class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-xs font-bold text-slate-400 outline-none focus:ring-2 focus:ring-blue-500/50 transition-all cursor-not-allowed">
                            </div>
                        </div>

                        <!-- Foto Bukti -->
                        <div>
                            <label class="block text-xs font-black text-slate-300 uppercase tracking-widest mb-2 ml-1">Foto Bukti Lapangan <span class="text-red-500">*</span></label>
                            <div class="relative w-full h-40 border-2 border-dashed border-white/20 rounded-2xl bg-white/5 hover:bg-gold-500/5 hover:border-gold-500/50 transition-all cursor-pointer flex flex-col items-center justify-center overflow-hidden group">
                                <input type="file" name="foto" required accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" onchange="previewWargaFoto(this)">
                                <div id="warga-foto-placeholder" class="text-center px-4">
                                    <div class="w-12 h-12 bg-white/10 border border-white/10 rounded-full flex items-center justify-center mx-auto mb-2 text-slate-400 group-hover:text-gold-500 group-hover:border-gold-500/50 group-hover:bg-gold-500/10 transition-colors">
                                        <i class="fas fa-camera text-xl"></i>
                                    </div>
                                    <p class="text-xs font-bold text-slate-400">Ketuk untuk mengambil foto</p>
                                </div>
                                <img id="warga-foto-preview" class="hidden absolute inset-0 w-full h-full object-cover">
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Footer -->
            <div class="p-6 border-t border-white/10 flex justify-end gap-3 shrink-0 bg-[#0f0e2c]">
                <button type="button" onclick="document.getElementById('modal-lapor').classList.add('hidden')" class="px-6 py-3.5 bg-white/5 border border-white/10 hover:bg-white/10 text-slate-300 rounded-xl font-black text-xs uppercase tracking-widest transition-all">
                    Batal
                </button>
                <button type="submit" form="form-lapor-warga" class="px-8 py-3.5 bg-gold-500 hover:bg-gold-600 text-white rounded-xl font-black text-xs uppercase tracking-widest transition-all shadow-lg shadow-gold-500/30 flex items-center gap-2 border border-gold-400">
                    Kirim Laporan <i class="fas fa-paper-plane"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Script for Warga Report -->
    <script>
        let mapWarga;
        let markerWarga;

        // Initialize map when modal is opened
        document.querySelector('button[onclick*="modal-lapor\').classList.remove(\'hidden\')"]').addEventListener('click', function() {
            setTimeout(() => {
                if (!mapWarga) {
                    // Default coordinate Banjarmasin
                    let defaultLat = -3.316694;
                    let defaultLng = 114.590111;

                    mapWarga = L.map('warga-map').setView([defaultLat, defaultLng], 13);
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '© OpenStreetMap'
                    }).addTo(mapWarga);

                    markerWarga = L.marker([defaultLat, defaultLng], { draggable: true }).addTo(mapWarga);
                    
                    // Update input when marker dragged
                    markerWarga.on('dragend', function (e) {
                        const latlng = markerWarga.getLatLng();
                        document.getElementById('warga-lat').value = latlng.lat.toFixed(6);
                        document.getElementById('warga-lng').value = latlng.lng.toFixed(6);
                    });

                    // Update marker and input when map clicked
                    mapWarga.on('click', function(e) {
                        markerWarga.setLatLng(e.latlng);
                        document.getElementById('warga-lat').value = e.latlng.lat.toFixed(6);
                        document.getElementById('warga-lng').value = e.latlng.lng.toFixed(6);
                    });
                } else {
                    mapWarga.invalidateSize(); // Ensure map renders correctly if already initialized
                }
            }, 300); // give time for modal to transition
        });

        function getWargaLocation(btn) {
            const oriText = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mencari...';
            
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(pos) {
                    const lat = pos.coords.latitude;
                    const lng = pos.coords.longitude;
                    
                    document.getElementById('warga-lat').value = lat.toFixed(6);
                    document.getElementById('warga-lng').value = lng.toFixed(6);
                    
                    if (mapWarga && markerWarga) {
                        mapWarga.setView([lat, lng], 16);
                        markerWarga.setLatLng([lat, lng]);
                    }

                    btn.innerHTML = '<i class="fas fa-check"></i> Sukses';
                    btn.classList.add('bg-emerald-500', 'text-white');
                    setTimeout(() => { btn.innerHTML = oriText; btn.classList.remove('bg-emerald-500'); }, 3000);
                }, function(err) {
                    alert('Gagal mengambil lokasi. Pastikan GPS diizinkan di browser Anda. Anda juga bisa menggeser pin pada peta.');
                    btn.innerHTML = oriText;
                }, { enableHighAccuracy: true, timeout: 10000 });
            } else {
                alert('Browser tidak mendukung GPS. Silakan klik/geser pin pada peta.');
                btn.innerHTML = oriText;
            }
        }

        function previewWargaFoto(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('warga-foto-placeholder').classList.add('hidden');
                    const preview = document.getElementById('warga-foto-preview');
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
    
    @if(session('success_laporan'))
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Laporan Berhasil!',
                text: "{{ session('success_laporan') }}",
                icon: 'success',
                confirmButtonColor: '#1e1b4b',
                confirmButtonText: 'Terima Kasih'
            });
        });
    </script>
    @endif

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Navbar Scroll Logic
            const navbar = document.getElementById('navbar');
            
            window.addEventListener('scroll', function() {
                if (window.scrollY > 50) {
                    navbar.classList.remove('nav-transparent');
                    navbar.classList.add('nav-scrolled');
                } else {
                    navbar.classList.remove('nav-scrolled');
                    navbar.classList.add('nav-transparent');
                }
            });

            // Intersection Observer for Reveal Animations
            const revealElements = document.querySelectorAll('.reveal-up');
            
            const revealOptions = {
                threshold: 0.15,
                rootMargin: "0px 0px -50px 0px"
            };

            const revealOnScroll = new IntersectionObserver(function(entries, observer) {
                entries.forEach(entry => {
                    if (!entry.isIntersecting) {
                        return;
                    } else {
                        entry.target.classList.add('active');
                        observer.unobserve(entry.target);
                    }
                });
            }, revealOptions);

            revealElements.forEach(el => {
                revealOnScroll.observe(el);
            });
            
            // Initial render
            applyFilters();

            // Custom Icon untuk heatmap & cluster
            const customClusterIcon = function (cluster) {
                const childCount = cluster.getChildCount();
                let c = ' bg-navy-900/90 text-gold-500 border border-gold-500/50';
                return new L.DivIcon({ 
                    html: `<div class="flex items-center justify-center w-full h-full rounded-full ${c} shadow-[0_0_15px_rgba(197,160,89,0.3)]"><span class="font-black text-xs">${childCount}</span></div>`, 
                    className: 'custom-cluster-icon', 
                    iconSize: new L.Point(40, 40) 
                });
            };

            function getSelectedTahun() {
                const select = document.getElementById('filter-tahun');
                return select ? select.value : 'all';
            }

            function fetchMapData() {
                // Show loading state if needed
                document.querySelectorAll('.stat-val').forEach(el => el.innerHTML = '<i class="fas fa-spinner fa-spin text-xs"></i>');
                
                const tahun = getSelectedTahun();
                fetch('/api/map-data?tahun=' + tahun)
                    .then(res => res.json())
                    .then(data => {
                        if (data && data.infrastruktur) {
                            dataInfra = data.infrastruktur;
                            
                            // Re-render map
                            applyFilters();
                            
                            // Update the DOM stats if available
                            if (data.stats) {
                                const statTotal = document.querySelector('#stat-total');
                                const statKecamatan = document.querySelector('#stat-kecamatan');
                                const statRusak = document.querySelector('#stat-rusak-berat');
                                const statAkurasi = document.querySelector('#stat-akurasi');
                                
                                if (statTotal) statTotal.innerText = data.stats.total.toLocaleString('id-ID');
                                if (statKecamatan) statKecamatan.innerText = data.stats.kecamatan.toLocaleString('id-ID');
                                if (statRusak) statRusak.innerText = data.stats.rusak_berat.toLocaleString('id-ID');
                                if (statAkurasi) statAkurasi.innerText = data.stats.akurasi_ai + '%';
                            }
                        }
                    })
                    .catch(err => console.error("Error fetching map data:", err));
            }

            // Jalankan polling setiap 30 detik
            setInterval(fetchMapData, 30000);
        });
    </script>
</body>
</html>