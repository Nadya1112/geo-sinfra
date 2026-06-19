<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Data Saya | GEO-SINFRA</title>
    <link rel="icon" href="<?php echo e(asset('logo_geo-sinfra.png')); ?>" type="image/png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
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
    <script>
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    </style>
</head>
<body class="bg-slate-50 dark:bg-[#0f0e2c] flex h-screen overflow-hidden text-slate-800 font-sans dark:bg-navy-950 dark:text-white transition-colors duration-300">

    <?php echo $__env->make('surveyor.partials.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <main class="flex-1 flex flex-col h-screen overflow-hidden">
        
        <header class="bg-white dark:bg-[#1e1b4b]/80 backdrop-blur-xl border-b border-slate-100 dark:border-white/10 px-8 py-5 flex justify-between items-center z-40 shrink-0">
            <div class="flex items-center gap-4">
                <a href="<?php echo e(route('surveyor.dashboard')); ?>" class="w-10 h-10 flex items-center justify-center bg-white dark:bg-[#1e1b4b] text-slate-400 rounded-xl hover:bg-gold-50 hover:text-gold-600 transition-all border border-slate-200 dark:border-white/20 hover:border-gold-200">
                    <i class="fas fa-arrow-left text-sm"></i>
                </a>
                <div>
                    <p class="text-[10px] font-black text-gold-500 uppercase tracking-[0.2em] mb-1">Manajemen Laporan</p>
                    <h2 class="text-xl font-black text-navy-900 dark:text-white tracking-tight">Riwayat Survey Anda</h2>
                </div>
            </div>

            <div class="flex items-center gap-6">
                <div class="text-right hidden sm:block">
                    <p class="text-[11px] font-black text-navy-900 dark:text-white" id="mini-clock">00:00 WITA</p>
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-tighter"><?php echo e(now()->translatedFormat('l, d F Y')); ?></p>
                </div>
                <div class="h-8 w-[1px] bg-slate-100"></div>
                <div class="flex items-center gap-3">
                    <div class="text-right">
                        <p class="text-[11px] font-black text-navy-900 dark:text-white leading-none uppercase"><?php echo e(auth()->user()->name); ?></p>
                        <p class="text-[9px] font-bold text-emerald-500 uppercase mt-1">ONLINE</p>
                    </div>
                    <div class="w-10 h-10 bg-navy-900 rounded-xl flex items-center justify-center text-gold-500 border border-navy-800 overflow-hidden shadow-md">
                        <?php if(auth()->user()->profile_photo): ?>
                            <img src="<?php echo e(asset('storage/' . auth()->user()->profile_photo)); ?>" class="w-full h-full object-cover">
                        <?php else: ?>
                            <i class="fas fa-user-circle text-xl text-gold-500"></i>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </header>

        <div class="flex-1 overflow-y-auto custom-scrollbar p-8 pb-16">
            <div class="max-w-7xl mx-auto">

                
                <?php if(session('success')): ?>
                <div class="mb-6 px-6 py-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-2xl flex items-center gap-3 shadow-sm">
                    <i class="fas fa-check-circle"></i>
                    <p class="text-sm font-bold"><?php echo e(session('success')); ?></p>
                </div>
                <?php endif; ?>

                
                <?php if(session('error')): ?>
                <div class="mb-6 px-6 py-4 bg-red-50 border border-red-100 text-red-700 rounded-2xl flex items-center gap-3 shadow-sm">
                    <i class="fas fa-exclamation-circle"></i>
                    <p class="text-sm font-bold"><?php echo e(session('error')); ?></p>
                </div>
                <?php endif; ?>

                <div class="flex justify-between items-end mb-6">
                    <div>
                        <h3 class="text-lg font-black text-navy-900 dark:text-white">Daftar Data Lapangan</h3>
                        <p class="text-xs text-slate-400 font-medium mt-1">Seluruh laporan infrastruktur yang telah Anda kumpulkan.</p>
                    </div>
                    <form action="<?php echo e(route('surveyor.history')); ?>" method="GET" class="w-48">
                        <select name="show" onchange="this.form.submit()" class="w-full bg-white dark:bg-[#1e1b4b] border border-slate-200 dark:border-white/20 rounded-xl px-4 py-3 text-xs font-black text-navy-900 dark:text-white shadow-sm focus:outline-none focus:ring-4 focus:ring-gold-500/10 focus:border-gold-500 transition-all cursor-pointer">
                            <option value="10" <?php echo e(request('show') != 'all' ? 'selected' : ''); ?>>Tampilkan 10 Data</option>
                            <option value="all" <?php echo e(request('show') == 'all' ? 'selected' : ''); ?>>Tampilkan Semua</option>
                        </select>
                    </form>
                </div>

                <div class="bg-white dark:bg-[#1e1b4b] rounded-[2rem] border border-slate-100 dark:border-white/10 shadow-sm overflow-hidden mb-10">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gradient-to-r from-navy-900 to-navy-800 border-b border-navy-800 shadow-md">
                                    <th class="px-4 py-2 text-[10px] font-black text-gold-500 uppercase tracking-widest text-center w-12">NO</th>
                                    <th class="px-4 py-2 text-[10px] font-black text-gold-500 uppercase tracking-widest w-20 text-center">FOTO</th>
                                    <th class="px-4 py-2 text-[10px] font-black text-gold-500 uppercase tracking-widest">INFRASTRUKTUR</th>
                                    <th class="px-4 py-2 text-[10px] font-black text-gold-500 uppercase tracking-widest">WILAYAH</th>
                                    <th class="px-4 py-2 text-[10px] font-black text-gold-500 uppercase tracking-widest text-center">STATUS VALIDASI</th>
                                    <th class="px-4 py-2 text-[10px] font-black text-gold-500 uppercase tracking-widest text-center">STATUS KONDISI</th>
                                    <th class="px-4 py-2 text-[10px] font-black text-gold-500 uppercase tracking-widest text-center">AKSI</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                <?php $__empty_1 = true; $__currentLoopData = $riwayat; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr class="hover:bg-slate-50 dark:hover:bg-white/5 dark:bg-[#0f0e2c]/50 transition-colors">
                                    <td class="px-4 py-2 text-center">
                                        <span class="text-xs font-black text-slate-400"><?php echo e(request('show') == 'all' ? $index + 1 : ($riwayat->currentPage() - 1) * $riwayat->perPage() + $index + 1); ?></span>
                                    </td>

                                    
                                    <td class="px-4 py-2 text-center">
                                        <div class="w-10 h-10 rounded-xl overflow-hidden shadow-sm mx-auto bg-slate-100 flex items-center justify-center relative">
                                            <?php if($item->foto_terbaru): ?>
                                                <?php $cleanPath = str_replace('\\', '/', $item->foto_terbaru); ?>
                                                <img src="<?php echo e(asset('storage/' . (str_contains($cleanPath, 'infrastruktur/') ? $cleanPath : 'infrastruktur/' . $cleanPath))); ?>" class="w-full h-full object-cover">
                                            <?php else: ?>
                                                <i class="fas fa-image text-slate-300 text-sm"></i>
                                            <?php endif; ?>

                                            
                                            <?php if($item->status_verifikasi == 'Verified'): ?>
                                            <div class="absolute -top-1 -right-1 w-5 h-5 bg-emerald-500 rounded-full border-[2px] border-white flex items-center justify-center shadow-sm">
                                                <i class="fas fa-check text-[8px] text-white"></i>
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                    </td>

                                    
                                    <td class="px-4 py-2">
                                        <p class="text-xs font-black text-navy-900 dark:text-white uppercase tracking-tight mb-0.5"><?php echo e($item->nama_infrastruktur ?? $item->nama_objek); ?></p>
                                        <span class="inline-flex px-1.5 py-0.5 bg-navy-50 dark:bg-navy-900 text-navy-600 rounded-md text-[8px] font-black uppercase tracking-widest"><?php echo e(ucfirst($item->jenis)); ?></span>
                                    </td>

                                    
                                    <td class="px-4 py-2">
                                        <div class="flex items-center gap-2">
                                            <div class="w-6 h-6 rounded-md bg-gold-50 text-gold-500 flex items-center justify-center shrink-0">
                                                <i class="fas fa-map-marker-alt text-[10px]"></i>
                                            </div>
                                            <div>
                                                <p class="text-[10px] font-black text-navy-900 dark:text-white uppercase tracking-wider"><?php echo e($item->kelurahan ? $item->kelurahan->nama_kelurahan : '-'); ?></p>
                                                <p class="text-[8px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">
                                                    KEC. <?php echo e($item->kelurahan && $item->kelurahan->kecamatan ? $item->kelurahan->kecamatan->nama_kecamatan : '-'); ?>

                                                </p>
                                            </div>
                                        </div>
                                    </td>

                                    
                                    <td class="px-4 py-2 text-center">
                                        <?php if($item->status_validasi == 'Rejected'): ?>
                                            <div class="inline-flex flex-col items-center">
                                                <span class="inline-flex px-2 py-0.5 bg-red-100 text-red-600 border border-red-200 rounded-md text-[8px] font-black uppercase tracking-widest shadow-sm mb-0.5">Ditolak</span>
                                                <button onclick="alert('Alasan Penolakan: <?php echo e(addslashes($item->alasan_penolakan)); ?>')" class="text-[9px] font-bold text-red-500 hover:text-red-700 underline cursor-pointer">Lihat Alasan</button>
                                            </div>
                                        <?php elseif($item->status_validasi == 'Validated'): ?>
                                            <span class="inline-flex px-2 py-1 bg-emerald-100 text-emerald-600 border border-emerald-200 rounded-lg text-[9px] font-black uppercase tracking-widest shadow-sm">Di-ACC</span>
                                        <?php elseif($item->status_verifikasi == 'Verified'): ?>
                                            <span class="inline-flex px-2 py-1 bg-blue-100 text-blue-600 border border-blue-200 rounded-lg text-[9px] font-black uppercase tracking-widest shadow-sm">Verified</span>
                                        <?php else: ?>
                                            <span class="inline-flex px-2 py-1 bg-slate-100 text-slate-500 border border-slate-200 dark:border-white/20 rounded-lg text-[9px] font-black uppercase tracking-widest shadow-sm">Pending</span>
                                        <?php endif; ?>
                                    </td>

                                    
                                    <td class="px-4 py-2">
                                        <?php if($item->cnn || $item->analisis): ?>
                                            <div class="flex justify-center">
                                                <div class="inline-flex items-center bg-white dark:bg-[#1e1b4b] rounded-lg border border-slate-200 dark:border-white/20 shadow-sm overflow-hidden">
                                                    <?php if($item->analisis): ?>
                                                        <?php
                                                            $labelMap = [
                                                                'Baik'        => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-600', 'icon' => 'fa-check-circle'],
                                                                'Rusak Sedang'=> ['bg' => 'bg-orange-50',  'text' => 'text-orange-600',  'icon' => 'fa-hammer'],
                                                                'Rusak Berat' => ['bg' => 'bg-red-50',     'text' => 'text-red-600',     'icon' => 'fa-exclamation-triangle'],
                                                            ];
                                                            $style = $labelMap[$item->analisis->label_prioritas] ?? ['bg' => 'bg-slate-50 dark:bg-[#0f0e2c]', 'text' => 'text-slate-600 dark:text-slate-400', 'icon' => 'fa-info-circle'];
                                                        ?>
                                                        <div class="flex items-center gap-1.5 px-3 py-1.5 <?php echo e($style['bg']); ?>">
                                                            <i class="fas <?php echo e($style['icon']); ?> <?php echo e($style['text']); ?> text-[10px]"></i>
                                                            <span class="text-[10px] font-black <?php echo e($style['text']); ?> uppercase tracking-wider"><?php echo e($item->analisis->label_prioritas); ?></span>
                                                        </div>
                                                    <?php else: ?>
                                                        <div class="flex items-center gap-1 px-3 py-1.5 bg-slate-50 dark:bg-[#0f0e2c]">
                                                            <span class="text-[10px] font-black text-slate-500 uppercase tracking-wider">Menunggu Status</span>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        <?php else: ?>
                                            <p class="text-[9px] text-slate-400 font-bold uppercase text-center">-</p>
                                        <?php endif; ?>
                                    </td>

                                    
                                    <td class="px-4 py-2 text-center">
                                        <div class="flex items-center justify-center gap-1.5">
                                            <a href="<?php echo e(route('surveyor.infrastruktur.edit', $item->id_infrastruktur)); ?>" class="w-7 h-7 flex items-center justify-center bg-white dark:bg-[#1e1b4b] border border-slate-200 dark:border-white/20 text-slate-400 rounded-md hover:bg-gold-500 hover:text-white hover:border-gold-500 hover:shadow-sm transition-all cursor-pointer" title="Edit Data">
                                                <i class="fas fa-pen text-[10px]"></i>
                                            </a>
                                            <a href="<?php echo e(route('surveyor.infrastruktur.show', $item->id_infrastruktur)); ?>" class="w-7 h-7 flex items-center justify-center bg-navy-900 text-gold-500 rounded-md hover:bg-navy-950 hover:text-white transition-all shadow-sm cursor-pointer" title="Lihat Detail">
                                                <i class="fas fa-eye text-[10px]"></i>
                                            </a>

                                            
                                            <?php if($item->status_verifikasi === 'Pending'): ?>
                                            <button
                                                onclick="konfirmasiHapus(<?php echo e($item->id_infrastruktur); ?>, '<?php echo e(addslashes($item->nama_objek ?? $item->nama_infrastruktur)); ?>')"
                                                class="w-7 h-7 flex items-center justify-center bg-white dark:bg-[#1e1b4b] border border-red-200 text-red-400 rounded-md hover:bg-red-500 hover:text-white hover:border-red-500 hover:shadow-sm transition-all cursor-pointer"
                                                title="Hapus Data (hanya Pending)">
                                                <i class="fas fa-trash text-[10px]"></i>
                                            </button>
                                            <form id="form-hapus-<?php echo e($item->id_infrastruktur); ?>"
                                                action="<?php echo e(route('surveyor.infrastruktur.destroy', $item->id_infrastruktur)); ?>"
                                                method="POST" class="hidden">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                            </form>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="7" class="px-8 py-24 text-center">
                                        <div class="flex flex-col items-center gap-4">
                                            <div class="w-24 h-24 bg-slate-50 dark:bg-[#0f0e2c] rounded-full flex items-center justify-center text-slate-300">
                                                <i class="fas fa-folder-open text-5xl"></i>
                                            </div>
                                            <div>
                                                <p class="text-sm text-navy-900 dark:text-white font-black uppercase tracking-wider mb-1">Riwayat Kosong</p>
                                                <p class="text-xs text-slate-400 font-bold uppercase tracking-widest">Anda belum memiliki riwayat survey lapangan.</p>
                                            </div>
                                            <a href="<?php echo e(route('surveyor.input')); ?>" class="mt-4 px-8 py-3 bg-gold-500 text-white rounded-xl text-xs font-black uppercase tracking-widest shadow-md shadow-gold-500/20 hover:bg-gold-600 transition-all active:scale-95">
                                                Mulai Survey Pertama
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php if(request('show') != 'all' && isset($riwayat) && $riwayat instanceof \Illuminate\Pagination\LengthAwarePaginator): ?>
                        <div class="px-8 py-5 border-t border-slate-100 dark:border-white/10 bg-white dark:bg-[#1e1b4b]">
                            <?php echo e($riwayat->links()); ?>

                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>

    <script>
        function updateClock() {
            const now = new Date();
            const options = { timeZone: 'Asia/Makassar', hour: '2-digit', minute: '2-digit', hour12: false };
            const timeString = new Intl.DateTimeFormat('id-ID', options).format(now);
            const el = document.getElementById('mini-clock');
            if (el) el.textContent = timeString.replace('.', ':') + ' WITA';
        }
        setInterval(updateClock, 1000); updateClock();

        function konfirmasiHapus(id, nama) {
            if (confirm(`Yakin ingin menghapus data "${nama}"?\n\nData yang dihapus tidak dapat dikembalikan.`)) {
                document.getElementById('form-hapus-' + id).submit();
            }
        }
    </script>
</body>
</html>
<?php /**PATH C:\laragon1\laragon\www\geo-sinfra\resources\views/surveyor/history.blade.php ENDPATH**/ ?>