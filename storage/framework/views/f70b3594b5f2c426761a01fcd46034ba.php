<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Admin SINFRA</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        
        /* Animasi Latar Belakang Banner */
        .bg-pattern {
            background-image: radial-gradient(circle at 2px 2px, rgba(255,255,255,0.15) 1px, transparent 0);
            background-size: 24px 24px;
        }
    </style>
</head>
<body class="bg-gray-50 flex h-screen overflow-hidden text-gray-800 text-left">

    <?php echo $__env->make('admin.partials.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <main class="flex-1 overflow-y-auto custom-scrollbar text-left">
        <header class="sticky top-0 bg-white/80 backdrop-blur-md border-b border-gray-100 px-8 py-5 flex justify-between items-center z-40 text-left">
            <div class="text-left">
                <p class="text-[10px] font-extrabold text-blue-600 uppercase tracking-[0.2em] mb-1">Administrator Portal</p>
                <h2 class="text-xl font-black text-[#1e1b4b]">Beranda Utama</h2>
            </div>

            <div class="flex items-center gap-6">
                <div class="text-right hidden sm:block">
                    <p class="text-[11px] font-black text-[#1e1b4b]" id="mini-clock">00:00 WITA</p>
                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-tighter"><?php echo e(now()->translatedFormat('l, d F Y')); ?></p>
                </div>
                <div class="h-8 w-[1px] bg-gray-100"></div>
                <div class="flex items-center gap-3">
                    <a href="<?php echo e(route('admin.profile')); ?>" class="text-right group">
                        <p class="text-[11px] font-black text-[#1e1b4b] leading-none uppercase group-hover:text-blue-600 transition-all"><?php echo e(auth()->user()->name); ?></p>
                        <p class="text-[9px] font-bold text-green-500 uppercase mt-1">Online</p>
                    </a>
                    <a href="<?php echo e(route('admin.profile')); ?>" class="w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600 border border-indigo-100 overflow-hidden hover:shadow-lg hover:shadow-indigo-500/10 transition-all">
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
            
            <div class="relative bg-gradient-to-br from-blue-600 to-indigo-800 rounded-[2.5rem] p-10 mb-8 overflow-hidden shadow-lg shadow-blue-900/10 text-left">
                <div class="absolute inset-0 bg-pattern opacity-50"></div>
                <div class="absolute -right-10 -top-10 w-64 h-64 bg-white opacity-5 rounded-full blur-3xl"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6 text-left">
                    <div class="text-left">
                        <h3 class="text-3xl font-black text-white mb-2 leading-tight">Selamat Datang, Administrator!</h3>
                        <p class="text-blue-100 text-sm font-medium max-w-xl text-left">Pusat kendali manajemen infrastruktur dan pengguna Geographic Information System SINFRA. Apa yang ingin Anda kerjakan hari ini?</p>
                    </div>
                    <div class="hidden md:block">
                        <div class="w-20 h-20 bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl flex items-center justify-center shadow-2xl">
                            <i class="fas fa-shield-alt text-4xl text-white"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-8 bg-[#1e1b4b] p-8 rounded-[2.5rem] border border-white/10 shadow-xl flex flex-col sm:flex-row items-center justify-between gap-6 text-white text-left">
                <div class="flex items-center gap-6 text-left">
                    <div class="w-14 h-14 bg-white/10 rounded-2xl flex items-center justify-center border border-white/10 backdrop-blur-md shrink-0">
                        <i class="fas fa-sync text-xl text-yellow-400"></i>
                    </div>
                    <div class="text-left">
                        <h4 class="font-black text-sm uppercase tracking-wider text-white">Sinkronisasi Basis Data AI Masal</h4>
                        <p class="text-[11px] text-gray-300 font-medium mt-1 leading-relaxed">Gunakan fitur ini untuk memproses dan menganalisis seluruh data lama menggunakan algoritma Decision Tree & CNN.</p>
                        <a href="<?php echo e(route('admin.test-ai')); ?>" target="_blank" class="text-[9px] text-blue-300 hover:text-white transition-colors flex items-center gap-1 mt-2">
                            <i class="fas fa-plug"></i> Cek Status Koneksi Server AI
                        </a>
                    </div>
                </div>
                <form action="<?php echo e(route('admin.infrastruktur.sinkronisasi-ai')); ?>" method="POST" class="w-full sm:w-auto shrink-0">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white px-6 py-4 rounded-2xl font-black text-[10px] uppercase tracking-widest transition-all shadow-md active:scale-95">
                        Mulai Analisis Serentak
                    </button>
                </form>
            </div>

            <div class="mb-8 text-left">
                <h4 class="font-extrabold text-lg text-[#1e1b4b] mb-6">Akses Cepat</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 text-left">
                    
                    <a href="<?php echo e(route('admin.users.create')); ?>" class="group bg-white p-6 rounded-3xl border border-gray-100 shadow-sm hover:shadow-xl hover:shadow-blue-500/10 hover:border-blue-200 transition-all text-left">
                        <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                            <i class="fas fa-user-plus text-lg"></i>
                        </div>
                        <h5 class="font-black text-[#1e1b4b] mb-1">Tambah User</h5>
                        <p class="text-[10px] text-gray-400 font-medium leading-relaxed text-left">Daftarkan Surveyor atau Admin baru ke dalam sistem.</p>
                    </a>

                    <a href="<?php echo e(route('admin.wilayah')); ?>" class="group bg-white p-6 rounded-3xl border border-gray-100 shadow-sm hover:shadow-xl hover:shadow-indigo-500/10 hover:border-indigo-200 transition-all text-left">
                        <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                            <i class="fas fa-sitemap text-lg"></i>
                        </div>
                        <h5 class="font-black text-[#1e1b4b] mb-1">Kelola Wilayah</h5>
                        <p class="text-[10px] text-gray-400 font-medium leading-relaxed text-left">Kelola data master wilayah kecamatan dan kelurahan.</p>
                    </a>

                    <a href="<?php echo e(route('admin.statistik')); ?>" class="group bg-white p-6 rounded-3xl border border-gray-100 shadow-sm hover:shadow-xl hover:shadow-purple-500/10 hover:border-purple-200 transition-all text-left">
                        <div class="w-12 h-12 bg-purple-50 text-purple-600 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                            <i class="fas fa-chart-pie text-lg"></i>
                        </div>
                        <h5 class="font-black text-[#1e1b4b] mb-1">Statistik Data</h5>
                        <p class="text-[10px] text-gray-400 font-medium leading-relaxed text-left">Lihat rekapitulasi data dan prediksi prioritas harian.</p>
                    </a>

                    <a href="<?php echo e(route('admin.infrastruktur')); ?>" class="group bg-white p-6 rounded-3xl border border-gray-100 shadow-sm hover:shadow-xl hover:shadow-emerald-500/10 hover:border-emerald-200 transition-all text-left">
                        <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                            <i class="fas fa-database text-lg"></i>
                        </div>
                        <h5 class="font-black text-[#1e1b4b] mb-1">Data Master</h5>
                        <p class="text-[10px] text-gray-400 font-medium leading-relaxed text-left">Lihat dan kelola seluruh data infrastruktur lapangan.</p>
                    </a>

                </div>
            </div>

            <div class="bg-indigo-50 border border-indigo-100 rounded-2xl p-6 flex items-center gap-4 text-left">
                <div class="w-10 h-10 bg-indigo-200 text-indigo-700 rounded-full flex items-center justify-center shrink-0">
                    <i class="fas fa-info text-sm"></i>
                </div>
                <div>
                    <p class="text-[11px] text-indigo-700 font-medium text-left">Sistem berjalan optimal. Hybrid Model (CNN) aktif dan siap memproses data survei terbaru.</p>
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
    </script>
</body>
</html><?php /**PATH C:\laragon1\laragon\www\geo-sinfra\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>