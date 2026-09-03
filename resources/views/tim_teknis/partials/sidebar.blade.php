{{-- Hamburger button dihapus, diganti Bottom Nav --}}

{{-- Overlay Background (muncul saat menu terbuka) --}}
<div id="mobile-overlay" onclick="toggleMobileMenu()" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-[9998] hidden md:hidden transition-opacity duration-300 opacity-0"></div>

{{-- Sidebar Desktop & Tablet --}}
<aside class="w-20 lg:w-64 bg-white dark:bg-[#0f0e2c] text-navy-900 dark:text-white flex-col hidden md:flex shadow-2xl z-20 text-left border-r border-slate-200 dark:border-white/5 shrink-0 h-screen transition-all duration-300">
    <div class="p-4 lg:p-6 flex-1 text-left overflow-y-auto custom-scrollbar">
        <a href="{{ route('tim_teknis.dashboard') }}" class="flex items-center justify-center lg:justify-start gap-3 mb-10 hover:opacity-80 transition-opacity group">
            <div class="w-8 h-8 bg-white rounded-lg overflow-hidden shadow-lg shadow-navy-950/10 dark:shadow-navy-950/40 group-hover:scale-110 transition-transform shrink-0">
                <img src="{{ asset('logo_geo-sinfra.png') }}" class="w-full h-full object-contain" alt="Logo">
            </div>
            <span class="font-extrabold text-xl tracking-tighter uppercase text-navy-900 dark:text-white hidden lg:block">GEO-SINFRA</span>
        </a>
        
        <nav class="space-y-1">
            <a href="{{ route('tim_teknis.dashboard') }}" 
               class="flex items-center justify-center lg:justify-start gap-3 px-0 lg:px-4 py-3 {{ request()->routeIs('tim_teknis.dashboard') ? 'bg-gold-500 text-navy-900 dark:text-white font-bold shadow-lg shadow-gold-500/20' : 'text-slate-500 hover:text-navy-900 hover:bg-slate-100 dark:text-slate-400 dark:hover:text-white dark:hover:bg-white/5' }} rounded-xl text-sm font-semibold transition group text-left whitespace-nowrap" title="Beranda">
                <i class="fas fa-th-large {{ request()->routeIs('tim_teknis.dashboard') ? '' : 'group-hover:text-gold-500' }}"></i> 
                <span class="hidden lg:inline">Beranda</span>
            </a>

            <a href="{{ route('tim_teknis.monitoring') }}" 
               class="flex items-center justify-center lg:justify-start gap-3 px-0 lg:px-4 py-3 {{ request()->routeIs('tim_teknis.monitoring') ? 'bg-gold-500 text-navy-900 dark:text-white font-bold shadow-lg shadow-gold-500/20' : 'text-slate-500 hover:text-navy-900 hover:bg-slate-100 dark:text-slate-400 dark:hover:text-white dark:hover:bg-white/5' }} rounded-xl text-sm font-semibold transition group text-left whitespace-nowrap" title="WebGIS Eksekutif">
                <i class="fas fa-satellite-dish {{ request()->routeIs('tim_teknis.monitoring') ? '' : 'group-hover:text-gold-500' }}"></i> 
                <span class="hidden lg:inline">WebGIS Eksekutif</span>
            </a>

            <a href="{{ route('tim_teknis.prioritas') }}" 
               class="flex items-center justify-center lg:justify-start gap-3 px-0 lg:px-4 py-3 {{ request()->routeIs('tim_teknis.prioritas') ? 'bg-rose-500 text-navy-900 dark:text-white font-bold shadow-lg shadow-rose-500/20' : 'text-slate-400 hover:text-rose-400 hover:bg-rose-500/10' }} rounded-xl text-sm font-semibold transition group text-left whitespace-nowrap" title="Rekomendasi Prioritas">
                <i class="fas fa-bolt {{ request()->routeIs('tim_teknis.prioritas') ? 'animate-pulse' : 'text-rose-500 group-hover:text-rose-400' }}"></i> 
                <span class="hidden lg:inline">Rekomendasi Prioritas</span>
            </a>

            @php
                $lastReadValidasiAt = auth()->user()->last_read_validasi_at;
                $pendingValidasiQuery = \App\Models\Infrastruktur::where('status_verifikasi', 'Verified')->where('status_validasi', 'Pending');
                $pendingValidasiCount = $lastReadValidasiAt 
                    ? $pendingValidasiQuery->where('created_at', '>', $lastReadValidasiAt)->count()
                    : $pendingValidasiQuery->count();
            @endphp
            <a href="{{ route('tim_teknis.validasi') }}" 
               class="flex items-center justify-center lg:justify-between px-0 lg:px-4 py-3 {{ request()->routeIs('tim_teknis.validasi') ? 'bg-gold-500 text-navy-900 dark:text-white font-bold shadow-lg shadow-gold-500/20' : 'text-slate-500 hover:text-navy-900 hover:bg-slate-100 dark:text-slate-400 dark:hover:text-white dark:hover:bg-white/5' }} rounded-xl text-sm font-semibold transition group relative w-full" title="Validasi Usulan">
                <div class="flex items-center gap-3">
                    <i class="fas fa-clipboard-check {{ request()->routeIs('tim_teknis.validasi') ? '' : 'group-hover:text-gold-500' }}"></i> 
                    <span class="hidden lg:inline">Validasi Usulan</span>
                </div>
                @if($pendingValidasiCount > 0)
                    <span class="absolute top-1 right-1 lg:relative lg:top-auto lg:right-auto bg-rose-500 text-white text-[10px] lg:text-xs font-black px-1.5 py-0.5 rounded-full shadow-sm animate-pulse min-w-[16px] lg:min-w-[20px] text-center">
                        {{ $pendingValidasiCount }}
                    </span>
                @endif
            </a>

            <a href="{{ route('tim_teknis.laporan')  }}" 
               class="flex items-center justify-center lg:justify-start gap-3 px-0 lg:px-4 py-3 {{ request()->routeIs('tim_teknis.laporan') ? 'bg-gold-500 text-navy-900 dark:text-white font-bold shadow-lg shadow-gold-500/20' : 'text-slate-500 hover:text-navy-900 hover:bg-slate-100 dark:text-slate-400 dark:hover:text-white dark:hover:bg-white/5' }} rounded-xl text-sm font-semibold transition group text-left whitespace-nowrap" title="Cetak Laporan">
                <i class="fas fa-print {{ request()->routeIs('tim_teknis.laporan') ? '' : 'group-hover:text-gold-500' }}"></i> 
                <span class="hidden lg:inline">Cetak Laporan</span>
            </a>

            @php
                $unreadNotifCount = auth()->user()->unreadNotifications->count();
            @endphp
            <a href="{{ route('tim_teknis.notifikasi') }}" 
               class="flex items-center justify-center lg:justify-between px-0 lg:px-4 py-3 {{ request()->routeIs('tim_teknis.notifikasi') ? 'bg-gold-500 text-navy-900 dark:text-white font-bold shadow-lg shadow-gold-500/20' : 'text-slate-500 hover:text-navy-900 hover:bg-slate-100 dark:text-slate-400 dark:hover:text-white dark:hover:bg-white/5' }} rounded-xl text-sm font-semibold transition group relative w-full" title="Notifikasi Sistem">
                <div class="flex items-center gap-3">
                    <i class="fas fa-bell {{ request()->routeIs('tim_teknis.notifikasi') ? '' : 'group-hover:text-gold-500' }}"></i> 
                    <span class="hidden lg:inline">Notifikasi</span>
                </div>
                @if($unreadNotifCount > 0)
                    <span class="absolute top-1 right-1 lg:relative lg:top-auto lg:right-auto bg-rose-500 text-white text-[10px] lg:text-xs font-black px-1.5 py-0.5 rounded-full shadow-sm min-w-[16px] lg:min-w-[20px] text-center">
                        {{ $unreadNotifCount }}
                    </span>
                @endif
            </a>


        </nav>
    </div>

    <div class="p-4 lg:p-6 border-t border-slate-200 dark:border-white/5 text-center lg:text-left bg-slate-50 dark:bg-navy-950/20 relative flex flex-col items-center lg:items-stretch">
        <form method="POST" action="{{ route('logout') }}" class="w-full">
            @csrf
            <button type="submit" class="flex items-center justify-center lg:justify-start gap-3 px-0 lg:px-4 py-3.5 text-red-500 hover:text-red-600 dark:text-red-400 dark:hover:text-red-300 w-full text-left text-sm font-bold transition group rounded-xl hover:bg-red-50 dark:hover:bg-red-500/10" title="Keluar">
                <i class="fas fa-sign-out-alt group-hover:-translate-x-1 transition-transform"></i> 
                <span class="hidden lg:inline">Keluar</span>
            </button>
        </form>
    </div>
</aside>

{{-- Sidebar Mobile (Slide Drawer) --}}
<aside id="mobile-sidebar" class="fixed top-0 left-0 w-72 h-full bg-white dark:bg-[#0f0e2c] text-navy-900 dark:text-white flex flex-col z-[9999] shadow-2xl transform -translate-x-full transition-transform duration-300 ease-in-out md:hidden">
    <div class="p-6 flex-1 text-left overflow-y-auto">
        {{-- Header dengan tombol close --}}
        <div class="flex items-center justify-between mb-8">
            <a href="{{ route('tim_teknis.dashboard') }}" class="flex items-center gap-3 hover:opacity-80 transition-opacity group">
                <div class="w-8 h-8 bg-white dark:bg-[#1e1b4b] rounded-lg overflow-hidden shadow-lg shadow-gold-500/20">
                    <img src="{{ asset('logo_geo-sinfra.png') }}" class="w-full h-full object-contain" alt="Logo">
                </div>
                <span class="font-extrabold text-xl tracking-tighter uppercase text-white">GEO-SINFRA</span>
            </a>
            <button onclick="toggleMobileMenu()" class="w-8 h-8 text-slate-500 hover:text-navy-900 dark:text-slate-400 dark:hover:text-white rounded-lg flex items-center justify-center hover:bg-white/10 transition-all">
                <i class="fas fa-times text-sm"></i>
            </button>
        </div>

        {{-- User info --}}
        <a href="{{ route('tim_teknis.profile') }}" class="flex items-center gap-3 p-3 bg-white/5 rounded-2xl mb-6 border border-slate-200 dark:border-white/5 hover:bg-white/10 transition-all group">
            <div class="w-10 h-10 bg-navy-800 rounded-xl flex items-center justify-center text-gold-500 overflow-hidden border border-white/10">
                @if(auth()->check() && auth()->user()->profile_photo)
                    <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}" class="w-full h-full object-cover">
                @else
                    <i class="fas fa-user-circle text-lg"></i>
                @endif
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-xs font-black text-white uppercase truncate">{{ auth()->check() ? auth()->user()->name : 'Tim Teknis' }}</p>
                <p class="text-xs font-bold text-emerald-400 uppercase mt-0.5">● Aktif</p>
            </div>
            <i class="fas fa-chevron-right text-xs text-slate-500 group-hover:text-gold-500 transition-colors"></i>
        </a>
        
        {{-- Navigation --}}
        <p class="text-xs font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em] mb-3 px-2">Menu Utama</p>
        <nav class="space-y-1">
            <a href="{{ route('tim_teknis.dashboard') }}" 
               class="flex items-center gap-3 px-4 py-3.5 {{ request()->routeIs('tim_teknis.dashboard') ? 'bg-gold-500 text-navy-900 dark:text-white font-bold shadow-lg shadow-gold-500/20' : 'text-slate-500 hover:text-navy-900 hover:bg-slate-100 dark:text-slate-400 dark:hover:text-white dark:hover:bg-white/5' }} rounded-xl text-sm font-semibold transition group text-left whitespace-nowrap">
                <i class="fas fa-th-large text-sm {{ request()->routeIs('tim_teknis.dashboard') ? '' : 'group-hover:text-gold-500' }}"></i> 
                Beranda
            </a>

            <a href="{{ route('tim_teknis.monitoring') }}" 
               class="flex items-center gap-3 px-4 py-3.5 {{ request()->routeIs('tim_teknis.monitoring') ? 'bg-gold-500 text-navy-900 dark:text-white font-bold shadow-lg shadow-gold-500/20' : 'text-slate-500 hover:text-navy-900 hover:bg-slate-100 dark:text-slate-400 dark:hover:text-white dark:hover:bg-white/5' }} rounded-xl text-sm font-semibold transition group text-left whitespace-nowrap">
                <i class="fas fa-satellite-dish text-sm {{ request()->routeIs('tim_teknis.monitoring') ? '' : 'group-hover:text-gold-500' }}"></i> 
                WebGIS Eksekutif
            </a>

            <a href="{{ route('tim_teknis.prioritas') }}" 
               class="flex items-center gap-3 px-4 py-3.5 {{ request()->routeIs('tim_teknis.prioritas') ? 'bg-rose-500 text-navy-900 dark:text-white font-bold shadow-lg shadow-rose-500/20' : 'text-slate-400 hover:text-rose-400 hover:bg-rose-500/10' }} rounded-xl text-sm font-semibold transition group text-left whitespace-nowrap">
                <i class="fas fa-bolt text-sm {{ request()->routeIs('tim_teknis.prioritas') ? 'animate-pulse' : 'text-rose-500 group-hover:text-rose-400' }}"></i> 
                Rekomendasi Prioritas
            </a>

            <a href="{{ route('tim_teknis.validasi') }}" 
               class="flex items-center justify-between px-4 py-3.5 {{ request()->routeIs('tim_teknis.validasi') ? 'bg-gold-500 text-navy-900 dark:text-white font-bold shadow-lg shadow-gold-500/20' : 'text-slate-500 hover:text-navy-900 hover:bg-slate-100 dark:text-slate-400 dark:hover:text-white dark:hover:bg-white/5' }} rounded-xl text-sm font-semibold transition group">
                <div class="flex items-center gap-3">
                    <i class="fas fa-clipboard-check text-sm {{ request()->routeIs('tim_teknis.validasi') ? '' : 'group-hover:text-gold-500' }}"></i> 
                    Validasi Usulan
                </div>
                @if($pendingValidasiCount > 0)
                    <span class="bg-rose-500 text-white text-[10px] font-black px-2 py-0.5 rounded-full shadow-sm animate-pulse">
                        {{ $pendingValidasiCount }}
                    </span>
                @endif
            </a>

            <a href="{{ route('tim_teknis.laporan')  }}" 
               class="flex items-center gap-3 px-4 py-3.5 {{ request()->routeIs('tim_teknis.laporan') ? 'bg-gold-500 text-navy-900 dark:text-white font-bold shadow-lg shadow-gold-500/20' : 'text-slate-500 hover:text-navy-900 hover:bg-slate-100 dark:text-slate-400 dark:hover:text-white dark:hover:bg-white/5' }} rounded-xl text-sm font-semibold transition group text-left whitespace-nowrap">
                <i class="fas fa-print text-sm {{ request()->routeIs('tim_teknis.laporan') ? '' : 'group-hover:text-gold-500' }}"></i> 
                Cetak Laporan
            </a>

            <a href="{{ route('tim_teknis.notifikasi') }}" 
               class="flex items-center justify-between px-4 py-3.5 {{ request()->routeIs('tim_teknis.notifikasi') ? 'bg-gold-500 text-navy-900 dark:text-white font-bold shadow-lg shadow-gold-500/20' : 'text-slate-500 hover:text-navy-900 hover:bg-slate-100 dark:text-slate-400 dark:hover:text-white dark:hover:bg-white/5' }} rounded-xl text-sm font-semibold transition group">
                <div class="flex items-center gap-3">
                    <i class="fas fa-bell text-sm {{ request()->routeIs('tim_teknis.notifikasi') ? '' : 'group-hover:text-gold-500' }}"></i> 
                    Notifikasi
                </div>
                @if(isset($unreadNotifCount) && $unreadNotifCount > 0)
                    <span class="bg-rose-500 text-white text-[10px] font-black px-2 py-0.5 rounded-full shadow-sm">
                        {{ $unreadNotifCount }}
                    </span>
                @endif
            </a>
        </nav>
    </div>

    <div class="p-4 lg:p-6 border-t border-slate-200 dark:border-white/5 text-center lg:text-left bg-slate-50 dark:bg-navy-950/20 relative">
        

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="flex items-center gap-3 px-4 py-3.5 text-red-500 hover:text-red-600 dark:text-red-400 dark:hover:text-red-300 w-full text-left text-sm font-bold transition group rounded-xl hover:bg-red-50 dark:hover:bg-red-500/10">
                <i class="fas fa-sign-out-alt group-hover:-translate-x-1 transition-transform"></i> 
                Keluar
            </button>
        </form>
    </div>
</aside>

<!-- Bottom Navigation Bar (Mobile Only) -->
<nav class="md:hidden fixed bottom-0 left-0 w-full bg-navy-900 border-t border-white/10 z-[9990] flex justify-around items-center px-2 py-3 pb-safe shadow-[0_-10px_30px_rgba(0,0,0,0.3)]">
    <a href="{{ route('tim_teknis.dashboard') }}" class="flex flex-col items-center gap-1.5 p-2 {{ request()->routeIs('tim_teknis.dashboard') ? 'text-gold-500' : 'text-slate-500 hover:text-navy-900 dark:text-slate-400 dark:hover:text-white' }} transition-colors">
        <i class="fas fa-th-large text-xl {{ request()->routeIs('tim_teknis.dashboard') ? '-translate-y-1' : '' }} transition-transform"></i>
        <span class="text-[10px] font-bold uppercase tracking-wider">Beranda</span>
    </a>
    
    <a href="{{ route('tim_teknis.monitoring') }}" class="flex flex-col items-center gap-1.5 p-2 {{ request()->routeIs('tim_teknis.monitoring') ? 'text-gold-500' : 'text-slate-500 hover:text-navy-900 dark:text-slate-400 dark:hover:text-white' }} transition-colors">
        <i class="fas fa-satellite-dish text-xl {{ request()->routeIs('tim_teknis.monitoring') ? '-translate-y-1' : '' }} transition-transform"></i>
        <span class="text-[10px] font-bold uppercase tracking-wider">Peta GIS</span>
    </a>
    
    <a href="{{ route('tim_teknis.validasi') }}" class="flex flex-col items-center gap-1.5 p-2 {{ request()->routeIs('tim_teknis.validasi') ? 'text-gold-500' : 'text-slate-500 hover:text-navy-900 dark:text-slate-400 dark:hover:text-white' }} transition-colors relative">
        <i class="fas fa-clipboard-check text-xl {{ request()->routeIs('tim_teknis.validasi') ? '-translate-y-1' : '' }} transition-transform"></i>
        <span class="text-[10px] font-bold uppercase tracking-wider">Validasi</span>
        @if(isset($pendingValidasiCount) && $pendingValidasiCount > 0)
        <span class="absolute top-0 right-0 bg-rose-500 text-white text-[9px] font-black px-1.5 rounded-full border border-[#0f0e2c]">{{ $pendingValidasiCount }}</span>
        @endif
    </a>
    
    <button onclick="toggleMobileMenu()" class="flex flex-col items-center gap-1.5 p-2 text-slate-500 hover:text-navy-900 dark:text-slate-400 dark:hover:text-white transition-colors relative" id="mobile-menu-btn">
        <i class="fas fa-bars text-xl transition-transform" id="menu-icon"></i>
        <span class="text-[10px] font-bold uppercase tracking-wider">Lainnya</span>
    </button>
</nav>

{{-- Script toggle mobile menu --}}
<script>
    function toggleMobileMenu() {
        const sidebar = document.getElementById('mobile-sidebar');
        const overlay = document.getElementById('mobile-overlay');
        const icon = document.getElementById('menu-icon');
        
        const isOpen = !sidebar.classList.contains('-translate-x-full');
        
        if (isOpen) {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('opacity-0');
            setTimeout(() => overlay.classList.add('hidden'), 300);
            icon.classList.remove('fa-times');
            icon.classList.add('fa-bars');
            if(icon.parentElement.tagName === 'BUTTON') icon.parentElement.classList.remove('text-gold-500');
            document.body.style.overflow = '';
        } else {
            overlay.classList.remove('hidden');
            requestAnimationFrame(() => {
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('opacity-0');
            });
            icon.classList.remove('fa-bars');
            icon.classList.add('fa-times');
            if(icon.parentElement.tagName === 'BUTTON') icon.parentElement.classList.add('text-gold-500');
            document.body.style.overflow = 'hidden';
        }
    }
</script>
