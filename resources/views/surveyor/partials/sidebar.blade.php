{{-- Hamburger button dihapus, diganti Bottom Nav --}}

{{-- Overlay Background (muncul saat menu terbuka) --}}
<div id="mobile-overlay" onclick="toggleMobileMenu()" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-[9998] hidden md:hidden transition-opacity duration-300 opacity-0"></div>

{{-- Sidebar Desktop & Tablet --}}
<aside class="w-20 lg:w-64 bg-navy-900 text-navy-900 dark:text-white flex-col hidden md:flex shadow-2xl z-20 text-left shrink-0 transition-all duration-300">
    <div class="p-4 lg:p-6 flex-1 text-left overflow-y-auto custom-scrollbar">
        <a href="{{ route('surveyor.dashboard') }}" class="flex items-center justify-center lg:justify-start gap-3 mb-10 hover:opacity-80 transition-opacity group">
            <div class="w-8 h-8 bg-white rounded-lg overflow-hidden shadow-lg shadow-gold-500/20 group-hover:scale-110 transition-transform shrink-0">
                <img src="{{ asset('logo_geo-sinfra.png') }}" class="w-full h-full object-contain" alt="Logo">
            </div>
            <span class="font-extrabold text-xl tracking-tighter uppercase text-navy-900 dark:text-white hidden lg:block">GEO-SINFRA</span>
        </a>
        
        <nav class="space-y-1">
            <a href="{{ route('surveyor.dashboard') }}" 
               class="flex items-center justify-center lg:justify-start gap-3 px-0 lg:px-4 py-3 {{ request()->routeIs('surveyor.dashboard') ? 'bg-gold-500 text-navy-900 dark:text-white font-bold shadow-lg shadow-gold-500/20' : 'text-slate-500 hover:text-navy-900 hover:bg-slate-100 dark:text-slate-400 dark:hover:text-white dark:hover:bg-white/5' }} rounded-xl text-sm font-semibold transition group text-left" title="Beranda">
                <i class="fas fa-th-large text-lg lg:text-base {{ request()->routeIs('surveyor.dashboard') ? '' : 'group-hover:text-gold-400' }}"></i> 
                <span class="hidden lg:inline">Beranda</span>
            </a>

            <a href="{{ route('surveyor.laporan') }}" 
               class="flex items-center justify-center lg:justify-start gap-3 px-0 lg:px-4 py-3 {{ request()->routeIs('surveyor.laporan') ? 'bg-gold-500 text-navy-900 dark:text-white font-bold shadow-lg shadow-gold-500/20' : 'text-slate-500 hover:text-navy-900 hover:bg-slate-100 dark:text-slate-400 dark:hover:text-white dark:hover:bg-white/5' }} rounded-xl text-sm font-semibold transition group text-left" title="Penugasan Laporan Warga">
                <i class="fas fa-tasks text-lg lg:text-base {{ request()->routeIs('surveyor.laporan') ? '' : 'group-hover:text-gold-400' }}"></i> 
                <span class="hidden lg:inline">Penugasan Laporan</span>
            </a>

            <a href="{{ route('surveyor.input') }}" 
               class="flex items-center justify-center lg:justify-start gap-3 px-0 lg:px-4 py-3 {{ request()->routeIs('surveyor.input') ? 'bg-gold-500 text-navy-900 dark:text-white font-bold shadow-lg shadow-gold-500/20' : 'text-slate-500 hover:text-navy-900 hover:bg-slate-100 dark:text-slate-400 dark:hover:text-white dark:hover:bg-white/5' }} rounded-xl text-sm font-semibold transition group text-left" title="Input Data Lapangan">
                <i class="fas fa-plus-circle text-lg lg:text-base {{ request()->routeIs('surveyor.input') ? '' : 'group-hover:text-gold-400' }}"></i> 
                <span class="hidden lg:inline">Input Data</span>
            </a>

            <a href="{{ route('surveyor.history') }}" 
               class="flex items-center justify-center lg:justify-start gap-3 px-0 lg:px-4 py-3 {{ request()->routeIs('surveyor.history') ? 'bg-gold-500 text-navy-900 dark:text-white font-bold shadow-lg shadow-gold-500/20' : 'text-slate-500 hover:text-navy-900 hover:bg-slate-100 dark:text-slate-400 dark:hover:text-white dark:hover:bg-white/5' }} rounded-xl text-sm font-semibold transition group text-left" title="Riwayat Data Saya">
                <i class="fas fa-history text-lg lg:text-base {{ request()->routeIs('surveyor.history') ? '' : 'group-hover:text-gold-400' }}"></i> 
                <span class="hidden lg:inline">Riwayat Data Saya</span>
            </a>

            <a href="{{ route('surveyor.map') }}" 
               class="flex items-center justify-center lg:justify-start gap-3 px-0 lg:px-4 py-3 {{ request()->routeIs('surveyor.map') ? 'bg-gold-500 text-navy-900 dark:text-white font-bold shadow-lg shadow-gold-500/20' : 'text-slate-500 hover:text-navy-900 hover:bg-slate-100 dark:text-slate-400 dark:hover:text-white dark:hover:bg-white/5' }} rounded-xl text-sm font-semibold transition group text-left" title="Peta Sebaran Saya">
                <i class="fas fa-map-marked-alt text-lg lg:text-base {{ request()->routeIs('surveyor.map') ? '' : 'group-hover:text-gold-400' }}"></i> 
                <span class="hidden lg:inline">Peta Sebaran Saya</span>
            </a>
        </nav>
    </div>

    <div class="p-4 lg:p-6 border-t border-slate-200 dark:border-white/5 text-center lg:text-left bg-slate-50 dark:bg-navy-950/20 relative flex flex-col items-center lg:items-stretch">
        <form method="POST" action="{{ route('logout') }}" class="w-full">
            @csrf
            <button type="submit" class="flex items-center justify-center lg:justify-start gap-3 px-0 lg:px-4 py-3.5 text-red-500 hover:text-red-600 dark:text-red-400 dark:hover:text-red-300 w-full text-left text-sm font-bold transition group rounded-xl hover:bg-red-50 dark:hover:bg-red-500/10" title="Keluar">
                <i class="fas fa-sign-out-alt text-lg lg:text-base group-hover:-translate-x-1 transition-transform"></i> 
                <span class="hidden lg:inline">Keluar</span>
            </button>
        </form>
    </div>
</aside>

{{-- Sidebar Mobile (Slide Drawer) --}}
<aside id="mobile-sidebar" class="fixed top-0 left-0 w-72 h-full bg-navy-900 text-navy-900 dark:text-white flex flex-col z-[9999] shadow-2xl transform -translate-x-full transition-transform duration-300 ease-in-out md:hidden">
    <div class="p-6 flex-1 text-left overflow-y-auto">
        {{-- Header dengan tombol close --}}
        <div class="flex items-center justify-between mb-8">
            <a href="{{ route('surveyor.dashboard') }}" class="flex items-center gap-3 hover:opacity-80 transition-opacity group">
                <div class="w-8 h-8 bg-white rounded-lg overflow-hidden shadow-lg shadow-gold-500/20">
                    <img src="{{ asset('logo_geo-sinfra.png') }}" class="w-full h-full object-contain" alt="Logo">
                </div>
                <span class="font-extrabold text-xl tracking-tighter uppercase text-white">GEO-SINFRA</span>
            </a>
            <button onclick="toggleMobileMenu()" class="w-8 h-8 text-slate-500 hover:text-navy-900 dark:text-slate-400 dark:hover:text-white rounded-lg flex items-center justify-center hover:bg-white/10 transition-all">
                <i class="fas fa-times text-sm"></i>
            </button>
        </div>
        
        {{-- Navigation --}}
        <p class="text-xs font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em] mb-3 px-2">Menu Utama</p>
        <nav class="space-y-1">
            <a href="{{ route('surveyor.dashboard') }}" 
               class="flex items-center gap-3 px-4 py-3.5 {{ request()->routeIs('surveyor.dashboard') ? 'bg-gold-500 text-navy-900 dark:text-white font-bold shadow-lg shadow-gold-500/20' : 'text-slate-500 hover:text-navy-900 hover:bg-slate-100 dark:text-slate-400 dark:hover:text-white dark:hover:bg-white/5' }} rounded-xl text-sm font-semibold transition group text-left">
                <i class="fas fa-th-large text-sm {{ request()->routeIs('surveyor.dashboard') ? '' : 'group-hover:text-gold-400' }}"></i> 
                Beranda
            </a>

            <a href="{{ route('surveyor.laporan') }}" 
               class="flex items-center gap-3 px-4 py-3.5 {{ request()->routeIs('surveyor.laporan') ? 'bg-gold-500 text-navy-900 dark:text-white font-bold shadow-lg shadow-gold-500/20' : 'text-slate-500 hover:text-navy-900 hover:bg-slate-100 dark:text-slate-400 dark:hover:text-white dark:hover:bg-white/5' }} rounded-xl text-sm font-semibold transition group text-left">
                <i class="fas fa-tasks text-sm {{ request()->routeIs('surveyor.laporan') ? '' : 'group-hover:text-gold-400' }}"></i> 
                Penugasan Laporan Warga
            </a>

            <a href="{{ route('surveyor.input') }}" 
               class="flex items-center gap-3 px-4 py-3.5 {{ request()->routeIs('surveyor.input') ? 'bg-gold-500 text-navy-900 dark:text-white font-bold shadow-lg shadow-gold-500/20' : 'text-slate-500 hover:text-navy-900 hover:bg-slate-100 dark:text-slate-400 dark:hover:text-white dark:hover:bg-white/5' }} rounded-xl text-sm font-semibold transition group text-left">
                <i class="fas fa-plus-circle text-sm {{ request()->routeIs('surveyor.input') ? '' : 'group-hover:text-gold-400' }}"></i> 
                Input Data Lapangan
            </a>

            <a href="{{ route('surveyor.history') }}" 
               class="flex items-center gap-3 px-4 py-3.5 {{ request()->routeIs('surveyor.history') ? 'bg-gold-500 text-navy-900 dark:text-white font-bold shadow-lg shadow-gold-500/20' : 'text-slate-500 hover:text-navy-900 hover:bg-slate-100 dark:text-slate-400 dark:hover:text-white dark:hover:bg-white/5' }} rounded-xl text-sm font-semibold transition group text-left">
                <i class="fas fa-history text-sm {{ request()->routeIs('surveyor.history') ? '' : 'group-hover:text-gold-400' }}"></i> 
                Riwayat Data Saya
            </a>

            <a href="{{ route('surveyor.map') }}" 
               class="flex items-center gap-3 px-4 py-3.5 {{ request()->routeIs('surveyor.map') ? 'bg-gold-500 text-navy-900 dark:text-white font-bold shadow-lg shadow-gold-500/20' : 'text-slate-500 hover:text-navy-900 hover:bg-slate-100 dark:text-slate-400 dark:hover:text-white dark:hover:bg-white/5' }} rounded-xl text-sm font-semibold transition group text-left">
                <i class="fas fa-map-marked-alt text-sm {{ request()->routeIs('surveyor.map') ? '' : 'group-hover:text-gold-400' }}"></i> 
                Peta Sebaran Saya
            </a>
        </nav>
    </div>

    <div class="p-6 border-t border-slate-200 dark:border-white/5 text-left">
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
    <a href="{{ route('surveyor.dashboard') }}" class="flex flex-col items-center gap-1.5 p-2 {{ request()->routeIs('surveyor.dashboard') ? 'text-gold-500' : 'text-slate-500 hover:text-navy-900 dark:text-slate-400 dark:hover:text-white' }} transition-colors">
        <i class="fas fa-th-large text-xl {{ request()->routeIs('surveyor.dashboard') ? '-translate-y-1' : '' }} transition-transform"></i>
        <span class="text-[10px] font-bold uppercase tracking-wider">Beranda</span>
    </a>
    
    <a href="{{ route('surveyor.laporan') }}" class="flex flex-col items-center gap-1.5 p-2 {{ request()->routeIs('surveyor.laporan') ? 'text-gold-500' : 'text-slate-500 hover:text-navy-900 dark:text-slate-400 dark:hover:text-white' }} transition-colors">
        <i class="fas fa-tasks text-xl {{ request()->routeIs('surveyor.laporan') ? '-translate-y-1' : '' }} transition-transform"></i>
        <span class="text-[10px] font-bold uppercase tracking-wider">Penugasan</span>
    </a>
    
    <a href="{{ route('surveyor.input') }}" class="flex flex-col items-center gap-1.5 p-2 {{ request()->routeIs('surveyor.input') ? 'text-gold-500' : 'text-slate-500 hover:text-navy-900 dark:text-slate-400 dark:hover:text-white' }} transition-colors relative">
        <i class="fas fa-plus-circle text-xl {{ request()->routeIs('surveyor.input') ? '-translate-y-1' : '' }} transition-transform"></i>
        <span class="text-[10px] font-bold uppercase tracking-wider">Input</span>
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
