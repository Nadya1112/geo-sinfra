<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Warga | Admin SINFRA</title>
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
                        navy: { 50:'#f4f4fa', 100:'#e9e9f3', 200:'#c7c8e3', 500:'#6366f1', 800:'#1e1b4b', 900:'#0f0e2c', 950:'#070617' },
                        gold: { 50:'#fdfbf7', 100:'#fbf7ed', 500:'#c5a059', 600:'#b38f4a', 700:'#9d7c3d' }
                    }
                }
            }
        }
    </script>

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; height: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    </style>
<style>
    @media (min-width: 768px) { html { font-size: 14px; } }
    @media (max-width: 767px) { html { font-size: 12px; } }
</style>
</head>
<body class="bg-navy-50 dark:bg-navy-950 text-slate-800 dark:text-slate-200 antialiased flex overflow-hidden h-screen transition-colors duration-300">

    <?php echo $__env->make('admin.partials.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <main class="flex-1 flex flex-col h-screen overflow-hidden text-left font-sans relative">
        <header class="bg-white/85 dark:bg-navy-950/80 backdrop-blur-xl border-b border-slate-100 dark:border-white/5 px-4 pl-16 md:px-8 py-4 flex justify-between items-center z-40 text-left transition-colors duration-300">
            <div class="flex items-center gap-4 text-left ml-10 md:ml-0">
                <a href="<?php echo e(route('admin.dashboard')); ?>"
                   class="hidden md:flex w-10 h-10 bg-white border border-slate-100 rounded-xl flex items-center justify-center text-slate-400 hover:text-gold-500 hover:border-gold-500/20 hover:shadow-lg hover:shadow-gold-500/5 transition-all group"
                   title="Kembali ke Dashboard Utama">
                    <i class="fas fa-arrow-left text-xs group-hover:-translate-x-1 transition-transform"></i>
                </a>
                <div class="text-left">
                    <p class="text-xs font-black text-gold-500 uppercase tracking-wider mb-1">Administrator Portal</p>
                    <h2 class="text-xl font-black text-navy-900 dark:text-white leading-none">Laporan Warga</h2>
                </div>
            </div>

            <div class="flex items-center gap-3 md:gap-6">
                <div class="text-right">
                    <p class="text-xs font-black text-navy-900 dark:text-white" id="mini-clock">00:00 WITA</p>
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-tighter"><?php echo e(now()->translatedFormat('d M Y')); ?></p>
                </div>
                <div class="h-8 w-[1px] bg-slate-100"></div>
                <div class="flex items-center gap-3">
                    <a href="<?php echo e(route('admin.profile')); ?>" class="text-right group hidden md:block">
                        <p class="text-sm font-black text-navy-900 dark:text-white leading-none uppercase group-hover:text-gold-500 transition-all max-w-[100px] sm:max-w-[150px] md:max-w-[300px] truncate"><?php echo e(auth()->user()->name); ?></p>
                        <p class="text-[10px] md:text-xs font-bold text-emerald-500 uppercase mt-0.5">Online</p>
                    </a>
                    <a href="<?php echo e(route('admin.profile')); ?>" class="w-10 h-10 bg-navy-900 rounded-xl flex items-center justify-center text-gold-500 border border-white/10 overflow-hidden hover:shadow-lg hover:shadow-navy-950/20 transition-all shadow-md">
                        <?php if(auth()->user()->profile_photo): ?>
                            <img src="<?php echo e(asset('storage/' . auth()->user()->profile_photo)); ?>" class="w-full h-full object-cover">
                        <?php else: ?>
                            <i class="fas fa-user-circle text-xl"></i>
                        <?php endif; ?>
                    </a>
                </div>
            </div>
        </header>

        <!-- Content -->
        <div class="flex-1 overflow-y-auto custom-scrollbar p-6 md:p-8 bg-slate-50/50">
            <div class="max-w-7xl mx-auto space-y-6">

                <?php if(session('success')): ?>
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-2xl mb-6 flex items-center gap-4 shadow-sm animate-fade-in">
                    <div class="w-10 h-10 bg-emerald-100 rounded-full flex items-center justify-center shrink-0">
                        <i class="fas fa-check text-emerald-600"></i>
                    </div>
                    <div class="flex-1">
                        <p class="font-bold text-sm"><?php echo e(session('success')); ?></p>
                    </div>
                </div>
                <?php endif; ?>

                
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-2">
                    <div>
                        <h4 class="font-extrabold text-lg text-navy-900">Data Laporan Warga</h4>
                        <p class="text-xs text-slate-400 font-semibold mt-0.5">Pantau, proses, dan kelola laporan kerusakan dari masyarakat</p>
                    </div>

                    <div class="flex flex-wrap items-center gap-3">
                        
                        <a href="<?php echo e(route('admin.laporan-warga.excel')); ?>" target="_blank"
                            class="px-4 py-2 bg-emerald-50 text-emerald-600 hover:bg-emerald-500 hover:text-white border border-emerald-100 hover:border-emerald-500 rounded-xl text-xs font-black tracking-widest uppercase transition-all shadow-sm flex items-center gap-2">
                            <i class="fas fa-file-excel"></i> Ekspor Excel
                        </a>
                    </div>
                </div>

                <!-- Filters & Search -->
                <div class="bg-white p-5 rounded-[2rem] shadow-sm border border-slate-100 flex flex-col md:flex-row gap-4 items-center justify-between mb-4">
                    <form method="GET" action="<?php echo e(route('admin.laporan-warga')); ?>" class="flex flex-col md:flex-row gap-3 w-full">
                        <div class="flex-1 relative">
                            <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                            <input type="text" name="search" value="<?php echo e($search ?? ''); ?>" placeholder="Cari nama pelapor, deskripsi, atau no HP..." 
                                   class="w-full pl-11 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-gold-500/20 focus:border-gold-500 transition-all font-medium">
                        </div>
                        <div class="w-full md:w-48 relative">
                            <select name="status" onchange="this.form.submit()" class="w-full pl-4 pr-10 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-navy-900 focus:ring-2 focus:ring-gold-500/20 focus:border-gold-500 appearance-none">
                                <option value="all" <?php echo e(($status ?? 'all') == 'all' ? 'selected' : ''); ?>>Semua Status</option>
                                <option value="Menunggu" <?php echo e(($status ?? '') == 'Menunggu' ? 'selected' : ''); ?>>Menunggu</option>
                                <option value="Ditinjau" <?php echo e(($status ?? '') == 'Ditinjau' ? 'selected' : ''); ?>>Ditinjau</option>
                                <option value="Diproses" <?php echo e(($status ?? '') == 'Diproses' ? 'selected' : ''); ?>>Diproses</option>
                                <option value="Selesai" <?php echo e(($status ?? '') == 'Selesai' ? 'selected' : ''); ?>>Selesai</option>
                                <option value="Ditolak" <?php echo e(($status ?? '') == 'Ditolak' ? 'selected' : ''); ?>>Ditolak</option>
                            </select>
                            <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                        </div>
                        <?php if($search || ($status && $status !== 'all')): ?>
                        <a href="<?php echo e(route('admin.laporan-warga')); ?>" class="px-5 py-2.5 bg-red-50 text-red-600 font-bold text-xs uppercase tracking-widest rounded-xl hover:bg-red-100 transition-all text-center flex items-center justify-center shrink-0">
                            Reset
                        </a>
                        <?php endif; ?>
                        <button type="submit" class="px-6 py-2.5 bg-navy-900 text-white font-bold text-xs uppercase tracking-widest rounded-xl hover:bg-navy-800 transition-all shadow-md flex items-center justify-center shrink-0">
                            Cari
                        </button>
                    </form>
                </div>

                <!-- Table Container -->
                <div class="bg-white rounded-[2rem] shadow-xl shadow-slate-200/40 border border-slate-100 overflow-hidden">
                    <div class="overflow-x-auto custom-scrollbar">
                        <table class="w-full text-left text-sm whitespace-nowrap md:whitespace-normal">
                            <thead class="bg-gradient-to-r from-navy-900 to-navy-800 border-b border-navy-800 shadow-md">
                                <tr>
                                    <th class="px-4 py-3 font-extrabold uppercase tracking-widest text-xs text-gold-500">Waktu Lapor</th>
                                    <th class="px-4 py-3 font-extrabold uppercase tracking-widest text-xs text-gold-500">Pelapor</th>
                                    <th class="px-4 py-3 font-extrabold uppercase tracking-widest text-xs text-gold-500">Laporan Kerusakan</th>
                                    <th class="px-4 py-3 font-extrabold uppercase tracking-widest text-xs text-gold-500 text-center">Status</th>
                                    <th class="px-4 py-3 font-extrabold uppercase tracking-widest text-xs text-gold-500 text-center">Penugasan</th>
                                    <th class="px-4 py-3 font-extrabold uppercase tracking-widest text-xs text-gold-500 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <?php $__empty_1 = true; $__currentLoopData = $laporanWarga; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $laporan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr class="hover:bg-slate-50/80 transition-colors group">
                                    <td class="px-4 py-3">
                                        <div class="flex items-center gap-2">
                                            <i class="far fa-clock text-slate-400"></i>
                                            <div>
                                                <p class="font-bold text-navy-900"><?php echo e(\Carbon\Carbon::parse($laporan->created_at)->format('d M Y')); ?></p>
                                                <p class="text-xs text-slate-500 font-medium"><?php echo e(\Carbon\Carbon::parse($laporan->created_at)->format('H:i')); ?></p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <p class="font-bold text-navy-900"><?php echo e($laporan->nama_pelapor); ?></p>
                                        <p class="text-xs font-semibold text-slate-500 mt-0.5"><i class="fas fa-phone-alt text-xs text-slate-400 mr-1"></i> <?php echo e($laporan->no_hp); ?></p>
                                    </td>
                                    <td class="px-4 py-3 min-w-[250px]">
                                        <p class="text-sm font-medium text-slate-700 line-clamp-2 leading-relaxed mb-2"><?php echo e($laporan->deskripsi); ?></p>
                                        
                                        <?php if($laporan->label_ai): ?>
                                            <?php
                                                $aiColor = 'bg-[#0f0e2c] text-white border-gold-500/50 shadow-gold-500/20';
                                                $aiIcon = 'fa-robot text-gold-500';
                                                $statusText = '';
                                                if(str_contains(strtolower($laporan->label_ai), 'berat')) {
                                                    $statusText = '<span class="text-red-400 font-black">RUSAK BERAT</span>';
                                                } elseif(str_contains(strtolower($laporan->label_ai), 'sedang')) {
                                                    $statusText = '<span class="text-orange-400 font-black">RUSAK SEDANG</span>';
                                                } elseif(str_contains(strtolower($laporan->label_ai), 'baik')) {
                                                    $statusText = '<span class="text-emerald-400 font-black">BAIK</span>';
                                                } else {
                                                    $statusText = '<span class="text-slate-300 font-black">' . strtoupper($laporan->label_ai) . '</span>';
                                                }
                                                $skorPercent = $laporan->skor_ai ? round($laporan->skor_ai * 100) . '%' : '';
                                            ?>
                                            <div class="inline-flex items-center gap-2 px-3 py-1.5 mb-3 rounded-lg border <?php echo e($aiColor); ?> text-xs uppercase tracking-wider shadow-sm">
                                                <i class="fas <?php echo e($aiIcon); ?> animate-pulse"></i> 
                                                <span>Dianalisis AI: <?php echo $statusText; ?> <?php echo $skorPercent ? "<span class='text-gold-500 font-black ml-1'>($skorPercent Yakin)</span>" : ''; ?></span>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <div class="flex gap-2">
                                            <?php if($laporan->foto): ?>
                                                <button onclick="showPhotoModal('<?php echo e(asset('storage/' . $laporan->foto)); ?>')" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 text-blue-600 hover:bg-blue-100 rounded-lg text-xs font-bold uppercase tracking-wider transition-colors">
                                                    <i class="fas fa-image"></i> Lihat Foto
                                                </button>
                                            <?php endif; ?>
                                            
                                            <a href="https://www.google.com/maps/search/?api=1&query=<?php echo e($laporan->latitude); ?>,<?php echo e($laporan->longitude); ?>" target="_blank" 
                                               class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-50 text-emerald-600 hover:bg-emerald-100 rounded-lg text-xs font-bold uppercase tracking-wider transition-colors">
                                                <i class="fas fa-map-marker-alt"></i> Cek Lokasi
                                            </a>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <form action="<?php echo e(route('admin.laporan-warga.status', $laporan->id)); ?>" method="POST" class="inline-block relative w-36 text-left">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('PUT'); ?>
                                            
                                            <?php
                                                $statusColor = 'bg-slate-100 text-slate-700 dark:bg-navy-900 dark:text-slate-300 border-slate-200 dark:border-white/10';
                                                if($laporan->status == 'Menunggu') $statusColor = 'bg-yellow-50 text-yellow-700 dark:bg-yellow-900/20 dark:text-yellow-400 border-yellow-200 dark:border-yellow-500/20';
                                                if($laporan->status == 'Ditinjau') $statusColor = 'bg-blue-50 text-blue-700 dark:bg-blue-900/20 dark:text-blue-400 border-blue-200 dark:border-blue-500/20';
                                                if($laporan->status == 'Diproses') $statusColor = 'bg-indigo-50 text-indigo-700 dark:bg-indigo-900/20 dark:text-indigo-400 border-indigo-200 dark:border-indigo-500/20';
                                                if($laporan->status == 'Selesai') $statusColor = 'bg-emerald-50 text-emerald-700 dark:bg-emerald-900/20 dark:text-emerald-400 border-emerald-200 dark:border-emerald-500/20';
                                                if($laporan->status == 'Ditolak') $statusColor = 'bg-red-50 text-red-700 dark:bg-red-900/20 dark:text-red-400 border-red-200 dark:border-red-500/20';
                                            ?>
                                            
                                            <select name="status" onchange="this.form.submit()" class="w-full appearance-none pl-3 pr-8 py-1.5 rounded-lg text-xs font-bold border <?php echo e($statusColor); ?> focus:outline-none focus:ring-2 focus:ring-navy-500 cursor-pointer shadow-sm">
                                                <option value="Menunggu" <?php echo e($laporan->status == 'Menunggu' ? 'selected' : ''); ?>>⏳ Menunggu</option>
                                                <option value="Ditinjau" <?php echo e($laporan->status == 'Ditinjau' ? 'selected' : ''); ?>>👀 Ditinjau</option>
                                                <option value="Diproses" <?php echo e($laporan->status == 'Diproses' ? 'selected' : ''); ?>>⚙️ Diproses</option>
                                                <option value="Selesai" <?php echo e($laporan->status == 'Selesai' ? 'selected' : ''); ?>>✅ Selesai</option>
                                                <option value="Ditolak" <?php echo e($laporan->status == 'Ditolak' ? 'selected' : ''); ?>>❌ Tidak Valid</option>
                                            </select>
                                            <i class="fas fa-chevron-down absolute right-2.5 top-1/2 -translate-y-1/2 text-xs opacity-60 pointer-events-none"></i>
                                        </form>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <form action="<?php echo e(route('admin.laporan-warga.assign', $laporan->id)); ?>" method="POST" class="inline-block relative w-36 text-left">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('PUT'); ?>
                                            
                                            <?php
                                                $assignColor = $laporan->id_surveyor ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-900/20 dark:text-indigo-400 border-indigo-200 dark:border-indigo-500/20' : 'bg-slate-100 text-slate-500 dark:bg-navy-900 dark:text-slate-400 border-slate-200 dark:border-white/10';
                                            ?>
                                            
                                            <select name="id_surveyor" onchange="this.form.submit()" class="w-full appearance-none pl-3 pr-8 py-1.5 rounded-lg text-xs font-bold border <?php echo e($assignColor); ?> focus:outline-none focus:ring-2 focus:ring-indigo-500 cursor-pointer shadow-sm">
                                                <option value="" disabled <?php echo e(!$laporan->id_surveyor ? 'selected' : ''); ?>>Pilih Surveyor</option>
                                                <?php $__currentLoopData = $surveyors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $surveyor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($surveyor->id); ?>" <?php echo e($laporan->id_surveyor == $surveyor->id ? 'selected' : ''); ?>>
                                                        👷‍♂️ <?php echo e($surveyor->name); ?>

                                                    </option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                            <i class="fas fa-chevron-down absolute right-2.5 top-1/2 -translate-y-1/2 text-xs opacity-60 pointer-events-none"></i>
                                        </form>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <div class="flex items-center justify-center gap-2 mx-auto">
                                            <?php if(!$laporan->id_infrastruktur): ?>
                                            <a href="<?php echo e(route('admin.laporan-warga.convert', $laporan->id)); ?>" title="Verifikasi & Tindak Lanjuti" class="w-8 h-8 flex items-center justify-center bg-emerald-500 hover:bg-emerald-600 text-white rounded-lg text-xs font-black transition shadow-sm hover:scale-105">
                                                <i class="fas fa-check-double"></i>
                                            </a>
                                            <?php else: ?>
                                            <a href="<?php echo e(route('admin.infrastruktur.show', $laporan->id_infrastruktur)); ?>" title="Lihat Infrastruktur" class="w-8 h-8 flex items-center justify-center bg-navy-900 hover:bg-navy-950 text-white rounded-lg text-xs font-black transition shadow-sm hover:scale-105">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <?php endif; ?>
                                            
                                            <form action="<?php echo e(route('admin.laporan-warga.destroy', $laporan->id)); ?>" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus laporan ini secara permanen?');" class="inline-block">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button type="submit" class="w-8 h-8 flex items-center justify-center bg-rose-500 hover:bg-rose-600 text-white rounded-lg text-xs font-black transition shadow-sm hover:scale-105" title="Hapus Laporan">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="6" class="px-8 py-20 text-center">
                                        <i class="fas fa-file-alt text-4xl text-slate-200 mb-4 block"></i>
                                        <p class="text-slate-400 font-bold text-sm">Belum Ada Laporan Warga.</p>
                                    </td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <?php if($laporanWarga->hasPages()): ?>
                    <div class="p-6 border-t border-slate-100 bg-slate-50/50">
                        <?php echo e($laporanWarga->links()); ?>

                    </div>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </main>

    <!-- Modal Foto -->
    <div id="photoModal" class="fixed inset-0 bg-navy-950/90 backdrop-blur-sm z-[9999] hidden opacity-0 transition-opacity duration-300 flex items-center justify-center p-4">
        <button onclick="closePhotoModal()" class="absolute top-6 right-6 w-12 h-12 bg-white/10 hover:bg-white/20 text-white rounded-full flex items-center justify-center transition-colors">
            <i class="fas fa-times text-xl"></i>
        </button>
        <div class="max-w-4xl w-full max-h-[90vh] relative transform scale-95 transition-transform duration-300" id="photoModalContent">
            <img id="modalImage" src="" alt="Foto Laporan" class="w-full h-full object-contain rounded-xl shadow-2xl">
        </div>
    </div>

    <script>
        function showPhotoModal(src) {
            const modal = document.getElementById('photoModal');
            const modalContent = document.getElementById('photoModalContent');
            const img = document.getElementById('modalImage');
            
            img.src = src;
            modal.classList.remove('hidden');
            
            // Trigger animation
            setTimeout(() => {
                modal.classList.remove('opacity-0');
                modalContent.classList.remove('scale-95');
                modalContent.classList.add('scale-100');
            }, 10);
            
            document.body.style.overflow = 'hidden';
        }

        function closePhotoModal() {
            const modal = document.getElementById('photoModal');
            const modalContent = document.getElementById('photoModalContent');
            
            modal.classList.add('opacity-0');
            modalContent.classList.remove('scale-100');
            modalContent.classList.add('scale-95');
            
            setTimeout(() => {
                modal.classList.add('hidden');
                document.body.style.overflow = '';
            }, 300);
        }

        // Close on background click
        document.getElementById('photoModal').addEventListener('click', function(e) {
            if (e.target === this) closePhotoModal();
        });

        // Real-time Clock function
        function updateClock() {
            const now = new Date();
            const options = { timeZone: 'Asia/Makassar', hour: '2-digit', minute: '2-digit', hour12: false };
            const timeString = new Intl.DateTimeFormat('id-ID', options).format(now);
            const el = document.getElementById('mini-clock');
            if (el) el.textContent = timeString.replace('.', ':') + ' WITA';
        }
        setInterval(updateClock, 1000); updateClock();
    </script>

</body>
</html>

<?php /**PATH C:\laragon1\laragon\www\geo-sinfra\resources\views\admin\laporan-warga.blade.php ENDPATH**/ ?>