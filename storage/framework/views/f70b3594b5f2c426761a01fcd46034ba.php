<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Admin SINFRA</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <script>
        tailwind.config = {
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
    </style>
</head>
<body class="bg-slate-50 flex h-screen overflow-hidden text-slate-800 text-left font-sans">

    <?php echo $__env->make('admin.partials.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <main class="flex-1 overflow-y-auto custom-scrollbar text-left">
        <header class="sticky top-0 bg-white/80 backdrop-blur-xl border-b border-slate-100 px-8 py-5 flex justify-between items-center z-40 text-left">
            <div class="text-left">
                <p class="text-[10px] font-black text-gold-500 uppercase tracking-[0.2em] mb-1">Administrator Portal</p>
                <h2 class="text-xl font-black text-navy-900 leading-none">Beranda Utama</h2>
            </div>

            <div class="flex items-center gap-6">
                <div class="text-right hidden sm:block">
                    <p class="text-[11px] font-black text-navy-900" id="mini-clock">00:00 WITA</p>
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-tighter"><?php echo e(now()->translatedFormat('l, d F Y')); ?></p>
                </div>
                <div class="h-8 w-[1px] bg-slate-100"></div>
                <div class="flex items-center gap-3">
                    <a href="<?php echo e(route('admin.profile')); ?>" class="text-right group">
                        <p class="text-[11px] font-black text-navy-900 leading-none uppercase group-hover:text-gold-500 transition-all"><?php echo e(auth()->user()->name); ?></p>
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

                <!-- Action Suggestion -->
                <div class="lg:col-span-1 bg-white rounded-[2.5rem] p-8 border border-slate-100 shadow-sm flex flex-col justify-center relative overflow-hidden text-left hover:shadow-md transition-shadow">
                    <div class="absolute -right-5 -bottom-5 w-32 h-32 bg-orange-500/10 rounded-full blur-2xl pointer-events-none"></div>
                    <div class="w-12 h-12 bg-orange-50 text-orange-500 rounded-2xl flex items-center justify-center mb-4 border border-orange-100 shadow-sm">
                        <i class="fas fa-exclamation-circle text-xl"></i>
                    </div>
                    <h5 class="font-black text-navy-900 mb-2">Rekomendasi Prioritas AI</h5>
                    <?php if($rekomendasi): ?>
                        <p class="text-[10px] text-slate-500 font-medium leading-relaxed mb-4">
                            Sistem AI mendeteksi <strong class="text-red-500 uppercase"><?php echo e($rekomendasi->nama_objek ?? $rekomendasi->nama_infrastruktur); ?></strong> di wilayah <strong class="text-navy-900"><?php echo e($rekomendasi->nama_kelurahan); ?></strong> mengalami kerusakan berat. Segera jadwalkan perbaikan!
                        </p>
                        <a href="<?php echo e(route('admin.infrastruktur.show', $rekomendasi->id_infrastruktur)); ?>" class="inline-flex items-center gap-2 text-[9px] font-black text-orange-600 uppercase tracking-widest hover:text-orange-700 hover:gap-3 transition-all w-max">
                            Tinjau Aset Ini <i class="fas fa-arrow-right"></i>
                        </a>
                    <?php else: ?>
                        <p class="text-[10px] text-slate-500 font-medium leading-relaxed mb-4">
                            Saat ini belum ada infrastruktur dengan kondisi rusak berat yang mendesak untuk ditindaklanjuti.
                        </p>
                        <a href="<?php echo e(route('admin.infrastruktur')); ?>" class="inline-flex items-center gap-2 text-[9px] font-black text-slate-400 uppercase tracking-widest hover:text-gold-500 hover:gap-3 transition-all w-max">
                            Lihat Semua Data <i class="fas fa-arrow-right"></i>
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Quick Access Cards -->
            <div class="mb-8 text-left">
                <h4 class="font-extrabold text-lg text-navy-900 mb-6">Akses Cepat</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 text-left">
                    
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

    <script>
        function updateClock() {
            const now = new Date();
            document.getElementById('mini-clock').textContent = `${String(now.getHours()).padStart(2, '0')}:${String(now.getMinutes()).padStart(2, '0')} WITA`;
        }
        setInterval(updateClock, 1000); updateClock();

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
</html><?php /**PATH C:\laragon1\laragon\www\geo-sinfra\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>