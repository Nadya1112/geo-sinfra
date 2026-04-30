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
        .leaflet-bar { border: none !important; box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1) !important; border-radius: 8px !important; overflow: hidden; }
        .leaflet-bar a { width: 26px !important; height: 26px !important; line-height: 26px !important; font-size: 14px !important; }
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
    </style>
</head>
<body class="bg-gray-50 flex h-screen overflow-hidden text-gray-800 text-left">

    @include('kabid.partials.sidebar')

    <main class="flex-1 flex flex-col h-screen overflow-y-auto custom-scrollbar">
        <!-- HEADER -->
        <header class="bg-white border-b border-gray-100 px-8 py-5 flex justify-between items-center z-10 sticky top-0">
            <div class="flex items-center gap-4">
                <a href="{{ route('kabid.verifikasi') }}" class="w-10 h-10 flex items-center justify-center bg-gray-50 text-gray-400 rounded-xl hover:bg-indigo-50 hover:text-indigo-600 transition-all border border-gray-100">
                    <i class="fas fa-arrow-left text-sm"></i>
                </a>
                <div>
                    <p class="text-[10px] font-extrabold text-indigo-600 uppercase tracking-[0.2em] mb-1">Verifikasi Usulan</p>
                    <h2 class="text-xl font-black text-[#1e1b4b]">Detail Infrastruktur</h2>
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
                        <p class="text-[11px] font-black text-[#1e1b4b] leading-none uppercase">{{ auth()->user()->name }}</p>
                        <p class="text-[9px] font-bold text-indigo-500 uppercase mt-1 leading-none">KABID</p>
                    </div>
                    <div class="w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600 border border-indigo-100 overflow-hidden">
                        @if(auth()->user()->profile_photo)
                            <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}" class="w-full h-full object-cover">
                        @else
                            <i class="fas fa-user-tie text-xl"></i>
                        @endif
                    </div>
                </div>
            </div>
        </header>

        <!-- CONTENT -->
        <div class="p-8">
            <div class="max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-8">

                <!-- Kolom Kiri: Foto & AI Panel -->
                <div class="lg:col-span-1 space-y-6">
                    <!-- Foto -->
                    <div class="bg-white rounded-[2.5rem] p-4 border border-gray-100 shadow-sm overflow-hidden">
                        <div class="relative h-64 rounded-[2rem] overflow-hidden group">
                            <img src="{{ asset('storage/' . $infrastruktur->foto_terbaru) }}" class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-all flex items-end p-6">
                                <p class="text-white text-[10px] font-bold uppercase tracking-widest">Foto Dokumentasi</p>
                            </div>
                        </div>
                    </div>

                    <!-- AI Analysis Panel -->
                    <div class="bg-[#1e1b4b] rounded-[2.5rem] p-8 text-white shadow-xl">
                        <h4 class="text-xs font-black text-blue-300 uppercase tracking-widest mb-6 italic">Hasil Analisis AI</h4>

                        <div class="space-y-5">
                            <div class="flex items-center justify-between">
                                <p class="text-[10px] font-bold text-blue-200/60 uppercase">Kondisi</p>
                                <span class="px-3 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest
                                    {{ $infrastruktur->kondisi == 'Baik' ? 'bg-emerald-500/20 text-emerald-400' :
                                       ($infrastruktur->kondisi == 'Rusak Ringan' ? 'bg-yellow-500/20 text-yellow-400' : 'bg-red-500/20 text-red-400') }}">
                                    {{ $infrastruktur->kondisi }}
                                </span>
                            </div>

                            <div class="flex items-center justify-between">
                                <p class="text-[10px] font-bold text-blue-200/60 uppercase">Skor CNN</p>
                                <p class="text-lg font-black text-white">
                                    {{ $infrastruktur->cnn ? number_format($infrastruktur->cnn->skor_cnn * 100, 1) : '-' }}%
                                </p>
                            </div>

                            <div class="flex items-center justify-between">
                                <p class="text-[10px] font-bold text-blue-200/60 uppercase">Prioritas DT</p>
                                <p class="text-sm font-black text-blue-300">
                                    {{ $infrastruktur->analisis ? $infrastruktur->analisis->label_prioritas : '-' }}
                                </p>
                            </div>

                            <div class="pt-5 border-t border-white/10 flex items-center justify-between">
                                <p class="text-[10px] font-bold text-blue-200/60 uppercase">Status Verifikasi</p>
                                @php
                                    $vColor = match($infrastruktur->status_verifikasi) {
                                        'Verified' => 'bg-emerald-400',
                                        'Rejected' => 'bg-red-400',
                                        default => 'bg-amber-400'
                                    };
                                    $vLabel = match($infrastruktur->status_verifikasi) {
                                        'Verified' => 'Diterima',
                                        'Rejected' => 'Ditolak',
                                        default => 'Menunggu'
                                    };
                                @endphp
                                <div class="flex items-center gap-2">
                                    <div class="w-2 h-2 rounded-full {{ $vColor }}"></div>
                                    <p class="text-[9px] font-black uppercase">{{ $vLabel }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Aksi Verifikasi -->
                    @if($infrastruktur->status_verifikasi == 'Pending')
                    <div class="bg-white rounded-[2.5rem] p-6 border border-gray-100 shadow-sm">
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4">Tindakan Verifikasi</p>
                        <div class="flex flex-col gap-3">
                            <form action="{{ route('kabid.verifikasi.proses', $infrastruktur->id_infrastruktur) }}" method="POST">
                                @csrf
                                <input type="hidden" name="status" value="Verified">
                                <button type="submit" class="w-full flex items-center justify-center gap-2 py-3 bg-emerald-500 text-white rounded-2xl hover:bg-emerald-600 transition-all shadow-lg shadow-emerald-500/20 font-black text-[11px] uppercase tracking-widest">
                                    <i class="fas fa-check"></i> Terima Usulan
                                </button>
                            </form>
                            <form action="{{ route('kabid.verifikasi.proses', $infrastruktur->id_infrastruktur) }}" method="POST">
                                @csrf
                                <input type="hidden" name="status" value="Rejected">
                                <button type="submit" class="w-full flex items-center justify-center gap-2 py-3 bg-white border border-red-200 text-red-400 rounded-2xl hover:bg-red-50 hover:border-red-300 transition-all font-black text-[11px] uppercase tracking-widest">
                                    <i class="fas fa-times"></i> Tolak Usulan
                                </button>
                            </form>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Kolom Kanan: Info & Peta -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white rounded-[2.5rem] p-8 border border-gray-100 shadow-sm">
                        <div class="flex justify-between items-start mb-8">
                            <div>
                                <p class="text-[10px] font-black text-indigo-600 uppercase tracking-widest mb-1">{{ $infrastruktur->jenis_infrastruktur }}</p>
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
                                    <div class="w-10 h-10 rounded-2xl bg-indigo-50 flex items-center justify-center text-indigo-500 border border-indigo-100 flex-shrink-0">
                                        <i class="fas fa-map-marked-alt text-sm"></i>
                                    </div>
                                    <div>
                                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Kecamatan</p>
                                        <p class="text-sm font-bold text-[#1e1b4b]">{{ $infrastruktur->kelurahan->kecamatan->nama_kecamatan ?? '-' }}</p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-4">
                                    <div class="w-10 h-10 rounded-2xl bg-indigo-50 flex items-center justify-center text-indigo-500 border border-indigo-100 flex-shrink-0">
                                        <i class="fas fa-building text-sm"></i>
                                    </div>
                                    <div>
                                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Kelurahan</p>
                                        <p class="text-sm font-bold text-[#1e1b4b]">{{ $infrastruktur->kelurahan->nama_kelurahan ?? '-' }}</p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-4">
                                    <div class="w-10 h-10 rounded-2xl bg-indigo-50 flex items-center justify-center text-indigo-500 border border-indigo-100 flex-shrink-0">
                                        <i class="fas fa-user text-sm"></i>
                                    </div>
                                    <div>
                                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Surveyor</p>
                                        <p class="text-sm font-bold text-[#1e1b4b]">{{ $infrastruktur->user->name ?? '-' }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="space-y-4">
                                <div class="flex items-start gap-4">
                                    <div class="w-10 h-10 rounded-2xl bg-indigo-50 flex items-center justify-center text-indigo-500 border border-indigo-100 flex-shrink-0">
                                        <i class="fas fa-location-arrow text-sm"></i>
                                    </div>
                                    <div>
                                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Koordinat</p>
                                        <p class="text-xs font-bold text-[#1e1b4b]">{{ $infrastruktur->latitude }}, {{ $infrastruktur->longitude }}</p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-4">
                                    <div class="w-10 h-10 rounded-2xl bg-indigo-50 flex items-center justify-center text-indigo-500 border border-indigo-100 flex-shrink-0">
                                        <i class="fas fa-map-pin text-sm"></i>
                                    </div>
                                    <div>
                                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Alamat / Catatan</p>
                                        <p class="text-xs font-medium text-gray-600 italic">"{{ $infrastruktur->alamat ?? 'Tidak ada catatan alamat.' }}"</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Mini Map -->
                        <div class="relative rounded-[2rem] border border-gray-100 shadow-inner overflow-hidden">
                            <div id="map" class="h-[280px] w-full z-0"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        function updateClock() {
            const now = new Date();
            document.getElementById('mini-clock').textContent =
                `${String(now.getHours()).padStart(2,'0')}:${String(now.getMinutes()).padStart(2,'0')} WITA`;
        }
        setInterval(updateClock, 1000); updateClock();

        const lat = {{ $infrastruktur->latitude }};
        const lng = {{ $infrastruktur->longitude }};
        const map = L.map('map', { zoomControl: true, scrollWheelZoom: false }).setView([lat, lng], 16);
        L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png').addTo(map);

        const color = "{{ $infrastruktur->kondisi == 'Baik' ? '#10b981' : ($infrastruktur->kondisi == 'Rusak Ringan' ? '#f59e0b' : '#ef4444') }}";
        const markerHtml = `<div style="background-color:${color};width:18px;height:18px;border-radius:50%;border:4px solid white;box-shadow:0 0 15px rgba(0,0,0,0.25);"></div>`;
        const icon = L.divIcon({ html: markerHtml, className: '', iconSize: [18,18], iconAnchor: [9,9] });
        L.marker([lat, lng], { icon }).addTo(map);
    </script>
</body>
</html>
