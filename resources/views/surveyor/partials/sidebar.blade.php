{{-- Tombol Hamburger (Hanya muncul di mobile) --}}
<button id="mobile-menu-btn" onclick="toggleMobileMenu()" class="fixed top-4 left-4 z-[9999] w-10 h-10 bg-navy-900 text-gold-500 rounded-xl flex items-center justify-center shadow-lg md:hidden border border-white/10 hover:bg-navy-800 transition-all active:scale-95">
    <i class="fas fa-bars text-sm" id="menu-icon"></i>
</button>

{{-- Overlay Background (muncul saat menu terbuka) --}}
<div id="mobile-overlay" onclick="toggleMobileMenu()" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-[9998] hidden md:hidden transition-opacity duration-300 opacity-0"></div>

{{-- Sidebar Desktop (tetap seperti semula) --}}
<aside class="w-64 bg-navy-900 text-white flex-col hidden md:flex shadow-2xl z-20 text-left shrink-0">
    <div class="p-6 flex-1 text-left">
        <a href="{{ route('surveyor.dashboard') }}" class="flex items-center gap-3 mb-10 hover:opacity-80 transition-opacity group">
            <div class="w-8 h-8 bg-white rounded-lg overflow-hidden shadow-lg shadow-gold-500/20 group-hover:scale-110 transition-transform">
                <img src="{{ asset('logo_geo-sinfra.png') }}" class="w-full h-full object-contain" alt="Logo">
            </div>
            <span class="font-extrabold text-xl tracking-tighter uppercase text-white">GEO-SINFRA</span>
        </a>
        
        <nav class="space-y-1">
            <a href="{{ route('surveyor.dashboard') }}" 
               class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('surveyor.dashboard') ? 'bg-gold-500 text-white font-bold shadow-lg shadow-gold-500/20' : 'text-slate-400 hover:text-white hover:bg-white/5' }} rounded-xl text-sm font-semibold transition group text-left">
                <i class="fas fa-th-large {{ request()->routeIs('surveyor.dashboard') ? '' : 'group-hover:text-gold-400' }}"></i> 
                Dashboard
            </a>

            <a href="{{ route('surveyor.laporan') }}" 
               class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('surveyor.laporan') ? 'bg-gold-500 text-white font-bold shadow-lg shadow-gold-500/20' : 'text-slate-400 hover:text-white hover:bg-white/5' }} rounded-xl text-sm font-semibold transition group text-left">
                <i class="fas fa-tasks {{ request()->routeIs('surveyor.laporan') ? '' : 'group-hover:text-gold-400' }}"></i> 
                Penugasan Laporan Warga
            </a>

            <a href="{{ route('surveyor.input') }}" 
               class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('surveyor.input') ? 'bg-gold-500 text-white font-bold shadow-lg shadow-gold-500/20' : 'text-slate-400 hover:text-white hover:bg-white/5' }} rounded-xl text-sm font-semibold transition group text-left">
                <i class="fas fa-plus-circle {{ request()->routeIs('surveyor.input') ? '' : 'group-hover:text-gold-400' }}"></i> 
                Input Data Lapangan
            </a>

            <a href="{{ route('surveyor.history') }}" 
               class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('surveyor.history') ? 'bg-gold-500 text-white font-bold shadow-lg shadow-gold-500/20' : 'text-slate-400 hover:text-white hover:bg-white/5' }} rounded-xl text-sm font-semibold transition group text-left">
                <i class="fas fa-history {{ request()->routeIs('surveyor.history') ? '' : 'group-hover:text-gold-400' }}"></i> 
                Riwayat Data Saya
            </a>

            <a href="{{ route('surveyor.map') }}" 
               class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('surveyor.map') ? 'bg-gold-500 text-white font-bold shadow-lg shadow-gold-500/20' : 'text-slate-400 hover:text-white hover:bg-white/5' }} rounded-xl text-sm font-semibold transition group text-left">
                <i class="fas fa-map-marked-alt {{ request()->routeIs('surveyor.map') ? '' : 'group-hover:text-gold-400' }}"></i> 
                Peta Sebaran Saya
            </a>
        </nav>
    </div>

    <div class="p-6 border-t border-white/5 text-left bg-navy-950/20 relative">
        

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="flex items-center gap-3 px-4 py-3.5 text-red-400 hover:text-red-300 w-full text-left text-sm font-bold transition group rounded-xl hover:bg-red-500/10">
                <i class="fas fa-sign-out-alt group-hover:-translate-x-1 transition-transform"></i> 
                Log Out
            </button>
        </form>
    </div>
</aside>

{{-- Sidebar Mobile (Slide Drawer) --}}
<aside id="mobile-sidebar" class="fixed top-0 left-0 w-72 h-full bg-navy-900 text-white flex flex-col z-[9999] shadow-2xl transform -translate-x-full transition-transform duration-300 ease-in-out md:hidden">
    <div class="p-6 flex-1 text-left overflow-y-auto">
        {{-- Header dengan tombol close --}}
        <div class="flex items-center justify-between mb-8">
            <a href="{{ route('surveyor.dashboard') }}" class="flex items-center gap-3 hover:opacity-80 transition-opacity group">
                <div class="w-8 h-8 bg-white rounded-lg overflow-hidden shadow-lg shadow-gold-500/20">
                    <img src="{{ asset('logo_geo-sinfra.png') }}" class="w-full h-full object-contain" alt="Logo">
                </div>
                <span class="font-extrabold text-xl tracking-tighter uppercase text-white">GEO-SINFRA</span>
            </a>
            <button onclick="toggleMobileMenu()" class="w-8 h-8 text-slate-400 hover:text-white rounded-lg flex items-center justify-center hover:bg-white/10 transition-all">
                <i class="fas fa-times text-sm"></i>
            </button>
        </div>

        {{-- User info --}}
        <a href="{{ route('surveyor.profile') }}" class="flex items-center gap-3 p-3 bg-white/5 rounded-2xl mb-6 border border-white/5 hover:bg-white/10 transition-all group">
            <div class="w-10 h-10 bg-navy-800 rounded-xl flex items-center justify-center text-gold-500 overflow-hidden border border-white/10">
                @if(auth()->user()->profile_photo)
                    <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}" class="w-full h-full object-cover">
                @else
                    <i class="fas fa-user-circle text-lg"></i>
                @endif
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-xs font-black text-white uppercase truncate">{{ auth()->user()->name }}</p>
                <p class="text-xs font-bold text-emerald-400 uppercase mt-0.5">● Online</p>
            </div>
            <i class="fas fa-chevron-right text-xs text-slate-500 group-hover:text-gold-400 transition-colors"></i>
        </a>
        
        {{-- Navigation --}}
        <p class="text-xs font-black text-slate-500 uppercase tracking-[0.2em] mb-3 px-2">Menu Utama</p>
        <nav class="space-y-1">
            <a href="{{ route('surveyor.dashboard') }}" 
               class="flex items-center gap-3 px-4 py-3.5 {{ request()->routeIs('surveyor.dashboard') ? 'bg-gold-500 text-white font-bold shadow-lg shadow-gold-500/20' : 'text-slate-400 hover:text-white hover:bg-white/5' }} rounded-xl text-sm font-semibold transition group text-left">
                <i class="fas fa-th-large text-sm {{ request()->routeIs('surveyor.dashboard') ? '' : 'group-hover:text-gold-400' }}"></i> 
                Dashboard
            </a>

            <a href="{{ route('surveyor.laporan') }}" 
               class="flex items-center gap-3 px-4 py-3.5 {{ request()->routeIs('surveyor.laporan') ? 'bg-gold-500 text-white font-bold shadow-lg shadow-gold-500/20' : 'text-slate-400 hover:text-white hover:bg-white/5' }} rounded-xl text-sm font-semibold transition group text-left">
                <i class="fas fa-tasks text-sm {{ request()->routeIs('surveyor.laporan') ? '' : 'group-hover:text-gold-400' }}"></i> 
                Penugasan Laporan Warga
            </a>

            <a href="{{ route('surveyor.input') }}" 
               class="flex items-center gap-3 px-4 py-3.5 {{ request()->routeIs('surveyor.input') ? 'bg-gold-500 text-white font-bold shadow-lg shadow-gold-500/20' : 'text-slate-400 hover:text-white hover:bg-white/5' }} rounded-xl text-sm font-semibold transition group text-left">
                <i class="fas fa-plus-circle text-sm {{ request()->routeIs('surveyor.input') ? '' : 'group-hover:text-gold-400' }}"></i> 
                Input Data Lapangan
            </a>

            <a href="{{ route('surveyor.history') }}" 
               class="flex items-center gap-3 px-4 py-3.5 {{ request()->routeIs('surveyor.history') ? 'bg-gold-500 text-white font-bold shadow-lg shadow-gold-500/20' : 'text-slate-400 hover:text-white hover:bg-white/5' }} rounded-xl text-sm font-semibold transition group text-left">
                <i class="fas fa-history text-sm {{ request()->routeIs('surveyor.history') ? '' : 'group-hover:text-gold-400' }}"></i> 
                Riwayat Data Saya
            </a>

            <a href="{{ route('surveyor.map') }}" 
               class="flex items-center gap-3 px-4 py-3.5 {{ request()->routeIs('surveyor.map') ? 'bg-gold-500 text-white font-bold shadow-lg shadow-gold-500/20' : 'text-slate-400 hover:text-white hover:bg-white/5' }} rounded-xl text-sm font-semibold transition group text-left">
                <i class="fas fa-map-marked-alt text-sm {{ request()->routeIs('surveyor.map') ? '' : 'group-hover:text-gold-400' }}"></i> 
                Peta Sebaran Saya
            </a>
        </nav>
    </div>

    <div class="p-6 border-t border-white/5 text-left">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="flex items-center gap-3 px-4 py-3.5 text-red-400 hover:text-red-300 w-full text-left text-sm font-bold transition group rounded-xl hover:bg-red-500/10">
                <i class="fas fa-sign-out-alt group-hover:-translate-x-1 transition-transform"></i> 
                Log Out
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
            // Tutup
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('opacity-0');
            setTimeout(() => overlay.classList.add('hidden'), 300);
            icon.classList.remove('fa-times');
            icon.classList.add('fa-bars');
            document.body.style.overflow = '';
        } else {
            // Buka
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

    
     else {
            menu.classList.add('hidden');
        }
    }

     else if (theme === 'light') {
            localStorage.theme = 'light';
            document.documentElement.classList.remove('dark');
        } else {
            localStorage.removeItem('theme');
            if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        }
        
        document.getElementById('theme-menu-desktop').classList.add('hidden');
        document.getElementById('theme-menu-mobile').classList.add('hidden');
        
        // Pemicu event kustom agar chart/peta bisa dirender ulang jika perlu
        window.dispatchEvent(new Event('themeChanged'));
    }

    // Menutup menu jika klik di luar
    
</script>
