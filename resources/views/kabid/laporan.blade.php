<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Laporan & Rekapitulasi | Kabid SINFRA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        @media print {
            .no-print { display: none !important; }
            .print-only { display: block !important; }
            body { background: white; }
            .flex { display: block; }
            aside { display: none; }
            main { width: 100%; margin: 0; padding: 0; }
        }
    </style>
</head>
<body class="bg-gray-50 flex h-screen overflow-hidden text-gray-800 text-left">

    @include('kabid.partials.sidebar')

    <main class="flex-1 flex flex-col h-screen overflow-hidden">
        <header class="bg-white border-b border-gray-100 px-8 py-5 flex justify-between items-center z-10 no-print">
            <div class="flex items-center gap-4">
                <a href="{{ route('kabid.dashboard') }}" class="w-10 h-10 flex items-center justify-center bg-gray-50 text-gray-400 rounded-xl hover:bg-indigo-50 hover:text-indigo-600 transition-all border border-gray-100">
                    <i class="fas fa-arrow-left text-sm"></i>
                </a>
                <div>
                    <p class="text-[10px] font-extrabold text-indigo-600 uppercase tracking-[0.2em] mb-1">Reporting Center</p>
                    <h2 class="text-xl font-black text-[#1e1b4b]">Laporan & Rekapitulasi</h2>
                </div>
            </div>
            
            <div class="flex items-center gap-6">
                <button onclick="window.print()" class="no-print px-4 py-2 bg-indigo-50 text-indigo-600 rounded-xl text-[9px] font-black uppercase tracking-widest hover:bg-indigo-100 transition-all flex items-center gap-2 border border-indigo-100">
                    <i class="fas fa-print"></i> Cetak Laporan
                </button>
                <div class="text-right hidden sm:block">
                    <p class="text-[11px] font-black text-[#1e1b4b]" id="mini-clock">00:00 WITA</p>
                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-tighter">{{ now()->translatedFormat('l, d F Y') }}</p>
                </div>
                <div class="h-8 w-[1px] bg-gray-100"></div>
                <a href="{{ route('kabid.profile') }}" class="flex items-center gap-3 group">
                    <div class="text-right">
                        <p class="text-[11px] font-black text-[#1e1b4b] leading-none uppercase group-hover:text-indigo-600 transition-colors">{{ auth()->user()->name }}</p>
                        <p class="text-[9px] font-bold text-emerald-500 uppercase mt-1 italic">ONLINE</p>
                    </div>
                    <div class="w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600 border border-indigo-100 overflow-hidden shadow-sm group-hover:border-indigo-300 group-hover:shadow-md transition-all">
                        @if(auth()->user()->profile_photo)
                            <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}" class="w-full h-full object-cover">
                        @else
                            <i class="fas fa-user-tie text-xl"></i>
                        @endif
                    </div>
                </a>
            </div>
        </header>

        <div class="flex-1 overflow-y-auto p-8 custom-scrollbar">
            <!-- Filter Section (No Print) -->
            <div class="bg-white rounded-[2rem] p-8 border border-gray-100 shadow-sm mb-8 no-print">
                <form action="{{ route('kabid.laporan') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-6 items-end">
                    <div>
                        <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest block mb-2">Wilayah Kecamatan</label>
                        <select name="kecamatan" class="w-full bg-gray-50 border border-gray-100 rounded-xl px-4 py-2.5 text-xs font-bold focus:outline-none focus:ring-2 focus:ring-indigo-500/20 transition-all">
                            <option value="">Semua Wilayah</option>
                            @foreach($kecamatan as $k)
                                <option value="{{ $k->id_kecamatan }}" {{ request('kecamatan') == $k->id_kecamatan ? 'selected' : '' }}>{{ $k->nama_kecamatan }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest block mb-2">Kondisi</label>
                        <select name="kondisi" class="w-full bg-gray-50 border border-gray-100 rounded-xl px-4 py-2.5 text-xs font-bold focus:outline-none focus:ring-2 focus:ring-indigo-500/20 transition-all">
                            <option value="">Semua Kondisi</option>
                            <option value="Baik" {{ request('kondisi') == 'Baik' ? 'selected' : '' }}>Baik</option>
                            <option value="Rusak Ringan" {{ request('kondisi') == 'Rusak Ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                            <option value="Rusak Berat" {{ request('kondisi') == 'Rusak Berat' ? 'selected' : '' }}>Rusak Berat</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest block mb-2">Jenis Infrastruktur</label>
                        <select name="jenis" class="w-full bg-gray-50 border border-gray-100 rounded-xl px-4 py-2.5 text-xs font-bold focus:outline-none focus:ring-2 focus:ring-indigo-500/20 transition-all">
                            <option value="">Semua Jenis</option>
                            <option value="Jalan" {{ request('jenis') == 'Jalan' ? 'selected' : '' }}>Jalan</option>
                            <option value="Jembatan" {{ request('jenis') == 'Jembatan' ? 'selected' : '' }}>Jembatan</option>
                            <option value="Drainase" {{ request('jenis') == 'Drainase' ? 'selected' : '' }}>Drainase</option>
                        </select>
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="flex-1 py-2.5 bg-indigo-50 text-indigo-600 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-indigo-100 transition-all">
                            Filter Data
                        </button>
                        <a href="{{ route('kabid.laporan') }}" class="px-4 py-2.5 bg-gray-50 text-gray-400 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-gray-100 transition-all flex items-center">
                            <i class="fas fa-sync-alt"></i>
                        </a>
                    </div>
                </form>
            </div>

            <!-- Print Header (Hidden on Screen) -->
            <div class="hidden print-only mb-10 text-center border-b-2 border-[#1e1b4b] pb-6">
                <h1 class="text-2xl font-black text-[#1e1b4b] uppercase tracking-tighter">Laporan Rekapitulasi Infrastruktur</h1>
                <p class="text-sm font-bold text-gray-500 mt-1 uppercase">Sistem Informasi Geospasial (GEO-SINFRA)</p>
                <div class="mt-4 flex justify-center gap-8 text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                    <span>Wilayah: {{ request('kecamatan') ? $kecamatan->find(request('kecamatan'))->nama_kecamatan : 'Semua' }}</span>
                    <span>Kondisi: {{ request('kondisi') ?: 'Semua' }}</span>
                    <span>Dicetak: {{ now()->translatedFormat('d F Y H:i') }}</span>
                </div>
            </div>

            <!-- Data Table -->
            <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm overflow-hidden">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-gray-50/50 text-[9px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-100">
                            <th class="px-6 py-4">No</th>
                            <th class="px-6 py-4">Infrastruktur</th>
                            <th class="px-6 py-4">Wilayah</th>
                            <th class="px-6 py-4 text-center">Kondisi</th>
                            <th class="px-6 py-4">Surveyor</th>
                            <th class="px-6 py-4">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($reports as $index => $item)
                        <tr class="group hover:bg-indigo-50/30 transition-all">
                            <td class="px-6 py-4 text-xs font-bold text-gray-400">{{ $index + 1 }}</td>
                            <td class="px-6 py-4">
                                <p class="text-xs font-black text-[#1e1b4b] uppercase">{{ $item->nama_infrastruktur }}</p>
                                <p class="text-[9px] text-indigo-500 font-bold uppercase mt-0.5">{{ $item->jenis_infrastruktur }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-xs font-bold text-gray-600">{{ $item->kelurahan->nama_kelurahan ?? '-' }}</p>
                                <p class="text-[9px] text-gray-400 font-bold uppercase mt-0.5">{{ $item->kelurahan->kecamatan->nama_kecamatan ?? '-' }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex justify-center">
                                    @php
                                        $color = $item->kondisi == 'Baik' ? 'emerald' : ($item->kondisi == 'Rusak Ringan' ? 'amber' : 'red');
                                    @endphp
                                    <span class="px-2 py-1 bg-{{ $color }}-50 text-{{ $color }}-600 rounded-lg text-[8px] font-black uppercase border border-{{ $color }}-100">
                                        {{ $item->kondisi }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-xs font-bold text-gray-600">{{ $item->user->name ?? 'System' }}</p>
                            </td>
                            <td class="px-6 py-4 text-xs font-bold text-gray-400">
                                {{ $item->created_at->format('d/m/Y') }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-20 text-center">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-folder-open text-gray-200 text-4xl mb-4"></i>
                                    <p class="text-xs text-gray-400 font-bold italic uppercase">Tidak ada data yang ditemukan sesuai filter.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Print Footer -->
            <div class="hidden print-only mt-20 grid grid-cols-2 text-center">
                <div></div>
                <div class="text-xs font-bold">
                    <p>Banjarmasin, {{ now()->translatedFormat('d F Y') }}</p>
                    <p class="mt-2 text-[10px] text-gray-400 uppercase tracking-widest">Mengetahui,</p>
                    <p class="mt-16 font-black uppercase text-[#1e1b4b] underline">KABID SINFRA</p>
                    <p class="text-[10px] text-gray-400 font-bold">NIP. 19850320 201001 1 005</p>
                </div>
            </div>
        </div>
    </main>

    <script>
        // No additional scripts needed for basic print functionality
    </script>
</body>
</html>
