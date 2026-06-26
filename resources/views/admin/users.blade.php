<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Pengguna | Admin SINFRA</title>
    <link rel="icon" href="{{ asset('logo_geo-sinfra.png') }}" type="image/png">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <script>
        tailwind.config = {
            darkMode: 'class',
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
<style>
    @media (min-width: 768px) { html { font-size: 14px; } }
    @media (max-width: 767px) { html { font-size: 12px; } }
</style>
</head>
<body class="bg-navy-50 dark:bg-navy-950 text-slate-800 dark:text-slate-200 antialiased flex overflow-hidden h-screen transition-colors duration-300">

    @include('admin.partials.sidebar')

    <main class="flex-1 flex flex-col h-screen overflow-hidden text-left">
        <header class="bg-white/85 dark:bg-navy-950/80 backdrop-blur-xl border-b border-slate-100 dark:border-white/5 px-4 pl-16 md:px-8 py-4 flex justify-between items-center z-40 text-left transition-colors duration-300">
            <div class="flex items-center gap-4 text-left">
                <a href="{{ route('admin.dashboard') }}" 
                   class="hidden md:flex w-10 h-10 bg-white border border-slate-100 rounded-xl flex items-center justify-center text-slate-400 hover:text-gold-500 hover:border-gold-500/20 hover:shadow-lg hover:shadow-gold-500/5 transition-all group"
                   title="Kembali ke Dashboard Utama">
                    <i class="fas fa-arrow-left text-xs group-hover:-translate-x-1 transition-transform"></i>
                </a>

                <div class="text-left">
                    <p class="text-xs font-black text-gold-500 uppercase tracking-wider mb-1">Administrator Portal</p>
                    <h2 class="text-xl font-black text-navy-900 dark:text-white leading-none">Manajemen Pengguna</h2>
                </div>
            </div>
            
            <div class="flex items-center gap-3 md:gap-6">
                <div class="text-right">
                    <p class="text-sm font-black text-navy-900 dark:text-white" id="mini-clock">00:00 WITA</p>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-tighter">{{ now()->translatedFormat('d M Y') }}</p>
                </div>
                <div class="h-8 w-[1px] bg-slate-100"></div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.profile') }}" class="text-right group hidden md:block">
                        <p class="text-sm font-black text-navy-900 dark:text-white leading-none uppercase group-hover:text-gold-500 transition-all">{{ auth()->user()->name }}</p>
                        <p class="text-xs font-bold text-emerald-500 uppercase mt-1">Online</p>
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

            @if(session('error'))
            <div class="mb-6 px-6 py-4 bg-red-50 border border-red-100 text-red-700 rounded-2xl flex items-center gap-3">
                <i class="fas fa-exclamation-circle"></i>
                <p class="text-xs font-bold">{{ session('error') }}</p>
            </div>
            @endif

            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
                <div>
                    <h4 class="font-extrabold text-lg text-navy-900">Daftar Pengguna Sistem</h4>
                    <p class="text-xs text-slate-400 font-medium text-left font-sans">Kelola hak akses untuk Admin, Surveyor, dan Tim Teknis</p>
                </div>
                
                <div class="flex flex-wrap items-center gap-3 w-full md:w-auto">
                    <form action="{{ route('admin.users') }}" method="GET" class="flex items-center flex-1 md:w-[400px]">
                        <select name="show" onchange="this.form.submit()" class="pl-4 pr-8 py-2.5 bg-white border border-slate-100 border-r-0 rounded-l-2xl text-xs font-bold text-navy-900 focus:outline-none focus:ring-4 focus:ring-gold-500/10 focus:border-gold-500 transition-all shadow-sm">
                            <option value="10" {{ request('show') != 'all' ? 'selected' : '' }}>Per 10 Data</option>
                            <option value="all" {{ request('show') == 'all' ? 'selected' : '' }}>Semua Data</option>
                        </select>
                        <input type="text" 
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Ketik nama pengguna..." 
                            class="flex-1 pl-4 pr-4 py-2.5 bg-white border border-slate-100 text-xs font-semibold focus:outline-none focus:ring-4 focus:ring-gold-500/10 focus:border-gold-500 transition-all shadow-sm">
                        <button type="submit" class="bg-white border-y border-r border-slate-100 px-5 py-2.5 rounded-r-2xl hover:bg-slate-50 transition-all shadow-sm group">
                            <i class="fas fa-search text-slate-400 group-hover:text-gold-500 transition-colors text-xs"></i>
                        </button>
                    </form>

                    <a href="{{ route('admin.users.create') }}" class="bg-gold-500 text-white text-xs px-6 py-2.5 rounded-2xl font-bold shadow-lg shadow-gold-500/10 hover:bg-gold-600 hover:shadow-gold-500/20 transition flex items-center gap-2 whitespace-nowrap">
                        <i class="fas fa-user-plus text-xs"></i> Tambah User
                    </a>
                </div>
            </div>

            <div class="bg-white rounded-3xl md:rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden mb-10">
                <div class="overflow-x-auto w-full custom-scrollbar"><table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gradient-to-r from-navy-900 to-navy-800 border-b border-navy-800 shadow-md">
                            <th class="px-4 md:px-5 py-4 text-xs font-black text-gold-500 uppercase tracking-widest w-12 text-center">No.</th>
                            <th class="px-4 md:px-5 py-4 text-xs font-black text-gold-500 uppercase tracking-widest">Nama User</th>
                            <th class="px-4 md:px-5 py-4 text-xs font-black text-gold-500 uppercase tracking-widest">Email Address</th>
                            <th class="px-4 md:px-5 py-4 text-xs font-black text-gold-500 uppercase tracking-widest">Role / Jabatan</th>
                            <th class="px-4 md:px-5 py-4 text-xs font-black text-gold-500 uppercase tracking-widest text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach($users as $index => $user)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="px-4 md:px-5 py-4 text-center">
                                <span class="text-xs font-black text-navy-900">{{ request('show') == 'all' ? $index + 1 : ($users->currentPage() - 1) * $users->perPage() + $index + 1 }}</span>
                            </td>
                            <td class="px-4 md:px-5 py-4">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 {{ $user->role == 'admin' ? 'bg-gold-500/10 text-gold-500 border-gold-500/20' : ($user->role == 'tim_teknis' ? 'bg-navy-900/10 text-navy-900 border-navy-900/20' : 'bg-slate-100 text-slate-600 border-slate-200') }} rounded-xl flex items-center justify-center font-bold text-xs border">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="text-xs font-black text-navy-900 uppercase leading-none">{{ $user->name }}</p>
                                        <p class="text-xs text-slate-400 font-bold uppercase mt-1 italic">ID: #{{ str_pad($user->id, 4, '0', STR_PAD_LEFT) }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 md:px-5 py-4 text-sm font-medium text-slate-500">{{ $user->email }}</td>
                            <td class="px-4 md:px-5 py-4">
                                <span class="px-4 py-1.5 rounded-full text-xs font-black uppercase tracking-tighter 
                                    {{ $user->role == 'admin' ? 'bg-gold-500/10 text-gold-500 border border-gold-500/20' : ($user->role == 'tim_teknis' ? 'bg-navy-900/10 text-navy-900 border border-navy-900/20' : 'bg-slate-100 text-slate-600 border border-slate-200') }}">
                                    {{ $user->role }}
                                </span>
                            </td>
                            <td class="px-4 md:px-5 py-4">
                                <div class="flex justify-center gap-2">
                                    @if($user->role !== 'tim_teknis')
                                    
                                    <a href="{{ route('admin.users.edit', $user->id) }}" title="Edit User" class="w-8 h-8 flex items-center justify-center bg-gold-500 hover:bg-gold-600 text-white rounded-lg text-xs font-black transition shadow-sm hover:scale-105">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    
                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline-block m-0 p-0" onsubmit="return confirm('PERINGATAN!\n\nApakah Anda yakin ingin menghapus akun milik {{ $user->name }}?\nTindakan ini tidak dapat dibatalkan.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="Hapus User" class="w-8 h-8 flex items-center justify-center bg-rose-500 hover:bg-rose-600 text-white rounded-lg text-xs font-black transition shadow-sm hover:scale-105">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>

                                    @else
                                    <span class="px-2 h-8 bg-slate-100 text-slate-400 text-[10px] md:text-xs font-bold rounded-lg flex items-center justify-center cursor-not-allowed gap-1 md:gap-1.5" title="Akun Tim Teknis dilindungi sistem">
                                        <i class="fas fa-lock"></i> Terkunci
                                    </span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table></div>
                
                @if(request('show') != 'all' && isset($users) && $users instanceof \Illuminate\Pagination\LengthAwarePaginator)
                    <div class="px-8 py-4 border-t border-gray-50 bg-gray-50/10">
                        {{ $users->links() }}
                    </div>
                @endif
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
    </script>
</body>
</html>

