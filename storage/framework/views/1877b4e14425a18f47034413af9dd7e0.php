<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Infrastruktur | Admin SINFRA</title>
    <link rel="icon" href="<?php echo e(asset('logo_geo-sinfra.png')); ?>" type="image/png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

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

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }

        #mini-map { border: none !important; outline: none !important; }
        .leaflet-control-zoom { border: none !important; box-shadow: 0 4px 24px rgba(7,6,23,0.15) !important; border-radius: 0.75rem !important; overflow: hidden; }
        .leaflet-control-zoom a { width: 32px !important; height: 32px !important; line-height: 32px !important; background: #0f0e2c !important; color: #c5a059 !important; border: none !important; border-bottom: 1px solid rgba(255,255,255,0.08) !important; font-weight: 900 !important; transition: background 0.2s !important; }
        .leaflet-control-zoom a:hover { background: #1e1b4b !important; color: #fff !important; }
        .leaflet-control-zoom-out { border-bottom: none !important; }

        @keyframes scan {
            0% { transform: translateY(-100%); }
            100% { transform: translateY(400%); }
        }
    </style>
<style>
    @media (min-width: 768px) { html { font-size: 14px; } }
    @media (max-width: 767px) { html { font-size: 12px; } }
</style>
</head>
<body class="bg-navy-50 dark:bg-navy-950 text-slate-800 dark:text-slate-200 antialiased flex overflow-hidden h-screen transition-colors duration-300">

    <?php echo $__env->make('admin.partials.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <main class="flex-1 flex flex-col h-screen overflow-hidden">
        <style>
            #mobile-menu-btn { display: none !important; }
        </style>

        
        <header class="bg-white/80 backdrop-blur-xl border-b border-slate-100 px-4  md:px-8 py-4 flex justify-between items-center z-40 shrink-0">
            <div class="flex items-center gap-4">
                <a href="<?php echo e(route('admin.infrastruktur')); ?>"
                   class="hidden md:flex w-10 h-10 bg-white border border-slate-100 rounded-xl flex items-center justify-center text-slate-400 hover:text-gold-500 hover:border-gold-500/30 hover:shadow-md transition-all group">
                    <i class="fas fa-arrow-left text-xs group-hover:-translate-x-1 transition-transform"></i>
                </a>
                <div>
                    <p class="text-xs font-black text-gold-500 uppercase tracking-wider mb-1">Administrator Portal</p>
                    <h2 class="text-xl font-black text-navy-900 dark:text-white leading-none">Detail <?php echo e(ucfirst($inf->jenis) ?? 'Infrastruktur'); ?></h2>
                </div>
            </div>

            <div class="flex items-center gap-4">
                
                <?php if(($inf->status_verifikasi ?? 'Pending') != 'Verified'): ?>
                    <form action="<?php echo e(route('admin.infrastruktur.verifikasi', $inf->id_infrastruktur)); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <button type="submit" onclick="return confirm('Verifikasi aset ini?')"
                            class="bg-emerald-500 hover:bg-emerald-600 text-white text-sm px-5 py-2.5 rounded-xl font-black shadow-md shadow-emerald-500/20 hover:shadow-emerald-500/30 transition flex items-center gap-2">
                            <i class="fas fa-check-double"></i> Verifikasi
                        </button>
                    </form>
                <?php else: ?>
                    <span class="flex items-center gap-2 bg-emerald-500 text-white px-4 py-2.5 rounded-xl text-xs font-black shadow-sm shadow-emerald-500/20">
                        <i class="fas fa-check-double text-white"></i> Terverifikasi
                    </span>
                <?php endif; ?>

                <div class="h-8 w-[1px] bg-slate-100"></div>

                
                <div class="text-right">
                    <?php if(($inf->status_verifikasi ?? 'Pending') != 'Verified'): ?>
                        <p class="text-xs font-black text-navy-900 dark:text-white" id="mini-clock">00:00 WITA</p>
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-tighter"><?php echo e(now()->translatedFormat('d M Y')); ?></p>
                    <?php else: ?>
                        <p class="text-sm font-black text-navy-900"><?php echo e(\Carbon\Carbon::parse($inf->updated_at)->translatedFormat('H:i')); ?> WITA</p>
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-tighter"><?php echo e(\Carbon\Carbon::parse($inf->updated_at)->translatedFormat('l, d F Y')); ?></p>
                    <?php endif; ?>
                </div>


                <div class="h-8 w-[1px] bg-slate-100"></div>
                <div class="flex items-center gap-3">
                    <a href="<?php echo e(route('admin.profile')); ?>" class="text-right group hidden md:block">
                        <p class="text-sm font-black text-navy-900 dark:text-white leading-none uppercase group-hover:text-gold-500 transition-all max-w-[100px] sm:max-w-[150px] md:max-w-[300px] truncate"><?php echo e(auth()->user()->name); ?></p>
                        <p class="text-[10px] md:text-xs font-bold text-emerald-500 uppercase mt-0.5">Online</p>
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

        
        <div class="flex-1 overflow-y-auto custom-scrollbar p-8 pb-16">

            
            <?php if(session('success')): ?>
            <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 rounded-2xl flex items-center gap-3">
                <i class="fas fa-check-circle text-emerald-500 text-xl"></i>
                <p class="text-sm font-bold text-emerald-700"><?php echo e(session('success')); ?></p>
            </div>
            <?php endif; ?>

            
            <div class="flex flex-wrap items-center gap-3 mb-6">
                <span class="px-3 py-1.5 bg-navy-900 text-gold-500 rounded-xl text-xs font-black tracking-widest uppercase">
                    <i class="fas fa-hashtag mr-1"></i> INF-<?php echo e($inf->id_infrastruktur); ?>

                </span>
                <span class="px-3 py-1.5 bg-gold-500/10 text-gold-600 border border-gold-500/20 rounded-xl text-xs font-black tracking-widest uppercase">
                    <?php echo e(strtoupper(ucfirst($inf->jenis) ?? 'Infrastruktur')); ?>

                </span>
                <?php
                    $statusMap = [
                        'baik'         => 'bg-emerald-50 text-emerald-600 border-emerald-200',
                        'rusak sedang' => 'bg-orange-50 text-orange-600 border-orange-200',
                        'rusak berat'  => 'bg-red-50 text-red-600 border-red-200',
                    ];
                    $statusClass = $statusMap[strtolower($inf->kondisi ?? '')] ?? 'bg-slate-50 text-slate-500 border-slate-200';
                ?>
                <span class="px-3 py-1.5 border rounded-xl text-xs font-black tracking-widest uppercase <?php echo e($statusClass); ?>">
                    <?php echo e(strtoupper($inf->kondisi ?? 'Pending')); ?>

                </span>
                <span class="text-xs text-slate-400 font-semibold">
                    <i class="fas fa-user-circle mr-1"></i> Surveyor: <?php echo e($inf->nama_user ?? 'Tidak diketahui'); ?>

                </span>
            </div>

            <?php
                $hasilAi  = \Illuminate\Support\Facades\DB::table('analisis_ai')->where('id_infrastruktur', $inf->id_infrastruktur)->first();
                $hasilCnn = \Illuminate\Support\Facades\DB::table('citra_cnn')->where('id_infrastruktur', $inf->id_infrastruktur)->first();
                $cleanPath = $inf->foto_terbaru ? str_replace('\\', '/', $inf->foto_terbaru) : null;
                $fotoUrl   = $cleanPath ? asset('storage/' . (str_contains($cleanPath, 'infrastruktur/') ? $cleanPath : 'infrastruktur/' . $cleanPath)) : null;
            ?>

            <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

                
                <div class="xl:col-span-2 space-y-6">

                    
                    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-8">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-8 h-8 bg-navy-900 rounded-xl flex items-center justify-center text-gold-500 shrink-0">
                                <i class="fas fa-info-circle text-xs"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-black text-navy-900 uppercase tracking-wider">Identitas & Wilayah</h4>
                                <p class="text-xs text-slate-400 font-semibold mt-0.5">Informasi dasar aset infrastruktur</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            
                            <div class="md:col-span-2">
                                <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1.5">Nama Infrastruktur</p>
                                <div class="px-4 py-3 bg-slate-50 border border-slate-100 rounded-xl text-sm font-black text-navy-900">
                                    <?php echo e($inf->nama_objek ?? $inf->nama_infrastruktur); ?>

                                </div>
                            </div>
                            
                            <div>
                                <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1.5">Jenis Infrastruktur</p>
                                <div class="px-4 py-3 bg-slate-50 border border-slate-100 rounded-xl flex items-center gap-2">
                                    <span class="px-2 py-0.5 bg-navy-900 text-gold-500 rounded-md text-[7px] font-black tracking-wider uppercase">AI</span>
                                    <span class="text-sm font-black text-navy-900 uppercase"><?php echo e(ucfirst($inf->jenis) ?? '—'); ?></span>
                                </div>
                            </div>
                            
                            <div>
                                <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1.5">Material Utama</p>
                                <div class="px-4 py-3 bg-slate-50 border border-slate-100 rounded-xl text-sm font-semibold text-slate-700">
                                    <?php echo e($inf->material_eksisting ?? '—'); ?>

                                </div>
                            </div>
                            
                            <div>
                                <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1.5">Kecamatan</p>
                                <div class="px-4 py-3 bg-slate-50 border border-slate-100 rounded-xl text-sm font-semibold text-slate-700 flex items-center gap-2">
                                    <i class="fas fa-map-marker-alt text-gold-500 text-xs"></i>
                                    <?php echo e($inf->nama_kecamatan ?? '—'); ?>

                                </div>
                            </div>
                            
                            <div>
                                <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1.5">Kelurahan</p>
                                <div class="px-4 py-3 bg-slate-50 border border-slate-100 rounded-xl text-sm font-semibold text-slate-700 flex items-center gap-2">
                                    <i class="fas fa-map-marker-alt text-gold-500 text-xs"></i>
                                    <?php echo e($inf->nama_kelurahan ?? '—'); ?>

                                </div>
                            </div>
                        </div>
                    </div>

                    
                    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-8">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-8 h-8 bg-gold-500 rounded-xl flex items-center justify-center text-white shrink-0">
                                <i class="fas fa-ruler-combined text-xs"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-black text-navy-900 uppercase tracking-wider">Detail Teknis</h4>
                                <p class="text-xs text-slate-400 font-semibold mt-0.5">Dimensi, kondisi, dan parameter lapangan</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-5">
                            <div class="bg-amber-50 border border-amber-100 rounded-2xl p-4 text-center">
                                <p class="text-xs font-black text-slate-400 uppercase tracking-wider mb-1">Panjang</p>
                                <p class="text-xl font-black text-navy-900"><?php echo e(number_format($inf->panjang ?? 0, 1)); ?></p>
                                <p class="text-xs text-slate-400 font-bold">meter</p>
                            </div>
                            <div class="bg-amber-50 border border-amber-100 rounded-2xl p-4 text-center">
                                <p class="text-xs font-black text-slate-400 uppercase tracking-wider mb-1">Lebar</p>
                                <p class="text-xl font-black text-navy-900"><?php echo e(number_format($inf->lebar ?? 0, 1)); ?></p>
                                <p class="text-xs text-slate-400 font-bold">meter</p>
                            </div>
                            <div class="bg-amber-50 border border-amber-100 rounded-2xl p-4 text-center">
                                <p class="text-xs font-black text-slate-400 uppercase tracking-wider mb-1">Drainase</p>
                                <?php if(($inf->has_drainase ?? 'tidak') == 'ya'): ?>
                                    <i class="fas fa-check-circle text-2xl text-emerald-500 my-1 block"></i>
                                    <p class="text-xs text-emerald-600 font-black uppercase">Ada</p>
                                <?php else: ?>
                                    <i class="fas fa-times-circle text-2xl text-red-400 my-1 block"></i>
                                    <p class="text-xs text-red-500 font-black uppercase">Tidak Ada</p>
                                <?php endif; ?>
                            </div>
                            <div class="bg-amber-50 border border-amber-100 rounded-2xl p-4 text-center">
                                <p class="text-xs font-black text-slate-400 uppercase tracking-wider mb-1">Gorong-gorong</p>
                                <?php if(($inf->has_gorong_gorong ?? 'tidak') == 'ya'): ?>
                                    <i class="fas fa-check-circle text-2xl text-emerald-500 my-1 block"></i>
                                    <p class="text-xs text-emerald-600 font-black uppercase">Ada</p>
                                <?php else: ?>
                                    <i class="fas fa-times-circle text-2xl text-red-400 my-1 block"></i>
                                    <p class="text-xs text-red-500 font-black uppercase">Tidak Ada</p>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div>
                            <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1.5">Deskripsi Kondisi Lapangan</p>
                            <div class="px-4 py-3 bg-amber-50 border border-amber-100 rounded-xl text-sm font-semibold text-slate-700 italic leading-relaxed">
                                <?php if(strtolower($inf->kondisi ?? '') == 'menunggu ai'): ?>
                                    <div class="flex items-center gap-2 text-amber-600 mb-2 not-italic">
                                        <i class="fas fa-exclamation-triangle text-xs"></i>
                                        <span class="text-xs font-black uppercase tracking-widest">Deskripsi Belum Lengkap</span>
                                    </div>
                                    <p class="text-xs text-slate-400 not-italic">Silakan edit dan masukkan kata kunci kerusakan agar Decision Tree dapat memberikan skor akurat.</p>
                                <?php else: ?>
                                    "<?php echo e($inf->kondisi ?? '—'); ?>"
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    
                    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-8">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-8 h-8 bg-navy-500 rounded-xl flex items-center justify-center text-white shrink-0">
                                <i class="fas fa-map-marker-alt text-xs"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-black text-navy-900 uppercase tracking-wider">Lokasi Geografis</h4>
                                <p class="text-xs text-slate-400 font-semibold mt-0.5">Koordinat dan visualisasi peta</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-5">
                            <div>
                                <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1.5">Latitude</p>
                                <div class="px-4 py-3 bg-slate-50 border border-slate-100 rounded-xl text-sm font-mono font-bold text-navy-900">
                                    <?php echo e($inf->latitude ?? '—'); ?>

                                </div>
                            </div>
                            <div>
                                <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1.5">Longitude</p>
                                <div class="px-4 py-3 bg-slate-50 border border-slate-100 rounded-xl text-sm font-mono font-bold text-navy-900">
                                    <?php echo e($inf->longitude ?? '—'); ?>

                                </div>
                            </div>
                        </div>

                        <div id="mini-map" class="w-full rounded-2xl overflow-hidden" style="height:260px;"></div>
                    </div>

                    
                    <div class="bg-navy-900 rounded-3xl p-8 relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-64 h-64 bg-gold-500/5 rounded-full -mr-20 -mt-20 blur-3xl pointer-events-none"></div>
                        <div class="absolute bottom-0 left-0 w-48 h-48 bg-navy-500/10 rounded-full -ml-10 -mb-10 blur-2xl pointer-events-none"></div>

                        <div class="flex items-center gap-3 mb-6 relative">
                            <div class="w-10 h-10 bg-gold-500/20 rounded-xl flex items-center justify-center">
                                <i class="fas fa-brain text-gold-500"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-black text-white uppercase tracking-wider">Hybrid AI Analytics</h4>
                                <p class="text-xs text-slate-400 font-semibold mt-0.5">Decision Tree + CNN Vision Integration</p>
                            </div>
                            <span class="ml-auto px-3 py-1.5 bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 rounded-xl text-xs font-black uppercase tracking-wider">
                                <i class="fas fa-shield-alt mr-1"></i> Terverifikasi
                            </span>
                        </div>

                        <?php if($hasilAi || $hasilCnn): ?>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-5 relative">
                            
                            <div class="bg-white/5 border border-white/10 rounded-2xl p-5">
                                <div class="flex justify-between items-center mb-3">
                                    <span class="px-2 py-1 bg-navy-500/30 text-navy-100 rounded-lg text-[7px] font-black tracking-wider uppercase">Visual CNN</span>
                                    <i class="fas fa-eye text-slate-500 text-sm"></i>
                                </div>
                                <p class="text-4xl font-black text-white mb-1">
                                    <?php echo e($hasilCnn ? round($hasilCnn->skor_cnn * 100) : 0); ?><span class="text-sm font-bold text-slate-400 ml-1">%</span>
                                </p>
                                <p class="text-xs font-black text-gold-500 uppercase tracking-wider mb-3"><?php echo e($hasilCnn->label_kondisi ?? 'Scanning...'); ?></p>
                                <div class="w-full bg-white/10 h-1.5 rounded-full overflow-hidden">
                                    <div class="bg-gradient-to-r from-navy-500 to-gold-500 h-full rounded-full transition-all duration-1000"
                                         style="width: <?php echo e($hasilCnn ? ($hasilCnn->skor_cnn * 100) : 0); ?>%"></div>
                                </div>
                            </div>

                            
                            <div class="bg-white/5 border border-white/10 rounded-2xl p-5">
                                <div class="flex justify-between items-center mb-3">
                                    <span class="px-2 py-1 bg-gold-500/20 text-gold-400 rounded-lg text-[7px] font-black tracking-wider uppercase">Decision Tree</span>
                                    <i class="fas fa-project-diagram text-slate-500 text-sm"></i>
                                </div>
                                <p class="text-4xl font-black text-white mb-1">
                                    <?php echo e($hasilAi->skor_dt ?? 0); ?><span class="text-sm font-bold text-slate-400 ml-1">/100</span>
                                </p>
                                <?php
                                    $labelPrio = $hasilAi->label_prioritas ?? 'Pending';
                                    $prioColor = str_contains(strtolower($labelPrio), 'berat') ? 'text-red-400' :
                                         (str_contains(strtolower($labelPrio), 'sedang') ? 'text-orange-400' : 'text-emerald-400');
                                ?>
                                <p class="text-xs font-black <?php echo e($prioColor); ?> uppercase tracking-wider mb-3"><?php echo e($labelPrio); ?></p>
                                <div class="w-full bg-white/10 h-1.5 rounded-full overflow-hidden">
                                    <div class="bg-gradient-to-r from-gold-500 to-gold-600 h-full rounded-full transition-all duration-1000"
                                         style="width: <?php echo e($hasilAi->skor_dt ?? 0); ?>%"></div>
                                </div>
                            </div>
                        </div>

                        
                        <div class="bg-gold-500/10 border border-gold-500/20 rounded-2xl p-5 relative">
                            <div class="flex items-start gap-4">
                                <div class="w-9 h-9 bg-gold-500/20 rounded-xl flex items-center justify-center shrink-0">
                                    <i class="fas fa-lightbulb text-gold-500"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-black text-gold-500 uppercase tracking-widest mb-1.5">Rekomendasi AI</p>
                                    <p class="text-sm font-semibold text-slate-300 italic leading-relaxed">
                                        "<?php echo e($hasilAi->rekomendasi ?? 'Melakukan kalkulasi aturan Decision Tree...'); ?>"
                                    </p>
                                </div>
                            </div>
                        </div>

                        <?php else: ?>
                        <div class="bg-white/5 border border-dashed border-white/20 rounded-2xl p-10 text-center">
                            <i class="fas fa-microchip text-4xl text-slate-500 mb-3 block animate-pulse"></i>
                            <h4 class="font-black text-white text-sm mb-1">Sedang Sinkronisasi AI...</h4>
                            <p class="text-xs text-slate-400 max-w-xs mx-auto leading-relaxed">Sistem sedang melakukan analisis. Muat ulang halaman dalam beberapa saat.</p>
                        </div>
                        <?php endif; ?>
                    </div>

                    
                    <?php if($inf->alasan_penolakan): ?>
                    <div class="bg-amber-50 rounded-[2.5rem] p-8 border border-amber-100 shadow-sm relative overflow-hidden mt-6">
                        <div class="absolute top-0 right-0 w-24 h-24 bg-amber-500/5 rounded-bl-full"></div>
                        <h4 class="text-sm font-black text-amber-900 uppercase tracking-widest mb-3 flex items-center gap-2">
                            <i class="fas fa-comment-dots text-amber-500"></i> Catatan Eksekutif (Tim Teknis)
                        </h4>
                        <div class="p-5 bg-white/60 rounded-2xl border border-amber-200/50">
                            <p class="text-sm font-bold text-slate-600 leading-relaxed"><?php echo e($inf->alasan_penolakan); ?></p>
                        </div>
                    </div>
                    <?php endif; ?>

                </div>

                
                <div class="space-y-6">

                    
                    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-8 h-8 bg-emerald-500 rounded-xl flex items-center justify-center text-white shrink-0">
                                <i class="fas fa-camera text-xs"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-black text-navy-900 uppercase tracking-wider">Dokumentasi Visual</h4>
                                <p class="text-xs text-slate-400 font-semibold mt-0.5">Foto survei lapangan</p>
                            </div>
                        </div>

                        <div class="relative rounded-2xl overflow-hidden bg-navy-950 aspect-[3/4] w-full flex items-center justify-center group">
                            <?php if($fotoUrl): ?>
                                <img src="<?php echo e($fotoUrl); ?>" alt="Foto Infrastruktur"
                                     class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">

                                
                                <?php if(!in_array(strtolower($inf->kondisi ?? ''), ['baik','menunggu ai'])): ?>
                                <div class="absolute inset-0 flex items-center justify-center pointer-events-none p-6">
                                    <div class="relative w-[50%] h-[50%] border-2 border-red-500/60 bg-red-500/5 animate-pulse">
                                        <div class="absolute -top-1 -left-1 w-4 h-4 border-t-4 border-l-4 border-red-500"></div>
                                        <div class="absolute -top-1 -right-1 w-4 h-4 border-t-4 border-r-4 border-red-500"></div>
                                        <div class="absolute -bottom-1 -left-1 w-4 h-4 border-b-4 border-l-4 border-red-500"></div>
                                        <div class="absolute -bottom-1 -right-1 w-4 h-4 border-b-4 border-r-4 border-red-500"></div>
                                        <div class="absolute -top-7 left-0 bg-red-600 text-white text-xs font-black px-2 py-1 rounded flex items-center gap-1">
                                            <i class="fas fa-exclamation-triangle"></i>
                                            KERUSAKAN <?php echo e(round(($hasilCnn->skor_cnn ?? 0) * 100)); ?>%
                                        </div>
                                        <div class="absolute inset-0 bg-gradient-to-b from-transparent via-red-500/20 to-transparent h-1/4 w-full" style="animation: scan 2s linear infinite;"></div>
                                    </div>
                                </div>
                                <?php endif; ?>

                                <div class="absolute inset-0 bg-black/50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                    <a href="<?php echo e($fotoUrl); ?>" target="_blank"
                                       class="bg-white text-navy-900 px-4 py-2 rounded-xl text-xs font-black shadow-xl uppercase tracking-widest hover:scale-105 transition-all flex items-center gap-2">
                                        <i class="fas fa-expand"></i> Lihat Full
                                    </a>
                                </div>
                            <?php else: ?>
                                <div class="text-center py-10">
                                    <i class="fas fa-image text-5xl text-slate-700 mb-3 block"></i>
                                    <p class="text-xs font-black text-slate-500 uppercase tracking-widest">Tidak Ada Foto</p>
                                </div>
                            <?php endif; ?>
                        </div>

                        <?php if($fotoUrl): ?>
                        <p class="text-xs text-slate-400 font-semibold mt-2 truncate">
                            <i class="fas fa-user-circle mr-1"></i> <?php echo e($inf->nama_user ?? 'Surveyor'); ?>

                        </p>
                        <?php endif; ?>
                    </div>

                    
                    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6">
                        <h5 class="text-xs font-black text-navy-900 uppercase tracking-widest mb-4">
                            <i class="fas fa-list-ul mr-1 text-gold-500"></i> Ringkasan Data
                        </h5>
                        <div class="space-y-3">
                            <?php
                                $rows = [
                                    ['label' => 'ID Aset',      'value' => 'INF-'.$inf->id_infrastruktur],
                                    ['label' => 'Status',       'value' => ($inf->status_verifikasi ?? 'Pending') == 'Verified' ? 'Terverifikasi' : 'Pending'],
                                    ['label' => 'Diverifikasi', 'value' => ($inf->status_verifikasi ?? 'Pending') == 'Verified' ? \Carbon\Carbon::parse($inf->updated_at)->translatedFormat('d M Y, H:i') . ' WITA' : '—'],
                                    ['label' => 'Admin',        'value' => ($inf->status_verifikasi ?? 'Pending') == 'Verified' ? 'Admin Online' : '—'],
                                    ['label' => 'Tgl Survey',   'value' => $inf->tgl_survey ? \Carbon\Carbon::parse($inf->tgl_survey)->translatedFormat('d M Y') : '-'],
                                    ['label' => 'Dibuat',       'value' => $inf->created_at ? \Carbon\Carbon::parse($inf->created_at)->translatedFormat('d M Y') : '-'],
                                    ['label' => 'CNN Label',    'value' => $hasilCnn->label_kondisi ?? '—'],
                                    ['label' => 'DT Prioritas', 'value' => $hasilAi->label_prioritas ?? '—'],
                                ];
                            ?>
                            <?php $__currentLoopData = $rows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="flex justify-between items-center py-2 border-b border-slate-50 last:border-0">
                                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider"><?php echo e($row['label']); ?></span>
                                <span class="text-xs font-black text-navy-900"><?php echo e($row['value']); ?></span>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>

                    
                    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6 space-y-3">
                        <a href="<?php echo e(route('admin.infrastruktur.edit', $inf->id_infrastruktur)); ?>"
                            class="w-full flex items-center justify-center gap-2 bg-gold-500 hover:bg-gold-600 text-white py-3.5 rounded-2xl font-black text-sm tracking-widest transition-all shadow-md shadow-gold-500/20 uppercase">
                            <i class="fas fa-edit"></i> Edit Data
                        </a>
                        <a href="<?php echo e(route('admin.infrastruktur.pdf', $inf->id_infrastruktur)); ?>"
                            class="w-full flex items-center justify-center gap-2 bg-navy-900 hover:bg-navy-950 text-white py-3.5 rounded-2xl font-black text-sm tracking-widest transition-all shadow-md shadow-navy-900/20 uppercase">
                            <i class="fas fa-file-pdf"></i> Export PDF
                        </a>
                        <a href="<?php echo e(route('admin.infrastruktur')); ?>"
                            class="w-full flex items-center justify-center gap-2 bg-slate-100 hover:bg-slate-200 text-slate-500 py-3.5 rounded-2xl font-black text-sm tracking-widest transition-all uppercase">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </main>

    <script>
        // Clock
        function updateClock() {
            const now = new Date();
            const options = { timeZone: 'Asia/Makassar', hour: '2-digit', minute: '2-digit', hour12: false };
            const timeString = new Intl.DateTimeFormat('id-ID', options).format(now);
            const el = document.getElementById('mini-clock');
            if (el) el.textContent = timeString.replace('.', ':') + ' WITA';
        }
        setInterval(updateClock, 1000); updateClock();

        // Leaflet map
        const lat = <?php echo e($inf->latitude ?? -3.316694); ?>;
        const lng = <?php echo e($inf->longitude ?? 114.590111); ?>;
        const map = L.map('mini-map', { zoomControl: true, dragging: false, scrollWheelZoom: false }).setView([lat, lng], 16);
        L.tileLayer('https://{s}.google.com/vt/lyrs=s,h&x={x}&y={y}&z={z}', {
            maxZoom: 20, subdomains: ['mt0','mt1','mt2','mt3']
        }).addTo(map);

        <?php $prioritas = $hasilAi->label_prioritas ?? $inf->kondisi ?? 'Baik'; ?>
        const prioritas = '<?php echo e(strtolower($prioritas)); ?>';
        let markerColor = '#10b981';
        if (prioritas.includes('berat'))  markerColor = '#ef4444';
        else if (prioritas.includes('sedang')) markerColor = '#f97316';

        const icon = L.divIcon({
            html: `<div style="background:${markerColor};width:16px;height:16px;border-radius:50%;border:3px solid white;box-shadow:0 4px 12px rgba(0,0,0,0.3);"></div>`,
            className: '', iconSize: [16,16], iconAnchor: [8,8]
        });

        L.marker([lat, lng], { icon }).addTo(map)
            .bindPopup(`<div style="font-family:'Plus Jakarta Sans',sans-serif;min-width:140px;">
                <p style="font-size:9px;font-weight:900;color:#0f0e2c;text-transform:uppercase;margin-bottom:2px;"><?php echo e($inf->nama_objek ?? $inf->nama_infrastruktur); ?></p>
                <p style="font-size:8px;color:#c5a059;font-weight:700;text-transform:uppercase;"><?php echo e($inf->jenis ?? '—'); ?></p>
            </div>`, { maxWidth: 200 })
            .openPopup();
    </script>
</body>
</html>

<?php /**PATH C:\laragon1\laragon\www\geo-sinfra\resources\views\admin\detail-infrastruktur.blade.php ENDPATH**/ ?>