<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Infrastruktur | Admin SINFRA</title>
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

        /* Input focus ring */
        .field-input {
            @apply w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-700 outline-none
                   focus:ring-2 focus:ring-gold-500/20 focus:border-gold-500 transition-all placeholder-slate-400;
        }
        .field-select {
            @apply w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-700 outline-none
                   focus:ring-2 focus:ring-gold-500/20 focus:border-gold-500 transition-all;
        }

        /* Map border cleanup */
        #edit-map { border: none !important; outline: none !important; box-shadow: none !important; }

        /* Custom Leaflet Zoom Controls — sesuai tema navy/gold */
        .leaflet-control-zoom {
            border: none !important;
            box-shadow: 0 4px 24px rgba(7,6,23,0.15) !important;
            border-radius: 0.75rem !important;
            overflow: hidden;
        }
        .leaflet-control-zoom a {
            width: 36px !important;
            height: 36px !important;
            line-height: 36px !important;
            background: #0f0e2c !important;
            color: #c5a059 !important;
            border: none !important;
            border-bottom: 1px solid rgba(255,255,255,0.08) !important;
            font-size: 16px !important;
            font-weight: 900 !important;
            transition: background 0.2s, color 0.2s !important;
        }
        .leaflet-control-zoom a:hover {
            background: #1e1b4b !important;
            color: #ffffff !important;
        }
        .leaflet-control-zoom-out {
            border-bottom: none !important;
        }
    </style>
<style>
    @media (min-width: 768px) { html { font-size: 14px; } }
    @media (max-width: 767px) { html { font-size: 12px; } }
</style>
</head>
<body class="bg-navy-50 dark:bg-navy-950 text-slate-800 dark:text-slate-200 antialiased flex overflow-hidden h-screen transition-colors duration-300">

    @include('admin.partials.sidebar')

    <main class="flex-1 flex flex-col h-screen overflow-hidden">

        {{-- ── Header ── --}}
        <header class="bg-white/80 backdrop-blur-xl border-b border-slate-100 px-4 pl-16 md:px-8 py-4 flex justify-between items-center z-40 shrink-0">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.infrastruktur') }}"
                   class="w-10 h-10 bg-white border border-slate-100 rounded-xl flex items-center justify-center text-slate-400 hover:text-gold-500 hover:border-gold-500/30 hover:shadow-md transition-all group"
                   title="Kembali ke Manajemen Infrastruktur">
                    <i class="fas fa-arrow-left text-xs group-hover:-translate-x-1 transition-transform"></i>
                </a>
                <div>
                    <p class="text-xs font-black text-gold-500 uppercase tracking-wider mb-1">Administrator Portal</p>
                    <h2 class="text-xl font-black text-navy-900 dark:text-white leading-none">Edit Data Infrastruktur</h2>
                </div>
            </div>

            <div class="flex items-center gap-3 md:gap-6">
                <div class="text-right">
                    <p class="text-sm font-black text-navy-900 dark:text-white" id="mini-clock">00:00 WITA</p>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-tighter">{{ now()->translatedFormat('d M Y') }}</p>
                </div>
                <div class="h-8 w-[1px] bg-slate-100"></div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.profile') }}" class="text-right group hidden md:block">
                        <p class="text-sm font-black text-navy-900 dark:text-white leading-none uppercase group-hover:text-gold-500 transition-all">{{ auth()->user()->name }}</p>
                        <p class="text-xs font-bold text-emerald-500 uppercase mt-1">Online</p>
                    </a>
                    <a href="{{ route('admin.profile') }}" class="w-10 h-10 bg-navy-900 rounded-xl flex items-center justify-center text-gold-500 border border-white/10 overflow-hidden hover:shadow-lg hover:shadow-navy-950/20 transition-all shadow-md">
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

            {{-- ID Badge --}}
            <div class="flex items-center gap-3 mb-6">
                <span class="px-3 py-1.5 bg-navy-900 text-gold-500 rounded-xl text-xs font-black tracking-widest uppercase">
                    <i class="fas fa-edit mr-1"></i> Mode Edit
                </span>
                <span class="px-3 py-1.5 bg-gold-500/10 text-gold-600 border border-gold-500/20 rounded-xl text-xs font-black tracking-widest uppercase">
                    ID: INF-{{ $inf->id_infrastruktur }}
                </span>
                <span class="text-xs text-slate-400 font-semibold">{{ $inf->nama_objek ?? $inf->nama_infrastruktur }}</span>
            </div>

            <form action="{{ route('admin.infrastruktur.update', $inf->id_infrastruktur) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

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
                                    <p class="text-xs text-slate-400 font-semibold mt-0.5">Nama, jenis, dan lokasi wilayah infrastruktur</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div class="md:col-span-2">
                                    <label class="block text-xs font-black text-navy-900 uppercase tracking-widest mb-2">Nama Infrastruktur <span class="text-red-400">*</span></label>
                                    <input type="text" name="nama_infrastruktur"
                                           value="{{ $inf->nama_objek ?? $inf->nama_infrastruktur }}"
                                           placeholder="Nama infrastruktur..."
                                           class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-700 outline-none focus:ring-2 focus:ring-gold-500/20 focus:border-gold-500 transition-all placeholder-slate-400"
                                           required>
                                </div>
                                <div>
                                    <label class="block text-xs font-black text-navy-900 uppercase tracking-widest mb-2">Jenis Infrastruktur</label>
                                    <input type="hidden" name="jenis" value="{{ $inf->jenis }}">
                                    <div class="w-full px-4 py-3 bg-slate-100 border border-slate-200 rounded-xl flex items-center justify-between cursor-not-allowed">
                                        <div class="flex items-center gap-2">
                                            <span class="px-2 py-0.5 bg-navy-900 text-gold-500 rounded-md text-[7px] font-black tracking-wider uppercase">AI</span>
                                            <span class="text-sm font-black text-navy-900 uppercase">{{ ucfirst($inf->jenis) ?? '—' }}</span>
                                        </div>
                                        <i class="fas fa-lock text-slate-400 text-xs"></i>
                                    </div>
                                    <p class="text-xs text-slate-400 font-semibold mt-1.5">
                                        <i class="fas fa-robot mr-1 text-gold-500"></i> Jenis tidak dapat diubah pada mode edit.
                                    </p>
                                </div>
                                <div>
                                    <label class="block text-xs font-black text-navy-900 uppercase tracking-widest mb-2">Kecamatan</label>
                                    <select name="id_kecamatan"
                                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-700 outline-none focus:ring-2 focus:ring-gold-500/20 focus:border-gold-500 transition-all">
                                        @foreach($semuaKecamatan as $kec)
                                            <option value="{{ $kec->id_kecamatan }}" {{ $inf->id_kecamatan == $kec->id_kecamatan ? 'selected' : '' }}>
                                                {{ $kec->nama_kecamatan }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-xs font-black text-navy-900 uppercase tracking-widest mb-2">Kelurahan</label>
                                    <select name="id_kelurahan"
                                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-700 outline-none focus:ring-2 focus:ring-gold-500/20 focus:border-gold-500 transition-all">
                                        @foreach($semuaKelurahan as $kel)
                                            <option value="{{ $kel->id_kelurahan }}" {{ $inf->id_kelurahan == $kel->id_kelurahan ? 'selected' : '' }}>
                                                {{ $kel->nama_kelurahan }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        {{-- Section 2: Detail Teknis & AI Parameter --}}
                        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-8">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-8 h-8 bg-gold-500 rounded-xl flex items-center justify-center text-white shrink-0">
                                    <i class="fas fa-brain text-xs"></i>
                                </div>
                                <div>
                                    <h4 class="text-sm font-black text-navy-900 uppercase tracking-wider">Detail Teknis & Parameter AI</h4>
                                    <p class="text-xs text-slate-400 font-semibold mt-0.5">Data ini digunakan sebagai input model Decision Tree</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-5">
                                <div>
                                    <label class="block text-xs font-black text-navy-900 uppercase tracking-widest mb-2">Material Utama <span class="text-red-400">*</span></label>
                                    <select name="material_eksisting"
                                            class="w-full px-4 py-3 bg-amber-50 border border-amber-100 rounded-xl text-sm font-semibold text-slate-700 outline-none focus:ring-2 focus:ring-gold-500/20 focus:border-gold-500 transition-all"
                                            required>
                                        <option value="" disabled>-- Pilih Material --</option>
                                        <option value="Cor Beton" {{ $inf->material_eksisting == 'Cor Beton' ? 'selected' : '' }}>Cor Beton</option>
                                        <option value="Titian (Kayu Ulin)" {{ $inf->material_eksisting == 'Titian (Kayu Ulin)' ? 'selected' : '' }}>Titian (Kayu Ulin)</option>
                                        <option value="Tanah Asli" {{ $inf->material_eksisting == 'Tanah Asli' ? 'selected' : '' }}>Tanah Asli</option>
                                        <option value="Tanah Pemadatan" {{ $inf->material_eksisting == 'Tanah Pemadatan' ? 'selected' : '' }}>Tanah Pemadatan</option>
                                        <option value="Tanah Lepas" {{ $inf->material_eksisting == 'Tanah Lepas' ? 'selected' : '' }}>Tanah Lepas</option>
                                        <option value="Paving Block" {{ $inf->material_eksisting == 'Paving Block' ? 'selected' : '' }}>Paving Block</option>
                                        <option value="Aspal" {{ $inf->material_eksisting == 'Aspal' ? 'selected' : '' }}>Aspal</option>
                                        <option value="Bata Press" {{ $inf->material_eksisting == 'Bata Press' ? 'selected' : '' }}>Bata Press</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-black text-navy-900 uppercase tracking-widest mb-2">Panjang (m) <span class="text-red-400">*</span></label>
                                    <input type="number" step="0.01" name="panjang"
                                           value="{{ $inf->panjang }}"
                                           placeholder="0.00"
                                           class="w-full px-4 py-3 bg-amber-50 border border-amber-100 rounded-xl text-sm font-semibold text-slate-700 outline-none focus:ring-2 focus:ring-gold-500/20 focus:border-gold-500 transition-all"
                                           required>
                                </div>
                                <div>
                                    <label class="block text-xs font-black text-navy-900 uppercase tracking-widest mb-2">Lebar (m) <span class="text-red-400">*</span></label>
                                    <input type="number" step="0.01" name="lebar"
                                           value="{{ $inf->lebar }}"
                                           placeholder="0.00"
                                           class="w-full px-4 py-3 bg-amber-50 border border-amber-100 rounded-xl text-sm font-semibold text-slate-700 outline-none focus:ring-2 focus:ring-gold-500/20 focus:border-gold-500 transition-all"
                                           required>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                                <div>
                                    <label class="block text-xs font-black text-navy-900 uppercase tracking-widest mb-2">
                                        <i class="fas fa-water text-navy-500 mr-1"></i> Ketersediaan Drainase
                                    </label>
                                    <select name="has_drainase"
                                            class="w-full px-4 py-3 bg-amber-50 border border-amber-100 rounded-xl text-sm font-semibold text-slate-700 outline-none focus:ring-2 focus:ring-gold-500/20 focus:border-gold-500 transition-all">
                                        <option value="ya"    {{ $inf->has_drainase == 'ya'    ? 'selected' : '' }}>Ada Drainase</option>
                                        <option value="tidak" {{ $inf->has_drainase == 'tidak' ? 'selected' : '' }}>Tidak Ada Drainase</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-black text-navy-900 uppercase tracking-widest mb-2">
                                        <i class="fas fa-circle-notch text-navy-500 mr-1"></i> Ketersediaan Gorong-gorong
                                    </label>
                                    <select name="has_gorong_gorong"
                                            class="w-full px-4 py-3 bg-amber-50 border border-amber-100 rounded-xl text-sm font-semibold text-slate-700 outline-none focus:ring-2 focus:ring-gold-500/20 focus:border-gold-500 transition-all">
                                        <option value="ya"    {{ $inf->has_gorong_gorong == 'ya'    ? 'selected' : '' }}>Ada Gorong-gorong</option>
                                        <option value="tidak" {{ $inf->has_gorong_gorong == 'tidak' ? 'selected' : '' }}>Tidak Ada Gorong-gorong</option>
                                    </select>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-black text-navy-900 uppercase tracking-widest mb-2">
                                    Deskripsi Kerusakan <span class="text-xs text-slate-400 normal-case font-semibold">(Trigger Decision Tree)</span>
                                    <span class="text-red-400">*</span>
                                </label>
                                <textarea name="kondisi" id="kondisi-textarea" rows="3"
                                    class="w-full px-4 py-3 bg-amber-50 border border-amber-100 rounded-xl text-sm font-semibold text-slate-700 outline-none focus:ring-2 focus:ring-gold-500/20 focus:border-gold-500 transition-all placeholder-slate-400"
                                    placeholder="Contoh: titian putus, cor beton retak, amblas..."
                                    required>{{ $inf->kondisi }}</textarea>

                                {{-- Keyword Chips --}}
                                <div class="mt-3 flex flex-wrap gap-2">
                                    @foreach(['Putus','Hancur','Amblas','Retak','Lubang','Goyang','Total','Parah'] as $keyword)
                                        <button type="button" onclick="addKeyword('{{ $keyword }}')"
                                            class="px-3 py-1 bg-white border border-slate-200 rounded-lg text-xs font-black text-slate-500 hover:bg-navy-900 hover:text-gold-500 hover:border-navy-900 transition-all shadow-sm">
                                            + {{ $keyword }}
                                        </button>
                                    @endforeach
                                </div>
                                <p class="text-xs text-slate-400 mt-2 font-semibold">
                                    <i class="fas fa-info-circle mr-1"></i> Perubahan teks ini akan otomatis mengupdate skor AI saat disimpan.
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
                                    <p class="text-xs text-slate-400 font-semibold mt-0.5">Geser marker di peta atau isi koordinat secara manual</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-5 mb-5">
                                <div>
                                    <label class="block text-xs font-black text-navy-900 uppercase tracking-widest mb-2">Latitude</label>
                                    <input type="text" name="latitude" id="lat-input"
                                           value="{{ $inf->latitude }}"
                                           class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-700 outline-none focus:ring-2 focus:ring-gold-500/20 focus:border-gold-500 transition-all">
                                </div>
                                <div>
                                    <label class="block text-xs font-black text-navy-900 uppercase tracking-widest mb-2">Longitude</label>
                                    <input type="text" name="longitude" id="lng-input"
                                           value="{{ $inf->longitude }}"
                                           class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-700 outline-none focus:ring-2 focus:ring-gold-500/20 focus:border-gold-500 transition-all">
                                </div>
                            </div>

                            <div id="edit-map" class="w-full rounded-2xl overflow-hidden" style="height: 260px;"></div>
                            <p class="text-xs text-slate-400 font-semibold mt-2">
                                <i class="fas fa-hand-pointer mr-1 text-gold-500"></i> Klik dan geser marker untuk memperbarui koordinat.
                            </p>
                        </div>

                    </div>{{-- /kolom kiri --}}

                    {{-- ── Kolom Kanan (1/3) ── --}}
                    <div class="space-y-6">

                        {{-- Foto Preview --}}
                        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="w-8 h-8 bg-emerald-500 rounded-xl flex items-center justify-center text-white shrink-0">
                                    <i class="fas fa-camera text-xs"></i>
                                </div>
                                <div>
                                    <h4 class="text-sm font-black text-navy-900 uppercase tracking-wider">Foto Terkini</h4>
                                    <p class="text-xs text-slate-400 font-semibold mt-0.5">Read-only — ubah via surveyor</p>
                                </div>
                            </div>

                            <div class="relative rounded-2xl overflow-hidden border border-slate-100 bg-slate-50 h-52 flex items-center justify-center group">
                                @if($inf->foto_terbaru && $inf->foto_terbaru != 'default.jpg')
                                    @php $cleanPath = str_replace('\\', '/', $inf->foto_terbaru); @endphp
                                    <img src="{{ asset('storage/' . (str_contains($cleanPath, 'infrastruktur/') ? $cleanPath : 'infrastruktur/' . $cleanPath)) }}"
                                         class="absolute inset-0 w-full h-full object-cover">
                                @else
                                    <div class="text-center">
                                        <i class="fas fa-image text-5xl text-slate-200 mb-2 block"></i>
                                        <p class="text-xs font-black text-slate-400 uppercase tracking-widest">Tidak Ada Foto</p>
                                    </div>
                                @endif
                                <div class="absolute inset-0 bg-black/30 backdrop-blur-[2px] opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                    <span class="bg-white/90 px-4 py-2 rounded-xl text-xs font-black text-navy-900 uppercase tracking-widest shadow-xl">
                                        <i class="fas fa-lock mr-1 text-red-400"></i> Tidak Dapat Diedit
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- Info Panel --}}
                        <div class="bg-navy-900 rounded-3xl p-6 text-white">
                            <h5 class="text-xs font-black text-gold-500 uppercase tracking-widest mb-4">
                                <i class="fas fa-robot mr-1"></i> Informasi AI
                            </h5>
                            @php
                                $dtData  = \Illuminate\Support\Facades\DB::table('analisis_ai')->where('id_infrastruktur', $inf->id_infrastruktur)->first();
                                $cnnData = \Illuminate\Support\Facades\DB::table('citra_cnn')->where('id_infrastruktur', $inf->id_infrastruktur)->first();
                            @endphp
                            <div class="space-y-3">
                                <div class="flex justify-between items-center py-2 border-b border-white/10">
                                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">CNN Score</span>
                                    <span class="text-xs font-black text-gold-500">{{ $cnnData ? round($cnnData->skor_cnn * 100).'%' : '—' }}</span>
                                </div>
                                <div class="flex justify-between items-center py-2 border-b border-white/10">
                                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">CNN Label</span>
                                    <span class="text-xs font-black text-white">{{ $cnnData->label_kondisi ?? 'Belum Dianalisis' }}</span>
                                </div>
                                <div class="flex justify-between items-center py-2 border-b border-white/10">
                                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">DT Score</span>
                                    <span class="text-xs font-black text-gold-500">{{ $dtData->skor_dt ?? '0' }}/100</span>
                                </div>
                                <div class="flex justify-between items-center py-2">
                                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Prioritas</span>
                                    <span class="text-xs font-black text-white">{{ $dtData->label_prioritas ?? $inf->kondisi }}</span>
                                </div>
                            </div>
                        </div>

                        {{-- Tombol Aksi --}}
                        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6 space-y-3">
                            <button type="submit"
                                class="w-full flex items-center justify-center gap-2 bg-navy-900 hover:bg-navy-950 text-white py-3.5 rounded-2xl font-black text-sm tracking-widest transition-all shadow-lg shadow-navy-900/20 uppercase">
                                <i class="fas fa-save"></i> Update & Jalankan AI
                            </button>
                            <a href="{{ route('admin.infrastruktur') }}"
                                class="w-full flex items-center justify-center gap-2 bg-slate-100 hover:bg-slate-200 text-slate-500 py-3.5 rounded-2xl font-black text-sm tracking-widest transition-all uppercase">
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

        // Leaflet map
        const latInput = document.getElementById('lat-input');
        const lngInput = document.getElementById('lng-input');
        const lat = parseFloat(latInput.value) || -3.316694;
        const lng = parseFloat(lngInput.value) || 114.590111;

        const map = L.map('edit-map', { zoomControl: true }).setView([lat, lng], 15);
        L.tileLayer('https://{s}.google.com/vt/lyrs=s,h&x={x}&y={y}&z={z}', {
            maxZoom: 20, subdomains: ['mt0','mt1','mt2','mt3']
        }).addTo(map);

        const marker = L.marker([lat, lng], { draggable: true }).addTo(map);

        marker.on('dragend', function () {
            const pos = marker.getLatLng();
            latInput.value = pos.lat.toFixed(8);
            lngInput.value = pos.lng.toFixed(8);
        });

        [latInput, lngInput].forEach(input => {
            input.addEventListener('input', () => {
                const newLat = parseFloat(latInput.value);
                const newLng = parseFloat(lngInput.value);
                if (!isNaN(newLat) && !isNaN(newLng)) {
                    marker.setLatLng([newLat, newLng]);
                    map.panTo([newLat, newLng]);
                }
            });
        });
    </script>
</body>
</html>

