{{-- Hamburger button dihapus, diganti Bottom Nav --}}

{{-- Overlay Background (muncul saat menu terbuka) --}}
<div id="mobile-overlay" onclick="toggleMobileMenu()" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-[9998] hidden md:hidden transition-opacity duration-300 opacity-0"></div>

{{-- Sidebar Desktop & Tablet --}}
<aside class="w-20 lg:w-64 bg-white dark:bg-[#0f0e2c] text-navy-900 dark:text-white flex-col hidden md:flex shadow-2xl z-20 text-left border-r border-slate-200 dark:border-white/5 shrink-0 h-screen transition-all duration-300">
    <div class="p-4 lg:p-6 flex-1 text-left overflow-y-auto custom-scrollbar">
        <a href="{{ route('admin.dashboard') }}" wire:navigate class="flex items-center justify-center lg:justify-start gap-3 mb-10 hover:opacity-85 transition-opacity group">
            <div class="w-9 h-9 bg-white rounded-xl overflow-hidden shadow-lg shadow-navy-950/10 dark:shadow-navy-950/40 group-hover:scale-105 transition-all shrink-0">
                <img src="{{ asset('logo_geo-sinfra.png') }}" class="w-full h-full object-contain" alt="Logo">
            </div>
            <span class="font-extrabold text-lg tracking-tighter uppercase text-navy-900 dark:text-white hidden lg:block">GEO-SINFRA</span>
        </a>
        
        <nav class="space-y-1.5">
            <a href="{{ route('admin.dashboard') }}" wire:navigate 
               class="flex items-center justify-center lg:justify-start gap-3 px-0 lg:px-4 py-3.5 {{ request()->routeIs('admin.dashboard') ? 'bg-gold-500 text-navy-950 font-bold shadow-xl shadow-gold-500/10' : 'text-slate-500 hover:text-navy-900 hover:bg-slate-100 dark:text-slate-400 dark:hover:text-white dark:hover:bg-white/5' }} rounded-xl text-sm font-semibold transition group text-left whitespace-nowrap" title="Beranda">
                <i class="fas fa-home {{ request()->routeIs('admin.dashboard') ? '' : 'group-hover:text-gold-500' }}"></i> 
                <span class="hidden lg:inline">Beranda</span>
            </a>

            <a href="{{ route('admin.users') }}" wire:navigate 
               class="flex items-center justify-center lg:justify-start gap-3 px-0 lg:px-4 py-3.5 {{ request()->routeIs('admin.users*') ? 'bg-gold-500 text-navy-950 font-bold shadow-xl shadow-gold-500/10' : 'text-slate-500 hover:text-navy-900 hover:bg-slate-100 dark:text-slate-400 dark:hover:text-white dark:hover:bg-white/5' }} rounded-xl text-sm font-semibold transition group text-left whitespace-nowrap" title="Manajemen Pengguna">
                <i class="fas fa-users-cog {{ request()->routeIs('admin.users*') ? '' : 'group-hover:text-gold-500' }}"></i> 
                <span class="hidden lg:inline">Manajemen Pengguna</span>
            </a>

            <a href="{{ route('admin.wilayah') }}" wire:navigate 
               class="flex items-center justify-center lg:justify-start gap-3 px-0 lg:px-4 py-3.5 {{ request()->routeIs('admin.wilayah*') ? 'bg-gold-500 text-navy-950 font-bold shadow-xl shadow-gold-500/10' : 'text-slate-500 hover:text-navy-900 hover:bg-slate-100 dark:text-slate-400 dark:hover:text-white dark:hover:bg-white/5' }} rounded-xl text-sm font-semibold transition group text-left whitespace-nowrap" title="Manajemen Wilayah">
                <i class="fas fa-sitemap {{ request()->routeIs('admin.wilayah*') ? '' : 'group-hover:text-gold-500' }}"></i> 
                <span class="hidden lg:inline">Manajemen Wilayah</span>
            </a>

            <a href="{{ route('admin.infrastruktur') }}" wire:navigate 
               class="flex items-center justify-center lg:justify-start gap-3 px-0 lg:px-4 py-3.5 {{ request()->routeIs('admin.infrastruktur*') ? 'bg-gold-500 text-navy-950 font-bold shadow-xl shadow-gold-500/10' : 'text-slate-500 hover:text-navy-900 hover:bg-slate-100 dark:text-slate-400 dark:hover:text-white dark:hover:bg-white/5' }} rounded-xl text-sm font-semibold transition group text-left whitespace-nowrap" title="Manajemen Infrastruktur">
                <i class="fas fa-database {{ request()->routeIs('admin.infrastruktur*') ? '' : 'group-hover:text-gold-500' }}"></i> 
                <span class="hidden lg:inline">Manajemen Infrastruktur</span>
            </a>

            <a href="{{ route('admin.laporan-warga') }}" wire:navigate 
               class="flex items-center justify-center lg:justify-between px-0 lg:px-4 py-3.5 {{ request()->routeIs('admin.laporan-warga*') ? 'bg-gold-500 text-navy-950 font-bold shadow-xl shadow-gold-500/10' : 'text-slate-500 hover:text-navy-900 hover:bg-slate-100 dark:text-slate-400 dark:hover:text-white dark:hover:bg-white/5' }} rounded-xl text-sm font-semibold transition group text-left whitespace-nowrap relative w-full" title="Laporan Warga">
                <div class="flex items-center gap-3">
                    <i class="fas fa-bullhorn {{ request()->routeIs('admin.laporan-warga*') ? '' : 'group-hover:text-gold-500' }}"></i> 
                    <span class="hidden lg:inline">Laporan Warga</span>
                </div>
                @if(!request()->routeIs('admin.laporan-warga*') && isset($laporanMenungguCount) && $laporanMenungguCount > 0)
                <span class="absolute top-1 right-1 lg:relative lg:top-auto lg:right-auto bg-red-500 text-white text-[10px] lg:text-xs font-black px-1.5 py-0.5 rounded-md min-w-[16px] lg:min-w-[20px] text-center shadow-lg">{{ $laporanMenungguCount }}</span>
                @endif
            </a>

            <a href="{{ route('admin.statistik') }}" wire:navigate 
               class="flex items-center justify-center lg:justify-start gap-3 px-0 lg:px-4 py-3.5 {{ request()->routeIs('admin.statistik') ? 'bg-gold-500 text-navy-950 font-bold shadow-xl shadow-gold-500/10' : 'text-slate-500 hover:text-navy-900 hover:bg-slate-100 dark:text-slate-400 dark:hover:text-white dark:hover:bg-white/5' }} rounded-xl text-sm font-semibold transition group text-left whitespace-nowrap" title="Ringkasan Statistik">
                <i class="fas fa-chart-bar {{ request()->routeIs('admin.statistik') ? '' : 'group-hover:text-gold-500' }}"></i> 
                <span class="hidden lg:inline">Ringkasan Statistik</span>
            </a>

            <a href="{{ route('admin.statistik.tahunan') }}" wire:navigate 
               class="flex items-center justify-center lg:justify-start gap-3 px-0 lg:px-4 py-3.5 {{ request()->routeIs('admin.statistik.tahunan') ? 'bg-gold-500 text-navy-950 font-bold shadow-xl shadow-gold-500/10' : 'text-slate-500 hover:text-navy-900 hover:bg-slate-100 dark:text-slate-400 dark:hover:text-white dark:hover:bg-white/5' }} rounded-xl text-sm font-semibold transition group text-left whitespace-nowrap" title="Statistik Tahunan">
                <i class="fas fa-calendar-alt {{ request()->routeIs('admin.statistik.tahunan') ? '' : 'group-hover:text-gold-500' }}"></i> 
                <span class="hidden lg:inline">Statistik Tahunan</span>
            </a>

            <div class="pt-4 mt-2 border-t border-slate-200 dark:border-white/5 flex flex-col items-center lg:items-stretch">
                <p class="text-xs font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em] mb-3 px-2 hidden lg:block">Sistem & Keamanan</p>
                <i class="fas fa-ellipsis-h text-slate-600 mb-3 block lg:hidden" title="Sistem & Keamanan"></i>
                <a href="{{ route('admin.activity') }}" wire:navigate 
                   class="flex items-center justify-center lg:justify-start gap-3 px-0 lg:px-4 py-3.5 {{ request()->routeIs('admin.activity') ? 'bg-gold-500 text-navy-950 font-bold shadow-xl shadow-gold-500/10' : 'text-slate-500 hover:text-navy-900 hover:bg-slate-100 dark:text-slate-400 dark:hover:text-white dark:hover:bg-white/5' }} rounded-xl text-sm font-semibold transition group text-left whitespace-nowrap w-full" title="Log Aktivitas">
                    <i class="fas fa-shield-alt {{ request()->routeIs('admin.activity') ? '' : 'group-hover:text-gold-500' }}"></i> 
                    <span class="hidden lg:inline">Log Aktivitas</span>
                </a>
                
                <!-- Simulasi AI -->
                <a href="{{ route('admin.simulasi-ai') }}" wire:navigate 
                   class="flex items-center justify-center lg:justify-start gap-3 px-0 lg:px-4 py-3.5 {{ request()->routeIs('admin.simulasi-ai') ? 'bg-gold-500 text-navy-950 font-bold shadow-xl shadow-gold-500/10' : 'text-slate-500 hover:text-navy-900 hover:bg-slate-100 dark:text-slate-400 dark:hover:text-white dark:hover:bg-white/5' }} rounded-xl text-sm font-semibold transition group text-left whitespace-nowrap mt-1 w-full" title="Simulasi Model AI">
                    <i class="fas fa-robot {{ request()->routeIs('admin.simulasi-ai') ? '' : 'group-hover:text-gold-500' }}"></i> 
                    <span class="hidden lg:inline">Simulasi Model AI</span>
                </a>
            </div>
        </nav>
    </div>

    <div class="p-4 lg:p-6 border-t border-slate-200 dark:border-white/5 text-center lg:text-left bg-slate-50 dark:bg-navy-950/20 relative flex flex-col items-center lg:items-stretch">
        <a href="{{ route('admin.settings') }}" wire:navigate class="flex items-center justify-center lg:justify-start gap-3 px-0 lg:px-4 py-3 mb-2 {{ request()->routeIs('admin.settings') ? 'bg-gold-500 text-navy-950 font-bold shadow-xl shadow-gold-500/10' : 'text-slate-500 hover:text-navy-900 hover:bg-slate-100 dark:text-slate-400 dark:hover:text-white dark:hover:bg-white/5' }} rounded-xl text-sm font-bold transition group w-full" title="Pengaturan">
            <i class="fas fa-cog group-hover:text-gold-500 transition-colors"></i>
            <span class="hidden lg:inline">Pengaturan</span>
        </a>

        <form method="POST" action="{{ route('logout') }}" class="w-full">
            @csrf
            <button type="submit" class="flex items-center justify-center lg:justify-start gap-3 px-0 lg:px-4 py-3 text-red-500 hover:text-red-600 dark:text-red-400 dark:hover:text-red-300 w-full text-left text-sm font-bold transition group hover:bg-red-50 dark:hover:bg-red-500/10 rounded-xl" title="Keluar">
                <i class="fas fa-sign-out-alt group-hover:-translate-x-1 transition-transform"></i> 
                <span class="hidden lg:inline">Keluar</span>
            </button>
        </form>
    </div>
</aside>

{{-- Sidebar Mobile (Slide Drawer) --}}
<aside id="mobile-sidebar" class="fixed top-0 left-0 w-72 h-full bg-white dark:bg-[#0f0e2c] text-navy-900 dark:text-white flex flex-col z-[9999] shadow-2xl transform -translate-x-full transition-transform duration-300 ease-in-out md:hidden">
    <div class="p-6 flex-1 text-left overflow-y-auto">
        <div class="flex items-center justify-between mb-8">
            <a href="{{ route('admin.dashboard') }}" wire:navigate class="flex items-center gap-3 hover:opacity-85 transition-opacity group">
                <div class="w-9 h-9 bg-white rounded-xl overflow-hidden shadow-lg shadow-navy-950/10 dark:shadow-navy-950/40">
                    <img src="{{ asset('logo_geo-sinfra.png') }}" class="w-full h-full object-contain" alt="Logo">
                </div>
                <span class="font-extrabold text-lg tracking-tighter uppercase text-white">GEO-SINFRA</span>
            </a>
            <button onclick="toggleMobileMenu()" class="w-8 h-8 text-slate-500 hover:text-navy-900 dark:text-slate-400 dark:hover:text-white rounded-lg flex items-center justify-center hover:bg-white/10 transition-all">
                <i class="fas fa-times text-sm"></i>
            </button>
        </div>


        
        <p class="text-xs font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em] mb-3 px-2">Menu Utama</p>
        <nav class="space-y-1.5">
            <a href="{{ route('admin.dashboard') }}" wire:navigate 
               class="flex items-center gap-3 px-4 py-3.5 {{ request()->routeIs('admin.dashboard') ? 'bg-gold-500 text-navy-950 font-bold shadow-xl shadow-gold-500/10' : 'text-slate-500 hover:text-navy-900 hover:bg-slate-100 dark:text-slate-400 dark:hover:text-white dark:hover:bg-white/5' }} rounded-xl text-sm font-semibold transition group text-left whitespace-nowrap">
                <i class="fas fa-home {{ request()->routeIs('admin.dashboard') ? '' : 'group-hover:text-gold-500' }}"></i> 
                Beranda
            </a>

            <a href="{{ route('admin.users') }}" wire:navigate 
               class="flex items-center gap-3 px-4 py-3.5 {{ request()->routeIs('admin.users*') ? 'bg-gold-500 text-navy-950 font-bold shadow-xl shadow-gold-500/10' : 'text-slate-500 hover:text-navy-900 hover:bg-slate-100 dark:text-slate-400 dark:hover:text-white dark:hover:bg-white/5' }} rounded-xl text-sm font-semibold transition group text-left whitespace-nowrap">
                <i class="fas fa-users-cog {{ request()->routeIs('admin.users*') ? '' : 'group-hover:text-gold-500' }}"></i> 
                Manajemen Pengguna
            </a>

            <a href="{{ route('admin.wilayah') }}" wire:navigate 
               class="flex items-center gap-3 px-4 py-3.5 {{ request()->routeIs('admin.wilayah*') ? 'bg-gold-500 text-navy-950 font-bold shadow-xl shadow-gold-500/10' : 'text-slate-500 hover:text-navy-900 hover:bg-slate-100 dark:text-slate-400 dark:hover:text-white dark:hover:bg-white/5' }} rounded-xl text-sm font-semibold transition group text-left whitespace-nowrap">
                <i class="fas fa-sitemap {{ request()->routeIs('admin.wilayah*') ? '' : 'group-hover:text-gold-500' }}"></i> 
                Manajemen Wilayah
            </a>

            <a href="{{ route('admin.infrastruktur') }}" wire:navigate 
               class="flex items-center gap-3 px-4 py-3.5 {{ request()->routeIs('admin.infrastruktur*') ? 'bg-gold-500 text-navy-950 font-bold shadow-xl shadow-gold-500/10' : 'text-slate-500 hover:text-navy-900 hover:bg-slate-100 dark:text-slate-400 dark:hover:text-white dark:hover:bg-white/5' }} rounded-xl text-sm font-semibold transition group text-left whitespace-nowrap">
                <i class="fas fa-database {{ request()->routeIs('admin.infrastruktur*') ? '' : 'group-hover:text-gold-500' }}"></i> 
                Manajemen Infrastruktur
            </a>

            <a href="{{ route('admin.laporan-warga') }}" wire:navigate onclick="const badge = this.querySelector('.laporan-badge'); if(badge) badge.remove();"
               class="flex items-center justify-between px-4 py-3.5 {{ request()->routeIs('admin.laporan-warga*') ? 'bg-gold-500 text-navy-950 font-bold shadow-xl shadow-gold-500/10' : 'text-slate-500 hover:text-navy-900 hover:bg-slate-100 dark:text-slate-400 dark:hover:text-white dark:hover:bg-white/5' }} rounded-xl text-sm font-semibold transition group text-left whitespace-nowrap relative w-full">
                <div class="flex items-center gap-3">
                    <i class="fas fa-bullhorn {{ request()->routeIs('admin.laporan-warga*') ? '' : 'group-hover:text-gold-500' }}"></i> 
                    <span>Laporan Warga</span>
                </div>
                @if(!request()->routeIs('admin.laporan-warga*') && isset($laporanMenungguCount) && $laporanMenungguCount > 0)
                <span class="laporan-badge bg-red-500 text-white text-xs font-black px-1.5 py-0.5 rounded-md min-w-[20px] text-center shadow-lg">{{ $laporanMenungguCount }}</span>
                @endif
            </a>

            <a href="{{ route('admin.statistik') }}" wire:navigate 
               class="flex items-center gap-3 px-4 py-3.5 {{ request()->routeIs('admin.statistik') ? 'bg-gold-500 text-navy-950 font-bold shadow-xl shadow-gold-500/10' : 'text-slate-500 hover:text-navy-900 hover:bg-slate-100 dark:text-slate-400 dark:hover:text-white dark:hover:bg-white/5' }} rounded-xl text-sm font-semibold transition group text-left whitespace-nowrap">
                <i class="fas fa-chart-bar {{ request()->routeIs('admin.statistik') ? '' : 'group-hover:text-gold-500' }}"></i> 
                Ringkasan Statistik
            </a>

            <a href="{{ route('admin.statistik.tahunan') }}" wire:navigate 
               class="flex items-center gap-3 px-4 py-3.5 {{ request()->routeIs('admin.statistik.tahunan') ? 'bg-gold-500 text-navy-950 font-bold shadow-xl shadow-gold-500/10' : 'text-slate-500 hover:text-navy-900 hover:bg-slate-100 dark:text-slate-400 dark:hover:text-white dark:hover:bg-white/5' }} rounded-xl text-sm font-semibold transition group text-left whitespace-nowrap">
                <i class="fas fa-calendar-alt {{ request()->routeIs('admin.statistik.tahunan') ? '' : 'group-hover:text-gold-500' }}"></i> 
                Statistik Tahunan
            </a>

            <div class="pt-4 mt-2 border-t border-slate-200 dark:border-white/5">
                <p class="text-xs font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em] mb-3 px-2">Sistem & Keamanan</p>
                <a href="{{ route('admin.activity') }}" wire:navigate 
                   class="flex items-center gap-3 px-4 py-3.5 {{ request()->routeIs('admin.activity') ? 'bg-gold-500 text-navy-950 font-bold shadow-xl shadow-gold-500/10' : 'text-slate-500 hover:text-navy-900 hover:bg-slate-100 dark:text-slate-400 dark:hover:text-white dark:hover:bg-white/5' }} rounded-xl text-sm font-semibold transition group text-left whitespace-nowrap">
                    <i class="fas fa-shield-alt {{ request()->routeIs('admin.activity') ? '' : 'group-hover:text-gold-500' }}"></i> 
                    Log Aktivitas
                </a>
                
                <!-- Simulasi AI -->
                <a href="{{ route('admin.simulasi-ai') }}" wire:navigate 
                   class="flex items-center gap-3 px-4 py-3.5 {{ request()->routeIs('admin.simulasi-ai') ? 'bg-gold-500 text-navy-950 font-bold shadow-xl shadow-gold-500/10' : 'text-slate-500 hover:text-navy-900 hover:bg-slate-100 dark:text-slate-400 dark:hover:text-white dark:hover:bg-white/5' }} rounded-xl text-sm font-semibold transition group text-left whitespace-nowrap mt-1">
                    <i class="fas fa-robot {{ request()->routeIs('admin.simulasi-ai') ? '' : 'group-hover:text-gold-500' }}"></i> 
                    Simulasi Model AI
                </a>
            </div>
        </nav>
    </div>

    <div class="p-6 border-t border-slate-200 dark:border-white/5 text-left bg-slate-50 dark:bg-navy-950/20 relative">


        <a href="{{ route('admin.settings') }}" wire:navigate class="flex items-center gap-3 px-4 py-3.5 mb-2 {{ request()->routeIs('admin.settings') ? 'bg-gold-500 text-navy-950 font-bold shadow-xl shadow-gold-500/10' : 'text-slate-500 hover:text-navy-900 hover:bg-slate-100 dark:text-slate-400 dark:hover:text-white dark:hover:bg-white/5' }} rounded-xl text-sm font-bold transition group">
            <i class="fas fa-cog group-hover:text-gold-500 transition-colors"></i>
            <span>Pengaturan</span>
        </a>

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
<nav class="md:hidden fixed bottom-0 left-0 w-full bg-white dark:bg-[#0f0e2c] border-t border-slate-200 dark:border-white/10 z-[9990] flex justify-around items-center px-2 py-3 pb-safe shadow-[0_-10px_30px_rgba(0,0,0,0.3)]">
    <a href="{{ route('admin.dashboard') }}" wire:navigate class="flex flex-col items-center gap-1.5 p-2 {{ request()->routeIs('admin.dashboard') ? 'text-gold-500' : 'text-slate-500 hover:text-navy-900 dark:text-slate-400 dark:hover:text-white' }} transition-colors">
        <i class="fas fa-home text-xl {{ request()->routeIs('admin.dashboard') ? '-translate-y-1' : '' }} transition-transform"></i>
        <span class="text-[10px] font-bold uppercase tracking-wider">Beranda</span>
    </a>
    
    <a href="{{ route('admin.infrastruktur') }}" wire:navigate class="flex flex-col items-center gap-1.5 p-2 {{ request()->routeIs('admin.infrastruktur*') ? 'text-gold-500' : 'text-slate-500 hover:text-navy-900 dark:text-slate-400 dark:hover:text-white' }} transition-colors">
        <i class="fas fa-database text-xl {{ request()->routeIs('admin.infrastruktur*') ? '-translate-y-1' : '' }} transition-transform"></i>
        <span class="text-[10px] font-bold uppercase tracking-wider">Aset</span>
    </a>
    
    <a href="{{ route('admin.laporan-warga') }}" wire:navigate class="flex flex-col items-center gap-1.5 p-2 {{ request()->routeIs('admin.laporan-warga*') ? 'text-gold-500' : 'text-slate-500 hover:text-navy-900 dark:text-slate-400 dark:hover:text-white' }} transition-colors relative">
        <i class="fas fa-bullhorn text-xl {{ request()->routeIs('admin.laporan-warga*') ? '-translate-y-1' : '' }} transition-transform"></i>
        <span class="text-[10px] font-bold uppercase tracking-wider">Laporan</span>
        @if(isset($laporanMenungguCount) && $laporanMenungguCount > 0)
        <span class="absolute top-0 right-0 bg-red-500 text-white text-[9px] font-black px-1.5 rounded-full border border-[#0f0e2c]">{{ $laporanMenungguCount }}</span>
        @endif
    </a>
    
    <button onclick="toggleMobileMenu()" class="flex flex-col items-center gap-1.5 p-2 text-slate-500 hover:text-navy-900 dark:text-slate-400 dark:hover:text-white transition-colors relative" id="mobile-menu-btn">
        <i class="fas fa-bars text-xl transition-transform" id="menu-icon"></i>
        <span class="text-[10px] font-bold uppercase tracking-wider">Lainnya</span>
    </button>
</nav>

<!-- Real-time Notification Toast Container -->
<div id="notification-container" class="fixed bottom-28 md:bottom-6 right-6 z-[9999] flex flex-col gap-3"></div>

<!-- Notification Audio Element -->
<audio id="notification-sound" src="https://actions.google.com/sounds/v1/alarms/beep_short.ogg" preload="auto"></audio>

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
            icon.parentElement.classList.remove('text-gold-500');
            document.body.style.overflow = '';
        } else {
            overlay.classList.remove('hidden');
            requestAnimationFrame(() => {
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('opacity-0');
            });
            icon.classList.remove('fa-bars');
            icon.classList.add('fa-times');
            icon.parentElement.classList.add('text-gold-500');
            document.body.style.overflow = 'hidden';
        }
    }
</script>

