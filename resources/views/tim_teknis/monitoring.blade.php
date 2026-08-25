<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitoring Peta | GEO-SINFRA</title>
    <script src="https://cdn.tailwindcss.com"></script>
            <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: { sans: ['Plus Jakarta Sans', 'sans-serif'] },
                    colors: {
                        navy: { 50:'#f4f4fa', 100:'#e9e9f3', 200:'#c7c8e3', 300:'#9fb3c8', 400:'#829ab1', 500:'#6366f1', 600:'#486581', 700:'#334e68', 800:'#1e1b4b', 900:'#0f0e2c', 950:'#070617' },
                        gold: { 50:'#fdfbf7', 100:'#fbf7ed', 200:'#eed9b9', 300:'#e5c292', 400:'#dba665', 500:'#c5a059', 600:'#b38f4a', 700:'#9d7c3d', 800:'#7c5327', 900:'#644422', 950:'#382310' }
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <style> body { font-family: 'Plus Jakarta Sans', sans-serif; }
    .leaflet-tooltip.premium-tooltip {
        background: rgba(15, 14, 44, 0.85) !important;
        backdrop-filter: blur(8px) !important;
        border: 1px solid rgba(255, 255, 255, 0.1) !important;
        color: white !important;
        font-family: 'Plus Jakarta Sans', sans-serif !important;
        font-weight: 900 !important;
        font-size: 8px !important;
        text-transform: uppercase !important;
        letter-spacing: 0.1em !important;
        padding: 4px 8px !important;
        border-radius: 8px !important;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.2), 0 2px 4px -1px rgba(0, 0, 0, 0.1) !important;
    }
    .leaflet-tooltip.premium-tooltip::before {
        border-right-color: rgba(15, 14, 44, 0.85) !important;
    }
    </style>
<style>
    
    
@media (max-width: 767px) { html { font-size: 12px; } }
</style>
</head>
<body class="bg-slate-50 dark:bg-[#0f0e2c] flex h-screen overflow-hidden text-slate-800 dark:text-white text-left font-sans dark:bg-navy-950 transition-colors duration-300">

    @include('tim_teknis.partials.sidebar')

    <main class="flex-1 flex flex-col h-screen overflow-hidden pb-24 md:pb-0">
        <header class="bg-white dark:bg-[#1e1b4b] border-b border-slate-100 dark:border-white/10 px-4 pl-20 md:pl-4 md:px-8 py-3 md:py-4 flex justify-between items-center z-40 sticky top-0">
            <div class="flex items-center gap-2 md:gap-4 min-w-0">
                <a href="{{ route('tim_teknis.dashboard') }}" class="w-10 h-10 flex items-center justify-center bg-slate-50 dark:bg-[#0f0e2c] text-slate-400 rounded-xl hover:bg-gold-50 hover:text-gold-500 transition-all border border-slate-100 dark:border-white/10 hidden md:flex flex-shrink-0">
                    <i class="fas fa-arrow-left text-sm"></i>
                </a>
                <div class="min-w-0">
                    <p class="text-[9px] md:text-xs font-extrabold text-rose-500 uppercase tracking-[0.15em] md:tracking-[0.2em] mb-0.5 md:mb-1"><i class="fas fa-satellite-dish mr-1 animate-pulse"></i>WebGIS Eksekutif</p>
                    <h2 class="text-sm md:text-xl font-black text-navy-900 dark:text-white leading-tight whitespace-normal">Peta Sebaran</h2>
                </div>
            </div>
            
            <div class="flex items-center gap-3 md:gap-6 flex-shrink-0">
                <div class="text-right">
                    <p class="text-[10px] md:text-xs font-black text-navy-900 dark:text-white mt-1" id="mini-clock">00:00 WITA</p>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-tighter hidden md:block">{{ now()->translatedFormat('l, d F Y') }}</p>
                </div>
                <div class="h-6 md:h-8 w-[1px] bg-slate-200 dark:bg-white/10"></div>
                <a href="{{ route('tim_teknis.profile') }}" class="flex items-center gap-2 md:gap-3 group">
                    <div class="text-right">
                        <p class="text-sm font-black text-navy-900 dark:text-white leading-none uppercase group-hover:text-gold-500 transition-colors max-w-[200px] truncate hidden md:block">{{ auth()->user()->name }}</p>
                        <p class="text-[8px] md:text-xs font-bold text-emerald-500 uppercase md:mt-0.5">Aktif</p>
                    </div>
                    <div class="w-8 h-8 md:w-10 md:h-10 bg-navy-900 rounded-xl flex items-center justify-center text-gold-500 shadow-md group-hover:shadow-lg transition-all overflow-hidden flex-shrink-0">
                        @if(auth()->user()->profile_photo)
                            <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}" class="w-full h-full object-cover">
                        @else
                            <i class="fas fa-user-circle text-lg md:text-xl"></i>
                        @endif
                    </div>
                </a>
            </div>
        </header>

        <!-- Map Toolbar (Outside the map) -->
        <div class="bg-white dark:bg-[#1e1b4b] border-b border-navy-50 dark:border-white/10 p-3 flex flex-col md:flex-row gap-3 md:items-center justify-between z-[9999] relative shadow-sm">
            <!-- Search Box -->
            <div class="relative w-full md:w-1/3">
                <input type="text" id="map-search" placeholder="Cari laporan (contoh: Jalan Teratai)..." class="w-full bg-slate-100 dark:bg-[#0f0e2c] border border-slate-300 dark:border-white/10 rounded-xl px-4 py-2 pl-9 text-sm font-bold text-navy-900 dark:text-white focus:ring-2 focus:ring-gold-500 focus:outline-none transition-all placeholder:text-slate-400">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
            </div>

            <!-- Controls (Horizontal Scrollable on Mobile) -->
            <div class="flex items-center gap-2 overflow-x-auto custom-scrollbar pb-1 md:pb-0 hide-scrollbar">
                
                <!-- Filter Dropdown Button -->
                <button onclick="document.getElementById('mobile-filter-sheet').classList.remove('hidden'); setTimeout(() => document.getElementById('mobile-filter-sheet').classList.remove('translate-y-full'), 10);" class="flex-shrink-0 bg-navy-900 dark:bg-white/10 text-white px-4 py-2 rounded-xl text-xs font-black uppercase tracking-wider hover:bg-navy-800 dark:hover:bg-white/20 transition-all flex items-center gap-2 shadow-md md:hidden">
                    <i class="fas fa-filter text-gold-500"></i> Filter Peta
                </button>

                <!-- Statistics Dropdown Button -->
                <div class="relative hidden md:block">
                    <button onclick="toggleMenu('condition-options-desktop')" class="flex-shrink-0 bg-white dark:bg-[#0f0e2c] border border-slate-200 dark:border-white/10 text-slate-600 dark:text-white px-4 py-2 rounded-xl text-xs font-black uppercase tracking-wider hover:bg-slate-50 dark:hover:bg-white/5 transition-all flex items-center gap-2 shadow-sm">
                        <i class="fas fa-chart-pie text-gold-500"></i> <span id="current-cond-label-desktop">Statistik</span> <i class="fas fa-chevron-down text-[10px] ml-1"></i>
                    </button>
                    <!-- Stats Dropdown Menu -->
                    <div id="condition-options-desktop" class="hidden absolute top-full left-0 mt-2 p-1.5 bg-[#1e1b4b]/95 backdrop-blur-xl rounded-xl border border-white/10 shadow-2xl flex flex-col min-w-[200px]">
                        <div class="w-full px-3 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-wider text-gray-300 flex items-center justify-between">
                            <span>Total</span>
                            <span id="stat-total-desktop" class="text-[10px] font-black text-blue-400 bg-blue-500/10 px-2 py-0.5 rounded border border-blue-400/20">0</span>
                        </div>
                        <div id="dynamic-stats-container-desktop" class="flex flex-col w-full gap-1 mt-1"></div>
                    </div>
                </div>

                <!-- Basemap Layer Button -->
                <div class="relative hidden md:block">
                    <button onclick="toggleMenu('layer-options-desktop')" class="flex-shrink-0 bg-white dark:bg-[#0f0e2c] border border-slate-200 dark:border-white/10 text-slate-600 dark:text-white px-4 py-2 rounded-xl text-xs font-black uppercase tracking-wider hover:bg-slate-50 dark:hover:bg-white/5 transition-all flex items-center gap-2 shadow-sm">
                        <i class="fas fa-layer-group text-blue-500"></i> Basemap
                    </button>
                    <!-- Basemap Dropdown -->
                    <div id="layer-options-desktop" class="hidden absolute top-full left-0 mt-2 p-1.5 bg-[#1e1b4b]/95 backdrop-blur-xl rounded-xl border border-white/10 shadow-2xl flex flex-col gap-1 min-w-[150px]">
                        <button onclick="changeBaseLayer('greyscale')" class="flex items-center gap-2 px-3 py-2 rounded-xl hover:bg-white/10 transition-all group">
                            <div class="w-6 h-6 rounded-md bg-gray-500/20 flex items-center justify-center text-gray-400 group-hover:bg-gray-500 group-hover:text-white transition-all">
                                <i class="fas fa-adjust text-[10px]"></i>
                            </div>
                            <span class="text-[10px] font-black uppercase tracking-wider text-gray-300 group-hover:text-white">Greyscale</span>
                        </button>
                        <button onclick="changeBaseLayer('satellite')" class="flex items-center gap-2 px-3 py-2 rounded-xl hover:bg-white/10 transition-all group">
                            <div class="w-6 h-6 rounded-md bg-emerald-500/20 flex items-center justify-center text-emerald-400 group-hover:bg-emerald-500 group-hover:text-white transition-all">
                                <i class="fas fa-satellite text-[10px]"></i>
                            </div>
                            <span class="text-[10px] font-black uppercase tracking-wider text-gray-300 group-hover:text-white">Satelit</span>
                        </button>
                        <button onclick="changeBaseLayer('osm')" class="flex items-center gap-2 px-3 py-2 rounded-xl hover:bg-white/10 transition-all group">
                            <div class="w-6 h-6 rounded-md bg-amber-500/20 flex items-center justify-center text-amber-400 group-hover:bg-amber-500 group-hover:text-white transition-all">
                                <i class="fas fa-map-marked-alt text-[10px]"></i>
                            </div>
                            <span class="text-[10px] font-black uppercase tracking-wider text-gray-300 group-hover:text-white">OSM</span>
                        </button>
                        <button onclick="changeBaseLayer('dark')" class="flex items-center gap-2 px-3 py-2 rounded-xl hover:bg-white/10 transition-all group">
                            <div class="w-6 h-6 rounded-md bg-indigo-500/20 flex items-center justify-center text-indigo-400 group-hover:bg-indigo-500 group-hover:text-white transition-all">
                                <i class="fas fa-moon text-[10px]"></i>
                            </div>
                            <span class="text-[10px] font-black uppercase tracking-wider text-gray-300 group-hover:text-white">Gelap</span>
                        </button>
                        <button onclick="changeBaseLayer('street')" class="flex items-center gap-2 px-3 py-2 rounded-xl hover:bg-white/10 transition-all group">
                            <div class="w-6 h-6 rounded-md bg-blue-500/20 flex items-center justify-center text-blue-400 group-hover:bg-blue-500 group-hover:text-white transition-all">
                                <i class="fas fa-map text-[10px]"></i>
                            </div>
                            <span class="text-[10px] font-black uppercase tracking-wider text-gray-300 group-hover:text-white">Default</span>
                        </button>
                        <div class="h-[1px] bg-white/10 my-0.5 mx-1"></div>
                        <button onclick="toggleFloodLayer()" class="flex items-center justify-between px-3 py-2 rounded-xl hover:bg-white/10 transition-all group w-full text-left">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-water text-blue-400 text-[10px]"></i>
                                <span class="text-[10px] font-black uppercase tracking-wider text-slate-300 group-hover:text-white transition-colors">Banjir</span>
                            </div>
                            <div class="w-5 h-2.5 rounded-full bg-slate-700 relative border border-white/10 transition-colors" id="flood-toggle-bg">
                                <div id="flood-toggle-dot" class="absolute left-[2px] top-[1px] w-1.5 h-1.5 bg-slate-400 rounded-full transition-all"></div>
                            </div>
                        </button>
                    </div>
                </div>

                <!-- Kategori Dropdown Button -->
                <div class="relative hidden md:block">
                    <button onclick="toggleMenu('category-options-desktop')" class="flex-shrink-0 bg-white dark:bg-[#0f0e2c] border border-slate-200 dark:border-white/10 text-slate-600 dark:text-white px-4 py-2 rounded-xl text-xs font-black uppercase tracking-wider hover:bg-slate-50 dark:hover:bg-white/5 transition-all flex items-center gap-2 shadow-sm">
                        <i class="fas fa-layer-group text-gold-500"></i> <span id="current-cat-label-desktop">Semua Kategori</span> <i class="fas fa-chevron-down text-[10px] ml-1"></i>
                    </button>
                    <div id="category-options-desktop" class="hidden absolute top-full right-0 mt-2 p-1.5 bg-[#0f0e2c]/95 backdrop-blur-xl rounded-xl border border-white/10 shadow-2xl flex flex-col gap-1 min-w-[200px]">
                        <button onclick="toggleType('Semua')" class="type-btn w-full px-3 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-wider text-gray-400 hover:bg-white/10 transition-all flex items-center justify-between group" data-id="Semua">
                            <div class="flex items-center gap-1.5">
                                <div class="w-3 h-3 rounded border border-white/20 flex items-center justify-center group-hover:border-gold-500 transition-colors">
                                    <i class="fas fa-check text-[7px] text-gold-500 check-icon" style="opacity:1"></i>
                                </div>
                                <span class="group-hover:text-white transition-colors">Semua Kategori</span>
                            </div>
                        </button>
                        <button onclick="toggleType('Jalan')" class="type-btn w-full px-3 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-wider text-gray-400 hover:bg-white/10 transition-all flex items-center justify-between group" data-type="Jalan">
                            <div class="flex items-center gap-1.5">
                                <div class="w-3 h-3 rounded border border-white/20 flex items-center justify-center group-hover:border-blue-400 transition-colors">
                                    <i class="fas fa-check text-[7px] text-blue-400 check-icon" style="opacity:1"></i>
                                </div>
                                <span class="group-hover:text-white transition-colors">Jalan</span>
                            </div>
                            <div class="w-3 h-3 rounded bg-blue-500"></div>
                        </button>
                        <button onclick="toggleType('Jembatan')" class="type-btn w-full px-3 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-wider text-gray-400 hover:bg-white/10 transition-all flex items-center justify-between group" data-type="Jembatan">
                            <div class="flex items-center gap-1.5">
                                <div class="w-3 h-3 rounded border border-white/20 flex items-center justify-center group-hover:border-indigo-400 transition-colors">
                                    <i class="fas fa-check text-[7px] text-indigo-400 check-icon" style="opacity:1"></i>
                                </div>
                                <span class="group-hover:text-white transition-colors">Jembatan</span>
                            </div>
                            <div class="w-3 h-3 rounded bg-indigo-500"></div>
                        </button>
                        <button onclick="toggleType('Titian')" class="type-btn w-full px-3 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-wider text-gray-400 hover:bg-white/10 transition-all flex items-center justify-between group" data-type="Titian">
                            <div class="flex items-center gap-1.5">
                                <div class="w-3 h-3 rounded border border-white/20 flex items-center justify-center group-hover:border-amber-400 transition-colors">
                                    <i class="fas fa-check text-[7px] text-amber-400 check-icon" style="opacity:1"></i>
                                </div>
                                <span class="group-hover:text-white transition-colors">Titian</span>
                            </div>
                            <div class="w-3 h-3 rounded bg-amber-500"></div>
                        </button>
                        <div class="h-[1px] bg-white/5 my-1"></div>
                        <button onclick="toggleKelurahanPoints()" class="w-full px-3 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-wider text-white hover:bg-white/10 transition-all flex items-center justify-between group" id="kel-toggle-btn-desktop">
                            <div class="flex items-center gap-1.5">
                                <div class="w-3 h-3 rounded border border-white/20 flex items-center justify-center group-hover:border-emerald-400 transition-colors">
                                    <i class="fas fa-check text-[7px] text-emerald-400 kel-check-icon-sync" style="opacity:1"></i>
                                </div>
                                <span class="group-hover:text-white transition-colors">Kelurahan</span>
                            </div>
                            <i class="fas fa-home text-emerald-500 text-xs"></i>
                        </button>
                    </div>
                </div>
                
                <!-- Wilayah Dropdown Button -->
                <div class="relative hidden md:block">
                    <button onclick="toggleMenu('territory-options-desktop')" class="flex-shrink-0 bg-white dark:bg-[#0f0e2c] border border-slate-200 dark:border-white/10 text-slate-600 dark:text-white px-4 py-2 rounded-xl text-xs font-black uppercase tracking-wider hover:bg-slate-50 dark:hover:bg-white/5 transition-all flex items-center gap-2 shadow-sm">
                        <i class="fas fa-map-location-dot text-gold-500"></i> <span id="current-kec-label-desktop">Semua Wilayah</span> <i class="fas fa-chevron-down text-[10px] ml-1"></i>
                    </button>
                    <div id="territory-options-desktop" class="hidden absolute top-full right-0 mt-2 p-1 bg-[#0f0e2c]/95 backdrop-blur-xl rounded-xl border border-white/10 shadow-2xl flex flex-col gap-1 min-w-[200px] max-h-[50vh] overflow-y-auto custom-scrollbar">
                        <button onclick="toggleKecamatan('Semua')" class="w-full px-3 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-wider text-emerald-400 hover:bg-white/10 transition-all flex items-center justify-between group border-b border-white/5 mb-1" id="btn-select-all-kec-desktop">
                            <div class="flex items-center gap-1.5">
                                <div class="w-3 h-3 rounded border border-emerald-400/50 flex items-center justify-center group-hover:border-emerald-400 transition-colors">
                                    <i class="fas fa-check text-[7px] text-emerald-400 icon-select-all-kec-sync" style="opacity:1"></i>
                                </div>
                                <span class="group-hover:text-white transition-colors">Pilih Semua</span>
                            </div>
                        </button>
                        @foreach($kecamatan as $kec)
                        <button onclick="toggleKecamatan('{{ $kec->id_kecamatan }}')" class="kec-btn w-full px-3 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-wider text-slate-400 hover:bg-white/10 transition-all flex items-center justify-between group" data-id="{{ $kec->id_kecamatan }}">
                            <div class="flex items-center gap-1.5">
                                <div class="w-3 h-3 rounded border border-white/20 flex items-center justify-center group-hover:border-gold-500 transition-colors shrink-0">
                                    <i class="fas fa-check text-[7px] text-gold-500 check-icon" style="opacity:1"></i>
                                </div>
                                <span class="whitespace-normal leading-tight group-hover:text-white transition-colors text-left">{{ $kec->nama_kecamatan }}</span>
                            </div>
                            <div class="w-3 h-3 rounded border border-white/10 shrink-0" style="background-color: {{ $kec->warna ?? '#6366f1' }};"></div>
                        </button>
                        @endforeach
                    </div>
                </div>

            </div>

            <!-- Mobile Filter Sheet / Dropdown Modal (Only visible on mobile when toggled) -->
            <div id="mobile-filter-sheet" class="hidden fixed inset-x-0 bottom-0 md:hidden z-[2000] pointer-events-auto transition-transform duration-300 translate-y-full">
                <div class="bg-[#1e1b4b]/95 backdrop-blur-xl rounded-t-[2.5rem] border-t border-white/10 shadow-2xl w-full flex flex-col max-h-[85vh]">
                    
                    <!-- Mobile Close Button & Handle (Sticky Top) -->
                    <div class="flex-shrink-0 p-6 pb-2 border-b border-white/5">
                        <div class="w-12 h-1.5 bg-white/20 rounded-full mx-auto mb-2"></div>
                        <div class="flex justify-between items-center">
                            <h4 class="text-white font-black text-lg uppercase tracking-wider">Filter Peta</h4>
                            <button onclick="document.getElementById('mobile-filter-sheet').classList.add('translate-y-full'); setTimeout(() => document.getElementById('mobile-filter-sheet').classList.add('hidden'), 300);" class="w-8 h-8 rounded-full bg-white/10 flex items-center justify-center text-white hover:bg-white/20 transition-all">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Scrollable Content -->
                    <div class="overflow-y-auto custom-scrollbar flex-col gap-4 p-6 pt-4 pb-10 flex-1">
                        
                        <!-- Statistik Section (Mobile) -->
                        <div class="w-full">
                            <h5 class="text-white text-xs font-black uppercase tracking-wider mb-2 opacity-80">Statistik</h5>
                            <div class="bg-[#0f0e2c]/90 rounded-xl border border-white/5 p-2 flex flex-col">
                                <div class="w-full px-3 py-2 rounded-lg text-[10px] font-black uppercase tracking-wider text-gray-300 flex items-center justify-between border-b border-white/5">
                                    <span>Total Laporan</span>
                                    <span id="stat-total" class="text-xs font-black text-blue-400 bg-blue-500/10 px-2 py-0.5 rounded border border-blue-400/20">0</span>
                                </div>
                                <div id="dynamic-stats-container" class="flex flex-col w-full gap-1 mt-2"></div>
                            </div>
                        </div>

                        <!-- Category Section (Mobile) -->
                        <div class="w-full">
                            <h5 class="text-white text-xs font-black uppercase tracking-wider mb-2 opacity-80">Kategori</h5>
                            <div class="bg-[#0f0e2c]/90 rounded-xl border border-white/5 p-2 flex flex-col gap-1">
                                <button onclick="toggleType('Semua')" class="type-btn w-full px-3 py-2 rounded-lg text-[10px] font-black uppercase tracking-wider text-gray-400 hover:bg-white/10 transition-all flex items-center justify-between group" data-id="Semua">
                                    <div class="flex items-center gap-2">
                                        <div class="w-4 h-4 rounded border border-white/20 flex items-center justify-center group-hover:border-gold-500 transition-colors">
                                            <i class="fas fa-check text-[8px] text-gold-500 check-icon" style="opacity:1"></i>
                                        </div>
                                        <span class="group-hover:text-white transition-colors">Semua Kategori</span>
                                    </div>
                                </button>
                                <button onclick="toggleType('Jalan')" class="type-btn w-full px-3 py-2 rounded-lg text-[10px] font-black uppercase tracking-wider text-gray-400 hover:bg-white/10 transition-all flex items-center justify-between group" data-type="Jalan">
                                    <div class="flex items-center gap-2">
                                        <div class="w-4 h-4 rounded border border-white/20 flex items-center justify-center group-hover:border-blue-400 transition-colors">
                                            <i class="fas fa-check text-[8px] text-blue-400 check-icon" style="opacity:1"></i>
                                        </div>
                                        <span class="group-hover:text-white transition-colors">Jalan</span>
                                    </div>
                                    <div class="w-3 h-3 rounded bg-blue-500"></div>
                                </button>
                                <button onclick="toggleType('Jembatan')" class="type-btn w-full px-3 py-2 rounded-lg text-[10px] font-black uppercase tracking-wider text-gray-400 hover:bg-white/10 transition-all flex items-center justify-between group" data-type="Jembatan">
                                    <div class="flex items-center gap-2">
                                        <div class="w-4 h-4 rounded border border-white/20 flex items-center justify-center group-hover:border-indigo-400 transition-colors">
                                            <i class="fas fa-check text-[8px] text-indigo-400 check-icon" style="opacity:1"></i>
                                        </div>
                                        <span class="group-hover:text-white transition-colors">Jembatan</span>
                                    </div>
                                    <div class="w-3 h-3 rounded bg-indigo-500"></div>
                                </button>
                                <button onclick="toggleType('Titian')" class="type-btn w-full px-3 py-2 rounded-lg text-[10px] font-black uppercase tracking-wider text-gray-400 hover:bg-white/10 transition-all flex items-center justify-between group" data-type="Titian">
                                    <div class="flex items-center gap-2">
                                        <div class="w-4 h-4 rounded border border-white/20 flex items-center justify-center group-hover:border-amber-400 transition-colors">
                                            <i class="fas fa-check text-[8px] text-amber-400 check-icon" style="opacity:1"></i>
                                        </div>
                                        <span class="group-hover:text-white transition-colors">Titian</span>
                                    </div>
                                    <div class="w-3 h-3 rounded bg-amber-500"></div>
                                </button>
                                <div class="h-[1px] bg-white/5 my-1"></div>
                                <button onclick="toggleKelurahanPoints()" class="w-full px-3 py-2 rounded-lg text-[10px] font-black uppercase tracking-wider text-white hover:bg-white/10 transition-all flex items-center justify-between group" id="kel-toggle-btn-mobile">
                                    <div class="flex items-center gap-2">
                                        <div class="w-4 h-4 rounded border border-white/20 flex items-center justify-center group-hover:border-emerald-400 transition-colors">
                                            <i class="fas fa-check text-[8px] text-emerald-400 kel-check-icon-sync" style="opacity:1"></i>
                                        </div>
                                        <span class="group-hover:text-white transition-colors">Kelurahan</span>
                                    </div>
                                    <i class="fas fa-home text-emerald-500"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Territory Section (Mobile) -->
                        <div class="w-full">
                            <h5 class="text-white text-xs font-black uppercase tracking-wider mb-2 opacity-80">Wilayah</h5>
                            <div class="bg-[#0f0e2c]/90 rounded-xl border border-white/5 p-2 flex flex-col gap-1 max-h-[30vh] overflow-y-auto custom-scrollbar">
                                <button onclick="toggleKecamatan('Semua')" class="w-full px-3 py-2 rounded-lg text-[10px] font-black uppercase tracking-wider text-emerald-400 hover:bg-white/10 transition-all flex items-center justify-between group border-b border-white/5 mb-1" id="btn-select-all-kec-mobile">
                                    <div class="flex items-center gap-2">
                                        <div class="w-4 h-4 rounded border border-emerald-400/50 flex items-center justify-center group-hover:border-emerald-400 transition-colors">
                                            <i class="fas fa-check text-[8px] text-emerald-400 icon-select-all-kec-sync" style="opacity:1"></i>
                                        </div>
                                        <span class="group-hover:text-white transition-colors">Pilih Semua</span>
                                    </div>
                                </button>
                                @foreach($kecamatan as $kec)
                                <button onclick="toggleKecamatan('{{ $kec->id_kecamatan }}')" class="kec-btn w-full px-3 py-2 rounded-lg text-[10px] font-black uppercase tracking-wider text-slate-400 hover:bg-white/10 transition-all flex items-center justify-between group" data-id="{{ $kec->id_kecamatan }}">
                                    <div class="flex items-center gap-2">
                                        <div class="w-4 h-4 rounded border border-white/20 flex items-center justify-center group-hover:border-gold-500 transition-colors shrink-0">
                                            <i class="fas fa-check text-[8px] text-gold-500 check-icon" style="opacity:1"></i>
                                        </div>
                                        <span class="whitespace-normal leading-tight group-hover:text-white transition-colors text-left">{{ $kec->nama_kecamatan }}</span>
                                    </div>
                                    <div class="w-3 h-3 rounded border border-white/10 shrink-0" style="background-color: {{ $kec->warna ?? '#6366f1' }};"></div>
                                </button>
                                @endforeach
                            </div>
                        </div>

                        <!-- Layer Section (Mobile) -->
                        <div class="w-full">
                            <h5 class="text-white text-xs font-black uppercase tracking-wider mb-2 opacity-80">Basemap Layer</h5>
                            <div class="bg-[#0f0e2c]/90 rounded-xl border border-white/5 p-2 flex flex-col gap-1">
                                <button onclick="changeBaseLayer('greyscale')" class="flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-white/10 transition-all group">
                                    <div class="w-8 h-8 rounded-md bg-gray-500/20 flex items-center justify-center text-gray-400 group-hover:bg-gray-500 group-hover:text-white transition-all">
                                        <i class="fas fa-adjust text-sm"></i>
                                    </div>
                                    <span class="text-[10px] font-black uppercase tracking-wider text-gray-300 group-hover:text-white">Greyscale</span>
                                </button>
                                <button onclick="changeBaseLayer('satellite')" class="flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-white/10 transition-all group">
                                    <div class="w-8 h-8 rounded-md bg-emerald-500/20 flex items-center justify-center text-emerald-400 group-hover:bg-emerald-500 group-hover:text-white transition-all">
                                        <i class="fas fa-satellite text-sm"></i>
                                    </div>
                                    <span class="text-[10px] font-black uppercase tracking-wider text-gray-300 group-hover:text-white">Satelit</span>
                                </button>
                                <button onclick="changeBaseLayer('osm')" class="flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-white/10 transition-all group">
                                    <div class="w-8 h-8 rounded-md bg-amber-500/20 flex items-center justify-center text-amber-400 group-hover:bg-amber-500 group-hover:text-white transition-all">
                                        <i class="fas fa-map-marked-alt text-sm"></i>
                                    </div>
                                    <span class="text-[10px] font-black uppercase tracking-wider text-gray-300 group-hover:text-white">OSM</span>
                                </button>
                                <button onclick="changeBaseLayer('dark')" class="flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-white/10 transition-all group">
                                    <div class="w-8 h-8 rounded-md bg-indigo-500/20 flex items-center justify-center text-indigo-400 group-hover:bg-indigo-500 group-hover:text-white transition-all">
                                        <i class="fas fa-moon text-sm"></i>
                                    </div>
                                    <span class="text-[10px] font-black uppercase tracking-wider text-gray-300 group-hover:text-white">Gelap</span>
                                </button>
                                <button onclick="changeBaseLayer('street')" class="flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-white/10 transition-all group">
                                    <div class="w-8 h-8 rounded-md bg-blue-500/20 flex items-center justify-center text-blue-400 group-hover:bg-blue-500 group-hover:text-white transition-all">
                                        <i class="fas fa-map text-sm"></i>
                                    </div>
                                    <span class="text-[10px] font-black uppercase tracking-wider text-gray-300 group-hover:text-white">Default</span>
                                </button>
                                <div class="h-[1px] bg-white/10 my-1 mx-2"></div>
                                <button onclick="toggleFloodLayer()" class="flex items-center justify-between px-3 py-2 rounded-xl hover:bg-white/10 transition-all group w-full text-left">
                                    <div class="flex items-center gap-3">
                                        <i class="fas fa-water text-blue-400 text-sm"></i>
                                        <span class="text-[10px] font-black uppercase tracking-wider text-slate-300 group-hover:text-white transition-colors">Banjir</span>
                                    </div>
                                    <div class="w-8 h-4 rounded-full bg-slate-700 relative border border-white/10 transition-colors" id="flood-toggle-bg">
                                        <div id="flood-toggle-dot" class="absolute left-[3px] top-[2px] w-3 h-3 bg-slate-400 rounded-full transition-all"></div>
                                    </div>
                                </button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="flex-1 relative">
            <div id="main-map" class="absolute inset-0 z-0"></div>

        </div>
    </main>

    <script>
        const baseLayers = {
            greyscale: L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png'),
            satellite: L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}'),
            osm: L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png'),
            dark: L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png'),
            street: L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png')
        };

        const map = L.map('main-map', { zoomControl: false, attributionControl: false }).setView([-3.316694, 114.590111], 13);
        let currentBaseLayer = baseLayers.osm.addTo(map);

        // Create panes to manage layering
        map.createPane('polygonsPane');
        map.getPane('polygonsPane').style.zIndex = 400;
        map.createPane('markersPane');
        map.getPane('markersPane').style.zIndex = 650;

        const dataPoints = @json($infrastruktur);
        const kecamatans = @json($kecamatan);
        const kelurahans = @json($kelurahan);
        let activeMarkers = [];
        let kelurahanMarkers = [];
        let kelurahanPolygons = [];
        let showKelurahan = true;
        const geoLayers = {};
        
        // --- Layer Rawan Banjir (Mock) ---
        let showFloodLayer = false;
        const floodLayer = L.layerGroup([
            L.circle([-3.315, 114.590], { color: '#ef4444', fillColor: '#ef4444', fillOpacity: 0.2, weight: 1, radius: 800 }).bindPopup('<div class="text-center"><p class="text-xs font-black text-red-500 uppercase">Zona Merah</p><p class="text-xs">Rawan Banjir Tinggi</p></div>'),
            L.circle([-3.325, 114.598], { color: '#f59e0b', fillColor: '#f59e0b', fillOpacity: 0.2, weight: 1, radius: 1200 }).bindPopup('<div class="text-center"><p class="text-xs font-black text-orange-500 uppercase">Zona Kuning</p><p class="text-xs">Rawan Banjir Sedang</p></div>'),
            L.circle([-3.295, 114.580], { color: '#ef4444', fillColor: '#ef4444', fillOpacity: 0.2, weight: 1, radius: 900 }).bindPopup('<div class="text-center"><p class="text-xs font-black text-red-500 uppercase">Zona Merah</p><p class="text-xs">Rawan Banjir Tinggi</p></div>'),
            L.circle([-3.330, 114.570], { color: '#f59e0b', fillColor: '#f59e0b', fillOpacity: 0.2, weight: 1, radius: 1000 }).bindPopup('<div class="text-center"><p class="text-xs font-black text-orange-500 uppercase">Zona Kuning</p><p class="text-xs">Rawan Banjir Sedang</p></div>')
        ]);

        function toggleFloodLayer() {
            showFloodLayer = !showFloodLayer;
            const bg = document.getElementById('flood-toggle-bg');
            const dot = document.getElementById('flood-toggle-dot');
            
            if(showFloodLayer) {
                map.addLayer(floodLayer);
                bg.classList.replace('bg-slate-700', 'bg-blue-500');
                dot.classList.replace('bg-slate-400', 'bg-white dark:bg-[#1e1b4b]');
                dot.classList.replace('left-[2px]', 'left-[14px]');
            } else {
                map.removeLayer(floodLayer);
                bg.classList.replace('bg-blue-500', 'bg-slate-700');
                dot.classList.replace('bg-white dark:bg-[#1e1b4b]', 'bg-slate-400');
                dot.classList.replace('left-[14px]', 'left-[2px]');
            }
        }
        // ---------------------------------

        // Render Polygons first in lower pane
        kecamatans.forEach(kec => {
            if (kec.geometri) {
                try {
                    const geoData = typeof kec.geometri === 'string' ? JSON.parse(kec.geometri) : kec.geometri;
                    const poly = L.geoJSON(geoData, {
                        pane: 'polygonsPane',
                        style: {
                            fillColor: kec.warna || '#6366f1',
                            weight: 2.5,
                            opacity: 1,
                            color: 'white',
                            fillOpacity: 0.35
                        },
                        interactive: true
                    });

                    poly.on('mouseover', function() {
                        this.setStyle({ fillOpacity: 0.6, weight: 4 });
                    });
                    poly.on('mouseout', function() {
                        this.setStyle({ fillOpacity: 0.35, weight: 2.5 });
                    }); // Do NOT add to map yet — applyFilters() controls visibility

                    poly.bindPopup(`<p class="text-xs font-black text-navy-900 dark:text-white uppercase">${kec.nama_kecamatan}</p>`, { 
                        className: 'custom-polygon-popup', 
                        closeButton: false 
                    });
                    
                    poly.on('mouseover', function() { this.setStyle({ fillOpacity: 0.3, weight: 3 }); });
                    poly.on('mouseout', function() { this.setStyle({ fillOpacity: 0.15, weight: 2 }); });

                    geoLayers[kec.id_kecamatan] = poly;
                } catch (e) { console.error(e); }
            }
        });

        function renderMarkers(points) {
            activeMarkers.forEach(m => map.removeLayer(m));
            activeMarkers = [];

            points.forEach(point => {
                const kondisiAktual = point.analisis?.label_prioritas || point.kondisi;
                let isBaik = kondisiAktual.toLowerCase().includes('baik');
                let isRingan = kondisiAktual.toLowerCase().includes('ringan');
                let isSedang = kondisiAktual.toLowerCase().includes('sedang');
                let isBerat = !isBaik && !isRingan && !isSedang;
                
                // Override jika sudah selesai diperbaiki
                const isSelesai = point.status_perbaikan === 'Selesai';
                if (isSelesai) {
                    isBaik = true;
                    isRingan = false;
                    isSedang = false;
                    isBerat = false;
                }

                const color = isBaik ? '#10b981' : (isRingan ? '#facc15' : (isSedang ? '#f59e0b' : '#ef4444'));
                const pulseHtml = isBerat ? `<div class="absolute inset-0 rounded-full animate-ping bg-rose-500 opacity-75"></div>` : '';
                
                const icon = L.divIcon({
                    html: `<div class="relative w-[16px] h-[16px] group">
                               ${pulseHtml}
                               <div class="absolute inset-0 rounded-full border-[2.5px] border-white shadow-lg flex items-center justify-center" style="background-color: ${color}; z-index: 10;">
                                    ${isSelesai ? '<i class="fas fa-check text-[7px] text-white"></i>' : ''}
                               </div>
                           </div>`,
                    className: '', iconSize: [16, 16], iconAnchor: [8, 8]
                });
                let imagePath = point.foto_terbaru || '';
                if(imagePath && !imagePath.includes('infrastruktur/')) {
                    imagePath = 'infrastruktur/' + imagePath;
                }
                imagePath = imagePath.replace(/\\/g, '/');
                
                let finalUrl = '';
                const rawJenis = point.jenis || '-';
                if (imagePath) {
                    finalUrl = `/storage/${imagePath}`;
                } else {
                    const type = rawJenis.toLowerCase();
                    let typeStr = 'jalan';
                    if (type.includes('titian')) typeStr = 'titian';
                    else if (type.includes('jembatan')) typeStr = 'jembatan';
                    
                    let condStr = 'baik';
                    const pLower = kondisiAktual.toLowerCase();
                    if (pLower.includes('berat')) condStr = 'rusak_berat';
                    else if (pLower.includes('sedang') || pLower.includes('ringan')) condStr = 'rusak_sedang';

                    finalUrl = `/dummy_${typeStr}_${condStr}.jpg`;
                }

                const popupContent = `
                    <div class="p-1" style="min-width: 240px;">
                        <div class="relative h-32 rounded-2xl bg-slate-100 mb-3 overflow-hidden shadow-inner">
                            <img src="${finalUrl}" class="w-full h-full object-cover" onerror="this.style.display='none'">
                            <div class="absolute top-2 left-2 px-2 py-1 bg-white/90 dark:bg-[#1e1b4b]/90 backdrop-blur-md rounded-lg text-[7px] font-black uppercase tracking-widest text-navy-900 dark:text-white">
                                ${rawJenis}
                            </div>
                        </div>
                        <div class="px-1">
                            <h4 class="text-xs font-black text-navy-900 dark:text-white mb-1">${point.nama_objek || point.nama_infrastruktur || '-'}</h4>
                            <p class="text-xs text-slate-400 font-bold uppercase mb-3">Wilayah: ${point.kelurahan?.nama_kelurahan ?? '-'}</p>
                            
                            <div class="flex items-center gap-2 mb-2">
                                <span class="px-2 py-1 rounded-full text-[7px] font-black uppercase tracking-widest" style="background-color: ${color}15; color: ${color}; border: 1px solid ${color}30;">
                                    ${isSelesai ? 'SUDAH DIPERBAIKI' : kondisiAktual}
                                </span>
                                <span class="px-2 py-1 bg-navy-50 text-navy-600 rounded-full text-[7px] font-black uppercase border border-navy-100">
                                    By: ${point.user?.name ?? 'Surveyor'}
                                </span>
                            </div>

                            <div class="mb-3 flex items-center gap-1.5 text-[8px] font-bold text-slate-400 uppercase">
                                <i class="fas fa-clock"></i>
                                Update: ${new Date(point.updated_at).toLocaleDateString('id-ID', {day: 'numeric', month: 'short', year: 'numeric'})}
                            </div>

                            <a href="/tim-teknis/infrastruktur/${point.id_infrastruktur}" class="block w-full py-2 bg-navy-900 text-white rounded-xl text-xs font-black uppercase tracking-widest text-center hover:bg-gold-500 transition-all shadow-lg shadow-navy-900/10">Lihat Detail</a>
                        </div>
                    </div>
                `;

                const marker = L.marker([point.latitude, point.longitude], {
                    icon: icon,
                    pane: 'markersPane'
                }).addTo(map)
                .bindPopup(popupContent, { className: 'premium-popup', maxWidth: 300 });
                // PENGHAPUSAN TOOLTIP KONDISI SESUAI PERMINTAAN USER
                
                activeMarkers.push(marker);
            });
        }

        function renderKelurahanData() {
            kelurahanMarkers.forEach(m => map.removeLayer(m));
            kelurahanPolygons.forEach(p => map.removeLayer(p));
            kelurahanMarkers = [];
            kelurahanPolygons = [];

            if (!showKelurahan) return;

            kelurahans.forEach(kel => {
                // 1. Render Poligon Kelurahan (Hanya Garis Tepi)
                if (kel.geometri) {
                    try {
                        const geoData = typeof kel.geometri === 'string' ? JSON.parse(kel.geometri) : kel.geometri;
                        const poly = L.geoJSON(geoData, {
                            pane: 'polygonsPane',
                            filter: function(feature) {
                                return feature.geometry.type !== 'Point';
                            },
                            style: {
                                fillColor: 'transparent',
                                weight: 2,
                                opacity: 0.8,
                                color: '#94a3b8',
                                fillOpacity: 0,
                                dashArray: '5, 5' // Tetap putus-putus
                            }
                        }).addTo(map);

                        poly.bindPopup(`
                            <div class="px-2 py-0.5 text-center">
                                <p class="text-xs font-black text-slate-500 uppercase tracking-[0.2em] mb-0.5">Kelurahan</p>
                                <p class="text-xs font-black uppercase tracking-widest text-navy-900 dark:text-white">${kel.nama_kelurahan}</p>
                            </div>
                        `, { 
                            className: 'custom-polygon-popup', 
                            closeButton: false,
                            offset: [0, -5]
                        });

                        poly.on('mouseover', function() { this.setStyle({ color: '#f1f5f9', weight: 3, opacity: 1, dashArray: '' }); });
                        poly.on('mouseout', function() { this.setStyle({ color: '#94a3b8', weight: 2, opacity: 0.8, dashArray: '5, 5' }); });

                        kelurahanPolygons.push(poly);
                    } catch (e) { console.error(e); }
                }
            });
        }

        function toggleKelurahanPoints() {
            showKelurahan = !showKelurahan;
            document.querySelectorAll('.kel-check-icon-sync').forEach(icon => {
                icon.style.opacity = showKelurahan ? '1' : '0';
            });
            document.querySelectorAll('#kel-toggle-btn-mobile, #kel-toggle-btn-desktop').forEach(btn => {
                if(btn) btn.classList.toggle('text-white', showKelurahan);
            });
            renderKelurahanData();
        }

        // 1. Inisialisasi: Semua Objek & Wilayah Aktif by Default
        const allAvailableTypes = ['Jalan', 'Jembatan', 'Titian'];
        let activeTypes = [...allAvailableTypes];
        let activeKecs = kecamatans.map(k => k.id_kecamatan.toString());
        const totalKec = kecamatans.length;

        function applyFilters() {
            const searchQuery = document.getElementById('map-search') ? document.getElementById('map-search').value.toLowerCase().trim() : '';
            // ... (logika poligon tetap sama)
            Object.keys(geoLayers).forEach(id => {
                if (activeKecs.includes(id.toString())) {
                    if (!map.hasLayer(geoLayers[id])) geoLayers[id].addTo(map);
                } else {
                    if (map.hasLayer(geoLayers[id])) map.removeLayer(geoLayers[id]);
                }
            });

            if (activeTypes.length === 0) {
                renderMarkers([]);
                updateStats([]);
                return;
            }

            const normalisedActiveTypes = activeTypes.map(t => t.toLowerCase().trim());
            let filtered = dataPoints.filter(p => {
                const pType = (p.jenis || '').toLowerCase().trim();
                const typeMatch = normalisedActiveTypes.some(type => pType.includes(type));
                const kecId = p.kelurahan?.id_kecamatan?.toString() || p.id_kecamatan?.toString();
                const kecMatch = !kecId || activeKecs.includes(kecId);
                const namaObjek = (p.nama_objek || '').toLowerCase();
                
                const matchesSearch = searchQuery === '' || 
                                      namaObjek.includes(searchQuery) || 
                                      pType.includes(searchQuery);
                                      
                return typeMatch && kecMatch && matchesSearch;
            });
            
            renderMarkers(filtered);
            updateStats(filtered);
        }

        function updateStats(points) {
            const totalElems = [
                document.getElementById('stat-total'),
                document.getElementById('stat-total-desktop')
            ];
            totalElems.forEach(el => { if (el) el.textContent = points.length; });
            
            const fixedCounts = {
                'Kondisi Baik': 0,
                'Kondisi Rusak Ringan': 0,
                'Kondisi Rusak Sedang': 0,
                'Kondisi Rusak Berat': 0,
                'Sudah Diperbaiki': 0
            };

            points.forEach(p => {
                let label = p.cnn?.label_kondisi || p.analisis?.label_prioritas || p.kondisi || 'Tidak Diketahui';
                let lowerLabel = label.toLowerCase();
                
                if (p.status_perbaikan === 'Selesai') {
                    fixedCounts['Sudah Diperbaiki']++;
                } else if (lowerLabel.includes('baik')) {
                    fixedCounts['Kondisi Baik']++;
                } else if (lowerLabel.includes('ringan')) {
                    fixedCounts['Kondisi Rusak Ringan']++;
                } else if (lowerLabel.includes('sedang')) {
                    fixedCounts['Kondisi Rusak Sedang']++;
                } else if (lowerLabel.includes('berat')) {
                    fixedCounts['Kondisi Rusak Berat']++;
                }
            });

            const containers = [
                document.getElementById('dynamic-stats-container'), 
                document.getElementById('dynamic-stats-container-desktop')
            ];
            
            containers.forEach(container => {
                if(container) {
                    container.innerHTML = '';
                
                const displayOrder = ['Kondisi Baik', 'Kondisi Rusak Ringan', 'Kondisi Rusak Sedang', 'Kondisi Rusak Berat'];
                if (fixedCounts['Sudah Diperbaiki'] > 0) {
                    displayOrder.push('Sudah Diperbaiki');
                }
                
                displayOrder.forEach(label => {
                    const count = fixedCounts[label];
                    
                    const isBaik = label.toLowerCase().includes('baik') || label.toLowerCase().includes('selesai');
                    const isRingan = label.toLowerCase().includes('ringan');
                    const isSedang = label.toLowerCase().includes('sedang');
                    const isBerat = !isBaik && !isSedang && !isRingan;
                    
                    let colorClass = isBaik ? 'text-[#059669]' : (isRingan ? 'text-[#ca8a04]' : (isSedang ? 'text-[#d97706]' : 'text-[#be123c]'));
                    let bgClass = isBaik ? 'bg-[#059669]/10 border-[#059669]/20' : (isRingan ? 'bg-[#ca8a04]/10 border-[#ca8a04]/20' : (isSedang ? 'bg-[#d97706]/10 border-[#d97706]/20' : 'bg-[#be123c]/10 border-[#be123c]/20'));
                    let dotColor = isBaik ? 'bg-[#059669]' : (isRingan ? 'bg-[#ca8a04]' : (isSedang ? 'bg-[#d97706]' : 'bg-[#be123c]'));

                    const html = `
                        <div class="w-full px-3 py-1.5 rounded-lg text-[7px] font-black uppercase tracking-wider text-gray-400 flex items-center justify-between">
                            <div class="flex items-center gap-1.5 truncate pr-2">
                                <div class="w-1.5 h-1.5 rounded-full shrink-0 ${dotColor}"></div>
                                <span class="truncate" title="${label}">${label}</span>
                            </div>
                            <span class="text-[7px] font-black px-1.5 py-0.5 rounded border shrink-0 ${colorClass} ${bgClass}">${count}</span>
                        </div>
                    `;
                    container.insertAdjacentHTML('beforeend', html);
                });
                }
            });
        }

        function toggleType(type) {
            if (type === 'Semua') {
                activeTypes = activeTypes.length === allAvailableTypes.length ? [] : [...allAvailableTypes];
            } else {
                if (activeTypes.includes(type)) {
                    activeTypes = activeTypes.filter(t => t !== type);
                } else {
                    activeTypes.push(type);
                }
            }

            // Update UI
            const allChecked = activeTypes.length === allAvailableTypes.length;
            document.querySelectorAll('.type-btn').forEach(btn => {
                const bId = btn.getAttribute('data-id');
                const bType = btn.getAttribute('data-type');
                const isActive = bId === 'Semua' ? allChecked : activeTypes.includes(bType);
                const icon = btn.querySelector('.check-icon');
                if (icon) icon.style.opacity = isActive ? '1' : '0';
                btn.classList.toggle('text-white', isActive);
            });

            const label = activeTypes.length === 0 ? 'Kategori Objek' :
                          activeTypes.length === allAvailableTypes.length ? 'Semua Kategori' :
                          activeTypes.join(', ');
            document.getElementById('current-cat-label-desktop').textContent = label;
            applyFilters();
        }

        function toggleKecamatan(kecId) {
            if (kecId === 'Semua') {
                if (activeKecs.length === totalKec) {
                    activeKecs = [];
                } else {
                    activeKecs = kecamatans.map(k => k.id_kecamatan.toString());
                }
            } else {
                kecId = kecId.toString();
                if (activeKecs.includes(kecId)) {
                    activeKecs = activeKecs.filter(k => k !== kecId);
                } else {
                    activeKecs.push(kecId);
                    if (geoLayers[kecId]) map.fitBounds(geoLayers[kecId].getBounds(), { padding: [50, 50] });
                }
            }
            applyFilters();
            updateSelectAllStatus();
        }

        function updateSelectAllStatus() {
            const isAllSelected = activeKecs.length === totalKec && totalKec > 0;
            
            document.querySelectorAll('.icon-select-all-kec-sync').forEach(icon => {
                icon.style.opacity = isAllSelected ? '1' : '0.2';
            });
            
            document.querySelectorAll('#btn-select-all-kec-mobile, #btn-select-all-kec-desktop').forEach(btn => {
                if(btn) btn.classList.toggle('text-emerald-400', isAllSelected);
            });

            document.querySelectorAll('.kec-btn').forEach(btn => {
                const id = btn.getAttribute('data-id');
                if (id === 'Semua') return;
                const isActive = activeKecs.includes(id);
                const icon = btn.querySelector('.check-icon');
                if (icon) icon.style.opacity = isActive ? '1' : '0';
                btn.classList.toggle('text-white', isActive);
            });

            // Update label
            const label = activeKecs.length === 0 ? 'Saring Kecamatan' :
                          activeKecs.length === totalKec ? 'Semua Wilayah' :
                          activeKecs.length + ' Wilayah Dipilih';
            document.getElementById('current-kec-label-desktop').textContent = label;
        }

        function toggleMenu(id) { document.getElementById(id).classList.toggle('hidden'); }
        function changeBaseLayer(type) {
            map.removeLayer(currentBaseLayer);
            currentBaseLayer = baseLayers[type].addTo(map);
            toggleMenu('layer-options');
        }

        // Auto-close dropdowns when clicking outside
        document.addEventListener('click', function(e) {
            const isClickInsideMenu = e.target.closest('#condition-options-desktop') || e.target.closest('#layer-options-desktop') || e.target.closest('#category-options-desktop') || e.target.closest('#territory-options-desktop');
            const isClickOnButton = e.target.closest('button[onclick^="toggleMenu"]');
            
            if (!isClickInsideMenu && !isClickOnButton) {
                ['condition-options-desktop', 'layer-options-desktop', 'category-options-desktop', 'territory-options-desktop'].forEach(id => {
                    const el = document.getElementById(id);
                    if (el) el.classList.add('hidden');
                });
            }
        });

        applyFilters(); // Initialize map state correctly
        renderKelurahanData(); // Render kelurahan data initially

        function updateClock() {
            const now = new Date();
            document.getElementById('mini-clock').textContent = `${String(now.getHours()).padStart(2, '0')}:${String(now.getMinutes()).padStart(2, '0')} WITA`;
        }
        setInterval(updateClock, 1000); updateClock();

        const searchInput = document.getElementById('map-search');
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                applyFilters();
            });
        }
    </script>

    <style>
        .active-filter { background: rgba(255,255,255,0.15) !important; color: white !important; }
        .active-kec { background: rgba(255,255,255,0.15) !important; color: white !important; }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 10px; }
        .premium-popup .leaflet-popup-content-wrapper { border-radius: 1.5rem; padding: 5px; }
        .premium-popup .leaflet-popup-tip-container { display: none; }
        .custom-polygon-popup .leaflet-popup-content-wrapper { background: rgba(255,255,255,0.9) !important; backdrop-filter: blur(4px); border-radius: 8px !important; padding: 2px !important; }
    </style>
</body>
</html>
