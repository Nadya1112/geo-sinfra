<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Aset Infrastruktur | Admin SINFRA</title>
    <link rel="icon" href="{{ asset('logo_geo-sinfra.png') }}" type="image/png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Plus Jakarta Sans', 'sans-serif'] },
                    colors: {
                        navy: {
                            50:  '#f4f4fa',
                            100: '#e9e9f3',
                            500: '#6366f1',
                            800: '#1e1b4b',
                            900: '#0f0e2c',
                            950: '#070617',
                        },
                        gold: {
                            50:  '#fdfbf7',
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
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }

        /* Map */
        #create-map { border: none !important; outline: none !important; box-shadow: none !important; }

        /* Zoom Controls */
        .leaflet-control-zoom { border: none !important; box-shadow: 0 4px 24px rgba(7,6,23,0.15) !important; border-radius: 0.75rem !important; overflow: hidden; }
        .leaflet-control-zoom a { width: 36px !important; height: 36px !important; line-height: 36px !important; background: #0f0e2c !important; color: #c5a059 !important; border: none !important; border-bottom: 1px solid rgba(255,255,255,0.08) !important; font-size: 16px !important; font-weight: 900 !important; transition: background 0.2s !important; }
        .leaflet-control-zoom a:hover { background: #1e1b4b !important; color: #fff !important; }
        .leaflet-control-zoom-out { border-bottom: none !important; }

        /* File input */
        #foto-input { display: none; }
        #foto-label { cursor: pointer; }
        #foto-preview { display: none; }
    </style>
</head>
<body class="bg-slate-50 flex h-screen overflow-hidden text-slate-800 font-sans">

    @include('admin.partials.sidebar')

    <main class="flex-1 flex flex-col h-screen overflow-hidden">

        {{-- ── Header ── --}}
        <header class="bg-white/80 backdrop-blur-xl border-b border-slate-100 px-8 py-5 flex justify-between items-center z-40 shrink-0">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.infrastruktur') }}"
                   class="w-10 h-10 bg-white border border-slate-100 rounded-xl flex items-center justify-center text-slate-400 hover:text-gold-500 hover:border-gold-500/30 hover:shadow-md transition-all group"
                   title="Kembali ke Manajemen Infrastruktur">
                    <i class="fas fa-arrow-left text-xs group-hover:-translate-x-1 transition-transform"></i>
                </a>
                <div>
                    <p class="text-[10px] font-black text-gold-500 uppercase tracking-[0.2em] mb-1">Administrator Portal</p>
                    <h2 class="text-xl font-black text-navy-900 leading-none">Tambah Aset Infrastruktur</h2>
                </div>
            </div>

            <div class="flex items-center gap-6">
                <div class="text-right hidden sm:block">
                    <p class="text-[11px] font-black text-navy-900" id="mini-clock">00:00 WITA</p>
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-tighter">{{ now()->translatedFormat('l, d F Y') }}</p>
                </div>
                <div class="h-8 w-[1px] bg-slate-100"></div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.profile') }}" class="text-right group">
                        <p class="text-[11px] font-black text-navy-900 leading-none uppercase group-hover:text-gold-500 transition-all">{{ auth()->user()->name }}</p>
                        <p class="text-[9px] font-bold text-emerald-500 uppercase mt-1">Online</p>
                    </a>
                    <a href="{{ route('admin.profile') }}" class="w-10 h-10 bg-navy-900 rounded-xl flex items-center justify-center text-gold-500 border border-white/10 overflow-hidden hover:shadow-lg transition-all shadow-md">
                        @if(auth()->user()->profile_photo)
                            <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}" class="w-full h-full object-cover">
                        @else
                            <i class="fas fa-user-circle text-xl"></i>
                        @endif
                    </a>
                </div>
            </div>
        </header>

        {{-- ── Content ── --}}
        <div class="flex-1 overflow-y-auto custom-scrollbar p-8 pb-16">

            {{-- Badge --}}
            <div class="flex items-center gap-3 mb-6">
                <span class="px-3 py-1.5 bg-gold-500 text-white rounded-xl text-[9px] font-black tracking-widest uppercase">
                    <i class="fas fa-plus mr-1"></i> Input Aset Baru
                </span>
                <span class="text-[10px] text-slate-400 font-semibold">Data akan dianalisis AI secara otomatis setelah disimpan</span>
            </div>

            {{-- Error Alert --}}
            @if($errors->any())
            <div class="mb-6 p-5 bg-red-50 border border-red-200 rounded-2xl flex items-center gap-4">
                <div class="w-10 h-10 bg-red-100 rounded-xl flex items-center justify-center text-red-500 shrink-0">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div>
                    <h4 class="text-sm font-black text-red-800 uppercase mb-1">Validasi Gagal!</h4>
                    <ul class="text-[11px] text-red-600 font-semibold space-y-0.5">
                        @foreach($errors->all() as $error)
                            <li>• {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif

            <form action="{{ route('admin.infrastruktur.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

                    {{-- ── Kolom Kiri (2/3) ── --}}
                    <div class="xl:col-span-2 space-y-6">

                        {{-- Section 1: Identitas & Wilayah --}}
                        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-8">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-8 h-8 bg-navy-900 rounded-xl flex items-center justify-center text-gold-500 shrink-0">
                                    <i class="fas fa-info-circle text-xs"></i>
                                </div>
                                <div>
                                    <h4 class="text-sm font-black text-navy-900 uppercase tracking-wider">Identitas & Wilayah</h4>
                                    <p class="text-[9px] text-slate-400 font-semibold mt-0.5">Nama dan lokasi wilayah infrastruktur</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                {{-- Nama --}}
                                <div class="md:col-span-2">
                                    <label class="block text-[10px] font-black text-navy-900 uppercase tracking-widest mb-2">Nama Infrastruktur <span class="text-red-400">*</span></label>
                                    <input type="text" name="nama_infrastruktur"
                                           value="{{ old('nama_infrastruktur') }}"
                                           placeholder="Contoh: Titian Sungai Bilu RT 03..."
                                           class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-700 outline-none focus:ring-2 focus:ring-gold-500/20 focus:border-gold-500 transition-all placeholder-slate-400"
                                           required>
                                </div>

                                {{-- Jenis — Read only, ditentukan AI --}}
                                <div>
                                    <label class="block text-[10px] font-black text-navy-900 uppercase tracking-widest mb-2">Jenis Infrastruktur <span class="text-red-400">*</span></label>
                                    <select name="jenis"
                                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-700 outline-none focus:ring-2 focus:ring-gold-500/20 focus:border-gold-500 transition-all" required>
                                        <option value="" disabled selected>-- Pilih Jenis --</option>
                                        <option value="jalan" {{ old('jenis') == 'jalan' ? 'selected' : '' }}>Jalan</option>
                                        <option value="titian" {{ old('jenis') == 'titian' ? 'selected' : '' }}>Titian</option>

                                        <option value="jembatan" {{ old('jenis') == 'jembatan' ? 'selected' : '' }}>Jembatan</option>
                                    </select>
                                </div>

                                {{-- Kecamatan --}}
                                <div>
                                    <label class="block text-[10px] font-black text-navy-900 uppercase tracking-widest mb-2">Kecamatan</label>
                                    <select name="id_kecamatan" id="select-kecamatan"
                                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-700 outline-none focus:ring-2 focus:ring-gold-500/20 focus:border-gold-500 transition-all">
                                        @foreach($semuaKecamatan as $kec)
                                            <option value="{{ $kec->id_kecamatan }}" {{ old('id_kecamatan') == $kec->id_kecamatan ? 'selected' : '' }}>
                                                {{ $kec->nama_kecamatan }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Kelurahan --}}
                                <div class="md:col-span-2">
                                    <label class="block text-[10px] font-black text-navy-900 uppercase tracking-widest mb-2">Kelurahan</label>
                                    <select name="id_kelurahan"
                                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-700 outline-none focus:ring-2 focus:ring-gold-500/20 focus:border-gold-500 transition-all">
                                        @foreach($semuaKelurahan as $kel)
                                            <option value="{{ $kel->id_kelurahan }}" {{ old('id_kelurahan') == $kel->id_kelurahan ? 'selected' : '' }}>
                                                {{ $kel->nama_kelurahan }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        {{-- Section 2: Detail Teknis & Parameter AI --}}
                        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-8">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-8 h-8 bg-gold-500 rounded-xl flex items-center justify-center text-white shrink-0">
                                    <i class="fas fa-brain text-xs"></i>
                                </div>
                                <div>
                                    <h4 class="text-sm font-black text-navy-900 uppercase tracking-wider">Detail Teknis & Parameter AI</h4>
                                    <p class="text-[9px] text-slate-400 font-semibold mt-0.5">Data ini digunakan sebagai input model Decision Tree</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-5">
                                <div>
                                    <label class="block text-[10px] font-black text-navy-900 uppercase tracking-widest mb-2">Material Utama <span class="text-red-400">*</span></label>
                                    <select name="material_eksisting"
                                        class="w-full px-4 py-3 bg-amber-50 border border-amber-100 rounded-xl text-sm font-semibold text-slate-700 outline-none focus:ring-2 focus:ring-gold-500/20 focus:border-gold-500 transition-all"
                                        required>
                                        <option value="" disabled selected>-- Pilih Material --</option>
                                        <option value="Cor Beton">Cor Beton</option>
                                        <option value="Titian (Kayu Ulin)">Titian (Kayu Ulin)</option>
                                        <option value="Tanah Asli">Tanah Asli</option>
                                        <option value="Tanah Pemadatan">Tanah Pemadatan</option>
                                        <option value="Tanah Lepas">Tanah Lepas</option>
                                        <option value="Paving Block">Paving Block</option>
                                        <option value="Aspal">Aspal</option>
                                        <option value="Bata Press">Bata Press</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-navy-900 uppercase tracking-widest mb-2">Panjang (m) <span class="text-red-400">*</span></label>
                                    <input type="number" step="0.01" name="panjang"
                                           value="{{ old('panjang') }}"
                                           placeholder="0.00"
                                           class="w-full px-4 py-3 bg-amber-50 border border-amber-100 rounded-xl text-sm font-semibold text-slate-700 outline-none focus:ring-2 focus:ring-gold-500/20 focus:border-gold-500 transition-all"
                                           required>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-navy-900 uppercase tracking-widest mb-2">Lebar (m) <span class="text-red-400">*</span></label>
                                    <input type="number" step="0.01" name="lebar"
                                           value="{{ old('lebar') }}"
                                           placeholder="0.00"
                                           class="w-full px-4 py-3 bg-amber-50 border border-amber-100 rounded-xl text-sm font-semibold text-slate-700 outline-none focus:ring-2 focus:ring-gold-500/20 focus:border-gold-500 transition-all"
                                           required>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                                <div>
                                    <label class="block text-[10px] font-black text-navy-900 uppercase tracking-widest mb-2">
                                        <i class="fas fa-water text-navy-500 mr-1"></i> Ketersediaan Drainase
                                    </label>
                                    <select name="has_drainase"
                                            class="w-full px-4 py-3 bg-amber-50 border border-amber-100 rounded-xl text-sm font-semibold text-slate-700 outline-none focus:ring-2 focus:ring-gold-500/20 focus:border-gold-500 transition-all">
                                        <option value="ya">Ada Drainase</option>
                                        <option value="tidak" selected>Tidak Ada Drainase</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-navy-900 uppercase tracking-widest mb-2">
                                        <i class="fas fa-circle-notch text-navy-500 mr-1"></i> Ketersediaan Gorong-gorong
                                    </label>
                                    <select name="has_gorong_gorong"
                                            class="w-full px-4 py-3 bg-amber-50 border border-amber-100 rounded-xl text-sm font-semibold text-slate-700 outline-none focus:ring-2 focus:ring-gold-500/20 focus:border-gold-500 transition-all">
                                        <option value="ya">Ada Gorong-gorong</option>
                                        <option value="tidak" selected>Tidak Ada Gorong-gorong</option>
                                    </select>
                                </div>
                            </div>

                            <div>
                                <label class="block text-[10px] font-black text-navy-900 uppercase tracking-widest mb-2">
                                    Deskripsi Kerusakan <span class="text-[9px] text-slate-400 normal-case font-semibold">(Trigger Decision Tree)</span>
                                    <span class="text-red-400">*</span>
                                </label>
                                <textarea name="kondisi" id="kondisi-textarea" rows="3"
                                    class="w-full px-4 py-3 bg-amber-50 border border-amber-100 rounded-xl text-sm font-semibold text-slate-700 outline-none focus:ring-2 focus:ring-gold-500/20 focus:border-gold-500 transition-all placeholder-slate-400"
                                    placeholder="Contoh: titian putus, cor beton retak, amblas..."
                                    required>{{ old('kondisi') }}</textarea>

                                {{-- Keyword Chips --}}
                                <div class="mt-3 flex flex-wrap gap-2">
                                    @foreach(['Putus','Hancur','Amblas','Retak','Lubang','Goyang','Total','Parah'] as $keyword)
                                        <button type="button" onclick="addKeyword('{{ $keyword }}')"
                                            class="px-3 py-1 bg-white border border-slate-200 rounded-lg text-[8px] font-black text-slate-500 hover:bg-navy-900 hover:text-gold-500 hover:border-navy-900 transition-all shadow-sm">
                                            + {{ $keyword }}
                                        </button>
                                    @endforeach
                                </div>
                                <p class="text-[9px] text-slate-400 mt-2 font-semibold">
                                    <i class="fas fa-info-circle mr-1"></i> Deskripsi ini akan diproses Decision Tree untuk menentukan skor prioritas.
                                </p>
                            </div>
                        </div>

                        {{-- Section 3: Lokasi Geografis --}}
                        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-8">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-8 h-8 bg-navy-500 rounded-xl flex items-center justify-center text-white shrink-0">
                                    <i class="fas fa-map-marker-alt text-xs"></i>
                                </div>
                                <div>
                                    <h4 class="text-sm font-black text-navy-900 uppercase tracking-wider">Lokasi Geografis</h4>
                                    <p class="text-[9px] text-slate-400 font-semibold mt-0.5">Klik pada peta atau isi koordinat secara manual</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-5 mb-5">
                                <div>
                                    <label class="block text-[10px] font-black text-navy-900 uppercase tracking-widest mb-2">Latitude</label>
                                    <input type="text" name="latitude" id="lat-input"
                                           value="{{ old('latitude') }}"
                                           placeholder="-3.316694"
                                           class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-700 outline-none focus:ring-2 focus:ring-gold-500/20 focus:border-gold-500 transition-all placeholder-slate-400">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-navy-900 uppercase tracking-widest mb-2">Longitude</label>
                                    <input type="text" name="longitude" id="lng-input"
                                           value="{{ old('longitude') }}"
                                           placeholder="114.590111"
                                           class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-700 outline-none focus:ring-2 focus:ring-gold-500/20 focus:border-gold-500 transition-all placeholder-slate-400">
                                </div>
                            </div>

                            <div id="create-map" class="w-full rounded-2xl overflow-hidden" style="height: 260px;"></div>
                            <p class="text-[9px] text-slate-400 font-semibold mt-2">
                                <i class="fas fa-hand-pointer mr-1 text-gold-500"></i> Klik titik di peta untuk mengisi koordinat secara otomatis.
                            </p>
                        </div>

                    </div>{{-- /kolom kiri --}}

                    {{-- ── Kolom Kanan (1/3) ── --}}
                    <div class="space-y-6">

                        {{-- Upload Foto --}}
                        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="w-8 h-8 bg-emerald-500 rounded-xl flex items-center justify-center text-white shrink-0">
                                    <i class="fas fa-camera text-xs"></i>
                                </div>
                                <div>
                                    <h4 class="text-sm font-black text-navy-900 uppercase tracking-wider">Dokumentasi Foto</h4>
                                    <p class="text-[9px] text-slate-400 font-semibold mt-0.5">Wajib — untuk analisis CNN <span class="text-red-400">*</span></p>
                                </div>
                            </div>

                            {{-- Drop Zone --}}
                            <label id="foto-label" for="foto-input"
                                class="relative flex flex-col items-center justify-center w-full h-48 rounded-2xl border-2 border-dashed border-slate-200 bg-slate-50 hover:border-gold-500 hover:bg-gold-50/30 transition-all cursor-pointer group">
                                <div id="foto-placeholder" class="text-center">
                                    <i class="fas fa-cloud-upload-alt text-4xl text-slate-300 mb-3 block group-hover:text-gold-500 transition-colors"></i>
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Klik untuk unggah foto</p>
                                    <p class="text-[9px] text-slate-400 mt-1">JPG, PNG • Maks 5MB</p>
                                </div>
                                <img id="foto-preview" class="absolute inset-0 w-full h-full object-cover rounded-2xl">
                            </label>
                            <input type="file" id="foto-input" name="foto" accept="image/jpeg,image/png,image/jpg" required>

                            <p class="text-[9px] text-slate-400 font-semibold mt-2">
                                <i class="fas fa-robot mr-1 text-gold-500"></i> Foto digunakan oleh CNN untuk analisis kondisi.
                            </p>
                        </div>

                        {{-- Info Panel --}}
                        <div class="bg-navy-900 rounded-3xl p-6 text-white">
                            <h5 class="text-[10px] font-black text-gold-500 uppercase tracking-widest mb-4">
                                <i class="fas fa-robot mr-1"></i> Proses Setelah Simpan
                            </h5>
                            <div class="space-y-3">
                                <div class="flex items-start gap-3 py-2 border-b border-white/10">
                                    <div class="w-6 h-6 bg-navy-500/30 rounded-lg flex items-center justify-center shrink-0 mt-0.5">
                                        <span class="text-[8px] font-black text-gold-500">1</span>
                                    </div>
                                    <div>
                                        <p class="text-[9px] font-black text-white">CNN Image Analysis</p>
                                        <p class="text-[8px] text-slate-400 mt-0.5">Foto dianalisis untuk deteksi jenis & kondisi</p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-3 py-2 border-b border-white/10">
                                    <div class="w-6 h-6 bg-gold-500/20 rounded-lg flex items-center justify-center shrink-0 mt-0.5">
                                        <span class="text-[8px] font-black text-gold-500">2</span>
                                    </div>
                                    <div>
                                        <p class="text-[9px] font-black text-white">Decision Tree Scoring</p>
                                        <p class="text-[8px] text-slate-400 mt-0.5">Skor prioritas dihitung dari parameter teknis</p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-3 py-2">
                                    <div class="w-6 h-6 bg-emerald-500/20 rounded-lg flex items-center justify-center shrink-0 mt-0.5">
                                        <span class="text-[8px] font-black text-emerald-400">3</span>
                                    </div>
                                    <div>
                                        <p class="text-[9px] font-black text-white">Label Prioritas Akhir</p>
                                        <p class="text-[8px] text-slate-400 mt-0.5">Hasil Hybrid AI siap ditampilkan di dashboard</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Tombol Aksi --}}
                        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6 space-y-3">
                            <button type="submit"
                                class="w-full flex items-center justify-center gap-2 bg-navy-900 hover:bg-navy-950 text-white py-3.5 rounded-2xl font-black text-[11px] tracking-widest transition-all shadow-lg shadow-navy-900/20 uppercase">
                                <i class="fas fa-save"></i> Simpan & Jalankan AI
                            </button>
                            <a href="{{ route('admin.infrastruktur') }}"
                                class="w-full flex items-center justify-center gap-2 bg-slate-100 hover:bg-slate-200 text-slate-500 py-3.5 rounded-2xl font-black text-[11px] tracking-widest transition-all uppercase">
                                <i class="fas fa-times"></i> Batal
                            </a>
                        </div>

                    </div>{{-- /kolom kanan --}}

                </div>
            </form>
        </div>
    </main>

    <script>
        // Clock
        function updateClock() {
            const now = new Date();
            const options = { timeZone: 'Asia/Makassar', hour: '2-digit', minute: '2-digit', hour12: false };
            const timeString = new Intl.DateTimeFormat('id-ID', options).format(now);
            const el = document.getElementById('mini-clock');
            if (el) el.textContent = timeString.replace('.', ':') + ' WITA';
        }
        setInterval(updateClock, 1000); updateClock();

        // Keyword chips
        function addKeyword(word) {
            const ta = document.getElementById('kondisi-textarea');
            ta.value = ta.value.trim() ? ta.value.trim() + ', ' + word : word;
            ta.focus();
        }

        // Foto preview
        document.getElementById('foto-input').addEventListener('change', function () {
            const file = this.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = (e) => {
                const preview = document.getElementById('foto-preview');
                const placeholder = document.getElementById('foto-placeholder');
                preview.src = e.target.result;
                preview.style.display = 'block';
                placeholder.style.display = 'none';
            };
            reader.readAsDataURL(file);
        });

        // Leaflet map
        const latInput = document.getElementById('lat-input');
        const lngInput = document.getElementById('lng-input');
        const defaultLat = -3.316694;
        const defaultLng = 114.590111;

        const map = L.map('create-map', { zoomControl: true }).setView([defaultLat, defaultLng], 14);
        L.tileLayer('https://{s}.google.com/vt/lyrs=s,h&x={x}&y={y}&z={z}', {
            maxZoom: 20, subdomains: ['mt0','mt1','mt2','mt3']
        }).addTo(map);

        let marker = null;

        // Klik peta → set marker + isi koordinat
        map.on('click', function (e) {
            const { lat, lng } = e.latlng;
            latInput.value = lat.toFixed(8);
            lngInput.value = lng.toFixed(8);

            if (marker) {
                marker.setLatLng([lat, lng]);
            } else {
                marker = L.marker([lat, lng], { draggable: true }).addTo(map);
                marker.on('dragend', function () {
                    const pos = marker.getLatLng();
                    latInput.value = pos.lat.toFixed(8);
                    lngInput.value = pos.lng.toFixed(8);
                });
            }
        });

        // Jika input koordinat diketik manual
        [latInput, lngInput].forEach(input => {
            input.addEventListener('input', () => {
                const newLat = parseFloat(latInput.value);
                const newLng = parseFloat(lngInput.value);
                if (!isNaN(newLat) && !isNaN(newLng)) {
                    if (marker) {
                        marker.setLatLng([newLat, newLng]);
                    } else {
                        marker = L.marker([newLat, newLng], { draggable: true }).addTo(map);
                    }
                    map.panTo([newLat, newLng]);
                }
            });
        });
    </script>
</body>
</html>
