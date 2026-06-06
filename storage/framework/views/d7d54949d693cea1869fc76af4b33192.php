<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Pengguna | Admin SINFRA</title>
    
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
    </style>
</head>
<body class="bg-slate-50 flex h-screen overflow-hidden text-slate-800 text-left font-sans">

    <?php echo $__env->make('admin.partials.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <main class="flex-1 flex flex-col h-screen overflow-hidden text-left font-sans">
        <header class="bg-white/85 backdrop-blur-xl border-b border-slate-100 px-8 py-5 flex justify-between items-center z-40 text-left">
            <div class="flex items-center gap-4 text-left">
                <a href="<?php echo e(route('admin.users')); ?>" 
                   class="w-10 h-10 bg-white border border-slate-100 rounded-xl flex items-center justify-center text-slate-400 hover:text-gold-500 hover:border-gold-500/20 hover:shadow-lg hover:shadow-gold-500/5 transition-all group"
                   title="Kembali ke Daftar">
                    <i class="fas fa-arrow-left text-xs group-hover:-translate-x-1 transition-transform"></i>
                </a>

                <div class="text-left">
                    <p class="text-[10px] font-black text-gold-500 uppercase tracking-[0.2em] mb-1">Administrator Portal</p>
                    <h2 class="text-xl font-black text-navy-900 leading-none">Tambah Pengguna Baru</h2>
                </div>
            </div>
            
            <div class="flex items-center gap-6">
                <div class="text-right hidden sm:block text-left">
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

        <div class="flex-1 p-8 overflow-y-auto custom-scrollbar text-left">
            
            <?php if($errors->any()): ?>
            <div class="max-w-4xl mx-auto mb-6 px-6 py-4 bg-red-50 border border-red-100 text-red-700 rounded-2xl">
                <div class="flex items-center gap-3 mb-2 text-left">
                    <i class="fas fa-exclamation-triangle"></i>
                    <p class="text-xs font-bold">Gagal menyimpan data. Silakan periksa kembali:</p>
                </div>
                <ul class="list-disc list-inside text-[11px] font-medium ml-4 text-left">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
            <?php endif; ?>

            <div class="max-w-4xl bg-white rounded-[2.5rem] p-10 border border-slate-100 shadow-sm mx-auto text-left">
                <form action="<?php echo e(route('admin.users.store')); ?>" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-8 text-left">
                    <?php echo csrf_field(); ?>
                    
                    <div class="space-y-6 text-left">
                        <div class="text-left">
                            <label class="block text-[10px] font-black text-navy-900 uppercase tracking-widest mb-2">
                                Nama Pengguna <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" value="<?php echo e(old('name')); ?>" class="w-full px-5 py-3 bg-slate-50 border border-slate-100 rounded-2xl text-sm font-semibold focus:ring-4 focus:ring-gold-500/10 focus:border-gold-500 outline-none transition-all" placeholder="Masukkan Nama Lengkap" required>
                        </div>

                        <div class="text-left">
                            <label class="block text-[10px] font-black text-navy-900 uppercase tracking-widest mb-2">
                                Email <span class="text-red-500">*</span>
                            </label>
                            <input type="email" name="email" value="<?php echo e(old('email')); ?>" class="w-full px-5 py-3 bg-slate-50 border border-slate-100 rounded-2xl text-sm font-semibold focus:ring-4 focus:ring-gold-500/10 focus:border-gold-500 outline-none transition-all" placeholder="email@contoh.com" required>
                        </div>

                        <div class="text-left">
                            <label class="block text-[10px] font-black text-navy-900 uppercase tracking-widest mb-2">
                                Nomor WhatsApp / HP <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="no_hp" value="<?php echo e(old('no_hp')); ?>" class="w-full px-5 py-3 bg-slate-50 border border-slate-100 rounded-2xl text-sm font-semibold focus:ring-4 focus:ring-gold-500/10 focus:border-gold-500 outline-none transition-all" placeholder="Contoh: 08123456789" required>
                        </div>

                        <div class="text-left">
                            <label class="block text-[10px] font-black text-navy-900 uppercase tracking-widest mb-2">
                                Password <span class="text-red-500">*</span>
                            </label>
                            <div class="relative text-left">
                                <input type="password" name="password" class="w-full px-5 py-3 bg-slate-50 border border-slate-100 rounded-2xl text-sm font-semibold focus:ring-4 focus:ring-gold-500/10 focus:border-gold-500 outline-none transition-all" placeholder="Min. 8 Karakter" required>
                                <i class="fas fa-lock absolute right-5 top-1/2 -translate-y-1/2 text-slate-300"></i>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-6 text-left">
                        <div class="text-left">
                            <label class="block text-[10px] font-black text-navy-900 uppercase tracking-widest mb-2">
                                Role Akses <span class="text-red-500">*</span>
                            </label>
                            <select id="role-select" name="role" class="w-full px-5 py-3 bg-slate-50 border border-slate-100 rounded-2xl text-sm font-semibold focus:ring-4 focus:ring-gold-500/10 focus:border-gold-500 outline-none transition-all" onchange="toggleWilayah()" required>
                                <option value="surveyor" <?php echo e(old('role') == 'surveyor' ? 'selected' : ''); ?>>SURVEYOR</option>
                                <option value="admin" <?php echo e(old('role') == 'admin' ? 'selected' : ''); ?>>ADMIN</option>
                            </select>
                        </div>

                        <div id="wilayah-container" class="text-left">
                            <label class="block text-[10px] font-black text-navy-900 uppercase tracking-widest mb-2">
                                Wilayah Tugas <span class="text-slate-400 font-medium">(Opsional)</span>
                            </label>
                            <select name="id_kecamatan" class="w-full px-5 py-3 bg-slate-50 border border-slate-100 rounded-2xl text-sm font-semibold focus:ring-4 focus:ring-gold-500/10 focus:border-gold-500 outline-none transition-all">
                                <option value="">Pilih Wilayah</option>
                                <?php $__currentLoopData = $semuaWilayah; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $wilayah): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($wilayah->id_kecamatan); ?>" <?php echo e(old('id_kecamatan') == $wilayah->id_kecamatan ? 'selected' : ''); ?>><?php echo e($wilayah->nama_kecamatan); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>

                        <div class="pt-10 flex gap-3 text-left">
                            <button type="submit" class="flex-1 bg-gold-500 text-white text-xs px-6 py-4 rounded-2xl font-bold shadow-lg shadow-gold-500/10 hover:bg-gold-600 transition tracking-widest uppercase">Simpan User</button>
                            <a href="<?php echo e(route('admin.users')); ?>" class="flex-1 bg-slate-100 text-slate-500 text-xs px-6 py-4 rounded-2xl font-bold hover:bg-slate-200 transition text-center flex items-center justify-center gap-2 text-left tracking-widest uppercase">
                                <i class="fas fa-times-circle text-[10px]"></i> Batal
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <script>
        // WITA Clock Script
        function updateClock() {
            const now = new Date();
            document.getElementById('mini-clock').textContent = `${String(now.getHours()).padStart(2, '0')}:${String(now.getMinutes()).padStart(2, '0')} WITA`;
        }
        setInterval(updateClock, 1000); updateClock();

        // Role Toggle Script
        function toggleWilayah() {
            const role = document.getElementById('role-select').value;
            const container = document.getElementById('wilayah-container');
            // Jika role Admin, kolom wilayah otomatis hilang
            container.classList.toggle('hidden', role === 'admin');
        }
        
        // Jalankan saat pertama kali dimuat
        window.onload = toggleWilayah;
    </script>
</body>
</html><?php /**PATH C:\laragon1\laragon\www\geo-sinfra\resources\views/admin/create-user.blade.php ENDPATH**/ ?>