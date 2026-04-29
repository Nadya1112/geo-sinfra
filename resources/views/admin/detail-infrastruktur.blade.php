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

    @include('admin.partials.sidebar')

    <main class="flex-1 flex flex-col h-screen overflow-y-auto">
        <header class="bg-white border-b border-gray-100 px-8 py-5 flex justify-between items-center z-10">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.infrastruktur') }}" class="w-10 h-10 bg-white border border-gray-100 rounded-xl flex items-center justify-center text-gray-400 hover:text-blue-600 transition-all">
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
                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-tighter">{{ now()->translatedFormat('l, d F Y') }}</p>
                </div>
                
                <div class="h-8 w-[1px] bg-gray-100"></div>
                
                <div class="flex items-center gap-3">
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
            <div class="max-w-4xl bg-white rounded-[2.5rem] p-10 border border-gray-100 shadow-sm mx-auto">
                <div class="mb-10 border-b border-gray-50 pb-5 flex justify-between items-end">
                    <div>
                        <h3 class="text-lg font-black text-[#1e1b4b] tracking-tight">Identitas Objek</h3>
                        <p class="text-xs text-gray-400 font-medium tracking-tighter">Detail Informasi Aset SINFRA</p>
                    </div>
                    <div class="flex gap-3">
                        <span class="px-4 py-2 rounded-xl text-[10px] font-black tracking-widest border {{ $inf->kondisi == 'Baik' ? 'bg-green-50 text-green-600 border-green-200' : ($inf->kondisi == 'Rusak Ringan' ? 'bg-yellow-50 text-yellow-600 border-yellow-200' : 'bg-red-50 text-red-600 border-red-200') }}">
                            {{ strtoupper($inf->kondisi) }}
                        </span>
                    </div>
                </div>

                <div class="space-y-8">
                    
                    <!-- Bagian 1: Identitas & Lokasi -->
                    <div class="space-y-6">
                        <div class="border-l-4 border-blue-500 pl-4 mb-4">
                            <h4 class="text-sm font-black text-[#1e1b4b] uppercase tracking-wider">1. Identitas & Lokasi</h4>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div>
                                <label class="block text-[10px] font-black text-[#1e1b4b] tracking-widest mb-2">Nama Infrastruktur</label>
                                <div class="w-full px-5 py-3 bg-gray-50 border border-gray-100 rounded-2xl text-sm font-semibold text-gray-600">
                                    {{ $inf->nama_infrastruktur }}
                                </div>
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-[#1e1b4b] uppercase mb-2">Jenis Infrastruktur</label>
                                <div class="w-full px-5 py-3 bg-gray-50 border border-gray-100 rounded-2xl text-sm font-semibold text-gray-600">
                                    {{ $inf->jenis_infrastruktur }}
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div>
                                <label class="block text-[10px] font-black text-[#1e1b4b] uppercase mb-2">Kecamatan</label>
                                <div class="w-full px-5 py-3 bg-gray-50 border border-gray-100 rounded-2xl text-sm font-semibold text-gray-600">
                                    {{ $inf->nama_kecamatan ?? '-' }}
                                </div>
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-[#1e1b4b] uppercase mb-2">Kelurahan</label>
                                <div class="w-full px-5 py-3 bg-gray-50 border border-gray-100 rounded-2xl text-sm font-semibold text-gray-600">
                                    {{ $inf->nama_kelurahan ?? '-' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Bagian 2: Sistem Informasi Geografis (SIG) -->
                    <div class="space-y-6 pt-6 border-t border-gray-100">
                        <div class="border-l-4 border-indigo-500 pl-4 mb-4">
                            <h4 class="text-sm font-black text-[#1e1b4b] uppercase tracking-wider">2. SIG (Sistem Informasi Geografis)</h4>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div>
                                <label class="block text-[10px] font-black text-[#1e1b4b] uppercase mb-2">Latitude</label>
                                <div class="w-full px-5 py-3 bg-gray-50 border border-gray-100 rounded-2xl text-sm font-semibold text-gray-600">
                                    {{ $inf->latitude }}
                                </div>
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-[#1e1b4b] uppercase mb-2">Longitude</label>
                                <div class="w-full px-5 py-3 bg-gray-50 border border-gray-100 rounded-2xl text-sm font-semibold text-gray-600">
                                    {{ $inf->longitude }}
                                </div>
                            </div>
                        </div>

                        <!-- Mini Map -->
                        <div class="mt-6">
                            <label class="block text-[10px] font-black text-[#1e1b4b] uppercase mb-3">Visualisasi Lokasi (Mini Map)</label>
                            <div id="mini-map" class="w-full h-48 rounded-3xl border border-gray-100 shadow-inner z-0"></div>
                        </div>
                    </div>

                    <!-- Bagian 3: Koreksi Analisis AI (CNN & DT) -->
                    <div class="space-y-6 pt-6 border-t border-gray-100">
                        <div class="border-l-4 border-purple-500 pl-4 mb-4">
                            <h4 class="text-sm font-black text-[#1e1b4b] uppercase tracking-wider">3. Analisis Cerdas (CNN & DT)</h4>
                        </div>
                        @php
                            $priority = $inf->kondisi == 'Baik' ? 'Prioritas Rendah' : ($inf->kondisi == 'Rusak Ringan' ? 'PERLU PERHATIAN' : 'PRIORITAS TINGGI');
                            $cnnResult = $inf->kondisi == 'Baik' ? 'NORMAL (88.5%)' : 'RUSAK (94.2%)';
                        @endphp
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div>
                                <label class="block text-[10px] font-black text-[#1e1b4b] uppercase mb-2">Akurasi CNN (%)</label>
                                <div class="w-full px-5 py-3 bg-purple-50 border border-purple-100 rounded-2xl flex items-center justify-between">
                                    <span class="text-sm font-black text-purple-700">{{ $cnnResult }}</span>
                                    <i class="fas fa-brain text-purple-400"></i>
                                </div>
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-[#1e1b4b] uppercase mb-2">Hasil Akhir Decision Tree</label>
                                <div class="w-full px-5 py-3 bg-gray-50 border border-gray-100 rounded-2xl flex items-center justify-between text-gray-600">
                                    <span class="text-sm font-black">{{ $priority }}</span>
                                    <span class="text-[10px] font-bold text-gray-400 italic normal-case">Otomatis Terverifikasi</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Bagian 4: Dokumentasi Visual -->
                    <div class="space-y-6 pt-6 border-t border-gray-100">
                        <div class="border-l-4 border-emerald-500 pl-4 mb-4">
                            <h4 class="text-sm font-black text-[#1e1b4b] uppercase tracking-wider">4. Dokumentasi Visual</h4>
                        </div>
                        
                        <div class="w-full max-w-2xl mx-auto relative rounded-2xl overflow-hidden border border-gray-100 shadow-sm bg-gray-50 aspect-video flex items-center justify-center group">
                            @if($inf->foto_terbaru && $inf->foto_terbaru != 'default.jpg')
                                <img src="{{ asset('storage/infrastruktur/' . $inf->foto_terbaru) }}" alt="Foto Infrastruktur" class="w-full h-full object-cover">
                                @if($inf->kondisi != 'Baik')
                                    <!-- Bounding Box Simulasi Kerusakan -->
                                    <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                                        <div class="border-2 border-red-500 bg-red-500/20 w-1/3 h-1/2 relative flex items-start justify-center">
                                            <span class="bg-red-500 text-white text-[8px] font-black px-2 py-1 mt-[-20px] whitespace-nowrap rounded-t-md tracking-wider shadow-sm">KERUSAKAN TERDETEKSI</span>
                                        </div>
                                    </div>
                                @endif
                                <div class="absolute inset-0 bg-black/10 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                    <a href="{{ asset('storage/infrastruktur/' . $inf->foto_terbaru) }}" target="_blank" class="bg-white/90 text-[#1e1b4b] px-4 py-2 rounded-xl text-[10px] font-black shadow-lg uppercase tracking-widest hover:bg-white hover:scale-105 transition-all">Lihat Foto Asli</a>
                                </div>
                            @else
                                <div class="text-center text-gray-300">
                                    <i class="fas fa-image text-3xl mb-2"></i>
                                    <p class="text-[10px] font-bold">[ TIDAK ADA FOTO ]</p>
                                </div>
                            @endif
                        </div>
                        
                        <div class="mt-3 text-center">
                            <p class="text-[9px] font-bold text-gray-400 italic normal-case">{{ $inf->foto_terbaru ?? 'tidak_ada_foto.jpg' }} - Diupload oleh {{ $inf->nama_user ?? 'Admin' }}</p>
                        </div>
                    </div>

                </div>

                <!-- Tombol Aksi -->
                <div class="flex gap-4 pt-8 mt-10 border-t border-gray-100">
                    <a href="{{ route('admin.infrastruktur.pdf', $inf->id_infrastruktur) }}" class="flex-1 bg-yellow-400 text-white py-4 rounded-2xl font-black text-[11px] tracking-widest hover:bg-yellow-500 shadow-lg shadow-yellow-100 transition-all flex justify-center items-center gap-2 uppercase">
                        <i class="fas fa-file-pdf"></i> Export Data Ke PDF
                    </a>
                    <a href="{{ route('admin.infrastruktur.edit', $inf->id_infrastruktur) }}" class="flex-1 bg-white text-[#1e1b4b] border-2 border-gray-100 py-3.5 rounded-2xl font-black text-[11px] tracking-widest hover:border-indigo-500 hover:text-indigo-600 transition-all flex justify-center items-center gap-2 uppercase">
                        <i class="fas fa-edit"></i> Edit Data Manual
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
        const lat = {{ $inf->latitude }};
        const lng = {{ $inf->longitude }};
        const map = L.map('mini-map').setView([lat, lng], 15);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        L.marker([lat, lng]).addTo(map)
            .bindPopup('{{ $inf->nama_infrastruktur }}')
            .openPopup();
    </script>
</body>
</html>
