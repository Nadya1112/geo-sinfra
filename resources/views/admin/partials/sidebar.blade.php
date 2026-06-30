{{-- Tombol Hamburger (Hanya muncul di mobile) --}}
@if(!request()->routeIs('admin.profile'))
<button id="mobile-menu-btn" onclick="toggleMobileMenu()" class="fixed top-4 left-4 z-[9999] w-10 h-10 bg-navy-900 text-gold-500 rounded-xl flex items-center justify-center shadow-lg md:hidden border border-white/10 hover:bg-navy-800 transition-all active:scale-95">
    <i class="fas fa-bars text-sm" id="menu-icon"></i>
</button>
@endif

{{-- Overlay Background (muncul saat menu terbuka) --}}
<div id="mobile-overlay" onclick="toggleMobileMenu()" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-[9998] hidden md:hidden transition-opacity duration-300 opacity-0"></div>

{{-- Sidebar Desktop --}}
<aside class="w-64 bg-[#0f0e2c] text-white flex-col hidden md:flex shadow-2xl z-20 text-left border-r border-white/5 shrink-0 h-screen">
    <div class="p-6 flex-1 text-left overflow-y-auto custom-scrollbar">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 mb-10 hover:opacity-85 transition-opacity group">
            <div class="w-9 h-9 bg-white rounded-xl overflow-hidden shadow-lg shadow-navy-950/40 group-hover:scale-105 transition-all">
                <img src="{{ asset('logo_geo-sinfra.png') }}" class="w-full h-full object-contain" alt="Logo">
            </div>
            <span class="font-extrabold text-lg tracking-tighter uppercase text-white">GEO-SINFRA</span>
        </a>
        
        <nav class="space-y-1.5">
            <a href="{{ route('admin.dashboard') }}" 
               class="flex items-center gap-3 px-4 py-3.5 {{ request()->routeIs('admin.dashboard') ? 'bg-gold-500 text-navy-950 font-bold shadow-xl shadow-gold-500/10' : 'text-slate-400 hover:text-white hover:bg-white/5' }} rounded-xl text-sm font-semibold transition group text-left">
                <i class="fas fa-home {{ request()->routeIs('admin.dashboard') ? '' : 'group-hover:text-gold-500' }}"></i> 
                Dashboard
            </a>

            <a href="{{ route('admin.users') }}" 
               class="flex items-center gap-3 px-4 py-3.5 {{ request()->routeIs('admin.users*') ? 'bg-gold-500 text-navy-950 font-bold shadow-xl shadow-gold-500/10' : 'text-slate-400 hover:text-white hover:bg-white/5' }} rounded-xl text-sm font-semibold transition group text-left">
                <i class="fas fa-users-cog {{ request()->routeIs('admin.users*') ? '' : 'group-hover:text-gold-500' }}"></i> 
                Manajemen Pengguna
            </a>

            <a href="{{ route('admin.wilayah') }}" 
               class="flex items-center gap-3 px-4 py-3.5 {{ request()->routeIs('admin.wilayah*') ? 'bg-gold-500 text-navy-950 font-bold shadow-xl shadow-gold-500/10' : 'text-slate-400 hover:text-white hover:bg-white/5' }} rounded-xl text-sm font-semibold transition group text-left">
                <i class="fas fa-sitemap {{ request()->routeIs('admin.wilayah*') ? '' : 'group-hover:text-gold-500' }}"></i> 
                Manajemen Wilayah
            </a>

            <a href="{{ route('admin.infrastruktur') }}" 
               class="flex items-center gap-3 px-4 py-3.5 {{ request()->routeIs('admin.infrastruktur*') ? 'bg-gold-500 text-navy-950 font-bold shadow-xl shadow-gold-500/10' : 'text-slate-400 hover:text-white hover:bg-white/5' }} rounded-xl text-sm font-semibold transition group text-left">
                <i class="fas fa-database {{ request()->routeIs('admin.infrastruktur*') ? '' : 'group-hover:text-gold-500' }}"></i> 
                Manajemen Infrastruktur
            </a>

            <a href="{{ route('admin.laporan-warga') }}" 
               class="flex items-center justify-between px-4 py-3.5 {{ request()->routeIs('admin.laporan-warga*') ? 'bg-gold-500 text-navy-950 font-bold shadow-xl shadow-gold-500/10' : 'text-slate-400 hover:text-white hover:bg-white/5' }} rounded-xl text-sm font-semibold transition group text-left relative w-full">
                <div class="flex items-center gap-3">
                    <i class="fas fa-bullhorn {{ request()->routeIs('admin.laporan-warga*') ? '' : 'group-hover:text-gold-500' }}"></i> 
                    <span>Laporan Warga</span>
                </div>
                @if(isset($laporanMenungguCount) && $laporanMenungguCount > 0)
                <span class="bg-red-500 text-white text-xs font-black px-1.5 py-0.5 rounded-md min-w-[20px] text-center shadow-lg">{{ $laporanMenungguCount }}</span>
                @endif
            </a>

            <a href="{{ route('admin.statistik') }}" 
               class="flex items-center gap-3 px-4 py-3.5 {{ request()->routeIs('admin.statistik') ? 'bg-gold-500 text-navy-950 font-bold shadow-xl shadow-gold-500/10' : 'text-slate-400 hover:text-white hover:bg-white/5' }} rounded-xl text-sm font-semibold transition group text-left">
                <i class="fas fa-chart-bar {{ request()->routeIs('admin.statistik') ? '' : 'group-hover:text-gold-500' }}"></i> 
                Ringkasan Statistik
            </a>

            <a href="{{ route('admin.statistik.tahunan') }}" 
               class="flex items-center gap-3 px-4 py-3.5 {{ request()->routeIs('admin.statistik.tahunan') ? 'bg-gold-500 text-navy-950 font-bold shadow-xl shadow-gold-500/10' : 'text-slate-400 hover:text-white hover:bg-white/5' }} rounded-xl text-sm font-semibold transition group text-left">
                <i class="fas fa-calendar-alt {{ request()->routeIs('admin.statistik.tahunan') ? '' : 'group-hover:text-gold-500' }}"></i> 
                Statistik Tahunan
            </a>

            <div class="pt-4 mt-2 border-t border-white/5">
                <p class="text-xs font-black text-slate-500 uppercase tracking-[0.2em] mb-3 px-2">Sistem & Keamanan</p>
                <a href="{{ route('admin.activity') }}" 
                   class="flex items-center gap-3 px-4 py-3.5 {{ request()->routeIs('admin.activity') ? 'bg-gold-500 text-navy-950 font-bold shadow-xl shadow-gold-500/10' : 'text-slate-400 hover:text-white hover:bg-white/5' }} rounded-xl text-sm font-semibold transition group text-left">
                    <i class="fas fa-shield-alt {{ request()->routeIs('admin.activity') ? '' : 'group-hover:text-gold-500' }}"></i> 
                    Log Aktivitas
                </a>
                
                <!-- Simulasi AI -->
                <a href="{{ route('admin.simulasi-ai') }}" 
                   class="flex items-center gap-3 px-4 py-3.5 {{ request()->routeIs('admin.simulasi-ai') ? 'bg-gold-500 text-navy-950 font-bold shadow-xl shadow-gold-500/10' : 'text-slate-400 hover:text-white hover:bg-white/5' }} rounded-xl text-sm font-semibold transition group text-left mt-1">
                    <i class="fas fa-robot {{ request()->routeIs('admin.simulasi-ai') ? '' : 'group-hover:text-gold-500' }}"></i> 
                    Simulasi Model AI
                </a>
            </div>
        </nav>
    </div>

    <div class="p-6 border-t border-white/5 text-left bg-navy-950/20 relative">


        <a href="{{ route('admin.settings') }}" class="flex items-center gap-3 px-4 py-3 mb-2 {{ request()->routeIs('admin.settings') ? 'bg-gold-500 text-navy-950 font-bold shadow-xl shadow-gold-500/10' : 'text-slate-400 hover:text-white hover:bg-white/5' }} rounded-xl text-sm font-bold transition group">
            <i class="fas fa-cog group-hover:text-gold-500 transition-colors"></i>
            <span>Pengaturan</span>
        </a>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="flex items-center gap-3 px-4 py-3 text-red-400 hover:text-red-300 w-full text-left text-sm font-bold transition group hover:bg-red-500/10 rounded-xl">
                <i class="fas fa-sign-out-alt group-hover:-translate-x-1 transition-transform"></i> 
                Log Out
            </button>
        </form>
    </div>
</aside>

{{-- Sidebar Mobile (Slide Drawer) --}}
<aside id="mobile-sidebar" class="fixed top-0 left-0 w-72 h-full bg-[#0f0e2c] text-white flex flex-col z-[9999] shadow-2xl transform -translate-x-full transition-transform duration-300 ease-in-out md:hidden">
    <div class="p-6 flex-1 text-left overflow-y-auto">
        <div class="flex items-center justify-between mb-8">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 hover:opacity-85 transition-opacity group">
                <div class="w-9 h-9 bg-white rounded-xl overflow-hidden shadow-lg shadow-navy-950/40">
                    <img src="{{ asset('logo_geo-sinfra.png') }}" class="w-full h-full object-contain" alt="Logo">
                </div>
                <span class="font-extrabold text-lg tracking-tighter uppercase text-white">GEO-SINFRA</span>
            </a>
            <button onclick="toggleMobileMenu()" class="w-8 h-8 text-slate-400 hover:text-white rounded-lg flex items-center justify-center hover:bg-white/10 transition-all">
                <i class="fas fa-times text-sm"></i>
            </button>
        </div>

        {{-- User info --}}
        <a href="{{ route('admin.profile') }}" class="flex items-center gap-3 p-3 bg-white/5 rounded-2xl mb-6 border border-white/5 hover:bg-white/10 transition-all group">
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
        
        <p class="text-xs font-black text-slate-500 uppercase tracking-[0.2em] mb-3 px-2">Menu Utama</p>
        <nav class="space-y-1.5">
            <a href="{{ route('admin.dashboard') }}" 
               class="flex items-center gap-3 px-4 py-3.5 {{ request()->routeIs('admin.dashboard') ? 'bg-gold-500 text-navy-950 font-bold shadow-xl shadow-gold-500/10' : 'text-slate-400 hover:text-white hover:bg-white/5' }} rounded-xl text-sm font-semibold transition group text-left">
                <i class="fas fa-home {{ request()->routeIs('admin.dashboard') ? '' : 'group-hover:text-gold-500' }}"></i> 
                Dashboard
            </a>

            <a href="{{ route('admin.users') }}" 
               class="flex items-center gap-3 px-4 py-3.5 {{ request()->routeIs('admin.users*') ? 'bg-gold-500 text-navy-950 font-bold shadow-xl shadow-gold-500/10' : 'text-slate-400 hover:text-white hover:bg-white/5' }} rounded-xl text-sm font-semibold transition group text-left">
                <i class="fas fa-users-cog {{ request()->routeIs('admin.users*') ? '' : 'group-hover:text-gold-500' }}"></i> 
                Manajemen Pengguna
            </a>

            <a href="{{ route('admin.wilayah') }}" 
               class="flex items-center gap-3 px-4 py-3.5 {{ request()->routeIs('admin.wilayah*') ? 'bg-gold-500 text-navy-950 font-bold shadow-xl shadow-gold-500/10' : 'text-slate-400 hover:text-white hover:bg-white/5' }} rounded-xl text-sm font-semibold transition group text-left">
                <i class="fas fa-sitemap {{ request()->routeIs('admin.wilayah*') ? '' : 'group-hover:text-gold-500' }}"></i> 
                Manajemen Wilayah
            </a>

            <a href="{{ route('admin.infrastruktur') }}" 
               class="flex items-center gap-3 px-4 py-3.5 {{ request()->routeIs('admin.infrastruktur*') ? 'bg-gold-500 text-navy-950 font-bold shadow-xl shadow-gold-500/10' : 'text-slate-400 hover:text-white hover:bg-white/5' }} rounded-xl text-sm font-semibold transition group text-left">
                <i class="fas fa-database {{ request()->routeIs('admin.infrastruktur*') ? '' : 'group-hover:text-gold-500' }}"></i> 
                Manajemen Infrastruktur
            </a>

            <a href="{{ route('admin.laporan-warga') }}" 
               class="flex items-center justify-between px-4 py-3.5 {{ request()->routeIs('admin.laporan-warga*') ? 'bg-gold-500 text-navy-950 font-bold shadow-xl shadow-gold-500/10' : 'text-slate-400 hover:text-white hover:bg-white/5' }} rounded-xl text-sm font-semibold transition group text-left relative w-full">
                <div class="flex items-center gap-3">
                    <i class="fas fa-bullhorn {{ request()->routeIs('admin.laporan-warga*') ? '' : 'group-hover:text-gold-500' }}"></i> 
                    <span>Laporan Warga</span>
                </div>
                @if(isset($laporanMenungguCount) && $laporanMenungguCount > 0)
                <span class="bg-red-500 text-white text-xs font-black px-1.5 py-0.5 rounded-md min-w-[20px] text-center shadow-lg">{{ $laporanMenungguCount }}</span>
                @endif
            </a>

            <a href="{{ route('admin.statistik') }}" 
               class="flex items-center gap-3 px-4 py-3.5 {{ request()->routeIs('admin.statistik') ? 'bg-gold-500 text-navy-950 font-bold shadow-xl shadow-gold-500/10' : 'text-slate-400 hover:text-white hover:bg-white/5' }} rounded-xl text-sm font-semibold transition group text-left">
                <i class="fas fa-chart-bar {{ request()->routeIs('admin.statistik') ? '' : 'group-hover:text-gold-500' }}"></i> 
                Ringkasan Statistik
            </a>

            <a href="{{ route('admin.statistik.tahunan') }}" 
               class="flex items-center gap-3 px-4 py-3.5 {{ request()->routeIs('admin.statistik.tahunan') ? 'bg-gold-500 text-navy-950 font-bold shadow-xl shadow-gold-500/10' : 'text-slate-400 hover:text-white hover:bg-white/5' }} rounded-xl text-sm font-semibold transition group text-left">
                <i class="fas fa-calendar-alt {{ request()->routeIs('admin.statistik.tahunan') ? '' : 'group-hover:text-gold-500' }}"></i> 
                Statistik Tahunan
            </a>

            <div class="pt-4 mt-2 border-t border-white/5">
                <p class="text-xs font-black text-slate-500 uppercase tracking-[0.2em] mb-3 px-2">Sistem & Keamanan</p>
                <a href="{{ route('admin.activity') }}" 
                   class="flex items-center gap-3 px-4 py-3.5 {{ request()->routeIs('admin.activity') ? 'bg-gold-500 text-navy-950 font-bold shadow-xl shadow-gold-500/10' : 'text-slate-400 hover:text-white hover:bg-white/5' }} rounded-xl text-sm font-semibold transition group text-left">
                    <i class="fas fa-shield-alt {{ request()->routeIs('admin.activity') ? '' : 'group-hover:text-gold-500' }}"></i> 
                    Log Aktivitas
                </a>
                
                <!-- Simulasi AI -->
                <a href="{{ route('admin.simulasi-ai') }}" 
                   class="flex items-center gap-3 px-4 py-3.5 {{ request()->routeIs('admin.simulasi-ai') ? 'bg-gold-500 text-navy-950 font-bold shadow-xl shadow-gold-500/10' : 'text-slate-400 hover:text-white hover:bg-white/5' }} rounded-xl text-sm font-semibold transition group text-left mt-1">
                    <i class="fas fa-robot {{ request()->routeIs('admin.simulasi-ai') ? '' : 'group-hover:text-gold-500' }}"></i> 
                    Simulasi Model AI
                </a>
            </div>
        </nav>
    </div>

    <div class="p-6 border-t border-white/5 text-left bg-navy-950/20 relative">


        <a href="{{ route('admin.settings') }}" class="flex items-center gap-3 px-4 py-3.5 mb-2 {{ request()->routeIs('admin.settings') ? 'bg-gold-500 text-navy-950 font-bold shadow-xl shadow-gold-500/10' : 'text-slate-400 hover:text-white hover:bg-white/5' }} rounded-xl text-sm font-bold transition group">
            <i class="fas fa-cog group-hover:text-gold-500 transition-colors"></i>
            <span>Pengaturan</span>
        </a>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="flex items-center gap-3 px-4 py-3.5 text-red-400 hover:text-red-300 w-full text-left text-sm font-bold transition group rounded-xl hover:bg-red-500/10">
                <i class="fas fa-sign-out-alt group-hover:-translate-x-1 transition-transform"></i> 
                Log Out
            </button>
        </form>
    </div>
</aside>

<!-- Real-time Notification Toast Container -->
<div id="notification-container" class="fixed bottom-6 right-6 z-[9999] flex flex-col gap-3"></div>

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

