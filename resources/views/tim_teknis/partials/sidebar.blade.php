
{{-- Tombol Hamburger (Hanya muncul di mobile) --}}
<button id="mobile-menu-btn" onclick="toggleMobileMenu()" class="fixed top-4 left-4 z-[9999] w-10 h-10 bg-navy-900 text-gold-500 rounded-xl flex items-center justify-center shadow-lg md:hidden border border-white/10 hover:bg-navy-800 transition-all active:scale-95">
    <i class="fas fa-bars text-sm" id="menu-icon"></i>
</button>

{{-- Overlay Background (muncul saat menu terbuka) --}}
<div id="mobile-overlay" onclick="toggleMobileMenu()" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-[9998] hidden md:hidden transition-opacity duration-300 opacity-0"></div>

{{-- Sidebar Desktop --}}
<aside class="w-64 bg-navy-900 text-white flex-col hidden md:flex shadow-2xl z-20 text-left shrink-0 border-r border-navy-800">
    <div class="p-6 flex-1 text-left">
        <a href="{{ route('tim_teknis.dashboard') }}" class="flex items-center gap-3 mb-10 hover:opacity-80 transition-opacity group">
            <div class="w-8 h-8 bg-white dark:bg-[#1e1b4b] rounded-lg overflow-hidden shadow-lg shadow-gold-500/20 group-hover:scale-110 transition-transform">
                <img src="{{ asset('logo_geo-sinfra.png') }}" class="w-full h-full object-contain" alt="Logo">
            </div>
            <span class="font-extrabold text-xl tracking-tighter uppercase text-white">GEO-SINFRA</span>
        </a>
        
        <nav class="space-y-1">
            <a href="{{ route('tim_teknis.dashboard') }}" 
               class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('tim_teknis.dashboard') ? 'bg-gold-500 text-white font-bold shadow-lg shadow-gold-500/20' : 'text-slate-400 hover:text-white hover:bg-white/5' }} rounded-xl text-sm font-semibold transition group text-left">
                <i class="fas fa-th-large {{ request()->routeIs('tim_teknis.dashboard') ? '' : 'group-hover:text-gold-400' }}"></i> 
                Beranda
            </a>

            <a href="{{ route('tim_teknis.monitoring') }}" 
               class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('tim_teknis.monitoring') ? 'bg-gold-500 text-white font-bold shadow-lg shadow-gold-500/20' : 'text-slate-400 hover:text-white hover:bg-white/5' }} rounded-xl text-sm font-semibold transition group text-left">
                <i class="fas fa-satellite-dish {{ request()->routeIs('tim_teknis.monitoring') ? '' : 'group-hover:text-gold-400' }}"></i> 
                WebGIS Eksekutif
            </a>

            <a href="{{ route('tim_teknis.prioritas') }}" 
               class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('tim_teknis.prioritas') ? 'bg-rose-500 text-white font-bold shadow-lg shadow-rose-500/20' : 'text-slate-400 hover:text-rose-400 hover:bg-rose-500/10' }} rounded-xl text-sm font-semibold transition group text-left">
                <i class="fas fa-bolt {{ request()->routeIs('tim_teknis.prioritas') ? 'animate-pulse' : 'text-rose-500 group-hover:text-rose-400' }}"></i> 
                Rekomendasi Prioritas
            </a>

            @php
                $pendingValidasiCount = \App\Models\Infrastruktur::where('status_verifikasi', 'Verified')->where('status_validasi', 'Pending')->count();
            @endphp
            <a href="{{ route('tim_teknis.validasi') }}" 
               class="flex items-center justify-between px-4 py-3 {{ request()->routeIs('tim_teknis.validasi') ? 'bg-gold-500 text-white font-bold shadow-lg shadow-gold-500/20' : 'text-slate-400 hover:text-white hover:bg-white/5' }} rounded-xl text-sm font-semibold transition group">
                <div class="flex items-center gap-3">
                    <i class="fas fa-clipboard-check {{ request()->routeIs('tim_teknis.validasi') ? '' : 'group-hover:text-gold-400' }}"></i> 
                    Validasi Usulan
                </div>
                @if($pendingValidasiCount > 0)
                    <span class="bg-rose-500 text-white text-xs font-black px-2 py-0.5 rounded-full shadow-sm animate-pulse">
                        {{ $pendingValidasiCount }}
                    </span>
                @endif
            </a>

            <a href="{{ route('tim_teknis.laporan')  }}" 
               class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('tim_teknis.laporan') ? 'bg-gold-500 text-white font-bold shadow-lg shadow-gold-500/20' : 'text-slate-400 hover:text-white hover:bg-white/5' }} rounded-xl text-sm font-semibold transition group text-left">
                <i class="fas fa-print {{ request()->routeIs('tim_teknis.laporan') ? '' : 'group-hover:text-gold-400' }}"></i> 
                Cetak Laporan
            </a>
        </nav>
    </div>

    <div class="p-6 border-t border-white/5 text-left bg-navy-950/20 relative">
        

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="flex items-center gap-3 px-4 py-3.5 text-red-400 hover:text-red-300 w-full text-left text-sm font-bold transition group rounded-xl hover:bg-red-500/10">
                <i class="fas fa-sign-out-alt group-hover:-translate-x-1 transition-transform"></i> 
                Keluar
            </button>
        </form>
    </div>
</aside>

{{-- Sidebar Mobile (Slide Drawer) --}}
<aside id="mobile-sidebar" class="fixed top-0 left-0 w-72 h-full bg-navy-900 text-white flex flex-col z-[9999] shadow-2xl transform -translate-x-full transition-transform duration-300 ease-in-out md:hidden">
    <div class="p-6 flex-1 text-left overflow-y-auto">
        {{-- Header dengan tombol close --}}
        <div class="flex items-center justify-between mb-8">
            <a href="{{ route('tim_teknis.dashboard') }}" class="flex items-center gap-3 hover:opacity-80 transition-opacity group">
                <div class="w-8 h-8 bg-white dark:bg-[#1e1b4b] rounded-lg overflow-hidden shadow-lg shadow-gold-500/20">
                    <img src="{{ asset('logo_geo-sinfra.png') }}" class="w-full h-full object-contain" alt="Logo">
                </div>
                <span class="font-extrabold text-xl tracking-tighter uppercase text-white">GEO-SINFRA</span>
            </a>
            <button onclick="toggleMobileMenu()" class="w-8 h-8 text-slate-400 hover:text-white rounded-lg flex items-center justify-center hover:bg-white/10 transition-all">
                <i class="fas fa-times text-sm"></i>
            </button>
        </div>

        {{-- User info --}}
        <a href="{{ route('tim_teknis.profile') }}" class="flex items-center gap-3 p-3 bg-white/5 rounded-2xl mb-6 border border-white/5 hover:bg-white/10 transition-all group">
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
            <i class="fas fa-chevron-right text-xs text-slate-500 group-hover:text-gold-400 transition-colors"></i>
        </a>
        
        {{-- Navigation --}}
        <p class="text-xs font-black text-slate-500 uppercase tracking-[0.2em] mb-3 px-2">Menu Utama</p>
        <nav class="space-y-1">
            <a href="{{ route('tim_teknis.dashboard') }}" 
               class="flex items-center gap-3 px-4 py-3.5 {{ request()->routeIs('tim_teknis.dashboard') ? 'bg-gold-500 text-white font-bold shadow-lg shadow-gold-500/20' : 'text-slate-400 hover:text-white hover:bg-white/5' }} rounded-xl text-sm font-semibold transition group text-left">
                <i class="fas fa-th-large text-sm {{ request()->routeIs('tim_teknis.dashboard') ? '' : 'group-hover:text-gold-400' }}"></i> 
                Beranda
            </a>

            <a href="{{ route('tim_teknis.monitoring') }}" 
               class="flex items-center gap-3 px-4 py-3.5 {{ request()->routeIs('tim_teknis.monitoring') ? 'bg-gold-500 text-white font-bold shadow-lg shadow-gold-500/20' : 'text-slate-400 hover:text-white hover:bg-white/5' }} rounded-xl text-sm font-semibold transition group text-left">
                <i class="fas fa-satellite-dish text-sm {{ request()->routeIs('tim_teknis.monitoring') ? '' : 'group-hover:text-gold-400' }}"></i> 
                WebGIS Eksekutif
            </a>

            <a href="{{ route('tim_teknis.prioritas') }}" 
               class="flex items-center gap-3 px-4 py-3.5 {{ request()->routeIs('tim_teknis.prioritas') ? 'bg-rose-500 text-white font-bold shadow-lg shadow-rose-500/20' : 'text-slate-400 hover:text-rose-400 hover:bg-rose-500/10' }} rounded-xl text-sm font-semibold transition group text-left">
                <i class="fas fa-bolt text-sm {{ request()->routeIs('tim_teknis.prioritas') ? 'animate-pulse' : 'text-rose-500 group-hover:text-rose-400' }}"></i> 
                Rekomendasi Prioritas
            </a>

            <a href="{{ route('tim_teknis.validasi') }}" 
               class="flex items-center justify-between px-4 py-3.5 {{ request()->routeIs('tim_teknis.validasi') ? 'bg-gold-500 text-white font-bold shadow-lg shadow-gold-500/20' : 'text-slate-400 hover:text-white hover:bg-white/5' }} rounded-xl text-sm font-semibold transition group">
                <div class="flex items-center gap-3">
                    <i class="fas fa-clipboard-check text-sm {{ request()->routeIs('tim_teknis.validasi') ? '' : 'group-hover:text-gold-400' }}"></i> 
                    Validasi Usulan
                </div>
                @if($pendingValidasiCount > 0)
                    <span class="bg-rose-500 text-white text-xs font-black px-2 py-0.5 rounded-full shadow-sm animate-pulse">
                        {{ $pendingValidasiCount }}
                    </span>
                @endif
            </a>

            <a href="{{ route('tim_teknis.laporan')  }}" 
               class="flex items-center gap-3 px-4 py-3.5 {{ request()->routeIs('tim_teknis.laporan') ? 'bg-gold-500 text-white font-bold shadow-lg shadow-gold-500/20' : 'text-slate-400 hover:text-white hover:bg-white/5' }} rounded-xl text-sm font-semibold transition group text-left">
                <i class="fas fa-print text-sm {{ request()->routeIs('tim_teknis.laporan') ? '' : 'group-hover:text-gold-400' }}"></i> 
                Cetak Laporan
            </a>
        </nav>
    </div>

    <div class="p-6 border-t border-white/5 text-left bg-navy-950/20 relative">
        

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="flex items-center gap-3 px-4 py-3.5 text-red-400 hover:text-red-300 w-full text-left text-sm font-bold transition group rounded-xl hover:bg-red-500/10">
                <i class="fas fa-sign-out-alt group-hover:-translate-x-1 transition-transform"></i> 
                Keluar
            </button>
        </form>
    </div>
</aside>

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
            document.body.style.overflow = '';
        } else {
            overlay.classList.remove('hidden');
            requestAnimationFrame(() => {
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('opacity-0');
            });
            icon.classList.remove('fa-bars');
            icon.classList.add('fa-times');
            document.body.style.overflow = 'hidden';
        }
    }
</script>
