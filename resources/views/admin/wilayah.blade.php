<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Wilayah | Admin SINFRA</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                    },
                    colors: {
                        navy: {
                            50: '#f4f4fa',
                            100: '#e9e9f3',
                            200: '#c7c8e3',
                            500: '#6366f1',
                            800: '#1e1b4b',
                            900: '#0f0e2c',
                            950: '#070617',
                        },
                        gold: {
                            50: '#fdfbf7',
                            100: '#fbf7ed',
                            500: '#c5a059',
                            600: '#b38f4a',
                            700: '#9d7c3d',
                        }
                    }
                }
            }
        }
    </script>
    
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    </style>
</head>
<body class="bg-slate-50 flex h-screen overflow-hidden text-slate-800 text-left font-sans">

    @include('admin.partials.sidebar')

    <main class="flex-1 flex flex-col h-screen overflow-hidden text-left font-sans">
        <header class="bg-white/85 backdrop-blur-xl border-b border-slate-100 px-8 py-5 flex justify-between items-center z-40 text-left">
            <div class="flex items-center gap-4 text-left">
                <a href="{{ route('admin.dashboard') }}" 
                   class="w-10 h-10 bg-white border border-slate-100 rounded-xl flex items-center justify-center text-slate-400 hover:text-gold-500 hover:border-gold-500/20 hover:shadow-lg hover:shadow-gold-500/5 transition-all group"
                   title="Kembali ke Dashboard">
                    <i class="fas fa-arrow-left text-xs group-hover:-translate-x-1 transition-transform"></i>
                </a>

                <div class="text-left">
                    <p class="text-[10px] font-black text-gold-500 uppercase tracking-[0.2em] mb-1">Administrator Portal</p>
                    <h2 class="text-xl font-black text-navy-900 leading-none">Manajemen Wilayah</h2>
                </div>
            </div>
            
            <div class="flex items-center gap-6">
                <div class="text-right hidden sm:block">
                    <p class="text-[11px] font-black text-navy-900" id="mini-clock">00:00 WITA</p>
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-tighter">{{ now()->translatedFormat('l, d F Y') }}</p>
                </div>
                <div class="h-8 w-[1px] bg-slate-100"></div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.profile') }}" class="text-right group">
                        <p class="text-[11px] font-black text-navy-900 leading-none uppercase group-hover:text-gold-500 transition-all">{{ auth()->user()->name }}</p>
                        <p class="text-[9px] font-bold text-emerald-500 uppercase mt-1">Online</p>
                    </a>
                    <a href="{{ route('admin.profile') }}" class="w-10 h-10 bg-navy-900 rounded-xl flex items-center justify-center text-gold-500 border border-white/10 overflow-hidden hover:shadow-lg hover:shadow-navy-950/20 transition-all shadow-md">
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
            
            @if(session('success'))
            <div class="mb-6 px-6 py-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-2xl flex items-center gap-3">
                <i class="fas fa-check-circle"></i>
                <p class="text-xs font-bold">{{ session('success') }}</p>
            </div>
            @endif

            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
                <div>
                    <h4 class="font-extrabold text-lg text-navy-900">DATA MASTER WILAYAH</h4>
                    <p class="text-xs text-slate-400 font-medium text-left">Kelola data wilayah cakupan pemetaan infrastruktur</p>
                </div>
                
                <div class="flex flex-wrap items-center gap-3 w-full md:w-auto">
                    <form action="{{ route('admin.wilayah') }}" method="GET" class="flex items-center flex-1 md:w-[400px]">
                        <select name="show" onchange="this.form.submit()" class="pl-4 pr-8 py-2.5 bg-white border border-slate-100 border-r-0 rounded-l-2xl text-[10px] font-bold text-navy-900 focus:outline-none focus:ring-4 focus:ring-gold-500/10 focus:border-gold-500 transition-all shadow-sm">
                            <option value="10" {{ request('show') != 'all' ? 'selected' : '' }}>Per 10 Data</option>
                            <option value="all" {{ request('show') == 'all' ? 'selected' : '' }}>Semua Data</option>
                        </select>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama kecamatan atau kelurahan..." class="flex-1 pl-4 pr-4 py-2.5 bg-white border border-slate-100 text-xs font-semibold focus:outline-none focus:ring-4 focus:ring-gold-500/10 focus:border-gold-500 transition-all shadow-sm">
                        <button type="submit" class="bg-white border-y border-r border-slate-100 px-5 py-2.5 rounded-r-2xl hover:bg-slate-50 transition-all shadow-sm group">
                            <i class="fas fa-search text-slate-400 group-hover:text-gold-500 transition-colors text-xs"></i>
                        </button>
                    </form>

                    <a href="{{ route('admin.wilayah.create') }}" class="bg-gold-500 text-white text-xs px-6 py-2.5 rounded-2xl font-bold shadow-lg shadow-gold-500/10 hover:bg-gold-600 hover:shadow-gold-500/20 transition flex items-center gap-2 whitespace-nowrap">
                        <i class="fas fa-plus text-[10px]"></i> Tambah Wilayah
                    </a>
                </div>
            </div>

            <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden mb-10">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50 border-b border-slate-100">
                            <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest w-24 text-center">No.</th>
                            <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Nama Kecamatan</th>
                            <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Kelurahan</th>
                            <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center w-32">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($wilayah as $index => $wly)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="px-8 py-5 text-center">
                                <span class="px-3 py-1.5 bg-slate-100 text-slate-600 rounded-lg text-xs font-bold font-mono">
                                    {{ request('show') == 'all' ? $index + 1 : ($wilayah->currentPage() - 1) * $wilayah->perPage() + $index + 1 }}
                                </span>
                            </td>
                            <td class="px-8 py-5">
                                <p class="text-xs font-black text-navy-900 uppercase leading-none">{{ $wly->nama_kecamatan }}</p>
                            </td>
                            <td class="px-8 py-5">
                                <p class="text-sm font-bold text-navy-900 leading-relaxed max-w-sm truncate" title="{{ $wly->nama_kelurahan }}">
                                    {{ $wly->nama_kelurahan ?? '-' }}
                                </p>
                            </td>
                            <td class="px-8 py-5">
                                <div class="flex justify-center gap-2">
                                    <a href="{{ route('admin.wilayah.edit', $wly->id_kelurahan) }}" title="Edit Wilayah" class="w-8 h-8 bg-slate-50 text-slate-400 hover:text-gold-500 hover:bg-gold-500/10 rounded-lg transition flex items-center justify-center">
                                        <i class="fas fa-edit text-[10px]"></i>
                                    </a>
                                    
                                    <form action="{{ route('admin.wilayah.destroy', $wly->id_kelurahan) }}" method="POST" class="inline-block m-0 p-0" onsubmit="return confirm('PERINGATAN!\n\nApakah Anda yakin ingin menghapus data kelurahan {{ $wly->nama_kelurahan }}?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="Hapus Wilayah" class="w-8 h-8 bg-slate-50 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition flex items-center justify-center">
                                            <i class="fas fa-trash text-[10px]"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-8 py-10 text-center text-sm font-semibold text-gray-400">
                                <i class="fas fa-folder-open text-2xl mb-2 block text-gray-300"></i>
                                Belum ada data Master Wilayah yang ditambahkan.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                
                @if(request('show') != 'all' && isset($wilayah) && $wilayah instanceof \Illuminate\Pagination\LengthAwarePaginator)
                    <div class="px-8 py-4 border-t border-gray-50 bg-gray-50/10">
                        {{ $wilayah->links() }}
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
    </script>
</body>
</html>