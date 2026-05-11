<aside class="w-64 bg-[#1e1b4b] text-white flex flex-col hidden md:flex shadow-2xl z-20 text-left">
    <div class="p-6 flex-1 text-left">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 mb-10 hover:opacity-80 transition-opacity group">
            <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center shadow-lg shadow-blue-500/20 group-hover:scale-110 transition-transform">
                <i class="fas fa-city text-xs text-white"></i>
            </div>
            <span class="font-extrabold text-xl tracking-tighter uppercase text-white">GEO-SINFRA</span>
        </a>
        
        <nav class="space-y-1">
            <a href="{{ route('admin.dashboard') }}" 
               class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('admin.dashboard') ? 'bg-blue-600 text-white font-bold shadow-lg shadow-blue-900/20' : 'text-gray-400 hover:text-white hover:bg-white/5' }} rounded-xl text-sm font-semibold transition group text-left">
                <i class="fas fa-home {{ request()->routeIs('admin.dashboard') ? '' : 'group-hover:text-blue-400' }}"></i> 
                Dashboard
            </a>

            <a href="{{ route('admin.users') }}" 
               class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('admin.users*') ? 'bg-blue-600 text-white font-bold shadow-lg shadow-blue-900/20' : 'text-gray-400 hover:text-white hover:bg-white/5' }} rounded-xl text-sm font-semibold transition group text-left">
                <i class="fas fa-users-cog {{ request()->routeIs('admin.users*') ? '' : 'group-hover:text-blue-400' }}"></i> 
                Manajemen Pengguna
            </a>

            <a href="{{ route('admin.wilayah') }}" 
               class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('admin.wilayah*') ? 'bg-blue-600 text-white font-bold shadow-lg shadow-blue-900/20' : 'text-gray-400 hover:text-white hover:bg-white/5' }} rounded-xl text-sm font-semibold transition group text-left">
                <i class="fas fa-sitemap {{ request()->routeIs('admin.wilayah*') ? '' : 'group-hover:text-blue-400' }}"></i> 
                Manajemen Wilayah
            </a>

            <a href="{{ route('admin.infrastruktur') }}" 
               class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('admin.infrastruktur*') ? 'bg-blue-600 text-white font-bold shadow-lg shadow-blue-900/20' : 'text-gray-400 hover:text-white hover:bg-white/5' }} rounded-xl text-sm font-semibold transition group text-left">
                <i class="fas fa-database {{ request()->routeIs('admin.infrastruktur*') ? '' : 'group-hover:text-blue-400' }}"></i> 
                Manajemen Infrastruktur
            </a>



            <a href="{{ route('admin.statistik') }}" 
               class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('admin.statistik') ? 'bg-blue-600 text-white font-bold shadow-lg shadow-blue-900/20' : 'text-gray-400 hover:text-white hover:bg-white/5' }} rounded-xl text-sm font-semibold transition group text-left">
                <i class="fas fa-chart-bar {{ request()->routeIs('admin.statistik') ? '' : 'group-hover:text-blue-400' }}"></i> 
                Ringkasan Statistik
            </a>

            <a href="{{ route('admin.statistik.tahunan') }}" 
               class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('admin.statistik.tahunan') ? 'bg-blue-600 text-white font-bold shadow-lg shadow-blue-900/20' : 'text-gray-400 hover:text-white hover:bg-white/5' }} rounded-xl text-sm font-semibold transition group text-left">
                <i class="fas fa-calendar-alt {{ request()->routeIs('admin.statistik.tahunan') ? '' : 'group-hover:text-blue-400' }}"></i> 
                Statistik Tahunan
            </a>
        </nav>
    </div>

    <div class="p-6 border-t border-white/5 text-left">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="flex items-center gap-3 px-4 py-3 text-red-400 hover:text-red-300 w-full text-left text-sm font-bold transition group">
                <i class="fas fa-sign-out-alt group-hover:-translate-x-1 transition-transform"></i> 
                Keluar Sistem
            </button>
        </form>
    </div>
</aside>