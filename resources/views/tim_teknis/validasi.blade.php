<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validasi Usulan | GEO-SINFRA</title>
    <link rel="icon" href="{{ asset('logo_geo-sinfra.png') }}" type="image/png">
    <script src="https://cdn.tailwindcss.com"></script>
            <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: { sans: ['Plus Jakarta Sans', 'sans-serif'] },
                    colors: {
                        navy: { 50:'#f4f4fa', 100:'#e9e9f3', 200:'#c7c8e3', 300:'#9fb3c8', 400:'#829ab1', 500:'#6366f1', 600:'#486581', 700:'#334e68', 800:'#1e1b4b', 900:'#0f0e2c', 950:'#070617' },
                        gold: { 50:'#fdfbf7', 100:'#fbf7ed', 200:'#eed9b9', 300:'#e5c292', 400:'#dba665', 500:'#c5a059', 600:'#b38f4a', 700:'#9d7c3d', 800:'#7c5327', 900:'#644422', 950:'#382310' }
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
    <script>
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .glass-card { background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.2); }
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    </style>
</head>
<body class="bg-slate-50 dark:bg-[#0f0e2c] flex h-screen overflow-hidden text-slate-800 dark:text-white text-left font-sans dark:bg-navy-950 transition-colors duration-300">

    @include('tim_teknis.partials.sidebar')

    <main class="flex-1 flex flex-col h-screen overflow-y-auto custom-scrollbar">
        <!-- HEADER -->
        <header class="bg-white dark:bg-[#1e1b4b] border-b border-slate-100 dark:border-white/10 px-8 py-5 flex justify-between items-center z-10 sticky top-0">
            <div class="flex items-center gap-4">
                <a href="{{ route('tim_teknis.dashboard') }}" class="w-10 h-10 flex items-center justify-center bg-slate-50 dark:bg-[#0f0e2c] text-slate-400 rounded-xl hover:bg-gold-50 hover:text-gold-500 transition-all border border-slate-100 dark:border-white/10">
                    <i class="fas fa-arrow-left text-sm"></i>
                </a>
                <div>
                    <p class="text-xs font-extrabold text-gold-500 uppercase tracking-[0.2em] mb-1">Manajemen Validasi</p>
                    <h2 class="text-xl font-black text-navy-900 dark:text-white">Validasi Usulan</h2>
                </div>
            </div>
            
            <div class="flex items-center gap-6">
                <div class="text-right hidden sm:block">
                    <p class="text-sm font-black text-navy-900 dark:text-white" id="mini-clock">00:00 WITA</p>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-tighter">{{ now()->translatedFormat('l, d F Y') }}</p>
                </div>
                <div class="h-8 w-[1px] bg-slate-100"></div>
                <div class="flex items-center gap-3">
                    <div class="text-right">
                        <p class="text-sm font-black text-navy-900 dark:text-white leading-none uppercase">{{ auth()->user()->name }}</p>
                        <p class="text-xs font-bold text-emerald-500 uppercase mt-1 leading-none">ONLINE</p>
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

        <!-- MAIN CONTENT -->
        <div class="p-8 space-y-8">
            
            @if(session('success'))
                <div class="p-4 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-100 dark:border-emerald-500/20 rounded-2xl flex items-center gap-3 text-emerald-600 dark:text-emerald-400 shadow-sm animate-pulse">
                    <i class="fas fa-check-circle"></i>
                    <p class="text-sm font-bold">{{ session('success') }}</p>
                </div>
            @endif

            <!-- STATS SUMMARY CARDS -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Menunggu -->
                <div class="bg-gradient-to-br from-amber-500 to-orange-600 p-6 rounded-[2.5rem] shadow-lg shadow-amber-500/30 relative overflow-hidden group border border-amber-400/50">
                    <div class="absolute -right-4 -top-4 w-32 h-32 bg-white/20 dark:bg-[#1e1b4b]/20 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-500"></div>
                    <div class="absolute right-4 bottom-4 text-white/10 group-hover:scale-110 transition-transform duration-500">
                        <i class="fas fa-clock text-6xl"></i>
                    </div>
                    <div class="relative z-10">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 bg-white/20 dark:bg-[#1e1b4b]/20 backdrop-blur-sm text-white rounded-xl flex items-center justify-center border border-white/30 shadow-inner">
                                <i class="fas fa-clock text-sm"></i>
                            </div>
                            <p class="text-xs font-black text-white/90 uppercase tracking-widest drop-shadow-sm">Menunggu Validasi</p>
                        </div>
                        <div class="flex items-end gap-2">
                            <h3 class="text-4xl font-black text-white leading-none drop-shadow-md">{{ $counts['pending'] }}</h3>
                            <span class="text-xs font-bold text-amber-100 mb-1 uppercase tracking-wider">Usulan</span>
                        </div>
                    </div>
                </div>

                <!-- Diterima -->
                <div class="bg-gradient-to-br from-[#059669] to-emerald-700 p-6 rounded-[2.5rem] shadow-lg shadow-[#059669]/30 relative overflow-hidden group border border-[#059669]/50">
                    <div class="absolute -right-4 -top-4 w-32 h-32 bg-white/20 dark:bg-[#1e1b4b]/20 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-500"></div>
                    <div class="absolute right-4 bottom-4 text-white/10 group-hover:scale-110 transition-transform duration-500">
                        <i class="fas fa-check-double text-6xl"></i>
                    </div>
                    <div class="relative z-10">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 bg-white/20 dark:bg-[#1e1b4b]/20 backdrop-blur-sm text-white rounded-xl flex items-center justify-center border border-white/30 shadow-inner">
                                <i class="fas fa-check-double text-sm"></i>
                            </div>
                            <p class="text-xs font-black text-white/90 uppercase tracking-widest drop-shadow-sm">Telah Diterima</p>
                        </div>
                        <div class="flex items-end gap-2">
                            <h3 class="text-4xl font-black text-white leading-none drop-shadow-md">{{ $counts['verified'] }}</h3>
                            <span class="text-xs font-bold text-emerald-100 mb-1 uppercase tracking-wider">Verified</span>
                        </div>
                    </div>
                </div>

                <!-- Ditolak -->
                <div class="bg-gradient-to-br from-rose-500 to-red-600 p-6 rounded-[2.5rem] shadow-lg shadow-rose-500/30 relative overflow-hidden group border border-rose-400/50">
                    <div class="absolute -right-4 -top-4 w-32 h-32 bg-white/20 dark:bg-[#1e1b4b]/20 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-500"></div>
                    <div class="absolute right-4 bottom-4 text-white/10 group-hover:scale-110 transition-transform duration-500">
                        <i class="fas fa-times-circle text-6xl"></i>
                    </div>
                    <div class="relative z-10">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 bg-white/20 dark:bg-[#1e1b4b]/20 backdrop-blur-sm text-white rounded-xl flex items-center justify-center border border-white/30 shadow-inner">
                                <i class="fas fa-times-circle text-sm"></i>
                            </div>
                            <p class="text-xs font-black text-white/90 uppercase tracking-widest drop-shadow-sm">Ditolak / Perbaikan</p>
                        </div>
                        <div class="flex items-end gap-2">
                            <h3 class="text-4xl font-black text-white leading-none drop-shadow-md">{{ $counts['rejected'] }}</h3>
                            <span class="text-xs font-bold text-rose-100 mb-1 uppercase tracking-wider">Rejected</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TABLE SECTION -->
            <div class="bg-white dark:bg-[#1e1b4b] rounded-[3rem] shadow-sm border border-slate-100 dark:border-white/10 overflow-hidden mb-10">
                <div class="px-8 py-5 border-b border-slate-50 flex flex-col md:flex-row justify-between items-center bg-white dark:bg-[#1e1b4b] gap-4">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-xl bg-gold-50 flex items-center justify-center text-gold-500 border border-gold-100">
                            <i class="fas fa-tasks"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-black text-navy-900 dark:text-white uppercase tracking-widest">Antrean Validasi</h3>
                            <p class="text-xs text-slate-400 font-bold uppercase mt-1">Daftar laporan surveyor yang menunggu keputusan</p>
                        </div>
                    </div>
                    <div>
                        <form action="{{ route('tim_teknis.validasi') }}" method="GET" class="flex items-center gap-2">
                            <label class="text-xs font-black text-slate-400 uppercase tracking-widest">Tampilan:</label>
                            <select name="show" onchange="this.form.submit()" class="text-xs font-bold text-navy-900 dark:text-white bg-slate-50 dark:bg-[#0f0e2c] border border-slate-200 dark:border-white/20 rounded-xl px-3 py-2 focus:outline-none focus:border-gold-500 focus:ring-2 focus:ring-gold-500/20 transition-all shadow-sm">
                                <option value="10" {{ request('show') != 'all' ? 'selected' : '' }}>10 Baris</option>
                                <option value="all" {{ request('show') == 'all' ? 'selected' : '' }}>Semua Data</option>
                            </select>
                        </form>
                    </div>
                </div>
                
                <!-- FILTER TABS & ADVANCED FILTER -->
                <div class="px-8 py-4 bg-white dark:bg-[#1e1b4b] border-b border-slate-100 dark:border-white/10">
                    <form action="{{ route('tim_teknis.validasi') }}" method="GET" class="flex flex-col gap-4">
                        <input type="hidden" name="show" value="{{ request('show') }}">
                        @php $currentStatus = request('status', 'Pending'); @endphp
                        <input type="hidden" name="status" value="{{ $currentStatus }}">

                        <!-- Filter Status -->
                        <div class="flex flex-wrap gap-2 mb-2">
                            <a href="{{ route('tim_teknis.validasi', array_merge(request()->query(), ['status' => 'All'])) }}" class="px-4 py-2 rounded-xl text-xs font-black uppercase tracking-widest transition-all {{ $currentStatus == 'All' ? 'bg-navy-900 text-white shadow-md' : 'bg-white dark:bg-[#1e1b4b] text-slate-400 hover:bg-slate-100 border border-slate-200 dark:border-white/20' }}">Semua Antrean</a>
                            <a href="{{ route('tim_teknis.validasi', array_merge(request()->query(), ['status' => 'Pending'])) }}" class="px-4 py-2 rounded-xl text-xs font-black uppercase tracking-widest transition-all {{ $currentStatus == 'Pending' ? 'bg-amber-500 text-white shadow-md shadow-amber-500/20' : 'bg-white dark:bg-[#1e1b4b] text-slate-400 hover:bg-slate-100 border border-slate-200 dark:border-white/20' }}">Menunggu (Pending)</a>
                            <a href="{{ route('tim_teknis.validasi', array_merge(request()->query(), ['status' => 'Validated'])) }}" class="px-4 py-2 rounded-xl text-xs font-black uppercase tracking-widest transition-all {{ $currentStatus == 'Validated' ? 'bg-emerald-500 text-white shadow-md shadow-emerald-500/20' : 'bg-white dark:bg-[#1e1b4b] text-slate-400 hover:bg-slate-100 border border-slate-200 dark:border-white/20' }}">Disetujui (Validated)</a>
                            <a href="{{ route('tim_teknis.validasi', array_merge(request()->query(), ['status' => 'Rejected'])) }}" class="px-4 py-2 rounded-xl text-xs font-black uppercase tracking-widest transition-all {{ $currentStatus == 'Rejected' ? 'bg-rose-500 text-white shadow-md shadow-rose-500/20' : 'bg-white dark:bg-[#1e1b4b] text-slate-400 hover:bg-slate-100 border border-slate-200 dark:border-white/20' }}">Ditolak / Perbaikan</a>
                        </div>

                        <!-- Advanced Filter -->
                        <div class="flex flex-wrap md:flex-nowrap gap-4 items-end bg-slate-50 dark:bg-[#0f0e2c]/50 p-4 rounded-2xl border border-slate-100 dark:border-white/10">
                            <div class="w-full md:flex-1">
                                <label class="text-xs font-black text-slate-400 uppercase tracking-widest block mb-2">Wilayah Kecamatan</label>
                                <select name="kecamatan" class="w-full bg-white dark:bg-[#1e1b4b] border border-slate-200 dark:border-white/20 rounded-xl px-4 py-2.5 text-xs font-bold text-navy-900 dark:text-white focus:outline-none focus:border-gold-500 transition-all shadow-sm">
                                    <option value="">Semua Kecamatan</option>
                                    @foreach($kecamatan as $kec)
                                        <option value="{{ $kec->id_kecamatan }}" {{ request('kecamatan') == $kec->id_kecamatan ? 'selected' : '' }}>
                                            {{ $kec->nama_kecamatan }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="w-full md:flex-1">
                                <label class="text-xs font-black text-slate-400 uppercase tracking-widest block mb-2">Mulai Tanggal</label>
                                <input type="date" name="start_date" value="{{ request('start_date') }}" class="w-full bg-white dark:bg-[#1e1b4b] border border-slate-200 dark:border-white/20 rounded-xl px-4 py-2.5 text-xs font-bold text-navy-900 dark:text-white focus:outline-none focus:border-gold-500 transition-all shadow-sm">
                            </div>
                            <div class="w-full md:flex-1">
                                <label class="text-xs font-black text-slate-400 uppercase tracking-widest block mb-2">Sampai Tanggal</label>
                                <input type="date" name="end_date" value="{{ request('end_date') }}" class="w-full bg-white dark:bg-[#1e1b4b] border border-slate-200 dark:border-white/20 rounded-xl px-4 py-2.5 text-xs font-bold text-navy-900 dark:text-white focus:outline-none focus:border-gold-500 transition-all shadow-sm">
                            </div>
                            <div class="w-full md:flex-[0.5] flex gap-2 justify-end">
                                <button type="submit" class="px-6 py-2.5 bg-navy-900 text-white rounded-xl text-xs font-black uppercase tracking-widest hover:bg-gold-500 transition-all shadow-md w-full md:w-auto">
                                    Filter
                                </button>
                                <a href="{{ route('tim_teknis.validasi', ['status' => $currentStatus]) }}" class="px-4 py-2.5 bg-white dark:bg-[#1e1b4b] text-slate-400 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-slate-100 hover:text-slate-600 transition-all border border-slate-200 dark:border-white/20 shadow-sm flex items-center justify-center">
                                    <i class="fas fa-sync-alt"></i>
                                </a>
                            </div>
                        </div>
                    </form>
                </div>


                <form id="bulkForm" action="{{ route('tim_teknis.validasi.bulk') }}" method="POST">
                    @csrf
                    <input type="hidden" name="status" id="bulkStatus" value="">
                    
                    <div id="bulkActionBar" class="px-8 py-3 bg-indigo-50 dark:bg-indigo-900/20 border-b border-indigo-100 dark:border-indigo-500/20 flex items-center gap-4 transition-all duration-300 hidden">
                        <div class="flex items-center gap-2">
                            <span class="w-6 h-6 rounded-full bg-indigo-500 text-white flex items-center justify-center text-xs font-black shadow-sm" id="selectedCount">0</span>
                            <span class="text-xs font-black text-indigo-900 dark:text-indigo-400 uppercase tracking-widest">Data Terpilih</span>
                        </div>
                        <div class="h-4 w-[2px] bg-indigo-200 rounded-full"></div>
                        <button type="button" onclick="submitBulk('Validated')" class="px-4 py-1.5 bg-emerald-500 text-white rounded-lg text-xs font-black uppercase tracking-widest hover:bg-emerald-600 transition-all shadow-sm shadow-emerald-500/30 border border-emerald-600 flex items-center gap-2 group">
                            <i class="fas fa-check-double group-hover:scale-110 transition-transform"></i> Setujui Semua
                        </button>
                        <button type="button" onclick="submitBulk('Rejected')" class="px-4 py-1.5 bg-rose-500 text-white rounded-lg text-xs font-black uppercase tracking-widest hover:bg-rose-600 transition-all shadow-sm shadow-rose-500/30 border border-rose-600 flex items-center gap-2 group">
                            <i class="fas fa-times group-hover:scale-110 transition-transform"></i> Tolak Semua
                        </button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gradient-to-r from-navy-900 to-navy-800 border-b border-navy-800 shadow-md text-xs font-black text-gold-500 uppercase tracking-widest">
                                    <th class="px-6 py-4 w-10 border-b border-navy-800 text-center">
                                        <input type="checkbox" id="selectAll" class="roun border-slate-300 text-gold-500 focus:ring-gold-500 cursor-pointer w-4 h-4">
                                    </th>
                                    <th class="px-2 py-4 w-12 border-b border-navy-800">No</th>
                                <th class="px-6 py-4 border-b border-navy-800">Infrastruktur</th>
                                <th class="px-6 py-4 border-b border-navy-800">Wilayah</th>
                                <th class="px-6 py-4 border-b border-navy-800">Surveyor</th>
                                <th class="px-6 py-4 border-b border-navy-800">Status Kondisi</th>
                                <th class="px-6 py-4 border-b border-navy-800">Status Validasi</th>
                                <th class="px-6 py-4 text-center border-b border-navy-800">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($allUsulan as $index => $item)
                            <tr class="hover:bg-slate-50 dark:bg-[#0f0e2c]/50 transition-colors group">
                                <td class="px-6 py-5 text-center">
                                    @if($item->status_validasi == 'Pending')
                                        <input type="checkbox" name="ids[]" value="{{ $item->id_infrastruktur }}" class="row-checkbox roun border-slate-300 text-gold-500 focus:ring-gold-500 cursor-pointer w-4 h-4">
                                    @else
                                        <input type="checkbox" disabled class="roun border-slate-200 dark:border-white/20 bg-slate-50 dark:bg-[#0f0e2c] cursor-not-allowed opacity-50 w-4 h-4">
                                    @endif
                                </td>
                                <td class="px-2 py-5 whitespace-nowrap text-xs font-black text-slate-300">
                                    {{ request('show') == 'all' ? sprintf('%02d', $index + 1) : sprintf('%02d', ($allUsulan->currentPage() - 1) * $allUsulan->perPage() + $index + 1) }}
                                </td>
                                <td class="px-6 py-5 min-w-[280px]">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 rounded-2xl bg-slate-100 overflow-hidden shadow-inner border border-white flex-shrink-0 flex items-center justify-center relative">
                                            @if($item->foto_terbaru)
                                                <img src="{{ asset('storage/' . $item->foto_terbaru) }}" class="w-full h-full object-cover">
                                            @else
                                                <i class="fas fa-image text-slate-300"></i>
                                            @endif
                                        </div>
                                        <div>
                                            <h4 class="text-[13px] font-black text-navy-900 dark:text-white leading-tight mb-0.5">{{ $item->nama_objek }}</h4>
                                            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">{{ $item->jenis }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-5 min-w-[150px]">
                                    <p class="text-xs font-bold text-navy-900 dark:text-white mb-0.5">{{ $item->kelurahan->nama_kelurahan ?? '-' }}</p>
                                    <p class="text-xs text-slate-400 font-bold uppercase tracking-widest">{{ $item->kelurahan->kecamatan->nama_kecamatan ?? '-' }}</p>
                                </td>
                                <td class="px-6 py-5 min-w-[150px]">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-gold-50 border border-gold-100 flex items-center justify-center text-gold-500 font-bold text-xs uppercase shadow-sm">
                                            {{ substr($item->user->name ?? 'A', 0, 1) }}
                                        </div>
                                        <p class="text-xs font-black text-navy-900 dark:text-white">{{ $item->user->name ?? 'Anonim' }}</p>
                                    </div>
                                </td>
                                <td class="px-6 py-5 min-w-[150px]">
                                    <div class="flex flex-col gap-2 items-start">
                                        @php
                                            $aiLabel = $item->analisis->label_prioritas ?? '';
                                            $aiLabelLower = strtolower($aiLabel);
                                            $aiScore = $item->analisis->skor_dt ?? null;
                                            
                                            $aiClass = 'bg-slate-50 dark:bg-[#0f0e2c] text-slate-600 border-slate-200 dark:border-white/20';
                                            if (str_contains($aiLabelLower, 'berat')) {
                                                $aiClass = 'bg-[#be123c]/10 text-[#be123c] border-[#be123c]/30';
                                            } elseif (str_contains($aiLabelLower, 'sedang') || str_contains($aiLabelLower, 'ringan')) {
                                                $aiClass = 'bg-[#d97706]/10 text-[#d97706] border-[#d97706]/30';
                                            } elseif (str_contains($aiLabelLower, 'baik')) {
                                                $aiClass = 'bg-[#059669]/10 text-[#059669] border-[#059669]/30';
                                            }
                                        @endphp
                                        <span class="px-2.5 py-1 rounded-md border text-xs font-black uppercase tracking-widest whitespace-nowrap {{ $aiClass }}">
                                            {{ $aiLabel ?: 'Belum Dianalisis' }}
                                        </span>
                                        @if($aiScore !== null)
                                            <span class="text-xs font-bold text-slate-400 uppercase flex items-center gap-1.5 tracking-widest mt-1">
                                                <i class="fas fa-chart-bar text-gold-500"></i> Skor Prioritas: {{ number_format($aiScore, 1) }}%
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-5 min-w-[140px]">
                                    <div class="flex flex-col gap-2">
                                        @php
                                            $statusClass = match($item->status_validasi) {
                                                'Validated' => 'bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 border-emerald-100 dark:border-emerald-500/20',
                                                'Rejected' => 'bg-rose-50 dark:bg-rose-900/20 text-rose-600 dark:text-rose-400 border-rose-100 dark:border-rose-500/20',
                                                default => 'bg-slate-50 dark:bg-[#0f0e2c] text-slate-600 border-slate-200 dark:border-white/20'
                                            };
                                            $statusIcon = match($item->status_validasi) {
                                                'Validated' => 'fa-check-double',
                                                'Rejected' => 'fa-times-circle',
                                                default => 'fa-clock'
                                            };
                                        @endphp
                                        <span class="px-3 py-1.5 rounded-xl text-xs font-black uppercase tracking-widest border flex items-center gap-2 w-fit {{ $statusClass }}">
                                            <i class="fas {{ $statusIcon }} text-xs"></i>
                                            {{ $item->status_validasi == 'Validated' ? 'Diterima' : ($item->status_validasi == 'Rejected' ? 'Ditolak' : 'Menunggu') }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-5 min-w-[260px]">
                                    <div class="flex items-center justify-center gap-2">
                                        {{-- Detail --}}
                                        <a href="{{ route('tim_teknis.infrastruktur.show', $item->id_infrastruktur) }}" class="flex items-center justify-center gap-2 px-3 py-2.5 bg-navy-50 text-navy-900 dark:text-white rounded-xl hover:bg-gold-500 hover:text-white transition-all border border-navy-100 shadow-sm group" title="Lihat Detail">
                                            <i class="fas fa-eye text-xs group-hover:scale-110 transition-transform"></i>
                                            <span class="text-xs font-black uppercase tracking-widest hidden 2xl:block">Detail</span>
                                        </a>

                                        {{-- ACC --}}
                                        @if($item->status_validasi == 'Pending')
                                            <form action="{{ route('tim_teknis.validasi.proses', $item->id_infrastruktur) }}" method="POST" class="flex-1" onsubmit="return promptCatatan(event, this, 'Validated')">
                                                @csrf
                                                <input type="hidden" name="status" value="Validated">
                                                <button type="submit" class="w-full flex items-center justify-center gap-2 px-3 py-2.5 bg-[#059669] text-white rounded-xl hover:bg-[#047857] transition-all shadow-lg shadow-[#059669]/20 group border border-[#059669]" title="Setujui Validasi">
                                                    <i class="fas fa-check text-xs group-hover:scale-110 transition-transform"></i>
                                                    <span class="text-xs font-black uppercase tracking-widest">ACC</span>
                                                </button>
                                            </form>
                                            
                                            {{-- Tolak --}}
                                            <form action="{{ route('tim_teknis.validasi.proses', $item->id_infrastruktur) }}" method="POST" class="flex-1" onsubmit="return promptCatatan(event, this, 'Rejected')">
                                                @csrf
                                                <input type="hidden" name="status" value="Rejected">
                                                <button type="submit" class="w-full flex items-center justify-center gap-2 px-3 py-2.5 bg-rose-50 text-rose-600 rounded-xl hover:bg-rose-500 hover:text-white transition-all border border-rose-200 shadow-sm group" title="Tolak Validasi">
                                                    <i class="fas fa-times text-xs group-hover:scale-110 transition-transform"></i>
                                                    <span class="text-xs font-black uppercase tracking-widest">Tolak</span>
                                                </button>
                                            </form>
                                        @else
                                            <button disabled class="flex-1 flex items-center justify-center gap-2 px-3 py-2.5 bg-slate-50 dark:bg-[#0f0e2c] text-slate-300 rounded-xl border border-slate-100 dark:border-white/10 cursor-not-allowed">
                                                <i class="fas fa-check text-xs"></i>
                                                <span class="text-xs font-black uppercase tracking-widest">ACC</span>
                                            </button>
                                            <button disabled class="flex-1 flex items-center justify-center gap-2 px-3 py-2.5 bg-slate-50 dark:bg-[#0f0e2c] text-slate-300 rounded-xl border border-slate-100 dark:border-white/10 cursor-not-allowed">
                                                <i class="fas fa-times text-xs"></i>
                                                <span class="text-xs font-black uppercase tracking-widest">Tolak</span>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-8 py-20 text-center">
                                    <div class="flex flex-col items-center gap-3">
                                        <div class="w-16 h-16 bg-slate-50 dark:bg-[#0f0e2c] rounded-full flex items-center justify-center text-slate-300">
                                            <i class="fas fa-clipboard-check text-2xl"></i>
                                        </div>
                                        <p class="text-sm font-bold text-slate-400 uppercase tracking-widest">Tidak ada data untuk divalidasi</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                </form>

                @if(request('show') != 'all')
                    <div class="px-8 py-4 border-t border-slate-50 bg-slate-50 dark:bg-[#0f0e2c]/10">
                        {{ $allUsulan->links() }}
                    </div>
                @endif
            </div>
        </div>

        <!-- Validation Modal -->
        <div id="validasiModal" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-slate-900/50 backdrop-blur-sm transition-all duration-300">
            <div class="bg-white dark:bg-[#1e1b4b] rounded-[2rem] shadow-2xl w-full max-w-md p-8 transform scale-95 opacity-0 transition-all duration-300" id="validasiModalContent">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-black text-navy-900 dark:text-white" id="validasiModalTitle">Validasi Data</h3>
                    <button type="button" onclick="closeValidasiModal()" class="w-8 h-8 flex items-center justify-center bg-slate-50 dark:bg-[#0f0e2c] text-slate-400 rounded-xl hover:bg-rose-50 hover:text-rose-500 transition-colors border border-slate-100 dark:border-white/10">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <p class="text-sm text-slate-500 mb-6 font-medium" id="validasiModalDesc">Silakan masukkan catatan.</p>
                
                <div class="mb-8">
                    <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Catatan / Alasan</label>
                    <textarea id="validasiCatatanInput" rows="4" class="w-full bg-slate-50 dark:bg-[#0f0e2c] border border-slate-200 dark:border-white/20 rounded-2xl p-4 text-sm font-medium text-navy-900 dark:text-white focus:outline-none focus:border-gold-500 focus:ring-4 focus:ring-gold-500/20 transition-all placeholder:text-slate-300" placeholder="Ketik catatan di sini..."></textarea>
                    <p id="validasiError" class="text-xs text-rose-500 mt-2 font-bold hidden flex items-center gap-1.5">
                        <i class="fas fa-exclamation-circle"></i> Catatan/Alasan wajib diisi untuk penolakan!
                    </p>
                </div>
                
                <div class="flex items-center justify-end gap-3">
                    <button type="button" onclick="closeValidasiModal()" class="px-5 py-2.5 bg-white dark:bg-[#1e1b4b] border border-slate-200 dark:border-white/20 text-slate-500 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-slate-50 dark:bg-[#0f0e2c] transition-colors">Batal</button>
                    <button type="button" onclick="confirmValidasiModal()" class="px-5 py-2.5 bg-navy-900 text-white rounded-xl text-xs font-black uppercase tracking-widest hover:bg-gold-500 transition-colors shadow-lg shadow-navy-900/20" id="validasiSubmitBtn">Konfirmasi</button>
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

        // Bulk Validation Logic
        const selectAll = document.getElementById('selectAll');
        const rowCheckboxes = document.querySelectorAll('.row-checkbox');
        const selectedCountEl = document.getElementById('selectedCount');
        const bulkActionBar = document.getElementById('bulkActionBar');

        function updateBulkState() {
            const checkedCount = document.querySelectorAll('.row-checkbox:checked').length;
            selectedCountEl.textContent = checkedCount;
            
            if (checkedCount > 0) {
                bulkActionBar.classList.remove('hidden');
                bulkActionBar.classList.add('flex');
            } else {
                bulkActionBar.classList.add('hidden');
                bulkActionBar.classList.remove('flex');
            }
            
            selectAll.checked = checkedCount === rowCheckboxes.length && rowCheckboxes.length > 0;
        }

        selectAll.addEventListener('change', function() {
            rowCheckboxes.forEach(cb => {
                if (!cb.disabled) cb.checked = selectAll.checked;
            });
            updateBulkState();
        });

        rowCheckboxes.forEach(cb => {
            cb.addEventListener('change', updateBulkState);
        });

        // --- Modal Logic ---
        let pendingForm = null;
        let pendingStatus = '';
        let isBulk = false;

        function openValidasiModal(formOrStatus, statusParam, bulk = false) {
            isBulk = bulk;
            if (bulk) {
                pendingStatus = statusParam;
            } else {
                pendingForm = formOrStatus;
                pendingStatus = statusParam;
            }

            const modal = document.getElementById('validasiModal');
            const content = document.getElementById('validasiModalContent');
            const title = document.getElementById('validasiModalTitle');
            const desc = document.getElementById('validasiModalDesc');
            const input = document.getElementById('validasiCatatanInput');
            const btn = document.getElementById('validasiSubmitBtn');
            const error = document.getElementById('validasiError');

            input.value = '';
            error.classList.add('hidden');

            if (pendingStatus === 'Rejected') {
                title.textContent = 'Tolak Validasi';
                title.className = 'text-xl font-black text-rose-600';
                desc.innerHTML = 'Silakan masukkan <strong>alasan penolakan</strong> (Wajib diisi).';
                btn.className = 'px-5 py-2.5 bg-rose-500 text-white rounded-xl text-xs font-black uppercase tracking-widest hover:bg-rose-600 transition-colors shadow-lg shadow-rose-500/20';
                btn.innerHTML = '<i class="fas fa-times mr-2"></i> Tolak Data';
            } else {
                title.textContent = 'Setujui Validasi';
                title.className = 'text-xl font-black text-emerald-600';
                desc.innerHTML = 'Silakan masukkan <strong>catatan persetujuan</strong> (Opsional).';
                btn.className = 'px-5 py-2.5 bg-emerald-500 text-white rounded-xl text-xs font-black uppercase tracking-widest hover:bg-emerald-600 transition-colors shadow-lg shadow-emerald-500/20';
                btn.innerHTML = '<i class="fas fa-check mr-2"></i> Setujui Data';
            }

            modal.classList.remove('hidden');
            setTimeout(() => {
                content.classList.remove('scale-95', 'opacity-0');
                content.classList.add('scale-100', 'opacity-100');
                input.focus();
            }, 10);
        }

        function closeValidasiModal() {
            const modal = document.getElementById('validasiModal');
            const content = document.getElementById('validasiModalContent');
            content.classList.remove('scale-100', 'opacity-100');
            content.classList.add('scale-95', 'opacity-0');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }

        function confirmValidasiModal() {
            const input = document.getElementById('validasiCatatanInput').value.trim();
            const error = document.getElementById('validasiError');

            if (pendingStatus === 'Rejected' && input === '') {
                error.classList.remove('hidden');
                document.getElementById('validasiCatatanInput').focus();
                return;
            }

            if (isBulk) {
                const inputAlasan = document.createElement('input');
                inputAlasan.type = 'hidden';
                inputAlasan.name = 'alasan_penolakan';
                inputAlasan.value = input;
                document.getElementById('bulkForm').appendChild(inputAlasan);
                document.getElementById('bulkStatus').value = pendingStatus;
                document.getElementById('bulkForm').submit();
            } else {
                const inputAlasan = document.createElement('input');
                inputAlasan.type = 'hidden';
                inputAlasan.name = 'alasan_penolakan';
                inputAlasan.value = input;
                pendingForm.appendChild(inputAlasan);
                pendingForm.submit();
            }
        }

        function submitBulk(status) {
            openValidasiModal(null, status, true);
        }

        function promptCatatan(e, form, status) {
            e.preventDefault();
            openValidasiModal(form, status, false);
            return false;
        }
    </script>
</body>
</html>
