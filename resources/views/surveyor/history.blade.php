<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Data Saya | GEO-SINFRA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style> body { font-family: 'Plus Jakarta Sans', sans-serif; } </style>
</head>
<body class="bg-gray-50 flex h-screen overflow-hidden text-gray-800 text-left">

    @include('surveyor.partials.sidebar')

    <main class="flex-1 flex flex-col h-screen overflow-y-auto">
        <header class="bg-white border-b border-gray-100 px-8 py-5 flex justify-between items-center z-10">
            <div class="flex items-center gap-4">
                <a href="{{ route('surveyor.dashboard') }}" class="w-10 h-10 flex items-center justify-center bg-gray-50 text-gray-400 rounded-xl hover:bg-emerald-50 hover:text-emerald-600 transition-all border border-gray-100">
                    <i class="fas fa-arrow-left text-sm"></i>
                </a>
                <div>
                    <p class="text-[10px] font-extrabold text-emerald-600 uppercase tracking-[0.2em] mb-1">Manajemen Laporan</p>
                    <h2 class="text-xl font-black text-[#1e1b4b]">Riwayat Survey Anda</h2>
                </div>
            </div>
            
            <div class="flex items-center gap-6">
                <div class="text-right hidden sm:block">
                    <p class="text-[11px] font-black text-[#1e1b4b]" id="mini-clock">00:00 WITA</p>
                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-tighter">{{ now()->translatedFormat('l, d F Y') }}</p>
                </div>
                <div class="h-8 w-[1px] bg-gray-100"></div>
                <a href="{{ route('surveyor.profile') }}" class="flex items-center gap-3">
                    <div class="text-right">
                        <p class="text-[11px] font-black text-[#1e1b4b] leading-none uppercase">{{ auth()->user()->name }}</p>
                        <p class="text-[9px] font-bold text-emerald-500 uppercase mt-1">ONLINE</p>
                    </div>
                    <div class="w-10 h-10 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-600 border border-emerald-100 overflow-hidden">
                        @if(auth()->user()->profile_photo)
                            <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}" class="w-full h-full object-cover">
                        @else
                            <i class="fas fa-user-circle text-xl"></i>
                        @endif
                    </div>
                </a>
            </div>
        </header>

        <div class="p-8">
            @if(session('success'))
            <div class="mb-6 px-6 py-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-2xl flex items-center gap-3 shadow-sm">
                <i class="fas fa-check-circle"></i>
                <p class="text-xs font-bold">{{ session('success') }}</p>
            </div>
            @endif

            <div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-gray-50/50 border-b border-gray-100">
                                <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest w-16 text-center">NO</th>
                                <th class="px-6 py-5 text-[10px] font-black text-[#1e1b4b] uppercase tracking-widest">Nama Infrastruktur</th>
                                <th class="px-6 py-5 text-[10px] font-black text-[#1e1b4b] uppercase tracking-widest">Wilayah</th>
                                <th class="px-6 py-5 text-[10px] font-black text-[#1e1b4b] uppercase tracking-widest text-center">Kondisi</th>
                                <th class="px-8 py-5 text-[10px] font-black text-[#1e1b4b] uppercase tracking-widest text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($riwayat as $index => $item)
                            <tr class="hover:bg-gray-50/50 transition-colors group">
                                <td class="px-8 py-4 text-center">
                                    <span class="text-xs font-black text-gray-400">{{ $index + 1 }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-xs font-black text-[#1e1b4b] uppercase mb-0.5">{{ $item->nama_infrastruktur }}</p>
                                    <span class="px-2 py-0.5 bg-blue-50 text-blue-600 rounded-md text-[9px] font-black uppercase tracking-tighter">{{ $item->jenis_infrastruktur }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-500">
                                            <i class="fas fa-map-marker-alt"></i>
                                        </div>
                                        <div>
                                            <p class="text-xs font-bold text-gray-700">{{ $item->kelurahan ? $item->kelurahan->nama_kelurahan : '-' }}</p>
                                            <p class="text-[9px] text-gray-400 font-medium uppercase tracking-widest">{{ $item->created_at->translatedFormat('d M Y') }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex flex-col items-center gap-1.5">
                                        <span class="px-2.5 py-1 rounded-lg text-[9px] font-black tracking-widest border {{ $item->kondisi == 'Baik' ? 'bg-emerald-50 text-emerald-600 border-emerald-200' : ($item->kondisi == 'Rusak Ringan' ? 'bg-yellow-50 text-yellow-600 border-yellow-200' : ($item->kondisi == 'Rusak Berat' ? 'bg-red-50 text-red-600 border-red-200' : 'bg-gray-50 text-gray-500 border-gray-200')) }}">
                                            {{ strtoupper($item->kondisi) }}
                                        </span>
                                        @if($item->cnn || $item->analisis)
                                        <div class="flex flex-col items-center gap-0.5">
                                            @if($item->cnn)
                                                <p class="text-[8px] font-bold text-gray-400 uppercase tracking-tighter">CNN: <span class="text-emerald-500">{{ number_format($item->cnn->skor_cnn * 100, 1) }}%</span></p>
                                            @endif
                                            @if($item->analisis)
                                                <p class="text-[8px] font-bold text-gray-400 uppercase tracking-tighter">D-Tree: <span class="text-blue-500">{{ $item->analisis->label_prioritas }}</span></p>
                                            @endif
                                        </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-8 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('surveyor.infrastruktur.edit', $item->id_infrastruktur) }}" class="w-8 h-8 flex items-center justify-center bg-gray-100 text-gray-500 rounded-lg hover:bg-blue-500 hover:text-white transition-colors cursor-pointer" title="Edit Data">
                                            <i class="fas fa-pen text-xs"></i>
                                        </a>
                                        <a href="{{ route('surveyor.infrastruktur.show', $item->id_infrastruktur) }}" class="w-8 h-8 flex items-center justify-center bg-blue-50 text-blue-600 rounded-lg hover:bg-[#1e1b4b] hover:text-white transition-all shadow-sm border border-blue-100" title="Lihat Detail">
                                            <i class="fas fa-eye text-xs"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-8 py-20 text-center">
                                    <div class="flex flex-col items-center gap-3">
                                        <i class="fas fa-folder-open text-4xl text-gray-100"></i>
                                        <p class="text-xs text-gray-400 font-bold italic">Anda belum memiliki riwayat survey.</p>
                                        <a href="{{ route('surveyor.input') }}" class="mt-2 text-xs font-black text-emerald-600 uppercase tracking-widest hover:underline">Mulai Survey Pertama <i class="fas fa-plus ml-1"></i></a>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <script>
        function updateClock() {
            const now = new Date();
            document.getElementById('mini-clock').textContent = `${String(now.getHours()).padStart(2, '0')}:${String(now.getMinutes()).padStart(2, '0')} WITA`;
        }
        setInterval(updateClock, 1000); updateClock();
    </script>
</body>
</html>
