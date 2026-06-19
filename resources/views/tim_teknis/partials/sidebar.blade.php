<aside class="w-64 bg-navy-900 text-white flex flex-col hidden md:flex shadow-2xl z-20 text-left border-r border-navy-800">
    <div class="p-6 flex-1 text-left">
        <a href="{{ route('tim_teknis.dashboard') }}" class="flex items-center gap-3 mb-10 hover:opacity-80 transition-opacity group">
            <div class="w-8 h-8 bg-white rounded-lg overflow-hidden shadow-lg shadow-gold-500/20 group-hover:scale-110 transition-transform">
                <img src="{{ asset('logo_geo-sinfra.png') }}" class="w-full h-full object-contain" alt="Logo">
            </div>
            <span class="font-extrabold text-xl tracking-tighter uppercase text-white">GEO-SINFRA</span>
        </a>
        
        <nav class="space-y-1">
            <a href="{{ route('tim_teknis.dashboard') }}" 
               class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('tim_teknis.dashboard') ? 'bg-gold-500 text-white font-bold shadow-lg shadow-gold-500/20' : 'text-slate-400 hover:text-white hover:bg-white/5' }} rounded-xl text-sm font-semibold transition group text-left">
                <i class="fas fa-th-large {{ request()->routeIs('tim_teknis.dashboard') ? '' : 'group-hover:text-gold-400' }}"></i> 
                Dashboard
            </a>

            <a href="{{ route('tim_teknis.monitoring') }}" 
               class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('tim_teknis.monitoring') ? 'bg-gold-500 text-white font-bold shadow-lg shadow-gold-500/20' : 'text-slate-400 hover:text-white hover:bg-white/5' }} rounded-xl text-sm font-semibold transition group text-left">
                <i class="fas fa-satellite-dish {{ request()->routeIs('tim_teknis.monitoring') ? '' : 'group-hover:text-gold-400' }}"></i> 
                Executive WebGIS
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
                    <span class="bg-rose-500 text-white text-[10px] font-black px-2 py-0.5 rounded-full shadow-sm animate-pulse">
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

    <div class="p-6 border-t border-white/5 text-left">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="flex items-center gap-3 px-4 py-3 text-[#be123c] hover:text-red-400 w-full text-left text-sm font-bold transition group">
                <i class="fas fa-sign-out-alt group-hover:-translate-x-1 transition-transform"></i> 
                Keluar Sistem
            </button>
        </form>
    </div>
</aside>
