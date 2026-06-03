<aside class="w-64 bg-[#0f0e2c] text-white flex flex-col hidden md:flex shadow-2xl z-20 text-left border-r border-white/5">
    <div class="p-6 flex-1 text-left">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 mb-10 hover:opacity-85 transition-opacity group">
            <div class="w-9 h-9 bg-navy-900 rounded-xl flex items-center justify-center text-gold-500 border border-white/10 shadow-lg shadow-navy-950/40 group-hover:scale-105 transition-all">
                <i class="fas fa-globe-asia text-xs"></i>
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
        </nav>
    </div>

    <div class="p-6 border-t border-white/5 text-left bg-navy-950/20">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="flex items-center gap-3 px-4 py-3 text-red-400 hover:text-red-300 w-full text-left text-sm font-bold transition group">
                <i class="fas fa-sign-out-alt group-hover:-translate-x-1 transition-transform"></i> 
                Keluar Sistem
            </button>
        </form>
    </div>
</aside>