<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Admin SINFRA</title>
    <link rel="icon" href="<?php echo e(asset('logo_geo-sinfra.png')); ?>" type="image/png">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                    },
                    colors: {
                        navy: {
                            50: '#f4f4fa',
                            100: '#e9e9f3',
                            200: '#c7c8e3',
                            500: '#6366f1',
                            800: '#1e1b4b',
                            900: '#0f0e2c',
                            950: '#070617',
                        },
                        gold: {
                            50: '#fdfbf7',
                            100: '#fbf7ed',
                            500: '#c5a059',
                            600: '#b38f4a',
                            700: '#9d7c3d',
                        }
                    }
                }
            }
        }
    </script>
    
    <!-- Theme Switcher Init Script (To Prevent Flicker) -->
    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark')
        } else {
            document.documentElement.classList.remove('dark')
        }
    </script>

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        
        /* Animasi Latar Belakang Banner */
        .bg-pattern {
            background-image: radial-gradient(circle at 2px 2px, rgba(255,255,255,0.06) 1px, transparent 0);
            background-size: 24px 24px;
        }
        .bg-premium-mesh {
            background: radial-gradient(circle at 80% 20%, rgba(99, 102, 241, 0.15) 0%, transparent 50%),
                        radial-gradient(circle at 20% 80%, rgba(197, 160, 89, 0.12) 0%, transparent 50%),
                        #070617;
        }
        /* Global Transitions */
        html { transition: background-color 0.3s ease, color 0.3s ease; }
    </style>
</head>
<body class="bg-navy-50 dark:bg-navy-950 text-slate-800 dark:text-slate-200 antialiased selection:bg-gold-500 selection:text-white flex overflow-hidden h-screen transition-colors duration-300">

    <?php echo $__env->make('admin.partials.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <main class="flex-1 overflow-y-auto custom-scrollbar text-left">
        <header class="sticky top-0 bg-white/80 dark:bg-navy-950/80 backdrop-blur-xl border-b border-slate-100 dark:border-white/5 px-8 py-5 flex justify-between items-center z-40 text-left transition-colors duration-300">
            <div class="text-left">
                <p class="text-[10px] font-black text-gold-500 uppercase tracking-[0.2em] mb-1">Administrator Portal</p>
                <h2 class="text-xl font-black text-navy-900 dark:text-white leading-none">Beranda Utama</h2>
            </div>

            <div class="flex items-center gap-6">
                <div class="text-right hidden sm:block">
                    <p class="text-[11px] font-black text-navy-900 dark:text-white" id="mini-clock">00:00 WITA</p>
                    <p class="text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-tighter"><?php echo e(now()->translatedFormat('l, d F Y')); ?></p>
                </div>
                <div class="h-8 w-[1px] bg-slate-100 dark:bg-white/10"></div>
                <div class="flex items-center gap-3">
                    <a href="<?php echo e(route('admin.profile')); ?>" class="text-right group">
                        <p class="text-[11px] font-black text-navy-900 dark:text-white leading-none uppercase group-hover:text-gold-500 transition-all"><?php echo e(auth()->user()->name); ?></p>
                        <p class="text-[9px] font-bold text-emerald-500 uppercase mt-1">Online</p>
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

        <div class="p-8 text-left">
            <?php if(session('success')): ?>
            <div class="mb-6 px-6 py-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-2xl flex items-center gap-3 shadow-sm">
                <i class="fas fa-check-circle"></i>
                <p class="text-xs font-bold"><?php echo e(session('success')); ?></p>
            </div>
            <?php endif; ?>
            
            <!-- Welcome Banner (Premium Dark Mesh UI) -->
            <div class="relative bg-premium-mesh rounded-[2.5rem] p-10 mb-8 overflow-hidden shadow-2xl shadow-navy-950/20 border border-white/5 text-left">
                <div class="absolute inset-0 bg-pattern opacity-50"></div>
                <div class="absolute -right-10 -top-10 w-64 h-64 bg-white opacity-5 rounded-full blur-3xl"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6 text-left">
                    <div class="text-left">
                        <h3 class="text-3xl font-black text-white mb-2 leading-tight">Selamat Datang, Administrator!</h3>
                        <p class="text-slate-300 text-sm font-medium max-w-xl text-left">Pusat kendali manajemen infrastruktur dan pengguna Sistem Pemetaan Infrastruktur Permukiman Kota Banjarmasin. Apa yang ingin Anda kerjakan hari ini?</p>
                    </div>
                    <div class="hidden md:block">
                        <div class="w-20 h-20 bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl flex items-center justify-center shadow-2xl text-gold-500">
                            <i class="fas fa-shield-alt text-4xl"></i>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Status Infrastruktur Kota & Rekomendasi AI -->
            <div class="mb-8 grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Status Kesehatan Infrastruktur Kota -->
                <div class="lg:col-span-2 bg-gradient-to-br from-[#0f0e2c] to-navy-900 rounded-[2.5rem] p-8 border border-white/10 shadow-xl relative overflow-hidden flex flex-col sm:flex-row justify-between items-center gap-6">
                    <div class="absolute -right-10 -top-10 w-40 h-40 bg-gold-500/20 rounded-full blur-3xl pointer-events-none"></div>
                    
                    <div class="flex items-center gap-6 relative z-10 text-left w-full">
                        <div class="w-20 h-20 bg-white/5 backdrop-blur-md rounded-3xl flex items-center justify-center text-gold-500 border border-white/10 shadow-inner shrink-0">
                            <i class="fas fa-city text-4xl"></i>
                        </div>
                        <div class="text-left">
                            <div class="flex items-center gap-2 mb-1.5">
                                <span class="px-2.5 py-1 bg-emerald-500/20 border border-emerald-500/30 text-emerald-400 rounded-md text-[8px] font-black uppercase tracking-widest shadow-sm">Sistem Pemetaan Aktif</span>
                                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Banjarmasin, Kalsel</span>
                            </div>
                            <h4 class="text-2xl font-black text-white leading-tight"><?php echo e(number_format($totalInfrastruktur)); ?> Aset Diawasi</h4>
                            <p class="text-[11px] text-slate-300 font-medium mt-1">Total infrastruktur di Kota Banjarmasin yang terdata dan dianalisis oleh AI (<?php echo e($persenDianalisis); ?>% teranalisis).</p>
                        </div>
                    </div>
                    
                    <div class="flex sm:flex-col justify-between sm:justify-center gap-6 sm:gap-3 relative z-10 shrink-0 bg-white/5 p-4 rounded-2xl border border-white/10 backdrop-blur-sm w-full sm:w-auto">
                        <div class="text-center sm:text-right">
                            <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Rusak Berat</p>
                            <p class="text-xl font-black text-red-500"><?php echo e(number_format($rusakBerat)); ?> <span class="text-sm text-red-400"><i class="fas fa-exclamation-triangle"></i></span></p>
                        </div>
                        <div class="w-px sm:w-full h-10 sm:h-px bg-white/10 my-auto"></div>
                        <div class="text-center sm:text-right">
                            <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Kondisi Baik</p>
                            <p class="text-xl font-black text-emerald-400"><?php echo e(number_format($kondisiBaik)); ?> <span class="text-sm text-emerald-300"><i class="fas fa-check-circle"></i></span></p>
                        </div>
                    </div>
                </div>

                <!-- Rekomendasi Prioritas AI -->
                <div class="lg:col-span-1 flex flex-col justify-center bg-white dark:bg-[#1e1b4b] rounded-[2.5rem] p-7 border border-slate-100 dark:border-white/5 shadow-xl shadow-slate-200/50 dark:shadow-black/50 relative overflow-hidden group transition-colors duration-300 text-left">
                    <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-red-500/10 dark:bg-red-500/20 rounded-full blur-3xl group-hover:bg-red-500/20 transition-all duration-500"></div>
                    <div class="w-12 h-12 bg-gold-500/10 text-gold-500 rounded-2xl flex items-center justify-center mb-4 border border-gold-500/20 shadow-sm">
                        <i class="fas fa-robot text-xl animate-pulse"></i>
                    </div>
                    <h5 class="font-black text-navy-900 dark:text-white mb-2">Rekomendasi Prioritas AI</h5>
                    <?php if($rekomendasi): ?>
                        <h5 class="text-sm font-black text-navy-900 dark:text-white mt-4 mb-2 line-clamp-1 leading-snug"><?php echo e($rekomendasi->nama_objek ?? $rekomendasi->nama_infrastruktur); ?></h5>
                        <p class="text-[11px] font-bold text-slate-500 dark:text-slate-400 mb-6 flex items-center gap-1.5"><i class="fas fa-map-marker-alt text-slate-400"></i> Kelurahan <?php echo e($rekomendasi->nama_kelurahan); ?></p>
                        <a href="<?php echo e(route('admin.infrastruktur.show', $rekomendasi->id_infrastruktur)); ?>" class="inline-flex items-center justify-center gap-2 w-full bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 py-3 rounded-xl text-xs font-black hover:bg-red-100 dark:hover:bg-red-500/20 transition-colors uppercase tracking-wider">
                            Lihat Detail <i class="fas fa-arrow-right"></i>
                        </a>
                    <?php else: ?>
                        <div class="w-12 h-12 bg-emerald-50 dark:bg-emerald-500/10 text-emerald-500 rounded-xl flex items-center justify-center mb-4 border border-emerald-100 dark:border-emerald-500/20 shadow-sm">
                            <i class="fas fa-check-circle text-lg"></i>
                        </div>
                        <h5 class="text-sm font-black text-navy-900 dark:text-white mt-4 mb-2 leading-snug">Semua Aman</h5>
                        <p class="text-[11px] font-bold text-slate-500 dark:text-slate-400 mb-6">Tidak ada infrastruktur dengan prioritas rusak berat saat ini.</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Quick Access Cards -->
            <div class="mb-8 text-left">
                <h4 class="font-extrabold text-lg text-navy-900 mb-6">Akses Cepat</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 text-left">
                    
                    <button onclick="openQuickModal('Tambah User', 'Daftarkan Surveyor atau Admin baru ke dalam sistem untuk memperluas tim operasional.', '<?php echo e(route('admin.users.create')); ?>', 'fa-user-plus', 'bg-blue-50 text-blue-500 border border-blue-100')" class="group bg-white p-6 rounded-3xl border border-slate-100 shadow-sm hover:shadow-2xl hover:shadow-navy-950/5 hover:border-gold-500/50 transition-all text-left w-full relative">
                        <div class="absolute top-4 right-4 bg-blue-50 text-blue-500 border border-blue-100 text-[9px] font-black px-2 py-1 rounded-lg"><?php echo e(number_format($totalUser)); ?> User</div>
                        <div class="w-12 h-12 bg-navy-50 text-gold-500 border border-navy-100 rounded-2xl flex items-center justify-center mb-4 group-hover:scale-105 transition-all">
                            <i class="fas fa-user-plus text-lg"></i>
                        </div>
                        <h5 class="font-black text-navy-900 mb-1">Tambah User</h5>
                        <p class="text-[10px] text-slate-400 font-semibold leading-relaxed text-left">Daftarkan Surveyor atau Admin baru ke dalam sistem.</p>
                    </button>

                    <button onclick="openQuickModal('Kelola Wilayah', 'Tambahkan atau edit data master wilayah kecamatan dan kelurahan untuk pemetaan.', '<?php echo e(route('admin.wilayah')); ?>', 'fa-sitemap', 'bg-emerald-50 text-emerald-500 border border-emerald-100')" class="group bg-white p-6 rounded-3xl border border-slate-100 shadow-sm hover:shadow-2xl hover:shadow-navy-950/5 hover:border-gold-500/50 transition-all text-left w-full relative">
                        <div class="absolute top-4 right-4 bg-emerald-50 text-emerald-500 border border-emerald-100 text-[9px] font-black px-2 py-1 rounded-lg"><?php echo e(number_format($totalWilayah)); ?> Area</div>
                        <div class="w-12 h-12 bg-navy-50 text-gold-500 border border-navy-100 rounded-2xl flex items-center justify-center mb-4 group-hover:scale-105 transition-all">
                            <i class="fas fa-sitemap text-lg"></i>
                        </div>
                        <h5 class="font-black text-navy-900 mb-1">Kelola Wilayah</h5>
                        <p class="text-[10px] text-slate-400 font-semibold leading-relaxed text-left">Kelola data master wilayah kecamatan dan kelurahan.</p>
                    </button>

                    <button onclick="openQuickModal('Statistik Data', 'Lihat laporan analitik AI, kurva-S, dan distribusi prioritas infrastruktur kota.', '<?php echo e(route('admin.statistik')); ?>', 'fa-chart-pie', 'bg-purple-50 text-purple-500 border border-purple-100')" class="group bg-white p-6 rounded-3xl border border-slate-100 shadow-sm hover:shadow-2xl hover:shadow-navy-950/5 hover:border-gold-500/50 transition-all text-left w-full relative">
                        <div class="absolute top-4 right-4 bg-purple-50 text-purple-500 border border-purple-100 text-[9px] font-black px-2 py-1 rounded-lg"><?php echo e($persenDianalisis); ?>% AI</div>
                        <div class="w-12 h-12 bg-navy-50 text-gold-500 border border-navy-100 rounded-2xl flex items-center justify-center mb-4 group-hover:scale-105 transition-all">
                            <i class="fas fa-chart-pie text-lg"></i>
                        </div>
                        <h5 class="font-black text-navy-900 mb-1">Statistik Data</h5>
                        <p class="text-[10px] text-slate-400 font-semibold leading-relaxed text-left">Lihat rekapitulasi data dan prediksi prioritas harian.</p>
                    </button>

                    <button onclick="openQuickModal('Data Master', 'Telusuri, edit, atau hapus seluruh data survei infrastruktur beserta hasil AI.', '<?php echo e(route('admin.infrastruktur')); ?>', 'fa-database', 'bg-orange-50 text-orange-500 border border-orange-100')" class="group bg-white p-6 rounded-3xl border border-slate-100 shadow-sm hover:shadow-2xl hover:shadow-navy-950/5 hover:border-gold-500/50 transition-all text-left w-full relative">
                        <div class="absolute top-4 right-4 bg-orange-50 text-orange-500 border border-orange-100 text-[9px] font-black px-2 py-1 rounded-lg"><?php echo e(number_format($totalInfrastruktur)); ?> Aset</div>
                        <div class="w-12 h-12 bg-navy-50 text-gold-500 border border-navy-100 rounded-2xl flex items-center justify-center mb-4 group-hover:scale-105 transition-all">
                            <i class="fas fa-database text-lg"></i>
                        </div>
                        <h5 class="font-black text-navy-900 mb-1">Data Master</h5>
                        <p class="text-[10px] text-slate-400 font-semibold leading-relaxed text-left">Lihat dan kelola seluruh data infrastruktur lapangan.</p>
                    </button>

                    <button onclick="openQuickModal('Laporan Warga', 'Tindak lanjuti pengaduan warga terkait infrastruktur rusak dan tugaskan surveyor.', '<?php echo e(route('admin.laporan-warga')); ?>', 'fa-bullhorn', 'bg-red-50 text-red-500 border border-red-100')" class="group bg-white p-6 rounded-3xl border border-slate-100 shadow-sm hover:shadow-2xl hover:shadow-navy-950/5 hover:border-gold-500/50 transition-all text-left w-full relative">
                        <div class="absolute top-4 right-4 bg-red-50 text-red-500 border border-red-100 text-[9px] font-black px-2 py-1 rounded-lg"><?php echo e(number_format($totalLaporanWarga ?? 0)); ?> Laporan</div>
                        <div class="w-12 h-12 bg-navy-50 text-gold-500 border border-navy-100 rounded-2xl flex items-center justify-center mb-4 group-hover:scale-105 transition-all">
                            <i class="fas fa-bullhorn text-lg"></i>
                        </div>
                        <h5 class="font-black text-navy-900 mb-1">Laporan Warga</h5>
                        <p class="text-[10px] text-slate-400 font-semibold leading-relaxed text-left">Kelola dan tindak lanjuti laporan kerusakan dari masyarakat.</p>
                    </button>

                </div>
            </div>

            <!-- Security & Maintenance Section -->
            <div class="mb-8 text-left">
                <h4 class="font-extrabold text-lg text-navy-900 mb-6">Pemeliharaan & Keamanan</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-left">
                    
                    <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm flex items-center justify-between group hover:shadow-md transition-shadow">
                        <div class="flex items-center gap-6">
                            <div class="w-16 h-16 bg-blue-50 text-blue-500 rounded-2xl flex items-center justify-center border border-blue-100 shadow-sm group-hover:scale-105 transition-transform">
                                <i class="fas fa-database text-3xl"></i>
                            </div>
                            <div>
                                <h5 class="font-black text-navy-900 text-lg mb-1">Backup Database</h5>
                                <p class="text-[10px] text-slate-400 font-semibold leading-relaxed max-w-xs">Unduh salinan keamanan (dump) dari seluruh basis data koordinat dan infrastruktur saat ini.</p>
                            </div>
                        </div>
                        <button onclick="startBackup()" class="px-6 py-3 bg-navy-900 text-white rounded-xl text-xs font-black uppercase tracking-widest hover:bg-gold-500 hover:shadow-lg transition-all flex items-center gap-2">
                            <i class="fas fa-download"></i> Backup Sekarang
                        </button>
                    </div>

                    <a href="<?php echo e(route('admin.activity')); ?>" class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm flex items-center justify-between group hover:shadow-md transition-shadow">
                        <div class="flex items-center gap-6">
                            <div class="w-16 h-16 bg-emerald-50 text-emerald-500 rounded-2xl flex items-center justify-center border border-emerald-100 shadow-sm group-hover:scale-105 transition-transform">
                                <i class="fas fa-shield-alt text-3xl"></i>
                            </div>
                            <div>
                                <h5 class="font-black text-navy-900 text-lg mb-1">Audit Trail & Log</h5>
                                <p class="text-[10px] text-slate-400 font-semibold leading-relaxed max-w-xs">Pantau riwayat aktivitas pengguna, penambahan data, dan perubahan konfigurasi sistem.</p>
                            </div>
                        </div>
                        <div class="w-12 h-12 bg-slate-50 text-slate-400 rounded-xl flex items-center justify-center group-hover:bg-emerald-500 group-hover:text-white transition-colors">
                            <i class="fas fa-arrow-right"></i>
                        </div>
                    </a>

                </div>
            </div>

            <!-- Status Notice -->
            <div class="bg-navy-50 border border-navy-100/50 rounded-2xl p-6 flex items-center gap-4 text-left">
                <div class="w-10 h-10 bg-gold-500/10 text-gold-500 rounded-full flex items-center justify-center shrink-0 border border-gold-500/20">
                    <i class="fas fa-info text-xs"></i>
                </div>
                <div>
                    <p class="text-[11px] text-navy-900 font-semibold text-left">Sistem berjalan optimal. Hybrid Model (CNN) aktif dan siap memproses data survei terbaru.</p>
                </div>
            </div>

        </div>
    </main>

    <!-- Quick Access Modal -->
    <div id="quick-modal" class="fixed inset-0 z-[60] hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-4">
            <!-- Overlay -->
            <div class="fixed inset-0 bg-navy-900/60 backdrop-blur-sm transition-opacity" onclick="closeQuickModal()"></div>
            
            <!-- Modal Content -->
            <div class="relative bg-white rounded-[2.5rem] w-full max-w-md p-8 shadow-2xl transition-all scale-95 opacity-0 duration-300" id="quickModalContent">
                
                <div class="flex justify-between items-center mb-6 pb-4 border-b border-slate-100">
                    <div class="flex items-center gap-4">
                        <div id="qm-icon-bg" class="w-12 h-12 rounded-xl flex items-center justify-center shadow-sm">
                            <i id="qm-icon" class="text-xl"></i>
                        </div>
                        <div>
                            <h3 id="qm-title" class="text-lg font-black text-navy-900">Title</h3>
                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider mt-0.5">Akses Cepat</p>
                        </div>
                    </div>
                    <button onclick="closeQuickModal()" class="w-10 h-10 flex items-center justify-center bg-slate-50 text-slate-400 rounded-xl hover:bg-red-50 hover:text-red-500 transition-all shrink-0">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <p id="qm-desc" class="text-xs text-slate-500 font-medium leading-relaxed mb-8">Description here</p>
                
                <div class="flex gap-3">
                    <button type="button" onclick="closeQuickModal()" class="flex-1 py-4 bg-slate-100 text-slate-500 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-slate-200 transition-all">
                        Batal
                    </button>
                    <a id="qm-btn" href="#" class="flex-[2] py-4 flex items-center justify-center gap-2 bg-navy-900 text-white rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-gold-500 transition-all shadow-xl shadow-navy-900/10 group">
                        Lanjutkan <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- SweetAlert2 — dipindahkan ke sini, SETELAH semua HTML modal selesai -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function updateClock() {
            const now = new Date();
            const options = { timeZone: 'Asia/Makassar', hour: '2-digit', minute: '2-digit', hour12: false };
            const timeString = new Intl.DateTimeFormat('id-ID', options).format(now);
            const el = document.getElementById('mini-clock');
            if (el) el.textContent = timeString.replace('.', ':') + ' WITA';
        }
        setInterval(updateClock, 1000); updateClock();

        function startBackup() {
            Swal.fire({
                title: 'Mempersiapkan Backup',
                html: 'Mengekspor struktur database dan data infrastruktur...',
                timer: 2500,
                timerProgressBar: true,
                didOpen: () => {
                    Swal.showLoading()
                }
            }).then((result) => {
                // Buat form tersembunyi untuk trigger download file
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '<?php echo e(route("admin.backup")); ?>';
                
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '<?php echo e(csrf_token()); ?>';
                
                form.appendChild(csrfToken);
                document.body.appendChild(form);
                form.submit();
                document.body.removeChild(form);

                Swal.fire({
                    icon: 'success',
                    title: 'Backup Dimulai!',
                    text: 'Proses pengunduhan file SQL sedang berjalan di browser Anda.',
                    confirmButtonColor: '#6366f1'
                });
            })
        }

        function openQuickModal(title, desc, url, icon, colorClass) {
            document.getElementById('qm-title').innerText = title;
            document.getElementById('qm-desc').innerText = desc;
            document.getElementById('qm-btn').href = url;
            document.getElementById('qm-icon').className = `fas ${icon}`;
            document.getElementById('qm-icon-bg').className = `w-12 h-12 rounded-xl flex items-center justify-center shadow-sm shrink-0 ${colorClass}`;
            
            const modal = document.getElementById('quick-modal');
            const content = document.getElementById('quickModalContent');
            modal.classList.remove('hidden');
            
            // Add slight delay for animation
            requestAnimationFrame(() => {
                setTimeout(() => {
                    content.classList.remove('scale-95', 'opacity-0');
                    content.classList.add('scale-100', 'opacity-100');
                }, 10);
            });
        }

        function closeQuickModal() {
            const modal = document.getElementById('quick-modal');
            const content = document.getElementById('quickModalContent');
            content.classList.remove('scale-100', 'opacity-100');
            content.classList.add('scale-95', 'opacity-0');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }
    </script>
</body>
</html>
<?php /**PATH C:\laragon1\laragon\www\geo-sinfra\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>