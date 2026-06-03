<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya | Admin SINFRA</title>
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
    <style> body { font-family: 'Plus Jakarta Sans', sans-serif; } </style>
</head>
<body class="bg-slate-50 flex h-screen overflow-hidden text-slate-800 text-left font-sans">

    <?php echo $__env->make('admin.partials.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <main class="flex-1 flex flex-col h-screen overflow-y-auto font-sans">
        <header class="bg-white/85 backdrop-blur-xl border-b border-slate-100 px-8 py-5 flex justify-between items-center z-40 text-left">
            <div class="flex items-center gap-4 text-left">
                <a href="<?php echo e(route('admin.dashboard')); ?>" class="w-10 h-10 bg-white border border-slate-100 rounded-xl flex items-center justify-center text-slate-400 hover:text-gold-500 hover:border-gold-500/20 hover:shadow-lg hover:shadow-gold-500/5 transition-all group">
                    <i class="fas fa-arrow-left text-xs group-hover:-translate-x-1 transition-transform"></i>
                </a>
                <div class="text-left">
                    <p class="text-[10px] font-black text-gold-500 uppercase tracking-[0.2em] mb-1">Administrator Portal</p>
                    <h2 class="text-xl font-black text-navy-900 leading-none">Pengaturan Profil</h2>
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
                        <p class="text-[9px] font-bold text-emerald-500 uppercase mt-1">Online</p>
                    </div>
                    <div class="w-10 h-10 bg-navy-900 rounded-xl flex items-center justify-center text-gold-500 border border-white/10 overflow-hidden hover:shadow-lg hover:shadow-navy-950/20 transition-all shadow-md">
                        <?php if(auth()->user()->profile_photo): ?>
                            <img src="<?php echo e(asset('storage/' . auth()->user()->profile_photo)); ?>" class="w-full h-full object-cover">
                        <?php else: ?>
                            <i class="fas fa-user-circle text-xl"></i>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </header>

        <div class="p-8">
            <div class="max-w-4xl mx-auto">
                <?php if(session('success')): ?>
                <div class="mb-6 px-6 py-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-2xl flex items-center gap-3 shadow-sm">
                    <i class="fas fa-check-circle"></i>
                    <p class="text-xs font-bold"><?php echo e(session('success')); ?></p>
                </div>
                <?php endif; ?>

                <?php if($errors->any()): ?>
                <div class="mb-6 px-6 py-4 bg-red-50 border border-red-100 text-red-700 rounded-2xl shadow-sm">
                    <ul class="list-disc list-inside text-xs font-bold">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
                <?php endif; ?>

                <form action="<?php echo e(route('admin.profile.update')); ?>" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>

                    <!-- Foto Profil -->
                    <div class="lg:col-span-1">
                        <div class="bg-white rounded-[2.5rem] p-8 border border-slate-100 shadow-sm text-center">
                            <div class="relative w-32 h-32 mx-auto mb-6">
                                <div class="w-full h-full rounded-full bg-slate-50 border-4 border-white shadow-md overflow-hidden flex items-center justify-center">
                                    <?php if(auth()->user()->profile_photo): ?>
                                        <img src="<?php echo e(asset('storage/' . auth()->user()->profile_photo)); ?>" id="preview-photo" class="w-full h-full object-cover">
                                    <?php else: ?>
                                        <i class="fas fa-user text-4xl text-slate-300" id="placeholder-icon"></i>
                                        <img id="preview-photo" class="w-full h-full object-cover hidden">
                                    <?php endif; ?>
                                </div>
                                <label for="profile_photo" class="absolute bottom-0 right-0 w-10 h-10 bg-gold-500 text-white rounded-full flex items-center justify-center shadow-lg cursor-pointer hover:bg-gold-600 transition-all border-4 border-white">
                                    <i class="fas fa-camera text-xs"></i>
                                    <input type="file" name="profile_photo" id="profile_photo" class="hidden" accept="image/*" onchange="previewImage(this)">
                                </label>
                            </div>
                            <h4 class="font-black text-navy-900 uppercase tracking-tight"><?php echo e(auth()->user()->name); ?></h4>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1"><?php echo e(auth()->user()->role); ?></p>
                        </div>
                    </div>

                    <!-- Detail Akun -->
                    <div class="lg:col-span-2">
                        <div class="bg-white rounded-[2.5rem] p-10 border border-slate-100 shadow-sm">
                            <div class="mb-8 border-b border-slate-50 pb-5">
                                <h3 class="text-lg font-black text-navy-900 tracking-tight">Informasi Pribadi</h3>
                                <p class="text-xs text-slate-400 font-medium">Perbarui informasi dasar dan kredensial akun Anda</p>
                            </div>

                            <div class="space-y-6">
                                <div>
                                    <label class="block text-[10px] font-black text-navy-900 uppercase tracking-widest mb-2">Nama Lengkap</label>
                                    <input type="text" name="name" value="<?php echo e(auth()->user()->name); ?>" class="w-full px-5 py-3 bg-slate-50 border border-slate-100 rounded-2xl text-sm font-semibold outline-none focus:ring-4 focus:ring-gold-500/10 focus:border-gold-500 transition-all" required>
                                </div>

                                <div>
                                    <label class="block text-[10px] font-black text-navy-900 uppercase tracking-widest mb-2">Alamat Email</label>
                                    <input type="email" name="email" value="<?php echo e(auth()->user()->email); ?>" class="w-full px-5 py-3 bg-slate-50 border border-slate-100 rounded-2xl text-sm font-semibold outline-none focus:ring-4 focus:ring-gold-500/10 focus:border-gold-500 transition-all" required>
                                </div>

                                <div class="pt-4 border-t border-slate-50">
                                    <p class="text-xs font-bold text-slate-400 mb-4 italic">Kosongkan jika tidak ingin mengubah kata sandi</p>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label class="block text-[10px] font-black text-navy-900 uppercase tracking-widest mb-2">Kata Sandi Baru</label>
                                            <input type="password" name="password" class="w-full px-5 py-3 bg-slate-50 border border-slate-100 rounded-2xl text-sm font-semibold outline-none focus:ring-4 focus:ring-gold-500/10 focus:border-gold-500 transition-all">
                                        </div>
                                        <div>
                                            <label class="block text-[10px] font-black text-navy-900 uppercase tracking-widest mb-2">Konfirmasi Sandi</label>
                                            <input type="password" name="password_confirmation" class="w-full px-5 py-3 bg-slate-50 border border-slate-100 rounded-2xl text-sm font-semibold outline-none focus:ring-4 focus:ring-gold-500/10 focus:border-gold-500 transition-all">
                                        </div>
                                    </div>
                                </div>

                                <div class="pt-6">
                                    <button type="submit" class="w-full bg-navy-900 text-gold-500 border border-white/10 py-4 rounded-2xl font-black shadow-xl shadow-navy-950/20 hover:bg-gold-500 hover:text-navy-950 transition-all tracking-widest text-xs uppercase">
                                        Simpan Perubahan
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <script>
        function updateClock() {
            const now = new Date();
            document.getElementById('mini-clock').textContent = `${String(now.getHours()).padStart(2, '0')}:${String(now.getMinutes()).padStart(2, '0')} WITA`;
        }
        setInterval(updateClock, 1000); updateClock();

        function previewImage(input) {
            const preview = document.getElementById('preview-photo');
            const placeholder = document.getElementById('placeholder-icon');
            
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    if (placeholder) placeholder.classList.add('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</body>
</html>
<?php /**PATH C:\laragon1\laragon\www\geo-sinfra\resources\views/admin/profile.blade.php ENDPATH**/ ?>