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
            
            <button onclick="window.print()" class="px-6 py-2.5 bg-[#1e1b4b] text-white rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-indigo-900/10 hover:bg-indigo-600 transition-all flex items-center gap-2">
                <i class="fas fa-print"></i> Cetak Laporan
            </button>
        </header>

        <div class="flex-1 overflow-y-auto p-8 custom-scrollbar">
            <!-- Filter Section (No Print) -->
            <div class="bg-white rounded-[2rem] p-8 border border-gray-100 shadow-sm mb-8 no-print">
                <form action="<?php echo e(route('kabid.laporan')); ?>" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-6 items-end">
                    <div>
                        <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest block mb-2">Wilayah Kecamatan</label>
                        <select name="kecamatan" class="w-full bg-gray-50 border border-gray-100 rounded-xl px-4 py-2.5 text-xs font-bold focus:outline-none focus:ring-2 focus:ring-indigo-500/20 transition-all">
                            <option value="">Semua Wilayah</option>
                            <?php $__currentLoopData = $kecamatan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($k->id_kecamatan); ?>" <?php echo e(request('kecamatan') == $k->id_kecamatan ? 'selected' : ''); ?>><?php echo e($k->nama_kecamatan); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div>
                        <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest block mb-2">Kondisi</label>
                        <select name="kondisi" class="w-full bg-gray-50 border border-gray-100 rounded-xl px-4 py-2.5 text-xs font-bold focus:outline-none focus:ring-2 focus:ring-indigo-500/20 transition-all">
                            <option value="">Semua Kondisi</option>
                            <option value="Baik" <?php echo e(request('kondisi') == 'Baik' ? 'selected' : ''); ?>>Baik</option>
                            <option value="Rusak Ringan" <?php echo e(request('kondisi') == 'Rusak Ringan' ? 'selected' : ''); ?>>Rusak Ringan</option>
                            <option value="Rusak Berat" <?php echo e(request('kondisi') == 'Rusak Berat' ? 'selected' : ''); ?>>Rusak Berat</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest block mb-2">Jenis Infrastruktur</label>
                        <select name="jenis" class="w-full bg-gray-50 border border-gray-100 rounded-xl px-4 py-2.5 text-xs font-bold focus:outline-none focus:ring-2 focus:ring-indigo-500/20 transition-all">
                            <option value="">Semua Jenis</option>
                            <option value="Jalan" <?php echo e(request('jenis') == 'Jalan' ? 'selected' : ''); ?>>Jalan</option>
                            <option value="Jembatan" <?php echo e(request('jenis') == 'Jembatan' ? 'selected' : ''); ?>>Jembatan</option>
                            <option value="Drainase" <?php echo e(request('jenis') == 'Drainase' ? 'selected' : ''); ?>>Drainase</option>
                        </select>
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="flex-1 py-2.5 bg-indigo-50 text-indigo-600 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-indigo-100 transition-all">
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
            <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm overflow-hidden">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-gray-50/50 text-[9px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-100">
                            <th class="px-6 py-4">No</th>
                            <th class="px-6 py-4">Infrastruktur</th>
                            <th class="px-6 py-4">Wilayah</th>
                            <th class="px-6 py-4 text-center">Kondisi</th>
                            <th class="px-6 py-4">Surveyor</th>
                            <th class="px-6 py-4">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        <?php $__empty_1 = true; $__currentLoopData = $reports; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="group hover:bg-indigo-50/30 transition-all">
                            <td class="px-6 py-4 text-xs font-bold text-gray-400"><?php echo e($index + 1); ?></td>
                            <td class="px-6 py-4">
                                <p class="text-xs font-black text-[#1e1b4b] uppercase"><?php echo e($item->nama_infrastruktur); ?></p>
                                <p class="text-[9px] text-indigo-500 font-bold uppercase mt-0.5"><?php echo e($item->jenis_infrastruktur); ?></p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-xs font-bold text-gray-600"><?php echo e($item->kelurahan->nama_kelurahan ?? '-'); ?></p>
                                <p class="text-[9px] text-gray-400 font-bold uppercase mt-0.5"><?php echo e($item->kelurahan->kecamatan->nama_kecamatan ?? '-'); ?></p>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex justify-center">
                                    <?php
                                        $color = $item->kondisi == 'Baik' ? 'emerald' : ($item->kondisi == 'Rusak Ringan' ? 'amber' : 'red');
                                    ?>
                                    <span class="px-2 py-1 bg-<?php echo e($color); ?>-50 text-<?php echo e($color); ?>-600 rounded-lg text-[8px] font-black uppercase border border-<?php echo e($color); ?>-100">
                                        <?php echo e($item->kondisi); ?>

                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-xs font-bold text-gray-600"><?php echo e($item->user->name ?? 'System'); ?></p>
                            </td>
                            <td class="px-6 py-4 text-xs font-bold text-gray-400">
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
        // No additional scripts needed for basic print functionality
    </script>
</body>
</html>
<?php /**PATH C:\laragon1\laragon\www\geo-sinfra\resources\views/kabid/laporan.blade.php ENDPATH**/ ?>