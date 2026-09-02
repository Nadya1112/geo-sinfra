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

    <title>Detail Laporan Warga | Surveyor SINFRA</title>
    <link rel="icon" href="{{ asset('logo_geo-sinfra.png') }}" type="image/png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
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
    <style> body { font-family: 'Plus Jakarta Sans', sans-serif; } </style>
</head>
<body class="bg-slate-50 dark:bg-[#0b0a26]/50 flex h-screen overflow-hidden text-slate-800 font-sans transition-colors duration-300">

    @include('surveyor.partials.sidebar')

    <main class="flex-1 flex flex-col h-screen overflow-hidden text-left relative">
        <header class="bg-white/85 backdrop-blur-xl border-b border-slate-100 dark:border-white/10 sticky top-0 px-4 pl-20 md:px-8 py-4 flex justify-between items-center z-40">
            <div class="flex items-center gap-4">
                <a href="{{ route('surveyor.laporan') }}" class="w-10 h-10 bg-white border border-slate-100 dark:border-white/10 rounded-xl flex items-center justify-center text-slate-400 dark:text-slate-300 hover:text-gold-500 hover:border-gold-500/20 hover:shadow-lg hover:shadow-gold-500/5 transition-all group" title="Kembali ke Daftar Laporan">
                    <i class="fas fa-arrow-left text-xs group-hover:-translate-x-1 transition-transform"></i>
                </a>
                <div class="text-left">
                    <p class="text-xs font-black text-gold-500 uppercase tracking-[0.2em] mb-1">Detail Laporan</p>
                    <h2 class="text-xl font-black text-navy-900 dark:text-white leading-none">Informasi Laporan Warga</h2>
                </div>
            </div>
        </header>

        <div class="flex-1 overflow-y-auto custom-scrollbar p-6 md:p-8">
            <div class="max-w-4xl mx-auto space-y-6">
                <div class="bg-white dark:bg-navy-900/90 rounded-[2rem] p-6 md:p-8 shadow-sm border border-slate-100 dark:border-white/10">
                    <div class="flex flex-col md:flex-row justify-between items-start gap-4 mb-6">
                        <div>
                            <h3 class="text-lg font-black text-navy-900 dark:text-white">Deskripsi Laporan</h3>
                            <p class="text-xs text-slate-500 font-bold uppercase mt-1">{{ \Carbon\Carbon::parse($laporan->created_at)->translatedFormat('l, d F Y H:i') }}</p>
                        </div>
                        <div class="px-4 py-2 bg-slate-50 dark:bg-[#0b0a26]/50 rounded-xl border border-slate-200 dark:border-white/10">
                            <span class="text-xs font-black text-navy-900 dark:text-white uppercase tracking-widest">Status: </span>
                            <span class="text-xs font-bold text-gold-500 ml-1">{{ $laporan->status }}</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                        <div>
                            <p class="text-[10px] font-black text-slate-400 dark:text-slate-300 uppercase tracking-widest mb-1.5">Nama Pelapor</p>
                            <p class="text-sm font-bold text-navy-900 dark:text-white">{{ $laporan->nama_pelapor }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-slate-400 dark:text-slate-300 uppercase tracking-widest mb-1.5">No HP / WhatsApp</p>
                            <p class="text-sm font-bold text-navy-900 dark:text-white"><i class="fab fa-whatsapp text-emerald-500 mr-1"></i>{{ $laporan->no_hp }}</p>
                        </div>
                    </div>

                    <div>
                        <p class="text-[10px] font-black text-slate-400 dark:text-slate-300 uppercase tracking-widest mb-2">Isi Laporan Kerusakan</p>
                        <div class="p-4 bg-slate-50 dark:bg-[#0b0a26]/50 rounded-xl border border-slate-100 dark:border-white/10">
                            <p class="text-sm text-slate-700 leading-relaxed font-medium">{{ $laporan->deskripsi }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-navy-900/90 rounded-[2rem] p-6 md:p-8 shadow-sm border border-slate-100 dark:border-white/10">
                    <h3 class="text-lg font-black text-navy-900 dark:text-white mb-6">Lokasi & Lampiran Foto</h3>
                    
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div>
                            <p class="text-[10px] font-black text-slate-400 dark:text-slate-300 uppercase tracking-widest mb-2">Titik Koordinat</p>
                            <div class="flex items-center justify-between p-4 bg-slate-50 dark:bg-[#0b0a26]/50 rounded-xl border border-slate-100 dark:border-white/10 mb-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-emerald-100 text-emerald-600 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs font-black text-navy-900 dark:text-white">{{ $laporan->latitude }}, {{ $laporan->longitude }}</p>
                                        <p class="text-[10px] text-slate-500 font-bold uppercase mt-0.5">Koordinat Google Maps</p>
                                    </div>
                                </div>
                                <a href="https://www.google.com/maps/search/?api=1&query={{ $laporan->latitude }},{{ $laporan->longitude }}" target="_blank" class="px-4 py-2 bg-white dark:bg-navy-900/90 border border-slate-200 dark:border-white/10 rounded-lg text-xs font-bold text-navy-900 dark:text-white hover:bg-emerald-50 hover:text-emerald-600 hover:border-emerald-200 transition-all shadow-sm">
                                    Buka di Maps
                                </a>
                            </div>
                        </div>

                        <div>
                            <p class="text-[10px] font-black text-slate-400 dark:text-slate-300 uppercase tracking-widest mb-2">Foto Laporan Warga</p>
                            @if($laporan->foto)
                                <a href="{{ asset('storage/' . $laporan->foto) }}" target="_blank" class="block w-full h-40 rounded-xl overflow-hidden group relative border border-slate-200 dark:border-white/10">
                                    <img src="{{ asset('storage/' . $laporan->foto) }}" alt="Foto Laporan" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                    <div class="absolute inset-0 bg-navy-900/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                        <div class="bg-white/90 backdrop-blur-sm px-4 py-2 rounded-lg text-xs font-bold text-navy-900 dark:text-white flex items-center gap-2">
                                            <i class="fas fa-expand"></i> Lihat Penuh
                                        </div>
                                    </div>
                                </a>
                            @else
                                <div class="w-full h-40 rounded-xl bg-slate-50 dark:bg-[#0b0a26]/50 border border-slate-200 dark:border-white/10 flex flex-col items-center justify-center text-slate-400 dark:text-slate-300">
                                    <i class="fas fa-image text-2xl mb-2"></i>
                                    <p class="text-xs font-bold uppercase tracking-widest">Tidak ada foto</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
