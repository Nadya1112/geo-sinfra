<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Data Lapangan | GEO-SINFRA</title>
    <link rel="icon" href="{{ asset('logo_geo-sinfra.png') }}" type="image/png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/compressorjs/1.2.1/compressor.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/localforage/1.10.0/localforage.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
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

    @include('surveyor.partials.sidebar')

    <main class="flex-1 flex flex-col h-screen overflow-y-auto custom-scrollbar bg-slate-50">
        {{-- ── Header ── --}}
        <header class="bg-white/80 backdrop-blur-xl border-b border-slate-100 px-8 py-5 flex justify-between items-center sticky top-0 z-[1000] shadow-sm">
            <div class="flex items-center gap-4">
                <a href="{{ route('surveyor.dashboard') }}" class="w-10 h-10 flex items-center justify-center bg-white text-slate-400 rounded-xl hover:bg-gold-50 hover:text-gold-600 transition-all border border-slate-200 hover:border-gold-200">
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
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-tighter">{{ now()->translatedFormat('l, d F Y') }}</p>
                </div>
                <div class="h-8 w-[1px] bg-slate-100"></div>
                <div class="flex items-center gap-3">
                    <div class="text-right">
                        <p class="text-[10px] font-black text-navy-900 leading-none uppercase">{{ auth()->user()->name }}</p>
                        <p class="text-[8px] font-bold text-emerald-500 uppercase mt-1">Online</p>
                    </div>
                    <div class="w-10 h-10 bg-navy-900 rounded-xl flex items-center justify-center text-gold-500 border border-navy-800 overflow-hidden shadow-md">
                        @if(auth()->user()->profile_photo)
                            <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}" class="w-full h-full object-cover">
                        @else
                            <i class="fas fa-user-circle text-xl text-gold-500"></i>
                        @endif
                    </div>
                </div>
            </div>
        </header>

        <div class="p-8">
            <div class="max-w-7xl mx-auto">
                @if($errors->any())
                <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-2xl flex items-center gap-4 animate-pulse">
                    <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-red-500 shadow-sm border border-red-100">
                        <i class="fas fa-exclamation-circle"></i>
                    </div>
                    <div>
                        <h4 class="text-xs font-black text-red-900 uppercase tracking-tighter">Gagal Memproses Laporan!</h4>
                        <p class="text-[10px] text-red-700 font-medium mt-1">Harap periksa kembali semua isian yang wajib diisi (bertanda <span class="text-red-500">*</span>), termasuk foto dokumentasi.</p>
                    </div>
                </div>
                @endif

                <div id="offline-sync-container" class="hidden mb-6 p-5 bg-orange-50 border border-orange-200 rounded-2xl flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 bg-orange-100 rounded-xl flex items-center justify-center text-orange-600 shadow-sm border border-orange-200">
                            <i class="fas fa-wifi text-lg"></i>
                        </div>
                        <div>
                            <h4 class="text-xs font-black text-orange-900 uppercase tracking-tighter">Data Offline Tersimpan</h4>
                            <p class="text-[10px] text-orange-700 font-medium mt-1" id="offline-sync-count">Ada 0 laporan yang belum dikirim ke server.</p>
                        </div>
                    </div>
                    <button type="button" onclick="syncOfflineData()" class="px-5 py-2.5 bg-orange-600 hover:bg-orange-700 text-white rounded-xl text-[10px] font-black uppercase tracking-widest shadow-md transition-all flex items-center gap-2">
                        <i class="fas fa-cloud-upload-alt"></i> Upload Sekarang
                    </button>
                </div>

                <form id="survey-form" action="{{ route('surveyor.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                        
                        {{-- KOLOM KIRI --}}
                        <div class="lg:col-span-7 space-y-8">
                            
                            {{-- Section: Identitas Laporan --}}
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
                                                    @foreach($semuaKecamatan as $kec)
                                                        <option value="{{ $kec->id_kecamatan }}" {{ count($semuaKecamatan) == 1 ? 'selected' : '' }}>{{ $kec->nama_kecamatan }}</option>
                                                    @endforeach
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
                                                    @foreach($semuaKelurahan as $kel)
                                                        <option value="{{ $kel->id_kelurahan }}" 
                                                                data-kecamatan="{{ $kel->id_kecamatan }}"
                                                                data-lat="{{ $kel->latitude }}"
                                                                data-lng="{{ $kel->longitude }}">
                                                            {{ $kel->nama_kelurahan }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <i class="fas fa-chevron-down absolute right-5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-[10px]"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Section: Spesifikasi Teknis --}}
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
                                            
                                            {{-- DED Material Utama --}}
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

                        {{-- KOLOM KANAN --}}
                        <div class="lg:col-span-5 space-y-8">
                            
                            {{-- Section: Peta --}}
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
                                    
                                    <!-- Offline Map Warning -->
                                    <div id="offline-map-warning" class="hidden absolute inset-0 z-[5] bg-slate-100/90 backdrop-blur-sm flex flex-col items-center justify-center p-6 text-center border-2 border-orange-200">
                                        <i class="fas fa-wifi-slash text-3xl text-orange-400 mb-3"></i>
                                        <h5 class="text-xs font-black text-navy-900 uppercase tracking-widest mb-1">Peta Offline</h5>
                                        <p class="text-[10px] text-slate-500 font-bold leading-relaxed max-w-xs">Gambar peta tidak dapat dimuat tanpa internet, namun pencatatan koordinat GPS tetap berfungsi akurat.</p>
                                    </div>

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
                                    <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200 relative group">
                                        <div class="absolute top-2 right-2 text-red-500 text-[10px]"><i class="fas fa-asterisk"></i></div>
                                        <label class="block text-[8px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">Latitude</label>
                                        <input type="text" id="lat-input" name="latitude" readonly class="w-full bg-transparent border-none p-0 text-xs font-black text-navy-900 outline-none cursor-default" required placeholder="Kosong">
                                    </div>
                                    <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200 relative group">
                                        <div class="absolute top-2 right-2 text-red-500 text-[10px]"><i class="fas fa-asterisk"></i></div>
                                        <label class="block text-[8px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">Longitude</label>
                                        <input type="text" id="lng-input" name="longitude" readonly class="w-full bg-transparent border-none p-0 text-xs font-black text-navy-900 outline-none cursor-default" required placeholder="Kosong">
                                    </div>
                                </div>
                            </div>

                            {{-- Section: Dokumentasi & Submit --}}
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
                                            <strong class="font-black uppercase tracking-widest text-[10px] text-white">STATUS KONDISI:</strong><br>
                                            Ambil foto secara jelas. Foto ini akan digunakan untuk mendata status kondisi infrastruktur.
                                        </p>
                                    </div>

                                    <div class="grid grid-cols-1 gap-4 pt-4 border-t border-slate-100">
                                        <div class="flex items-center justify-between px-2 mb-2">
                                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Tgl Survey: <span class="text-navy-900">{{ date('d M Y') }}</span></span>
                                            <input type="hidden" name="tgl_survey" value="{{ date('Y-m-d') }}">
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
            const options = { timeZone: 'Asia/Makassar', hour: '2-digit', minute: '2-digit', hour12: false };
            const timeString = new Intl.DateTimeFormat('id-ID', options).format(now);
            const el = document.getElementById('mini-clock');
            if (el) el.textContent = timeString.replace('.', ':') + ' WITA';
        }
        setInterval(updateClock, 1000); updateClock();

        let compressedImageBlob = null;

        function previewImage(event) {
            const file = event.target.files[0];
            if (!file) return;

            new Compressor(file, {
                quality: 0.6,
                maxWidth: 1920,
                success(result) {
                    compressedImageBlob = result;
                    const reader = new FileReader();
                    reader.onload = function(){
                        const output = document.getElementById('image-preview');
                        const placeholder = document.getElementById('placeholder-elements');
                        output.src = reader.result;
                        output.classList.remove('hidden');
                        placeholder.classList.add('hidden');
                    };
                    reader.readAsDataURL(result);
                },
                error(err) {
                    console.error('Compression error:', err.message);
                },
            });
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

            const fallbackLocation = () => {
                // Karena kita di localhost/HTTP, navigator.geolocation sering diblokir browser.
                // Kita buat simulasi GPS yang mengunci koordinat di sekitar Kelurahan yang dipilih (jika ada), 
                // atau default ke Banjarmasin agar tidak nyasar ke negara lain akibat VPN/Proxy ISP.
                const kelSelect = document.getElementById('id_kelurahan');
                let defLat = -3.316694;
                let defLng = 114.590111;
                
                if (kelSelect && kelSelect.selectedIndex > 0) {
                    const opt = kelSelect.options[kelSelect.selectedIndex];
                    if (opt.dataset.lat && opt.dataset.lng) {
                        defLat = parseFloat(opt.dataset.lat);
                        defLng = parseFloat(opt.dataset.lng);
                    }
                }
                
                // Tambahkan sedikit pergeseran acak (jitter) agar tidak bertumpuk di titik tengah persis
                defLat += (Math.random() * 0.005 - 0.0025);
                defLng += (Math.random() * 0.005 - 0.0025);
                
                map.setView([defLat, defLng], 17);
                updateMarker(defLat, defLng);
                btn.innerHTML = '<span class="text-amber-400 font-bold text-[10px]"><i class="fas fa-exclamation-triangle"></i> Simulasi (Akurasi Rendah)</span>';
                setTimeout(() => { btn.innerHTML = originalHtml; }, 4000);
            };

            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;
                    const accuracy = position.coords.accuracy;
                    
                    map.setView([lat, lng], 17);
                    updateMarker(lat, lng);
                    
                    let accColor = accuracy <= 20 ? 'text-emerald-400' : 'text-orange-400';
                    btn.innerHTML = `<span class="${accColor} font-bold text-[10px]"><i class="fas fa-satellite-dish"></i> ±${Math.round(accuracy)}m (Satelit)</span>`;
                    
                    setTimeout(() => { btn.innerHTML = originalHtml; }, 4000);
                }, function(error) {
                    if (error.code === 1) { // PERMISSION_DENIED
                        Swal.fire({
                            title: 'Izin Lokasi Ditolak!',
                            text: 'Sistem tidak dapat menggunakan satelit karena izin diblokir browser. Silakan izinkan akses lokasi.',
                            icon: 'warning',
                            confirmButtonColor: '#1e1b4b'
                        });
                    }
                    fallbackLocation();
                }, {
                    enableHighAccuracy: true,
                    timeout: 15000,
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

        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js').catch(err => console.log('SW registration failed', err));
            });
        }

        localforage.config({ name: 'GeoSinfraOffline' });

        async function checkOfflineData() {
            const keys = await localforage.keys();
            const draftKeys = keys.filter(k => k.startsWith('draft_'));
            const syncContainer = document.getElementById('offline-sync-container');
            const syncCount = document.getElementById('offline-sync-count');
            
            if (draftKeys.length > 0) {
                syncContainer.classList.remove('hidden');
                syncCount.innerText = `Ada ${draftKeys.length} laporan survei yang belum dikirim ke server.`;
            } else {
                syncContainer.classList.add('hidden');
            }
        }

        async function syncOfflineData() {
            if (!navigator.onLine) {
                Swal.fire('Koneksi Terputus', 'Anda masih dalam mode offline. Cari sinyal internet terlebih dahulu.', 'warning');
                return;
            }

            const keys = await localforage.keys();
            const draftKeys = keys.filter(k => k.startsWith('draft_'));
            if (draftKeys.length === 0) return;

            Swal.fire({
                title: 'Mengunggah Data...',
                text: `Sinkronisasi ${draftKeys.length} data ke server. Mohon tunggu.`,
                allowOutsideClick: false,
                didOpen: () => { Swal.showLoading(); }
            });

            let successCount = 0;
            for (let key of draftKeys) {
                try {
                    const formData = await localforage.getItem(key);
                    const response = await fetch('{{ route("surveyor.store") }}', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    });
                    
                    if (response.ok || response.redirected) {
                        await localforage.removeItem(key);
                        successCount++;
                    }
                } catch (e) {
                    console.error('Gagal sync', key, e);
                }
            }

            checkOfflineData();
            Swal.fire('Sinkronisasi Selesai', `${successCount} dari ${draftKeys.length} data berhasil diunggah.`, 'success');
        }

        const form = document.getElementById('survey-form');
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            disableSubmitButton();

            // Validasi GPS Kosong
            if (!document.getElementById('lat-input').value || !document.getElementById('lng-input').value) {
                Swal.fire('Lokasi Kosong', 'Harap tekan tombol "Sync GPS" terlebih dahulu untuk mendapatkan koordinat.', 'warning');
                resetSubmitButton();
                return;
            }

            const formData = new FormData(form);
            if (compressedImageBlob) {
                formData.set('foto', compressedImageBlob, 'survey_photo.jpg');
            }

            if (!navigator.onLine) {
                const draftId = 'draft_' + new Date().getTime();
                await localforage.setItem(draftId, formData);
                
                Swal.fire('Tersimpan Offline', 'Tidak ada sinyal internet. Data berhasil disimpan di perangkat dan siap diunggah nanti.', 'info')
                .then(() => {
                    localStorage.removeItem('survey_draft');
                    form.reset();
                    document.getElementById('image-preview').classList.add('hidden');
                    document.getElementById('placeholder-elements').classList.remove('hidden');
                    compressedImageBlob = null;
                    document.getElementById('btn-submit').disabled = false;
                    document.getElementById('btn-submit').classList.remove('opacity-75', 'cursor-not-allowed');
                    document.getElementById('btn-text').innerHTML = 'Proses';
                    checkOfflineData();
                });
                return;
            }

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                
                if (response.ok || response.redirected) {
                    localStorage.removeItem('survey_draft');
                    Swal.fire('Berhasil!', 'Data survei berhasil diunggah ke server dan dianalisis AI.', 'success')
                    .then(() => { window.location.href = "{{ route('surveyor.history') }}"; });
                } else {
                    Swal.fire('Gagal', 'Terjadi kesalahan pada server saat mengunggah data.', 'error');
                    resetSubmitButton();
                }
            } catch (error) {
                Swal.fire('Koneksi Gagal', 'Gagal mengirim data. Coba cek sinyal atau simpan offline jika masih berlanjut.', 'error');
                resetSubmitButton();
            }
        });
        
        function resetSubmitButton() {
            document.getElementById('btn-submit').disabled = false;
            document.getElementById('btn-submit').classList.remove('opacity-75', 'cursor-not-allowed');
            document.getElementById('btn-text').innerHTML = 'Proses';
        }

        window.addEventListener('DOMContentLoaded', () => {
            filterKelurahan();
            checkOfflineData();
            
            window.addEventListener('online', checkOfflineData);
            window.addEventListener('offline', () => {
                const btn = document.getElementById('btn-submit');
                if(btn) {
                    document.getElementById('btn-text').innerHTML = 'Simpan Offline';
                    btn.classList.replace('bg-gold-500', 'bg-orange-500');
                }
                document.getElementById('offline-map-warning').classList.remove('hidden');
            });

            window.addEventListener('online', () => {
                document.getElementById('offline-map-warning').classList.add('hidden');
            });
            
            // Check initial network state for map
            if (!navigator.onLine) {
                document.getElementById('offline-map-warning').classList.remove('hidden');
            }

            // Auto-save drafts logic
            const formInputs = document.querySelectorAll('#survey-form input:not([type="file"]), #survey-form select, #survey-form textarea');
            function saveDraft() {
                let draft = {};
                formInputs.forEach(input => {
                    if(input.type === 'checkbox' || input.type === 'radio') {
                        draft[input.name] = input.checked;
                    } else {
                        draft[input.name] = input.value;
                    }
                });
                localStorage.setItem('survey_draft', JSON.stringify(draft));
            }
            
            function loadDraft() {
                const draftStr = localStorage.getItem('survey_draft');
                if(draftStr) {
                    try {
                        const draft = JSON.parse(draftStr);
                        formInputs.forEach(input => {
                            if(draft[input.name] !== undefined) {
                                if(input.type === 'checkbox' || input.type === 'radio') {
                                    input.checked = draft[input.name];
                                } else {
                                    input.value = draft[input.name];
                                }
                            }
                        });
                        // Filter dropdown dependent
                        filterKelurahan();
                        // Recover marker if lat lng exists
                        if(document.getElementById('lat-input').value && document.getElementById('lng-input').value) {
                            const lat = parseFloat(document.getElementById('lat-input').value);
                            const lng = parseFloat(document.getElementById('lng-input').value);
                            if (!isNaN(lat) && !isNaN(lng)) {
                                map.setView([lat, lng], 17);
                                updateMarker(lat, lng);
                            }
                        }
                    } catch(e) {}
                }
            }

            formInputs.forEach(input => {
                input.addEventListener('input', saveDraft);
                input.addEventListener('change', saveDraft);
            });

            loadDraft();
        });
    </script>
</body>
</html>
