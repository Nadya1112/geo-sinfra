<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistik Tahunan <?php echo e($year); ?> | Admin SINFRA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        tailwind.config = {
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
</head>
<body class="bg-slate-50 flex h-screen overflow-hidden text-slate-800 font-sans">

    <?php echo $__env->make('admin.partials.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <main class="flex-1 flex flex-col h-screen overflow-hidden">

        
        <header class="bg-white/80 backdrop-blur-xl border-b border-slate-100 px-8 py-5 flex justify-between items-center z-40 shrink-0">
            <div class="flex items-center gap-4">
                <a href="<?php echo e(route('admin.statistik')); ?>"
                   class="w-10 h-10 bg-white border border-slate-100 rounded-xl flex items-center justify-center text-slate-400 hover:text-gold-500 hover:border-gold-500/30 hover:shadow-md transition-all group">
                    <i class="fas fa-arrow-left text-xs group-hover:-translate-x-1 transition-transform"></i>
                </a>
                <div>
                    <p class="text-[10px] font-black text-gold-500 uppercase tracking-[0.2em] mb-1">Yearly Report <?php echo e($year); ?></p>
                    <h2 class="text-xl font-black text-navy-900 leading-none">Analisis Tren Tahunan</h2>
                </div>
            </div>

            <div class="flex items-center gap-4">
                <div class="text-right hidden sm:block">
                    <p class="text-[11px] font-black text-navy-900" id="mini-clock">00:00 WITA</p>
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-tighter"><?php echo e(now()->translatedFormat('l, d F Y')); ?></p>
                </div>
                <div class="h-8 w-[1px] bg-slate-100"></div>

                
                <form action="<?php echo e(url()->current()); ?>" method="GET" class="relative group">
                    <select name="year" onchange="this.form.submit()" 
                            class="appearance-none bg-navy-900 text-gold-500 pl-8 pr-7 py-2 rounded-xl text-[10px] font-black tracking-widest uppercase outline-none cursor-pointer hover:shadow-lg hover:shadow-navy-900/20 transition-all">
                        <?php $__currentLoopData = $availableYears ?? [$year]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $y): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($y); ?>" <?php echo e($y == $year ? 'selected' : ''); ?>><?php echo e($y); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <i class="fas fa-calendar-alt absolute left-3 top-1/2 -translate-y-1/2 text-gold-500 text-[10px] pointer-events-none group-hover:scale-110 transition-transform"></i>
                    <i class="fas fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-gold-500 text-[8px] pointer-events-none group-hover:translate-y-0.5 transition-transform"></i>
                </form>

                
                <button onclick="window.print()"
                    class="flex items-center gap-2 bg-gold-500 hover:bg-gold-600 text-white px-4 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-md shadow-gold-500/20 transition-all">
                    <i class="fas fa-print"></i> Cetak Laporan
                </button>

                <div class="h-8 w-[1px] bg-slate-100"></div>
                <div class="flex items-center gap-3">
                    <a href="<?php echo e(route('admin.profile')); ?>" class="text-right group">
                        <p class="text-[11px] font-black text-navy-900 leading-none uppercase group-hover:text-gold-500 transition-all"><?php echo e(auth()->user()->name); ?></p>
                        <p class="text-[9px] font-bold text-emerald-500 uppercase mt-1">Online</p>
                    </a>
                    <a href="<?php echo e(route('admin.profile')); ?>" class="w-10 h-10 bg-navy-900 rounded-xl flex items-center justify-center text-gold-500 overflow-hidden hover:shadow-lg transition-all shadow-md">
                        <?php if(auth()->user()->profile_photo): ?>
                            <img src="<?php echo e(asset('storage/' . auth()->user()->profile_photo)); ?>" class="w-full h-full object-cover">
                        <?php else: ?>
                            <i class="fas fa-user-circle text-xl"></i>
                        <?php endif; ?>
                    </a>
                </div>
            </div>
        </header>

        
        <div class="flex-1 overflow-y-auto custom-scrollbar p-8 pb-16 space-y-6">

            
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
                            <p class="text-[9px] text-slate-400 font-semibold mt-0.5">Kurva-S kumulatif data survey per bulan · Tahun <?php echo e($year); ?></p>
                        </div>
                    </div>
                    <span class="px-4 py-2 bg-emerald-500 text-white rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-emerald-500/30">
                        <i class="fas fa-shield-alt mr-1"></i> Data Terverifikasi
                    </span>
                </div>

                <div class="h-72 w-full relative">
                    <canvas id="yearlyChart"></canvas>
                </div>
            </div>

            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                
                <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-8">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-8 h-8 bg-navy-900 rounded-xl flex items-center justify-center text-gold-500 shrink-0">
                            <i class="fas fa-layer-group text-xs"></i>
                        </div>
                        <div>
                            <h4 class="text-sm font-black text-navy-900 uppercase tracking-wider">Distribusi Jenis Infrastruktur</h4>
                            <p class="text-[9px] text-slate-400 font-semibold mt-0.5">Tahun <?php echo e($year); ?></p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <?php
                            $jenisColors = [
                                'jalan'    => ['bg'=>'bg-navy-900',   'bar'=>'bg-navy-900',    'text'=>'text-white',      'icon'=>'fa-road',         'label'=>'Jalan'],
                                'sanitasi' => ['bg'=>'bg-emerald-500','bar'=>'bg-emerald-500', 'text'=>'text-white',      'icon'=>'fa-faucet-drip',  'label'=>'Sanitasi'],
                                'titian'   => ['bg'=>'bg-gold-500',   'bar'=>'bg-gold-500',    'text'=>'text-white',      'icon'=>'fa-bridge-water', 'label'=>'Titian'],
                                'jembatan' => ['bg'=>'bg-navy-500',   'bar'=>'bg-navy-500',    'text'=>'text-white',      'icon'=>'fa-archway',      'label'=>'Jembatan'],
                            ];
                            $totalJenis = max(1, $statsJenis->sum('total'));
                        ?>

                        <?php $__empty_1 = true; $__currentLoopData = $statsJenis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php
                            $c   = $jenisColors[strtolower($s->jenis)] ?? ['bg'=>'bg-slate-400','bar'=>'bg-slate-400','text'=>'text-white','icon'=>'fa-cube','label'=>$s->jenis];
                            $pct = round(($s->total / $totalJenis) * 100);
                        ?>
                        <div class="p-4 bg-slate-50 border border-slate-100 rounded-2xl">
                            <div class="flex items-center gap-3 mb-3">
                                <div class="w-9 h-9 <?php echo e($c['bg']); ?> <?php echo e($c['text']); ?> rounded-xl flex items-center justify-center shrink-0 shadow-sm">
                                    <i class="fas <?php echo e($c['icon']); ?> text-sm"></i>
                                </div>
                                <div class="flex-1 flex justify-between items-center">
                                    <p class="text-xs font-black text-navy-900 uppercase"><?php echo e($c['label']); ?></p>
                                    <div class="flex items-center gap-2">
                                        <span class="text-[9px] font-bold text-slate-400"><?php echo e($pct); ?>%</span>
                                        <span class="text-sm font-black text-navy-900"><?php echo e($s->total); ?> <span class="text-[9px] text-slate-400 font-semibold">titik</span></span>
                                    </div>
                                </div>
                            </div>
                            <div class="w-full bg-slate-200 h-1.5 rounded-full overflow-hidden">
                                <div class="<?php echo e($c['bar']); ?> h-full rounded-full transition-all duration-700" style="width: <?php echo e($pct); ?>%"></div>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="text-center py-10">
                            <i class="fas fa-inbox text-4xl text-slate-200 mb-3 block"></i>
                            <p class="text-sm font-bold text-slate-400">Belum ada data jenis infrastruktur.</p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                
                <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-8">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-8 h-8 bg-gold-500 rounded-xl flex items-center justify-center text-white shrink-0">
                            <i class="fas fa-map-marked-alt text-xs"></i>
                        </div>
                        <div>
                            <h4 class="text-sm font-black text-navy-900 uppercase tracking-wider">Kondisi per Kecamatan</h4>
                            <p class="text-[9px] text-slate-400 font-semibold mt-0.5">Rekapitulasi wilayah · Tahun <?php echo e($year); ?></p>
                        </div>
                    </div>

                    <div class="overflow-x-auto custom-scrollbar">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-100">
                                    <th class="px-3 py-3 text-[9px] font-black text-slate-400 tracking-widest uppercase">Kecamatan</th>
                                    <th class="px-3 py-3 text-[9px] font-black text-emerald-500 tracking-widest text-center">Baik</th>
                                    <th class="px-3 py-3 text-[9px] font-black text-orange-500 tracking-widest text-center">Sedang</th>
                                    <th class="px-3 py-3 text-[9px] font-black text-red-500 tracking-widest text-center">Berat</th>
                                    <th class="px-3 py-3 text-[9px] font-black text-slate-400 tracking-widest text-right">Total</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                <?php $__currentLoopData = $kondisiKecamatan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="hover:bg-slate-50/60 transition-colors">
                                    <td class="px-3 py-3">
                                        <div class="flex items-center gap-2">
                                            <i class="fas fa-map-marker-alt text-gold-500 text-[9px]"></i>
                                            <p class="text-[10px] font-black text-navy-900 uppercase"><?php echo e($item['nama']); ?></p>
                                        </div>
                                    </td>
                                    <td class="px-3 py-3 text-center">
                                        <span class="text-[10px] font-black text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-lg"><?php echo e($item['baik']); ?></span>
                                    </td>
                                    <td class="px-3 py-3 text-center">
                                        <span class="text-[10px] font-black text-orange-600 bg-orange-50 px-2 py-0.5 rounded-lg"><?php echo e($item['sedang']); ?></span>
                                    </td>
                                    <td class="px-3 py-3 text-center">
                                        <span class="text-[10px] font-black text-red-600 bg-red-50 px-2 py-0.5 rounded-lg"><?php echo e($item['berat']); ?></span>
                                    </td>
                                    <td class="px-3 py-3 text-right">
                                        <span class="text-[10px] font-black text-navy-900 bg-navy-50 border border-navy-100 px-2 py-0.5 rounded-lg"><?php echo e($item['total']); ?></span>
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
            document.getElementById('mini-clock').textContent =
                `${String(now.getHours()).padStart(2,'0')}:${String(now.getMinutes()).padStart(2,'0')} WITA`;
        }
        setInterval(updateClock, 1000); updateClock();

        // Kurva-S Chart
        const ctx = document.getElementById('yearlyChart').getContext('2d');
        const monthLimit = 5;
        const allLabels = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
        const chartLabels = allLabels.slice(0, monthLimit);
        const rawData = <?php echo json_encode($chartData, 15, 512) ?>.slice(0, monthLimit);

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
</html><?php /**PATH C:\laragon1\laragon\www\geo-sinfra\resources\views/admin/statistik-tahunan.blade.php ENDPATH**/ ?>