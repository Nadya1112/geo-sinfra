<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data Lapangan | GEO-SINFRA</title>
    <link rel="icon" href="{{ asset('logo_geo-sinfra.png') }}" type="image/png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    
    <script>
        tailwind.config = {
            darkMode: 'class',
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
    <script>
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
    <style> 
        body { font-family: 'Plus Jakarta Sans', sans-serif; } 
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        .leaflet-container { font-family: inherit; }
        .premium-shadow { box-shadow: 0 20px 50px rgba(0,0,0,0.05); }
    </style>
</head>
<body class="bg-slate-50 dark:bg-[#0f0e2c] flex h-screen overflow-hidden text-slate-800 text-left font-sans dark:bg-navy-950 dark:text-white transition-colors duration-300">

    @include('surveyor.partials.sidebar')

    <main class="flex-1 flex flex-col h-screen overflow-y-auto custom-scrollbar bg-slate-50 dark:bg-[#0f0e2c]">
        {{-- ── Header ── --}}
        <header class="bg-white/80 dark:bg-[#1e1b4b]/80 backdrop-blur-xl border-b border-slate-100 dark:border-white/10 px-8 py-5 flex justify-between items-center sticky top-0 z-[1000] shadow-sm">
            <div class="flex items-center gap-4">
                <a href="{{ route('surveyor.history') }}" class="w-10 h-10 flex items-center justify-center bg-white dark:bg-[#1e1b4b] text-slate-400 rounded-xl hover:bg-gold-50 hover:text-gold-600 transition-all border border-slate-200 dark:border-white/20 hover:border-gold-200">
                    <i class="fas fa-arrow-left text-sm"></i>
                </a>
                <div>
                    <p class="text-[9px] font-black text-gold-500 uppercase tracking-[0.2em] mb-0.5">Edit Data</p>
                    <h2 class="text-xl font-black text-navy-900 dark:text-white tracking-tight">Perbarui Laporan Lapangan</h2>
                </div>
            </div>
            
            <div class="flex items-center gap-6">
                <div class="text-right hidden sm:block">
                    <p class="text-[11px] font-black text-navy-900 dark:text-white" id="mini-clock">00:00 WITA</p>
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-tighter">{{ now()->translatedFormat('l, d F Y') }}</p>
                </div>
                <div class="h-8 w-[1px] bg-slate-100"></div>
                <a href="{{ route('surveyor.profile') }}" class="flex items-center gap-3">
                    <div class="text-right">
                        <p class="text-[10px] font-black text-navy-900 dark:text-white leading-none uppercase">{{ auth()->user()->name }}</p>
                        <p class="text-[8px] font-bold text-emerald-500 uppercase mt-1">Online</p>
                    </div>
                    <div class="w-10 h-10 bg-navy-900 rounded-xl flex items-center justify-center text-gold-500 border border-navy-800 overflow-hidden shadow-md">
                        @if(auth()->user()->profile_photo)
                            <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}" class="w-full h-full object-cover">
                        @else
                            <i class="fas fa-user-circle text-xl text-gold-500"></i>
                        @endif
                    </div>
                </a>
            </div>
        </header>

        <div class="p-8">
            <div class="max-w-7xl mx-auto">
                @if($errors->any())
                <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-2xl flex items-center gap-4 animate-pulse">
                    <div class="w-10 h-10 bg-white dark:bg-[#1e1b4b] rounded-xl flex items-center justify-center text-red-500 shadow-sm border border-red-100">
                        <i class="fas fa-exclamation-circle"></i>
                    </div>
                    <div>
                        <h4 class="text-xs font-black text-red-900 uppercase tracking-tighter">Gagal Memperbarui Laporan!</h4>
                        <p class="text-[10px] text-red-700 font-medium mt-1">Harap periksa kembali semua isian yang wajib diisi (bertanda <span class="text-red-500">*</span>).</p>
                    </div>
                </div>
                @endif

                <form id="survey-form" action="{{ route('surveyor.infrastruktur.update', $infrastruktur->id_infrastruktur) }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 lg:grid-cols-12 gap-8" onsubmit="disableSubmitButton()">
                    @csrf
                    @method('PUT')
                    
                    {{-- KOLOM KIRI --}}
                    <div class="lg:col-span-7 space-y-8">
                        
                        {{-- Section: Identitas Laporan --}}
                        <div class="bg-white dark:bg-[#1e1b4b] rounded-[2.5rem] p-8 border border-slate-100 dark:border-white/10 shadow-sm hover:shadow-md transition-shadow">
                            
                            {{-- Info Bar (Status & AI) --}}
                            <div class="flex items-center justify-between mb-8 p-4 bg-slate-50 dark:bg-[#0f0e2c] rounded-2xl border border-slate-100 dark:border-white/10">
                                <h4 class="font-black text-navy-900 dark:text-white text-xs uppercase tracking-widest">Status Terkini</h4>
                                <div class="flex items-center gap-3">
                                    <span class="px-4 py-1.5 rounded-xl text-[10px] font-black tracking-widest border uppercase shadow-sm
                                        {{ $infrastruktur->kondisi == 'Baik' ? 'bg-emerald-50 text-emerald-600 border-emerald-200' : 
                                        ($infrastruktur->kondisi == 'Rusak Sedang' ? 'bg-amber-50 text-amber-600 border-amber-200' : 
                                        ($infrastruktur->kondisi == 'Rusak Berat' ? 'bg-red-50 text-red-600 border-red-200' : 'bg-white dark:bg-[#1e1b4b] text-slate-500 border-slate-200 dark:border-white/20')) }}">
                                        {{ $infrastruktur->kondisi }}
                                    </span>
                                    @if($infrastruktur->cnn || $infrastruktur->analisis)
                                    <div class="flex gap-2">
                                        @if($infrastruktur->cnn)
                                            <span class="px-3 py-1.5 rounded-xl bg-white dark:bg-[#1e1b4b] border border-slate-200 dark:border-white/20 text-[9px] font-black text-slate-500 shadow-sm">
                                                CNN: <span class="text-navy-900 dark:text-white">{{ number_format($infrastruktur->cnn->skor_cnn * 100, 1) }}%</span>
                                            </span>
                                        @endif
                                        @if($infrastruktur->analisis)
                                            <span class="px-3 py-1.5 rounded-xl bg-gold-50 border border-gold-200 text-[9px] font-black text-slate-500 shadow-sm">
                                                AI: <span class="text-gold-600">{{ $infrastruktur->analisis->label_prioritas }}</span>
                                            </span>
                                        @endif
                                    </div>
                                    @endif
                                </div>
                            </div>

                            <div class="flex items-center gap-4 mb-6 pb-6 border-b border-slate-50">
                                <div class="w-12 h-12 rounded-2xl bg-navy-50 dark:bg-navy-900 flex items-center justify-center text-gold-500 border border-navy-100">
                                    <i class="fas fa-file-signature text-xl"></i>
                                </div>
                                <div>
                                    <h4 class="font-black text-navy-900 dark:text-white uppercase tracking-tight text-lg">Identitas Laporan</h4>
                                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">Klasifikasi Objek Infrastruktur</p>
                                </div>
                            </div>

                            <div class="space-y-6">
                                <div class="grid grid-cols-1 gap-5">
                                    <div>
                                        <label class="block text-[10px] font-black text-navy-900 dark:text-white uppercase tracking-widest mb-3 ml-1">Alamat / Lokasi Jalan <span class="text-red-500">*</span></label>
                                        <div class="relative group">
                                            <i class="fas fa-map-pin absolute left-5 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-gold-500 transition-colors"></i>
                                            <input type="text" name="nama_infrastruktur" value="{{ old('nama_infrastruktur', $infrastruktur->nama_objek) }}" placeholder="Contoh: Gg. Manggis RT 02 / Jalan Hasan Basry" class="w-full pl-12 pr-5 py-4 bg-slate-50 dark:bg-[#0f0e2c] border border-slate-200 dark:border-white/20 rounded-2xl text-sm font-semibold focus:ring-4 focus:ring-gold-500/10 focus:border-gold-500 outline-none transition-all text-navy-900 dark:text-white" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                    <div>
                                        <label class="block text-[10px] font-black text-navy-900 dark:text-white uppercase tracking-widest mb-3 ml-1">
                                            Kecamatan Wilayah <span class="text-red-500">*</span>
                                            @if(auth()->user()->id_kecamatan)
                                                <span class="ml-2 text-[8px] text-gold-600 bg-gold-50 px-2 py-0.5 rounded-full font-bold">WILAYAH TUGAS ANDA</span>
                                            @endif
                                        </label>
                                        <div class="relative group">
                                            <i class="fas fa-map-location-dot absolute left-5 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-gold-500 transition-colors z-10"></i>
                                            <select name="id_kecamatan" id="id_kecamatan" class="w-full pl-12 pr-10 py-4 bg-slate-50 dark:bg-[#0f0e2c] border border-slate-200 dark:border-white/20 rounded-2xl text-sm font-semibold focus:ring-4 focus:ring-gold-500/10 focus:border-gold-500 outline-none appearance-none cursor-pointer transition-all relative z-0 text-navy-900 dark:text-white" required onchange="filterKelurahan()">
                                                <option value="">Pilih Kecamatan...</option>
                                                @foreach($semuaKecamatan as $kec)
                                                    <option value="{{ $kec->id_kecamatan }}" {{ $infrastruktur->id_kecamatan == $kec->id_kecamatan ? 'selected' : '' }}>{{ $kec->nama_kecamatan }}</option>
                                                @endforeach
                                            </select>
                                            <i class="fas fa-chevron-down absolute right-5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-[10px]"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-black text-navy-900 dark:text-white uppercase tracking-widest mb-3 ml-1">Kelurahan / Desa <span class="text-red-500">*</span></label>
                                        <div class="relative group">
                                            <i class="fas fa-city absolute left-5 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-gold-500 transition-colors z-10"></i>
                                            <select name="id_kelurahan" id="id_kelurahan" class="w-full pl-12 pr-10 py-4 bg-slate-50 dark:bg-[#0f0e2c] border border-slate-200 dark:border-white/20 rounded-2xl text-sm font-semibold focus:ring-4 focus:ring-gold-500/10 focus:border-gold-500 outline-none appearance-none cursor-pointer transition-all relative z-0 text-navy-900 dark:text-white" required onchange="focusToKelurahan()">
                                                <option value="">Pilih Kelurahan...</option>
                                                @foreach($semuaKelurahan as $kel)
                                                    <option value="{{ $kel->id_kelurahan }}" 
                                                            data-kecamatan="{{ $kel->id_kecamatan }}"
                                                            data-lat="{{ $kel->latitude }}"
                                                            data-lng="{{ $kel->longitude }}"
                                                            {{ $infrastruktur->id_kelurahan == $kel->id_kelurahan ? 'selected' : '' }}>
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
                        <div class="bg-white dark:bg-[#1e1b4b] rounded-[2.5rem] p-8 border border-slate-100 dark:border-white/10 shadow-sm hover:shadow-md transition-shadow">
                            <div class="flex items-center gap-4 mb-8 pb-6 border-b border-slate-50">
                                <div class="w-12 h-12 rounded-2xl bg-navy-50 dark:bg-navy-900 flex items-center justify-center text-gold-500 border border-navy-100">
                                    <i class="fas fa-ruler-combined text-xl"></i>
                                </div>
                                <div>
                                    <h4 class="font-black text-navy-900 dark:text-white uppercase tracking-tight text-lg">Spesifikasi Teknis</h4>
                                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">Dimensi & Material Infrastruktur</p>
                                </div>
                            </div>

                            <div class="space-y-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                    <div class="space-y-6">
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-[10px] font-black text-navy-900 dark:text-white uppercase tracking-widest mb-3 ml-1">Panjang (m) <span class="text-red-500">*</span></label>
                                                <div class="relative group">
                                                    <i class="fas fa-arrows-left-right absolute left-5 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-gold-500 transition-colors"></i>
                                                    <input type="number" step="0.01" name="panjang" value="{{ old('panjang', $infrastruktur->panjang) }}" placeholder="0.00" class="w-full pl-12 pr-5 py-3.5 bg-slate-50 dark:bg-[#0f0e2c] border border-slate-200 dark:border-white/20 rounded-2xl text-sm font-semibold focus:ring-4 focus:ring-gold-500/10 focus:border-gold-500 outline-none transition-all text-navy-900 dark:text-white" required>
                                                </div>
                                            </div>
                                            <div>
                                                <label class="block text-[10px] font-black text-navy-900 dark:text-white uppercase tracking-widest mb-3 ml-1">Lebar (m) <span class="text-red-500">*</span></label>
                                                <div class="relative group">
                                                    <i class="fas fa-arrows-up-down absolute left-5 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-gold-500 transition-colors"></i>
                                                    <input type="number" step="0.01" name="lebar" value="{{ old('lebar', $infrastruktur->lebar) }}" placeholder="0.00" class="w-full pl-12 pr-5 py-3.5 bg-slate-50 dark:bg-[#0f0e2c] border border-slate-200 dark:border-white/20 rounded-2xl text-sm font-semibold focus:ring-4 focus:ring-gold-500/10 focus:border-gold-500 outline-none transition-all text-navy-900 dark:text-white" required>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        {{-- Material Utama --}}
                                        <div>
                                            <label class="block text-[10px] font-black text-navy-900 dark:text-white uppercase tracking-widest mb-3 ml-1">Material Utama  <span class="text-red-500">*</span></label>
                                            <div class="relative group">
                                                <i class="fas fa-layer-group absolute left-5 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-gold-500 transition-colors z-10"></i>
                                                <select name="material_eksisting" class="w-full pl-12 pr-10 py-3.5 bg-slate-50 dark:bg-[#0f0e2c] border border-slate-200 dark:border-white/20 rounded-2xl text-sm font-semibold focus:ring-4 focus:ring-gold-500/10 focus:border-gold-500 outline-none appearance-none cursor-pointer transition-all relative z-0 text-navy-900 dark:text-white" required>
                                                    <option value="" disabled>Pilih Material Utama...</option>
                                                    @foreach(['Cor Beton', 'Titian (Kayu Ulin)', 'Tanah Asli', 'Tanah Pemadatan', 'Tanah Lepas', 'Paving Block', 'Aspal', 'Bata Press'] as $mat)
                                                        <option value="{{ $mat }}" {{ (old('material_eksisting', $infrastruktur->material_eksisting) == $mat) ? 'selected' : '' }}>{{ $mat }}</option>
                                                    @endforeach
                                                </select>
                                                <i class="fas fa-chevron-down absolute right-5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-[10px]"></i>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="space-y-4">
                                        <label class="block text-[10px] font-black text-navy-900 dark:text-white uppercase tracking-widest mb-3 ml-1">Ketersediaan </label>
                                        <div class="space-y-3">
                                            <label class="flex items-center gap-4 p-4 bg-slate-50 dark:bg-[#0f0e2c] rounded-2xl border border-slate-200 dark:border-white/20 cursor-pointer hover:bg-gold-50 hover:border-gold-200 transition-all group">
                                                <input type="checkbox" name="has_drainase" value="1" {{ $infrastruktur->has_drainase == 'ya' ? 'checked' : '' }} class="peer hidden">
                                                <div class="w-6 h-6 rounded-lg border-2 border-slate-300 peer-checked:bg-gold-500 peer-checked:border-gold-500 transition-all flex items-center justify-center">
                                                    <i class="fas fa-check text-xs text-white opacity-0 peer-checked:opacity-100"></i>
                                                </div>
                                                <span class="text-xs font-black text-navy-900 dark:text-white uppercase tracking-widest">Saluran Drainase</span>
                                            </label>
                                            <label class="flex items-center gap-4 p-4 bg-slate-50 dark:bg-[#0f0e2c] rounded-2xl border border-slate-200 dark:border-white/20 cursor-pointer hover:bg-gold-50 hover:border-gold-200 transition-all group">
                                                <input type="checkbox" name="has_gorong_gorong" value="1" {{ $infrastruktur->has_gorong_gorong == 'ya' ? 'checked' : '' }} class="peer hidden">
                                                <div class="w-6 h-6 rounded-lg border-2 border-slate-300 peer-checked:bg-gold-500 peer-checked:border-gold-500 transition-all flex items-center justify-center">
                                                    <i class="fas fa-check text-xs text-white opacity-0 peer-checked:opacity-100"></i>
                                                </div>
                                                <span class="text-xs font-black text-navy-900 dark:text-white uppercase tracking-widest">Gorong-gorong</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="pt-4 border-t border-slate-50">
                                    <label class="block text-[10px] font-black text-navy-900 dark:text-white uppercase tracking-widest mb-3 ml-1">Deskripsi Kondisi Fisik Lapangan <span class="text-slate-400 font-medium">(Opsional)</span></label>
                                    <div class="relative group">
                                        <textarea name="kondisi" id="kondisi-textarea" rows="3" placeholder="Deskripsikan kerusakan spesifik..." class="w-full px-5 py-4 bg-slate-50 dark:bg-[#0f0e2c] border border-slate-200 dark:border-white/20 rounded-2xl text-sm font-semibold focus:ring-4 focus:ring-gold-500/10 focus:border-gold-500 outline-none transition-all text-navy-900 dark:text-white">{{ old('kondisi', $infrastruktur->kondisi) }}</textarea>
                                    </div>
                                    <p class="text-[9px] text-slate-400 mt-2 italic font-medium px-2">* Memperbarui deskripsi akan menyebabkan AI menghitung ulang prioritas.</p>
                                </div>

                            </div>
                        </div>
                    </div>

                    {{-- KOLOM KANAN --}}
                    <div class="lg:col-span-5 space-y-8">
                        
                        {{-- Section: Peta --}}
                        <div class="bg-white dark:bg-[#1e1b4b] rounded-[2.5rem] p-8 border border-slate-100 dark:border-white/10 shadow-sm hover:shadow-md transition-shadow">
                            <div class="flex items-center justify-between mb-8 pb-4 border-b border-slate-50">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-2xl bg-navy-50 dark:bg-navy-900 flex items-center justify-center text-gold-500 border border-navy-100">
                                        <i class="fas fa-location-crosshairs text-xl"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-black text-navy-900 dark:text-white uppercase tracking-tight text-lg">Titik Lokasi</h4>
                                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">Akurasi Geospasial</p>
                                    </div>
                                </div>
                                <button type="button" onclick="getLocation()" class="px-4 py-3 bg-navy-900 hover:bg-gold-500 hover:text-navy-900 dark:text-white text-white rounded-xl text-[9px] font-black uppercase tracking-widest flex items-center gap-2 transition-all shadow-md active:scale-95 border border-white/10">
                                    <i class="fas fa-crosshairs"></i>
                                    Sync GPS
                                </button>
                            </div>

                            <div class="relative rounded-[2rem] border-[6px] border-slate-50 shadow-inner overflow-hidden mb-6 h-[260px]">
                                <div id="map" class="absolute inset-0 z-0"></div>
                                <div class="absolute bottom-4 left-4 right-4 z-10">
                                    <div class="bg-white/90 dark:bg-[#1e1b4b]/90 backdrop-blur-md px-4 py-3 rounded-xl shadow-lg border border-slate-100 dark:border-white/10 text-center flex items-center justify-center gap-2">
                                        <div class="w-2 h-2 rounded-full bg-gold-500 animate-pulse"></div>
                                        <p class="text-[9px] font-black uppercase tracking-widest text-navy-900 dark:text-white">Klik Peta Untuk Menggeser Pin</p>
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div class="bg-slate-50 dark:bg-[#0f0e2c] p-4 rounded-2xl border border-slate-200 dark:border-white/20">
                                    <label class="block text-[8px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">Latitude</label>
                                    <input type="text" id="lat-input" name="latitude" value="{{ old('latitude', $infrastruktur->latitude) }}" class="w-full bg-transparent border-none p-0 text-xs font-black text-navy-900 dark:text-white outline-none focus:ring-0">
                                </div>
                                <div class="bg-slate-50 dark:bg-[#0f0e2c] p-4 rounded-2xl border border-slate-200 dark:border-white/20">
                                    <label class="block text-[8px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">Longitude</label>
                                    <input type="text" id="lng-input" name="longitude" value="{{ old('longitude', $infrastruktur->longitude) }}" class="w-full bg-transparent border-none p-0 text-xs font-black text-navy-900 dark:text-white outline-none focus:ring-0">
                                </div>
                            </div>
                        </div>

                        {{-- Section: Dokumentasi & Submit --}}
                        <div class="bg-white dark:bg-[#1e1b4b] rounded-[2.5rem] p-8 border border-slate-100 dark:border-white/10 shadow-sm hover:shadow-md transition-shadow">
                            <h4 class="text-[10px] font-black text-navy-900 dark:text-white mb-6 border-b border-slate-50 pb-4 uppercase tracking-widest">Dokumentasi Visual (Terkunci)</h4>
                            <div class="relative rounded-[2rem] overflow-hidden border border-slate-100 dark:border-white/10 shadow-inner bg-slate-50 dark:bg-[#0f0e2c] h-52 flex items-center justify-center mb-8">
                                @if($infrastruktur->foto_terbaru)
                                    <img src="{{ asset('storage/infrastruktur/' . $infrastruktur->foto_terbaru) }}" class="absolute inset-0 w-full h-full object-cover">
                                @else
                                    <div class="text-center">
                                        <i class="fas fa-image text-3xl text-slate-300 mb-2"></i>
                                        <p class="text-[10px] font-bold text-slate-400 uppercase">Tidak ada foto</p>
                                    </div>
                                @endif
                                <div class="absolute inset-0 bg-navy-900/40 flex items-center justify-center backdrop-blur-[2px]">
                                    <span class="px-5 py-2.5 bg-white/95 dark:bg-[#1e1b4b]/95 backdrop-blur-md rounded-xl text-[9px] font-black text-navy-900 dark:text-white uppercase tracking-widest border border-slate-200 dark:border-white/20 shadow-lg flex items-center gap-2">
                                        <i class="fas fa-lock text-gold-500"></i> Foto Dilindungi AI
                                    </span>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 gap-4 pt-4 border-t border-slate-100 dark:border-white/10">
                                <div class="flex items-center justify-between px-2 mb-2">
                                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Tgl Survey: <span class="text-navy-900 dark:text-white">{{ \Carbon\Carbon::parse($infrastruktur->tgl_survey)->translatedFormat('d M Y') }}</span></span>
                                    <input type="hidden" name="tgl_survey" value="{{ $infrastruktur->tgl_survey }}">
                                </div>
                                <button type="submit" id="btn-submit" class="w-full py-5 bg-navy-900 hover:bg-gold-500 text-white hover:text-navy-900 dark:text-white rounded-[2rem] font-black text-[11px] uppercase tracking-[0.2em] transition-all shadow-xl active:scale-95 flex items-center justify-center gap-3">
                                    <span id="btn-text">Simpan Perubahan</span>
                                    <i class="fas fa-save group-hover:scale-110 transition-transform"></i>
                                </button>
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

        const initialLat = {{ $infrastruktur->latitude ?? -3.316694 }};
        const initialLng = {{ $infrastruktur->longitude ?? 114.590111 }};

        const map = L.map('map', {
            zoomControl: false,
            attributionControl: false
        }).setView([initialLat, initialLng], 15);
        
        L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png').addTo(map);

        let marker = L.marker([initialLat, initialLng], {
            icon: L.divIcon({
                className: '',
                html: `<div class="w-8 h-8 bg-gold-500 rounded-full border-[3px] border-white shadow-lg flex items-center justify-center text-white"><i class="fas fa-location-dot"></i></div>`,
                iconSize: [32, 32],
                iconAnchor: [16, 32]
            })
        }).addTo(map);

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

        function getLocation() {
            const btn = event.currentTarget;
            const originalHtml = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mencari...';

            const fallbackLocation = () => {
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
                
                defLat += (Math.random() * 0.005 - 0.0025);
                defLng += (Math.random() * 0.005 - 0.0025);
                
                map.setView([defLat, defLng], 17);
                updateMarker(defLat, defLng);
                btn.innerHTML = '<span class="text-emerald-400 font-bold text-[10px]"><i class="fas fa-check"></i> Sukses (Simulasi)</span>';
                setTimeout(() => { btn.innerHTML = originalHtml; }, 3000);
            };

            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;
                    map.setView([lat, lng], 17);
                    updateMarker(lat, lng);
                    btn.innerHTML = '<span class="text-emerald-400 font-bold text-[10px]"><i class="fas fa-check"></i> Sukses</span>';
                    setTimeout(() => { btn.innerHTML = originalHtml; }, 3000);
                }, function() {
                    fallbackLocation();
                }, {
                    enableHighAccuracy: true,
                    timeout: 5000,
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
            
            const currentSelected = kelurahanSelect.value;
            let currentStillVisible = false;

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
                    if (opt.value === currentSelected) currentStillVisible = true;
                } else {
                    opt.style.display = "none";
                    opt.disabled = true;
                    opt.hidden = true;
                }
            });
            
            if (!currentStillVisible && idKecamatan !== "") {
                kelurahanSelect.value = "";
            }
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

        document.getElementById('lat-input').addEventListener('input', updateMapFromInput);
        document.getElementById('lng-input').addEventListener('input', updateMapFromInput);

        function updateMapFromInput() {
            const latStr = document.getElementById('lat-input').value.replace(',', '.');
            const lngStr = document.getElementById('lng-input').value.replace(',', '.');
            const lat = parseFloat(latStr);
            const lng = parseFloat(lngStr);
            
            if (!isNaN(lat) && !isNaN(lng)) {
                if (marker) map.removeLayer(marker);
                marker = L.marker([lat, lng], {
                    icon: L.divIcon({
                        className: '',
                        html: `<div class="w-8 h-8 bg-gold-500 rounded-full border-[3px] border-white shadow-lg flex items-center justify-center text-white"><i class="fas fa-location-dot"></i></div>`,
                        iconSize: [32, 32],
                        iconAnchor: [16, 32]
                    })
                }).addTo(map);
                map.setView([lat, lng]);
            }
        }

        window.addEventListener('DOMContentLoaded', () => {
            filterKelurahan();
        });
    </script>
</body>
</html>
