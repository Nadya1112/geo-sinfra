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

    @include('surveyor.partials.sidebar')

    <main class="flex-1 flex flex-col h-screen overflow-y-auto">
        <header class="bg-white border-b border-gray-100 px-8 py-5 flex justify-between items-center z-10">
            <div class="flex items-center gap-4">
                <a href="{{ route('surveyor.history') }}" class="w-10 h-10 flex items-center justify-center bg-gray-50 text-gray-400 rounded-xl hover:bg-emerald-50 hover:text-emerald-600 transition-all border border-gray-100">
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
                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-tighter">{{ now()->translatedFormat('l, d F Y') }}</p>
                </div>
                <div class="h-8 w-[1px] bg-gray-100"></div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('surveyor.infrastruktur.edit', $infrastruktur->id_infrastruktur) }}" class="px-5 py-2.5 bg-emerald-600 text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-[#1e1b4b] transition-all shadow-lg shadow-emerald-900/10">
                        <i class="fas fa-edit mr-2"></i> Edit Data
                    </a>
                </div>
            </div>
        </header>

        <div class="p-8">
            <div class="max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- Kolom Kiri: Foto & Status -->
                <div class="lg:col-span-1 space-y-6">
                    <div class="bg-white rounded-[2.5rem] p-4 border border-gray-100 shadow-sm overflow-hidden">
                        <div class="relative h-64 rounded-[2rem] overflow-hidden group">
                            <img src="{{ asset('storage/' . $infrastruktur->foto_terbaru) }}" class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-all flex items-end p-6">
                                <p class="text-white text-[10px] font-bold uppercase tracking-widest">Foto Dokumentasi</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-[#1e1b4b] rounded-[2.5rem] p-8 text-white shadow-xl">
                        <h4 class="text-xs font-black text-blue-300 uppercase tracking-widest mb-6 italic">Hasil Analisis AI</h4>
                        
                        <div class="space-y-6">
                            <div class="flex items-center justify-between">
                                <p class="text-[10px] font-bold text-blue-200/60 uppercase">Kondisi</p>
                                <span class="px-3 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest {{ $infrastruktur->kondisi == 'Baik' ? 'bg-emerald-500/20 text-emerald-400' : ($infrastruktur->kondisi == 'Rusak Ringan' ? 'bg-yellow-500/20 text-yellow-400' : 'bg-red-500/20 text-red-400') }}">
                                    {{ $infrastruktur->kondisi }}
                                </span>
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <p class="text-[10px] font-bold text-blue-200/60 uppercase">Skor CNN</p>
                                <p class="text-lg font-black text-white">{{ $infrastruktur->cnn ? number_format($infrastruktur->cnn->skor_cnn * 100, 1) : '-' }}%</p>
                            </div>

                            <div class="flex items-center justify-between">
                                <p class="text-[10px] font-bold text-blue-200/60 uppercase">Prioritas DT</p>
                                <p class="text-sm font-black text-blue-300">{{ $infrastruktur->analisis ? $infrastruktur->analisis->label_prioritas : '-' }}</p>
                            </div>

                            <div class="pt-6 border-t border-white/10 flex items-center justify-between">
                                <p class="text-[10px] font-bold text-blue-200/60 uppercase">Verifikasi</p>
                                <div class="flex items-center gap-2">
                                    <div class="w-2 h-2 rounded-full {{ $infrastruktur->status_verifikasi == 'Verified' ? 'bg-emerald-400' : 'bg-amber-400' }}"></div>
                                    <p class="text-[9px] font-black uppercase">{{ $infrastruktur->status_verifikasi ?? 'Pending' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kolom Kanan: Detail & Map -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white rounded-[2.5rem] p-8 border border-gray-100 shadow-sm">
                        <div class="flex justify-between items-start mb-8">
                            <div>
                                <p class="text-[10px] font-black text-emerald-600 uppercase tracking-widest mb-1">{{ $infrastruktur->jenis_infrastruktur }}</p>
                                <h3 class="text-2xl font-black text-[#1e1b4b]">{{ $infrastruktur->nama_infrastruktur }}</h3>
                            </div>
                            <div class="text-right">
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter">Diinput Pada</p>
                                <p class="text-xs font-black text-[#1e1b4b]">{{ $infrastruktur->created_at->translatedFormat('d F Y, H:i') }} WITA</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-8 mb-8">
                            <div class="space-y-4">
                                <div class="flex items-start gap-4">
                                    <div class="w-10 h-10 rounded-2xl bg-gray-50 flex items-center justify-center text-[#1e1b4b] border border-gray-100">
                                        <i class="fas fa-map-marked-alt text-sm"></i>
                                    </div>
                                    <div>
                                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Kecamatan</p>
                                        <p class="text-sm font-bold text-[#1e1b4b]">{{ $infrastruktur->kelurahan->kecamatan->nama_kecamatan ?? '-' }}</p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-4">
                                    <div class="w-10 h-10 rounded-2xl bg-gray-50 flex items-center justify-center text-[#1e1b4b] border border-gray-100">
                                        <i class="fas fa-building text-sm"></i>
                                    </div>
                                    <div>
                                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Kelurahan</p>
                                        <p class="text-sm font-bold text-[#1e1b4b]">{{ $infrastruktur->kelurahan->nama_kelurahan ?? '-' }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="space-y-4">
                                <div class="flex items-start gap-4">
                                    <div class="w-10 h-10 rounded-2xl bg-gray-50 flex items-center justify-center text-[#1e1b4b] border border-gray-100">
                                        <i class="fas fa-location-arrow text-sm"></i>
                                    </div>
                                    <div>
                                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Koordinat</p>
                                        <p class="text-xs font-bold text-[#1e1b4b]">{{ $infrastruktur->latitude }}, {{ $infrastruktur->longitude }}</p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-4">
                                    <div class="w-10 h-10 rounded-2xl bg-gray-50 flex items-center justify-center text-[#1e1b4b] border border-gray-100">
                                        <i class="fas fa-map-pin text-sm"></i>
                                    </div>
                                    <div>
                                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Alamat / Catatan</p>
                                        <p class="text-xs font-medium text-gray-600 italic">"{{ $infrastruktur->alamat ?? 'Tidak ada catatan alamat.' }}"</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="relative rounded-[2rem] overflow-hidden border border-gray-100 shadow-inner">
                            <div id="map" class="h-[250px] w-full z-0"></div>
                            <div class="absolute bottom-4 left-4 z-10">
                                <a href="https://www.google.com/maps/search/?api=1&query={{ $infrastruktur->latitude }},{{ $infrastruktur->longitude }}" target="_blank" class="px-4 py-2 bg-white/90 backdrop-blur text-[#1e1b4b] rounded-xl text-[9px] font-black uppercase tracking-widest shadow-xl border border-gray-100 flex items-center gap-2 hover:bg-emerald-600 hover:text-white transition-all">
                                    <i class="fas fa-external-link-alt"></i> Buka di Google Maps
                                </a>
                            </div>
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

        const lat = {{ $infrastruktur->latitude }};
        const lng = {{ $infrastruktur->longitude }};
        const map = L.map('map', {
            zoomControl: true,
            scrollWheelZoom: false
        }).setView([lat, lng], 16);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        const color = "{{ $infrastruktur->kondisi == 'Baik' ? '#10b981' : ($infrastruktur->kondisi == 'Rusak Ringan' ? '#f59e0b' : '#ef4444') }}";
        
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
</html>
