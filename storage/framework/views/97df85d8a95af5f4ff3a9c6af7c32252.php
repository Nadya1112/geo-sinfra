<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Laporan & Rekapitulasi | Kabid SINFRA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        @media print {
            .no-print { display: none !important; }
            .print-only { display: block !important; }
            body { background: white; }
            .flex { display: block; }
            aside { display: none; }
            main { width: 100%; margin: 0; padding: 0; }
        }
    </style>
</head>
<body class="bg-gray-50 flex h-screen overflow-hidden text-gray-800 text-left">

    <?php echo $__env->make('kabid.partials.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <main class="flex-1 flex flex-col h-screen overflow-hidden">
        <header class="bg-white border-b border-gray-100 px-8 py-5 flex justify-between items-center z-10 no-print">
            <div class="flex items-center gap-4">
                <a href="<?php echo e(route('kabid.dashboard')); ?>" class="w-10 h-10 flex items-center justify-center bg-gray-50 text-gray-400 rounded-xl hover:bg-indigo-50 hover:text-indigo-600 transition-all border border-gray-100">
                    <i class="fas fa-arrow-left text-sm"></i>
                </a>
                <div>
                    <p class="text-[10px] font-extrabold text-indigo-600 uppercase tracking-[0.2em] mb-1">Reporting Center</p>
                    <h2 class="text-xl font-black text-[#1e1b4b]">Laporan & Rekapitulasi</h2>
                </div>
            </div>
            
            <div class="flex items-center gap-6">
                <button onclick="window.print()" class="no-print px-4 py-2 bg-indigo-50 text-indigo-600 rounded-xl text-[9px] font-black uppercase tracking-widest hover:bg-indigo-100 transition-all flex items-center gap-2 border border-indigo-100">
                    <i class="fas fa-print"></i> Cetak Laporan
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

        <div class="flex-1 overflow-y-auto p-8 custom-scrollbar">
            <!-- Filter Section (No Print) -->
            <div class="bg-white rounded-[2rem] p-8 border border-gray-100 shadow-sm mb-8 no-print">
                <form action="<?php echo e(route('kabid.laporan')); ?>" method="GET" class="flex flex-wrap md:flex-nowrap gap-6 items-end">
                    <input type="hidden" name="show" value="<?php echo e(request('show')); ?>">
                    <div class="w-full md:w-1/4">
                        <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest block mb-2">Kecamatan</label>
                        <select name="kecamatan" class="w-full bg-gray-50 border border-gray-100 rounded-xl px-4 py-2.5 text-xs font-bold focus:outline-none focus:ring-2 focus:ring-indigo-500/20 transition-all">
                            <option value="">Semua Wilayah</option>
                            <?php $__currentLoopData = $kecamatan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kec): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($kec->id_kecamatan); ?>" <?php echo e(request('kecamatan') == $kec->id_kecamatan ? 'selected' : ''); ?>>
                                    <?php echo e($kec->nama_kecamatan); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="w-full md:w-1/4">
                        <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest block mb-2">Kondisi AI</label>
                        <select name="kondisi" class="w-full bg-gray-50 border border-gray-100 rounded-xl px-4 py-2.5 text-xs font-bold focus:outline-none focus:ring-2 focus:ring-indigo-500/20 transition-all">
                            <option value="">Semua Kondisi</option>
                            <option value="Baik" <?php echo e(request('kondisi') == 'Baik' ? 'selected' : ''); ?>>Baik</option>
                            <option value="Rusak Ringan" <?php echo e(request('kondisi') == 'Rusak Ringan' ? 'selected' : ''); ?>>Rusak Ringan</option>
                            <option value="Rusak Berat" <?php echo e(request('kondisi') == 'Rusak Berat' ? 'selected' : ''); ?>>Rusak Berat</option>
                        </select>
                    </div>
                    <div class="w-full md:w-1/4">
                        <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest block mb-2">Jenis Infrastruktur</label>
                        <select name="jenis" class="w-full bg-gray-50 border border-gray-100 rounded-xl px-4 py-2.5 text-xs font-bold focus:outline-none focus:ring-2 focus:ring-indigo-500/20 transition-all">
                            <option value="">Semua Jenis</option>
                            <option value="Jalan" <?php echo e(request('jenis') == 'Jalan' ? 'selected' : ''); ?>>Jalan</option>
                            <option value="Titian" <?php echo e(request('jenis') == 'Titian' ? 'selected' : ''); ?>>Titian</option>
                            <option value="Sanitasi" <?php echo e(request('jenis') == 'Sanitasi' ? 'selected' : ''); ?>>Sanitasi</option>
                        </select>
                    </div>
                    <div class="w-full md:w-1/4 flex gap-2 justify-end">
                        <button type="submit" class="px-6 py-2.5 bg-indigo-50 text-indigo-600 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-indigo-100 transition-all">
                            Filter Data
                        </button>
                        <a href="<?php echo e(route('kabid.laporan')); ?>" class="px-4 py-2.5 bg-gray-50 text-gray-400 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-gray-100 transition-all flex items-center">
                            <i class="fas fa-sync-alt"></i>
                        </a>
                    </div>
                </form>
            </div>

            <!-- Print Header (Hidden on Screen) -->
            <div class="hidden print-only mb-10 text-center border-b-2 border-[#1e1b4b] pb-6">
                <h1 class="text-2xl font-black text-[#1e1b4b] uppercase tracking-tighter">Laporan Rekapitulasi Infrastruktur</h1>
                <p class="text-sm font-bold text-gray-500 mt-1 uppercase">Sistem Informasi Geospasial (GEO-SINFRA)</p>
                <div class="mt-4 flex justify-center gap-8 text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                    <span>Wilayah: <?php echo e(request('kecamatan') ? $kecamatan->find(request('kecamatan'))->nama_kecamatan : 'Semua'); ?></span>
                    <span>Kondisi: <?php echo e(request('kondisi') ?: 'Semua'); ?></span>
                    <span>Dicetak: <?php echo e(now()->translatedFormat('d F Y H:i')); ?></span>
                </div>
            </div>

            <!-- Data Table -->
            <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm overflow-hidden mt-6">
                <!-- Header with Tampilan Dropdown -->
                <div class="px-8 py-6 border-b border-gray-50 flex justify-between items-center bg-gray-50/30 no-print">
                    <div>
                        <h3 class="text-sm font-black text-[#1e1b4b] uppercase tracking-widest">Data Laporan</h3>
                        <p class="text-[9px] text-gray-400 font-bold uppercase mt-1">Hasil filter rekapitulasi data</p>
                    </div>
                    <div>
                        <form action="<?php echo e(route('kabid.laporan')); ?>" method="GET" class="flex items-center gap-2">
                            <?php $__currentLoopData = request()->except('show'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if(is_array($value)): ?>
                                    <?php $__currentLoopData = $value; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <input type="hidden" name="<?php echo e($key); ?>[]" value="<?php echo e($v); ?>">
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php else: ?>
                                    <input type="hidden" name="<?php echo e($key); ?>" value="<?php echo e($value); ?>">
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Tampilan:</label>
                            <select name="show" onchange="this.form.submit()" class="text-xs font-bold text-[#1e1b4b] bg-white border border-gray-200 rounded-xl px-3 py-1.5 focus:outline-none focus:border-indigo-500 transition-colors">
                                <option value="10" <?php echo e(request('show') != 'all' ? 'selected' : ''); ?>>Per 10 Data</option>
                                <option value="all" <?php echo e(request('show') == 'all' ? 'selected' : ''); ?>>Semua Data</option>
                            </select>
                        </form>
                    </div>
                </div>
                <?php if(request('kecamatan') || request('kondisi') || request('jenis')): ?>
                <div class="bg-indigo-50/50 px-6 py-4 border-b border-indigo-100/50 flex flex-wrap items-center gap-3 no-print">
                    <span class="text-[9px] font-black text-indigo-400 uppercase tracking-widest mr-2">Filter Aktif:</span>
                    <?php if(request('kecamatan')): ?>
                        <span class="px-3 py-1 bg-white text-indigo-600 rounded-full text-[10px] font-bold shadow-sm border border-indigo-100">
                            <i class="fas fa-map-marker-alt mr-1"></i> <?php echo e($kecamatan->find(request('kecamatan'))->nama_kecamatan ?? 'Wilayah'); ?>

                        </span>
                    <?php endif; ?>
                    <?php if(request('kondisi')): ?>
                        <span class="px-3 py-1 bg-white text-indigo-600 rounded-full text-[10px] font-bold shadow-sm border border-indigo-100">
                            <i class="fas fa-heartbeat mr-1"></i> <?php echo e(request('kondisi')); ?>

                        </span>
                    <?php endif; ?>
                    <?php if(request('jenis')): ?>
                        <span class="px-3 py-1 bg-white text-indigo-600 rounded-full text-[10px] font-bold shadow-sm border border-indigo-100">
                            <i class="fas fa-layer-group mr-1"></i> <?php echo e(request('jenis')); ?>

                        </span>
                    <?php endif; ?>
                    <a href="<?php echo e(route('kabid.laporan')); ?>" class="ml-auto text-[10px] font-bold text-red-400 hover:text-red-600 transition-all">
                        <i class="fas fa-times mr-1"></i> Hapus Filter
                    </a>
                </div>
                <?php endif; ?>
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-gray-50/50 text-[9px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-100">
                            <th class="px-6 py-2">No</th>
                            <th class="px-6 py-2">Infrastruktur</th>
                            <th class="px-6 py-2">Wilayah</th>
                            <th class="px-6 py-2 text-center">Kondisi</th>
                            <th class="px-6 py-2">Surveyor</th>
                            <th class="px-6 py-2">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        <?php $__empty_1 = true; $__currentLoopData = $reports; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="group hover:bg-indigo-50/30 transition-all">
                            <td class="px-6 py-2 text-xs font-bold text-gray-400"><?php echo e(request('show') == 'all' ? $index + 1 : ($reports->currentPage() - 1) * $reports->perPage() + $index + 1); ?></td>
                            <td class="px-6 py-2">
                                <p class="text-xs font-black text-[#1e1b4b] uppercase"><?php echo e($item->nama_objek); ?></p>
                                <p class="text-[9px] text-indigo-500 font-bold uppercase mt-0.5"><?php echo e($item->jenis); ?></p>
                            </td>
                            <td class="px-6 py-2">
                                <p class="text-xs font-bold text-gray-600"><?php echo e($item->kelurahan->nama_kelurahan ?? '-'); ?></p>
                                <p class="text-[9px] text-gray-400 font-bold uppercase mt-0.5"><?php echo e($item->kelurahan->kecamatan->nama_kecamatan ?? '-'); ?></p>
                            </td>
                            <td class="px-6 py-2">
                                <div class="flex justify-center">
                                    <?php
                                        $color = $item->kondisi == 'Baik' ? 'emerald' : ($item->kondisi == 'Rusak Ringan' ? 'amber' : 'red');
                                    ?>
                                    <span class="px-2 py-1 bg-<?php echo e($color); ?>-50 text-<?php echo e($color); ?>-600 rounded-lg text-[8px] font-black uppercase border border-<?php echo e($color); ?>-100">
                                        <?php echo e($item->kondisi); ?>

                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-2">
                                <p class="text-xs font-bold text-gray-600"><?php echo e($item->user->name ?? 'System'); ?></p>
                            </td>
                            <td class="px-6 py-2 text-xs font-bold text-gray-400">
                                <?php echo e($item->created_at->format('d/m/Y')); ?>

                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="6" class="px-6 py-20 text-center">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-folder-open text-gray-200 text-4xl mb-4"></i>
                                    <p class="text-xs text-gray-400 font-bold italic uppercase">Tidak ada data yang ditemukan sesuai filter.</p>
                                </div>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
                
                <?php if(request('show') != 'all' && isset($reports) && $reports instanceof \Illuminate\Pagination\LengthAwarePaginator): ?>
                    <div class="px-8 py-4 border-t border-gray-50 bg-gray-50/10 no-print">
                        <?php echo e($reports->links()); ?>

                    </div>
                <?php endif; ?>
            </div>

            <!-- Print Footer -->
            <div class="hidden print-only mt-20 grid grid-cols-2 text-center">
                <div></div>
                <div class="text-xs font-bold">
                    <p>Banjarmasin, <?php echo e(now()->translatedFormat('d F Y')); ?></p>
                    <p class="mt-2 text-[10px] text-gray-400 uppercase tracking-widest">Mengetahui,</p>
                    <p class="mt-16 font-black uppercase text-[#1e1b4b] underline">KABID SINFRA</p>
                    <p class="text-[10px] text-gray-400 font-bold">NIP. 19850320 201001 1 005</p>
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
<?php /**PATH C:\laragon1\laragon\www\geo-sinfra\resources\views/kabid/laporan.blade.php ENDPATH**/ ?>