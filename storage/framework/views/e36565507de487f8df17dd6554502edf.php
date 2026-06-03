<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Usulan | GEO-SINFRA</title>
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
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .glass-card { background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.2); }
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    </style>
</head>
<body class="bg-slate-50 flex h-screen overflow-hidden text-slate-800 text-left">

    <?php echo $__env->make('kabid.partials.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <main class="flex-1 flex flex-col h-screen overflow-y-auto custom-scrollbar">
        <!-- HEADER -->
        <header class="bg-white border-b border-slate-100 px-8 py-5 flex justify-between items-center z-10 sticky top-0">
            <div class="flex items-center gap-4">
                <a href="<?php echo e(route('kabid.dashboard')); ?>" class="w-10 h-10 flex items-center justify-center bg-slate-50 text-slate-400 rounded-xl hover:bg-gold-50 hover:text-gold-500 transition-all border border-slate-100">
                    <i class="fas fa-arrow-left text-sm"></i>
                </a>
                <div>
                    <p class="text-[10px] font-extrabold text-gold-500 uppercase tracking-[0.2em] mb-1">Manajemen Validasi</p>
                    <h2 class="text-xl font-black text-navy-900">Verifikasi Usulan Masuk</h2>
                </div>
            </div>
            
            <div class="flex items-center gap-6">
                <div class="text-right hidden sm:block">
                    <p class="text-[11px] font-black text-navy-900" id="mini-clock">00:00 WITA</p>
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-tighter"><?php echo e(now()->translatedFormat('l, d F Y')); ?></p>
                </div>
                <div class="h-8 w-[1px] bg-slate-100"></div>
                <div class="flex items-center gap-3">
                    <div class="text-right">
                        <p class="text-[11px] font-black text-navy-900 leading-none uppercase"><?php echo e(auth()->user()->name); ?></p>
                        <p class="text-[9px] font-bold text-[#059669] uppercase mt-1 leading-none">ONLINE</p>
                    </div>
                    <div class="w-10 h-10 bg-navy-50 rounded-xl flex items-center justify-center text-navy-900 border border-navy-100 overflow-hidden shadow-sm">
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


            <!-- TABLE SECTION -->
            <div class="bg-white rounded-[3rem] shadow-sm border border-slate-100 overflow-hidden mb-10">
                <div class="px-8 py-6 border-b border-slate-50 flex justify-between items-center bg-slate-50/30">
                    <div>
                        <h3 class="text-sm font-black text-navy-900 uppercase tracking-widest">Validasi Data Usulan</h3>
                        <p class="text-[9px] text-slate-400 font-bold uppercase mt-1">Daftar riwayat dan usulan baru dari surveyor</p>
                    </div>
                    <div>
                        <form action="<?php echo e(route('kabid.verifikasi')); ?>" method="GET" class="flex items-center gap-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Tampilan:</label>
                            <select name="show" onchange="this.form.submit()" class="text-xs font-bold text-navy-900 bg-white border border-slate-200 rounded-xl px-3 py-1.5 focus:outline-none focus:border-gold-500 transition-colors">
                                <option value="10" <?php echo e(request('show') != 'all' ? 'selected' : ''); ?>>Per 10 Data</option>
                                <option value="all" <?php echo e(request('show') == 'all' ? 'selected' : ''); ?>>Semua Data</option>
                            </select>
                        </form>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="text-[10px] font-black text-slate-400 uppercase tracking-widest bg-slate-50/50">
                                <th class="px-6 py-4 w-16 border-b border-slate-100">No</th>
                                <th class="px-6 py-4 border-b border-slate-100">Infrastruktur</th>
                                <th class="px-6 py-4 border-b border-slate-100">Wilayah</th>
                                <th class="px-6 py-4 border-b border-slate-100">Analisis AI</th>
                                <th class="px-6 py-4 border-b border-slate-100">Status Admin</th>
                                <th class="px-6 py-4 text-center border-b border-slate-100">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            <?php $__empty_1 = true; $__currentLoopData = $allUsulan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="hover:bg-slate-50/50 transition-colors group">
                                <td class="px-6 py-5 whitespace-nowrap text-xs font-black text-slate-300">
                                    <?php echo e(request('show') == 'all' ? sprintf('%02d', $index + 1) : sprintf('%02d', ($allUsulan->currentPage() - 1) * $allUsulan->perPage() + $index + 1)); ?>

                                </td>
                                <td class="px-6 py-5 min-w-[280px]">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 rounded-2xl bg-slate-100 overflow-hidden shadow-inner border border-white flex-shrink-0 flex items-center justify-center relative">
                                            <?php if($item->foto_terbaru): ?>
                                                <img src="<?php echo e(asset('storage/' . $item->foto_terbaru)); ?>" class="w-full h-full object-cover">
                                            <?php else: ?>
                                                <i class="fas fa-image text-slate-300"></i>
                                            <?php endif; ?>
                                        </div>
                                        <div>
                                            <h4 class="text-sm font-black text-navy-900 leading-tight mb-0.5"><?php echo e($item->nama_objek); ?></h4>
                                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest"><?php echo e($item->jenis); ?></p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-5 min-w-[150px]">
                                    <p class="text-xs font-bold text-navy-900 mb-0.5"><?php echo e($item->kelurahan->nama_kelurahan ?? '-'); ?></p>
                                    <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest"><?php echo e($item->kelurahan->kecamatan->nama_kecamatan ?? '-'); ?></p>
                                </td>
                                <td class="px-6 py-5 min-w-[150px]">
                                    <div class="flex flex-col gap-2 items-start">
                                        <?php
                                            $aiLabel = $item->analisis->label_prioritas ?? '';
                                            $aiLabelLower = strtolower($aiLabel);
                                            $aiScore = $item->analisis->skor_dt ?? null;
                                            
                                            // Fallback color if no score is matched
                                            $aiClass = 'bg-slate-50 text-slate-600 border-slate-200';
                                            
                                            if (str_contains($aiLabelLower, 'berat')) {
                                                $aiClass = 'bg-[#be123c]/10 text-[#be123c] border-[#be123c]/30';
                                            } elseif (str_contains($aiLabelLower, 'sedang') || str_contains($aiLabelLower, 'ringan')) {
                                                $aiClass = 'bg-[#d97706]/10 text-[#d97706] border-[#d97706]/30';
                                            } elseif (str_contains($aiLabelLower, 'baik')) {
                                                $aiClass = 'bg-[#059669]/10 text-[#059669] border-[#059669]/30';
                                            }
                                        ?>
                                        <span class="px-2.5 py-1 rounded-md border text-[9px] font-black uppercase tracking-widest <?php echo e($aiClass); ?>">
                                            <?php echo e($aiLabel ?: 'Belum Dianalisis'); ?>

                                        </span>
                                        <?php if($aiScore !== null): ?>
                                            <span class="text-[8px] font-bold text-slate-400 uppercase flex items-center gap-1.5 tracking-widest mt-1">
                                                <i class="fas fa-robot text-gold-500"></i> AI Score: <?php echo e(number_format($aiScore, 1)); ?>%
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td class="px-6 py-5 min-w-[140px]">
                                    <?php
                                        $statusClass = match($item->status_verifikasi) {
                                            'Verified' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                            'Rejected' => 'bg-rose-50 text-rose-600 border-rose-100',
                                            default => 'bg-slate-50 text-slate-600 border-slate-200'
                                        };
                                        $statusIcon = match($item->status_verifikasi) {
                                            'Verified' => 'fa-check-double',
                                            'Rejected' => 'fa-times-circle',
                                            default => 'fa-clock'
                                        };
                                    ?>
                                    <span class="px-3 py-1.5 rounded-xl text-[9px] font-black uppercase tracking-widest border flex items-center gap-2 w-fit <?php echo e($statusClass); ?>">
                                        <i class="fas <?php echo e($statusIcon); ?> text-[10px]"></i>
                                        <?php echo e($item->status_verifikasi == 'Verified' ? 'Diterima' : ($item->status_verifikasi == 'Rejected' ? 'Ditolak' : 'Menunggu')); ?>

                                    </span>
                                </td>
                                <td class="px-6 py-5 min-w-[260px]">
                                    <div class="flex items-center justify-center gap-2">
                                        
                                        <a href="<?php echo e(route('kabid.infrastruktur.show', $item->id_infrastruktur)); ?>" class="flex items-center justify-center gap-2 px-3 py-2.5 bg-navy-50 text-navy-900 rounded-xl hover:bg-gold-500 hover:text-white transition-all border border-navy-100 shadow-sm group" title="Tinjau Usulan">
                                            <i class="fas fa-eye text-[10px] group-hover:scale-110 transition-transform"></i>
                                            <span class="text-[9px] font-black uppercase tracking-widest hidden 2xl:block">Tinjau</span>
                                        </a>

                                        
                                        <?php if($item->status_verifikasi == 'Pending'): ?>
                                            <form action="<?php echo e(route('kabid.verifikasi.proses', $item->id_infrastruktur)); ?>" method="POST" class="flex-1">
                                                <?php echo csrf_field(); ?>
                                                <input type="hidden" name="status" value="Verified">
                                                <button type="submit" class="w-full flex items-center justify-center gap-2 px-3 py-2.5 bg-[#059669] text-white rounded-xl hover:bg-[#047857] transition-all shadow-lg shadow-[#059669]/20 group border border-[#059669]" title="Terima Usulan">
                                                    <i class="fas fa-check text-[10px] group-hover:scale-110 transition-transform"></i>
                                                    <span class="text-[9px] font-black uppercase tracking-widest">ACC</span>
                                                </button>
                                            </form>
                                            
                                            
                                            <form action="<?php echo e(route('kabid.verifikasi.proses', $item->id_infrastruktur)); ?>" method="POST" class="flex-1">
                                                <?php echo csrf_field(); ?>
                                                <input type="hidden" name="status" value="Rejected">
                                                <button type="submit" class="w-full flex items-center justify-center gap-2 px-3 py-2.5 bg-rose-50 text-rose-600 rounded-xl hover:bg-rose-500 hover:text-white transition-all border border-rose-200 shadow-sm group" title="Tolak Usulan">
                                                    <i class="fas fa-times text-[10px] group-hover:scale-110 transition-transform"></i>
                                                    <span class="text-[9px] font-black uppercase tracking-widest">Tolak</span>
                                                </button>
                                            </form>
                                        <?php else: ?>
                                            <button disabled class="flex-1 flex items-center justify-center gap-2 px-3 py-2.5 bg-slate-50 text-slate-300 rounded-xl border border-slate-100 cursor-not-allowed">
                                                <i class="fas fa-check text-[10px]"></i>
                                                <span class="text-[9px] font-black uppercase tracking-widest">ACC</span>
                                            </button>
                                            <button disabled class="flex-1 flex items-center justify-center gap-2 px-3 py-2.5 bg-slate-50 text-slate-300 rounded-xl border border-slate-100 cursor-not-allowed">
                                                <i class="fas fa-times text-[10px]"></i>
                                                <span class="text-[9px] font-black uppercase tracking-widest">Tolak</span>
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="6" class="px-8 py-20 text-center">
                                    <div class="flex flex-col items-center gap-3">
                                        <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center text-slate-300">
                                            <i class="fas fa-clipboard-check text-2xl"></i>
                                        </div>
                                        <p class="text-sm font-bold text-slate-400 uppercase tracking-widest">Tidak ada usulan untuk divalidasi</p>
                                    </div>
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <?php if(request('show') != 'all'): ?>
                    <div class="px-8 py-4 border-t border-slate-50 bg-slate-50/10">
                        <?php echo e($allUsulan->links()); ?>

                    </div>
                <?php endif; ?>
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
<?php /**PATH C:\laragon1\laragon\www\geo-sinfra\resources\views/kabid/verifikasi.blade.php ENDPATH**/ ?>