<aside class="w-64 bg-[#1e1b4b] text-white flex flex-col hidden md:flex shadow-2xl z-20 text-left">
    <div class="p-6 flex-1 text-left">
        <a href="{{ route('surveyor.dashboard') }}" class="flex items-center gap-3 mb-10 hover:opacity-80 transition-opacity group">
            <div class="w-8 h-8 bg-emerald-500 rounded-lg flex items-center justify-center shadow-lg shadow-emerald-500/20 group-hover:scale-110 transition-transform">
                <i class="fas fa-camera text-xs text-white"></i>
            </div>
            <span class="font-extrabold text-xl tracking-tighter uppercase text-white">SINFRA-CORE</span>
        </a>
        
        <nav class="space-y-1">
            <a href="{{ route('surveyor.dashboard') }}" 
               class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('surveyor.dashboard') ? 'bg-emerald-600 text-white font-bold shadow-lg shadow-emerald-900/20' : 'text-gray-400 hover:text-white hover:bg-white/5' }} rounded-xl text-sm font-semibold transition group text-left">
                <i class="fas fa-th-large {{ request()->routeIs('surveyor.dashboard') ? '' : 'group-hover:text-emerald-400' }}"></i> 
                Dashboard
            </a>

            <a href="#" 
               class="flex items-center gap-3 px-4 py-3 text-gray-400 hover:text-white hover:bg-white/5 rounded-xl text-sm font-semibold transition group text-left">
                <i class="fas fa-plus-circle group-hover:text-emerald-400"></i> 
                Input Data Baru
            </a>

            <a href="#" 
               class="flex items-center gap-3 px-4 py-3 text-gray-400 hover:text-white hover:bg-white/5 rounded-xl text-sm font-semibold transition group text-left">
                <i class="fas fa-history group-hover:text-emerald-400"></i> 
                Riwayat Survey
            </a>

            <a href="#" 
               class="flex items-center gap-3 px-4 py-3 text-gray-400 hover:text-white hover:bg-white/5 rounded-xl text-sm font-semibold transition group text-left">
                <i class="fas fa-chart-pie group-hover:text-emerald-400"></i> 
                Statistik Saya
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
