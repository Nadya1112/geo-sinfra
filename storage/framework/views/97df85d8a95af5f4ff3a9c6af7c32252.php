<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Laporan & Rekapitulasi | Kabid SINFRA</title>
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
        @media print {
            .no-print { display: none !important; }
            .print-only { display: block !important; }
            body { background: white; }
            .flex { display: block; }
            aside { display: none; }
            main { width: 100%; margin: 0; padding: 0; }
        }
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    </style>
</head>
<body class="bg-slate-50 flex h-screen overflow-hidden text-slate-800 text-left">

    <?php echo $__env->make('kabid.partials.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <main class="flex-1 flex flex-col h-screen overflow-hidden">
        <header class="bg-white border-b border-slate-100 px-8 py-5 flex justify-between items-center z-10 no-print sticky top-0">
            <div class="flex items-center gap-4">
                <a href="<?php echo e(route('kabid.dashboard')); ?>" class="w-10 h-10 flex items-center justify-center bg-slate-50 text-slate-400 rounded-xl hover:bg-gold-50 hover:text-gold-500 transition-all border border-slate-100">
                    <i class="fas fa-arrow-left text-sm"></i>
                </a>
                <div>
                    <p class="text-[10px] font-extrabold text-gold-500 uppercase tracking-[0.2em] mb-1">Reporting Center</p>
                    <h2 class="text-xl font-black text-navy-900">Laporan & Rekapitulasi</h2>
                </div>
            </div>
            
            <div class="flex items-center gap-6">
                <div class="text-right hidden sm:block">
                    <p class="text-[11px] font-black text-navy-900" id="mini-clock">00:00 WITA</p>
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-tighter"><?php echo e(now()->translatedFormat('l, d F Y')); ?></p>
                </div>
                <div class="h-8 w-[1px] bg-slate-100"></div>
                <a href="<?php echo e(route('kabid.profile')); ?>" class="flex items-center gap-3 group">
                    <div class="text-right">
                        <p class="text-[11px] font-black text-navy-900 leading-none uppercase group-hover:text-gold-500 transition-colors"><?php echo e(auth()->user()->name); ?></p>
                        <p class="text-[9px] font-bold text-[#059669] uppercase mt-1 italic">ONLINE</p>
                    </div>
                    <div class="w-10 h-10 bg-navy-50 rounded-xl flex items-center justify-center text-navy-900 border border-navy-100 overflow-hidden shadow-sm group-hover:border-gold-300 group-hover:shadow-md transition-all">
                        <?php if(auth()->user()->profile_photo): ?>
                            <img src="<?php echo e(asset('storage/' . auth()->user()->profile_photo)); ?>" class="w-full h-full object-cover">
                        <?php else: ?>
                            <i class="fas fa-user-tie text-xl group-hover:text-gold-500 transition-colors"></i>
                        <?php endif; ?>
                    </div>
                </a>
            </div>
        </header>

        <div class="flex-1 overflow-y-auto p-8 custom-scrollbar">
            <!-- Summary Cards (No Print) -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-8 no-print">
                <div class="bg-white rounded-[2rem] p-6 border border-slate-100 shadow-sm flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-slate-50 border border-slate-100 text-slate-400 flex items-center justify-center shrink-0">
                        <i class="fas fa-layer-group text-xl"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Laporan</p>
                        <h3 class="text-2xl font-black text-navy-900 leading-none"><?php echo e($totalLaporan); ?></h3>
                    </div>
                </div>
                <div class="bg-white rounded-[2rem] p-6 border border-emerald-50 shadow-sm flex items-center gap-4 hover:border-emerald-100 transition-colors">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                        <i class="fas fa-check-circle text-xl"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-emerald-600/70 uppercase tracking-widest mb-1">Kondisi Baik</p>
                        <h3 class="text-2xl font-black text-navy-900 leading-none"><?php echo e($totalBaik); ?></h3>
                    </div>
                </div>
                <div class="bg-white rounded-[2rem] p-6 border border-amber-50 shadow-sm flex items-center gap-4 hover:border-amber-100 transition-colors">
                    <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
                        <i class="fas fa-exclamation-triangle text-xl"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-amber-600/70 uppercase tracking-widest mb-1">Kondisi Sedang</p>
                        <h3 class="text-2xl font-black text-navy-900 leading-none"><?php echo e($totalSedang); ?></h3>
                    </div>
                </div>
                <div class="bg-white rounded-[2rem] p-6 border border-rose-50 shadow-sm flex items-center gap-4 hover:border-rose-100 transition-colors">
                    <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center shrink-0">
                        <i class="fas fa-times-circle text-xl"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-rose-600/70 uppercase tracking-widest mb-1">Kondisi Berat</p>
                        <h3 class="text-2xl font-black text-rose-600 leading-none"><?php echo e($totalBerat); ?></h3>
                    </div>
                </div>
            </div>

            <!-- Filter Section (No Print) -->
            <div class="bg-white rounded-[2rem] p-8 border border-slate-100 shadow-sm mb-8 no-print">
                <form action="<?php echo e(route('kabid.laporan')); ?>" method="GET" class="flex flex-wrap md:flex-nowrap gap-6 items-end">
                    <input type="hidden" name="show" value="<?php echo e(request('show')); ?>">
                    <div class="w-full md:w-1/4">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-2">Wilayah</label>
                        <select name="kecamatan" class="w-full bg-slate-50 border border-slate-100 rounded-xl px-4 py-2.5 text-xs font-bold text-navy-900 focus:outline-none focus:ring-2 focus:ring-gold-500/20 focus:border-gold-500 transition-all">
                            <option value="">Semua Wilayah</option>
                            <?php $__currentLoopData = $kecamatan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kec): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($kec->id_kecamatan); ?>" <?php echo e(request('kecamatan') == $kec->id_kecamatan ? 'selected' : ''); ?>>
                                    <?php echo e($kec->nama_kecamatan); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="w-full md:w-1/4">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-2">Kondisi</label>
                        <select name="kondisi" class="w-full bg-slate-50 border border-slate-100 rounded-xl px-4 py-2.5 text-xs font-bold text-navy-900 focus:outline-none focus:ring-2 focus:ring-gold-500/20 focus:border-gold-500 transition-all">
                            <option value="">Semua Analisis</option>
                            <option value="Baik" <?php echo e(request('kondisi') == 'Baik' ? 'selected' : ''); ?>>Baik</option>
                            <option value="Sedang" <?php echo e(request('kondisi') == 'Sedang' ? 'selected' : ''); ?>>Sedang</option>
                            <option value="Berat" <?php echo e(request('kondisi') == 'Berat' ? 'selected' : ''); ?>>Berat</option>
                        </select>
                    </div>
                    <div class="w-full md:w-1/4">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-2">Infrastruktur</label>
                        <select name="jenis" class="w-full bg-slate-50 border border-slate-100 rounded-xl px-4 py-2.5 text-xs font-bold text-navy-900 focus:outline-none focus:ring-2 focus:ring-gold-500/20 focus:border-gold-500 transition-all">
                            <option value="">Semua Infrastruktur</option>
                            <option value="Jalan" <?php echo e(request('jenis') == 'Jalan' ? 'selected' : ''); ?>>Jalan</option>
                            <option value="Titian" <?php echo e(request('jenis') == 'Titian' ? 'selected' : ''); ?>>Titian</option>
                            <option value="Sanitasi" <?php echo e(request('jenis') == 'Sanitasi' ? 'selected' : ''); ?>>Sanitasi</option>
                        </select>
                    </div>
                    <div class="w-full md:w-1/4 flex gap-2 justify-end">
                        <button type="submit" class="px-6 py-2.5 bg-navy-900 text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-gold-500 transition-all shadow-lg shadow-navy-900/10">
                            Filter Data
                        </button>
                        <a href="<?php echo e(route('kabid.laporan')); ?>" class="px-4 py-2.5 bg-slate-50 text-slate-400 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-slate-100 hover:text-slate-600 transition-all flex items-center border border-slate-100 shadow-sm">
                            <i class="fas fa-sync-alt"></i>
                        </a>
                    </div>
                </form>
            </div>

            <!-- Print Header (Hidden on Screen) -->
            <div class="hidden print-only mb-10 text-center border-b-2 border-navy-900 pb-6">
                <h1 class="text-2xl font-black text-navy-900 uppercase tracking-tighter">Laporan Rekapitulasi Infrastruktur</h1>
                <p class="text-sm font-bold text-slate-500 mt-1 uppercase">Sistem Informasi Geospasial (GEO-SINFRA)</p>
                <div class="mt-4 flex justify-center gap-8 text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                    <span>Wilayah: <?php echo e(request('kecamatan') ? $kecamatan->find(request('kecamatan'))->nama_kecamatan : 'Semua'); ?></span>
                    <span>Catatan: <?php echo e(request('kondisi') ?: 'Semua'); ?></span>
                    <span>Dicetak: <?php echo e(now()->translatedFormat('d F Y H:i')); ?></span>
                </div>
            </div>

            <!-- Data Table -->
            <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden mt-6">
                <!-- Header with Tampilan Dropdown -->
                <div class="px-8 py-6 border-b border-slate-50 flex justify-between items-center bg-slate-50/30 no-print">
                    <div>
                        <h3 class="text-sm font-black text-navy-900 uppercase tracking-widest">Data Laporan</h3>
                        <p class="text-[9px] text-slate-400 font-bold uppercase mt-1">Hasil filter rekapitulasi data</p>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="flex items-center gap-2 mr-2 border-r border-slate-200 pr-4">
                            <button onclick="window.print()" class="no-print px-4 py-2 bg-rose-50 text-rose-600 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-rose-100 hover:scale-[1.02] transition-all flex items-center gap-2 border border-rose-100 shadow-sm">
                                <i class="fas fa-file-pdf"></i> Cetak PDF
                            </button>
                            <button onclick="exportTableToCSV('Laporan-Infrastruktur-<?php echo e(date('Y-m-d')); ?>.csv')" class="no-print px-4 py-2 bg-emerald-50 text-emerald-600 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-emerald-100 hover:scale-[1.02] transition-all flex items-center gap-2 border border-emerald-100 shadow-sm">
                                <i class="fas fa-file-excel"></i> Export Excel
                            </button>
                        </div>
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
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Tampilan:</label>
                            <select name="show" onchange="this.form.submit()" class="text-xs font-bold text-navy-900 bg-white border border-slate-200 rounded-xl px-3 py-1.5 focus:outline-none focus:border-gold-500 transition-colors">
                                <option value="10" <?php echo e(request('show') != 'all' ? 'selected' : ''); ?>>Per 10 Data</option>
                                <option value="all" <?php echo e(request('show') == 'all' ? 'selected' : ''); ?>>Semua Data</option>
                            </select>
                        </form>
                    </div>
                </div>
                <?php if(request('kecamatan') || request('kondisi')): ?>
                <div class="bg-navy-50/50 px-6 py-4 border-b border-navy-100/50 flex flex-wrap items-center gap-3 no-print">
                    <span class="text-[9px] font-black text-navy-400 uppercase tracking-widest mr-2">Filter Aktif:</span>
                    <?php if(request('kecamatan')): ?>
                        <span class="px-3 py-1 bg-white text-navy-600 rounded-full text-[10px] font-bold shadow-sm border border-navy-100">
                            <i class="fas fa-map-marker-alt mr-1"></i> <?php echo e($kecamatan->find(request('kecamatan'))->nama_kecamatan ?? 'Wilayah'); ?>

                        </span>
                    <?php endif; ?>
                    <?php if(request('kondisi')): ?>
                        <span class="px-3 py-1 bg-white text-navy-600 rounded-full text-[10px] font-bold shadow-sm border border-navy-100">
                            <i class="fas fa-clipboard-list mr-1"></i> <?php echo e(request('kondisi')); ?>

                        </span>
                    <?php endif; ?>
                    <?php if(request('jenis')): ?>
                        <span class="px-3 py-1 bg-white text-navy-600 rounded-full text-[10px] font-bold shadow-sm border border-navy-100">
                            <i class="fas fa-layer-group mr-1"></i> <?php echo e(request('jenis')); ?>

                        </span>
                    <?php endif; ?>
                    <a href="<?php echo e(route('kabid.laporan')); ?>" class="ml-auto text-[10px] font-bold text-red-400 hover:text-red-600 transition-all">
                        <i class="fas fa-times mr-1"></i> Hapus Filter
                    </a>
                </div>
                <?php endif; ?>
                <table id="laporanTable" class="w-full text-left">
                    <thead>
                        <tr class="bg-slate-50/50 text-[9px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">
                            <th class="px-6 py-4 border-b border-slate-100">No</th>
                            <th class="px-6 py-4 border-b border-slate-100">Infrastruktur</th>
                            <th class="px-6 py-4 border-b border-slate-100">Wilayah</th>
                            <th class="px-6 py-4 text-center border-b border-slate-100">Analisis AI</th>
                            <th class="px-6 py-4 border-b border-slate-100 text-right">Tanggal Data</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        <?php $__empty_1 = true; $__currentLoopData = $reports; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="group hover:bg-slate-50/50 transition-all">
                            <td class="px-6 py-3 text-xs font-bold text-slate-400"><?php echo e(request('show') == 'all' ? $index + 1 : ($reports->currentPage() - 1) * $reports->perPage() + $index + 1); ?></td>
                            <td class="px-6 py-3">
                                <p class="text-xs font-black text-navy-900 uppercase"><?php echo e($item->nama_objek); ?></p>
                                <p class="text-[9px] text-slate-400 font-bold uppercase mt-0.5"><?php echo e($item->jenis); ?></p>
                            </td>
                            <td class="px-6 py-3">
                                <p class="text-xs font-bold text-navy-900"><?php echo e($item->kelurahan->nama_kelurahan ?? '-'); ?></p>
                                <p class="text-[9px] text-slate-400 font-bold uppercase mt-0.5"><?php echo e($item->kelurahan->kecamatan->nama_kecamatan ?? '-'); ?></p>
                            </td>
                            <td class="px-6 py-3">
                                <div class="flex justify-center">
                                    <?php
                                        $aiLabel = $item->analisis->label_prioritas ?? '';
                                        $aiLabelLower = strtolower($aiLabel);
                                        
                                        $condClass = 'bg-slate-50 text-slate-600 border-slate-200';
                                        if (str_contains($aiLabelLower, 'berat')) {
                                            $condClass = 'bg-[#be123c]/10 text-[#be123c] border-[#be123c]/30';
                                        } elseif (str_contains($aiLabelLower, 'sedang') || str_contains($aiLabelLower, 'ringan')) {
                                            $condClass = 'bg-[#d97706]/10 text-[#d97706] border-[#d97706]/30';
                                        } elseif (str_contains($aiLabelLower, 'baik')) {
                                            $condClass = 'bg-[#059669]/10 text-[#059669] border-[#059669]/30';
                                        }
                                    ?>
                                    <span class="px-2.5 py-1 rounded-md text-[8px] font-black uppercase border tracking-widest <?php echo e($condClass); ?>">
                                        <?php echo e($aiLabel ?: 'Belum Dianalisis'); ?>

                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-3 text-right text-xs font-bold text-slate-400">
                                <?php echo e($item->created_at->format('d/m/Y')); ?>

                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="5" class="px-6 py-20 text-center">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-folder-open text-slate-200 text-4xl mb-4"></i>
                                    <p class="text-xs text-slate-400 font-bold italic uppercase">Tidak ada data yang ditemukan sesuai filter.</p>
                                </div>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
                
                <?php if(request('show') != 'all' && isset($reports) && $reports instanceof \Illuminate\Pagination\LengthAwarePaginator): ?>
                    <div class="px-8 py-4 border-t border-slate-50 bg-slate-50/10 no-print">
                        <?php echo e($reports->links()); ?>

                    </div>
                <?php endif; ?>
            </div>

            <!-- Print Footer -->
            <div class="hidden print-only mt-20 grid grid-cols-2 text-center">
                <div></div>
                <div class="text-xs font-bold">
                    <p>Banjarmasin, <?php echo e(now()->translatedFormat('d F Y')); ?></p>
                    <p class="mt-2 text-[10px] text-slate-400 uppercase tracking-widest">Mengetahui,</p>
                    <p class="mt-16 font-black uppercase text-navy-900 underline">KABID SINFRA</p>
                    <p class="text-[10px] text-slate-400 font-bold">NIP. 19850320 201001 1 005</p>
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

        function exportTableToCSV(filename) {
            var csv = [];
            var rows = document.querySelectorAll("table#laporanTable tr");
            
            for (var i = 0; i < rows.length; i++) {
                var row = [], cols = rows[i].querySelectorAll("td, th");
                
                for (var j = 0; j < cols.length; j++) 
                    row.push('"' + cols[j].innerText.replace(/"/g, '""').replace(/\n/g, ' ') + '"');
                
                csv.push(row.join(","));
            }

            var csvFile = new Blob([csv.join("\n")], {type: "text/csv"});
            var downloadLink = document.createElement("a");
            downloadLink.download = filename;
            downloadLink.href = window.URL.createObjectURL(csvFile);
            downloadLink.style.display = "none";
            document.body.appendChild(downloadLink);
            downloadLink.click();
        }
    </script>
</body>
</html>
<?php /**PATH C:\laragon1\laragon\www\geo-sinfra\resources\views/kabid/laporan.blade.php ENDPATH**/ ?>