<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistik Tahunan {{ $year }} | Admin SINFRA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        .glass-card { background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px); }
    </style>
</head>
<body class="bg-gray-50 flex h-screen overflow-hidden text-gray-800 text-left">

    @include('admin.partials.sidebar')

    <main class="flex-1 overflow-y-auto custom-scrollbar">
        <header class="sticky top-0 bg-white/80 backdrop-blur-md border-b border-gray-100 px-8 py-5 flex justify-between items-center z-40">
            <div class="flex items-center gap-4 text-left">
                <a href="{{ route('admin.statistik') }}" 
                   class="w-10 h-10 bg-white border border-gray-100 rounded-xl flex items-center justify-center text-gray-400 hover:text-blue-600 hover:border-blue-100 hover:shadow-lg hover:shadow-blue-500/5 transition-all group"
                   title="Kembali">
                    <i class="fas fa-arrow-left text-xs group-hover:-translate-x-1 transition-transform"></i>
                </a>

                <div class="text-left">
                    <p class="text-[10px] font-extrabold text-blue-600 uppercase tracking-[0.2em] mb-1">Yearly Report {{ $year }}</p>
                    <h2 class="text-xl font-black text-[#1e1b4b]">Analisis Tren Tahunan</h2>
                </div>
            </div>

            <div class="flex items-center gap-6">
                <div class="text-right hidden sm:block">
                    <p class="text-[11px] font-black text-[#1e1b4b]" id="mini-clock">00:00 WITA</p>
                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-tighter">{{ now()->translatedFormat('l, d F Y') }}</p>
                </div>
                <button onclick="window.print()" class="px-4 py-2 bg-blue-600 text-white rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-blue-600/20 hover:bg-blue-700 transition-all flex items-center gap-2">
                    <i class="fas fa-print"></i> Cetak Laporan
                </button>
            </div>
        </header>

        <div class="p-8">
            <div class="bg-white rounded-[2.5rem] p-8 border border-gray-100 shadow-sm mb-8 overflow-hidden relative">
                <div class="absolute top-0 right-0 w-64 h-64 bg-blue-500/5 rounded-full -mr-20 -mt-20 blur-3xl"></div>
                <div class="relative z-10">
                    <div class="flex justify-between items-start mb-8">
                        <div>
                            <h4 class="font-extrabold text-lg text-[#1e1b4b]">Grafik Pertumbuhan Laporan</h4>
                            <p class="text-xs text-gray-400 font-medium">Tren kuantitas data survey masuk per bulan</p>
                        </div>
                        <div class="px-4 py-2 bg-blue-50 rounded-xl">
                            <span class="text-[10px] font-black text-blue-600 uppercase tracking-widest">Tahun {{ $year }}</span>
                        </div>
                    </div>
                    
                    <div class="h-[300px] w-full">
                        <canvas id="yearlyChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <div class="bg-white rounded-[2.5rem] p-8 border border-gray-100 shadow-sm">
                    <h4 class="font-extrabold text-lg text-[#1e1b4b] mb-8">Distribusi Jenis Infrastruktur</h4>
                    <div class="space-y-6">
                        @foreach($statsJenis as $s)
                        @php
                            $colors = [
                                'jalan' => ['bg' => 'bg-blue-50', 'bar' => 'bg-blue-500', 'text' => 'text-blue-600', 'icon' => 'fa-road'],
                                'sanitasi' => ['bg' => 'bg-emerald-50', 'bar' => 'bg-emerald-500', 'text' => 'text-emerald-600', 'icon' => 'fa-faucet-drip'],
                                'titian' => ['bg' => 'bg-amber-50', 'bar' => 'bg-amber-500', 'text' => 'text-amber-600', 'icon' => 'fa-bridge-water'],
                            ];
                            $c = $colors[strtolower($s->jenis)] ?? ['bg' => 'bg-gray-50', 'bar' => 'bg-gray-500', 'text' => 'text-gray-600', 'icon' => 'fa-cube'];
                        @endphp
                        <div class="group">
                            <div class="flex justify-between items-center mb-2">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 {{ $c['bg'] }} {{ $c['text'] }} rounded-lg flex items-center justify-center text-[10px]">
                                        <i class="fas {{ $c['icon'] }}"></i>
                                    </div>
                                    <span class="text-xs font-bold text-[#1e1b4b] uppercase">{{ $s->jenis }}</span>
                                </div>
                                <span class="text-xs font-black text-[#1e1b4b]">{{ $s->total }} <span class="text-[10px] text-gray-400 font-medium">Titik</span></span>
                            </div>
                            <div class="w-full h-2 bg-gray-100 rounded-full overflow-hidden">
                                <div class="h-full {{ $c['bar'] }} rounded-full" style="width: {{ ($s->total / max(1, $statsJenis->sum('total'))) * 100 }}%"></div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="bg-white rounded-[2.5rem] p-8 border border-gray-100 shadow-sm">
                    <h4 class="font-extrabold text-lg text-[#1e1b4b] mb-8 text-left">Ringkasan Kondisi per Wilayah</h4>
                    <div class="overflow-x-auto custom-scrollbar">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="text-[9px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-100">
                                    <th class="pb-4 px-2">Kecamatan</th>
                                    <th class="pb-4 px-2 text-center text-emerald-600">Baik</th>
                                    <th class="pb-4 px-2 text-center text-yellow-600">Ringan</th>
                                    <th class="pb-4 px-2 text-center text-amber-500">Sedang</th> <th class="pb-4 px-2 text-center text-red-500">Berat</th>
                                    <th class="pb-4 px-2 text-right">Total</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @foreach($kondisiKecamatan as $item)
                                <tr class="group hover:bg-gray-50 transition-colors">
                                    <td class="py-4 px-2">
                                        <p class="text-xs font-bold text-[#1e1b4b] uppercase">{{ $item['nama'] }}</p>
                                    </td>
                                    <td class="py-4 px-2 text-center">
                                        <span class="text-[10px] font-black text-emerald-600">{{ $item['baik'] }}</span>
                                    </td>
                                    <td class="py-4 px-2 text-center">
                                        <span class="text-[10px] font-black text-yellow-600">{{ $item['ringan'] }}</span>
                                    </td>
                                    <td class="py-4 px-2 text-center">
                                        <span class="text-[10px] font-black text-amber-600">{{ $item['sedang'] }}</span>
                                    </td>
                                    <td class="py-4 px-2 text-center">
                                        <span class="text-[10px] font-black text-red-600">{{ $item['berat'] }}</span>
                                    </td>
                                    <td class="py-4 px-2 text-right">
                                        <span class="text-[10px] font-black text-[#1e1b4b] bg-gray-100 px-2 py-1 rounded-lg">{{ $item['total'] }}</span>
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
        // Clock Function
        function updateClock() {
            const now = new Date();
            document.getElementById('mini-clock').textContent = `${String(now.getHours()).padStart(2, '0')}:${String(now.getMinutes()).padStart(2, '0')} WITA`;
        }
        setInterval(updateClock, 1000); updateClock();

        // 🌟 KONFIGURASI KURVA-S (S-CURVE CHART)
        const ctx = document.getElementById('yearlyChart').getContext('2d');
        
        // Sesuaikan hanya sampai bulan Mei (5 bulan pertama)
        const monthLimit = 5; 
        const allLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        const chartLabels = allLabels.slice(0, monthLimit);

        // Menghitung data kumulatif untuk Kurva-S (Hanya sampai Mei)
        const rawData = @json($chartData).slice(0, monthLimit);
        let cumulativeData = [];
        let total = 0;
        rawData.forEach(val => {
            total += val;
            cumulativeData.push(total);
        });

        // Membuat gradasi warna biru mewah untuk area bawah kurva
        const gradient = ctx.createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, 'rgba(37, 99, 235, 0.2)');
        gradient.addColorStop(1, 'rgba(37, 99, 235, 0.0)');

        new Chart(ctx, {
            type: 'line', // Menggunakan tipe line
            data: {
                labels: chartLabels,
                datasets: [{
                    label: 'Kumulatif Survey Masuk',
                    data: cumulativeData,
                    borderColor: '#2563eb',
                    borderWidth: 4,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#2563eb',
                    pointBorderWidth: 3,
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    tension: 0.4, // Memberikan efek kurva yang mulus (S-Curve)
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
                        grid: { 
                            borderDash: [5, 5], 
                            color: '#f1f5f9' 
                        },
                        ticks: { 
                            font: { size: 10, weight: 'bold' }, 
                            color: '#94a3b8',
                            stepSize: 1 // Skala grafik berupa bilangan bulat
                        }
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