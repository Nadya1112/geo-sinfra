@php
    $profileRoute = '#';
    $notifRoute = '#';
    if(auth()->check()) {
        $role = auth()->user()->role;
        if($role === 'admin') {
            $profileRoute = route('admin.profile');
            $notifRoute = route('admin.activity');
        } elseif($role === 'surveyor') {
            $profileRoute = route('surveyor.profile');
            $notifRoute = route('surveyor.history');
        } elseif($role === 'tim_teknis') {
            $profileRoute = route('tim_teknis.profile');
            $notifRoute = route('tim_teknis.notifikasi');
        }
    }
@endphp

<header class="sticky top-0 bg-white/80 dark:bg-navy-950/80 backdrop-blur-xl border-b border-slate-100 dark:border-white/5 px-4 pl-16 md:px-8 py-4 flex justify-between items-center z-40 text-left transition-colors duration-300 shadow-sm">
    <div class="flex items-center gap-2 md:gap-4 text-left">
        @hasSection('back_url')
        <a href="@yield('back_url')" class="hidden md:flex w-10 h-10 items-center justify-center bg-white dark:bg-navy-900 text-slate-400 rounded-xl hover:bg-gold-50 hover:text-gold-600 transition-all border border-slate-200 dark:border-white/10 hover:border-gold-200">
            <i class="fas fa-arrow-left text-sm"></i>
        </a>
        @endif
        <div>
            <p class="text-xs font-black text-gold-500 uppercase tracking-wider mb-1">@yield('subtitle', 'Portal SINFRA')</p>
            <h2 class="text-xl font-black text-navy-900 dark:text-white leading-none">@yield('page_title', 'Beranda Utama')</h2>
        </div>
    </div>

    <div class="flex items-center gap-3 md:gap-6">
        <div class="text-right">
            <p class="text-xs font-black text-navy-900 dark:text-white" id="mini-clock">00:00 WITA</p>
            <p class="text-[10px] font-bold text-emerald-500 uppercase mt-0.5 sm:hidden">Aktif</p>
            <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-tighter hidden sm:block">{{ now()->translatedFormat('d M Y') }}</p>
        </div>
        <div class="h-8 w-[1px] bg-slate-100 dark:bg-white/10"></div>
        <div class="flex items-center gap-3">
            @if(auth()->check())
            
            <!-- Theme Toggle -->
            <button type="button" id="theme-toggle" class="w-10 h-10 bg-white dark:bg-navy-900 rounded-xl flex items-center justify-center text-slate-400 hover:text-gold-500 hover:bg-gold-50 dark:hover:bg-white/5 border border-slate-200 dark:border-white/10 transition-all shadow-sm">
                <i class="fas fa-sun hidden dark:block" id="theme-icon-sun"></i>
                <i class="fas fa-moon block dark:hidden" id="theme-icon-moon"></i>
            </button>

            <!-- Notification Bell -->
            <div class="relative group">
                @php
                    $unreadCount = auth()->user()->unreadNotifications->count();
                @endphp
                <button type="button" class="w-10 h-10 bg-white dark:bg-navy-900 rounded-xl flex items-center justify-center text-slate-400 hover:text-gold-500 hover:bg-gold-50 dark:hover:bg-white/5 border border-slate-200 dark:border-white/10 transition-all relative">
                    <i class="fas fa-bell"></i>
                    @if($unreadCount > 0)
                        <span class="absolute -top-1 -right-1 w-4 h-4 bg-rose-500 text-white text-[9px] font-black rounded-full flex items-center justify-center border-2 border-white dark:border-navy-950 shadow-sm animate-pulse">
                            {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                        </span>
                    @endif
                </button>
                <!-- Dropdown -->
                <div class="absolute right-0 top-full mt-4 w-72 md:w-80 bg-white dark:bg-[#1e1b4b] rounded-2xl border border-slate-100 dark:border-white/10 shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50 transform origin-top-right scale-95 group-hover:scale-100 before:content-[''] before:absolute before:-top-4 before:right-0 before:w-10 before:h-4">
                    <div class="p-4 border-b border-slate-50 dark:border-white/5 flex justify-between items-center">
                        <h4 class="text-xs font-black text-navy-900 dark:text-white uppercase tracking-widest">Notifikasi</h4>
                        @if($unreadCount > 0)
                        <span class="text-[10px] font-bold text-rose-500">{{ $unreadCount }} Baru</span>
                        @endif
                    </div>
                    <div class="max-h-64 overflow-y-auto custom-scrollbar p-2">
                        @forelse(auth()->user()->notifications()->take(5)->get() as $notif)
                            <a href="{{ $notifRoute }}" class="block p-3 rounded-xl hover:bg-slate-50 dark:hover:bg-white/5 transition-colors {{ is_null($notif->read_at) ? 'bg-gold-50/50 dark:bg-gold-500/10' : '' }}">
                                <p class="text-xs font-bold text-navy-900 dark:text-white line-clamp-2 mb-1">{{ $notif->data['message'] ?? 'Notifikasi Baru' }}</p>
                                <p class="text-[9px] font-bold text-slate-400 uppercase"><i class="fas fa-clock mr-1"></i> {{ $notif->created_at->diffForHumans() }}</p>
                            </a>
                        @empty
                            <div class="p-4 text-center flex flex-col items-center gap-2">
                                <div class="w-10 h-10 rounded-full bg-slate-50 dark:bg-white/5 flex items-center justify-center text-slate-300">
                                    <i class="fas fa-bell-slash text-sm"></i>
                                </div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Belum ada notifikasi.</p>
                            </div>
                        @endforelse
                    </div>
                    <div class="p-2 border-t border-slate-50 dark:border-white/5">
                        <a href="{{ $notifRoute }}" class="block w-full py-2 text-center bg-navy-900 text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-gold-500 transition-colors">
                            Lihat Semua
                        </a>
                    </div>
                </div>
            </div>
            <a href="{{ $profileRoute }}" class="text-right group hidden md:block">
                <p class="text-sm font-black text-navy-900 dark:text-white leading-none uppercase group-hover:text-gold-500 transition-all max-w-[100px] sm:max-w-[150px] md:max-w-[300px] truncate">{{ auth()->user()->name }}</p>
                <p class="text-[10px] md:text-xs font-bold text-emerald-500 uppercase mt-0.5">Aktif</p>
            </a>
            <a href="{{ $profileRoute }}" class="w-10 h-10 bg-navy-900 rounded-xl flex items-center justify-center text-gold-500 border border-white/10 overflow-hidden hover:shadow-lg hover:shadow-navy-950/20 transition-all shadow-md">
                @if(auth()->user()->profile_photo)
                    <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}" class="w-full h-full object-cover">
                @else
                    <i class="fas fa-user-circle text-xl"></i>
                @endif
            </a>
            @endif
        </div>
    </div>
</header>
