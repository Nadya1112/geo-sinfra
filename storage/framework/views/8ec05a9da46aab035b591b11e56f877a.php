<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kabid Dashboard | GEO-SINFRA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style> body { font-family: 'Plus Jakarta Sans', sans-serif; } </style>
</head>
<body class="bg-gray-50 flex h-screen overflow-hidden text-gray-800 text-left">

    <?php echo $__env->make('kabid.partials.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <main class="flex-1 flex flex-col h-screen overflow-y-auto">
        <header class="bg-white border-b border-gray-100 px-8 py-5 flex justify-between items-center z-10">
            <div>
                <p class="text-[10px] font-extrabold text-indigo-600 uppercase tracking-[0.2em] mb-1">Portal Kepala Bidang</p>
                <h2 class="text-xl font-black text-[#1e1b4b]">Panel Pengawasan</h2>
            </div>
            
            <div class="flex items-center gap-6">
                <div class="text-right hidden sm:block">
                    <p class="text-[11px] font-black text-[#1e1b4b]" id="mini-clock">00:00 WITA</p>
                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-tighter"><?php echo e(now()->translatedFormat('l, d F Y')); ?></p>
                </div>
                <div class="h-8 w-[1px] bg-gray-100"></div>
                <div class="flex items-center gap-3">
                    <div class="text-right">
                        <p class="text-[11px] font-black text-[#1e1b4b] leading-none uppercase"><?php echo e(auth()->user()->name); ?></p>
                        <p class="text-[9px] font-bold text-emerald-500 uppercase mt-1 italic">ONLINE</p>
                    </div>
                    <div class="w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600 border border-indigo-100 overflow-hidden shadow-sm">
                        <?php if(auth()->user()->profile_photo): ?>
                            <img src="<?php echo e(asset('storage/' . auth()->user()->profile_photo)); ?>" class="w-full h-full object-cover">
                        <?php else: ?>
                            <i class="fas fa-user-tie text-xl"></i>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </header>

        <div class="p-8 space-y-10">
            <!-- Welcome Section -->
            <div class="relative bg-[#1e1b4b] rounded-[3rem] p-10 overflow-hidden shadow-2xl shadow-indigo-900/20">
                <div class="absolute -right-20 -top-20 w-80 h-80 bg-indigo-500/20 rounded-full blur-[100px]"></div>
                <div class="absolute -left-10 -bottom-10 w-60 h-60 bg-purple-500/10 rounded-full blur-[80px]"></div>
                
                <div class="relative z-10">
                    <h1 class="text-3xl font-black text-white mb-2">Selamat Datang, Bapak <?php echo e(auth()->user()->name); ?></h1>
                    <p class="text-indigo-200/80 text-sm font-medium tracking-wide">Berikut ringkasan kondisi infrastruktur Banjarmasin saat ini.</p>
                </div>

                <!-- Stats Bar -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-10">
                    <div class="bg-white/5 backdrop-blur-md rounded-3xl p-6 border border-white/10 group hover:bg-white/10 transition-all">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-indigo-500/20 rounded-2xl flex items-center justify-center text-indigo-400">
                                <i class="fas fa-database text-lg"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-indigo-300 uppercase tracking-widest">Total Terdata</p>
                                <h3 class="text-2xl font-black text-white"><?php echo e($totalInfrastruktur); ?> <span class="text-[10px] font-bold text-indigo-300/50 italic ml-1">Objek</span></h3>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white/5 backdrop-blur-md rounded-3xl p-6 border border-white/10 group hover:bg-white/10 transition-all border-l-red-500/50 border-l-4">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-red-500/20 rounded-2xl flex items-center justify-center text-red-400">
                                <i class="fas fa-triangle-exclamation text-lg"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-red-300 uppercase tracking-widest">Rusak Berat</p>
                                <h3 class="text-2xl font-black text-white"><?php echo e($totalRusakBerat); ?> <span class="text-[10px] font-bold text-red-300/50 italic ml-1">Lokasi</span></h3>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white/5 backdrop-blur-md rounded-3xl p-6 border border-white/10 group hover:bg-white/10 transition-all border-l-amber-500/50 border-l-4">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-amber-500/20 rounded-2xl flex items-center justify-center text-amber-400">
                                <i class="fas fa-bolt text-lg"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-amber-300 uppercase tracking-widest">Prioritas Utama</p>
                                <h3 class="text-2xl font-black text-white"><?php echo e($totalPrioritas); ?> <span class="text-[10px] font-bold text-amber-300/50 italic ml-1">Tindakan</span></h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Menu Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <a href="#" class="bg-white p-8 rounded-[2.5rem] border border-gray-100 shadow-sm hover:shadow-xl hover:-translate-y-2 transition-all group relative overflow-hidden">
                    <div class="absolute -right-6 -top-6 w-24 h-24 bg-indigo-50 rounded-full group-hover:scale-150 transition-transform duration-700"></div>
                    <div class="relative z-10 flex flex-col h-full justify-between gap-6">
                        <div class="w-14 h-14 bg-indigo-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-indigo-200">
                            <i class="fas fa-map-location-dot text-xl"></i>
                        </div>
                        <div>
                            <h4 class="font-black text-[#1e1b4b] text-sm uppercase tracking-tight mb-2">Monitoring Peta Sebaran</h4>
                            <p class="text-[10px] text-gray-400 font-medium leading-relaxed">Pantau persebaran infrastruktur di seluruh wilayah Banjarmasin secara real-time.</p>
                        </div>
                    </div>
                </a>

                <a href="#" class="bg-white p-8 rounded-[2.5rem] border border-gray-100 shadow-sm hover:shadow-xl hover:-translate-y-2 transition-all group relative overflow-hidden">
                    <div class="absolute -right-6 -top-6 w-24 h-24 bg-emerald-50 rounded-full group-hover:scale-150 transition-transform duration-700"></div>
                    <div class="relative z-10 flex flex-col h-full justify-between gap-6">
                        <div class="w-14 h-14 bg-emerald-500 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-emerald-200">
                            <i class="fas fa-file-circle-check text-xl"></i>
                        </div>
                        <div>
                            <h4 class="font-black text-[#1e1b4b] text-sm uppercase tracking-tight mb-2">Verifikasi Usulan Perbaikan</h4>
                            <p class="text-[10px] text-gray-400 font-medium leading-relaxed">Tinjau dan beri persetujuan pada laporan kerusakan dari surveyor lapangan.</p>
                        </div>
                    </div>
                </a>

                <a href="#" class="bg-white p-8 rounded-[2.5rem] border border-gray-100 shadow-sm hover:shadow-xl hover:-translate-y-2 transition-all group relative overflow-hidden">
                    <div class="absolute -right-6 -top-6 w-24 h-24 bg-amber-50 rounded-full group-hover:scale-150 transition-transform duration-700"></div>
                    <div class="relative z-10 flex flex-col h-full justify-between gap-6">
                        <div class="w-14 h-14 bg-amber-500 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-amber-200">
                            <i class="fas fa-chart-line text-xl"></i>
                        </div>
                        <div>
                            <h4 class="font-black text-[#1e1b4b] text-sm uppercase tracking-tight mb-2">Laporan Statistik Tahunan</h4>
                            <p class="text-[10px] text-gray-400 font-medium leading-relaxed">Lihat tren kondisi infrastruktur dan capaian perbaikan dalam satu tahun terakhir.</p>
                        </div>
                    </div>
                </a>

                <a href="#" class="bg-white p-8 rounded-[2.5rem] border border-gray-100 shadow-sm hover:shadow-xl hover:-translate-y-2 transition-all group relative overflow-hidden">
                    <div class="absolute -right-6 -top-6 w-24 h-24 bg-rose-50 rounded-full group-hover:scale-150 transition-transform duration-700"></div>
                    <div class="relative z-10 flex flex-col h-full justify-between gap-6">
                        <div class="w-14 h-14 bg-rose-500 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-rose-200">
                            <i class="fas fa-file-pdf text-xl"></i>
                        </div>
                        <div>
                            <h4 class="font-black text-[#1e1b4b] text-sm uppercase tracking-tight mb-2">Cetak Laporan Resmi PDF</h4>
                            <p class="text-[10px] text-gray-400 font-medium leading-relaxed">Ekspor ringkasan data pengawasan menjadi dokumen resmi siap cetak.</p>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Recent Activity Table (Secondary) -->
            <div class="bg-white rounded-[3rem] p-8 border border-gray-100 shadow-sm overflow-hidden">
                <div class="flex justify-between items-center mb-8 px-4">
                    <div>
                        <h4 class="font-black text-xl text-[#1e1b4b]">Laporan Terbaru</h4>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-1">Update masuk dari surveyor</p>
                    </div>
                    <a href="#" class="text-[10px] font-black text-indigo-600 uppercase tracking-widest hover:bg-indigo-50 px-4 py-2 rounded-xl transition-all">Semua Laporan <i class="fas fa-arrow-right ml-2"></i></a>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-separate border-spacing-y-3">
                        <thead>
                            <tr class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">
                                <th class="px-6 py-4">Infrastruktur</th>
                                <th class="px-6 py-4">Wilayah</th>
                                <th class="px-6 py-4">Kondisi</th>
                                <th class="px-6 py-4">Surveyor</th>
                                <th class="px-6 py-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $recentReports; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $report): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="group hover:bg-gray-50/80 transition-all rounded-3xl">
                                <td class="px-6 py-4 bg-gray-50/50 group-hover:bg-white first:rounded-l-3xl transition-all border-y border-transparent group-hover:border-gray-100">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 rounded-2xl bg-gray-200 overflow-hidden shadow-inner">
                                            <img src="<?php echo e(asset('storage/' . $report->foto_terbaru)); ?>" class="w-full h-full object-cover">
                                        </div>
                                        <div>
                                            <p class="font-black text-sm text-[#1e1b4b]"><?php echo e($report->nama_infrastruktur); ?></p>
                                            <p class="text-[9px] font-bold text-gray-400 uppercase"><?php echo e($report->jenis_infrastruktur); ?></p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 bg-gray-50/50 group-hover:bg-white transition-all border-y border-transparent group-hover:border-gray-100">
                                    <div class="flex flex-col">
                                        <p class="text-xs font-bold text-gray-600"><?php echo e($report->kecamatan->nama_kecamatan ?? 'Banjarmasin'); ?></p>
                                        <p class="text-[9px] text-gray-400"><?php echo e($report->kelurahan->nama_kelurahan ?? '-'); ?></p>
                                    </div>
                                </td>
                                <td class="px-6 py-4 bg-gray-50/50 group-hover:bg-white transition-all border-y border-transparent group-hover:border-gray-100">
                                    <span class="px-3 py-1.5 rounded-full text-[9px] font-black uppercase tracking-widest <?php echo e($report->kondisi == 'Rusak Berat' ? 'bg-red-50 text-red-600 border border-red-100' : 'bg-amber-50 text-amber-600 border border-amber-100'); ?>">
                                        <?php echo e($report->kondisi); ?>

                                    </span>
                                </td>
                                <td class="px-6 py-4 bg-gray-50/50 group-hover:bg-white transition-all border-y border-transparent group-hover:border-gray-100">
                                    <p class="text-xs font-bold text-gray-600"><?php echo e($report->user->name ?? 'User'); ?></p>
                                    <p class="text-[9px] text-gray-400 italic"><?php echo e($report->created_at->diffForHumans()); ?></p>
                                </td>
                                <td class="px-6 py-4 bg-gray-50/50 group-hover:bg-white last:rounded-r-3xl transition-all border-y border-transparent group-hover:border-gray-100">
                                    <button class="w-8 h-8 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center hover:bg-indigo-600 hover:text-white transition-all shadow-sm">
                                        <i class="fas fa-arrow-right text-[10px]"></i>
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="5" class="text-center py-10 text-gray-400 italic text-xs font-bold">Belum ada laporan masuk.</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <script>
        function updateClock() {
            const now = new Date();
            document.getElementById('mini-clock').textContent = `${String(now.getHours()).padStart(2, '0')}:${String(now.getMinutes()).padStart(2, '0')} WITA`;
        }
        setInterval(updateClock, 1000); updateClock();
    </script>
</body>
</html>
<?php /**PATH C:\laragon1\laragon\www\geo-sinfra\resources\views/kabid/dashboard.blade.php ENDPATH**/ ?>