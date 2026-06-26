<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan | Admin SINFRA</title>
    <link rel="icon" href="{{ asset('logo_geo-sinfra.png') }}" type="image/png">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
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
    
    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark')
        } else {
            document.documentElement.classList.remove('dark')
        }
    </script>

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        html { transition: background-color 0.3s ease, color 0.3s ease; }
        @media (min-width: 768px) { html { font-size: 14px; } }
        @media (max-width: 767px) { html { font-size: 12px; } }
    </style>
</head>
<body class="bg-navy-50 dark:bg-navy-950 text-slate-800 dark:text-slate-200 antialiased selection:bg-gold-500 selection:text-white flex overflow-hidden h-screen transition-colors duration-300">

    @include('admin.partials.sidebar')

    <main class="flex-1 overflow-y-auto custom-scrollbar flex flex-col h-screen relative">
        <header class="sticky top-0 bg-white/80 dark:bg-navy-950/80 backdrop-blur-xl border-b border-slate-100 dark:border-white/5 px-4 md:px-8 py-4 flex justify-between items-center z-40">
            <div class="flex items-center gap-2 md:gap-4">
                <a href="{{ route('admin.dashboard') }}" class="w-8 h-8 md:w-10 md:h-10 flex items-center justify-center bg-white border border-slate-200 text-slate-400 rounded-xl hover:bg-gold-50 hover:text-gold-600 hover:border-gold-200 transition-all shadow-sm">
                    <i class="fas fa-arrow-left text-sm"></i>
                </a>
                <div class="text-left">
                    <p class="text-xs font-black text-gold-500 uppercase tracking-wider mb-1">Administrator Portal</p>
                    <h2 class="text-lg md:text-xl font-black text-navy-900 dark:text-white leading-none">Pengaturan Sistem</h2>
                </div>
            </div>
            
            <div class="flex items-center gap-3 md:gap-6">
                <div class="text-right">
                    <p class="text-sm font-black text-navy-900 dark:text-white" id="mini-clock">00:00 WITA</p>
                    <p class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-tighter">{{ now()->translatedFormat('d M Y') }}</p>
                </div>
            </div>
        </header>

        <div class="p-4 md:p-8 flex-1">
            <div class="max-w-3xl mx-auto">
                @if(session('success'))
                <div class="mb-6 px-4 py-3 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-2xl flex items-center gap-3 shadow-sm">
                    <i class="fas fa-check-circle text-lg"></i>
                    <p class="text-sm font-bold">{{ session('success') }}</p>
                </div>
                @endif

                <form action="{{ route('admin.settings.update') }}" method="POST" class="bg-white dark:bg-navy-900 border border-slate-100 dark:border-white/10 rounded-3xl p-6 shadow-sm text-left">
                    @csrf
                    
                    <h4 class="text-lg font-black text-navy-900 dark:text-white mb-6 border-b border-slate-100 dark:border-white/10 pb-4">Kontak & Informasi Publik</h4>
                    
                    <div class="space-y-5 mb-8">
                        <div>
                            <label class="block text-xs font-black text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-2 ml-1">Email Kontak Utama</label>
                            <input type="email" name="contact_email" value="{{ $settings['contact_email'] ?? 'admin@geo-sinfra.co.id' }}" class="w-full px-5 py-3.5 bg-slate-50 dark:bg-navy-950 border border-slate-200 dark:border-white/10 rounded-2xl text-sm font-semibold text-navy-900 dark:text-white focus:ring-4 focus:ring-gold-500/10 focus:border-gold-500 outline-none transition-all">
                        </div>
                        <div>
                            <label class="block text-xs font-black text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-2 ml-1">Nomor WhatsApp Pelayanan</label>
                            <input type="text" name="contact_wa" value="{{ $settings['contact_wa'] ?? '+62 800 0000 0000' }}" class="w-full px-5 py-3.5 bg-slate-50 dark:bg-navy-950 border border-slate-200 dark:border-white/10 rounded-2xl text-sm font-semibold text-navy-900 dark:text-white focus:ring-4 focus:ring-gold-500/10 focus:border-gold-500 outline-none transition-all">
                        </div>
                        <div>
                            <label class="block text-xs font-black text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-2 ml-1">Jam Operasional</label>
                            @php $currentHours = $settings['operational_hours'] ?? 'Senin - Jumat, 08:00 - 16:00'; @endphp
                            <select name="operational_hours" class="w-full px-5 py-3.5 bg-slate-50 dark:bg-navy-950 border border-slate-200 dark:border-white/10 rounded-2xl text-sm font-semibold text-navy-900 dark:text-white focus:ring-4 focus:ring-gold-500/10 focus:border-gold-500 outline-none transition-all">
                                <option value="Senin - Kamis, 08.00 - 16.30" {{ $currentHours == 'Senin - Kamis, 08.00 - 16.30' ? 'selected' : '' }}>Senin - Kamis, 08.00 - 16.30</option>
                                <option value="Jumat, 08.00 - 11.00" {{ $currentHours == 'Jumat, 08.00 - 11.00' ? 'selected' : '' }}>Jumat, 08.00 - 11.00</option>
                            </select>
                        </div>
                    </div>

                    <h4 class="text-lg font-black text-navy-900 dark:text-white mb-6 border-b border-slate-100 dark:border-white/10 pb-4">Pengaturan Peta Dasar</h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-8">
                        <div>
                            <label class="block text-xs font-black text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-2 ml-1">Latitude Tengah (Titik Awal)</label>
                            <input type="text" name="map_center_lat" value="{{ $settings['map_center_lat'] ?? '-3.3276' }}" class="w-full px-5 py-3.5 bg-slate-50 dark:bg-navy-950 border border-slate-200 dark:border-white/10 rounded-2xl text-sm font-semibold text-navy-900 dark:text-white focus:ring-4 focus:ring-gold-500/10 focus:border-gold-500 outline-none transition-all">
                        </div>
                        <div>
                            <label class="block text-xs font-black text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-2 ml-1">Longitude Tengah (Titik Awal)</label>
                            <input type="text" name="map_center_lng" value="{{ $settings['map_center_lng'] ?? '114.5901' }}" class="w-full px-5 py-3.5 bg-slate-50 dark:bg-navy-950 border border-slate-200 dark:border-white/10 rounded-2xl text-sm font-semibold text-navy-900 dark:text-white focus:ring-4 focus:ring-gold-500/10 focus:border-gold-500 outline-none transition-all">
                        </div>
                    </div>

                    <div class="pt-6 border-t border-slate-100 dark:border-white/10 flex justify-end">
                        <button type="submit" class="px-8 py-3.5 bg-gold-500 hover:bg-gold-600 text-navy-950 font-black rounded-xl shadow-xl shadow-gold-500/20 hover:shadow-gold-500/40 transition-all flex items-center gap-2">
                            <i class="fas fa-save"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
    
    <script>
        function updateClock() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }) + ' WITA';
            document.getElementById('mini-clock').textContent = timeString;
        }
        setInterval(updateClock, 1000);
        updateClock();
    </script>
</body>
</html>
