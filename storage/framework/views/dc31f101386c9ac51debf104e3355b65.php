<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistik Laporan | Kabid SINFRA</title>
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

    <?php echo $__env->make('kabid.partials.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <main class="flex-1 overflow-y-auto custom-scrollbar">
        <header class="sticky top-0 bg-white/80 backdrop-blur-md border-b border-gray-100 px-8 py-5 flex justify-between items-center z-40">
            <div class="flex items-center gap-4 text-left">
                <a href="<?php echo e(route('kabid.dashboard')); ?>" 
                   class="w-10 h-10 bg-white border border-gray-100 rounded-xl flex items-center justify-center text-gray-400 hover:text-indigo-600 hover:border-indigo-100 hover:shadow-lg hover:shadow-indigo-500/5 transition-all group"
                   title="Kembali">
                    <i class="fas fa-arrow-left text-xs group-hover:-translate-x-1 transition-transform"></i>
                </a>

                <div class="text-left">
                    <p class="text-[10px] font-extrabold text-indigo-600 uppercase tracking-[0.2em] mb-1">Report Analysis</p>
                    <h2 class="text-xl font-black text-[#1e1b4b]">Statistik Laporan</h2>
                </div>
            </div>

            <div class="flex items-center gap-6">
                <button onclick="window.print()" class="no-print px-4 py-2 bg-indigo-50 text-indigo-600 rounded-xl text-[9px] font-black uppercase tracking-widest hover:bg-indigo-100 transition-all flex items-center gap-2 border border-indigo-100">
                    <i class="fas fa-print"></i> Ekspor
                </button>
                <div class="text-right hidden sm:block">
                    <p class="text-[11px] font-black text-[#1e1b4b]" id="mini-clock">00:00 WITA</p>
                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-tighter"><?php echo e(now()->translatedFormat('l, d F Y')); ?></p>
                </div>
                <div class="h-8 w-[1px] bg-gray-100"></div>
                <a href="<?php echo e(route('kabid.profile')); ?>" class="flex items-center gap-3 group">
                    <div class="text-right">
                        <p class="text-[11px] font-black text-[#1e1b4b] leading-none uppercase group-hover:text-indigo-600 transition-colors"><?php echo e(auth()->user()->name); ?></p>
                        <p class="text-[9px] font-bold text-emerald-500 uppercase mt-1 italic">ONLINE</p>
                    </div>
                    <div class="w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600 border border-indigo-100 overflow-hidden shadow-sm group-hover:border-indigo-300 group-hover:shadow-md transition-all">
                        <?php if(auth()->user()->profile_photo): ?>
                            <img src="<?php echo e(asset('storage/' . auth()->user()->profile_photo)); ?>" class="w-full h-full object-cover">
                        <?php else: ?>
                            <i class="fas fa-user-tie text-xl"></i>
                        <?php endif; ?>
                    </div>
                </a>
            </div>
        </header>

        <div class="p-8">
            <!-- Growth Overview -->
            <div class="bg-white rounded-[2.5rem] p-8 border border-gray-100 shadow-sm mb-8 overflow-hidden relative">
                <div class="absolute top-0 right-0 w-64 h-64 bg-indigo-500/5 rounded-full -mr-20 -mt-20 blur-3xl"></div>
                <div class="relative z-10">
                    <div class="flex justify-between items-start mb-8">
                            <h4 class="font-extrabold text-lg text-[#1e1b4b]">Grafik Tren Laporan</h4>
                            <p class="text-xs text-gray-400 font-medium">Monitoring jumlah survey yang masuk per bulan</p>
                        </div>
                        <form action="<?php echo e(route('kabid.statistik.tahunan')); ?>" method="GET" id="yearForm">
                            <div class="px-4 py-2 bg-indigo-50 rounded-xl flex items-center gap-3 border border-indigo-100">
                                <span class="text-[9px] font-black text-indigo-400 uppercase tracking-widest">Periode</span>
                                <select name="year" onchange="document.getElementById('yearForm').submit()" class="bg-transparent text-[10px] font-black text-indigo-600 focus:outline-none cursor-pointer">
                                    <?php $__currentLoopData = $availableYears; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $y): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($y); ?>" <?php echo e($year == $y ? 'selected' : ''); ?>><?php echo e($y); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <i class="fas fa-chevron-down text-[8px] text-indigo-400"></i>
                            </div>
                        </form>
                    </div>
                    
                    <div class="h-[300px] w-full">
                        <canvas id="yearlyChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Distribution by Type -->
                <div class="bg-white rounded-[2.5rem] p-8 border border-gray-100 shadow-sm">
                    <h4 class="font-extrabold text-lg text-[#1e1b4b] mb-8">Sebaran Berdasarkan Kategori</h4>
                    <div class="space-y-6">
                        <?php $__currentLoopData = $statsJenis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $colors = [
                                'jalan' => ['bg' => 'bg-blue-50', 'bar' => 'bg-blue-500', 'text' => 'text-blue-600', 'icon' => 'fa-road'],
                                'sanitasi' => ['bg' => 'bg-emerald-50', 'bar' => 'bg-emerald-500', 'text' => 'text-emerald-600', 'icon' => 'fa-faucet-drip'],
                                'titian' => ['bg' => 'bg-amber-50', 'bar' => 'bg-amber-500', 'text' => 'text-amber-600', 'icon' => 'fa-bridge-water'],
                            ];
                            $c = $colors[strtolower($s->jenis)] ?? ['bg' => 'bg-gray-50', 'bar' => 'bg-gray-500', 'text' => 'text-gray-600', 'icon' => 'fa-cube'];
                        ?>
                        <div class="group">
                            <div class="flex justify-between items-center mb-2">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 <?php echo e($c['bg']); ?> <?php echo e($c['text']); ?> rounded-lg flex items-center justify-center text-[10px]">
                                        <i class="fas <?php echo e($c['icon']); ?>"></i>
                                    </div>
                                    <span class="text-xs font-bold text-[#1e1b4b] uppercase"><?php echo e($s->jenis); ?></span>
                                </div>
                                <span class="text-xs font-black text-[#1e1b4b]"><?php echo e($s->total); ?> <span class="text-[10px] text-gray-400 font-medium">Unit</span></span>
                            </div>
                            <div class="w-full h-2 bg-gray-100 rounded-full overflow-hidden">
                                <div class="h-full <?php echo e($c['bar']); ?> rounded-full" style="width: <?php echo e(($s->total / max(1, $statsJenis->sum('total'))) * 100); ?>%"></div>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>

                <!-- Condition Summary Table -->
                <div class="bg-white rounded-[2.5rem] p-8 border border-gray-100 shadow-sm">
                    <h4 class="font-extrabold text-lg text-[#1e1b4b] mb-8 text-left">Peta Kondisi per Kecamatan</h4>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="text-[9px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-100">
                                    <th class="pb-4 px-2">Wilayah</th>
                                    <th class="pb-4 px-2 text-center">Baik</th>
                                    <th class="pb-4 px-2 text-center text-emerald-500">Ringan</th>
                                    <th class="pb-4 px-2 text-center text-amber-500">Sedang</th>
                                    <th class="pb-4 px-2 text-center text-red-500">Berat</th>
                                    <th class="pb-4 px-2 text-right">Total</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                <?php $__currentLoopData = $kondisiKecamatan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="group hover:bg-gray-50 transition-colors">
                                    <td class="py-4 px-2">
                                        <p class="text-xs font-bold text-[#1e1b4b] uppercase"><?php echo e($item['nama']); ?></p>
                                    </td>
                                    <td class="py-4 px-2 text-center">
                                        <span class="text-[10px] font-black text-emerald-600"><?php echo e($item['baik']); ?></span>
                                    </td>
                                    <td class="py-4 px-2 text-center">
                                        <span class="text-[10px] font-black text-emerald-500"><?php echo e($item['ringan']); ?></span>
                                    </td>
                                    <td class="py-4 px-2 text-center">
                                        <span class="text-[10px] font-black text-amber-600"><?php echo e($item['sedang']); ?></span>
                                    </td>
                                    <td class="py-4 px-2 text-center">
                                        <span class="text-[10px] font-black text-red-600"><?php echo e($item['berat']); ?></span>
                                    </td>
                                    <td class="py-4 px-2 text-right">
                                        <span class="text-[10px] font-black text-[#1e1b4b] bg-gray-100 px-2 py-1 rounded-lg"><?php echo e($item['total']); ?></span>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
            document.getElementById('mini-clock').textContent = `${String(now.getHours()).padStart(2, '0')}:${String(now.getMinutes()).padStart(2, '0')} WITA`;
        }
        setInterval(updateClock, 1000); updateClock();

        // Chart.js
        const ctx = document.getElementById('yearlyChart').getContext('2d');
        const gradient = ctx.createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, 'rgba(79, 70, 229, 0.2)');
        gradient.addColorStop(1, 'rgba(79, 70, 229, 0.0)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                datasets: [{
                    label: 'Jumlah Laporan',
                    data: <?php echo json_encode($chartData, 15, 512) ?>,
                    borderColor: '#4f46e5',
                    borderWidth: 4,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#4f46e5',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    tension: 0.4,
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
    </script>
</body>
</html>
<?php /**PATH C:\laragon1\laragon\www\geo-sinfra\resources\views/kabid/statistik-tahunan.blade.php ENDPATH**/ ?>