<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log Aktivitas Sistem | Admin SINFRA</title>
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
                        navy: { 50: '#f4f4fa', 100: '#e9e9f3', 200: '#c7c8e3', 500: '#6366f1', 800: '#1e1b4b', 900: '#0f0e2c', 950: '#070617' },
                        gold: { 50: '#fdfbf7', 100: '#fbf7ed', 500: '#c5a059', 600: '#b38f4a', 700: '#9d7c3d' }
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
                   title="Kembali ke Dashboard">
                    <i class="fas fa-arrow-left text-xs group-hover:-translate-x-1 transition-transform"></i>
                </a>

                <div class="text-left">
                    <p class="text-xs font-black text-gold-500 uppercase tracking-wider mb-1">Administrator Portal</p>
                    <h2 class="text-xl font-black text-navy-900 dark:text-white leading-none">Log Aktivitas Sistem</h2>
                </div>
            </div>
            
            <div class="flex items-center gap-3 md:gap-6">
                <div class="text-right">
                    <p class="text-xs font-black text-navy-900 dark:text-white" id="mini-clock">00:00 WITA</p>
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-tighter">{{ now()->translatedFormat('d M Y') }}</p>
                </div>
                <div class="h-8 w-[1px] bg-slate-100"></div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.profile') }}" class="text-right group hidden md:block">
                        <p class="text-sm font-black text-navy-900 dark:text-white leading-none uppercase group-hover:text-gold-500 transition-all max-w-[100px] sm:max-w-[150px] md:max-w-[300px] truncate">{{ auth()->user()->name }}</p>
                        <p class="text-[10px] md:text-xs font-bold text-emerald-500 uppercase mt-0.5">Online</p>
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
            
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
                <div>
                    <h4 class="font-extrabold text-lg text-navy-900 uppercase">LOG AKTIVITAS</h4>
                    <p class="text-xs text-slate-400 font-medium text-left">Memantau seluruh aktivitas pengguna di sistem</p>
                </div>
                <div class="flex flex-row flex-nowrap items-center gap-2 w-full md:w-auto">
                    <form action="{{ route('admin.activity') }}" method="GET" class="flex items-center flex-1 min-w-0 md:w-[400px]">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari log aktivitas..." class="flex-1 min-w-[80px] pl-4 pr-3 py-2.5 bg-white border border-slate-100 rounded-l-2xl text-[10px] md:text-xs font-semibold focus:outline-none focus:ring-4 focus:ring-gold-500/10 focus:border-gold-500 transition-all shadow-sm">
                        <button type="submit" class="bg-white border-y border-r border-slate-100 px-4 md:px-5 py-2.5 rounded-r-2xl hover:bg-slate-50 transition-all shadow-sm group shrink-0">
                            <i class="fas fa-search text-slate-400 group-hover:text-gold-500 transition-colors text-xs"></i>
                        </button>
                    </form>

                    <a href="{{ route('admin.activity.export') }}" class="bg-emerald-500 text-white text-xs px-4 md:px-6 py-2.5 rounded-2xl font-bold shadow-lg shadow-emerald-500/10 hover:bg-emerald-600 hover:shadow-emerald-500/20 transition flex items-center justify-center gap-2 whitespace-nowrap shrink-0">
                        <i class="fas fa-file-excel text-xs"></i> <span class="hidden sm:inline">Export Excel</span>
                    </a>
                </div>
            </div>

            <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden mb-10">
                <div class="overflow-x-auto w-full custom-scrollbar">
                <table class="w-full text-left border-collapse min-w-full">
                    <thead>
                        <tr class="bg-gradient-to-r from-navy-900 to-navy-800 border-b border-navy-800 shadow-md">
                            <th class="px-4 py-3 text-xs font-black text-gold-500 uppercase tracking-widest">Waktu</th>
                            <th class="px-4 py-3 text-xs font-black text-gold-500 uppercase tracking-widest">Pengguna</th>
                            <th class="px-4 py-3 text-xs font-black text-gold-500 uppercase tracking-widest">Aktivitas</th>
                            <th class="px-4 py-3 text-xs font-black text-gold-500 uppercase tracking-widest">Kategori</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 text-xs font-medium">
                        <!-- Dynamic Data Rows -->
                        @forelse($activities as $activity)
                            @php
                                $badgeColor = 'slate';
                                $icon = 'fa-info-circle';
                                $actionName = $activity->description;
                                
                                if (str_contains(strtolower($actionName), 'login')) {
                                    $badgeColor = 'emerald';
                                    $icon = 'fa-sign-in-alt';
                                } elseif (str_contains(strtolower($actionName), 'tambah')) {
                                    $badgeColor = 'blue';
                                    $icon = 'fa-plus';
                                } elseif (str_contains(strtolower($actionName), 'hapus') || str_contains(strtolower($actionName), 'delete')) {
                                    $badgeColor = 'rose';
                                    $icon = 'fa-trash';
                                } elseif (str_contains(strtolower($actionName), 'ubah') || str_contains(strtolower($actionName), 'edit') || str_contains(strtolower($actionName), 'perbarui')) {
                                    $badgeColor = 'amber';
                                    $icon = 'fa-pen';
                                } elseif (str_contains(strtolower($actionName), 'validasi')) {
                                    $badgeColor = 'emerald';
                                    $icon = 'fa-check-circle';
                                }

                                $roleColor = 'slate';
                                if($activity->user) {
                                    if ($activity->user->role == 'admin') $roleColor = 'navy';
                                    elseif ($activity->user->role == 'tim_teknis') $roleColor = 'gold';
                                    elseif ($activity->user->role == 'surveyor') $roleColor = 'blue';
                                }
                            @endphp
                            <tr class="hover:bg-slate-50/50 transition-colors group">
                                <td class="px-4 py-3 text-xs text-slate-500 whitespace-nowrap">
                                    <div class="flex flex-col gap-0.5">
                                        <span class="font-bold text-navy-900">{{ $activity->created_at->format('Y-m-d') }}</span>
                                        <span class="text-[10px] text-slate-400"><i class="fas fa-clock mr-1 text-slate-300"></i> {{ $activity->created_at->format('H:i:s') }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <div>
                                        <p class="font-bold text-navy-900">{{ $activity->user ? $activity->user->name : 'Sistem Otomatis' }}</p>
                                        <p class="text-[10px] text-slate-400 uppercase tracking-wider">{{ $activity->user ? $activity->user->role : 'System' }}</p>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="px-2.5 py-1.5 bg-{{$badgeColor}}-50 text-{{$badgeColor}}-600 text-[10px] font-bold uppercase tracking-wider rounded-lg border border-{{$badgeColor}}-100 inline-flex items-start gap-1.5 shadow-sm max-w-[200px] md:max-w-xs whitespace-normal text-left leading-relaxed">
                                        <i class="fas {{ $icon }} shrink-0 mt-0.5"></i> <span>{{ $actionName }}</span>
                                    </span>
                                </td>
                                <td class="px-4 py-3 font-bold text-navy-800 uppercase text-xs">{{ $activity->type }} {!! $activity->reference_id ? "<span class='text-slate-400 text-xs'>(ID: {$activity->reference_id})</span>" : "" !!}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-8 py-10 text-center text-sm font-semibold text-gray-400">
                                    <i class="fas fa-history text-2xl mb-2 block text-gray-300"></i>
                                    Belum ada aktivitas yang direkam oleh sistem.
                                </td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
                </div>
                
                @if($activities instanceof \Illuminate\Pagination\LengthAwarePaginator && $activities->hasPages())
                    <div class="px-8 py-4 border-t border-slate-50 bg-slate-50/30">
                        {{ $activities->links() }}
                    </div>
                @else
                    <div class="px-8 py-4 border-t border-slate-50 bg-slate-50/30 flex justify-between items-center text-xs text-slate-500 font-bold">
                        <span>Menampilkan total {{ $activities->count() }} aktivitas</span>
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

