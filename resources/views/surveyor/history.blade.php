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
            <div>
                <p class="text-[10px] font-extrabold text-emerald-600 uppercase tracking-[0.2em] mb-1">Manajemen Laporan</p>
                <h2 class="text-xl font-black text-[#1e1b4b]">Riwayat Survey Anda</h2>
            </div>
            
            <div class="flex items-center gap-6">
                <div class="text-right hidden sm:block">
                    <p class="text-[11px] font-black text-[#1e1b4b]" id="mini-clock">00:00 WITA</p>
                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-tighter">{{ now()->translatedFormat('l, d F Y') }}</p>
                </div>
                <div class="h-8 w-[1px] bg-gray-100"></div>
                <a href="{{ route('surveyor.profile') }}" class="flex items-center gap-3 group">
                    <div class="text-right">
                        <p class="text-[11px] font-black text-[#1e1b4b] leading-none uppercase group-hover:text-emerald-600 transition-colors">{{ auth()->user()->name }}</p>
                        <p class="text-[9px] font-bold text-emerald-500 uppercase mt-1">Aktif Melaporkan</p>
                    </div>
                    <div class="w-10 h-10 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-600 border border-emerald-100 overflow-hidden shadow-sm group-hover:shadow-md transition-all">
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
                                <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">ID / Foto</th>
                                <th class="px-6 py-5 text-[10px] font-black text-[#1e1b4b] uppercase tracking-widest">Nama & Jenis</th>
                                <th class="px-6 py-5 text-[10px] font-black text-[#1e1b4b] uppercase tracking-widest text-center">Analisis AI</th>
                                <th class="px-6 py-5 text-[10px] font-black text-[#1e1b4b] uppercase tracking-widest">Status</th>
                                <th class="px-8 py-5 text-[10px] font-black text-[#1e1b4b] uppercase tracking-widest text-right">Tanggal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($riwayat as $item)
                            <tr class="hover:bg-gray-50/50 transition-colors group">
                                <td class="px-8 py-4">
                                    <div class="flex items-center gap-4">
                                        <span class="text-[10px] font-black text-gray-400">#{{ $item->id_infrastruktur }}</span>
                                        <div class="w-12 h-12 rounded-xl bg-gray-100 border border-gray-100 overflow-hidden shadow-sm">
                                            @if($item->foto_terbaru)
                                                <img src="{{ asset('storage/' . $item->foto_terbaru) }}" class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center text-gray-300"><i class="fas fa-image"></i></div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-xs font-black text-[#1e1b4b] uppercase mb-0.5">{{ $item->nama_infrastruktur }}</p>
                                    <span class="px-2 py-0.5 bg-blue-50 text-blue-600 rounded-md text-[9px] font-black uppercase tracking-tighter">{{ $item->jenis_infrastruktur }}</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex flex-col items-center gap-1">
                                        <span class="px-2.5 py-1 rounded-lg text-[10px] font-black tracking-widest border {{ $item->kondisi == 'Baik' ? 'bg-emerald-50 text-emerald-600 border-emerald-200' : ($item->kondisi == 'Rusak Ringan' ? 'bg-yellow-50 text-yellow-600 border-yellow-200' : 'bg-red-50 text-red-600 border-red-200') }}">
                                            {{ strtoupper($item->kondisi) }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <div class="w-1.5 h-1.5 rounded-full {{ $item->status_verifikasi == 'Verified' ? 'bg-emerald-500 shadow-lg shadow-emerald-500/50' : 'bg-amber-500 shadow-lg shadow-amber-500/50' }}"></div>
                                        <span class="text-[10px] font-bold {{ $item->status_verifikasi == 'Verified' ? 'text-emerald-600' : 'text-amber-600' }} uppercase">{{ $item->status_verifikasi ?? 'Proses' }}</span>
                                    </div>
                                </td>
                                <td class="px-8 py-4 text-right">
                                    <p class="text-[10px] font-bold text-[#1e1b4b]">{{ $item->created_at->translatedFormat('d M Y') }}</p>
                                    <p class="text-[9px] text-gray-400 font-medium italic">{{ $item->created_at->format('H:i') }} WITA</p>
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
