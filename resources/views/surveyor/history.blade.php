<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Riwayat Data Saya | GEO-SINFRA</title>
    <link rel="icon" href="{{ asset('logo_geo-sinfra.png') }}" type="image/png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: { sans: ['Plus Jakarta Sans', 'sans-serif'] },
                    colors: {
                        navy: { 50:'#f4f4fa', 100:'#e9e9f3', 500:'#6366f1', 800:'#1e1b4b', 900:'#0f0e2c', 950:'#070617' },
                        gold: { 50:'#fdfbf7', 100:'#fbf7ed', 500:'#c5a059', 600:'#b38f4a', 700:'#9d7c3d' }
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    </style>

<style>
    @media (min-width: 768px) { html { font-size: 14px; } }
    @media (max-width: 767px) { html { font-size: 12px; } }
</style>
</head>
<body class="bg-slate-50  flex h-screen overflow-hidden text-slate-800 font-sans   transition-colors duration-300">

    @include('surveyor.partials.sidebar')

    <main class="flex-1 flex flex-col h-screen overflow-hidden">
        {{-- ── Header ── --}}
        <header class="bg-white/80  backdrop-blur-xl border-b border-slate-100  sticky top-0 px-4 pl-16 md:px-8 py-4 flex justify-between items-center z-40 shrink-0">
            <div class="flex items-center gap-4">
                <a href="{{ route('surveyor.dashboard') }}" class="hidden md:flex w-10 h-10  items-center justify-center bg-white  text-slate-400 rounded-xl hover:bg-gold-50 hover:text-gold-600 transition-all border border-slate-200  hover:border-gold-200">
                    <i class="fas fa-arrow-left text-sm"></i>
                </a>
                <div>
                    <p class="text-xs font-black text-gold-500 uppercase tracking-[0.2em] mb-1">Manajemen Laporan</p>
                    <h2 class="text-xl font-black text-navy-900  tracking-tight">Riwayat Survey Anda</h2>
                </div>
            </div>

            <div class="flex items-center gap-6">
                <div class="text-right">
                    <p class="text-xs font-black text-navy-900" id="mini-clock">00:00 WITA</p>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter hidden sm:block">{{ now()->translatedFormat('l, d F Y') }}</p>
                </div>
                <div class="h-8 w-[1px] bg-slate-100"></div>
                <div class="flex items-center gap-3">
                    <div class="text-right">
                        <p class="text-sm font-black text-navy-900  leading-none uppercase max-w-[100px] sm:max-w-[150px] md:max-w-[300px] truncate">{{ auth()->user()->name }}</p>
                        <p class="text-[10px] md:text-xs font-bold text-emerald-500 uppercase mt-0.5">Aktif</p>
                    </div>
                    <div class="w-10 h-10 bg-navy-900 rounded-xl flex items-center justify-center text-gold-500 border border-navy-800 overflow-hidden shadow-md">
                        @if(auth()->user()->profile_photo)
                            <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}" class="w-full h-full object-cover">
                        @else
                            <i class="fas fa-user-circle text-xl text-gold-500"></i>
                        @endif
                    </div>
                </div>
            </div>
        </header>

        <div class="flex-1 overflow-y-auto custom-scrollbar p-4 md:p-8 pb-16">
            <div class="max-w-7xl mx-auto">

                {{-- Alert Sukses --}}
                @if(session('success'))
                <div class="mb-6 px-6 py-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-2xl flex items-center gap-3 shadow-sm">
                    <i class="fas fa-check-circle"></i>
                    <p class="text-sm font-bold">{{ session('success') }}</p>
                </div>
                @endif

                {{-- Alert Error --}}
                @if(session('error'))
                <div class="mb-6 px-6 py-4 bg-red-50 border border-red-100 text-red-700 rounded-2xl flex items-center gap-3 shadow-sm">
                    <i class="fas fa-exclamation-circle"></i>
                    <p class="text-sm font-bold">{{ session('error') }}</p>
                </div>
                @endif
                @livewire('surveyor.history-table')
            </div>
        </div>
    </main>

    <script>
        function updateClock() {
            const now = new Date();
            const options = { timeZone: 'Asia/Makassar', hour: '2-digit', minute: '2-digit', hour12: false };
            const timeString = new Intl.DateTimeFormat('id-ID', options).format(now);
            const el = document.getElementById('mini-clock');
            if (el) el.textContent = timeString.replace('.', ':') + ' WITA';
        }
        setInterval(updateClock, 1000); updateClock();

        function konfirmasiHapus(id, nama) {
            if (confirm(`Yakin ingin menghapus data "${nama}"?\n\nData yang dihapus tidak dapat dikembalikan.`)) {
                document.getElementById('form-hapus-' + id).submit();
            }
        }
    </script>
</body>
</html>
