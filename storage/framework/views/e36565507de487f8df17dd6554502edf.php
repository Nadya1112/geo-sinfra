<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Usulan | GEO-SINFRA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .glass-card { background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.2); }
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
    </style>
</head>
<body class="bg-gray-50 flex h-screen overflow-hidden text-gray-800 text-left">

    <?php echo $__env->make('kabid.partials.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <main class="flex-1 flex flex-col h-screen overflow-y-auto custom-scrollbar">
        <!-- HEADER -->
        <header class="bg-white border-b border-gray-100 px-8 py-5 flex justify-between items-center z-10 sticky top-0">
            <div>
                <p class="text-[10px] font-extrabold text-indigo-600 uppercase tracking-[0.2em] mb-1">Manajemen Validasi</p>
                <h2 class="text-xl font-black text-[#1e1b4b]">Verifikasi Usulan Masuk</h2>
            </div>
            
            <div class="flex items-center gap-6">
                <div class="text-right hidden sm:block">
                    <p class="text-[11px] font-black text-[#1e1b4b]"><?php echo e(now()->translatedFormat('l, d F Y')); ?></p>
                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">DATA REAL-TIME</p>
                </div>
                <div class="h-8 w-[1px] bg-gray-100"></div>
                <div class="flex items-center gap-3">
                    <div class="text-right">
                        <p class="text-[11px] font-black text-[#1e1b4b] leading-none uppercase"><?php echo e(auth()->user()->name); ?></p>
                        <p class="text-[9px] font-bold text-indigo-500 uppercase mt-1 leading-none">KABID</p>
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

        <!-- MAIN CONTENT -->
        <div class="p-8 space-y-8">
            
            <?php if(session('success')): ?>
                <div class="p-4 bg-emerald-50 border border-emerald-100 rounded-2xl flex items-center gap-3 text-emerald-600 shadow-sm animate-pulse">
                    <i class="fas fa-check-circle"></i>
                    <p class="text-sm font-bold"><?php echo e(session('success')); ?></p>
                </div>
            <?php endif; ?>

            <!-- STATS SUMMARY CARDS -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Menunggu -->
                <div class="bg-white p-6 rounded-[2.5rem] border border-gray-100 shadow-sm relative overflow-hidden group">
                    <div class="absolute -right-4 -top-4 w-20 h-20 bg-amber-500/5 rounded-full blur-2xl group-hover:bg-amber-500/10 transition-all"></div>
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-10 h-10 bg-amber-50 text-amber-500 rounded-xl flex items-center justify-center border border-amber-100">
                            <i class="fas fa-clock text-xs"></i>
                        </div>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Menunggu</p>
                    </div>
                    <div class="flex items-end gap-2">
                        <h3 class="text-3xl font-black text-[#1e1b4b]"><?php echo e($counts['pending']); ?></h3>
                        <span class="text-[9px] font-bold text-amber-500 mb-1.5 uppercase">Usulan</span>
                    </div>
                </div>

                <!-- Diterima -->
                <div class="bg-white p-6 rounded-[2.5rem] border border-gray-100 shadow-sm relative overflow-hidden group">
                    <div class="absolute -right-4 -top-4 w-20 h-20 bg-emerald-500/5 rounded-full blur-2xl group-hover:bg-emerald-500/10 transition-all"></div>
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-10 h-10 bg-emerald-50 text-emerald-500 rounded-xl flex items-center justify-center border border-emerald-100">
                            <i class="fas fa-check-double text-xs"></i>
                        </div>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Diterima</p>
                    </div>
                    <div class="flex items-end gap-2">
                        <h3 class="text-3xl font-black text-[#1e1b4b]"><?php echo e($counts['verified']); ?></h3>
                        <span class="text-[9px] font-bold text-emerald-500 mb-1.5 uppercase">Verified</span>
                    </div>
                </div>

                <!-- Ditolak -->
                <div class="bg-white p-6 rounded-[2.5rem] border border-gray-100 shadow-sm relative overflow-hidden group">
                    <div class="absolute -right-4 -top-4 w-20 h-20 bg-rose-500/5 rounded-full blur-2xl group-hover:bg-rose-500/10 transition-all"></div>
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-10 h-10 bg-rose-50 text-rose-500 rounded-xl flex items-center justify-center border border-rose-100">
                            <i class="fas fa-times-circle text-xs"></i>
                        </div>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Ditolak</p>
                    </div>
                    <div class="flex items-end gap-2">
                        <h3 class="text-3xl font-black text-[#1e1b4b]"><?php echo e($counts['rejected']); ?></h3>
                        <span class="text-[9px] font-bold text-rose-500 mb-1.5 uppercase">Rejected</span>
                    </div>
                </div>
            </div>

            <!-- TABLE SECTION -->
            <div class="bg-white rounded-[3rem] shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-8 py-6 border-b border-gray-50 flex justify-between items-center bg-gray-50/30">
                    <div>
                        <h3 class="text-sm font-black text-[#1e1b4b] uppercase tracking-widest">Validasi Data Usulan</h3>
                        <p class="text-[9px] text-gray-400 font-bold uppercase mt-1">Daftar riwayat dan usulan baru dari surveyor</p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="text-[10px] font-black text-gray-400 uppercase tracking-widest">
                                <th class="px-8 py-5 w-16">No</th>
                                <th class="px-6 py-5">Infrastruktur</th>
                                <th class="px-6 py-5">Surveyor</th>
                                <th class="px-6 py-5">Status</th>
                                <th class="px-8 py-5 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            <?php $__empty_1 = true; $__currentLoopData = $allUsulan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="hover:bg-gray-50/50 transition-colors group">
                                <td class="px-8 py-6 text-xs font-black text-gray-300">
                                    <?php echo e(sprintf('%02d', $index + 1)); ?>

                                </td>
                                <td class="px-6 py-6">
                                    <div class="flex items-center gap-4">
                                        <div class="w-14 h-14 rounded-2xl bg-gray-100 overflow-hidden shadow-sm border border-white flex-shrink-0">
                                            <img src="<?php echo e(asset('storage/' . $item->foto_terbaru)); ?>" class="w-full h-full object-cover">
                                        </div>
                                        <div>
                                            <h4 class="text-[13px] font-black text-[#1e1b4b] leading-tight"><?php echo e($item->nama_infrastruktur); ?></h4>
                                            <p class="text-[9px] font-bold text-indigo-500 uppercase tracking-tighter mt-0.5"><?php echo e($item->jenis_infrastruktur); ?></p>
                                            <p class="text-[9px] text-gray-400 font-medium mt-0.5"><?php echo e($item->kelurahan->nama_kelurahan ?? '-'); ?></p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-6">
                                    <div class="flex flex-col">
                                        <span class="text-xs font-black text-[#1e1b4b] uppercase"><?php echo e($item->user->name ?? 'Surveyor'); ?></span>
                                        <span class="text-[9px] font-bold text-gray-400 uppercase tracking-tighter"><?php echo e($item->created_at->translatedFormat('d M Y')); ?></span>
                                    </div>
                                </td>
                                <td class="px-6 py-6">
                                    <?php
                                        $statusClass = match($item->status_verifikasi) {
                                            'Verified' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                            'Rejected' => 'bg-rose-50 text-rose-600 border-rose-100',
                                            default => 'bg-amber-50 text-amber-600 border-amber-100'
                                        };
                                        $statusIcon = match($item->status_verifikasi) {
                                            'Verified' => 'fa-check-double',
                                            'Rejected' => 'fa-times-circle',
                                            default => 'fa-clock'
                                        };
                                    ?>
                                    <span class="px-3 py-1.5 rounded-full text-[9px] font-black uppercase tracking-widest border flex items-center gap-2 w-fit <?php echo e($statusClass); ?>">
                                        <i class="fas <?php echo e($statusIcon); ?> text-[10px]"></i>
                                        <?php echo e($item->status_verifikasi == 'Verified' ? 'Diterima' : ($item->status_verifikasi == 'Rejected' ? 'Ditolak' : 'Menunggu')); ?>

                                    </span>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex items-center justify-center gap-2 flex-wrap">
                                        
                                        <a href="<?php echo e(route('surveyor.infrastruktur.show', $item->id_infrastruktur)); ?>" target="_blank" title="Lihat Detail" class="w-9 h-9 flex items-center justify-center bg-indigo-50 text-indigo-600 rounded-xl hover:bg-indigo-600 hover:text-white transition-all border border-indigo-100 shadow-sm">
                                            <i class="fas fa-eye text-xs"></i>
                                        </a>

                                        
                                        <?php if($item->status_verifikasi == 'Pending'): ?>
                                            <form action="<?php echo e(route('kabid.verifikasi.proses', $item->id_infrastruktur)); ?>" method="POST" class="inline">
                                                <?php echo csrf_field(); ?>
                                                <input type="hidden" name="status" value="Verified">
                                                <button type="submit" class="flex items-center gap-1.5 px-3 py-2 bg-emerald-500 text-white rounded-xl hover:bg-emerald-600 transition-all shadow-lg shadow-emerald-500/20">
                                                    <i class="fas fa-check text-[10px]"></i>
                                                    <span class="text-[9px] font-black uppercase tracking-widest">Diterima</span>
                                                </button>
                                            </form>
                                        <?php else: ?>
                                            <button disabled class="flex items-center gap-1.5 px-3 py-2 bg-gray-50 text-gray-300 rounded-xl border border-gray-100 cursor-not-allowed">
                                                <i class="fas fa-check text-[10px]"></i>
                                                <span class="text-[9px] font-black uppercase tracking-widest">Diterima</span>
                                            </button>
                                        <?php endif; ?>

                                        
                                        <?php if($item->status_verifikasi == 'Pending'): ?>
                                            <form action="<?php echo e(route('kabid.verifikasi.proses', $item->id_infrastruktur)); ?>" method="POST" class="inline">
                                                <?php echo csrf_field(); ?>
                                                <input type="hidden" name="status" value="Rejected">
                                                <button type="submit" class="flex items-center gap-1.5 px-3 py-2 bg-white border border-red-200 text-red-400 rounded-xl hover:bg-red-50 hover:text-red-600 hover:border-red-300 transition-all">
                                                    <i class="fas fa-times text-[10px]"></i>
                                                    <span class="text-[9px] font-black uppercase tracking-widest">Ditolak</span>
                                                </button>
                                            </form>
                                        <?php else: ?>
                                            <button disabled class="flex items-center gap-1.5 px-3 py-2 bg-gray-50 text-gray-300 rounded-xl border border-gray-100 cursor-not-allowed">
                                                <i class="fas fa-times text-[10px]"></i>
                                                <span class="text-[9px] font-black uppercase tracking-widest">Ditolak</span>
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="5" class="px-8 py-20 text-center">
                                    <div class="flex flex-col items-center gap-3">
                                        <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center text-gray-300">
                                            <i class="fas fa-clipboard-check text-2xl"></i>
                                        </div>
                                        <p class="text-sm font-bold text-gray-400 uppercase tracking-widest">Tidak ada usulan untuk divalidasi</p>
                                    </div>
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

         </main>

</body>
</html>
<?php /**PATH C:\laragon1\laragon\www\geo-sinfra\resources\views/kabid/verifikasi.blade.php ENDPATH**/ ?>