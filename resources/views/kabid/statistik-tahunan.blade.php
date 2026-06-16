<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistik Laporan | Kabid SINFRA</title>
    <link rel="icon" href="{{ asset('logo_geo-sinfra.png') }}" type="image/png">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        navy: { 50: '#f0f4f8', 100: '#d9e2ec', 200: '#bcccdc', 300: '#9fb3c8', 400: '#829ab1', 500: '#627d98', 600: '#486581', 700: '#334e68', 800: '#243b53', 900: '#0f0e2c', 950: '#0a091d' },
                        gold: { 50: '#fbf8f1', 100: '#f5ebd9', 200: '#eed9b9', 300: '#e5c292', 400: '#dba665', 500: '#c5a059', 600: '#b48135', 700: '#96652a', 800: '#7c5327', 900: '#644422', 950: '#382310' }
                    },
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        .glass-card { background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px); }
    </style>
</head>
<body class="bg-slate-50 flex h-screen overflow-hidden text-slate-800 text-left">

    @include('kabid.partials.sidebar')

    <main class="flex-1 overflow-y-auto custom-scrollbar">
        <header class="sticky top-0 bg-white/80 backdrop-blur-md border-b border-slate-100 px-8 py-5 flex justify-between items-center z-40">
            <div class="flex items-center gap-4 text-left">
                <a href="{{ route('kabid.dashboard') }}" 
                   class="w-10 h-10 bg-slate-50 border border-slate-100 rounded-xl flex items-center justify-center text-slate-400 hover:text-gold-500 hover:border-gold-100 hover:shadow-lg hover:shadow-gold-500/10 transition-all group"
                   title="Kembali">
                    <i class="fas fa-arrow-left text-xs group-hover:-translate-x-1 transition-transform"></i>
                </a>

                <div class="text-left">
                    <p class="text-[10px] font-extrabold text-gold-500 uppercase tracking-[0.2em] mb-1">Report Analysis</p>
                    <h2 class="text-xl font-black text-navy-900">Statistik Laporan</h2>
                </div>
            </div>

            <div class="flex items-center gap-6">
                <button onclick="window.print()" class="no-print px-4 py-2 bg-navy-50 text-navy-600 rounded-xl text-[9px] font-black uppercase tracking-widest hover:bg-navy-100 transition-all flex items-center gap-2 border border-navy-100">
                    <i class="fas fa-print"></i> Ekspor
                </button>
                <div class="text-right hidden sm:block">
                    <p class="text-[11px] font-black text-navy-900" id="mini-clock">00:00 WITA</p>
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-tighter">{{ now()->translatedFormat('l, d F Y') }}</p>
                </div>
                <div class="h-8 w-[1px] bg-slate-100"></div>
                <a href="{{ route('kabid.profile') }}" class="flex items-center gap-3 group">
                    <div class="text-right">
                        <p class="text-[11px] font-black text-navy-900 leading-none uppercase group-hover:text-gold-500 transition-colors">{{ auth()->user()->name }}</p>
                        <p class="text-[9px] font-bold text-emerald-500 uppercase mt-1">ONLINE</p>
                    </div>
                    <div class="w-10 h-10 bg-navy-900 rounded-xl flex items-center justify-center text-gold-500 shadow-md group-hover:shadow-lg transition-all overflow-hidden">
                        @if(auth()->user()->profile_photo)
                            <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}" class="w-full h-full object-cover">
                        @else
                            <i class="fas fa-user-circle text-xl"></i>
                        @endif
                    </div>
                </a>
            </div>
        </header>

        <div class="p-8">
            <!-- KPI Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Total Usulan -->
                <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm relative overflow-hidden group hover:shadow-md hover:-translate-y-1 transition-all">
                    <div class="absolute -right-6 -top-6 w-24 h-24 bg-navy-50 rounded-full group-hover:scale-150 transition-transform duration-500"></div>
                    <div class="relative z-10">
                        <div class="flex justify-between items-start mb-4">
                            <div class="w-12 h-12 bg-navy-50 rounded-2xl flex items-center justify-center text-navy-500">
                                <i class="fas fa-file-alt text-lg"></i>
                            </div>
                        </div>
                        <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Laporan</h4>
                        <div class="flex items-baseline gap-2">
                            <h2 class="text-3xl font-black text-navy-900">{{ $kpi['total'] }}</h2>
                            <span class="text-[10px] font-bold text-slate-400 uppercase">Unit</span>
                        </div>
                    </div>
                </div>

                <!-- Tervalidasi -->
                <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm relative overflow-hidden group hover:shadow-md hover:-translate-y-1 transition-all">
                    <div class="absolute -right-6 -top-6 w-24 h-24 bg-[#059669]/5 rounded-full group-hover:scale-150 transition-transform duration-500"></div>
                    <div class="relative z-10">
                        <div class="flex justify-between items-start mb-4">
                            <div class="w-12 h-12 bg-[#059669]/10 rounded-2xl flex items-center justify-center text-[#059669]">
                                <i class="fas fa-check-double text-lg"></i>
                            </div>
                        </div>
                        <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Disetujui (ACC)</h4>
                        <div class="flex items-baseline gap-2">
                            <h2 class="text-3xl font-black text-navy-900">{{ $kpi['validated'] }}</h2>
                            <span class="text-[10px] font-bold text-slate-400 uppercase">Unit</span>
                        </div>
                    </div>
                </div>

                <!-- Ditolak -->
                <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm relative overflow-hidden group hover:shadow-md hover:-translate-y-1 transition-all">
                    <div class="absolute -right-6 -top-6 w-24 h-24 bg-rose-50 rounded-full group-hover:scale-150 transition-transform duration-500"></div>
                    <div class="relative z-10">
                        <div class="flex justify-between items-start mb-4">
                            <div class="w-12 h-12 bg-rose-50 rounded-2xl flex items-center justify-center text-rose-500">
                                <i class="fas fa-times-circle text-lg"></i>
                            </div>
                        </div>
                        <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Ditolak</h4>
                        <div class="flex items-baseline gap-2">
                            <h2 class="text-3xl font-black text-navy-900">{{ $kpi['rejected'] }}</h2>
                            <span class="text-[10px] font-bold text-slate-400 uppercase">Unit</span>
                        </div>
                    </div>
                </div>

                <!-- Rusak Berat -->
                <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm relative overflow-hidden group hover:shadow-md hover:-translate-y-1 transition-all">
                    <div class="absolute -right-6 -top-6 w-24 h-24 bg-[#be123c]/5 rounded-full group-hover:scale-150 transition-transform duration-500"></div>
                    <div class="relative z-10">
                        <div class="flex justify-between items-start mb-4">
                            <div class="w-12 h-12 bg-[#be123c]/10 rounded-2xl flex items-center justify-center text-[#be123c]">
                                <i class="fas fa-exclamation-triangle text-lg"></i>
                            </div>
                        </div>
                        <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Prioritas (Rusak Berat)</h4>
                        <div class="flex items-baseline gap-2">
                            <h2 class="text-3xl font-black text-[#be123c]">{{ $kpi['berat'] }}</h2>
                            <span class="text-[10px] font-bold text-slate-400 uppercase">Unit</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Growth Overview -->
            <div class="bg-white rounded-[2.5rem] p-8 border border-slate-100 shadow-sm mb-8 overflow-hidden relative">
                <div class="absolute top-0 right-0 w-64 h-64 bg-gold-500/5 rounded-full -mr-20 -mt-20 blur-3xl"></div>
                <div class="relative z-10">
                    <div class="flex justify-between items-start mb-8">
                        <div>
                            <h4 class="font-extrabold text-lg text-navy-900">Grafik Tren Laporan</h4>
                            <p class="text-xs text-slate-400 font-medium">Monitoring jumlah survey yang masuk per bulan</p>
                        </div>
                        <form action="{{ route('kabid.statistik.tahunan') }}" method="GET" id="yearForm">
                            <div class="px-4 py-2 bg-navy-50 rounded-xl flex items-center gap-3 border border-navy-100">
                                <span class="text-[9px] font-black text-navy-400 uppercase tracking-widest">Periode</span>
                                <select name="year" onchange="document.getElementById('yearForm').submit()" class="bg-transparent text-[10px] font-black text-navy-900 focus:outline-none cursor-pointer">
                                    @foreach($availableYears as $y)
                                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                                    @endforeach
                                </select>
                                <i class="fas fa-chevron-down text-[8px] text-navy-400"></i>
                            </div>
                        </form>
                    </div>
                    
                    <div class="h-[300px] w-full">
                        <canvas id="yearlyChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                <!-- Distribution by Type -->
                <div class="bg-white rounded-[2.5rem] p-8 border border-slate-100 shadow-sm flex flex-col">
                    <h4 class="font-extrabold text-lg text-navy-900 mb-8">Sebaran Berdasarkan Kategori</h4>
                    <div class="space-y-6 flex-1 justify-center flex flex-col">
                        @foreach($statsJenis as $s)
                        @php
                            $colors = [
                                'jalan' => ['bg' => 'bg-navy-50', 'bar' => 'bg-navy-500', 'text' => 'text-navy-600', 'icon' => 'fa-road'],

                                'titian' => ['bg' => 'bg-gold-50', 'bar' => 'bg-gold-500', 'text' => 'text-gold-600', 'icon' => 'fa-bridge-water'],
                            ];
                            $c = $colors[strtolower($s->jenis)] ?? ['bg' => 'bg-slate-50', 'bar' => 'bg-slate-500', 'text' => 'text-slate-600', 'icon' => 'fa-cube'];
                        @endphp
                        <div class="group">
                            <div class="flex justify-between items-center mb-2">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 {{ $c['bg'] }} {{ $c['text'] }} rounded-lg flex items-center justify-center text-[10px]">
                                        <i class="fas {{ $c['icon'] }}"></i>
                                    </div>
                                    <span class="text-xs font-bold text-navy-900 uppercase">{{ $s->jenis }}</span>
                                </div>
                                <span class="text-xs font-black text-navy-900">{{ $s->total }} <span class="text-[10px] text-slate-400 font-medium">Unit</span></span>
                            </div>
                            <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden">
                                <div class="h-full {{ $c['bar'] }} rounded-full" style="width: {{ ($s->total / max(1, $statsJenis->sum('total'))) * 100 }}%"></div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Status Validasi Donut -->
                <div class="bg-white rounded-[2.5rem] p-8 border border-slate-100 shadow-sm flex flex-col">
                    <h4 class="font-extrabold text-lg text-navy-900 mb-2">Status Validasi Laporan</h4>
                    <p class="text-xs text-slate-400 font-medium mb-6">Persentase laporan yang telah Anda proses</p>
                    <div class="flex-1 relative flex items-center justify-center min-h-[200px]">
                        <canvas id="validationDonutChart"></canvas>
                        <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                            <span class="text-3xl font-black text-navy-900">{{ array_sum($donutData) }}</span>
                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-1">Total Laporan</span>
                        </div>
                    </div>
                    <div class="flex justify-center gap-6 mt-6">
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 rounded-full bg-[#059669]"></div>
                            <span class="text-[10px] font-black uppercase text-slate-500">ACC</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 rounded-full bg-rose-500"></div>
                            <span class="text-[10px] font-black uppercase text-slate-500">Ditolak</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 rounded-full bg-slate-200"></div>
                            <span class="text-[10px] font-black uppercase text-slate-500">Menunggu</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Condition Summary Table -->
            <div class="bg-white rounded-[2.5rem] p-8 border border-slate-100 shadow-sm mb-8">
                <h4 class="font-extrabold text-lg text-navy-900 mb-8 text-left">Peta Kondisi per Kecamatan</h4>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-gradient-to-r from-navy-900 to-navy-800 border-b border-navy-800 shadow-md text-[10px] font-black text-gold-500 uppercase tracking-widest">
                                <th class="py-4 px-4 w-[25%]">Wilayah Kecamatan</th>
                                <th class="py-4 px-4 text-center w-[20%]">Kondisi Baik</th>
                                <th class="py-4 px-4 text-center w-[20%]">Kondisi Sedang</th>
                                <th class="py-4 px-4 text-center w-[20%]">Rusak Berat</th>
                                <th class="py-4 px-4 text-right w-[15%]">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach($kondisiKecamatan as $item)
                            <tr class="group hover:bg-slate-50/50 transition-colors">
                                <td class="py-5 px-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-xl bg-navy-50 flex items-center justify-center text-navy-500">
                                            <i class="fas fa-map-marker-alt text-[10px]"></i>
                                        </div>
                                        <p class="text-xs font-bold text-navy-900 uppercase">{{ $item['nama'] }}</p>
                                    </div>
                                </td>
                                <td class="py-5 px-4">
                                    <div class="flex items-center gap-3">
                                        <span class="text-[10px] font-black text-[#059669] w-6 text-right">{{ $item['baik'] }}</span>
                                        <div class="flex-1 h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                            <div class="h-full bg-[#059669] rounded-full" style="width: {{ $item['total'] > 0 ? ($item['baik'] / $item['total'] * 100) : 0 }}%"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-5 px-4">
                                    <div class="flex items-center gap-3">
                                        @php $sedang = ($item['ringan'] ?? 0) + ($item['sedang'] ?? 0); @endphp
                                        <span class="text-[10px] font-black text-[#d97706] w-6 text-right">{{ $sedang }}</span>
                                        <div class="flex-1 h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                            <div class="h-full bg-[#d97706] rounded-full" style="width: {{ $item['total'] > 0 ? ($sedang / $item['total'] * 100) : 0 }}%"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-5 px-4">
                                    <div class="flex items-center gap-3">
                                        <span class="text-[10px] font-black text-[#be123c] w-6 text-right">{{ $item['berat'] }}</span>
                                        <div class="flex-1 h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                            <div class="h-full bg-[#be123c] rounded-full" style="width: {{ $item['total'] > 0 ? ($item['berat'] / $item['total'] * 100) : 0 }}%"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-5 px-4 text-right">
                                    <span class="text-[10px] font-black text-navy-900 bg-slate-100 border border-slate-200 px-3 py-1.5 rounded-xl">{{ $item['total'] }} Unit</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
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

        // Chart.js
        // 🌟 KONFIGURASI KURVA-S (S-CURVE CHART)
        const ctx = document.getElementById('yearlyChart').getContext('2d');
        
        const chartLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

        // Menghitung data kumulatif untuk Kurva-S
        const rawData = @json($chartData);
        let cumulativeData = [];
        let total = 0;
        rawData.forEach(val => {
            total += val;
            cumulativeData.push(total);
        });

        // Membuat gradasi warna biru mewah untuk area bawah kurva
        const gradient = ctx.createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, 'rgba(197, 160, 89, 0.2)'); // gold-500
        gradient.addColorStop(1, 'rgba(197, 160, 89, 0.0)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: chartLabels,
                datasets: [{
                    label: 'Kumulatif Survey Masuk',
                    data: cumulativeData,
                    borderColor: '#c5a059', // gold-500
                    borderWidth: 4,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#c5a059',
                    pointBorderWidth: 3,
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    tension: 0.4, // Membuat efek melengkung
                    fill: true,
                    backgroundColor: gradient
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { borderDash: [5, 5], color: '#f1f5f9' },
                        ticks: { font: { size: 10, weight: 'bold' }, color: '#94a3b8' }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { font: { size: 10, weight: 'bold' }, color: '#94a3b8' }
                    }
                }
            }
        });

        // 🌟 KONFIGURASI DONUT CHART (VALIDASI)
        const donutCtx = document.getElementById('validationDonutChart').getContext('2d');
        const donutData = @json(array_values($donutData));
        
        // Jika tidak ada data sama sekali, tampilkan abu-abu
        const hasData = donutData.some(val => val > 0);
        const displayData = hasData ? donutData : [1];
        const displayColors = hasData 
            ? ['#f1f5f9', '#059669', '#f43f5e'] // Pending (slate-100), Validated (emerald-600), Rejected (rose-500)
            : ['#f8fafc'];

        new Chart(donutCtx, {
            type: 'doughnut',
            data: {
                labels: ['Menunggu', 'ACC', 'Ditolak'],
                datasets: [{
                    data: displayData,
                    backgroundColor: displayColors,
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '75%',
                plugins: {
                    legend: { display: false },
                    tooltip: { enabled: hasData }
                }
            }
        });
    </script>
</body>
</html>
