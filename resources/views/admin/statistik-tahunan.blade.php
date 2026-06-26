<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistik Tahunan {{ $year }} | Admin SINFRA</title>
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
        <header class="bg-white/80 backdrop-blur-xl border-b border-slate-100 px-4 pl-16 md:px-8 py-4 flex justify-between items-center z-40 shrink-0">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.statistik') }}"
                   class="hidden md:flex w-10 h-10 bg-white border border-slate-100 rounded-xl flex items-center justify-center text-slate-400 hover:text-gold-500 hover:border-gold-500/30 hover:shadow-md transition-all group">
                    <i class="fas fa-arrow-left text-xs group-hover:-translate-x-1 transition-transform"></i>
                </a>
                <div>
                    <p class="text-xs font-black text-gold-500 uppercase tracking-wider mb-1">Administrator Portal</p>
                    <h2 class="text-xl font-black text-navy-900 dark:text-white leading-none">Statistik Tahunan</h2>
                </div>
            </div>

            <div class="flex items-center gap-4">
                {{-- Tahun Dropdown --}}
                <form action="{{ url()->current() }}" method="GET" class="relative group">
                    <select name="year" onchange="this.form.submit()" 
                            class="appearance-none bg-navy-900 text-gold-500 pl-8 pr-7 py-2 rounded-xl text-xs font-black tracking-widest uppercase outline-none cursor-pointer hover:shadow-lg hover:shadow-navy-900/20 transition-all">
                        @foreach($availableYears ?? [$year] as $y)
                            <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>{{ $y }}</option>
                        @endforeach
                    </select>
                    <i class="fas fa-calendar-alt absolute left-3 top-1/2 -translate-y-1/2 text-gold-500 text-xs pointer-events-none group-hover:scale-110 transition-transform"></i>
                    <i class="fas fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-gold-500 text-xs pointer-events-none group-hover:translate-y-0.5 transition-transform"></i>
                </form>

                {{-- Cetak --}}
                <button onclick="window.print()"
                    class="flex items-center gap-2 bg-gold-500 hover:bg-gold-600 text-navy-950 px-4 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest shadow-md shadow-gold-500/20 transition-all">
                    <i class="fas fa-print"></i> Cetak Laporan
                </button>

                <div class="h-8 w-[1px] bg-slate-100"></div>
                <div class="text-right">
                    <p class="text-sm font-black text-navy-900 dark:text-white" id="mini-clock">00:00 WITA</p>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-tighter">{{ now()->translatedFormat('d M Y') }}</p>
                </div>
                <div class="h-8 w-[1px] bg-slate-100"></div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.profile') }}" class="text-right group hidden md:block">
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
        <div class="flex-1 overflow-y-auto custom-scrollbar p-8 pb-16 space-y-6">

            {{-- ── Grafik Kurva-S ── --}}
            <div class="bg-navy-900 rounded-3xl p-8 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-80 h-80 bg-gold-500/5 rounded-full -mr-24 -mt-24 blur-3xl pointer-events-none"></div>
                <div class="absolute bottom-0 left-0 w-56 h-56 bg-navy-500/10 rounded-full -ml-16 -mb-16 blur-2xl pointer-events-none"></div>

                <div class="flex items-start justify-between mb-6 relative">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-gold-500/20 rounded-xl flex items-center justify-center">
                            <i class="fas fa-chart-line text-gold-500"></i>
                        </div>
                        <div>
                            <h4 class="text-sm font-black text-white uppercase tracking-wider">Grafik Pertumbuhan Laporan</h4>
                            <p class="text-xs text-slate-400 font-semibold mt-0.5">Kurva-S kumulatif data survey per bulan · Tahun {{ $year }}</p>
                        </div>
                    </div>
                    <span class="px-4 py-2 bg-emerald-500 text-white rounded-xl text-xs font-black uppercase tracking-widest shadow-lg shadow-emerald-500/30">
                        <i class="fas fa-shield-alt mr-1"></i> Data Terverifikasi
                    </span>
                </div>

                <div class="h-72 w-full relative">
                    <canvas id="yearlyChart"></canvas>
                </div>
            </div>

            {{-- ── Grid Bawah ── --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                {{-- Distribusi Jenis Infrastruktur --}}
                <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-8">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-8 h-8 bg-navy-900 rounded-xl flex items-center justify-center text-gold-500 shrink-0">
                            <i class="fas fa-layer-group text-xs"></i>
                        </div>
                        <div>
                            <h4 class="text-sm font-black text-navy-900 uppercase tracking-wider">Distribusi Jenis Infrastruktur</h4>
                            <p class="text-xs text-slate-400 font-semibold mt-0.5">Tahun {{ $year }}</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        @php
                            $jenisColors = [
                                'jalan'    => ['bg'=>'bg-navy-900',   'bar'=>'bg-navy-900',    'text'=>'text-white',      'icon'=>'fa-road',         'label'=>'Jalan'],

                                'titian'   => ['bg'=>'bg-gold-500',   'bar'=>'bg-gold-500',    'text'=>'text-white',      'icon'=>'fa-bridge-water', 'label'=>'Titian'],
                                'jembatan' => ['bg'=>'bg-navy-500',   'bar'=>'bg-navy-500',    'text'=>'text-white',      'icon'=>'fa-archway',      'label'=>'Jembatan'],
                            ];
                            $totalJenis = max(1, $statsJenis->sum('total'));
                        @endphp

                        @forelse($statsJenis as $s)
                        @php
                            $c   = $jenisColors[strtolower($s->jenis)] ?? ['bg'=>'bg-slate-400','bar'=>'bg-slate-400','text'=>'text-white','icon'=>'fa-cube','label'=>$s->jenis];
                            $pct = round(($s->total / $totalJenis) * 100);
                        @endphp
                        <div class="p-4 bg-slate-50 border border-slate-100 rounded-2xl">
                            <div class="flex items-center gap-3 mb-3">
                                <div class="w-9 h-9 {{ $c['bg'] }} {{ $c['text'] }} rounded-xl flex items-center justify-center shrink-0 shadow-sm">
                                    <i class="fas {{ $c['icon'] }} text-sm"></i>
                                </div>
                                <div class="flex-1 flex justify-between items-center">
                                    <p class="text-xs font-black text-navy-900 uppercase">{{ $c['label'] }}</p>
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs font-bold text-slate-400">{{ $pct }}%</span>
                                        <span class="text-sm font-black text-navy-900">{{ $s->total }} <span class="text-xs text-slate-400 font-semibold">titik</span></span>
                                    </div>
                                </div>
                            </div>
                            <div class="w-full bg-slate-200 h-1.5 rounded-full overflow-hidden">
                                <div class="{{ $c['bar'] }} h-full rounded-full transition-all duration-700" style="width: {{ $pct }}%"></div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-10">
                            <i class="fas fa-inbox text-4xl text-slate-200 mb-3 block"></i>
                            <p class="text-sm font-bold text-slate-400">Belum ada data jenis infrastruktur.</p>
                        </div>
                        @endforelse
                    </div>
                </div>

                {{-- Tabel Kondisi per Wilayah --}}
                <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-8">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-8 h-8 bg-gold-500 rounded-xl flex items-center justify-center text-white shrink-0">
                            <i class="fas fa-map-marked-alt text-xs"></i>
                        </div>
                        <div>
                            <h4 class="text-sm font-black text-navy-900 uppercase tracking-wider">Kondisi per Kecamatan</h4>
                            <p class="text-xs text-slate-400 font-semibold mt-0.5">Rekapitulasi wilayah · Tahun {{ $year }}</p>
                        </div>
                    </div>

                    <div class="overflow-x-auto custom-scrollbar">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-100">
                                    <th class="px-3 py-3 text-xs font-black text-slate-400 tracking-widest uppercase">Kecamatan</th>
                                    <th class="px-3 py-3 text-xs font-black text-emerald-500 tracking-widest text-center">Baik</th>
                                    <th class="px-3 py-3 text-xs font-black text-orange-500 tracking-widest text-center">Sedang</th>
                                    <th class="px-3 py-3 text-xs font-black text-red-500 tracking-widest text-center">Berat</th>
                                    <th class="px-3 py-3 text-xs font-black text-slate-400 tracking-widest text-right">Total</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @foreach($kondisiKecamatan as $item)
                                <tr class="hover:bg-slate-50/60 transition-colors">
                                    <td class="px-3 py-3">
                                        <div class="flex items-center gap-2">
                                            <i class="fas fa-map-marker-alt text-gold-500 text-xs"></i>
                                            <p class="text-xs font-black text-navy-900 uppercase">{{ $item['nama'] }}</p>
                                        </div>
                                    </td>
                                    <td class="px-3 py-3 text-center">
                                        <span class="text-xs font-black text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-lg">{{ $item['baik'] }}</span>
                                    </td>
                                    <td class="px-3 py-3 text-center">
                                        <span class="text-xs font-black text-orange-600 bg-orange-50 px-2 py-0.5 rounded-lg">{{ $item['sedang'] }}</span>
                                    </td>
                                    <td class="px-3 py-3 text-center">
                                        <span class="text-xs font-black text-red-600 bg-red-50 px-2 py-0.5 rounded-lg">{{ $item['berat'] }}</span>
                                    </td>
                                    <td class="px-3 py-3 text-right">
                                        <span class="text-xs font-black text-navy-900 bg-navy-50 border border-navy-100 px-2 py-0.5 rounded-lg">{{ $item['total'] }}</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
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

        // Kurva-S Chart
        const ctx = document.getElementById('yearlyChart').getContext('2d');
        const monthLimit = 12;
        const allLabels = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
        const chartLabels = allLabels.slice(0, monthLimit);
        const rawData = @json($chartData).slice(0, monthLimit);

        let cumulative = [], total = 0;
        rawData.forEach(v => { total += v; cumulative.push(total); });

        const gradient = ctx.createLinearGradient(0, 0, 0, 280);
        gradient.addColorStop(0, 'rgba(197,160,89,0.3)');
        gradient.addColorStop(1, 'rgba(197,160,89,0.0)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: chartLabels,
                datasets: [{
                    label: 'Kumulatif Survey Masuk',
                    data: cumulative,
                    borderColor: '#c5a059',
                    borderWidth: 3,
                    pointBackgroundColor: '#0f0e2c',
                    pointBorderColor: '#c5a059',
                    pointBorderWidth: 3,
                    pointRadius: 6,
                    pointHoverRadius: 9,
                    tension: 0.4,
                    fill: true,
                    backgroundColor: gradient
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#070617',
                        titleColor: '#c5a059',
                        bodyColor: '#fff',
                        padding: 12,
                        cornerRadius: 12,
                        callbacks: {
                            label: (ctx) => ` ${ctx.raw} survey kumulatif`
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(255,255,255,0.05)', borderDash: [4,4] },
                        ticks: { font: { size: 10, weight: 'bold' }, color: '#64748b', stepSize: 1 }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { font: { size: 10, weight: 'bold' }, color: '#94a3b8' }
                    }
                }
            }
        });
    </script>
</body>
</html>

