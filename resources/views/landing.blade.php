<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GEO-SINFRA | DISPERKIM Kota Banjarmasin</title>
    
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
                <a href="{{ url('/login') }}" class="btn-internal">Portal Internal</a>
            </div>
        </div>
    </nav>

    <!-- Hero -->
    <section class="hero-section h-[450px] flex items-center text-center md:text-left">
        <div class="max-w-7xl mx-auto px-8 w-full fade-up" style="animation-delay: 0.8s;">
            <div class="max-w-2xl">
                <p class="text-gold font-black text-[10px] uppercase tracking-[0.4em] mb-4">Layanan Transparansi Data</p>
                <h3 class="text-4xl md:text-6xl font-black text-white tracking-tighter leading-tight mb-6">Selamat Datang di <br> <span class="text-gold">GEO-SINFRA</span></h3>
                <p class="text-gray-300 font-bold text-lg mb-8">Sistem Informasi Pemetaan Infrastruktur Permukiman Kota Banjarmasin</p>
                <a href="#peta" class="inline-block bg-gold text-white px-8 py-4 rounded-xl font-black text-[11px] uppercase tracking-widest hover:bg-white hover:text-navy transition-all shadow-lg">Lihat Peta Publik</a>
            </div>
        </div>
    </section>

    <!-- Statistik -->
    <section id="statistik" class="py-20">
        <div class="max-w-7xl mx-auto px-8">
            <div class="text-center mb-16">
                <h4 class="text-navy font-black text-2xl tracking-tighter">STATISTIK INFRASTRUKTUR KOTA</h4>
                <p class="text-gray-400 text-sm mt-2">Ringkasan titik data yang telah terdata di sistem.</p>
            </div>
            
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="card-stat border-t-4 border-navy">
                    <i class="fas fa-database text-navy text-2xl mb-4"></i>
                    <p class="text-3xl font-black text-navy">{{ number_format($stats['total'] ?? 0) }}</p>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1">Total Data</p>
                </div>
                <div class="card-stat border-t-4 border-gold">
                    <i class="fas fa-map-marked-alt text-gold text-2xl mb-4"></i>
                    <p class="text-3xl font-black text-navy">{{ number_format($stats['kecamatan'] ?? 0) }}</p>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1">Kecamatan</p>
                </div>
                <div class="card-stat border-t-4 border-red-500">
                    <i class="fas fa-exclamation-triangle text-red-500 text-2xl mb-4"></i>
                    <p class="text-3xl font-black text-navy">{{ number_format($stats['rusak_berat'] ?? 0) }}</p>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1">Rusak Berat</p>
                </div>
                <div class="card-stat bg-navy text-white">
                    <i class="fas fa-robot text-gold text-2xl mb-4"></i>
                    <p class="text-3xl font-black text-white">{{ $stats['akurasi_ai'] ?? 0 }}%</p>
                    <p class="text-[10px] font-bold text-gold uppercase tracking-widest mt-1">Akurasi AI</p>
                </div>
            </div>

            <!-- Detail Statistik Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mt-12">
                <!-- Sebaran Perkecamatan -->
                <div class="bg-white p-8 rounded-2xl border border-gray-100 shadow-sm">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-2 h-6 bg-gold rounded-full"></div>
                        <h5 class="text-sm font-black text-navy uppercase tracking-widest">Sebaran Perkecamatan</h5>
                    </div>
                    <div class="space-y-4">
                        @foreach($sebaranKecamatan as $nama => $count)
                            <div>
                                <div class="flex justify-between text-[10px] font-bold uppercase mb-1">
                                    <span class="text-gray-500">{{ $nama ?: 'Tanpa Wilayah' }}</span>
                                    <span class="text-navy">{{ $count }} Titik</span>
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
                <h4 class="text-navy font-black text-2xl tracking-tighter leading-none mb-3">Eksplorasi Peta Sebaran</h4>
                <p class="text-gray-400 text-sm">Visualisasi interaktif sebaran infrastruktur permukiman di seluruh wilayah.</p>
            </div>

            <div class="relative bg-white rounded-3xl shadow-2xl border border-gray-100 overflow-hidden" style="height: 650px;">
                <!-- Map Container -->
                <div id="map" class="absolute inset-0 z-10"></div>

                <!-- Floating Stats Counter -->
                <div class="absolute top-6 left-6 z-20 bg-navy/90 backdrop-blur-md text-white px-6 py-4 rounded-2xl shadow-xl border border-white/10">
                    <p class="text-[10px] font-bold text-gold uppercase tracking-widest mb-1">Hasil Filter</p>
                    <div class="flex items-baseline gap-2">
                        <span id="visible-count" class="text-3xl font-black">0</span>
                        <span class="text-[10px] font-bold text-gray-400 uppercase">Titik Ditemukan</span>
                    </div>
                </div>

                <!-- Floating Basemap Toggle -->
                <div class="absolute top-6 right-6 z-20 flex gap-2">
                    <button onclick="setBasemap('google')" class="basemap-btn active bg-white/90 backdrop-blur-md px-4 py-2 rounded-xl text-[10px] font-bold uppercase tracking-widest shadow-lg hover:bg-navy hover:text-white transition-all border border-gray-100">Peta</button>
                    <button onclick="setBasemap('satelit')" class="basemap-btn bg-white/90 backdrop-blur-md px-4 py-2 rounded-xl text-[10px] font-bold uppercase tracking-widest shadow-lg hover:bg-navy hover:text-white transition-all border border-gray-100">Satelit</button>
                </div>

                <!-- Side Filter Panel (Floating) -->
                <div class="absolute bottom-6 right-6 z-20 w-80 space-y-4">
                    <!-- Legenda Peta -->
                    <div class="bg-white/90 backdrop-blur-md p-6 rounded-2xl shadow-xl border border-gray-100">
                        <h5 class="text-[10px] font-black text-navy uppercase tracking-widest mb-4 flex items-center gap-2">
                            <i class="fas fa-info-circle text-gold"></i> Legenda Peta
                        </h5>
                        <div class="grid grid-cols-1 gap-3">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-blue-500 flex items-center justify-center text-white shadow-sm"><i class="fas fa-road text-xs"></i></div>
                                <span class="text-[10px] font-bold text-navy uppercase">Infrastruktur Jalan</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-amber-500 flex items-center justify-center text-white shadow-sm"><i class="fas fa-bridge text-xs"></i></div>
                                <span class="text-[10px] font-bold text-navy uppercase">Infrastruktur Titian</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-emerald-500 flex items-center justify-center text-white shadow-sm"><i class="fas fa-toilet text-xs"></i></div>
                                <span class="text-[10px] font-bold text-navy uppercase">Sarana Sanitasi</span>
                            </div>
                        </div>
                    </div>

                    <!-- Kategori Filter -->
                    <div class="bg-white/90 backdrop-blur-md p-6 rounded-2xl shadow-xl border border-gray-100">
                        <h5 class="text-[10px] font-black text-navy uppercase tracking-widest mb-4 flex items-center gap-2">
                            <i class="fas fa-filter text-gold"></i> Filter Kategori
                        </h5>
                        <div class="grid grid-cols-1 gap-2">
                            <label class="flex items-center justify-between p-2 bg-gray-50/50 rounded-lg cursor-pointer hover:bg-gold/10 transition-all group">
                                <span class="text-[10px] font-bold text-navy uppercase">Pilih Semua Jalan</span>
                                <input type="checkbox" class="filter-category w-4 h-4 rounded border-gray-300 text-navy focus:ring-navy" value="jalan" checked>
                            </label>
                            <label class="flex items-center justify-between p-2 bg-gray-50/50 rounded-lg cursor-pointer hover:bg-gold/10 transition-all group">
                                <span class="text-[10px] font-bold text-navy uppercase">Pilih Semua Titian</span>
                                <input type="checkbox" class="filter-category w-4 h-4 rounded border-gray-300 text-navy focus:ring-navy" value="titian" checked>
                            </label>
                            <label class="flex items-center justify-between p-2 bg-gray-50/50 rounded-lg cursor-pointer hover:bg-gold/10 transition-all group">
                                <span class="text-[10px] font-bold text-navy uppercase">Pilih Semua Sanitasi</span>
                                <input type="checkbox" class="filter-category w-4 h-4 rounded border-gray-300 text-navy focus:ring-navy" value="sanitasi" checked>
                            </label>
                        </div>
                    </div>

                    <!-- Wilayah Filter & Status Data -->
                    <div class="bg-white/90 backdrop-blur-md p-6 rounded-2xl shadow-xl border border-gray-100">
                        <h5 class="text-[10px] font-black text-navy uppercase tracking-widest mb-4 flex items-center gap-2">
                            <i class="fas fa-database text-gold"></i> Status Wilayah Terdata
                        </h5>
                        <div class="space-y-1 max-h-[150px] overflow-y-auto pr-2 custom-scrollbar">
                            @foreach($semuaWilayah as $wil)
                                @php $count = $dataInfrastruktur->where('id_kecamatan', $wil->id_kecamatan)->count(); @endphp
                                <label class="district-item flex items-center justify-between p-2 {{ $count > 0 ? 'bg-gold/5' : '' }} hover:bg-gold/10 rounded-lg cursor-pointer transition-all border border-transparent {{ $count > 0 ? 'border-gold/20' : '' }}">
                                    <div class="flex flex-col">
                                        <span class="text-[9px] font-bold text-navy uppercase">{{ $wil->nama_kecamatan }}</span>
                                        <span class="text-[7px] font-bold {{ $count > 0 ? 'text-gold' : 'text-gray-400' }} uppercase">
                                            {{ $count > 0 ? $count . ' TITIK TERDATA' : 'BELUM ADA DATA' }}
                                        </span>
                                    </div>
                                    <input type="checkbox" class="filter-district w-4 h-4 rounded border-gray-300 text-navy focus:ring-navy" value="{{ $wil->id_kecamatan }}" checked>
                                </label>
                            @endforeach
                        </div>
                    </div>
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
        const map = L.map('map', { zoomControl: false }).setView([-3.316694, 114.590111], 13);
        
        const googleStreets = L.tileLayer('http://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}', { subdomains:['mt0','mt1','mt2','mt3'] }).addTo(map);
        const satelit = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}');
        
        const markersLayer = L.layerGroup().addTo(map);

        // Custom Icon Creator
        const createIcon = (type) => {
            let color = '#3b82f6';
            let icon = 'fa-road';
            
            if (type === 'titian') { color = '#f59e0b'; icon = 'fa-bridge'; }
            if (type === 'sanitasi') { color = '#10b981'; icon = 'fa-toilet'; }
            
            return L.divIcon({
                className: 'custom-marker',
                html: `
                    <div class="flex items-center justify-center w-10 h-10 rounded-xl border-2 border-white shadow-lg transition-all hover:scale-110" style="background: ${color}">
                        <i class="fas ${icon} text-white text-sm"></i>
                    </div>
                `,
                iconSize: [40, 40],
                iconAnchor: [20, 40],
                popupAnchor: [0, -40]
            });
        };

        function applyFilters() {
            markersLayer.clearLayers();
            const checkedCategories = Array.from(document.querySelectorAll('.filter-category:checked')).map(el => el.value);
            const checkedDistricts = Array.from(document.querySelectorAll('.filter-district:checked')).map(el => el.value);
            
            let count = 0;
            dataInfra.forEach(item => {
                if (checkedCategories.includes(item.jenis) && checkedDistricts.includes(item.id_kecamatan?.toString())) {
                    count++;
                    const marker = L.marker([item.latitude, item.longitude], { icon: createIcon(item.jenis) });
                    
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

            // Update Counter
            document.getElementById('visible-count').innerText = count;
        }

        // Event Listeners
        document.querySelectorAll('.filter-category, .filter-district').forEach(el => el.addEventListener('change', applyFilters));
        
        document.getElementById('search-wilayah').addEventListener('input', (e) => {
            const term = e.target.value.toLowerCase();
            document.querySelectorAll('.district-item').forEach(item => {
                item.style.display = item.textContent.toLowerCase().includes(term) ? 'flex' : 'none';
            });
        });

        function setBasemap(type) {
            // Update UI
            document.querySelectorAll('.basemap-btn').forEach(btn => {
                btn.classList.remove('bg-navy', 'text-white');
                btn.classList.add('bg-white/90', 'text-navy');
            });
            event.target.classList.add('bg-navy', 'text-white');
            event.target.classList.remove('bg-white/90', 'text-navy');

            if (type === 'satelit') { 
                map.addLayer(satelit); 
                map.removeLayer(googleStreets); 
            } else { 
                map.addLayer(googleStreets); 
                map.removeLayer(satelit); 
            }
        }

        // Init
        applyFilters();
    </script>
</body>
</html>