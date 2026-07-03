<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Laporan & Rekapitulasi | Tim Teknis SINFRA</title>
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        @media print {
            @page { margin: 0; size: A4 portrait; }
            html, body { height: auto !important; overflow: visible !important; background: white; color: black; font-family: 'Times New Roman', Times, serif; font-size: 11pt; padding: 0.5cm 1cm 1cm 1cm !important; margin: 0 !important; }
            .no-print { display: none !important; }
            .print-only { display: block !important; }
            .print\:grid { display: grid !important; }
            .no-break { page-break-inside: avoid; }
            aside, header { display: none !important; }
            main { width: 100%; margin: 0; padding: 0; height: auto !important; overflow: visible !important; display: block !important; }
            
            /* Table Formatting for Formal Document */
            .print-no-style { background: transparent !important; box-shadow: none !important; border: none !important; border-radius: 0 !important; }
            .rounded-\[2rem\] { border-radius: 0 !important; }
            table { border-collapse: collapse !important; width: 100% !important; border: 1px solid black !important; table-layout: fixed !important; }
            th, td { border: 1px solid black !important; padding: 8px !important; color: black !important; font-size: 11pt !important; word-wrap: break-word !important; }
            th { font-weight: bold !important; text-align: center !important; background-color: #f3f4f6 !important; }
            .badge-print { border: none !important; background: transparent !important; padding: 0 !important; }
            
            .custom-scrollbar, .overflow-y-auto, .overflow-hidden { overflow: visible !important; height: auto !important; max-height: none !important; }
            .p-8 { padding: 0 !important; }
            .mt-6 { margin-top: 15px !important; }
            .ttd-box {
                display: block !important;
                margin-top: 15px;
                text-align: right;
                font-family: 'Times New Roman', Times, serif;
                font-size: 11pt;
                page-break-inside: avoid;
            }
            .ttd-inner {
                display: inline-block;
                text-align: center;
                width: 260px;
                line-height: 1.5;
            }
            .print-tfoot-only {
                display: table-row-group !important;
            }
        }
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    </style>
<style>
    
    
@media (max-width: 767px) { html { font-size: 12px; } }
</style>
</head>
<body class="bg-slate-50 dark:bg-[#0f0e2c] flex h-screen overflow-hidden text-slate-800 dark:text-white text-left font-sans dark:bg-navy-950 transition-colors duration-300">

    @include('tim_teknis.partials.sidebar')

    <main class="flex-1 flex flex-col h-screen overflow-hidden text-left font-sans relative">
        <header class="bg-white dark:bg-[#1e1b4b] border-b border-slate-100 dark:border-white/10 px-4 pl-20 md:px-8 py-4 md:py-5 flex justify-between items-center z-10 no-print sticky top-0">
            <div class="flex items-center gap-4 min-w-0">
                <a href="{{ route('tim_teknis.dashboard') }}" class="w-10 h-10 flex items-center justify-center bg-slate-50 dark:bg-[#0f0e2c] text-slate-400 rounded-xl hover:bg-gold-50 hover:text-gold-500 transition-all border border-slate-100 dark:border-white/10 hidden md:flex">
                    <i class="fas fa-arrow-left text-sm"></i>
                </a>
                <div class="min-w-0">
                    <p class="text-[9px] md:text-xs font-extrabold text-gold-500 uppercase tracking-[0.2em] mb-0.5 md:mb-1 truncate">Reporting Center</p>
                    <h2 class="text-sm md:text-xl font-black text-navy-900 dark:text-white leading-tight whitespace-normal">Laporan & Rekapitulasi</h2>
                </div>
            </div>
            
            <div class="flex items-center gap-3 md:gap-6 flex-shrink-0">
                <div class="text-right">
                    <p class="text-[10px] md:text-xs font-black text-navy-900 dark:text-white mt-1" id="mini-clock">00:00 WITA</p>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-tighter hidden md:block">{{ now()->translatedFormat('l, d F Y') }}</p>
                </div>
                <div class="h-6 md:h-8 w-[1px] bg-slate-200 dark:bg-white/10"></div>
                <a href="{{ route('tim_teknis.profile') }}" class="flex items-center gap-2 md:gap-3 group">
                    <div class="text-right">
                        <p class="text-sm font-black text-navy-900 dark:text-white leading-none uppercase group-hover:text-gold-500 transition-colors max-w-[200px] truncate hidden md:block">{{ auth()->user()->name }}</p>
                        <p class="text-[8px] md:text-xs font-bold text-emerald-500 uppercase md:mt-0.5">ONLINE</p>
                    </div>
                    <div class="w-8 h-8 md:w-10 md:h-10 bg-navy-900 rounded-xl flex items-center justify-center text-gold-500 shadow-md group-hover:shadow-lg transition-all overflow-hidden shrink-0">
                        @if(auth()->user()->profile_photo)
                            <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}" class="w-full h-full object-cover">
                        @else
                            <i class="fas fa-user-circle text-lg md:text-xl"></i>
                        @endif
                    </div>
                </a>
            </div>
        </header>

        <div class="flex-1 overflow-y-auto p-8 custom-scrollbar">
            <!-- Summary Cards (No Print) -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-8 no-print">
                <!-- Total Laporan -->
                <div class="relative overflow-hidden rounded-[2rem] p-6 shadow-xl shadow-blue-500/20 hover:-translate-y-1 transition-transform bg-gradient-to-br from-blue-500 to-blue-700">
                    <i class="fas fa-layer-group absolute -right-4 -bottom-4 text-7xl text-white opacity-10"></i>
                    <div class="relative z-10 flex flex-col justify-between h-full">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 rounded-[0.8rem] bg-white/20 dark:bg-[#1e1b4b]/20 backdrop-blur-sm flex items-center justify-center text-white border border-white/10 shadow-inner">
                                <i class="fas fa-layer-group text-sm"></i>
                            </div>
                            <p class="text-xs font-black text-white uppercase tracking-widest mt-1">Total Laporan</p>
                        </div>
                        <div class="flex items-end gap-2">
                            <h3 class="text-4xl font-black text-white leading-none">{{ $totalLaporan }}</h3>
                            <span class="text-xs font-bold text-white/80 uppercase mb-1">Data</span>
                        </div>
                    </div>
                </div>

                <!-- Kondisi Baik -->
                <div class="relative overflow-hidden rounded-[2rem] p-6 shadow-xl shadow-emerald-500/20 hover:-translate-y-1 transition-transform bg-gradient-to-br from-emerald-400 to-emerald-600">
                    <i class="fas fa-check-double absolute -right-4 -bottom-4 text-7xl text-white opacity-10"></i>
                    <div class="relative z-10 flex flex-col justify-between h-full">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 rounded-[0.8rem] bg-white/20 dark:bg-[#1e1b4b]/20 backdrop-blur-sm flex items-center justify-center text-white border border-white/10 shadow-inner">
                                <i class="fas fa-check text-sm"></i>
                            </div>
                            <p class="text-xs font-black text-white uppercase tracking-widest mt-1">Kondisi Baik</p>
                        </div>
                        <div class="flex items-end gap-2">
                            <h3 class="text-4xl font-black text-white leading-none">{{ $totalBaik }}</h3>
                            <span class="text-xs font-bold text-white/80 uppercase mb-1">Lokasi</span>
                        </div>
                    </div>
                </div>

                <!-- Kondisi Sedang -->
                <div class="relative overflow-hidden rounded-[2rem] p-6 shadow-xl shadow-amber-500/20 hover:-translate-y-1 transition-transform bg-gradient-to-br from-amber-400 to-orange-500">
                    <i class="fas fa-exclamation-triangle absolute -right-4 -bottom-4 text-7xl text-white opacity-10"></i>
                    <div class="relative z-10 flex flex-col justify-between h-full">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 rounded-[0.8rem] bg-white/20 dark:bg-[#1e1b4b]/20 backdrop-blur-sm flex items-center justify-center text-white border border-white/10 shadow-inner">
                                <i class="fas fa-exclamation text-sm"></i>
                            </div>
                            <p class="text-xs font-black text-white uppercase tracking-widest mt-1">Kondisi Sedang</p>
                        </div>
                        <div class="flex items-end gap-2">
                            <h3 class="text-4xl font-black text-white leading-none">{{ $totalSedang }}</h3>
                            <span class="text-xs font-bold text-white/80 uppercase mb-1">Lokasi</span>
                        </div>
                    </div>
                </div>

                <!-- Kondisi Berat -->
                <div class="relative overflow-hidden rounded-[2rem] p-6 shadow-xl shadow-rose-500/20 hover:-translate-y-1 transition-transform bg-gradient-to-br from-rose-500 to-rose-600">
                    <i class="fas fa-times-circle absolute -right-4 -bottom-4 text-7xl text-white opacity-10"></i>
                    <div class="relative z-10 flex flex-col justify-between h-full">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 rounded-[0.8rem] bg-white/20 dark:bg-[#1e1b4b]/20 backdrop-blur-sm flex items-center justify-center text-white border border-white/10 shadow-inner">
                                <i class="fas fa-times text-sm"></i>
                            </div>
                            <p class="text-xs font-black text-white uppercase tracking-widest mt-1">Kondisi Berat</p>
                        </div>
                        <div class="flex items-end gap-2">
                            <h3 class="text-4xl font-black text-white leading-none">{{ $totalBerat }}</h3>
                            <span class="text-xs font-bold text-white/80 uppercase mb-1">Lokasi</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter Section (No Print) -->
            <div class="bg-white dark:bg-[#1e1b4b] rounded-[2rem] p-8 border border-slate-100 dark:border-white/10 shadow-sm mb-8 no-print">
                <form id="filterForm" action="{{ route('tim_teknis.laporan') }}" method="GET" class="flex flex-col gap-6">
                    <input type="hidden" name="show" value="{{ request('show') }}">
                    
                    <div class="flex flex-wrap md:flex-nowrap gap-6 items-end">
                        <div class="w-full md:flex-1">
                            <label class="text-xs font-black text-slate-400 uppercase tracking-widest block mb-2">Cari Nama</label>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Ketik infrastruktur..." class="w-full bg-slate-50 dark:bg-[#0f0e2c] border border-slate-100 dark:border-white/10 rounded-xl px-4 py-2.5 text-xs font-bold text-navy-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-gold-500/20 focus:border-gold-500 transition-all">
                        </div>
                        <div class="w-full md:flex-1">
                            <label class="text-xs font-black text-slate-400 uppercase tracking-widest block mb-2">Wilayah</label>
                            <select name="kecamatan" class="w-full bg-slate-50 dark:bg-[#0f0e2c] border border-slate-100 dark:border-white/10 rounded-xl px-4 py-2.5 text-xs font-bold text-navy-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-gold-500/20 focus:border-gold-500 transition-all">
                                <option value="">Semua Kecamatan</option>
                                @foreach($kecamatan as $kec)
                                    <option value="{{ $kec->id_kecamatan }}" {{ request('kecamatan') == $kec->id_kecamatan ? 'selected' : '' }}>
                                        {{ $kec->nama_kecamatan }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="w-full md:flex-1">
                            <label class="text-xs font-black text-slate-400 uppercase tracking-widest block mb-2">Kondisi</label>
                            <select name="kondisi" class="w-full bg-slate-50 dark:bg-[#0f0e2c] border border-slate-100 dark:border-white/10 rounded-xl px-4 py-2.5 text-xs font-bold text-navy-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-gold-500/20 focus:border-gold-500 transition-all">
                                <option value="">Semua Kondisi</option>
                                <option value="Baik" {{ request('kondisi') == 'Baik' ? 'selected' : '' }}>Baik</option>
                                <option value="Rusak Sedang" {{ request('kondisi') == 'Rusak Sedang' ? 'selected' : '' }}>Rusak Sedang</option>
                                <option value="Rusak Berat" {{ request('kondisi') == 'Rusak Berat' ? 'selected' : '' }}>Rusak Berat</option>
                            </select>
                        </div>
                        <div class="w-full md:flex-1">
                            <label class="text-xs font-black text-slate-400 uppercase tracking-widest block mb-2">Infrastruktur</label>
                            <select name="jenis" class="w-full bg-slate-50 dark:bg-[#0f0e2c] border border-slate-100 dark:border-white/10 rounded-xl px-4 py-2.5 text-xs font-bold text-navy-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-gold-500/20 focus:border-gold-500 transition-all">
                                <option value="">Semua Infrastruktur</option>
                                <option value="Jalan" {{ request('jenis') == 'Jalan' ? 'selected' : '' }}>Jalan</option>
                                <option value="Titian" {{ request('jenis') == 'Titian' ? 'selected' : '' }}>Titian</option>
                                <option value="Jembatan" {{ request('jenis') == 'Jembatan' ? 'selected' : '' }}>Jembatan</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex flex-wrap md:flex-nowrap gap-6 items-end">
                        <div class="w-full md:flex-1">
                            <label class="text-xs font-black text-slate-400 uppercase tracking-widest block mb-2">Mulai Tanggal</label>
                            <input type="date" name="start_date" value="{{ request('start_date') }}" class="w-full bg-slate-50 dark:bg-[#0f0e2c] border border-slate-100 dark:border-white/10 rounded-xl px-4 py-2.5 text-xs font-bold text-navy-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-gold-500/20 focus:border-gold-500 transition-all">
                        </div>
                        <div class="w-full md:flex-1">
                            <label class="text-xs font-black text-slate-400 uppercase tracking-widest block mb-2">Sampai Tanggal</label>
                            <input type="date" name="end_date" value="{{ request('end_date') }}" class="w-full bg-slate-50 dark:bg-[#0f0e2c] border border-slate-100 dark:border-white/10 rounded-xl px-4 py-2.5 text-xs font-bold text-navy-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-gold-500/20 focus:border-gold-500 transition-all">
                        </div>
                        <div class="w-full md:flex-1 flex gap-2 justify-end">
                            <button type="submit" class="px-6 py-2.5 bg-navy-900 text-white rounded-xl text-xs font-black uppercase tracking-widest hover:bg-gold-500 transition-all shadow-lg shadow-navy-900/10">
                                Filter Data
                            </button>
                            <a href="{{ route('tim_teknis.laporan') }}" class="px-4 py-2.5 bg-slate-50 dark:bg-[#0f0e2c] text-slate-400 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-slate-100 hover:text-slate-600 transition-all flex items-center border border-slate-100 dark:border-white/10 shadow-sm" title="Reset Filter">
                                <i class="fas fa-sync-alt"></i>
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Print Header (Kop Surat Dinas) -->
            <div id="kopSurat" class="hidden print-only mb-6 pb-4" style="border-bottom: 4px double black;">
                <div class="flex items-center gap-6" style="display: flex; align-items: center; justify-content: center;">
                    <img src="{{ asset('logo_dinas.jpeg') }}" style="width: 80px; height: auto;" alt="Logo Instansi">
                    <div style="text-align: center;">
                        <h2 style="font-size: 14pt; font-weight: bold; text-transform: uppercase; margin: 0; line-height: 1.3;">Dinas Perumahan Rakyat dan Kawasan Permukiman Kota Banjarmasin</h2>
                        <p style="font-size: 10pt; margin: 0; line-height: 1.5;">Jalan R.E Martadinata No. 1 Blok B Lantai 2 Kec. Banjarmasin Tengah, Kota Banjarmasin Kalimantan Selatan - 70111</p>
                        <p style="font-size: 10pt; margin: 0; line-height: 1.5;">Telepon: (0511) 3365592| Email:  ampihkumuh@gmail.com</p>
                    </div>
                </div>
            </div>

            <!-- Print Document Title -->
            <div id="docTitle" class="hidden print-only mb-8" style="text-align: center;">
                <h3 style="font-size: 14pt; font-weight: bold; text-transform: uppercase; text-decoration: underline; margin-bottom: 12px;">Laporan Rekapitulasi Kondisi Infrastruktur</h3>
                <div style="font-size: 10pt; display: flex; justify-content: flex-start; gap: 60px; padding-top: 8px; margin-top: 8px;">
                    @if(request('start_date') && request('end_date'))
                    <span><strong>Periode:</strong> {{ \Carbon\Carbon::parse(request('start_date'))->translatedFormat('d M Y') }} &ndash; {{ \Carbon\Carbon::parse(request('end_date'))->translatedFormat('d M Y') }}</span>
                    @endif
                </div>
            </div>

            <!-- TABLE SECTION -->
            <div class="print-no-style bg-white dark:bg-[#1e1b4b] rounded-[2rem] border border-slate-100 dark:border-white/10 shadow-sm overflow-hidden mt-6">
                <!-- Header with Tampilan Dropdown -->
                <div class="px-8 py-6 border-b border-slate-50 flex justify-between items-center bg-slate-50 dark:bg-[#0f0e2c]/30 no-print">
                    <div>
                        <h3 class="text-sm font-black text-navy-900 dark:text-white uppercase tracking-widest">Data Laporan</h3>
                        <p class="text-xs text-slate-400 font-bold uppercase mt-1">Hasil filter rekapitulasi data</p>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="flex items-center gap-2 mr-2 border-r border-slate-200 dark:border-white/20 pr-4">
                            <button onclick="printAllData()" class="no-print px-4 py-2 bg-rose-50 text-rose-600 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-rose-100 hover:scale-[1.02] transition-all flex items-center gap-2 border border-rose-100 shadow-sm">
                                <i class="fas fa-file-pdf"></i> Cetak PDF
                            </button>
                            <button onclick="exportAllDataToExcel()" class="no-print px-4 py-2 bg-emerald-50 text-emerald-600 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-emerald-100 hover:scale-[1.02] transition-all flex items-center gap-2 border border-emerald-100 shadow-sm">
                                <i class="fas fa-file-excel"></i> Export Excel
                            </button>
                        </div>
                        <form action="{{ route('tim_teknis.laporan') }}" method="GET" class="flex items-center gap-2">
                            @foreach(request()->except('show') as $key => $value)
                                @if(is_array($value))
                                    @foreach($value as $v)
                                        <input type="hidden" name="{{ $key }}[]" value="{{ $v }}">
                                    @endforeach
                                @else
                                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                @endif
                            @endforeach
                            <label class="text-xs font-black text-slate-400 uppercase tracking-widest">Tampilan Cetak:</label>
                            <select name="show" onchange="this.form.submit()" class="pl-4 pr-10 py-2 bg-white dark:bg-[#0f0e2c] border border-slate-200 dark:border-white/10 rounded-xl text-xs font-bold text-navy-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-gold-500 appearance-none shadow-sm cursor-pointer">
                                <option value="15" {{ request('show') == '15' || !request('show') ? 'selected' : '' }}>1 Halaman (±15 Baris)</option>
                                <option value="30" {{ request('show') == '30' ? 'selected' : '' }}>2 Halaman (±30 Baris)</option>
                                <option value="45" {{ request('show') == '45' ? 'selected' : '' }}>3 Halaman (±45 Baris)</option>
                                <option value="75" {{ request('show') == '75' ? 'selected' : '' }}>5 Halaman (±75 Baris)</option>
                                <option value="150" {{ request('show') == '150' ? 'selected' : '' }}>10 Halaman (±150 Baris)</option>
                                <option value="all" {{ request('show') == 'all' ? 'selected' : '' }}>Semua Halaman</option>
                            </select>
                        </form>
                    </div>
                </div>
                @if(request('search') || request('kecamatan') || request('kondisi') || request('jenis'))
                <div class="bg-navy-50/50 px-6 py-4 border-b border-navy-100/50 flex flex-wrap items-center gap-3 no-print">
                    <span class="text-xs font-black text-navy-400 uppercase tracking-widest mr-2">Filter Aktif:</span>
                    @if(request('search'))
                        <span class="px-3 py-1 bg-white dark:bg-[#1e1b4b] text-navy-600 rounded-full text-xs font-bold shadow-sm border border-navy-100">
                            <i class="fas fa-search mr-1"></i> "{{ request('search') }}"
                        </span>
                    @endif
                    @if(request('kecamatan'))
                        <span class="px-3 py-1 bg-white dark:bg-[#1e1b4b] text-navy-600 rounded-full text-xs font-bold shadow-sm border border-navy-100">
                            <i class="fas fa-map-marker-alt mr-1"></i> {{ $kecamatan->find(request('kecamatan'))->nama_kecamatan ?? 'Wilayah' }}
                        </span>
                    @endif
                    @if(request('kondisi'))
                        <span class="px-3 py-1 bg-white dark:bg-[#1e1b4b] text-navy-600 rounded-full text-xs font-bold shadow-sm border border-navy-100">
                            <i class="fas fa-clipboard-list mr-1"></i> {{ request('kondisi') }}
                        </span>
                    @endif
                    @if(request('jenis'))
                        <span class="px-3 py-1 bg-white dark:bg-[#1e1b4b] text-navy-600 rounded-full text-xs font-bold shadow-sm border border-navy-100">
                            <i class="fas fa-layer-group mr-1"></i> {{ request('jenis') }}
                        </span>
                    @endif
                    <a href="{{ route('tim_teknis.laporan') }}" class="ml-auto text-xs font-bold text-red-400 hover:text-red-600 transition-all">
                        <i class="fas fa-times mr-1"></i> Hapus Filter
                    </a>
                </div>
                @endif
                <table id="laporanTable" class="w-full text-left">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-[#0f0e2c]/50 text-xs font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 dark:border-white/10">
                            <th class="px-6 py-4 text-center border-b border-slate-100 dark:border-white/10" style="width: 10%;">No</th>
                            <th class="px-6 py-4 text-center border-b border-slate-100 dark:border-white/10" style="width: 30%;">Infrastruktur</th>
                            <th class="px-6 py-4 text-center border-b border-slate-100 dark:border-white/10" style="width: 20%;">Wilayah</th>
                            <th class="px-6 py-4 text-center border-b border-slate-100 dark:border-white/10" style="width: 20%;">Kondisi</th>
                            <th class="px-6 py-4 text-center border-b border-slate-100 dark:border-white/10" style="width: 20%;">Tanggal Data</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($reports as $index => $item)
                        <tr class="group hover:bg-slate-50 dark:bg-[#0f0e2c]/50 transition-all">
                            <td class="px-6 py-3 text-xs font-bold text-slate-400 text-center">{{ request('show') == 'all' ? $index + 1 : ($reports->currentPage() - 1) * $reports->perPage() + $index + 1 }}</td>
                            <td class="px-6 py-3">
                                <span class="text-xs font-black text-navy-900 dark:text-white uppercase">{{ $item->nama_objek }}</span><br style="mso-data-placement:same-cell;">
                                <span class="text-xs text-slate-400 font-bold uppercase">{{ $item->jenis }}</span>
                            </td>
                            <td class="px-6 py-3">
                                <span class="text-xs font-bold text-navy-900 dark:text-white">{{ $item->kelurahan->nama_kelurahan ?? '-' }}</span><br style="mso-data-placement:same-cell;">
                                <span class="text-xs text-slate-400 font-bold uppercase">{{ $item->kelurahan->kecamatan->nama_kecamatan ?? '-' }}</span>
                            </td>
                            <td class="px-6 py-3">
                                <div class="flex justify-center">
                                    @php
                                        $aiLabel = $item->analisis->label_prioritas ?? '';
                                        $aiLabelLower = strtolower($aiLabel);
                                        
                                        $condClass = 'bg-slate-50 dark:bg-[#0f0e2c] text-slate-600 border-slate-200 dark:border-white/20';
                                        if (str_contains($aiLabelLower, 'berat')) {
                                            $condClass = 'bg-[#be123c]/10 text-[#be123c] border-[#be123c]/30';
                                        } elseif (str_contains($aiLabelLower, 'sedang') || str_contains($aiLabelLower, 'ringan')) {
                                            $condClass = 'bg-[#d97706]/10 text-[#d97706] border-[#d97706]/30';
                                        } elseif (str_contains($aiLabelLower, 'baik')) {
                                            $condClass = 'bg-[#059669]/10 text-[#059669] border-[#059669]/30';
                                        }
                                    @endphp
                                    <span class="px-2.5 py-1 rounded-md text-xs font-black uppercase border tracking-widest badge-print {{ $condClass }}">
                                        {{ $aiLabel ?: 'Belum Dianalisis' }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-3 text-center text-xs font-bold text-slate-400">
                                {{ $item->created_at->format('d/m/Y') }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-20 text-center">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-folder-open text-slate-200 text-4xl mb-4"></i>
                                    <p class="text-xs text-slate-400 font-bold italic uppercase">Tidak ada data yang ditemukan sesuai filter.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse

                    </tbody>
                    <tfoot class="print-tfoot-only" style="display: none;">
                        <tr>
                            <td colspan="5" style="border: none !important; padding-top: 40px !important;">
                                <div style="float: right; text-align: center; width: 260px; font-family: 'Times New Roman', Times, serif; font-size: 11pt; page-break-inside: avoid;">
                                    <p style="margin-bottom: 4px;">Banjarmasin, {{ now()->translatedFormat('d F Y') }}</p>
                                    <p style="margin-bottom: 60px;">Mengetahui,<br><strong>Koordinator Tim Teknis</strong></p>
                                    <p style="margin: 0; font-weight: bold; text-decoration: underline;">HIZBULWATHONI, S.T.</p>
                                    <p style="margin: 0;">NIP. 19760814 200604 1 008</p>
                                </div>
                            </td>
                        </tr>
                    </tfoot>
                </table>

                
                @if(request('show') != 'all' && isset($reports) && $reports instanceof \Illuminate\Pagination\LengthAwarePaginator)
                    <div class="px-8 py-4 border-t border-slate-50 bg-slate-50 dark:bg-[#0f0e2c]/10 no-print">
                        {{ $reports->links() }}
                    </div>
                @endif
            </div>

        </div>
    </main>

    <script>
        function updateClock() {
            const now = new Date();
            document.getElementById('mini-clock').textContent = `${String(now.getHours()).padStart(2, '0')}:${String(now.getMinutes()).padStart(2, '0')} WITA`;
        }
        setInterval(updateClock, 1000); updateClock();

        function exportTableToExcel(filename) {
            var kopHTML = document.getElementById("kopSurat").outerHTML;
            var titleHTML = document.getElementById("docTitle").outerHTML;
            var tableHTML = document.getElementById("laporanTable").outerHTML;
            
            // Clean up 'hidden' class so it renders in Excel
            kopHTML = kopHTML.replace(/hidden print-only/g, "");
            titleHTML = titleHTML.replace(/hidden print-only/g, "");
            tableHTML = tableHTML.replace(/hidden print-only/g, "");
            
            // Bungkus tabel HTML dengan format meta khusus Excel agar bisa dibaca sebagai .xls
            var htmlTemplate = `
                <html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">
                <head>
                    <meta charset="UTF-8">
                    <!--[if gte mso 9]>
                    @verbatim
                    <xml>
                        <x:ExcelWorkbook>
                            <x:ExcelWorksheets>
                                <x:ExcelWorksheet>
                                    <x:Name>Data Laporan</x:Name>
                                    <x:WorksheetOptions>
                                        <x:DisplayGridlines/>
                                    </x:WorksheetOptions>
                                </x:ExcelWorksheet>
                            </x:ExcelWorksheets>
                        </x:ExcelWorkbook>
                    </xml>
                    @endverbatim
                    <![endif]-->
                <style>
    
    
</style>
</head>
                <body>
                    ${kopHTML}
                    ${titleHTML}
                    ${tableHTML}
                </body>
                </html>
            `;
            
            var blob = new Blob([htmlTemplate], {
                type: "application/vnd.ms-excel;charset=utf-8"
            });
            
            var downloadLink = document.createElement("a");
            downloadLink.href = window.URL.createObjectURL(blob);
            downloadLink.download = filename;
            document.body.appendChild(downloadLink);
            downloadLink.click();
            document.body.removeChild(downloadLink);
        }

        function printAllData() {
            const form = document.getElementById('filterForm');
            if (form) {
                // If there's an existing print param, remove it
                const oldPrint = form.querySelector('input[name="print"]');
                if (oldPrint) oldPrint.remove();
                
                const printInput = document.createElement('input');
                printInput.type = 'hidden';
                printInput.name = 'print';
                printInput.value = 'true';
                form.appendChild(printInput);

                form.submit();
            } else {
                const url = new URL(window.location.href);
                url.searchParams.set('print', 'true');
                window.location.href = url.toString();
            }
        }

        function exportAllDataToExcel() {
            const form = document.getElementById('filterForm');
            if (form) {
                const oldExport = form.querySelector('input[name="autoExportExcel"]');
                if (oldExport) oldExport.remove();
                
                const exportInput = document.createElement('input');
                exportInput.type = 'hidden';
                exportInput.name = 'autoExportExcel';
                exportInput.value = 'true';
                form.appendChild(exportInput);

                form.submit();
            } else {
                const url = new URL(window.location.href);
                url.searchParams.set('autoExportExcel', 'true');
                window.location.href = url.toString();
            }
        }

        // Jika URL punya parameter print=true, otomatis panggil window.print()
        @if(request('print') == 'true')
            window.addEventListener('load', function() {
                setTimeout(function() {
                    window.print();
                    
                    const cleanUrl = new URL(window.location.href);
                    cleanUrl.searchParams.delete('print');
                    window.history.replaceState({}, document.title, cleanUrl.toString());
                }, 500); 
            });
        @endif

        // Jika URL punya parameter autoExportExcel=true
        @if(request('autoExportExcel') == 'true')
            window.addEventListener('load', function() {
                setTimeout(function() {
                    exportTableToExcel('Laporan-Infrastruktur-{{ date("Y-m-d") }}.xls');
                    
                    const cleanUrl = new URL(window.location.href);
                    cleanUrl.searchParams.delete('autoExportExcel');
                    window.history.replaceState({}, document.title, cleanUrl.toString());
                }, 500); 
            });
        @endif
    </script>
</body>
</html>
