<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Laporan Warga | Admin SINFRA</title>
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
                        navy: { 50:  '#f4f4fa', 100: '#e9e9f3', 500: '#6366f1', 800: '#1e1b4b', 900: '#0f0e2c', 950: '#070617' },
                        gold: { 50:  '#fdfbf7', 100: '#fbf7ed', 500: '#c5a059', 600: '#b38f4a', 700: '#9d7c3d' }
                    }
                }
            }
        }
    </script>

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        #create-map { border: none !important; outline: none !important; box-shadow: none !important; }
        .leaflet-control-zoom { border: none !important; box-shadow: 0 4px 24px rgba(7,6,23,0.15) !important; border-radius: 0.75rem !important; overflow: hidden; }
        .leaflet-control-zoom a { width: 36px !important; height: 36px !important; line-height: 36px !important; background: #0f0e2c !important; color: #c5a059 !important; border: none !important; border-bottom: 1px solid rgba(255,255,255,0.08) !important; font-size: 16px !important; font-weight: 900 !important; transition: background 0.2s !important; }
        .leaflet-control-zoom a:hover { background: #1e1b4b !important; color: #fff !important; }
        .leaflet-control-zoom-out { border-bottom: none !important; }
    </style>
<style>
    @media (min-width: 768px) { html { zoom: 0.9 !important; } }
    @media (max-width: 767px) { html { zoom: 0.5 !important; } }
</style>
</head>
<body class="bg-navy-50 dark:bg-navy-950 text-slate-800 dark:text-slate-200 antialiased flex overflow-hidden h-screen transition-colors duration-300">

    @include('admin.partials.sidebar')

    <main class="flex-1 flex flex-col h-screen overflow-hidden">
        <header class="bg-white/80 backdrop-blur-xl border-b border-slate-100 px-4 pl-16 md:px-8 py-4 md:py-5 flex flex-col md:flex-row gap-4 md:gap-0 md:justify-between items-start md:items-center z-40 shrink-0">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.laporan-warga') }}"
                   class="w-10 h-10 bg-white border border-slate-100 rounded-xl flex items-center justify-center text-slate-400 hover:text-gold-500 hover:border-gold-500/30 hover:shadow-md transition-all group"
                   title="Kembali ke Laporan Warga">
                    <i class="fas fa-arrow-left text-xs group-hover:-translate-x-1 transition-transform"></i>
                </a>
                <div>
                    <p class="text-xs font-black text-gold-500 uppercase tracking-wider mb-1">Administrator Portal</p>
                    <h2 class="text-xl font-black text-navy-900 dark:text-white leading-none">Detail Laporan Warga</h2>
                </div>
            </div>
            <div class="flex items-center gap-6">
                <div class="text-right hidden sm:block">
                    <p class="text-sm font-black text-navy-900 dark:text-white" id="mini-clock">00:00 WITA</p>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-tighter">{{ now()->translatedFormat('l, d F Y') }}</p>
                </div>
                <div class="h-8 w-[1px] bg-slate-100"></div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.profile') }}" class="text-right group">
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

        <div class="flex-1 overflow-y-auto custom-scrollbar p-8 pb-16">
            <div class="flex items-center gap-3 mb-6">
                <span class="px-3 py-1.5 bg-emerald-500 text-white rounded-xl text-xs font-black tracking-widest uppercase shadow-sm shadow-emerald-500/20">
                    <i class="fas fa-check-double mr-1"></i> Verifikasi Laporan
                </span>
                <span class="text-xs text-slate-400 font-semibold">Lengkapi data teknis yang kurang untuk memverifikasi laporan warga menjadi aset infrastruktur.</span>
            </div>

            @if($errors->any())
            <div class="mb-6 p-5 bg-red-50 border border-red-200 rounded-2xl flex items-center gap-4">
                <div class="w-10 h-10 bg-red-100 rounded-xl flex items-center justify-center text-red-500 shrink-0">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div>
                    <h4 class="text-sm font-black text-red-800 uppercase mb-1">Validasi Gagal!</h4>
                    <ul class="text-sm text-red-600 font-semibold space-y-0.5">
                        @foreach($errors->all() as $error)
                            <li>• {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif

            <form action="{{ route('admin.laporan-warga.convert.store', $laporan->id) }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
                    <div class="xl:col-span-2 space-y-6">
                        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-8">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-8 h-8 bg-navy-900 rounded-xl flex items-center justify-center text-gold-500 shrink-0">
                                    <i class="fas fa-info-circle text-xs"></i>
                                </div>
                                <div>
                                    <h4 class="text-sm font-black text-navy-900 uppercase tracking-wider">Identitas & Wilayah</h4>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div class="md:col-span-2">
                                    <label class="block text-xs font-black text-navy-900 uppercase tracking-widest mb-2">Nama Infrastruktur <span class="text-red-400">*</span></label>
                                    <input type="text" name="nama_infrastruktur"
                                           value="{{ old('nama_infrastruktur') }}" placeholder="Contoh: Titian Jl. Kelayan A, Gang Mutiara..."
                                           class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-700 outline-none focus:ring-2 focus:ring-gold-500/20 focus:border-gold-500 transition-all"
                                           required>
                                </div>
                                <div>
                                    <label class="block text-xs font-black text-navy-900 uppercase tracking-widest mb-2">Jenis Infrastruktur <span class="text-red-400">*</span></label>
                                    <select name="jenis" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-700 outline-none focus:ring-2 focus:ring-gold-500/20 focus:border-gold-500 transition-all" required>
                                        @php $jenisTerpilih = old('jenis') ?? $laporan->jenis_ai; @endphp
                                        <option value="" disabled {{ empty($jenisTerpilih) ? 'selected' : '' }}>-- Pilih Jenis --</option>
                                        <option value="jalan" {{ $jenisTerpilih == 'jalan' ? 'selected' : '' }}>Jalan</option>
                                        <option value="titian" {{ $jenisTerpilih == 'titian' ? 'selected' : '' }}>Titian</option>
                                        <option value="jembatan" {{ $jenisTerpilih == 'jembatan' ? 'selected' : '' }}>Jembatan</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-black text-navy-900 uppercase tracking-widest mb-2">Kecamatan</label>
                                    <select name="id_kecamatan" id="select-kecamatan" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-700 outline-none focus:ring-2 focus:ring-gold-500/20 focus:border-gold-500 transition-all">
                                        @foreach($semuaKecamatan as $kec)
                                            <option value="{{ $kec->id_kecamatan }}">{{ $kec->nama_kecamatan }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-xs font-black text-navy-900 uppercase tracking-widest mb-2">Kelurahan</label>
                                    <select name="id_kelurahan" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-700 outline-none focus:ring-2 focus:ring-gold-500/20 focus:border-gold-500 transition-all" required>
                                        @foreach($semuaKelurahan as $kel)
                                            <option value="{{ $kel->id_kelurahan }}">{{ $kel->nama_kelurahan }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-8">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-8 h-8 bg-gold-500 rounded-xl flex items-center justify-center text-white shrink-0">
                                    <i class="fas fa-tools text-xs"></i>
                                </div>
                                <div>
                                    <h4 class="text-sm font-black text-navy-900 uppercase tracking-wider">Detail Teknis</h4>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-5">
                                <div>
                                    <label class="block text-xs font-black text-navy-900 uppercase tracking-widest mb-2">Material <span class="text-red-400">*</span></label>
                                    <select name="material_eksisting" class="w-full px-4 py-3 bg-amber-50 border border-amber-100 rounded-xl text-sm font-semibold text-slate-700 outline-none focus:ring-2 focus:ring-gold-500/20 focus:border-gold-500 transition-all" required>
                                        <option value="" disabled selected>-- Pilih Material --</option>
                                        <option value="Cor Beton">Cor Beton</option>
                                        <option value="Titian (Kayu Ulin)">Titian (Kayu Ulin)</option>
                                        <option value="Tanah Asli">Tanah Asli</option>
                                        <option value="Paving Block">Paving Block</option>
                                        <option value="Aspal">Aspal</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-black text-navy-900 uppercase tracking-widest mb-2">Panjang (m) <span class="text-red-400">*</span></label>
                                    <input type="number" step="0.01" name="panjang" class="w-full px-4 py-3 bg-amber-50 border border-amber-100 rounded-xl text-sm font-semibold text-slate-700 outline-none focus:ring-2 focus:ring-gold-500/20 focus:border-gold-500 transition-all" required>
                                </div>
                                <div>
                                    <label class="block text-xs font-black text-navy-900 uppercase tracking-widest mb-2">Lebar (m) <span class="text-red-400">*</span></label>
                                    <input type="number" step="0.01" name="lebar" class="w-full px-4 py-3 bg-amber-50 border border-amber-100 rounded-xl text-sm font-semibold text-slate-700 outline-none focus:ring-2 focus:ring-gold-500/20 focus:border-gold-500 transition-all" required>
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-black text-navy-900 uppercase tracking-widest mb-2">
                                    Deskripsi Kerusakan <span class="text-red-400">*</span>
                                </label>
                                <textarea name="kondisi" rows="3" class="w-full px-4 py-3 bg-amber-50 border border-amber-100 rounded-xl text-sm font-semibold text-slate-700 outline-none focus:ring-2 focus:ring-gold-500/20 focus:border-gold-500 transition-all" required>{{ old('kondisi') ?? $laporan->deskripsi }}</textarea>
                            </div>
                        </div>

                        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-8">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-8 h-8 bg-navy-500 rounded-xl flex items-center justify-center text-white shrink-0">
                                    <i class="fas fa-map-marker-alt text-xs"></i>
                                </div>
                                <div>
                                    <h4 class="text-sm font-black text-navy-900 uppercase tracking-wider">Lokasi Geografis</h4>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-5 mb-5">
                                <div>
                                    <label class="block text-xs font-black text-navy-900 uppercase tracking-widest mb-2">Latitude</label>
                                    <input type="text" name="latitude" id="lat-input" value="{{ $laporan->latitude }}" readonly class="w-full px-4 py-3 bg-slate-100 border border-slate-200 rounded-xl text-sm font-semibold text-slate-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-black text-navy-900 uppercase tracking-widest mb-2">Longitude</label>
                                    <input type="text" name="longitude" id="lng-input" value="{{ $laporan->longitude }}" readonly class="w-full px-4 py-3 bg-slate-100 border border-slate-200 rounded-xl text-sm font-semibold text-slate-500">
                                </div>
                            </div>
                            <div id="create-map" class="w-full rounded-2xl overflow-hidden" style="height: 260px;"></div>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="w-8 h-8 bg-emerald-500 rounded-xl flex items-center justify-center text-white shrink-0">
                                    <i class="fas fa-camera text-xs"></i>
                                </div>
                                <div>
                                    <h4 class="text-sm font-black text-navy-900 uppercase tracking-wider">Foto Laporan Asli</h4>
                                </div>
                            </div>
                            <div class="relative w-full h-48 rounded-2xl border border-slate-200 bg-slate-50 overflow-hidden">
                                @if($laporan->foto)
                                    <img src="{{ asset('storage/' . $laporan->foto) }}" class="w-full h-full object-cover" alt="Foto Laporan">
                                @else
                                    <div class="flex items-center justify-center w-full h-full text-slate-400">
                                        <i class="fas fa-image text-3xl"></i>
                                    </div>
                                @endif
                            </div>
                            <p class="text-xs text-slate-400 font-semibold mt-2">
                                <i class="fas fa-info-circle mr-1 text-gold-500"></i> Foto ini akan disalin ke database Infrastruktur saat disimpan.
                            </p>
                        </div>

                        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6 space-y-3">
                            <button type="submit" class="w-full flex items-center justify-center gap-2 bg-navy-900 hover:bg-navy-950 text-white py-3.5 rounded-2xl font-black text-sm tracking-widest transition-all shadow-lg shadow-navy-900/20 uppercase">
                                <i class="fas fa-check-circle"></i> Verifikasi & Simpan Aset
                            </button>
                            <a href="{{ route('admin.laporan-warga') }}" class="w-full flex items-center justify-center gap-2 bg-slate-100 hover:bg-slate-200 text-slate-500 py-3.5 rounded-2xl font-black text-sm tracking-widest transition-all uppercase">
                                <i class="fas fa-times"></i> Batal
                            </a>
                        </div>
                    </div>
                </div>
            </form>
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

        const lat = {{ $laporan->latitude ?? -3.316694 }};
        const lng = {{ $laporan->longitude ?? 114.590111 }};

        const map = L.map('create-map', { zoomControl: true }).setView([lat, lng], 16);
        L.tileLayer('https://{s}.google.com/vt/lyrs=s,h&x={x}&y={y}&z={z}', {
            maxZoom: 20, subdomains: ['mt0','mt1','mt2','mt3']
        }).addTo(map);

        L.marker([lat, lng]).addTo(map);
    </script>
</body>
</html>

