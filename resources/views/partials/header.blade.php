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
            
            <!-- Theme Toggle Button -->
            <button type="button" onclick="toggleThemeMode()" class="w-10 h-10 bg-white dark:bg-navy-900 rounded-xl flex items-center justify-center text-slate-400 hover:text-gold-500 hover:bg-gold-50 dark:hover:bg-white/5 border border-slate-200 dark:border-white/10 transition-all shadow-sm relative z-[6000] cursor-pointer" title="Ganti Tema">
                <i class="fas fa-sun block dark:hidden pointer-events-none"></i>
                <i class="fas fa-moon hidden dark:block pointer-events-none"></i>
            </button>
            
            <script>
                // Toggle Theme Function
                if (typeof window.toggleThemeMode !== 'function') {
                    window.toggleThemeMode = function() {
                        if (document.documentElement.classList.contains('dark')) {
                            document.documentElement.classList.remove('dark');
                            localStorage.setItem('geo-theme', 'light');
                        } else {
                            document.documentElement.classList.add('dark');
                            localStorage.setItem('geo-theme', 'dark');
                        }
                    };
                }
            </script>

            <!-- Notification Bell -->
            <div class="relative">
                @php
                    $unreadCount = auth()->user()->unreadNotifications->count();
                @endphp
                <a href="{{ $notifRoute }}" class="w-10 h-10 bg-white dark:bg-navy-900 rounded-xl flex items-center justify-center text-slate-400 hover:text-gold-500 hover:bg-gold-50 dark:hover:bg-white/5 border border-slate-200 dark:border-white/10 transition-all relative">
                    <i class="fas fa-bell"></i>
                    @if($unreadCount > 0)
                        <span class="absolute -top-1 -right-1 w-4 h-4 bg-rose-500 text-white text-[9px] font-black rounded-full flex items-center justify-center border-2 border-white dark:border-navy-950 shadow-sm animate-pulse">
                            {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                        </span>
                    @endif
                </a>
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
