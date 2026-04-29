<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Infrastruktur | Admin SINFRA</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    </style>
</head>
<body class="bg-gray-50 flex h-screen overflow-hidden text-gray-800 text-left">

    @include('admin.partials.sidebar')

    <main class="flex-1 flex flex-col h-screen overflow-hidden text-left">
        <header class="bg-white border-b border-gray-100 px-8 py-5 flex justify-between items-center z-10">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.dashboard') }}" 
                   class="w-10 h-10 bg-white border border-gray-100 rounded-xl flex items-center justify-center text-gray-400 hover:text-blue-600 transition-all group">
                    <i class="fas fa-arrow-left text-xs group-hover:-translate-x-1 transition-transform"></i>
                </a>
                <div>
                    <p class="text-[10px] font-extrabold text-blue-600 tracking-[0.2em] mb-1">Administrator Portal</p>
                    <h2 class="text-xl font-black text-[#1e1b4b]">Manajemen Infrastruktur</h2>
                </div>
            </div>
            
            <div class="flex items-center gap-6">
                <div class="text-right hidden sm:block">
                    <p class="text-[11px] font-black text-[#1e1b4b]" id="mini-clock">00:00 WITA</p>
                    <p class="text-[9px] font-bold text-gray-400 tracking-tighter">{{ now()->translatedFormat('l, d F Y') }}</p>
                </div>
                <div class="h-8 w-[1px] bg-gray-100"></div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.profile') }}" class="text-right group">
                        <p class="text-[11px] font-black text-[#1e1b4b] leading-none uppercase group-hover:text-blue-600 transition-all">{{ auth()->user()->name }}</p>
                        <p class="text-[9px] font-bold text-green-500 uppercase mt-1">Online</p>
                    </a>
                    <a href="{{ route('admin.profile') }}" class="w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600 border border-indigo-100 overflow-hidden hover:shadow-lg hover:shadow-indigo-500/10 transition-all">
                        @if(auth()->user()->profile_photo)
                            <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}" class="w-full h-full object-cover">
                        @else
                            <i class="fas fa-user-circle text-xl"></i>
                        @endif
                    </a>
                </div>
            </div>
        </header>

        <div class="flex-1 p-8 overflow-y-auto custom-scrollbar">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8 text-left">
                <div>
                    <h4 class="font-extrabold text-lg text-[#1e1b4b]">Data Manajemen Infrastruktur</h4>
                </div>
                
                <div class="flex flex-col sm:flex-row items-center gap-3 w-full md:w-auto">
                    <form action="{{ route('admin.infrastruktur') }}" method="GET" class="relative w-full sm:w-64">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari infrastruktur..." class="w-full pl-10 pr-4 py-3 bg-white border border-gray-100 rounded-2xl text-[10px] font-bold outline-none focus:border-blue-500 transition-all shadow-sm placeholder-gray-400 text-gray-600">
                        <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-[10px]"></i>
                    </form>
                    
                    <a href="{{ route('admin.infrastruktur.create') }}" class="bg-blue-600 text-white text-[10px] px-6 py-3 rounded-2xl font-black shadow-lg shadow-blue-200 hover:bg-blue-700 transition flex items-center justify-center gap-2 whitespace-nowrap tracking-widest w-full sm:w-auto">
                        <i class="fas fa-plus"></i> Tambah Data
                    </a>
                </div>
            </div>

            <div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-sm overflow-hidden text-left">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50 border-b border-gray-100">
                            <th class="px-6 py-5 text-[10px] font-black text-gray-400 tracking-widest">Infrastruktur</th>
                            <th class="px-6 py-5 text-[10px] font-black text-gray-400 tracking-widest">Wilayah</th>
                            <th class="px-6 py-5 text-[10px] font-black text-gray-400 tracking-widest text-center">Logika AI</th>
                            <th class="px-6 py-5 text-[10px] font-black text-gray-400 tracking-widest text-center">Status</th>
                            <th class="px-6 py-5 text-[10px] font-black text-gray-400 tracking-widest text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 font-bold text-xs">
                        @forelse($infrastruktur as $inf)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="px-6 py-5">
                                <p class="text-sm font-black text-[#1e1b4b]">{{ $inf->jenis_infrastruktur }}</p>
                                <p class="text-[9px] text-gray-400 mt-1 font-bold">ID: INF-{{ $inf->id_infrastruktur }}</p>
                            </td>
                            <td class="px-6 py-5">
                                <span class="text-[10px] text-gray-500 font-extrabold">{{ $inf->nama_infrastruktur }}</span>
                            </td>
                            
                            <td class="px-6 py-5">
                                <div class="flex flex-col items-center gap-1.5">
                                    <div class="flex items-center gap-1">
                                        <span class="px-1.5 py-0.5 bg-purple-50 text-purple-600 border border-purple-100 rounded text-[8px] font-black">CNN</span>
                                        <span class="text-[9px] {{ $inf->kondisi == 'Baik' ? 'text-green-500' : 'text-red-500' }} italic">
                                            {{ $inf->kondisi == 'Baik' ? '✓ Baik (88%)' : '⚠️ Rusak (92%)' }}
                                        </span>
                                    </div>
                                    <div class="flex items-center gap-1">
                                        <span class="px-1.5 py-0.5 bg-blue-50 text-blue-600 border border-blue-100 rounded text-[8px] font-black">DT</span>
                                        <span class="text-gray-400 text-[9px]">Kategori: {{ $inf->kondisi == 'Baik' ? 'Normal' : 'Prioritas' }}</span>
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-5 text-center">
                                <span class="px-4 py-1.5 rounded-lg text-[9px] font-black tracking-widest border {{ $inf->kondisi == 'Baik' ? 'bg-blue-50 text-blue-600 border-blue-100' : 'bg-yellow-50 text-yellow-600 border-yellow-100' }}">
                                    {{ $inf->kondisi == 'Baik' ? 'VERIFIED' : 'PENDING' }}
                                </span>
                            </td>

                            <td class="px-6 py-5">
                                <div class="flex items-center justify-center gap-2">
                                    @if($inf->kondisi == 'Baik')
                                        <button class="bg-gray-50 text-gray-300 px-3 py-2 rounded-xl text-[8px] font-black flex items-center gap-1 cursor-not-allowed border border-gray-100">
                                            <i class="fas fa-check-double"></i> Selesai
                                        </button>
                                    @else
                                        <button class="bg-emerald-500 text-white px-3 py-2 rounded-xl text-[8px] font-black hover:bg-emerald-600 transition shadow-sm flex items-center gap-1">
                                            <i class="fas fa-check"></i> Verifikasi
                                        </button>
                                    @endif

                                    <a href="{{ route('admin.infrastruktur.edit', $inf->id_infrastruktur) }}" class="bg-indigo-600 text-white px-3 py-2 rounded-xl text-[8px] font-black hover:bg-indigo-700 transition shadow-sm flex items-center gap-1">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>

                                    <a href="{{ route('admin.infrastruktur.show', $inf->id_infrastruktur) }}" class="bg-yellow-400 text-white px-3 py-2 rounded-xl text-[8px] font-black hover:bg-yellow-500 transition shadow-sm flex items-center gap-1">
                                        <i class="fas fa-eye"></i> Detail
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="px-8 py-16 text-center text-gray-400 italic font-bold">Belum Ada Data Infrastruktur.</td></tr>
                        @endforelse
                    </tbody>
                </table>
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