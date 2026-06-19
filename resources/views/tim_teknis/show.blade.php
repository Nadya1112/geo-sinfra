<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Infrastruktur | GEO-SINFRA</title>
    <link rel="icon" href="{{ asset('logo_geo-sinfra.png') }}" type="image/png">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        navy: { 50: '#f0f4f8', 100: '#d9e2ec', 200: '#bcccdc', 300: '#9fb3c8', 400: '#829ab1', 500: '#627d98', 600: '#486581', 700: '#334e68', 800: '#243b53', 900: '#0f0e2c', 950: '#0a091d' },
                        gold: { 50: '#fbf8f1', 100: '#f5ebd9', 200: '#eed9b9', 300: '#e5c292', 400: '#dba665', 500: '#c5a059', 600: '#b48135', 700: '#96652a', 800: '#7c5327', 900: '#644422', 950: '#382310' }
                    },
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .leaflet-bar { border: none !important; box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1) !important; border-radius: 8px !important; overflow: hidden; }
        .leaflet-bar a { width: 26px !important; height: 26px !important; line-height: 26px !important; font-size: 14px !important; }
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    </style>
</head>
<body class="bg-slate-50 flex h-screen overflow-hidden text-slate-800 text-left">

    @include('tim_teknis.partials.sidebar')

    <main class="flex-1 flex flex-col h-screen overflow-y-auto custom-scrollbar">
        <!-- HEADER -->
        <header class="bg-white border-b border-slate-100 px-8 py-5 flex justify-between items-center z-10 sticky top-0">
            <div class="flex items-center gap-4">
                <a href="{{ route('tim_teknis.validasi') }}" class="w-10 h-10 flex items-center justify-center bg-slate-50 text-slate-400 rounded-xl hover:bg-gold-50 hover:text-gold-500 transition-all border border-slate-100">
                    <i class="fas fa-arrow-left text-sm"></i>
                </a>
                <div>
                    <p class="text-[10px] font-extrabold text-gold-500 uppercase tracking-[0.2em] mb-1">Verifikasi Usulan</p>
                    <div class="flex items-center gap-4">
                        <h2 class="text-xl font-black text-navy-900">Detail Infrastruktur</h2>
                        <a href="{{ route('tim_teknis.infrastruktur.pdf', $infrastruktur->id_infrastruktur) }}" target="_blank" class="px-3 py-1.5 bg-rose-50 text-rose-600 rounded-lg text-[10px] font-black uppercase tracking-widest hover:bg-rose-500 hover:text-white transition-colors border border-rose-100 flex items-center gap-2 shadow-sm">
                            <i class="fas fa-file-pdf"></i> Cetak PDF
                        </a>
                    </div>
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
                        <p class="text-[11px] font-black text-navy-900 leading-none uppercase">{{ auth()->user()->name }}</p>
                        <p class="text-[9px] font-bold text-emerald-500 uppercase mt-1 leading-none">ONLINE</p>
                    </div>
                    <div class="w-10 h-10 bg-navy-900 rounded-xl flex items-center justify-center text-gold-500 shadow-md group-hover:shadow-lg transition-all overflow-hidden">
                        @if(auth()->user()->profile_photo)
                            <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}" class="w-full h-full object-cover">
                        @else
                            <i class="fas fa-user-circle text-xl"></i>
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
                    @php
                        $cleanPath = $infrastruktur->foto_terbaru ? str_replace('\\', '/', $infrastruktur->foto_terbaru) : null;
                        $fotoUrl   = $cleanPath ? asset('storage/' . (str_contains($cleanPath, 'infrastruktur/') ? $cleanPath : 'infrastruktur/' . $cleanPath)) : null;
                    @endphp
                    <div class="bg-white rounded-[2.5rem] p-4 border border-slate-100 shadow-sm overflow-hidden">
                        <div class="relative aspect-[3/4] w-full rounded-[2rem] overflow-hidden group bg-navy-950 flex items-center justify-center">
                            @if($fotoUrl)
                                <img src="{{ $fotoUrl }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-all flex items-end p-6">
                                    <p class="text-white text-[10px] font-bold uppercase tracking-widest">Foto Dokumentasi</p>
                                </div>
                                <div class="absolute inset-0 bg-black/50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                    <a href="{{ $fotoUrl }}" target="_blank" class="bg-white text-navy-900 px-4 py-2 rounded-xl text-[9px] font-black shadow-xl uppercase tracking-widest hover:scale-105 transition-all flex items-center gap-2">
                                        <i class="fas fa-expand"></i> Lihat Full
                                    </a>
                                </div>
                            @else
                                <div class="text-center">
                                    <i class="fas fa-image text-4xl text-slate-700 mb-2 block"></i>
                                    <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest">Tidak Ada Foto</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- AI Analysis Panel -->
                    <!-- HYBRID AI RESULTS -->
                    <div class="bg-navy-900 rounded-[2.5rem] p-8 text-white shadow-xl relative overflow-hidden">
                        <div class="absolute top-0 right-0 -mt-8 -mr-8 w-32 h-32 bg-white/5 rounded-full blur-2xl"></div>
                        <h4 class="text-[10px] font-black text-gold-300 uppercase tracking-[0.2em] mb-8 flex items-center gap-3">
                            <i class="fas fa-microchip"></i> Hybrid AI Analysis
                        </h4>
                        
                        <div class="space-y-8">
                            <!-- Visual CNN -->
                            <div class="relative">
                                <div class="flex justify-between items-end mb-2">
                                    <p class="text-[9px] font-black text-slate-300 uppercase tracking-widest">Vision (CNN)</p>
                                    <p class="text-xl font-black text-white">{{ $infrastruktur->cnn ? round($infrastruktur->cnn->skor_cnn * 100) : '0' }}%</p>
                                </div>
                                <div class="w-full bg-white/10 h-1.5 rounded-full overflow-hidden">
                                    <div class="bg-gradient-to-r from-gold-500 to-gold-300 h-full" style="width: {{ $infrastruktur->cnn ? ($infrastruktur->cnn->skor_cnn * 100) : '0' }}%"></div>
                                </div>
                                <p class="text-[8px] font-bold text-slate-400 mt-2 italic text-right">{{ $infrastruktur->cnn->label_kondisi ?? 'Scanning visual...' }}</p>
                            </div>
                            
                            <!-- Logic DT -->
                            <div class="relative">
                                <div class="flex justify-between items-end mb-2">
                                    <p class="text-[9px] font-black text-slate-300 uppercase tracking-widest">Logic (DT)</p>
                                    <p class="text-xl font-black text-white">{{ $infrastruktur->analisis->skor_dt ?? '0' }}<span class="text-xs text-slate-400 ml-0.5">/100</span></p>
                                </div>
                                <div class="w-full bg-white/10 h-1.5 rounded-full overflow-hidden">
                                    <div class="bg-gradient-to-r from-[#059669] to-emerald-400 h-full" style="width: {{ $infrastruktur->analisis->skor_dt ?? '0' }}%"></div>
                                </div>
                                <p class="text-[8px] font-bold {{ ($infrastruktur->analisis->label_prioritas ?? '') == 'Rusak Berat' ? 'text-rose-400' : 'text-[#059669]' }} mt-2 italic text-right">
                                    {{ $infrastruktur->analisis->label_prioritas ?? 'Calculating logic...' }}
                                </p>
                            </div>

                            <div class="pt-6 border-t border-white/10">
                                <div class="flex items-center justify-between mb-4">
                                    <p class="text-[9px] font-black text-slate-300 uppercase tracking-widest">Verification</p>
                                    <span class="px-3 py-1 bg-white/5 border border-white/10 rounded-lg text-[8px] font-black uppercase tracking-widest {{ $infrastruktur->status_verifikasi == 'Verified' ? 'text-[#059669]' : 'text-amber-400' }}">
                                        {{ $infrastruktur->status_verifikasi ?? 'Pending' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Aksi Verifikasi -->
                    @if($infrastruktur->status_verifikasi == 'Pending')
                    <div class="bg-white rounded-[2.5rem] p-6 border border-slate-100 shadow-sm">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Tindakan Verifikasi</p>
                        <div class="flex flex-col gap-3">
                            <form action="{{ route('tim_teknis.validasi.proses', $infrastruktur->id_infrastruktur) }}" method="POST">
                                @csrf
                                <input type="hidden" name="status" value="Verified">
                                <button type="submit" class="w-full flex items-center justify-center gap-2 py-3 bg-[#059669] text-white rounded-2xl hover:bg-[#047857] transition-all shadow-lg shadow-[#059669]/20 font-black text-[11px] uppercase tracking-widest">
                                    <i class="fas fa-check"></i> Terima Usulan
                                </button>
                            </form>
                            <form action="{{ route('tim_teknis.validasi.proses', $infrastruktur->id_infrastruktur) }}" method="POST">
                                @csrf
                                <input type="hidden" name="status" value="Rejected">
                                <button type="submit" class="w-full flex items-center justify-center gap-2 py-3 bg-white border border-rose-200 text-rose-500 rounded-2xl hover:bg-rose-50 hover:border-rose-300 transition-all font-black text-[11px] uppercase tracking-widest">
                                    <i class="fas fa-times"></i> Tolak Usulan
                                </button>
                            </form>
                        </div>
                    </div>
                    @elseif($infrastruktur->status_validasi == 'Validated')
                    <div class="bg-white rounded-[2.5rem] p-6 border border-slate-100 shadow-sm relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-24 h-24 bg-blue-500/5 rounded-bl-full"></div>
                        <h4 class="text-[10px] font-black text-navy-900 uppercase tracking-widest mb-4 flex items-center gap-2">
                            <i class="fas fa-tasks text-blue-500"></i> Status Pengerjaan (Tindak Lanjut)
                        </h4>
                        
                        <form action="{{ route('tim_teknis.perbaikan.update', $infrastruktur->id_infrastruktur) }}" method="POST">
                            @csrf
                            <div class="space-y-3">
                                <label class="flex items-center justify-between p-3 rounded-2xl border cursor-pointer transition-all {{ $infrastruktur->status_perbaikan == 'Menunggu' ? 'border-amber-500 bg-amber-50' : 'border-slate-200 hover:bg-slate-50' }}">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-xl {{ $infrastruktur->status_perbaikan == 'Menunggu' ? 'bg-amber-500 text-white' : 'bg-slate-100 text-slate-400' }} flex items-center justify-center text-xs shadow-sm">
                                            <i class="fas fa-clock"></i>
                                        </div>
                                        <div>
                                            <p class="text-[11px] font-black {{ $infrastruktur->status_perbaikan == 'Menunggu' ? 'text-amber-700' : 'text-slate-600' }} uppercase tracking-wider">Menunggu</p>
                                            <p class="text-[9px] font-bold text-slate-400">Belum ditindaklanjuti</p>
                                        </div>
                                    </div>
                                    <input type="radio" name="status_perbaikan" value="Menunggu" class="w-4 h-4 text-amber-500 border-slate-300 focus:ring-amber-500" {{ $infrastruktur->status_perbaikan == 'Menunggu' ? 'checked' : '' }} onchange="this.form.submit()">
                                </label>

                                <label class="flex items-center justify-between p-3 rounded-2xl border cursor-pointer transition-all {{ $infrastruktur->status_perbaikan == 'Proses Perbaikan' ? 'border-blue-500 bg-blue-50' : 'border-slate-200 hover:bg-slate-50' }}">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-xl {{ $infrastruktur->status_perbaikan == 'Proses Perbaikan' ? 'bg-blue-500 text-white' : 'bg-slate-100 text-slate-400' }} flex items-center justify-center text-xs shadow-sm">
                                            <i class="fas fa-tools"></i>
                                        </div>
                                        <div>
                                            <p class="text-[11px] font-black {{ $infrastruktur->status_perbaikan == 'Proses Perbaikan' ? 'text-blue-700' : 'text-slate-600' }} uppercase tracking-wider">Dalam Perbaikan</p>
                                            <p class="text-[9px] font-bold text-slate-400">Sedang dikerjakan tim</p>
                                        </div>
                                    </div>
                                    <input type="radio" name="status_perbaikan" value="Proses Perbaikan" class="w-4 h-4 text-blue-500 border-slate-300 focus:ring-blue-500" {{ $infrastruktur->status_perbaikan == 'Proses Perbaikan' ? 'checked' : '' }} onchange="this.form.submit()">
                                </label>

                                <label class="flex items-center justify-between p-3 rounded-2xl border cursor-pointer transition-all {{ $infrastruktur->status_perbaikan == 'Selesai' ? 'border-emerald-500 bg-emerald-50' : 'border-slate-200 hover:bg-slate-50' }}">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-xl {{ $infrastruktur->status_perbaikan == 'Selesai' ? 'bg-emerald-500 text-white' : 'bg-slate-100 text-slate-400' }} flex items-center justify-center text-xs shadow-sm">
                                            <i class="fas fa-check-double"></i>
                                        </div>
                                        <div>
                                            <p class="text-[11px] font-black {{ $infrastruktur->status_perbaikan == 'Selesai' ? 'text-emerald-700' : 'text-slate-600' }} uppercase tracking-wider">Selesai</p>
                                            <p class="text-[9px] font-bold text-slate-400">Infrastruktur telah tuntas</p>
                                        </div>
                                    </div>
                                    <input type="radio" name="status_perbaikan" value="Selesai" class="w-4 h-4 text-emerald-500 border-slate-300 focus:ring-emerald-500" {{ $infrastruktur->status_perbaikan == 'Selesai' ? 'checked' : '' }} onchange="this.form.submit()">
                                </label>
                            </div>
                        </form>
                    </div>
                    @endif

                    @if($infrastruktur->status_validasi == 'Rejected' && $infrastruktur->alasan_penolakan)
                    <div class="bg-amber-50 rounded-[2.5rem] p-6 border border-amber-100 shadow-sm relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-24 h-24 bg-amber-500/5 rounded-bl-full"></div>
                        <h4 class="text-[10px] font-black text-amber-900 uppercase tracking-widest mb-3 flex items-center gap-2">
                            <i class="fas fa-comment-dots text-amber-500"></i> Catatan Eksekutif (Tim Teknis)
                        </h4>
                        <div class="p-4 bg-white/60 rounded-2xl border border-amber-200/50">
                            <p class="text-xs font-bold text-slate-600 leading-relaxed">{{ $infrastruktur->alasan_penolakan }}</p>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Kolom Kanan: Info & Peta -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white rounded-[2.5rem] p-8 border border-slate-100 shadow-sm">
                        <div class="flex justify-between items-start mb-8">
                            <div>
                                <p class="text-[10px] font-black text-gold-500 uppercase tracking-widest mb-1">{{ ucfirst($infrastruktur->jenis) }}</p>
                                <h3 class="text-2xl font-black text-navy-900">{{ $infrastruktur->nama_infrastruktur }}</h3>
                            </div>
                            <div class="text-right">
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">Diinput Pada</p>
                                <p class="text-xs font-black text-navy-900">{{ $infrastruktur->created_at->translatedFormat('d F Y, H:i') }} WITA</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-8 mb-8">
                            <div class="space-y-4">
                                <div class="flex items-start gap-4">
                                    <div class="w-10 h-10 rounded-2xl bg-navy-50 flex items-center justify-center text-navy-500 border border-navy-100 flex-shrink-0">
                                        <i class="fas fa-map-marked-alt text-sm"></i>
                                    </div>
                                    <div>
                                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Kecamatan</p>
                                        <p class="text-sm font-bold text-navy-900">{{ $infrastruktur->kelurahan->kecamatan->nama_kecamatan ?? '-' }}</p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-4">
                                    <div class="w-10 h-10 rounded-2xl bg-navy-50 flex items-center justify-center text-navy-500 border border-navy-100 flex-shrink-0">
                                        <i class="fas fa-building text-sm"></i>
                                    </div>
                                    <div>
                                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Kelurahan</p>
                                        <p class="text-sm font-bold text-navy-900">{{ $infrastruktur->kelurahan->nama_kelurahan ?? '-' }}</p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-4">
                                    <div class="w-10 h-10 rounded-2xl bg-navy-50 flex items-center justify-center text-navy-500 border border-navy-100 flex-shrink-0">
                                        <i class="fas fa-user text-sm"></i>
                                    </div>
                                    <div>
                                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Surveyor</p>
                                        <p class="text-sm font-bold text-navy-900">{{ $infrastruktur->user->name ?? '-' }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="space-y-4">
                                <div class="flex items-start gap-4">
                                    <div class="w-10 h-10 rounded-2xl bg-navy-50 flex items-center justify-center text-navy-500 border border-navy-100 flex-shrink-0">
                                        <i class="fas fa-location-arrow text-sm"></i>
                                    </div>
                                    <div>
                                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Koordinat</p>
                                        <p class="text-xs font-bold text-navy-900">{{ $infrastruktur->latitude }}, {{ $infrastruktur->longitude }}</p>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <!-- Detail Teknis -->
                        <div class="border-t border-slate-100 pt-6 mb-8 mt-2">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Informasi Fisik Lapangan</p>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-5">
                                <div class="bg-amber-50 border border-amber-100 rounded-2xl p-4 text-center">
                                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-wider mb-1">Panjang</p>
                                    <p class="text-xl font-black text-navy-900">{{ number_format($infrastruktur->panjang ?? 0, 1) }}</p>
                                    <p class="text-[8px] text-slate-400 font-bold">meter</p>
                                </div>
                                <div class="bg-amber-50 border border-amber-100 rounded-2xl p-4 text-center">
                                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-wider mb-1">Lebar</p>
                                    <p class="text-xl font-black text-navy-900">{{ number_format($infrastruktur->lebar ?? 0, 1) }}</p>
                                    <p class="text-[8px] text-slate-400 font-bold">meter</p>
                                </div>
                                <div class="bg-amber-50 border border-amber-100 rounded-2xl p-4 text-center">
                                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-wider mb-1">Drainase</p>
                                    @if(($infrastruktur->has_drainase ?? 'tidak') == 'ya')
                                        <i class="fas fa-check-circle text-2xl text-emerald-500 my-1 block"></i>
                                        <p class="text-[8px] text-emerald-600 font-black uppercase">Ada</p>
                                    @else
                                        <i class="fas fa-times-circle text-2xl text-red-400 my-1 block"></i>
                                        <p class="text-[8px] text-red-500 font-black uppercase">Tidak Ada</p>
                                    @endif
                                </div>
                                <div class="bg-amber-50 border border-amber-100 rounded-2xl p-4 text-center">
                                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-wider mb-1">Gorong-gorong</p>
                                    @if(($infrastruktur->has_gorong_gorong ?? 'tidak') == 'ya')
                                        <i class="fas fa-check-circle text-2xl text-emerald-500 my-1 block"></i>
                                        <p class="text-[8px] text-emerald-600 font-black uppercase">Ada</p>
                                    @else
                                        <i class="fas fa-times-circle text-2xl text-red-400 my-1 block"></i>
                                        <p class="text-[8px] text-red-500 font-black uppercase">Tidak Ada</p>
                                    @endif
                                </div>
                            </div>
                            <div>
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Keterangan Tambahan Surveyor</p>
                                <div class="px-4 py-3 bg-amber-50 border border-amber-100 rounded-xl text-sm font-semibold text-slate-700 italic leading-relaxed">
                                    @if(strtolower($infrastruktur->kondisi ?? '') == 'menunggu ai')
                                        <span class="text-slate-400 font-medium">Kondisi akan ditentukan oleh sistem AI...</span>
                                    @else
                                        "{{ $infrastruktur->kondisi ?? 'Tidak ada keterangan tambahan.' }}"
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Mini Map -->
                        <div class="relative rounded-[2rem] border border-slate-100 shadow-inner overflow-hidden">
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
            const options = { timeZone: 'Asia/Makassar', hour: '2-digit', minute: '2-digit', hour12: false };
            const timeString = new Intl.DateTimeFormat('id-ID', options).format(now);
            const el = document.getElementById('mini-clock');
            if (el) el.textContent = timeString.replace('.', ':') + ' WITA';
        }
        setInterval(updateClock, 1000); updateClock();

        const lat = {{ $infrastruktur->latitude }};
        const lng = {{ $infrastruktur->longitude }};
        const map = L.map('map', { zoomControl: true, scrollWheelZoom: false }).setView([lat, lng], 16);
        L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png').addTo(map);

        const condLower = "{{ strtolower($infrastruktur->kondisi ?? '') }}";
        let color = '#059669'; // default Baik
        if (condLower.includes('berat')) {
            color = '#be123c';
        } else if (condLower.includes('sedang') || condLower.includes('ringan')) {
            color = '#d97706';
        }
        
        const markerHtml = `<div style="background-color:${color};width:18px;height:18px;border-radius:50%;border:4px solid white;box-shadow:0 0 15px rgba(0,0,0,0.25);"></div>`;
        const icon = L.divIcon({ html: markerHtml, className: '', iconSize: [18,18], iconAnchor: [9,9] });
        L.marker([lat, lng], { icon }).addTo(map);
    </script>
</body>
</html>
