<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Data Lapangan | GEO-SINFRA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <style> 
        body { font-family: 'Plus Jakarta Sans', sans-serif; } 
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
        .leaflet-container { font-family: inherit; }
        .premium-shadow { box-shadow: 0 20px 50px rgba(0,0,0,0.05); }
    </style>
</head>
<body class="bg-[#f8fafc] flex h-screen overflow-hidden text-gray-800 text-left">

    @include('surveyor.partials.sidebar')

    <main class="flex-1 flex flex-col h-screen overflow-y-auto custom-scrollbar bg-[#f8fafc]">
        <!-- Header -->
        <header class="bg-white/80 backdrop-blur-md border-b border-gray-100 px-8 py-5 flex justify-between items-center sticky top-0 z-[1000]">
            <div class="flex items-center gap-4">
                <a href="{{ route('surveyor.dashboard') }}" class="w-10 h-10 flex items-center justify-center bg-white text-gray-400 rounded-xl hover:bg-emerald-50 hover:text-emerald-600 transition-all border border-gray-100 shadow-sm">
                    <i class="fas fa-arrow-left text-sm"></i>
                </a>
                <div>
                    <p class="text-[9px] font-black text-emerald-600 uppercase tracking-[0.2em] mb-0.5">Sistem Input Geospasial</p>
                    <h2 class="text-xl font-black text-[#1e1b4b] tracking-tight">Input Data Lapangan</h2>
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
                        <p class="text-[10px] font-black text-[#1e1b4b] leading-none uppercase">{{ auth()->user()->name }}</p>
                        <p class="text-[8px] font-bold text-emerald-500 uppercase mt-1">Online</p>
                    </div>
                    <div class="w-10 h-10 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-600 border border-emerald-100 overflow-hidden shadow-sm">
                        @if(auth()->user()->profile_photo)
                            <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}" class="w-full h-full object-cover">
                        @else
                            <i class="fas fa-user-circle text-xl text-emerald-300"></i>
                        @endif
                    </div>
                </div>
            </div>
        </header>

        <div class="p-8">
            <div class="max-w-7xl mx-auto">
                <form id="survey-form" action="{{ route('surveyor.store') }}" method="POST" enctype="multipart/form-data" onsubmit="disableSubmitButton()">
                    @csrf
                    
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                        
                        <!-- KOLOM KIRI: FORM DATA (7/12) -->
                        <div class="lg:col-span-7 space-y-8">
                            
                            <!-- Section 1: Identitas & Klasifikasi -->
                            <div class="bg-white rounded-[2.5rem] p-8 border border-gray-100 premium-shadow">
                                <div class="flex items-center gap-4 mb-8">
                                    <div class="w-12 h-12 rounded-2xl bg-emerald-500/10 flex items-center justify-center text-emerald-600">
                                        <i class="fas fa-file-signature text-xl"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-black text-[#1e1b4b] uppercase tracking-tight">Identitas Laporan</h4>
                                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Klasifikasi Objek Infrastruktur</p>
                                    </div>
                                </div>

                                <div class="space-y-6">
                                    <div>
                                        <label class="block text-[10px] font-black text-[#1e1b4b] uppercase tracking-widest mb-3 ml-1">Nama Infrastruktur / Ruas Jalan <span class="text-red-500">*</span></label>
                                        <div class="relative group">
                                            <i class="fas fa-tag absolute left-5 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-emerald-500 transition-colors"></i>
                                            <input type="text" name="nama_infrastruktur" placeholder="Masukan nama objek (Contoh: Jalan Hasan Basry)" class="w-full pl-12 pr-5 py-4 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-semibold focus:ring-4 focus:ring-emerald-500/5 focus:border-emerald-500 outline-none transition-all" required>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                        <div>
                                            <label class="block text-[10px] font-black text-[#1e1b4b] uppercase tracking-widest mb-3 ml-1">Kecamatan Wilayah <span class="text-red-500">*</span></label>
                                            <div class="relative group">
                                                <i class="fas fa-map-location-dot absolute left-5 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-emerald-500 transition-colors z-10"></i>
                                                <select name="id_kecamatan" id="id_kecamatan" class="w-full pl-12 pr-10 py-4 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-semibold focus:border-emerald-500 outline-none appearance-none cursor-pointer transition-all relative z-0" required onchange="filterKelurahan()">
                                                    <option value="">Pilih Kecamatan...</option>
                                                    @foreach($semuaKecamatan as $kec)
                                                        <option value="{{ $kec->id_kecamatan }}" {{ count($semuaKecamatan) == 1 ? 'selected' : '' }}>{{ $kec->nama_kecamatan }}</option>
                                                    @endforeach
                                                </select>
                                                <i class="fas fa-chevron-down absolute right-5 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none text-[10px]"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <label class="block text-[10px] font-black text-[#1e1b4b] uppercase tracking-widest mb-3 ml-1">Kelurahan / Desa <span class="text-red-500">*</span></label>
                                            <div class="relative group">
                                                <i class="fas fa-city absolute left-5 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-emerald-500 transition-colors z-10"></i>
                                                <select name="id_kelurahan" id="id_kelurahan" class="w-full pl-12 pr-10 py-4 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-semibold focus:border-emerald-500 outline-none appearance-none cursor-pointer transition-all relative z-0" required onchange="focusToKelurahan()">
                                                    <option value="">Pilih Kelurahan...</option>
                                                    @foreach($semuaKelurahan as $kel)
                                                        <option value="{{ $kel->id_kelurahan }}" 
                                                                data-kecamatan="{{ $kel->id_kecamatan }}"
                                                                data-lat="{{ $kel->latitude }}"
                                                                data-lng="{{ $kel->longitude }}">
                                                            {{ $kel->nama_kelurahan }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <i class="fas fa-chevron-down absolute right-5 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none text-[10px]"></i>
                                            </div>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-[10px] font-black text-[#1e1b4b] uppercase tracking-widest mb-4 ml-1 text-center">Jenis Infrastruktur <span class="text-red-500">*</span></label>
                                        <div class="grid grid-cols-3 gap-4">
                                            @foreach(['Jalan', 'Sanitasi', 'Titian'] as $type)
                                            <label class="cursor-pointer group">
                                                <input type="radio" name="jenis_infrastruktur" value="{{ $type }}" class="peer hidden" {{ $loop->first ? 'checked' : '' }}>
                                                <div class="px-2 py-4 bg-gray-50 border border-gray-100 rounded-2xl text-center transition-all peer-checked:bg-[#1e1b4b] peer-checked:border-[#1e1b4b] peer-checked:text-white shadow-sm hover:bg-emerald-50 group-hover:scale-[1.02]">
                                                    <i class="fas fa-{{ $type == 'Jalan' ? 'road' : ($type == 'Sanitasi' ? 'faucet-drip' : 'bridge-water') }} text-lg mb-2 block group-hover:animate-bounce"></i>
                                                    <span class="text-[9px] font-black uppercase tracking-tighter">{{ $type }}</span>
                                                </div>
                                            </label>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Section 2: Spesifikasi Teknis -->
                            <div class="bg-white rounded-[2.5rem] p-8 border border-gray-100 premium-shadow">
                                <div class="flex items-center gap-4 mb-8">
                                    <div class="w-12 h-12 rounded-2xl bg-blue-500/10 flex items-center justify-center text-blue-600">
                                        <i class="fas fa-ruler-combined text-xl"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-black text-[#1e1b4b] uppercase tracking-tight">Spesifikasi Teknis</h4>
                                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Dimensi & Material Eksisting</p>
                                    </div>
                                </div>

                                <div class="space-y-6">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                        <div class="space-y-6">
                                            <div class="grid grid-cols-2 gap-4">
                                                <div>
                                                    <label class="block text-[10px] font-black text-[#1e1b4b] uppercase tracking-widest mb-3 ml-1">Panjang (m)</label>
                                                    <div class="relative group">
                                                        <i class="fas fa-arrows-left-right absolute left-5 top-1/2 -translate-y-1/2 text-gray-300 group-focus-within:text-blue-500 transition-colors"></i>
                                                        <input type="number" step="0.01" name="panjang" placeholder="0.00" class="w-full pl-12 pr-5 py-3.5 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-semibold focus:border-blue-500 outline-none transition-all">
                                                    </div>
                                                </div>
                                                <div>
                                                    <label class="block text-[10px] font-black text-[#1e1b4b] uppercase tracking-widest mb-3 ml-1">Lebar (m)</label>
                                                    <div class="relative group">
                                                        <i class="fas fa-arrows-up-down absolute left-5 top-1/2 -translate-y-1/2 text-gray-300 group-focus-within:text-blue-500 transition-colors"></i>
                                                        <input type="number" step="0.01" name="lebar" placeholder="0.00" class="w-full pl-12 pr-5 py-3.5 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-semibold focus:border-blue-500 outline-none transition-all">
                                                    </div>
                                                </div>
                                            </div>
                                            <div>
                                        <label class="block text-[10px] font-black text-[#1e1b4b] uppercase tracking-widest mb-3 ml-1">Material Utama</label>
                                        <div class="relative group">
                                            <i class="fas fa-layer-group absolute left-5 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-blue-500 transition-colors z-10"></i>
                                            <select name="material_eksisting" class="w-full pl-12 pr-10 py-3.5 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-semibold focus:border-blue-500 outline-none appearance-none cursor-pointer transition-all relative z-0">
                                                <option value="">Pilih Material...</option>
                                                <option value="Cor Beton">Cor Beton</option>
                                                <option value="Aspal">Aspal</option>
                                                <option value="Paving">Paving</option>
                                                <option value="Tanah Asli">Tanah Asli</option>
                                                <option value="Tanah Pemadatan">Tanah Pemadatan</option>
                                                <option value="Tanah Lepas">Tanah Lepas</option>
                                                <option value="Batapres">Batapres</option>
                                                <option value="Makadam">Makadam</option>
                                                <option value="Titian">Titian</option>
                                                <option value="Titian Ulin">Titian Ulin</option>
                                                <option value="Titian Rusak">Titian Rusak</option>
                                                <option value="WC">WC</option>
                                                <option value="Lainnya">Lainnya</option>
                                            </select>
                                            <i class="fas fa-chevron-down absolute right-5 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none text-[10px]"></i>
                                        </div>
                                    </div>
                                        </div>
                                        <div class="space-y-4">
                                            <label class="block text-[10px] font-black text-[#1e1b4b] uppercase tracking-widest mb-3 ml-1">Fasilitas Pendukung</label>
                                            <div class="space-y-3">
                                                <label class="flex items-center gap-4 p-4 bg-gray-50 rounded-2xl border border-gray-200 cursor-pointer hover:bg-blue-50 transition-all group">
                                                    <input type="checkbox" name="has_drainase" value="1" class="peer hidden">
                                                    <div class="w-6 h-6 rounded-lg border-2 border-gray-300 peer-checked:bg-blue-500 peer-checked:border-blue-500 transition-all flex items-center justify-center">
                                                        <i class="fas fa-check text-xs text-white opacity-0 peer-checked:opacity-100"></i>
                                                    </div>
                                                    <span class="text-[10px] font-black text-[#1e1b4b] uppercase tracking-widest">Saluran Drainase</span>
                                                </label>
                                                <label class="flex items-center gap-4 p-4 bg-gray-50 rounded-2xl border border-gray-200 cursor-pointer hover:bg-blue-50 transition-all group">
                                                    <input type="checkbox" name="has_gorong_gorong" value="1" class="peer hidden">
                                                    <div class="w-6 h-6 rounded-lg border-2 border-gray-300 peer-checked:bg-blue-500 peer-checked:border-blue-500 transition-all flex items-center justify-center">
                                                        <i class="fas fa-check text-xs text-white opacity-0 peer-checked:opacity-100"></i>
                                                    </div>
                                                    <span class="text-[10px] font-black text-[#1e1b4b] uppercase tracking-widest">Gorong-gorong</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-[10px] font-black text-[#1e1b4b] uppercase tracking-widest mb-3 ml-1">Alamat / Patokan Detail <span class="text-red-500">*</span></label>
                                        <div class="relative group">
                                            <i class="fas fa-map-pin absolute left-5 top-5 text-gray-300 group-focus-within:text-blue-500 transition-colors"></i>
                                            <textarea name="alamat" rows="2" placeholder="Masukan alamat lengkap atau patokan lokasi..." class="w-full pl-12 pr-5 py-4 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-semibold focus:border-blue-500 outline-none transition-all resize-none" required></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- KOLOM KANAN: PETA & FOTO (5/12) -->
                        <div class="lg:col-span-5 space-y-8">
                            
                            <!-- Section 3: Geolocation -->
                            <div class="bg-white rounded-[2.5rem] p-8 border border-gray-100 premium-shadow">
                                <div class="flex items-center justify-between mb-8">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 rounded-2xl bg-amber-500/10 flex items-center justify-center text-amber-600">
                                            <i class="fas fa-location-crosshairs text-xl"></i>
                                        </div>
                                        <div>
                                            <h4 class="font-black text-[#1e1b4b] uppercase tracking-tight">Titik Lokasi</h4>
                                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Akurasi Geospasial</p>
                                        </div>
                                    </div>
                                    <button type="button" onclick="getLocation()" class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white rounded-xl text-[10px] font-black uppercase tracking-widest flex items-center gap-2 transition-all shadow-lg shadow-amber-500/20 active:scale-95">
                                        <i class="fas fa-crosshairs"></i>
                                        Sync GPS
                                    </button>
                                </div>

                                <div class="relative rounded-[2rem] border-4 border-gray-50 shadow-inner overflow-hidden mb-6 h-[280px]">
                                    <div id="map" class="absolute inset-0 z-0"></div>
                                    <div class="absolute bottom-4 left-4 right-4 z-10">
                                        <div class="bg-white/90 backdrop-blur-md px-4 py-2 rounded-xl shadow-lg border border-white/20 text-center">
                                            <p class="text-[8px] font-black uppercase text-[#1e1b4b] animate-pulse">Klik Pada Peta Untuk Geser Pin</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div class="bg-gray-50 p-4 rounded-2xl border border-gray-200">
                                        <label class="block text-[8px] font-black text-gray-400 uppercase tracking-[0.2em] mb-1">Latitude</label>
                                        <input type="text" id="lat-input" name="latitude" readonly class="w-full bg-transparent border-none p-0 text-xs font-black text-[#1e1b4b] outline-none cursor-default">
                                    </div>
                                    <div class="bg-gray-50 p-4 rounded-2xl border border-gray-200">
                                        <label class="block text-[8px] font-black text-gray-400 uppercase tracking-[0.2em] mb-1">Longitude</label>
                                        <input type="text" id="lng-input" name="longitude" readonly class="w-full bg-transparent border-none p-0 text-xs font-black text-[#1e1b4b] outline-none cursor-default">
                                    </div>
                                </div>
                            </div>

                            <!-- Section 4: Dokumentasi -->
                            <div class="bg-white rounded-[2.5rem] p-8 border border-gray-100 premium-shadow">
                                <div class="flex items-center gap-4 mb-8">
                                    <div class="w-12 h-12 rounded-2xl bg-purple-500/10 flex items-center justify-center text-purple-600">
                                        <i class="fas fa-camera text-xl"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-black text-[#1e1b4b] uppercase tracking-tight">Dokumentasi</h4>
                                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Foto Visual Lapangan</p>
                                    </div>
                                </div>

                                <div class="space-y-6">
                                    <div class="relative group cursor-pointer h-44">
                                        <input type="file" name="foto" id="foto-input" accept="image/*" class="absolute inset-0 opacity-0 z-10 cursor-pointer" required onchange="previewImage(event)">
                                        <div id="foto-preview-container" class="absolute inset-0 border-2 border-dashed border-gray-200 rounded-[2.5rem] flex flex-col items-center justify-center gap-3 group-hover:bg-purple-50 group-hover:border-purple-200 transition-all overflow-hidden bg-gray-50/50">
                                            <div id="placeholder-elements" class="flex flex-col items-center text-center px-6">
                                                <div class="w-14 h-14 bg-white rounded-2xl shadow-sm flex items-center justify-center mb-2 group-hover:scale-110 transition-transform">
                                                    <i class="fas fa-cloud-arrow-up text-2xl text-purple-400"></i>
                                                </div>
                                                <p class="text-[9px] font-black text-gray-400 group-hover:text-purple-600 uppercase tracking-[0.1em]">Klik/Ambil Foto Objek</p>
                                            </div>
                                            <img id="image-preview" src="#" alt="Preview" class="hidden absolute inset-0 w-full h-full object-cover">
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-[10px] font-black text-[#1e1b4b] uppercase tracking-widest mb-3 ml-1">Rencana Perbaikan / Catatan Khusus</label>
                                        <textarea name="rencana_perbaikan" rows="2" placeholder="Sebutkan saran atau kondisi mendesak..." class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-semibold focus:border-purple-500 outline-none transition-all resize-none"></textarea>
                                    </div>

                                    <div class="grid grid-cols-1 gap-4 pt-2">
                                        <div class="flex items-center justify-between px-2">
                                            <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Tgl Survey: {{ date('d/m/Y') }}</span>
                                            <input type="hidden" name="tgl_survey" value="{{ date('Y-m-d') }}">
                                        </div>
                                        <button type="submit" id="btn-submit" class="w-full py-5 bg-[#1e1b4b] hover:bg-black text-white rounded-[2rem] font-black text-[10px] uppercase tracking-[0.3em] transition-all shadow-2xl shadow-[#1e1b4b]/20 active:scale-95 flex items-center justify-center gap-4">
                                            <span id="btn-text">Kirim Laporan Survey</span>
                                            <i class="fas fa-paper-plane text-[9px] group-hover:translate-x-1 transition-transform"></i>
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

        let marker;

        map.on('click', function(e) {
            updateMarker(e.latlng.lat, e.latlng.lng);
        });

        function updateMarker(lat, lng) {
            if (marker) map.removeLayer(marker);
            marker = L.marker([lat, lng], {
                icon: L.divIcon({
                    className: '',
                    html: `<div class="w-8 h-8 bg-emerald-500 rounded-full border-4 border-white shadow-2xl flex items-center justify-center text-white"><i class="fas fa-location-dot"></i></div>`,
                    iconSize: [32, 32],
                    iconAnchor: [16, 32]
                })
            }).addTo(map);
            document.getElementById('lat-input').value = lat.toFixed(8);
            document.getElementById('lng-input').value = lng.toFixed(8);
        }

        function getLocation() {
            if (navigator.geolocation) {
                const btn = event.currentTarget;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Finding...';
                navigator.geolocation.getCurrentPosition(function(position) {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;
                    map.setView([lat, lng], 17);
                    updateMarker(lat, lng);
                    btn.innerHTML = '<i class="fas fa-check"></i> Berhasil';
                    setTimeout(() => { btn.innerHTML = '<i class="fas fa-crosshairs"></i> Sync GPS'; }, 2000);
                }, function() {
                    alert('Gagal mendapatkan lokasi. Pastikan GPS aktif.');
                    btn.innerHTML = '<i class="fas fa-crosshairs"></i> Sync GPS';
                });
            }
        }

        function disableSubmitButton() {
            const btn = document.getElementById('btn-submit');
            const text = document.getElementById('btn-text');
            btn.disabled = true;
            btn.classList.add('opacity-50', 'cursor-not-allowed');
            text.innerHTML = 'Memproses Laporan...';
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
</html>
