<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'GEO-SINFRA')</title>
    <link rel="icon" href="{{ asset('logo_geo-sinfra.png') }}" type="image/png">
    
    <script>
        // System Preference and LocalStorage theme detection
        (function() {
            try {
                var storedTheme = localStorage.getItem('geo-theme');
                if (storedTheme === 'dark' || (!storedTheme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                    document.documentElement.classList.add('dark');
                } else {
                    document.documentElement.classList.remove('dark');
                }
            } catch (e) {}
        })();

        // Listen for system theme changes if no explicit preference is set
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
            if (!localStorage.getItem('geo-theme')) {
                if (e.matches) {
                    document.documentElement.classList.add('dark');
                } else {
                    document.documentElement.classList.remove('dark');
                }
            }
        });

        // Global Theme Toggle Function
        window.toggleTheme = function() {
            const html = document.documentElement;
            if (html.classList.contains('dark')) {
                html.classList.remove('dark');
                localStorage.setItem('geo-theme', 'light');
            } else {
                html.classList.add('dark');
                localStorage.setItem('geo-theme', 'dark');
            }
        };
    </script>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    
    <!-- Leaflet & SweetAlert2 (Shared UI Utilities) -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                    },
                    colors: {
                        navy: {
                            50: '#f4f4fa',
                            100: '#e9e9f3',
                            200: '#c7c8e3',
                            500: '#6366f1',
                            800: '#1e1b4b',
                            900: '#0f0e2c',
                            950: '#070617',
                        },
                        gold: {
                            50: '#fdfbf7',
                            100: '#fbf7ed',
                            500: '#c5a059',
                            600: '#b38f4a',
                            700: '#9d7c3d',
                        }
                    }
                }
            }
        }
    </script>

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .custom-scrollbar::-webkit-scrollbar { width: 5px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        .leaflet-container { font-family: inherit; }
        
        /* Premium UI Mesh & Patterns */
        .bg-pattern {
            background-image: radial-gradient(circle at 2px 2px, rgba(255,255,255,0.06) 1px, transparent 0);
            background-size: 24px 24px;
        }
        .bg-premium-mesh {
            background: radial-gradient(circle at 80% 20%, rgba(99, 102, 241, 0.15) 0%, transparent 50%),
                        radial-gradient(circle at 20% 80%, rgba(197, 160, 89, 0.12) 0%, transparent 50%),
                        #070617;
        }
        html { transition: background-color 0.3s ease, color 0.3s ease; }

        @media (min-width: 768px) { html { font-size: 14px; } }
        @media (max-width: 767px) { html { font-size: 12px; } }
    </style>

    @stack('styles')
</head>
<body class="bg-navy-50 dark:bg-navy-950 text-slate-800 dark:text-slate-200 antialiased selection:bg-gold-500 selection:text-white flex overflow-hidden h-screen transition-colors duration-300">

    {{-- Render Sidebar Sesuai Peran atau Custom Section --}}
    @hasSection('sidebar')
        @yield('sidebar')
    @else
        @if(auth()->check())
            @if(auth()->user()->role === 'admin')
                @include('admin.partials.sidebar')
            @elseif(auth()->user()->role === 'surveyor')
                @include('surveyor.partials.sidebar')
            @elseif(auth()->user()->role === 'tim_teknis')
                @include('tim_teknis.partials.sidebar')
            @endif
        @endif
    @endif

    <main class="flex-1 overflow-y-auto custom-scrollbar text-left flex flex-col h-screen pb-28 md:pb-0 relative z-10">
        @include('partials.header')

        <div class="p-4 md:p-8 flex-1">
            @if(session('success'))
            <div class="mb-6 px-4 py-3 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800/50 text-emerald-700 dark:text-emerald-300 rounded-2xl flex items-center gap-3 shadow-sm animate-fade-in">
                <i class="fas fa-check-circle text-emerald-500"></i>
                <p class="text-xs font-bold">{{ session('success') }}</p>
            </div>
            @endif

            @if(session('error'))
            <div class="mb-6 px-4 py-3 bg-red-50 dark:bg-red-950/40 border border-red-200 dark:border-red-800/50 text-red-700 dark:text-red-300 rounded-2xl flex items-center gap-3 shadow-sm animate-fade-in">
                <i class="fas fa-exclamation-circle text-red-500"></i>
                <p class="text-xs font-bold">{{ session('error') }}</p>
            </div>
            @endif

            @yield('content')
        </div>
    </main>

    <!-- Shared Scripts -->
    <script>
        function updateClock() {
            const now = new Date();
            const options = { timeZone: 'Asia/Makassar', hour: '2-digit', minute: '2-digit', hour12: false };
            const timeString = new Intl.DateTimeFormat('id-ID', options).format(now);
            const el = document.getElementById('mini-clock');
            if (el) el.textContent = timeString.replace('.', ':') + ' WITA';
        }
        setInterval(updateClock, 1000); 
        updateClock();
    </script>
    @stack('scripts')
</body>
</html>
