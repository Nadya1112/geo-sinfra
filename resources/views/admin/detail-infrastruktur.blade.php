<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script>
        if (localStorage.getItem('geo-theme') === 'dark' || (!('geo-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    <title>Detail Infrastruktur | Admin SINFRA</title>
    <link rel="icon" href="{{ asset('logo_geo-sinfra.png') }}" type="image/png">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }

        #mini-map { border: none !important; outline: none !important; }
        .leaflet-control-zoom { border: none !important; box-shadow: 0 4px 24px rgba(7,6,23,0.15) !important; border-radius: 0.75rem !important; overflow: hidden; }
        .leaflet-control-zoom a { width: 32px !important; height: 32px !important; line-height: 32px !important; background: #0f0e2c !important; color: #c5a059 !important; border: none !important; border-bottom: 1px solid rgba(255,255,255,0.08) !important; font-weight: 900 !important; transition: background 0.2s !important; }
        .leaflet-control-zoom a:hover { background: #1e1b4b !important; color: #fff !important; }
        .leaflet-control-zoom-out { border-bottom: none !important; }

        @keyframes scan {
            0% { transform: translateY(-100%); }
            100% { transform: translateY(400%); }
        }
    </style>
<style>
    @media (min-width: 768px) { html { font-size: 14px; } }
    @media (max-width: 767px) { html { font-size: 12px; } }
</style>
</head>
<body class="bg-navy-50 dark:bg-navy-950 text-slate-800 dark:text-slate-200 dark:text-slate-200 antialiased flex overflow-hidden h-screen transition-colors duration-300">

    @include('admin.partials.sidebar')

    <main class="flex-1 flex flex-col h-screen overflow-hidden">
        <style>
            #mobile-menu-btn { display: none !important; }
        </style>

        {{-- ── Header ── --}}
        <header class="bg-white/80 backdrop-blur-xl border-b border-slate-100 dark:border-white/10 px-4 md:px-8 py-4 flex justify-between items-center z-40 shrink-0">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.infrastruktur') }}"
                   class="hidden md:flex w-10 h-10 bg-white dark:bg-navy-900/90 dark:backdrop-blur-xl border border-slate-100 dark:border-white/10 rounded-xl flex items-center justify-center text-slate-400 dark:text-slate-300 hover:text-gold-500 hover:border-gold-500/30 hover:shadow-md transition-all group">
                    <i class="fas fa-arrow-left text-xs group-hover:-translate-x-1 transition-transform"></i>
                </a>
                <div>
                    <p class="text-xs font-black text-gold-500 uppercase tracking-wider mb-1">Portal Administrator</p>
                    <h2 class="text-xl font-black text-navy-900 dark:text-white dark:text-white leading-none">Detail {{ ucfirst($inf->jenis) ?? 'Infrastruktur' }}</h2>
                </div>
            </div>

            <div class="flex items-center gap-4">
                {{-- SLOT 1: Tombol Verifikasi / Badge Terverifikasi --}}
                @if(($inf->status_verifikasi ?? 'Pending') != 'Verified')
                    <form action="{{ route('admin.infrastruktur.verifikasi', $inf->id_infrastruktur) }}" method="POST">
                        @csrf
                        <button type="submit" onclick="return confirm('Verifikasi aset ini?')"
                            class="bg-emerald-500 hover:bg-emerald-600 text-white text-sm px-5 py-2.5 rounded-xl font-black shadow-md shadow-emerald-500/20 hover:shadow-emerald-500/30 transition flex items-center gap-2">
                            <i class="fas fa-check-double"></i> Verifikasi
                        </button>
                    </form>
                @else
                    <span class="flex items-center gap-2 bg-emerald-500 text-white px-4 py-2.5 rounded-xl text-xs font-black shadow-sm shadow-emerald-500/20">
                        <i class="fas fa-check-double text-white"></i> Terverifikasi
                    </span>
                @endif

                <div class="h-8 w-[1px] bg-slate-100 dark:bg-navy-950/50"></div>

                {{-- SLOT 2: Jam (Realtime jika belum verifikasi, Waktu Verifikasi jika sudah) --}}
                <div class="text-right">
                    @if(($inf->status_verifikasi ?? 'Pending') != 'Verified')
                        <p class="text-xs font-black text-navy-900 dark:text-white dark:text-white" id="mini-clock">00:00 WITA</p>
                        <p class="text-[9px] font-bold text-slate-400 dark:text-slate-300 uppercase tracking-tighter">{{ now()->translatedFormat('d M Y') }}</p>
                    @else
                        <p class="text-sm font-black text-navy-900 dark:text-white">{{ \Carbon\Carbon::parse($inf->updated_at)->translatedFormat('H:i') }} WITA</p>
                        <p class="text-[9px] font-bold text-slate-400 dark:text-slate-300 uppercase tracking-tighter">{{ \Carbon\Carbon::parse($inf->updated_at)->translatedFormat('l, d F Y') }}</p>
                    @endif
                </div>


                <div class="h-8 w-[1px] bg-slate-100 dark:bg-navy-950/50"></div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.profile') }}" class="text-right group hidden md:block">
                        <p class="text-sm font-black text-navy-900 dark:text-white dark:text-white leading-none uppercase group-hover:text-gold-500 transition-all max-w-[100px] sm:max-w-[150px] md:max-w-[300px] truncate">{{ auth()->user()->name }}</p>
                        <p class="text-[10px] md:text-xs font-bold text-emerald-500 uppercase mt-0.5">Aktif</p>
                    </a>
                    <a href="{{ route('admin.profile') }}" class="w-10 h-10 bg-navy-900 rounded-xl flex items-center justify-center text-gold-500 overflow-hidden hover:shadow-lg transition-all shadow-md">
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

            {{-- Success Alert --}}
            @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/20 rounded-2xl flex items-center gap-3">
                <i class="fas fa-check-circle text-emerald-500 text-xl"></i>
                <p class="text-sm font-bold text-emerald-700 dark:text-emerald-500">{{ session('success') }}</p>
            </div>
            @endif

            {{-- ID & Status Badge --}}
            <div class="flex flex-wrap items-center gap-3 mb-6">
                <span class="px-3 py-1.5 bg-navy-900 text-gold-500 rounded-xl text-xs font-black tracking-widest uppercase">
                    <i class="fas fa-hashtag mr-1"></i> INF-{{ $inf->id_infrastruktur }}
                </span>
                <span class="px-3 py-1.5 bg-gold-500/10 text-gold-600 dark:text-gold-500 border border-gold-500/20 rounded-xl text-xs font-black tracking-widest uppercase">
                    {{ strtoupper(ucfirst($inf->jenis) ?? 'Infrastruktur') }}
                </span>
                @php
                    $statusMap = [
                        'baik'         => 'bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-500 border-emerald-200 dark:border-emerald-500/20',
                        'rusak sedang' => 'bg-orange-50 dark:bg-orange-500/10 text-orange-600 dark:text-orange-500 border-orange-200 dark:border-orange-500/20',
                        'rusak berat'  => 'bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-500 border-red-200 dark:border-red-500/20',
                    ];
                    $statusClass = $statusMap[strtolower($inf->kondisi ?? '')] ?? 'bg-slate-50 dark:bg-navy-950/50 text-slate-500 dark:text-slate-400 border-slate-200';
                @endphp
                <span class="px-3 py-1.5 border rounded-xl text-xs font-black tracking-widest uppercase {{ $statusClass }}">
                    {{ strtoupper($inf->kondisi ?? 'Pending') }}
                </span>
                <span class="text-xs text-slate-400 dark:text-slate-300 font-semibold">
                    <i class="fas fa-user-circle mr-1"></i> Surveyor: {{ $inf->nama_user ?? 'Tidak diketahui' }}
                </span>
            </div>

            @php
                $hasilAi  = \Illuminate\Support\Facades\DB::table('analisis_ai')->where('id_infrastruktur', $inf->id_infrastruktur)->first();
                $hasilCnn = \Illuminate\Support\Facades\DB::table('citra_cnn')->where('id_infrastruktur', $inf->id_infrastruktur)->first();
                $cleanPath = $inf->foto_terbaru ? str_replace('\\', '/', $inf->foto_terbaru) : null;
                $fotoUrl   = $cleanPath ? asset('storage/' . (str_contains($cleanPath, 'infrastruktur/') ? $cleanPath : 'infrastruktur/' . $cleanPath)) : null;
            @endphp

            <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

                {{-- ── Kolom Kiri (2/3) ── --}}
                <div class="xl:col-span-2 space-y-6">

                    {{-- Section 1: Identitas & Wilayah --}}
                    <div class="bg-white dark:bg-navy-900/90 dark:backdrop-blur-xl rounded-3xl border border-slate-100 dark:border-white/10 shadow-sm p-8">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-8 h-8 bg-navy-900 rounded-xl flex items-center justify-center text-gold-500 shrink-0">
                                <i class="fas fa-info-circle text-xs"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-black text-navy-900 dark:text-white uppercase tracking-wider">Identitas & Wilayah</h4>
                                <p class="text-xs text-slate-400 dark:text-slate-300 font-semibold mt-0.5">Informasi dasar aset infrastruktur</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            {{-- Nama --}}
                            <div class="md:col-span-2">
                                <p class="text-xs font-black text-slate-400 dark:text-slate-300 uppercase tracking-widest mb-1.5">Nama Infrastruktur</p>
                                <div class="px-4 py-3 bg-slate-50 dark:bg-navy-950/50 border border-slate-100 dark:border-white/10 rounded-xl text-sm font-black text-navy-900 dark:text-white">
                                    {{ $inf->nama_objek ?? $inf->nama_infrastruktur }}
                                </div>
                            </div>
                            {{-- Jenis --}}
                            <div>
                                <p class="text-xs font-black text-slate-400 dark:text-slate-300 uppercase tracking-widest mb-1.5">Jenis Infrastruktur</p>
                                <div class="px-4 py-3 bg-slate-50 dark:bg-navy-950/50 border border-slate-100 dark:border-white/10 rounded-xl flex items-center gap-2">
                                    <span class="px-2 py-0.5 bg-navy-900 text-gold-500 rounded-md text-[7px] font-black tracking-wider uppercase">AI</span>
                                    <span class="text-sm font-black text-navy-900 dark:text-white uppercase">{{ ucfirst($inf->jenis) ?? '—' }}</span>
                                </div>
                            </div>
                            {{-- Material --}}
                            <div>
                                <p class="text-xs font-black text-slate-400 dark:text-slate-300 uppercase tracking-widest mb-1.5">Material Utama</p>
                                <div class="px-4 py-3 bg-slate-50 dark:bg-navy-950/50 border border-slate-100 dark:border-white/10 rounded-xl text-sm font-semibold text-slate-700 dark:text-slate-300">
                                    {{ $inf->material_eksisting ?? '—' }}
                                </div>
                            </div>
                            {{-- Kecamatan --}}
                            <div>
                                <p class="text-xs font-black text-slate-400 dark:text-slate-300 uppercase tracking-widest mb-1.5">Kecamatan</p>
                                <div class="px-4 py-3 bg-slate-50 dark:bg-navy-950/50 border border-slate-100 dark:border-white/10 rounded-xl text-sm font-semibold text-slate-700 dark:text-slate-300 flex items-center gap-2">
                                    <i class="fas fa-map-marker-alt text-gold-500 text-xs"></i>
                                    {{ $inf->nama_kecamatan ?? '—' }}
                                </div>
                            </div>
                            {{-- Kelurahan --}}
                            <div>
                                <p class="text-xs font-black text-slate-400 dark:text-slate-300 uppercase tracking-widest mb-1.5">Kelurahan</p>
                                <div class="px-4 py-3 bg-slate-50 dark:bg-navy-950/50 border border-slate-100 dark:border-white/10 rounded-xl text-sm font-semibold text-slate-700 dark:text-slate-300 flex items-center gap-2">
                                    <i class="fas fa-map-marker-alt text-gold-500 text-xs"></i>
                                    {{ $inf->nama_kelurahan ?? '—' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Section 2: Detail Teknis --}}
                    <div class="bg-white dark:bg-navy-900/90 dark:backdrop-blur-xl rounded-3xl border border-slate-100 dark:border-white/10 shadow-sm p-8">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-8 h-8 bg-gold-500 rounded-xl flex items-center justify-center text-white shrink-0">
                                <i class="fas fa-ruler-combined text-xs"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-black text-navy-900 dark:text-white uppercase tracking-wider">Detail Teknis</h4>
                                <p class="text-xs text-slate-400 dark:text-slate-300 font-semibold mt-0.5">Dimensi, kondisi, dan parameter lapangan</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-5">
                            <div class="bg-amber-50 dark:bg-amber-500/10 border border-amber-100 dark:border-amber-500/20 rounded-2xl p-4 text-center">
                                <p class="text-xs font-black text-slate-400 dark:text-slate-300 uppercase tracking-wider mb-1">Panjang</p>
                                <p class="text-xl font-black text-navy-900 dark:text-white">{{ number_format($inf->panjang ?? 0, 1) }}</p>
                                <p class="text-xs text-slate-400 dark:text-slate-300 font-bold">meter</p>
                            </div>
                            <div class="bg-amber-50 dark:bg-amber-500/10 border border-amber-100 dark:border-amber-500/20 rounded-2xl p-4 text-center">
                                <p class="text-xs font-black text-slate-400 dark:text-slate-300 uppercase tracking-wider mb-1">Lebar</p>
                                <p class="text-xl font-black text-navy-900 dark:text-white">{{ number_format($inf->lebar ?? 0, 1) }}</p>
                                <p class="text-xs text-slate-400 dark:text-slate-300 font-bold">meter</p>
                            </div>
                            <div class="bg-amber-50 dark:bg-amber-500/10 border border-amber-100 dark:border-amber-500/20 rounded-2xl p-4 text-center">
                                <p class="text-xs font-black text-slate-400 dark:text-slate-300 uppercase tracking-wider mb-1">Drainase</p>
                                @if(($inf->has_drainase ?? 'tidak') == 'ya')
                                    <i class="fas fa-check-circle text-2xl text-emerald-500 my-1 block"></i>
                                    <p class="text-xs text-emerald-600 dark:text-emerald-500 font-black uppercase">Ada</p>
                                @else
                                    <i class="fas fa-times-circle text-2xl text-red-400 my-1 block"></i>
                                    <p class="text-xs text-red-500 font-black uppercase">Tidak Ada</p>
                                @endif
                            </div>
                            <div class="bg-amber-50 dark:bg-amber-500/10 border border-amber-100 dark:border-amber-500/20 rounded-2xl p-4 text-center">
                                <p class="text-xs font-black text-slate-400 dark:text-slate-300 uppercase tracking-wider mb-1">Gorong-gorong</p>
                                @if(($inf->has_gorong_gorong ?? 'tidak') == 'ya')
                                    <i class="fas fa-check-circle text-2xl text-emerald-500 my-1 block"></i>
                                    <p class="text-xs text-emerald-600 dark:text-emerald-500 font-black uppercase">Ada</p>
                                @else
                                    <i class="fas fa-times-circle text-2xl text-red-400 my-1 block"></i>
                                    <p class="text-xs text-red-500 font-black uppercase">Tidak Ada</p>
                                @endif
                            </div>
                        </div>

                        <div>
                            <p class="text-xs font-black text-slate-400 dark:text-slate-300 uppercase tracking-widest mb-1.5">Deskripsi Kondisi Lapangan</p>
                            <div class="px-4 py-3 bg-amber-50 dark:bg-amber-500/10 border border-amber-100 dark:border-amber-500/20 rounded-xl text-sm font-semibold text-slate-700 dark:text-slate-300 italic leading-relaxed">
                                @if(strtolower($inf->kondisi ?? '') == 'menunggu ai')
                                    <div class="flex items-center gap-2 text-amber-600 dark:text-amber-500 mb-2 not-italic">
                                        <i class="fas fa-exclamation-triangle text-xs"></i>
                                        <span class="text-xs font-black uppercase tracking-widest">Deskripsi Belum Lengkap</span>
                                    </div>
                                    <p class="text-xs text-slate-400 dark:text-slate-300 not-italic">Silakan edit dan masukkan kata kunci kerusakan agar Decision Tree dapat memberikan skor akurat.</p>
                                @else
                                    "{{ $inf->kondisi ?? '—' }}"
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Section 3: Lokasi Geografis --}}
                    <div class="bg-white dark:bg-navy-900/90 dark:backdrop-blur-xl rounded-3xl border border-slate-100 dark:border-white/10 shadow-sm p-8">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-8 h-8 bg-navy-500 rounded-xl flex items-center justify-center text-white shrink-0">
                                <i class="fas fa-map-marker-alt text-xs"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-black text-navy-900 dark:text-white uppercase tracking-wider">Lokasi Geografis</h4>
                                <p class="text-xs text-slate-400 dark:text-slate-300 font-semibold mt-0.5">Koordinat dan visualisasi peta</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-5">
                            <div>
                                <p class="text-xs font-black text-slate-400 dark:text-slate-300 uppercase tracking-widest mb-1.5">Garis Lintang</p>
                                <div class="px-4 py-3 bg-slate-50 dark:bg-navy-950/50 border border-slate-100 dark:border-white/10 rounded-xl text-sm font-mono font-bold text-navy-900 dark:text-white">
                                    {{ $inf->latitude ?? '—' }}
                                </div>
                            </div>
                            <div>
                                <p class="text-xs font-black text-slate-400 dark:text-slate-300 uppercase tracking-widest mb-1.5">Garis Bujur</p>
                                <div class="px-4 py-3 bg-slate-50 dark:bg-navy-950/50 border border-slate-100 dark:border-white/10 rounded-xl text-sm font-mono font-bold text-navy-900 dark:text-white">
                                    {{ $inf->longitude ?? '—' }}
                                </div>
                            </div>
                        </div>

                        <div class="pt-2 mb-2">
                            <div class="relative rounded-[2rem] border border-slate-100 dark:border-white/10 shadow-inner overflow-hidden mb-2">
                                <div id="mini-map" class="w-full z-0 h-[260px]"></div>
                            </div>
                            <p class="text-xs font-black text-slate-400 dark:text-slate-300 text-center tracking-widest mt-3">
                                LAT: <span class="text-navy-900 dark:text-white">{{ $inf->latitude }}</span> &nbsp;|&nbsp; LNG: <span class="text-navy-900 dark:text-white">{{ $inf->longitude }}</span>
                            </p>
                        </div>
                    </div>

                    <!-- HYBRID AI RESULTS -->
                    <x-hybrid-ai-analytics 
                        :cnnScore="$hasilCnn ? round($hasilCnn->skor_cnn * 100) : 0"
                        :cnnLabel="$hasilCnn->label_kondisi ?? 'Tidak Diketahui'"
                        :dtScore="$hasilAi->skor_dt ?? 0"
                        :dtLabel="$hasilAi->label_prioritas ?? 'Tidak Diketahui'"
                        :rekomendasiAi="$hasilAi->rekomendasi ?? 'Belum ada rekomendasi penanganan.'"
                        :rekomendasiManual="$inf->rekomendasi_manual ?? null"
                        :status="$inf->status_verifikasi ?? 'Pending'"
                    />

                    {{-- Catatan Eksekutif --}}
                    @if($inf->alasan_penolakan)
                    <div class="bg-amber-50 dark:bg-amber-500/10 rounded-[2.5rem] p-8 border border-amber-100 dark:border-amber-500/20 shadow-sm relative overflow-hidden mt-6">
                        <div class="absolute top-0 right-0 w-24 h-24 bg-amber-500/5 rounded-bl-full"></div>
                        <h4 class="text-sm font-black text-amber-900 uppercase tracking-widest mb-3 flex items-center gap-2">
                            <i class="fas fa-comment-dots text-amber-500"></i> Catatan Eksekutif (Tim Teknis)
                        </h4>
                        <div class="p-5 bg-white/60 rounded-2xl border border-amber-200/50">
                            <p class="text-sm font-bold text-slate-600 dark:text-slate-400 leading-relaxed">{{ $inf->alasan_penolakan }}</p>
                        </div>
                    </div>
                    @endif

                </div>{{-- /kolom kiri --}}

                {{-- ── Kolom Kanan (1/3) ── --}}
                <div class="space-y-6">

                    {{-- Foto --}}
                    <div class="bg-white dark:bg-navy-900/90 dark:backdrop-blur-xl rounded-3xl border border-slate-100 dark:border-white/10 shadow-sm p-6">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-8 h-8 bg-emerald-500 rounded-xl flex items-center justify-center text-white shrink-0">
                                <i class="fas fa-camera text-xs"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-black text-navy-900 dark:text-white uppercase tracking-wider">Dokumentasi Visual</h4>
                                <p class="text-xs text-slate-400 dark:text-slate-300 font-semibold mt-0.5">Foto survei lapangan</p>
                            </div>
                        </div>

                        <div class="relative rounded-2xl overflow-hidden bg-navy-950 aspect-[3/4] w-full flex items-center justify-center group">
                            @if($fotoUrl)
                                <img src="{{ $fotoUrl }}" alt="Foto Infrastruktur"
                                     class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">

                                {{-- Detection overlay — berbasis label AI, bukan teks kondisi surveyor --}}
                                @php
                                    $labelCnnRaw2       = strtolower($hasilCnn->label_kondisi ?? '');
                                    $confidencePercent2 = round(($hasilCnn->skor_cnn ?? 0) * 100);
                                    $labelCnnDisplay2   = $hasilCnn->label_kondisi ?? 'Tidak Diketahui';

                                    if (in_array($labelCnnRaw2, ['kondisi rusak berat', 'rusak berat', 'berat'])) {
                                        $ov2Border = 'border-red-500/60';
                                        $ov2Bg     = 'bg-red-500/5';
                                        $ov2Corner = 'border-red-500';
                                        $ov2Badge  = 'bg-red-600';
                                        $ov2Icon   = 'fa-exclamation-triangle';
                                        $ov2Show   = true;
                                    } elseif (in_array($labelCnnRaw2, ['kondisi rusak sedang', 'rusak sedang', 'sedang'])) {
                                        $ov2Border = 'border-orange-400/60';
                                        $ov2Bg     = 'bg-orange-400/5';
                                        $ov2Corner = 'border-orange-400';
                                        $ov2Badge  = 'bg-orange-500';
                                        $ov2Icon   = 'fa-exclamation-circle';
                                        $ov2Show   = true;
                                    } elseif (in_array($labelCnnRaw2, ['kondisi rusak ringan', 'rusak ringan', 'ringan'])) {
                                        $ov2Border = 'border-yellow-400/60';
                                        $ov2Bg     = 'bg-yellow-400/5';
                                        $ov2Corner = 'border-yellow-400';
                                        $ov2Badge  = 'bg-yellow-500';
                                        $ov2Icon   = 'fa-wrench';
                                        $ov2Show   = true;
                                    } else {
                                        $ov2Border = 'border-emerald-500/60';
                                        $ov2Bg     = 'bg-emerald-500/5';
                                        $ov2Corner = 'border-emerald-500';
                                        $ov2Badge  = 'bg-emerald-600';
                                        $ov2Icon   = 'fa-check-circle';
                                        $ov2Show   = $hasilCnn ? true : false;
                                    }
                                @endphp
                                @if($hasilCnn && $ov2Show)
                                <div class="absolute inset-0 flex items-center justify-center pointer-events-none p-6">
                                    <div class="relative w-[50%] h-[50%] border-2 {{ $ov2Border }} {{ $ov2Bg }} animate-pulse">
                                        <div class="absolute -top-1 -left-1 w-4 h-4 border-t-4 border-l-4 {{ $ov2Corner }}"></div>
                                        <div class="absolute -top-1 -right-1 w-4 h-4 border-t-4 border-r-4 {{ $ov2Corner }}"></div>
                                        <div class="absolute -bottom-1 -left-1 w-4 h-4 border-b-4 border-l-4 {{ $ov2Corner }}"></div>
                                        <div class="absolute -bottom-1 -right-1 w-4 h-4 border-b-4 border-r-4 {{ $ov2Corner }}"></div>
                                        <div class="absolute -top-7 left-0 {{ $ov2Badge }} text-white text-[10px] font-black px-2 py-1 rounded flex items-center gap-1">
                                            <i class="fas {{ $ov2Icon }}"></i>
                                            Confidence {{ $confidencePercent2 }}% &rarr; {{ $labelCnnDisplay2 }}
                                        </div>
                                        <div class="absolute inset-0 bg-gradient-to-b from-transparent via-white/5 to-transparent h-1/4 w-full" style="animation: scan 2s linear infinite;"></div>
                                    </div>
                                </div>
                                @endif

                                <div class="absolute inset-0 bg-black/50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                    <a href="{{ $fotoUrl }}" target="_blank"
                                       class="bg-white dark:bg-navy-900/90 dark:backdrop-blur-xl text-navy-900 dark:text-white px-4 py-2 rounded-xl text-xs font-black shadow-xl uppercase tracking-widest hover:scale-105 transition-all flex items-center gap-2">
                                        <i class="fas fa-expand"></i> Lihat Full
                                    </a>
                                </div>
                            @else
                                <div class="text-center py-10">
                                    <i class="fas fa-image text-5xl text-slate-700 dark:text-slate-300 mb-3 block"></i>
                                    <p class="text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest">Tidak Ada Foto</p>
                                </div>
                            @endif
                        </div>

                        @if($fotoUrl)
                        <p class="text-xs text-slate-400 dark:text-slate-300 font-semibold mt-2 truncate">
                            <i class="fas fa-user-circle mr-1"></i> {{ $inf->nama_user ?? 'Surveyor' }}
                        </p>
                        @endif
                    </div>

                    {{-- Info Ringkas --}}
                    <div class="bg-white dark:bg-navy-900/90 dark:backdrop-blur-xl rounded-3xl border border-slate-100 dark:border-white/10 shadow-sm p-6">
                        <h5 class="text-xs font-black text-navy-900 dark:text-white uppercase tracking-widest mb-4">
                            <i class="fas fa-list-ul mr-1 text-gold-500"></i> Ringkasan Data
                        </h5>
                        <div class="space-y-3">
                            @php
                                $rows = [
                                    ['label' => 'ID Aset',      'value' => 'INF-'.$inf->id_infrastruktur],
                                    ['label' => 'Status',       'value' => ($inf->status_verifikasi ?? 'Pending') == 'Verified' ? 'Terverifikasi' : 'Pending'],
                                    ['label' => 'Diverifikasi', 'value' => ($inf->status_verifikasi ?? 'Pending') == 'Verified' ? \Carbon\Carbon::parse($inf->updated_at)->translatedFormat('d M Y, H:i') . ' WITA' : '—'],
                                    ['label' => 'Admin',        'value' => ($inf->status_verifikasi ?? 'Pending') == 'Verified' ? 'Admin Aktif' : '—'],
                                    ['label' => 'Tgl Survey',   'value' => $inf->tgl_survey ? \Carbon\Carbon::parse($inf->tgl_survey)->translatedFormat('d M Y') : '-'],
                                    ['label' => 'Dibuat',       'value' => $inf->created_at ? \Carbon\Carbon::parse($inf->created_at)->translatedFormat('d M Y') : '-'],
                                    ['label' => 'CNN Label',    'value' => $hasilCnn->label_kondisi ?? '—'],
                                    ['label' => 'DT Prioritas', 'value' => $hasilAi->label_prioritas ?? '—'],
                                ];
                            @endphp
                            @foreach($rows as $row)
                            <div class="flex justify-between items-center py-2 border-b border-slate-50 last:border-0">
                                <span class="text-xs font-bold text-slate-400 dark:text-slate-300 uppercase tracking-wider">{{ $row['label'] }}</span>
                                <span class="text-xs font-black text-navy-900 dark:text-white">{{ $row['value'] }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Tombol Aksi --}}
                    <div class="bg-white dark:bg-navy-900/90 dark:backdrop-blur-xl rounded-3xl border border-slate-100 dark:border-white/10 shadow-sm p-6 space-y-3">
                        <a href="{{ route('admin.infrastruktur.edit', $inf->id_infrastruktur) }}"
                            class="w-full flex items-center justify-center gap-2 bg-gold-500 hover:bg-gold-600 text-white py-3.5 rounded-2xl font-black text-sm tracking-widest transition-all shadow-md shadow-gold-500/20 uppercase">
                            <i class="fas fa-edit"></i> Edit Data
                        </a>
                        <a href="{{ route('admin.infrastruktur.pdf', $inf->id_infrastruktur) }}"
                            class="w-full flex items-center justify-center gap-2 bg-navy-900 hover:bg-navy-950 text-white py-3.5 rounded-2xl font-black text-sm tracking-widest transition-all shadow-md shadow-navy-900/20 uppercase">
                            <i class="fas fa-file-pdf"></i> Export PDF
                        </a>
                        <a href="{{ route('admin.infrastruktur') }}"
                            class="w-full flex items-center justify-center gap-2 bg-slate-100 dark:bg-navy-950/50 hover:bg-slate-200 text-slate-500 dark:text-slate-400 py-3.5 rounded-2xl font-black text-sm tracking-widest transition-all uppercase">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>

                </div>{{-- /kolom kanan --}}
            </div>
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

        // Leaflet map
        const lat = {{ $inf->latitude ?? -3.316694 }};
        const lng = {{ $inf->longitude ?? 114.590111 }};
        const map = L.map('mini-map', { zoomControl: true, dragging: false, scrollWheelZoom: false }).setView([lat, lng], 16);
        L.tileLayer('https://{s}.google.com/vt/lyrs=s,h&x={x}&y={y}&z={z}', {
            maxZoom: 20, subdomains: ['mt0','mt1','mt2','mt3']
        }).addTo(map);

        // Marker indikator kondisi
        const condLower = "{{ strtolower($inf->kondisi ?? '') }}";
        let markerColor = '#059669'; // default Baik
        if (condLower.includes('berat')) {
            markerColor = '#be123c';
        } else if (condLower.includes('sedang') || condLower.includes('ringan')) {
            markerColor = '#d97706';
        }

        const markerHtml = `<div style="background-color:${markerColor};width:18px;height:18px;border-radius:50%;border:4px solid white;box-shadow:0 0 15px rgba(0,0,0,0.25);"></div>`;

        const icon = L.divIcon({
            html: markerHtml,
            className: '',
            iconSize: [18, 18],
            iconAnchor: [9, 9]
        });

        L.marker([lat, lng], { icon }).addTo(map)
            .bindPopup(`<div style="font-family:'Plus Jakarta Sans',sans-serif;min-width:140px;">
                <p style="font-size:9px;font-weight:900;color:#0f0e2c;text-transform:uppercase;margin-bottom:2px;">{{ $inf->nama_objek ?? $inf->nama_infrastruktur }}</p>
                <p style="font-size:8px;color:#c5a059;font-weight:700;text-transform:uppercase;">{{ $inf->jenis ?? '—' }}</p>
            </div>`, { maxWidth: 200 })
            .openPopup();
    </script>
</body>
</html>

