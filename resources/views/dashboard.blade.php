<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GEO-SINFRA | Kota Banjarmasin</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <style>
        body { font-family: 'Inter', sans-serif; }
        .bg-government-gradient {
            background: linear-gradient(135deg, #1e3a8a 0%, #1e1b4b 100%);
        }
        #map { height: 500px; border-radius: 1rem; z-index: 10; }
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
                    <a href="#" class="text-gray-600 hover:text-blue-700 font-semibold transition text-sm">Peta Sebaran</a>
                    <a href="{{ url('/login') }}" class="inline-flex items-center px-8 py-2.5 bg-[#5c56e1] text-white text-sm font-bold rounded-xl hover:bg-blue-800 shadow-lg shadow-blue-200 transition-all active:scale-95 uppercase tracking-wider">LOGIN
                   </a>
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
        
        <div id="map" class="shadow-2xl border-[10px] border-white ring-1 ring-gray-200"></div>
    </main>

    <footer class="bg-white border-t border-gray-200 py-10 text-center">
        <p class="text-gray-500 text-sm italic">&copy; 2026 GEO-SINFRA - Pemerintah Kota Banjarmasin.</p>
    </footer>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        var map = L.map('map').setView([-3.316694, 114.590111], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
        L.marker([-3.316694, 114.590111]).addTo(map).bindPopup("<b>Banjarmasin Tengah</b>");
    </script>
</body>
</html>