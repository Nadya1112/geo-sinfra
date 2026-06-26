<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ringkasan Statistik | Admin SINFRA</title>
    <link rel="icon" href="{{ asset('logo_geo-sinfra.png') }}" type="image/png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        .stat-card { transition: transform 0.2s, box-shadow 0.2s; }
        .stat-card:hover { transform: translateY(-3px); box-shadow: 0 12px 32px rgba(15,14,44,0.10); }
    </style>
<style>
    @media (min-width: 768px) { html { font-size: 14px; } }
    @media (max-width: 767px) { html { font-size: 12px; } }
</style>
</head>
<body class="bg-navy-50 dark:bg-navy-950 text-slate-800 dark:text-slate-200 antialiased flex overflow-hidden h-screen transition-colors duration-300">

    @include('admin.partials.sidebar')

    <main class="flex-1 flex flex-col h-screen overflow-hidden">

        {{-- ── Header ── --}}
        <header class="bg-white/80 backdrop-blur-xl border-b border-slate-100 px-4 pl-16 md:px-8 py-4 md:py-5 flex flex-col md:flex-row gap-4 md:gap-0 md:justify-between items-start md:items-center z-40 shrink-0">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.dashboard') }}"
                   class="w-10 h-10 bg-white border border-slate-100 rounded-xl flex items-center justify-center text-slate-400 hover:text-gold-500 hover:border-gold-500/30 hover:shadow-md transition-all group">
                    <i class="fas fa-arrow-left text-xs group-hover:-translate-x-1 transition-transform"></i>
                </a>
                <div>
                    <p class="text-xs font-black text-gold-500 uppercase tracking-wider mb-1">Administrator Portal</p>
                    <h2 class="text-xl font-black text-navy-900 dark:text-white leading-none">Ringkasan Statistik</h2>
                </div>
                <div class="hidden md:block w-[1px] h-8 bg-slate-200 ml-4 mr-2"></div>
                <a href="{{ route('admin.infrastruktur.export') }}" class="ml-2 px-4 py-2 bg-emerald-50 text-emerald-600 hover:bg-emerald-500 hover:text-white border border-emerald-100 hover:border-emerald-500 rounded-xl text-xs font-black tracking-widest uppercase transition-all shadow-sm hover:shadow-lg hover:shadow-emerald-500/20 flex items-center gap-2">
                    <i class="fas fa-file-excel"></i> Export Excel
                </a>
            </div>

            <div class="flex items-center gap-6">
                <div class="text-right hidden sm:block">
                    <p class="text-sm font-black text-navy-900 dark:text-white" id="mini-clock">00:00 WITA</p>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-tighter">{{ now()->translatedFormat('l, d F Y') }}</p>
                </div>
                <div class="h-8 w-[1px] bg-slate-100"></div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.profile') }}" class="text-right group">
                        <p class="text-sm font-black text-navy-900 dark:text-white leading-none uppercase group-hover:text-gold-500 transition-all">{{ auth()->user()->name }}</p>
                        <p class="text-xs font-bold text-emerald-500 uppercase mt-1">Online</p>
                    </a>
                    <a href="{{ route('admin.profile') }}" class="w-10 h-10 bg-navy-900 rounded-xl flex items-center justify-center text-gold-500 overflow-hidden hover:shadow-lg transition-all shadow-md">
                        @if(auth()->user()->profile_photo)
                            <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}" class="w-full h-full object-cover">
                        @else
                            <i class="fas fa-user-circle text-xl"></i>
                        @endif
                    </a>
                </div>
            </div>
        </header>

        {{-- ── Content ── --}}
        <div class="flex-1 overflow-y-auto custom-scrollbar p-8 pb-16">

            {{-- ── 4 Stat Cards ── --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-5 mb-8">

                {{-- Total Infrastruktur --}}
                <div class="stat-card bg-white rounded-3xl border border-slate-100 shadow-sm p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div class="w-11 h-11 bg-navy-900 rounded-2xl flex items-center justify-center shadow-md shadow-navy-900/20">
                            <i class="fas fa-road text-gold-500"></i>
                        </div>
                        <span class="text-xs font-black text-emerald-500 bg-emerald-50 border border-emerald-100 px-2 py-1 rounded-lg uppercase tracking-wider">Aktif</span>
                    </div>
                    <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1">Total Infrastruktur</p>
                    <h3 class="text-3xl font-black text-navy-900">{{ $jumlahInfrastruktur }}</h3>
                    <p class="text-xs text-slate-400 font-semibold mt-1">Objek terdaftar</p>
                </div>

                {{-- Analisis AI --}}
                <div class="stat-card bg-white rounded-3xl border border-slate-100 shadow-sm p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div class="w-11 h-11 bg-gold-500 rounded-2xl flex items-center justify-center shadow-md shadow-gold-500/20">
                            <i class="fas fa-brain text-white"></i>
                        </div>
                        <span class="text-xs font-black text-gold-600 bg-gold-50 border border-gold-100 px-2 py-1 rounded-lg uppercase tracking-wider">AI</span>
                    </div>
                    <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1">Teranalisis AI</p>
                    <h3 class="text-3xl font-black text-navy-900">{{ $jumlahAnalisis }}</h3>
                    <p class="text-xs text-slate-400 font-semibold mt-1">Data diproses</p>
                </div>

                {{-- Surveyor & Tim Teknis --}}
                <div class="stat-card bg-white rounded-3xl border border-slate-100 shadow-sm p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div class="w-11 h-11 bg-navy-500 rounded-2xl flex items-center justify-center shadow-md shadow-navy-500/20">
                            <i class="fas fa-users text-white"></i>
                        </div>
                        <span class="text-xs font-black text-navy-800 bg-navy-50 border border-navy-100 px-2 py-1 rounded-lg uppercase tracking-wider">User</span>
                    </div>
                    <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1">Surveyor & Tim Teknis</p>
                    <h3 class="text-3xl font-black text-navy-900">{{ $jumlahSurveyor + $jumlahTimTeknis }}</h3>
                    <p class="text-xs text-slate-400 font-semibold mt-1">{{ $jumlahSurveyor }} surveyor · {{ $jumlahTimTeknis }} tim_teknis</p>
                </div>

                {{-- Wilayah --}}
                <div class="stat-card bg-white rounded-3xl border border-slate-100 shadow-sm p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div class="w-11 h-11 bg-emerald-500 rounded-2xl flex items-center justify-center shadow-md shadow-emerald-500/20">
                            <i class="fas fa-map text-white"></i>
                        </div>
                        <span class="text-xs font-black text-emerald-600 bg-emerald-50 border border-emerald-100 px-2 py-1 rounded-lg uppercase tracking-wider">SIG</span>
                    </div>
                    <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1">Wilayah</p>
                    <h3 class="text-3xl font-black text-navy-900">{{ $jumlahWilayah }}</h3>
                    <p class="text-xs text-slate-400 font-semibold mt-1">Kecamatan terpetakan</p>
                </div>
            </div>

            {{-- ── Grid Utama ── --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                {{-- Prioritas Perbaikan --}}
                <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-8 flex flex-col justify-between">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-8 h-8 bg-navy-900 rounded-xl flex items-center justify-center text-gold-500 shrink-0">
                                <i class="fas fa-chart-bar text-xs"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-black text-navy-900 uppercase tracking-wider">Prediksi Prioritas Perbaikan</h4>
                                <p class="text-xs text-slate-400 font-semibold mt-0.5">Berdasarkan klasifikasi Hybrid AI (CNN + Decision Tree)</p>
                            </div>
                        </div>

                        @php
                            $total = max(1, $jumlahRusakBerat + $jumlahRusakSedang + $jumlahBaik + $jumlahBelumDianalisis);
                            $priorities = [
                                ['label'=>'Rusak Berat',  'count'=>$jumlahRusakBerat,  'icon'=>'fa-exclamation-triangle', 'bg'=>'bg-red-500',    'light'=>'bg-red-50 border-red-100',    'text'=>'text-red-600',    'bar'=>'bg-red-500',    'desc'=>'Butuh penanganan segera'],
                                ['label'=>'Rusak Sedang', 'count'=>$jumlahRusakSedang, 'icon'=>'fa-hammer',              'bg'=>'bg-orange-500', 'light'=>'bg-orange-50 border-orange-100','text'=>'text-orange-600', 'bar'=>'bg-orange-500', 'desc'=>'Perbaikan dalam waktu dekat'],
                                ['label'=>'Baik',         'count'=>$jumlahBaik,         'icon'=>'fa-check-circle',        'bg'=>'bg-emerald-500','light'=>'bg-emerald-50 border-emerald-100','text'=>'text-emerald-600','bar'=>'bg-emerald-500','desc'=>'Kondisi layak dan stabil'],
                            ];
                        @endphp

                        <div class="space-y-3">
                            @foreach($priorities as $p)
                            @php $pct = round(($p['count'] / $total) * 100); @endphp
                            <div class="p-4 border rounded-2xl {{ $p['light'] }}">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 {{ $p['bg'] }} text-white rounded-xl flex items-center justify-center shrink-0 shadow-sm">
                                        <i class="fas {{ $p['icon'] }} text-sm"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex justify-between items-center mb-1.5">
                                            <p class="text-xs font-black text-navy-900">{{ $p['label'] }}</p>
                                            <div class="flex items-center gap-2">
                                                <span class="text-xs font-bold {{ $p['text'] }}">{{ $pct }}%</span>
                                                <span class="text-xs font-black text-navy-900">{{ $p['count'] }} <span class="text-xs text-slate-400 font-semibold">titik</span></span>
                                            </div>
                                        </div>
                                        <div class="w-full bg-white/70 h-1.5 rounded-full overflow-hidden">
                                            <div class="{{ $p['bar'] }} h-full rounded-full transition-all duration-700" style="width: {{ $pct }}%"></div>
                                        </div>
                                        <p class="text-xs {{ $p['text'] }} font-semibold mt-1">{{ $p['desc'] }}</p>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Donut Chart Kondisi --}}
                    <div class="bg-navy-900 rounded-3xl p-8 relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-56 h-56 bg-gold-500/5 rounded-full -mr-16 -mt-16 blur-3xl pointer-events-none"></div>
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-8 h-8 bg-gold-500/20 rounded-xl flex items-center justify-center">
                                <i class="fas fa-chart-pie text-gold-500 text-xs"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-black text-white uppercase tracking-wider">Distribusi Kondisi</h4>
                                <p class="text-xs text-slate-400 font-semibold mt-0.5">Proporsi kondisi seluruh infrastruktur</p>
                            </div>
                        </div>

                        <div class="flex flex-col md:flex-row items-center gap-8">
                            <div class="relative w-48 h-48 shrink-0">
                                <canvas id="donutChart"></canvas>
                                <div class="absolute inset-0 flex flex-col items-center justify-center">
                                    <p class="text-2xl font-black text-white">{{ $jumlahInfrastruktur }}</p>
                                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total</p>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-3 w-full">
                                @foreach([
                                    ['label'=>'Baik',         'count'=>$jumlahBaik,         'color'=>'bg-emerald-500', 'text'=>'text-emerald-400'],
                                    ['label'=>'Rusak Sedang', 'count'=>$jumlahRusakSedang,   'color'=>'bg-orange-500',  'text'=>'text-orange-400'],
                                    ['label'=>'Rusak Berat',  'count'=>$jumlahRusakBerat,    'color'=>'bg-red-500',     'text'=>'text-red-400'],
                                ] as $item)
                                <div class="bg-white/5 border border-white/10 rounded-2xl p-3">
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="w-2.5 h-2.5 rounded-full {{ $item['color'] }} shrink-0"></span>
                                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">{{ $item['label'] }}</span>
                                    </div>
                                    <p class="text-xl font-black {{ $item['text'] }}">{{ $item['count'] }}</p>
                                    <p class="text-xs text-slate-500 font-semibold">{{ $total > 0 ? round(($item['count']/$total)*100) : 0 }}%</p>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>



            </div>
        </div>
    </main>

    <script>
        // Clock
        function updateClock() {
            const now = new Date();
            const options = { timeZone: 'Asia/Makassar', hour: '2-digit', minute: '2-digit', hour12: false };
            const timeString = new Intl.DateTimeFormat('id-ID', options).format(now);
            const el = document.getElementById('mini-clock');
            if (el) el.textContent = timeString.replace('.', ':') + ' WITA';
        }
        setInterval(updateClock, 1000); updateClock();

        // Donut Chart
        const ctx = document.getElementById('donutChart').getContext('2d');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Baik', 'Rusak Sedang', 'Rusak Berat'],
                datasets: [{
                    data: [{{ $jumlahBaik }}, {{ $jumlahRusakSedang }}, {{ $jumlahRusakBerat }}],
                    backgroundColor: ['#10b981', '#f97316', '#ef4444'],
                    borderColor: '#0f0e2c',
                    borderWidth: 3,
                    hoverOffset: 8,
                }]
            },
            options: {
                cutout: '72%',
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: (ctx) => ` ${ctx.label}: ${ctx.raw} titik`
                        },
                        backgroundColor: '#0f0e2c',
                        titleColor: '#c5a059',
                        bodyColor: '#fff',
                        padding: 10,
                        cornerRadius: 10,
                    }
                },
                animation: { animateScale: true, duration: 1000 }
            }
        });
    </script>
</body>
</html>

