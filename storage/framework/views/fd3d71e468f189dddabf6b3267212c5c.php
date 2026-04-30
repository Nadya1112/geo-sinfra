<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Kabid | GEO-SINFRA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style> body { font-family: 'Plus Jakarta Sans', sans-serif; } </style>
</head>
<body class="bg-gray-50 flex h-screen overflow-hidden text-gray-800 text-left">

    <?php echo $__env->make('kabid.partials.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <main class="flex-1 flex flex-col h-screen overflow-y-auto">
        <header class="bg-white border-b border-gray-100 px-8 py-5 flex justify-between items-center z-10">
            <div class="flex items-center gap-4">
                <a href="<?php echo e(route('kabid.dashboard')); ?>" class="w-10 h-10 flex items-center justify-center bg-gray-50 text-gray-400 rounded-xl hover:bg-indigo-50 hover:text-indigo-600 transition-all border border-gray-100">
                    <i class="fas fa-arrow-left text-sm"></i>
                </a>
                <div>
                    <p class="text-[10px] font-extrabold text-indigo-600 uppercase tracking-[0.2em] mb-1">Pengaturan Akun</p>
                    <h2 class="text-xl font-black text-[#1e1b4b]">Profil Saya</h2>
                </div>
            </div>
            
            <div class="flex items-center gap-6">
                <div class="text-right hidden sm:block">
                    <p class="text-[11px] font-black text-[#1e1b4b]" id="mini-clock">00:00 WITA</p>
                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-tighter"><?php echo e(now()->translatedFormat('l, d F Y')); ?></p>
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

                <form action="<?php echo e(route('kabid.profile.update')); ?>" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <?php echo csrf_field(); ?>

                    <!-- Foto Profil -->
                    <div class="lg:col-span-1">
                        <div class="bg-white rounded-[2.5rem] p-8 border border-gray-100 shadow-sm text-center relative overflow-hidden group">
                            <div class="absolute -right-6 -top-6 w-20 h-20 bg-indigo-50 rounded-full transition-transform group-hover:scale-150"></div>
                            <div class="relative z-10">
                                <div class="relative w-32 h-32 mx-auto mb-6">
                                    <div class="w-full h-full rounded-[2.5rem] bg-indigo-100 border-4 border-white shadow-xl overflow-hidden flex items-center justify-center">
                                        <?php if(auth()->user()->profile_photo): ?>
                                            <img src="<?php echo e(asset('storage/' . auth()->user()->profile_photo)); ?>" id="preview-photo" class="w-full h-full object-cover">
                                        <?php else: ?>
                                            <i class="fas fa-user-tie text-4xl text-indigo-300" id="placeholder-icon"></i>
                                            <img id="preview-photo" class="w-full h-full object-cover hidden">
                                        <?php endif; ?>
                                    </div>
                                    <label for="profile_photo" class="absolute -bottom-2 -right-2 w-10 h-10 bg-[#1e1b4b] text-white rounded-xl flex items-center justify-center shadow-lg cursor-pointer hover:bg-indigo-600 transition-all border-4 border-white">
                                        <i class="fas fa-camera text-xs"></i>
                                        <input type="file" name="profile_photo" id="profile_photo" class="hidden" accept="image/*" onchange="previewImage(this)">
                                    </label>
                                </div>
                                <h4 class="font-black text-[#1e1b4b]"><?php echo e(auth()->user()->name); ?></h4>
                                <p class="text-[10px] font-bold text-indigo-500 uppercase tracking-widest mt-1">Kepala Bidang</p>
                            </div>
                        </div>
                    </div>

                    <!-- Detail Akun -->
                    <div class="lg:col-span-2">
                        <div class="bg-white rounded-[2.5rem] p-10 border border-gray-100 shadow-sm">
                            <div class="mb-8 border-b border-gray-50 pb-5">
                                <h3 class="text-lg font-black text-[#1e1b4b] tracking-tight italic">Informasi Personal</h3>
                                <p class="text-xs text-gray-400 font-medium">Perbarui informasi kredensial akun pengawas Anda</p>
                            </div>

                            <div class="space-y-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-[10px] font-black text-[#1e1b4b] uppercase tracking-widest mb-2 px-1">Nama Lengkap</label>
                                        <input type="text" name="name" value="<?php echo e(auth()->user()->name); ?>" class="w-full px-5 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-semibold outline-none focus:ring-4 focus:ring-indigo-500/5 focus:border-indigo-500 transition-all" required>
                                    </div>

                                    <div>
                                        <label class="block text-[10px] font-black text-[#1e1b4b] uppercase tracking-widest mb-2 px-1">Alamat Email</label>
                                        <input type="email" name="email" value="<?php echo e(auth()->user()->email); ?>" class="w-full px-5 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-semibold outline-none focus:ring-4 focus:ring-indigo-500/5 focus:border-indigo-500 transition-all" required>
                                    </div>
                                </div>

                                <div class="pt-4 border-t border-gray-50">
                                    <p class="text-[10px] font-black text-gray-300 mb-4 italic uppercase tracking-wider">Keamanan Akun (Opsional)</p>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label class="block text-[10px] font-black text-[#1e1b4b] uppercase tracking-widest mb-2 px-1">Password Baru</label>
                                            <input type="password" name="password" placeholder="••••••••" class="w-full px-5 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-semibold outline-none focus:ring-4 focus:ring-indigo-500/5 focus:border-indigo-500 transition-all">
                                        </div>
                                        <div>
                                            <label class="block text-[10px] font-black text-[#1e1b4b] uppercase tracking-widest mb-2 px-1">Konfirmasi Sandi</label>
                                            <input type="password" name="password_confirmation" placeholder="••••••••" class="w-full px-5 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-semibold outline-none focus:ring-4 focus:ring-indigo-500/5 focus:border-indigo-500 transition-all">
                                        </div>
                                    </div>
                                </div>

                                <div class="pt-6">
                                    <button type="submit" class="w-full bg-[#1e1b4b] text-white py-4 rounded-2xl font-black shadow-xl shadow-indigo-900/10 hover:bg-indigo-600 hover:-translate-y-1 transition-all tracking-[0.2em] text-[10px] uppercase">
                                        Simpan Perubahan Profil
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
<?php /**PATH C:\laragon1\laragon\www\geo-sinfra\resources\views/kabid/profile.blade.php ENDPATH**/ ?>