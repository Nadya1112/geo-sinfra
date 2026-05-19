<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GEO-SINFRA</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #fcfcfc; color: #1e293b; opacity: 0; transition: opacity 1s ease-in; }
        body.loaded { opacity: 1; }
        html { scroll-behavior: smooth; }

        /* Preloader Styles */
        #preloader {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: #1e1b4b;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            transition: all 0.8s cubic-bezier(0.645, 0.045, 0.355, 1);
        }
        #preloader.fade-out {
            opacity: 0;
            visibility: hidden;
            transform: translateY(-100%);
        }
        .loader-logo {
            font-size: 3rem;
            color: #c5a059;
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.1); opacity: 0.7; }
            100% { transform: scale(1); opacity: 1; }
        }

        .fade-up {
            animation: fadeUp 1s ease-out forwards;
            opacity: 0;
            transform: translateY(30px);
        }
        @keyframes fadeUp {
            to { opacity: 1; transform: translateY(0); }
        }

        .bg-navy { background-color: #1e1b4b; }
        .text-navy { color: #1e1b4b; }
        .bg-gold { background-color: #c5a059; }
        .text-gold { color: #c5a059; }
        
        .hero-section {
            background: linear-gradient(rgba(30, 27, 75, 0.8), rgba(30, 27, 75, 0.8)), url('https://images.unsplash.com/photo-1596422846543-75c6fc18a593?auto=format&fit=crop&q=80&w=2070');
            background-size: cover;
            background-position: center;
        }

        #map { height: 550px; width: 100%; border-radius: 1rem; border: 1px solid #e2e8f0; z-index: 10; }
        
        .card-stat {
            background: white;
            padding: 2rem;
            border-radius: 1rem;
            border: 1px solid #f1f5f9;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            text-align: center;
        }

        .nav-link {
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: #1e1b4b;
            transition: all 0.3s;
        }
        .nav-link:hover { color: #c5a059; }

        .btn-internal {
            background-color: #1e1b4b;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 0.75rem;
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            transition: all 0.3s;
        }
        .btn-internal:hover { background-color: #c5a059; }

        /* Scroll Reveal */
        .reveal {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.8s ease-out;
        }
        .reveal.active {
            opacity: 1;
            transform: translateY(0);
        }

        .back-to-top {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            background: #c5a059;
            color: white;
            width: 3rem;
            height: 3rem;
            border-radius: 50%;
            display: flex;
            items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }
        .back-to-top.show {
            opacity: 1;
            visibility: visible;
        }

        /* Custom Scrollbar */
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #f1f5f9; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #c5a059; border-radius: 10px; }
        
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

        .reveal-panel {
            animation: slideUp 0.3s cubic-bezier(0.4, 0, 0.2, 1) forwards;
        }
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(10px) scale(0.95); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }

        /* Leaflet Customizations for Dark UI */
        .leaflet-control-zoom a {
            background-color: #3b3759 !important;
            color: #ffffff !important;
            border: 1px solid rgba(255,255,255,0.1) !important;
            border-radius: 12px !important;
            margin-bottom: 8px !important;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1) !important;
            transition: all 0.3s !important;
        }
        .leaflet-control-zoom a:hover {
            background-color: #483d8b !important;
        }
        .leaflet-control-zoom { border: none !important; margin: 24px !important; }
    </style>
</head>
<body class="antialiased">

    <!-- Preloader -->
    <div id="preloader">
        <div class="loader-logo mb-4">
            <i class="fas fa-globe-asia"></i>
        </div>
        <h2 class="text-white font-black tracking-[0.5em] uppercase text-[10px]">Memuat Geo-Sinfra</h2>
    </div>

    <!-- Skip to Statistik Link -->
    <a href="#statistik" class="skip-link text-sm font-black text-navy bg-gold px-4 py-2 rounded-full absolute left-4 top-4 z-50 hover:bg-white hover:text-navy transition-all">Lihat Statistik</a>
    
    <!-- Header -->
    <nav class="bg-white border-b border-gray-100 h-24 flex items-center sticky top-0 z-[1000]">
        <div class="max-w-7xl mx-auto px-8 w-full flex justify-between items-center">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-navy rounded-xl flex items-center justify-center text-gold shadow-md">
                    <i class="fas fa-globe-asia text-xl"></i>
                </div>
                <div>
                    <h1 class="text-[10px] font-black text-gray-400 uppercase tracking-widest leading-none mb-1">Portal Informasi Publik</h1>
                    <h2 class="text-xl font-black text-navy tracking-tighter uppercase leading-none">GEO-SINFRA</h2>
                </div>
            </div>
            
            <div class="hidden md:flex items-center gap-8">
                <a href="#" class="nav-link">Beranda</a>
                <a href="#statistik" class="nav-link">Statistik</a>
                <a href="#peta" class="nav-link">Peta Sebaran</a>
                <a href="{{ url('/login') }}" class="btn-internal">Login</a>
            </div>
        </div>
    </nav>

    <!-- Hero -->
    <section class="hero-section h-[450px] flex items-center text-center md:text-left">
        <div class="max-w-7xl mx-auto px-8 w-full fade-up" style="animation-delay: 0.8s;">
            <div class="max-w-2xl">
                <div class="flex items-center gap-3 mb-4">
                </div>
                <h3 class="text-4xl md:text-6xl font-black text-white tracking-tighter leading-tight mb-6">Selamat Datang di <br> <span class="text-gold">GEO-SINFRA</span></h3>
                <p class="text-gray-300 font-bold text-lg mb-8">Sistem Informasi Pemetaan Infrastruktur Permukiman Kota Banjarmasin berbasis AI & GIS.</p>
                <div class="flex flex-wrap gap-4">
                    <a href="#peta" class="inline-block bg-gold text-white px-8 py-4 rounded-xl font-black text-[11px] uppercase tracking-widest hover:bg-white hover:text-navy transition-all shadow-lg">Lihat Peta Publik</a>
                    <a href="#statistik" class="inline-block bg-white/10 backdrop-blur-md text-white border border-white/20 px-8 py-4 rounded-xl font-black text-[11px] uppercase tracking-widest hover:bg-white hover:text-navy transition-all">Statistik</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Fitur Unggulan -->
    <section class="py-12 bg-white relative -mt-12 z-20 max-w-7xl mx-auto px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded-2xl shadow-xl border border-gray-100 flex gap-4 items-start hover:-translate-y-2 transition-all duration-300">
                <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600 shrink-0">
                    <i class="fas fa-map-location-dot text-xl"></i>
                </div>
                <div>
                    <h5 class="text-xs font-black text-navy uppercase mb-1">GIS</h5>
                    <p class="text-[10px] text-gray-400 font-medium leading-relaxed">Visualisasi real-time sebaran infrastruktur jalan, titian, dan sanitasi.</p>
                </div>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-xl border border-gray-100 flex gap-4 items-start hover:-translate-y-2 transition-all duration-300">
                <div class="w-12 h-12 bg-purple-50 rounded-xl flex items-center justify-center text-purple-600 shrink-0">
                    <i class="fas fa-brain text-xl"></i>
                </div>
                <div>
                    <h5 class="text-xs font-black text-navy uppercase mb-1">AI Prediction</h5>
                    <p class="text-[10px] text-gray-400 font-medium leading-relaxed">Analisis kondisi otomatis menggunakan Machine Learning untuk akurasi data.</p>
                </div>
            </div>

        </div>
    </section>

    <!-- Statistik -->
    <section id="statistik" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-8">
            <div class="text-center mb-16 reveal">
                <h4 class="text-navy font-black text-3xl tracking-tighter mb-2">STATISTIK</h4>
                <div class="w-20 h-1 bg-gold mx-auto rounded-full"></div>
                <p class="text-gray-400 text-sm mt-4">Ringkasan titik data yang telah terdata dan terverifikasi di sistem GEO-SINFRA.</p>
            </div>
            
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 reveal">
                <div class="card-stat border-t-4 border-navy group hover:border-gold transition-all duration-500">
                    <div class="w-12 h-12 bg-navy/5 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-navy group-hover:text-gold transition-all">
                        <i class="fas fa-database text-navy text-xl group-hover:text-gold"></i>
                    </div>
                    <p class="text-4xl font-black text-navy">{{ number_format($stats['total'] ?? 0) }}</p>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1">Total Data</p>
                </div>
                <div class="card-stat border-t-4 border-gold group hover:border-navy transition-all duration-500">
                    <div class="w-12 h-12 bg-gold/5 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-gold group-hover:text-white transition-all">
                        <i class="fas fa-map-marked-alt text-gold text-xl group-hover:text-white"></i>
                    </div>
                    <p class="text-4xl font-black text-navy">{{ number_format($stats['kecamatan'] ?? 0) }}</p>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1">Kecamatan</p>
                </div>
                <div class="card-stat border-t-4 border-red-500 group hover:border-red-600 transition-all duration-500">
                    <div class="w-12 h-12 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-red-500 group-hover:text-white transition-all">
                        <i class="fas fa-exclamation-triangle text-red-500 text-xl group-hover:text-white"></i>
                    </div>
                    <p class="text-4xl font-black text-navy">{{ number_format($stats['rusak_berat'] ?? 0) }}</p>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1">Rusak Berat</p>
                </div>
                <div class="card-stat bg-navy text-white group hover:bg-navy/90 transition-all duration-500 overflow-hidden relative">
                    <div class="relative z-10">
                        <div class="w-12 h-12 bg-white/10 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:rotate-12 transition-all">
                            <i class="fas fa-robot text-gold text-xl"></i>
                        </div>
                        <p class="text-4xl font-black text-white">{{ $stats['akurasi_ai'] ?? 0 }}%</p>
                        <p class="text-[10px] font-bold text-gold uppercase tracking-widest mt-1">Akurasi AI</p>
                    </div>
                    <i class="fas fa-brain absolute -right-4 -bottom-4 text-white/5 text-7xl"></i>
                </div>
            </div>

            <!-- Detail Statistik Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mt-12 reveal">
                <!-- Sebaran Perkecamatan -->
                <div class="bg-white p-8 rounded-2xl border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-500">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-2 h-6 bg-gold rounded-full"></div>
                        <h5 class="text-sm font-black text-navy uppercase tracking-widest">Sebaran Perkecamatan</h5>
                    </div>
                    <div class="space-y-4">
                        @foreach($sebaranKecamatan as $nama => $count)
                            <div>
                                <div class="flex justify-between text-[10px] font-bold uppercase mb-1">
                                    <span class="text-gray-500">{{ $nama ?: 'Tanpa Wilayah' }}</span>
                                    @if($count > 0)
                                        <span class="text-navy">{{ $count }} Titik</span>
                                    @endif
                                </div>
                                <div class="w-full bg-gray-100 h-2 rounded-full overflow-hidden">
                                    <div class="bg-navy h-full rounded-full" style="width: {{ $stats['total'] > 0 ? ($count / $stats['total'] * 100) : 0 }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Kategori Terbanyak -->
                <div class="bg-navy p-8 rounded-2xl shadow-xl relative overflow-hidden flex flex-col justify-center">
                    <div class="relative z-10">
                        <p class="text-gold font-bold text-[10px] uppercase tracking-[0.3em] mb-2">Kategori Terbanyak</p>
                        <h5 class="text-3xl font-black text-white uppercase tracking-tighter mb-4">{{ $topKategori }}</h5>
                        <div class="flex items-baseline gap-2">
                            <span class="text-5xl font-black text-gold">{{ number_format($topKategoriCount) }}</span>
                            <span class="text-gray-400 font-bold text-sm">Titik Data</span>
                        </div>
                    </div>
                    <!-- Decorative Icon -->
                    <i class="fas fa-chart-pie absolute -right-4 -bottom-4 text-white/5 text-9xl"></i>
                </div>
            </div>

            <!-- Tabel Ringkasan Kondisi -->
            <div class="mt-12 bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="p-8 border-b border-gray-50 flex items-center gap-3">
                    <div class="w-2 h-6 bg-navy rounded-full"></div>
                    <h5 class="text-sm font-black text-navy uppercase tracking-widest">Ringkasan Kondisi Wilayah</h5>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50">
                                <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Kecamatan</th>
                                <th class="px-8 py-4 text-[10px] font-black text-navy uppercase tracking-widest text-center">Total Titik</th>
                                <th class="px-6 py-4 text-[10px] font-black text-red-500 uppercase tracking-widest text-right pr-8">Rusak Berat</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($kondisiWilayah as $item)
                                <tr class="hover:bg-gray-50/50 transition-all">
                                    <td class="px-8 py-4 text-sm font-bold text-navy">{{ $item['nama'] ?: 'Lainnya' }}</td>
                                    <td class="px-8 py-4 text-center text-sm font-black text-navy">{{ $item['total'] }}</td>
                                    <td class="px-6 py-4 text-right pr-8">
                                        <span class="inline-block px-3 py-1 bg-red-50 text-red-600 rounded-full text-[10px] font-black">{{ $item['rusak_berat'] }}</span>
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
    <section id="peta" class="py-20 bg-gray-50 border-t border-gray-100 relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-8">
            <div class="mb-10">
                <h4 class="text-navy font-black text-2xl tracking-tighter leading-none mb-3">Peta Sebaran</h4>
                <p class="text-gray-400 text-sm">Visualisasi interaktif sebaran infrastruktur permukiman di seluruh wilayah.</p>
            </div>

            <div class="relative bg-white rounded-3xl shadow-2xl border border-gray-100 overflow-hidden" style="height: 700px;">
                <!-- Map Container -->
                <div id="map" class="absolute inset-0 z-0"></div>

                <!-- Floating UI Widgets -->
                
                <!-- Bottom Left: Stats Box -->
                <div class="absolute bottom-6 left-6 z-[9999] bg-[#3b3759]/95 backdrop-blur-xl rounded-[1.5rem] p-6 text-white w-64 shadow-2xl border border-white/10 pointer-events-auto">
            <div class="flex justify-between items-center mb-6">
                <div class="flex items-center gap-2">
                    <i class="fas fa-chart-pie text-blue-400"></i>
                    <span class="text-[10px] font-black uppercase tracking-widest text-gray-200">Statistik</span>
                </div>
                <i class="fas fa-chevron-up text-gray-400 text-[10px]"></i>
            </div>
            
            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-[10px] font-bold uppercase tracking-widest text-gray-300">Total</span>
                    <span id="stat-total" class="bg-[#2d2a4e] text-blue-400 px-3 py-1.5 rounded-lg text-xs font-black min-w-[50px] text-center">0</span>
                </div>
                <div class="flex justify-between items-center">
                    <div class="flex items-center gap-2">
                        <div class="w-2.5 h-2.5 rounded-full bg-emerald-500 shadow-sm shadow-emerald-500/50"></div>
                        <span class="text-[10px] font-bold uppercase tracking-widest text-gray-300">Baik</span>
                    </div>
                    <span id="stat-baik" class="bg-[#2d2a4e] text-emerald-400 px-3 py-1.5 rounded-lg text-xs font-black min-w-[50px] text-center">0</span>
                </div>
                <div class="flex justify-between items-center">
                    <div class="flex items-center gap-2">
                        <div class="w-2.5 h-2.5 rounded-full bg-amber-500 shadow-sm shadow-amber-500/50"></div>
                        <span class="text-[10px] font-bold uppercase tracking-widest text-gray-300">Ringan</span>
                    </div>
                    <span id="stat-ringan" class="bg-[#2d2a4e] text-amber-400 px-3 py-1.5 rounded-lg text-xs font-black min-w-[50px] text-center">0</span>
                </div>
                <div class="flex justify-between items-center">
                    <div class="flex items-center gap-2">
                        <div class="w-2.5 h-2.5 rounded-full bg-red-500 shadow-sm shadow-red-500/50"></div>
                        <span class="text-[10px] font-bold uppercase tracking-widest text-gray-300">Berat</span>
                    </div>
                    <span id="stat-berat" class="bg-[#2d2a4e] text-red-400 px-3 py-1.5 rounded-lg text-xs font-black min-w-[50px] text-center">0</span>
                </div>
            </div>
        </div>

        <!-- Top Right: Dropdowns -->
        <div class="absolute top-6 right-6 z-[9999] flex flex-col gap-3 w-64 pointer-events-auto">
            <!-- Kategori Objek -->
            <div class="relative w-full">
                <button onclick="toggleMenu('kategori-menu')" class="w-full bg-[#483d8b] text-white px-5 py-3.5 rounded-[1.25rem] flex justify-between items-center shadow-lg border border-white/10 hover:bg-[#3b3370] transition-all group">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-layer-group text-[10px] text-white/70 group-hover:text-white transition-colors"></i>
                        <span class="text-[10px] font-black uppercase tracking-[0.15em]">Kategori Objek</span>
                    </div>
                    <i class="fas fa-chevron-down text-[9px] opacity-70"></i>
                </button>
                <div id="kategori-menu" class="hidden absolute top-full mt-2 w-full bg-[#3b3759]/95 backdrop-blur-xl rounded-[1.25rem] p-3 shadow-2xl border border-white/10">
                    <label class="flex items-center justify-between p-2.5 hover:bg-white/10 rounded-xl cursor-pointer transition-all">
                        <span class="text-[9px] font-bold text-gray-200 uppercase tracking-widest">Jalan</span>
                        <input type="checkbox" class="filter-category rounded border-gray-600 bg-transparent text-[#483d8b] focus:ring-0" value="jalan" checked>
                    </label>
                    <label class="flex items-center justify-between p-2.5 hover:bg-white/10 rounded-xl cursor-pointer transition-all">
                        <span class="text-[9px] font-bold text-gray-200 uppercase tracking-widest">Titian</span>
                        <input type="checkbox" class="filter-category rounded border-gray-600 bg-transparent text-[#483d8b] focus:ring-0" value="titian" checked>
                    </label>
                    <label class="flex items-center justify-between p-2.5 hover:bg-white/10 rounded-xl cursor-pointer transition-all">
                        <span class="text-[9px] font-bold text-gray-200 uppercase tracking-widest">Sanitasi</span>
                        <input type="checkbox" class="filter-category rounded border-gray-600 bg-transparent text-[#483d8b] focus:ring-0" value="sanitasi" checked>
                    </label>
                </div>
            </div>

            <!-- Wilayah -->
            <div class="relative w-full">
                <button onclick="toggleMenu('wilayah-menu')" class="w-full bg-[#3b3759]/95 backdrop-blur-xl text-white px-5 py-3.5 rounded-[1.25rem] flex justify-between items-center shadow-lg border border-white/10 hover:bg-[#2d2a4e] transition-all group">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-map-location-dot text-[10px] text-white/50 group-hover:text-white/80 transition-colors"></i>
                        <span class="text-[10px] font-black uppercase tracking-[0.15em]">Wilayah</span>
                    </div>
                    <i class="fas fa-chevron-down text-[9px] opacity-70"></i>
                </button>
                <div id="wilayah-menu" class="hidden absolute top-full mt-2 w-full bg-[#3b3759]/95 backdrop-blur-xl rounded-[1.25rem] p-3 shadow-2xl border border-white/10 max-h-[300px] overflow-y-auto custom-scrollbar">
                    @php $kecColors = ['#6366f1', '#10b981', '#f59e0b', '#ef4444', '#06b6d4']; @endphp
                    @foreach($semuaWilayah as $index => $wil)
                    <label class="flex items-center justify-between p-2.5 hover:bg-white/10 rounded-xl cursor-pointer transition-all group">
                        <div class="flex items-center gap-3">
                            <input type="checkbox" class="filter-district rounded border-gray-600 bg-transparent text-[#483d8b] focus:ring-0" value="{{ $wil->id_kecamatan }}" checked>
                            <span class="text-[9px] font-bold text-gray-200 uppercase tracking-widest">{{ $wil->nama_kecamatan }}</span>
                        </div>
                        <div class="w-2 h-2 rounded-full" style="background: {{ $kecColors[$index % count($kecColors)] }}"></div>
                    </label>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Bottom Right: Basemap Toggle -->
        <div class="absolute bottom-6 right-6 z-[9999] pointer-events-auto">
            <button onclick="toggleMenu('layer-options')" class="w-14 h-14 bg-[#3b3759]/95 backdrop-blur-xl rounded-full flex items-center justify-center text-white/80 hover:text-white shadow-2xl border border-white/10 hover:scale-110 hover:bg-[#483d8b] transition-all">
                <i class="fas fa-layer-group text-lg"></i>
            </button>
            <div id="layer-options" class="hidden absolute bottom-[4.5rem] right-0 w-44 bg-[#3b3759]/95 backdrop-blur-xl rounded-[1.25rem] p-2 shadow-2xl border border-white/10 flex flex-col gap-1">
                <button onclick="setBasemap('google')" class="basemap-btn bg-white/10 text-white w-full px-4 py-2.5 rounded-xl text-[9px] font-bold uppercase tracking-widest hover:bg-white/20 transition-all text-left">Biasa</button>
                <button onclick="setBasemap('satelit')" class="basemap-btn text-gray-400 w-full px-4 py-2.5 rounded-xl text-[9px] font-bold uppercase tracking-widest hover:bg-white/10 hover:text-white transition-all text-left">Satelit</button>
                <button onclick="setBasemap('dark')" class="basemap-btn text-gray-400 w-full px-4 py-2.5 rounded-xl text-[9px] font-bold uppercase tracking-widest hover:bg-white/10 hover:text-white transition-all text-left">Gelap</button>
                <button onclick="setBasemap('greyscale')" class="basemap-btn text-gray-400 w-full px-4 py-2.5 rounded-xl text-[9px] font-bold uppercase tracking-widest hover:bg-white/10 hover:text-white transition-all text-left">Abu-abu</button>
            </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-navy py-12 text-white">
        <div class="max-w-7xl mx-auto px-8 flex flex-col md:flex-row justify-between items-center gap-8">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center text-gold"><i class="fas fa-globe-asia"></i></div>
                <h4 class="text-lg font-black uppercase tracking-tighter">GEO-SINFRA</h4>
            </div>
            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">&copy; {{ date('Y') }} PEMKOT BANJARMASIN. ALL RIGHTS RESERVED.</p>
        </div>
    </footer>

    <!-- Back to Top -->
    <div id="backToTop" class="back-to-top" onclick="window.scrollTo({top: 0, behavior: 'smooth'})">
        <i class="fas fa-chevron-up"></i>
    </div>

    <script>
        // Preloader Logic
        window.addEventListener('load', () => {
            const preloader = document.getElementById('preloader');
            setTimeout(() => {
                preloader.classList.add('fade-out');
                document.body.classList.add('loaded');
            }, 1500);
        });

        const dataInfra = @json($dataInfrastruktur);
        const dataWilayah = @json($semuaWilayah);
        const map = L.map('map', { zoomControl: true }).setView([-3.316694, 114.590111], 13);
        map.zoomControl.setPosition('topleft');
        
        const googleStreets = L.tileLayer('http://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}', { maxZoom: 20, subdomains:['mt0','mt1','mt2','mt3'] }).addTo(map);
        const satelit = L.tileLayer('http://{s}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}', { maxZoom: 20, subdomains:['mt0','mt1','mt2','mt3'] });
        const darkMap = L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', { maxZoom: 20 });
        const greyMap = L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', { maxZoom: 20 });
        
        const markersLayer = L.layerGroup().addTo(map);
        const polygonsLayer = L.layerGroup().addTo(map);

        // Map Colors (Sync with Filter)
        const kecColors = ['#6366f1', '#10b981', '#f59e0b', '#ef4444', '#06b6d4'];

        // Custom Icon Creator (Dot style like in the image)
        const createIcon = (type, kondisi) => {
            let color = '#3b82f6';
            if (kondisi === 'Baik') color = '#10b981'; // Emerald
            else if (kondisi === 'Rusak Ringan' || kondisi === 'Rusak Sedang') color = '#f59e0b'; // Amber/Orange
            else if (kondisi === 'Rusak Berat') color = '#ef4444'; // Red
            
            return L.divIcon({
                className: 'custom-dot-marker',
                html: `
                    <div class="w-4 h-4 rounded-full border-2 border-white shadow-md transition-all hover:scale-150" style="background-color: ${color}"></div>
                `,
                iconSize: [16, 16],
                iconAnchor: [8, 8],
                popupAnchor: [0, -8]
            });
        };

        function applyFilters() {
            markersLayer.clearLayers();
            polygonsLayer.clearLayers();

            const checkedCategories = Array.from(document.querySelectorAll('.filter-category:checked')).map(el => el.value);
            const checkedDistricts = Array.from(document.querySelectorAll('.filter-district:checked')).map(el => el.value);
            
            // 1. Render Polygons (Kecamatan)
            dataWilayah.forEach((wil, index) => {
                if (checkedDistricts.includes(wil.id_kecamatan.toString()) && wil.geometri) {
                    try {
                        const geoData = typeof wil.geometri === 'string' ? JSON.parse(wil.geometri) : wil.geometri;
                        const color = wil.warna || kecColors[index % kecColors.length];
                        
                        L.geoJSON(geoData, {
                            style: {
                                color: color,
                                weight: 2,
                                fillOpacity: 0.2,
                                dashArray: '3'
                            }
                        }).bindTooltip(wil.nama_kecamatan, { sticky: true }).addTo(polygonsLayer);
                    } catch (e) {
                        console.error("Error parsing geometry for " + wil.nama_kecamatan, e);
                    }
                }
            });

            // 2. Render Markers (Infrastruktur)
            let countTotal = 0;
            let countBaik = 0;
            let countRingan = 0;
            let countBerat = 0;

            dataInfra.forEach(item => {
                if (checkedCategories.includes(item.jenis.toLowerCase()) && checkedDistricts.includes(item.id_kecamatan?.toString())) {
                    countTotal++;
                    if (item.kondisi === 'Baik') countBaik++;
                    else if (item.kondisi === 'Rusak Ringan' || item.kondisi === 'Rusak Sedang') countRingan++;
                    else if (item.kondisi === 'Rusak Berat') countBerat++;

                    const marker = L.marker([item.latitude, item.longitude], { icon: createIcon(item.jenis, item.kondisi) });
                    
                    // Enhanced Popup
                    const popupContent = `
                        <div class="p-2 min-w-[200px] font-sans">
                            <div class="flex items-center gap-2 mb-3">
                                <div class="px-2 py-1 bg-navy text-gold text-[8px] font-black uppercase rounded">${item.jenis}</div>
                                <div class="px-2 py-1 bg-gray-100 text-gray-600 text-[8px] font-bold uppercase rounded">${item.kondisi}</div>
                            </div>
                            <h6 class="text-navy font-black text-sm uppercase mb-1">${item.nama_objek || 'Tanpa Nama'}</h6>
                            <p class="text-gray-400 text-[10px] leading-tight mb-3"><i class="fas fa-map-marker-alt mr-1"></i> ${item.nama_kecamatan || '-'}</p>
                            <div class="grid grid-cols-2 gap-2 border-t pt-3 border-gray-100">
                                <div>
                                    <p class="text-[8px] font-bold text-gray-400 uppercase">Dimensi</p>
                                    <p class="text-[10px] font-black text-navy">${item.panjang || 0}m x ${item.lebar || 0}m</p>
                                </div>
                                <div>
                                    <p class="text-[8px] font-bold text-gray-400 uppercase">Status</p>
                                    <p class="text-[10px] font-black text-emerald-500 uppercase">Terverifikasi</p>
                                </div>
                            </div>
                        </div>
                    `;
                    
                    marker.bindPopup(popupContent, {
                        className: 'custom-leaflet-popup'
                    }).addTo(markersLayer);
                }
            });

            // Update Dynamic Stats in DOM
            document.getElementById('stat-total').innerText = countTotal;
            document.getElementById('stat-baik').innerText = countBaik;
            document.getElementById('stat-ringan').innerText = countRingan;
            document.getElementById('stat-berat').innerText = countBerat;
        }

        // Event Listeners
        document.querySelectorAll('.filter-category, .filter-district').forEach(el => el.addEventListener('change', applyFilters));

        function setBasemap(type) {
            // Update UI Buttons inside Layer Switcher
            document.querySelectorAll('.basemap-btn').forEach(btn => {
                btn.classList.remove('bg-white/10', 'text-white');
                btn.classList.add('text-gray-400');
            });
            event.target.classList.add('bg-white/10', 'text-white');
            event.target.classList.remove('text-gray-400');

            // Remove all layers first
            map.removeLayer(googleStreets);
            map.removeLayer(satelit);
            map.removeLayer(darkMap);
            map.removeLayer(greyMap);

            // Add selected layer
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
            // Close others
            if (id !== 'layer-options') document.getElementById('layer-options').classList.add('hidden');
            if (id !== 'kategori-menu') document.getElementById('kategori-menu').classList.add('hidden');
            if (id !== 'wilayah-menu') document.getElementById('wilayah-menu').classList.add('hidden');
            
            const panel = document.getElementById(id);
            if (panel.classList.contains('hidden')) {
                panel.classList.remove('hidden');
            } else {
                panel.classList.add('hidden');
            }
        }

        // Scroll Reveal Logic
        function reveal() {
            var reveals = document.querySelectorAll(".reveal");
            for (var i = 0; i < reveals.length; i++) {
                var windowHeight = window.innerHeight;
                var elementTop = reveals[i].getBoundingClientRect().top;
                var elementVisible = 150;
                if (elementTop < windowHeight - elementVisible) {
                    reveals[i].classList.add("active");
                }
            }
        }
        window.addEventListener("scroll", reveal);

        // Back to Top Logic
        window.addEventListener('scroll', () => {
            const btt = document.getElementById('backToTop');
            if (window.scrollY > 300) {
                btt.classList.add('show');
            } else {
                btt.classList.remove('show');
            }
        });

        // Init
        applyFilters();
        reveal();
    </script>
</body>
</html>