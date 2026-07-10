<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Infrastruktur | GEO-SINFRA</title>
    <link rel="icon" href="<?php echo e(asset('logo_geo-sinfra.png')); ?>" type="image/png">
    <script src="https://cdn.tailwindcss.com"></script>
            <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: { sans: ['Plus Jakarta Sans', 'sans-serif'] },
                    colors: {
                        navy: { 50:'#f4f4fa', 100:'#e9e9f3', 200:'#c7c8e3', 300:'#9fb3c8', 400:'#829ab1', 500:'#6366f1', 600:'#486581', 700:'#334e68', 800:'#1e1b4b', 900:'#0f0e2c', 950:'#070617' },
                        gold: { 50:'#fdfbf7', 100:'#fbf7ed', 200:'#eed9b9', 300:'#e5c292', 400:'#dba665', 500:'#c5a059', 600:'#b38f4a', 700:'#9d7c3d', 800:'#7c5327', 900:'#644422', 950:'#382310' }
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .leaflet-bar { border: none !important; box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1) !important; border-radius: 8px !important; overflow: hidden; }
        .leaflet-bar a { width: 26px !important; height: 26px !important; line-height: 26px !important; font-size: 14px !important; }
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    </style>
<style>
    
    
@media (max-width: 767px) { html { font-size: 12px; } }
</style>
</head>
<body class="bg-slate-50 dark:bg-[#0f0e2c] flex h-screen overflow-hidden text-slate-800 dark:text-white text-left font-sans dark:bg-navy-950 transition-colors duration-300">

    <?php echo $__env->make('tim_teknis.partials.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <main class="flex-1 flex flex-col h-screen overflow-y-auto custom-scrollbar">
        <!-- HEADER -->
        <header class="bg-white dark:bg-[#1e1b4b] border-b border-slate-100 dark:border-white/10 px-4 pl-20 md:px-8 py-4 flex justify-between items-center z-40 sticky top-0">
            <div class="flex items-center gap-4 min-w-0">
                <a href="<?php echo e(route('tim_teknis.validasi')); ?>" class="w-10 h-10 flex items-center justify-center bg-slate-50 dark:bg-[#0f0e2c] text-slate-400 rounded-xl hover:bg-gold-50 hover:text-gold-500 transition-all border border-slate-100 dark:border-white/10 hidden md:flex">
                    <i class="fas fa-arrow-left text-sm"></i>
                </a>
                <div class="min-w-0">
                    <p class="text-[9px] md:text-xs font-extrabold text-gold-500 uppercase tracking-[0.2em] mb-0.5 md:mb-1 truncate">Verifikasi Usulan</p>
                    <div class="flex items-center gap-2 md:gap-4 flex-wrap">
                        <h2 class="text-sm md:text-xl font-black text-navy-900 dark:text-white leading-tight whitespace-normal">Detail Infrastruktur</h2>
                        <a href="<?php echo e(route('tim_teknis.infrastruktur.pdf', $infrastruktur->id_infrastruktur)); ?>" target="_blank" class="px-2 py-1 md:px-3 md:py-1.5 bg-rose-50 text-rose-600 rounded-lg text-[10px] md:text-xs font-black uppercase tracking-widest hover:bg-rose-500 hover:text-white transition-colors border border-rose-100 flex items-center gap-1 md:gap-2 shadow-sm whitespace-nowrap">
                            <i class="fas fa-file-pdf"></i> Cetak PDF
                        </a>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3 md:gap-6 flex-shrink-0">
                <div class="text-right">
                    <p class="text-[10px] md:text-xs font-black text-navy-900 dark:text-white mt-1" id="mini-clock">00:00 WITA</p>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-tighter hidden md:block"><?php echo e(now()->translatedFormat('l, d F Y')); ?></p>
                </div>
                <div class="h-6 md:h-8 w-[1px] bg-slate-200 dark:bg-white/10"></div>
                <div class="flex items-center gap-2 md:gap-3">
                    <div class="text-right">
                        <p class="text-sm font-black text-navy-900 dark:text-white leading-none uppercase max-w-[200px] truncate hidden md:block"><?php echo e(auth()->user()->name); ?></p>
                        <p class="text-[8px] md:text-xs font-bold text-emerald-500 uppercase md:mt-0.5 leading-none">ONLINE</p>
                    </div>
                    <div class="w-8 h-8 md:w-10 md:h-10 bg-navy-900 rounded-xl flex items-center justify-center text-gold-500 shadow-md transition-all overflow-hidden shrink-0">
                        <?php if(auth()->user()->profile_photo): ?>
                            <img src="<?php echo e(asset('storage/' . auth()->user()->profile_photo)); ?>" class="w-full h-full object-cover">
                        <?php else: ?>
                            <i class="fas fa-user-circle text-lg md:text-xl"></i>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </header>

        <!-- CONTENT -->
        <div class="p-8">
            <div class="max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-8">

                <!-- Kolom Kiri: Foto & AI Panel -->
                <div class="lg:col-span-1 space-y-6">
                    <!-- Foto -->
                    <?php
                        $cleanPath = $infrastruktur->foto_terbaru ? str_replace('\\', '/', $infrastruktur->foto_terbaru) : null;
                        $fotoUrl   = $cleanPath ? asset('storage/' . (str_contains($cleanPath, 'infrastruktur/') ? $cleanPath : 'infrastruktur/' . $cleanPath)) : null;
                    ?>
                    <div class="bg-white dark:bg-[#1e1b4b] rounded-[2.5rem] p-4 border border-slate-100 dark:border-white/10 shadow-sm overflow-hidden">
                        <div class="relative aspect-[3/4] w-full rounded-[2rem] overflow-hidden group bg-navy-950 flex items-center justify-center">
                            <?php if($fotoUrl): ?>
                                <img src="<?php echo e($fotoUrl); ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-all flex items-end p-6">
                                    <p class="text-white text-xs font-bold uppercase tracking-widest">Foto Dokumentasi</p>
                                </div>
                                <div class="absolute inset-0 bg-black/50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                    <a href="<?php echo e($fotoUrl); ?>" target="_blank" class="bg-white dark:bg-[#1e1b4b] text-navy-900 dark:text-white px-4 py-2 rounded-xl text-xs font-black shadow-xl uppercase tracking-widest hover:scale-105 transition-all flex items-center gap-2">
                                        <i class="fas fa-expand"></i> Lihat Full
                                    </a>
                                </div>
                            <?php else: ?>
                                <div class="text-center">
                                    <i class="fas fa-image text-4xl text-slate-700 mb-2 block"></i>
                                    <p class="text-xs font-black text-slate-500 uppercase tracking-widest">Tidak Ada Foto</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- AI Analysis Panel -->
                    <!-- HYBRID AI RESULTS -->
                    <div class="bg-navy-900 rounded-[2.5rem] p-8 text-white shadow-xl relative overflow-hidden">
                        <div class="absolute top-0 right-0 -mt-8 -mr-8 w-32 h-32 bg-white/5 dark:bg-[#1e1b4b]/5 rounded-full blur-2xl"></div>
                        <h4 class="text-xs font-black text-gold-300 uppercase tracking-[0.2em] mb-8 flex items-center gap-3">
                            <i class="fas fa-microchip"></i> Hybrid AI Analysis
                        </h4>
                        
                        <div class="space-y-8">
                            <!-- Visual CNN -->
                            <div class="relative">
                                <div class="flex justify-between items-end mb-2">
                                    <p class="text-xs font-black text-slate-300 uppercase tracking-widest">Vision (CNN)</p>
                                    <p class="text-xl font-black text-white"><?php echo e($infrastruktur->cnn ? round($infrastruktur->cnn->skor_cnn * 100) : '0'); ?>%</p>
                                </div>
                                <div class="w-full bg-white/10 dark:bg-[#1e1b4b]/10 h-1.5 rounded-full overflow-hidden">
                                    <div class="bg-gradient-to-r from-gold-500 to-gold-300 h-full" style="width: <?php echo e($infrastruktur->cnn ? ($infrastruktur->cnn->skor_cnn * 100) : '0'); ?>%"></div>
                                </div>
                                <p class="text-xs font-bold text-slate-400 mt-2 italic text-right"><?php echo e($infrastruktur->cnn->label_kondisi ?? 'Scanning visual...'); ?></p>
                            </div>
                            
                            <!-- Logic DT -->
                            <div class="relative">
                                <div class="flex justify-between items-end mb-2">
                                    <p class="text-xs font-black text-slate-300 uppercase tracking-widest">Logic (DT)</p>
                                    <p class="text-xl font-black text-white"><?php echo e($infrastruktur->analisis->skor_dt ?? '0'); ?><span class="text-xs text-slate-400 ml-0.5">/100</span></p>
                                </div>
                                <div class="w-full bg-white/10 dark:bg-[#1e1b4b]/10 h-1.5 rounded-full overflow-hidden">
                                    <div class="bg-gradient-to-r from-[#059669] to-emerald-400 h-full" style="width: <?php echo e($infrastruktur->analisis->skor_dt ?? '0'); ?>%"></div>
                                </div>
                                <p class="text-xs font-bold <?php echo e(($infrastruktur->analisis->label_prioritas ?? '') == 'Rusak Berat' ? 'text-rose-400' : 'text-[#059669]'); ?> mt-2 italic text-right">
                                    <?php echo e($infrastruktur->analisis->label_prioritas ?? 'Calculating logic...'); ?>

                                </p>
                            </div>

                            <div class="pt-6 border-t border-white/10">
                                <div class="flex items-center justify-between mb-4">
                                    <p class="text-xs font-black text-slate-300 uppercase tracking-widest">Verification</p>
                                    <span class="px-3 py-1 bg-white/5 dark:bg-[#1e1b4b]/5 border border-white/10 rounded-lg text-xs font-black uppercase tracking-widest <?php echo e($infrastruktur->status_verifikasi == 'Verified' ? 'text-[#059669]' : 'text-amber-400'); ?>">
                                        <?php echo e($infrastruktur->status_verifikasi ?? 'Pending'); ?>

                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Aksi Verifikasi -->
                    <?php if($infrastruktur->status_verifikasi == 'Pending'): ?>
                    <div class="bg-white dark:bg-[#1e1b4b] rounded-[2.5rem] p-6 border border-slate-100 dark:border-white/10 shadow-sm">
                        <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-4">Tindakan Verifikasi</p>
                        <div class="flex flex-col gap-3">
                            <form action="<?php echo e(route('tim_teknis.validasi.proses', $infrastruktur->id_infrastruktur)); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="status" value="Validated">
                                <button type="submit" class="w-full flex items-center justify-center gap-2 py-3 bg-[#059669] text-white rounded-2xl hover:bg-[#047857] transition-all shadow-lg shadow-[#059669]/20 font-black text-sm uppercase tracking-widest">
                                    <i class="fas fa-check"></i> Terima Usulan
                                </button>
                            </form>
                            <form action="<?php echo e(route('tim_teknis.validasi.proses', $infrastruktur->id_infrastruktur)); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="status" value="Rejected">
                                <button type="button" onclick="const alasan = prompt('Masukkan alasan penolakan:'); if(alasan) { const input = document.createElement('input'); input.type = 'hidden'; input.name = 'alasan_penolakan'; input.value = alasan; this.form.appendChild(input); this.form.submit(); }" class="w-full flex items-center justify-center gap-2 py-3 bg-white dark:bg-[#1e1b4b] border border-rose-200 text-rose-500 rounded-2xl hover:bg-rose-50 hover:border-rose-300 transition-all font-black text-sm uppercase tracking-widest">
                                    <i class="fas fa-times"></i> Tolak Usulan
                                </button>
                            </form>
                        </div>
                    </div>
                    <?php elseif($infrastruktur->status_validasi == 'Validated'): ?>
                    <div class="bg-white dark:bg-[#1e1b4b] rounded-[2.5rem] p-6 border border-slate-100 dark:border-white/10 shadow-sm relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-24 h-24 bg-blue-500/5 rounded-bl-full"></div>
                        <h4 class="text-xs font-black text-navy-900 dark:text-white uppercase tracking-widest mb-4 flex items-center gap-2">
                            <i class="fas fa-tasks text-blue-500"></i> Status Pengerjaan (Tindak Lanjut)
                        </h4>
                        
                        <form action="<?php echo e(route('tim_teknis.perbaikan.update', $infrastruktur->id_infrastruktur)); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <div class="space-y-3">
                                <label class="flex items-center justify-between p-3 rounded-2xl border cursor-pointer transition-all <?php echo e($infrastruktur->status_perbaikan == 'Menunggu' ? 'border-amber-500 bg-amber-50' : 'border-slate-200 dark:border-white/20 hover:bg-slate-50 dark:bg-[#0f0e2c]'); ?>">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-xl <?php echo e($infrastruktur->status_perbaikan == 'Menunggu' ? 'bg-amber-500 text-white' : 'bg-slate-100 text-slate-400'); ?> flex items-center justify-center text-xs shadow-sm">
                                            <i class="fas fa-clock"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm font-black <?php echo e($infrastruktur->status_perbaikan == 'Menunggu' ? 'text-amber-700' : 'text-slate-600'); ?> uppercase tracking-wider">Menunggu</p>
                                            <p class="text-xs font-bold text-slate-400">Belum ditindaklanjuti</p>
                                        </div>
                                    </div>
                                    <input type="radio" name="status_perbaikan" value="Menunggu" class="w-4 h-4 text-amber-500 border-slate-300 focus:ring-amber-500" <?php echo e($infrastruktur->status_perbaikan == 'Menunggu' ? 'checked' : ''); ?> onchange="this.form.submit()">
                                </label>

                                <label class="flex items-center justify-between p-3 rounded-2xl border cursor-pointer transition-all <?php echo e($infrastruktur->status_perbaikan == 'Proses Perbaikan' ? 'border-blue-500 bg-blue-50' : 'border-slate-200 dark:border-white/20 hover:bg-slate-50 dark:bg-[#0f0e2c]'); ?>">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-xl <?php echo e($infrastruktur->status_perbaikan == 'Proses Perbaikan' ? 'bg-blue-500 text-white' : 'bg-slate-100 text-slate-400'); ?> flex items-center justify-center text-xs shadow-sm">
                                            <i class="fas fa-tools"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm font-black <?php echo e($infrastruktur->status_perbaikan == 'Proses Perbaikan' ? 'text-blue-700' : 'text-slate-600'); ?> uppercase tracking-wider">Dalam Perbaikan</p>
                                            <p class="text-xs font-bold text-slate-400">Sedang dikerjakan tim</p>
                                        </div>
                                    </div>
                                    <input type="radio" name="status_perbaikan" value="Proses Perbaikan" class="w-4 h-4 text-blue-500 border-slate-300 focus:ring-blue-500" <?php echo e($infrastruktur->status_perbaikan == 'Proses Perbaikan' ? 'checked' : ''); ?> onchange="this.form.submit()">
                                </label>

                                <label class="flex items-center justify-between p-3 rounded-2xl border cursor-pointer transition-all <?php echo e($infrastruktur->status_perbaikan == 'Selesai' ? 'border-emerald-500 bg-emerald-50 dark:bg-emerald-900/20' : 'border-slate-200 dark:border-white/20 hover:bg-slate-50 dark:bg-[#0f0e2c]'); ?>">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-xl <?php echo e($infrastruktur->status_perbaikan == 'Selesai' ? 'bg-emerald-500 text-white' : 'bg-slate-100 text-slate-400'); ?> flex items-center justify-center text-xs shadow-sm">
                                            <i class="fas fa-check-double"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm font-black <?php echo e($infrastruktur->status_perbaikan == 'Selesai' ? 'text-emerald-700' : 'text-slate-600'); ?> uppercase tracking-wider">Selesai</p>
                                            <p class="text-xs font-bold text-slate-400">Infrastruktur telah tuntas</p>
                                        </div>
                                    </div>
                                    <input type="radio" name="status_perbaikan" value="Selesai" class="w-4 h-4 text-emerald-500 border-slate-300 focus:ring-emerald-500" <?php echo e($infrastruktur->status_perbaikan == 'Selesai' ? 'checked' : ''); ?> onchange="this.form.submit()">
                                </label>
                            </div>
                        </form>
                    </div>
                    <?php endif; ?>

                    <?php if($infrastruktur->status_validasi == 'Rejected' && $infrastruktur->alasan_penolakan): ?>
                    <div class="bg-amber-50 rounded-[2.5rem] p-6 border border-amber-100 shadow-sm relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-24 h-24 bg-amber-500/5 rounded-bl-full"></div>
                        <h4 class="text-xs font-black text-amber-900 uppercase tracking-widest mb-3 flex items-center gap-2">
                            <i class="fas fa-comment-dots text-amber-500"></i> Catatan Eksekutif (Tim Teknis)
                        </h4>
                        <div class="p-4 bg-white/60 dark:bg-[#1e1b4b]/60 rounded-2xl border border-amber-200/50">
                            <p class="text-xs font-bold text-slate-600 leading-relaxed"><?php echo e($infrastruktur->alasan_penolakan); ?></p>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Kolom Kanan: Info & Peta -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white dark:bg-[#1e1b4b] rounded-[2.5rem] p-8 border border-slate-100 dark:border-white/10 shadow-sm">
                        <div class="flex justify-between items-start mb-8">
                            <div>
                                <p class="text-xs font-black text-gold-500 uppercase tracking-widest mb-1"><?php echo e(ucfirst($infrastruktur->jenis)); ?></p>
                                <h3 class="text-2xl font-black text-navy-900 dark:text-white"><?php echo e($infrastruktur->nama_infrastruktur); ?></h3>
                            </div>
                            <div class="text-right">
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-tighter">Diinput Pada</p>
                                <p class="text-xs font-black text-navy-900 dark:text-white"><?php echo e($infrastruktur->created_at->translatedFormat('d F Y, H:i')); ?> WITA</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-8 mb-8">
                            <div class="space-y-4">
                                <div class="flex items-start gap-4">
                                    <div class="w-10 h-10 rounded-2xl bg-navy-50 flex items-center justify-center text-navy-500 border border-navy-100 flex-shrink-0">
                                        <i class="fas fa-map-marked-alt text-sm"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs font-black text-slate-400 uppercase tracking-widest">Kecamatan</p>
                                        <p class="text-sm font-bold text-navy-900 dark:text-white"><?php echo e($infrastruktur->kelurahan->kecamatan->nama_kecamatan ?? '-'); ?></p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-4">
                                    <div class="w-10 h-10 rounded-2xl bg-navy-50 flex items-center justify-center text-navy-500 border border-navy-100 flex-shrink-0">
                                        <i class="fas fa-building text-sm"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs font-black text-slate-400 uppercase tracking-widest">Kelurahan</p>
                                        <p class="text-sm font-bold text-navy-900 dark:text-white"><?php echo e($infrastruktur->kelurahan->nama_kelurahan ?? '-'); ?></p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-4">
                                    <div class="w-10 h-10 rounded-2xl bg-navy-50 flex items-center justify-center text-navy-500 border border-navy-100 flex-shrink-0">
                                        <i class="fas fa-user text-sm"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs font-black text-slate-400 uppercase tracking-widest">Surveyor</p>
                                        <p class="text-sm font-bold text-navy-900 dark:text-white"><?php echo e($infrastruktur->user->name ?? '-'); ?></p>
                                    </div>
                                </div>
                            </div>
                            <div class="space-y-4">
                                <div class="flex items-start gap-4">
                                    <div class="w-10 h-10 rounded-2xl bg-navy-50 flex items-center justify-center text-navy-500 border border-navy-100 flex-shrink-0">
                                        <i class="fas fa-location-arrow text-sm"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs font-black text-slate-400 uppercase tracking-widest">Koordinat</p>
                                        <p class="text-xs font-bold text-navy-900 dark:text-white"><?php echo e($infrastruktur->latitude); ?>, <?php echo e($infrastruktur->longitude); ?></p>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <!-- Detail Teknis -->
                        <div class="border-t border-slate-100 dark:border-white/10 pt-6 mb-8 mt-2">
                            <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-4">Informasi Fisik Lapangan</p>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-5">
                                <div class="bg-amber-50 border border-amber-100 rounded-2xl p-4 text-center">
                                    <p class="text-xs font-black text-slate-400 uppercase tracking-wider mb-1">Panjang</p>
                                    <p class="text-xl font-black text-navy-900 dark:text-white"><?php echo e(number_format($infrastruktur->panjang ?? 0, 1)); ?></p>
                                    <p class="text-xs text-slate-400 font-bold">meter</p>
                                </div>
                                <div class="bg-amber-50 border border-amber-100 rounded-2xl p-4 text-center">
                                    <p class="text-xs font-black text-slate-400 uppercase tracking-wider mb-1">Lebar</p>
                                    <p class="text-xl font-black text-navy-900 dark:text-white"><?php echo e(number_format($infrastruktur->lebar ?? 0, 1)); ?></p>
                                    <p class="text-xs text-slate-400 font-bold">meter</p>
                                </div>
                                <div class="bg-amber-50 border border-amber-100 rounded-2xl p-4 text-center">
                                    <p class="text-xs font-black text-slate-400 uppercase tracking-wider mb-1">Drainase</p>
                                    <?php if(($infrastruktur->has_drainase ?? 'tidak') == 'ya'): ?>
                                        <i class="fas fa-check-circle text-2xl text-emerald-500 my-1 block"></i>
                                        <p class="text-xs text-emerald-600 font-black uppercase">Ada</p>
                                    <?php else: ?>
                                        <i class="fas fa-times-circle text-2xl text-red-400 my-1 block"></i>
                                        <p class="text-xs text-red-500 font-black uppercase">Tidak Ada</p>
                                    <?php endif; ?>
                                </div>
                                <div class="bg-amber-50 border border-amber-100 rounded-2xl p-4 text-center">
                                    <p class="text-xs font-black text-slate-400 uppercase tracking-wider mb-1">Gorong-gorong</p>
                                    <?php if(($infrastruktur->has_gorong_gorong ?? 'tidak') == 'ya'): ?>
                                        <i class="fas fa-check-circle text-2xl text-emerald-500 my-1 block"></i>
                                        <p class="text-xs text-emerald-600 font-black uppercase">Ada</p>
                                    <?php else: ?>
                                        <i class="fas fa-times-circle text-2xl text-red-400 my-1 block"></i>
                                        <p class="text-xs text-red-500 font-black uppercase">Tidak Ada</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div>
                                <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1.5">Keterangan Tambahan Surveyor</p>
                                <div class="px-4 py-3 bg-amber-50 border border-amber-100 rounded-xl text-sm font-semibold text-slate-700 italic leading-relaxed">
                                    <?php if(strtolower($infrastruktur->kondisi ?? '') == 'menunggu ai'): ?>
                                        <span class="text-slate-400 font-medium">Kondisi akan ditentukan oleh sistem AI...</span>
                                    <?php else: ?>
                                        "<?php echo e($infrastruktur->kondisi ?? 'Tidak ada keterangan tambahan.'); ?>"
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Mini Map -->
                        <div class="relative rounded-[2rem] border border-slate-100 dark:border-white/10 shadow-inner overflow-hidden mb-8">
                            <div id="map" class="h-[280px] w-full z-0"></div>
                        </div>

                        <!-- Activity Log / Riwayat -->
                        <?php
                            $logs = \App\Models\ActivityLog::with('user')->where('reference_id', $infrastruktur->id_infrastruktur)->where('type', 'infrastruktur')->orderBy('created_at', 'desc')->get();
                        ?>
                        <div class="border-t border-slate-100 dark:border-white/10 pt-6">
                            <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-6 flex items-center gap-2">
                                <i class="fas fa-history text-slate-300"></i> Riwayat Aktivitas & Validasi
                            </p>
                            <?php if($logs->count() > 0): ?>
                                <div class="relative border-l-2 border-slate-200 dark:border-white/10 ml-3 md:ml-4 space-y-6">
                                    <?php $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="relative pl-6 md:pl-8">
                                        <div class="absolute -left-[9px] md:-left-[11px] top-1 w-4 h-4 md:w-5 md:h-5 rounded-full bg-gold-500 border-4 border-white dark:border-[#1e1b4b] shadow-sm"></div>
                                        <div class="bg-slate-50 dark:bg-[#0f0e2c] p-4 rounded-2xl border border-slate-100 dark:border-white/10">
                                            <div class="flex justify-between items-start mb-2">
                                                <p class="text-[10px] md:text-xs font-black text-navy-900 dark:text-white uppercase tracking-wider"><?php echo e($log->user->name ?? 'Sistem / Anonim'); ?></p>
                                                <p class="text-[9px] md:text-[10px] font-bold text-slate-400"><?php echo e($log->created_at->translatedFormat('d M Y, H:i')); ?></p>
                                            </div>
                                            <p class="text-xs md:text-sm text-slate-600 dark:text-slate-300 font-medium"><?php echo e($log->description); ?></p>
                                        </div>
                                    </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            <?php else: ?>
                                <div class="text-center py-6 bg-slate-50 dark:bg-[#0f0e2c] rounded-2xl border border-dashed border-slate-200 dark:border-white/10">
                                    <i class="fas fa-history text-2xl text-slate-300 mb-2 block"></i>
                                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Belum ada riwayat aktivitas</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
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

        const lat = <?php echo e($infrastruktur->latitude); ?>;
        const lng = <?php echo e($infrastruktur->longitude); ?>;
        const map = L.map('map', { zoomControl: true, scrollWheelZoom: false }).setView([lat, lng], 16);
        L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png').addTo(map);

        const condLower = "<?php echo e(strtolower($infrastruktur->kondisi ?? '')); ?>";
        let color = '#059669'; // default Baik
        if (condLower.includes('berat')) {
            color = '#be123c';
        } else if (condLower.includes('sedang') || condLower.includes('ringan')) {
            color = '#d97706';
        }
        
        const markerHtml = `<div style="background-color:${color};width:18px;height:18px;border-radius:50%;border:4px solid white;box-shadow:0 0 15px rgba(0,0,0,0.25);"></div>`;
        const icon = L.divIcon({ html: markerHtml, className: '', iconSize: [18,18], iconAnchor: [9,9] });
        L.marker([lat, lng], { icon }).addTo(map);
    </script>
</body>
</html>
<?php /**PATH C:\laragon1\laragon\www\geo-sinfra\resources\views\tim_teknis\show.blade.php ENDPATH**/ ?>