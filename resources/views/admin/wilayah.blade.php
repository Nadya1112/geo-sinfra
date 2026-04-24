<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Wilayah | Admin SINFRA</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        .color-preview { width: 24px; height: 24px; border-radius: 6px; border: 2px solid #fff; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
    </style>
</head>
<body class="bg-gray-50 flex h-screen overflow-hidden text-gray-800 text-left">

    <aside class="w-64 bg-[#1e1b4b] text-white flex flex-col hidden md:flex shadow-2xl z-20 text-left">
        <div class="p-6 flex-1 text-left">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 mb-10 hover:opacity-80 transition-opacity group">
                <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center shadow-lg shadow-blue-500/20 group-hover:scale-110 transition-transform">
                    <i class="fas fa-city text-xs text-white"></i>
                </div>
                <span class="font-extrabold text-xl tracking-tighter uppercase text-white">GEO-SINFRA</span>
            </a>
            
            <nav class="space-y-1">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 text-gray-400 hover:text-white hover:bg-white/5 rounded-xl text-sm font-semibold transition group">
                    <i class="fas fa-home group-hover:text-blue-400"></i> Dashboard
                </a>
                <a href="{{ route('admin.users') }}" class="flex items-center gap-3 px-4 py-3 text-gray-400 hover:text-white hover:bg-white/5 rounded-xl text-sm font-semibold transition group">
                    <i class="fas fa-users-cog group-hover:text-blue-400"></i> Manajemen Pengguna
                </a>
                
                <a href="{{ route('admin.wilayah') }}" class="flex items-center gap-3 px-4 py-3 bg-blue-600 rounded-xl text-sm font-bold transition shadow-lg shadow-blue-900/20">
                    <i class="fas fa-draw-polygon"></i> Manajemen Wilayah
                </a>

                <a href="#" class="flex items-center gap-3 px-4 py-3 text-gray-400 hover:text-white hover:bg-white/5 rounded-xl text-sm font-semibold transition group">
                    <i class="fas fa-database group-hover:text-blue-400"></i> Manajemen Infrastruktur
                </a>
                <a href="{{ route('admin.peta') }}" class="flex items-center gap-3 px-4 py-3 text-gray-400 hover:text-white hover:bg-white/5 rounded-xl text-sm font-semibold transition group">
                    <i class="fas fa-map-marked-alt group-hover:text-blue-400"></i> Peta Spasial
                </a>
                <a href="{{ route('admin.statistik') }}" class="flex items-center gap-3 px-4 py-3 text-gray-400 hover:text-white hover:bg-white/5 rounded-xl text-sm font-semibold transition group">
                    <i class="fas fa-chart-bar group-hover:text-blue-400"></i> Statistik dan Laporan
                </a>
            </nav>
        </div>

        <div class="p-6 border-t border-white/5">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="flex items-center gap-3 px-4 py-3 text-red-400 hover:text-red-300 w-full text-left text-sm font-bold transition group">
                    <i class="fas fa-sign-out-alt group-hover:-translate-x-1 transition-transform"></i> Keluar Sistem
                </button>
            </form>
        </div>
    </aside>

    <main class="flex-1 flex flex-col h-screen overflow-hidden text-left">
        <header class="bg-white border-b border-gray-100 px-8 py-5 flex justify-between items-center z-10 text-left">
            <div class="flex items-center gap-4 text-left">
                <a href="{{ route('admin.dashboard') }}" 
                   class="w-10 h-10 bg-white border border-gray-100 rounded-xl flex items-center justify-center text-gray-400 hover:text-blue-600 hover:border-blue-100 hover:shadow-lg hover:shadow-blue-500/5 transition-all group"
                   title="Kembali ke Dashboard">
                    <i class="fas fa-arrow-left text-xs group-hover:-translate-x-1 transition-transform"></i>
                </a>

                <div class="text-left">
                    <p class="text-[10px] font-extrabold text-blue-600 uppercase tracking-[0.2em] mb-1">Administrator Portal</p>
                    <h2 class="text-xl font-black text-[#1e1b4b]">Manajemen Wilayah</h2>
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
                        <p class="text-[11px] font-black text-[#1e1b4b] leading-none uppercase">Admin SINFRA</p>
                        <p class="text-[9px] font-bold text-green-500 uppercase mt-1">Online</p>
                    </div>
                    <div class="w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600 border border-indigo-100">
                        <i class="fas fa-user-circle text-xl"></i>
                    </div>
                </div>
            </div>
        </header>

        <div class="flex-1 p-8 overflow-y-auto custom-scrollbar">
            
            @if(session('success'))
            <div class="mb-6 px-6 py-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-2xl flex items-center gap-3">
                <i class="fas fa-check-circle"></i>
                <p class="text-xs font-bold">{{ session('success') }}</p>
            </div>
            @endif

            @if(session('error'))
            <div class="mb-6 px-6 py-4 bg-red-50 border border-red-100 text-red-700 rounded-2xl flex items-center gap-3">
                <i class="fas fa-exclamation-triangle"></i>
                <p class="text-xs font-bold">{{ session('error') }}</p>
            </div>
            @endif

            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
                <div>
                    <h4 class="font-extrabold text-lg text-[#1e1b4b]">Daftar Master Kecamatan</h4>
                    <p class="text-xs text-gray-400 font-medium text-left">Kelola data wilayah batas pemetaan (GeoJSON) dan Zonasi Warna</p>
                </div>
                
                <div class="flex flex-wrap items-center gap-3 w-full md:w-auto">
                    <form action="{{ route('admin.wilayah') }}" method="GET" class="flex items-center flex-1 md:w-80">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama kecamatan..." class="flex-1 pl-6 pr-4 py-2.5 bg-white border border-gray-100 rounded-l-2xl text-xs font-semibold focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all shadow-sm">
                        <button type="submit" class="bg-white border-y border-r border-gray-100 px-5 py-2.5 rounded-r-2xl hover:bg-gray-50 transition-all shadow-sm group">
                            <i class="fas fa-search text-gray-400 group-hover:text-blue-600 transition-colors text-xs"></i>
                        </button>
                    </form>

                    <a href="{{ route('admin.wilayah.create') }}" class="bg-blue-600 text-white text-xs px-6 py-2.5 rounded-2xl font-bold shadow-lg shadow-blue-200 hover:bg-blue-700 transition flex items-center gap-2 whitespace-nowrap">
                        <i class="fas fa-plus text-[10px]"></i> Tambah Wilayah
                    </a>
                </div>
            </div>

            <div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-sm overflow-hidden">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50 border-b border-gray-100">
                            <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">ID / Kode</th>
                            <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Nama Kecamatan</th>
                            <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Zonasi Warna</th>
                            <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Status Peta (GeoJSON)</th>
                            <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($wilayah as $wly)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="px-8 py-5">
                                <span class="px-3 py-1.5 bg-gray-100 text-gray-600 rounded-lg text-xs font-bold font-mono">
                                    {{ $wly->id_kecamatan }}
                                </span>
                            </td>
                            <td class="px-8 py-5">
                                <p class="text-xs font-black text-[#1e1b4b] uppercase leading-none">Kec. {{ $wly->nama_kecamatan }}</p>
                            </td>
                            <td class="px-8 py-5 text-center">
                                <div class="flex justify-center">
                                    <div class="color-preview" style="background-color: {{ $wly->warna ?? '#cbd5e1' }};" title="{{ $wly->warna ?? 'Tidak diatur' }}"></div>
                                </div>
                            </td>
                            <td class="px-8 py-5 text-center">
                                @if($wly->geometri)
                                    <span class="px-3 py-1 bg-emerald-50 text-emerald-600 border border-emerald-100 rounded-full text-[10px] font-bold flex items-center justify-center gap-1 w-max mx-auto">
                                        <i class="fas fa-check-circle"></i> Tersedia
                                    </span>
                                @else
                                    <span class="px-3 py-1 bg-red-50 text-red-500 border border-red-100 rounded-full text-[10px] font-bold flex items-center justify-center gap-1 w-max mx-auto">
                                        <i class="fas fa-times-circle"></i> Kosong
                                    </span>
                                @endif
                            </td>
                            <td class="px-8 py-5">
                                <div class="flex gap-2">
                                    <a href="{{ route('admin.wilayah.edit', $wly->id_kecamatan) }}" title="Edit Wilayah" class="w-8 h-8 bg-gray-50 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition flex items-center justify-center">
                                        <i class="fas fa-edit text-[10px]"></i>
                                    </a>
                                    
                                    <form action="{{ route('admin.wilayah.destroy', $wly->id_kecamatan) }}" method="POST" class="inline-block m-0 p-0" onsubmit="return confirm('PERINGATAN!\n\nApakah Anda yakin ingin menghapus Kecamatan {{ $wly->nama_kecamatan }}?\nData surveyor yang ditugaskan di wilayah ini mungkin akan terdampak.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="Hapus Wilayah" class="w-8 h-8 bg-gray-50 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition flex items-center justify-center">
                                            <i class="fas fa-trash text-[10px]"></i>
                                        </button>
                                    </form>