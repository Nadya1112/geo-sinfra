<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Wilayah | Admin SINFRA</title>
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
                <a href="<?php echo e(route('admin.wilayah')); ?>" class="w-10 h-10 bg-white border border-slate-100 rounded-xl flex items-center justify-center text-slate-400 hover:text-gold-500 hover:border-gold-500/20 hover:shadow-lg hover:shadow-gold-500/5 transition-all group">
                    <i class="fas fa-arrow-left text-xs group-hover:-translate-x-1 transition-transform"></i>
                </a>
                <div class="text-left">
                    <p class="text-[10px] font-black text-gold-500 uppercase tracking-[0.2em] mb-1">Administrator Portal</p>
                    <h2 class="text-xl font-black text-navy-900 leading-none">Edit Data Wilayah</h2>
                </div>
            </div>
            
            <div class="flex items-center gap-6 text-left">
                <div class="text-right hidden sm:block">
                    <p class="text-[11px] font-black text-navy-900" id="mini-clock">00:00 WITA</p>
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-tighter"><?php echo e(now()->translatedFormat('l, d F Y')); ?></p>
                </div>
                <div class="h-8 w-[1px] bg-slate-100"></div>
                <div class="flex items-center gap-3">
                    <div class="text-right">
                        <p class="text-[11px] font-black text-navy-900 leading-none uppercase">Admin SINFRA</p>
                        <p class="text-[9px] font-bold text-emerald-500 uppercase mt-1">Online</p>
                    </div>
                    <div class="w-10 h-10 bg-navy-900 rounded-xl flex items-center justify-center text-gold-500 border border-white/10 overflow-hidden hover:shadow-lg hover:shadow-navy-950/20 transition-all shadow-md">
                        <i class="fas fa-user-circle text-xl"></i>
                    </div>
                </div>
            </div>
        </header>

        <div class="p-8 text-left">
            <div class="max-w-4xl bg-white rounded-[2.5rem] p-10 border border-slate-100 shadow-sm mx-auto">
                <div class="mb-10 border-b border-slate-50 pb-5 text-left">
                    <h3 class="text-lg font-black text-navy-900 tracking-tight">Informasi Wilayah</h3>
                    <p class="text-xs text-slate-400 font-medium font-sans">Perbarui koordinat dan nama wilayah administratif</p>
                </div>

                <form action="<?php echo e(route('admin.wilayah.update', $wilayah->id_kelurahan)); ?>" method="POST" class="space-y-8 text-left">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 text-left">
                        <div class="text-left">
                            <label class="block text-[10px] font-black text-navy-900 tracking-widest mb-2 uppercase">Nama Kelurahan <span class="text-red-500">*</span></label>
                            <input type="text" name="nama_kelurahan" value="<?php echo e($wilayah->nama_kelurahan); ?>" class="w-full px-5 py-3 bg-slate-50 border border-slate-100 rounded-2xl text-sm font-semibold outline-none focus:ring-4 focus:ring-gold-500/10 focus:border-gold-500" required>
                        </div>
                        <div class="text-left">
                            <label class="block text-[10px] font-black text-navy-900 tracking-widest mb-2 uppercase">Kecamatan <span class="text-red-500">*</span></label>
                            <select name="id_kecamatan" class="w-full px-5 py-3 bg-slate-50 border border-slate-100 rounded-2xl text-sm font-semibold outline-none focus:ring-4 focus:ring-gold-500/10 focus:border-gold-500">
                                <?php $__currentLoopData = $semuaKecamatan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kec): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($kec->id_kecamatan); ?>" <?php echo e($wilayah->id_kecamatan == $kec->id_kecamatan ? 'selected' : ''); ?>>
                                        <?php echo e($kec->nama_kecamatan); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>

                    <div class="text-left">
                        <label class="block text-[10px] font-black text-navy-900 tracking-widest mb-2 uppercase">Data Geometri (GeoJSON) <span class="text-slate-400 font-medium normal-case ml-1">(Opsional)</span></label>
                        <textarea name="geometri" rows="8" placeholder='{"type": "Polygon", "coordinates": [...]}' class="w-full px-5 py-3 bg-slate-50 border border-slate-100 rounded-2xl text-xs font-mono focus:ring-4 focus:ring-gold-500/10 focus:border-gold-500 outline-none transition-all"><?php echo e(old('geometri', $wilayah->geometri)); ?></textarea>
                        <p class="text-[9px] text-slate-400 mt-2 italic font-medium text-left">Masukkan format GeoJSON untuk menampilkan poligon di peta.</p>
                    </div>

                    <div class="flex gap-4 pt-6 text-left">
                        <button type="submit" class="flex-1 bg-gold-500 text-white py-4 rounded-2xl font-bold shadow-lg shadow-gold-500/10 hover:bg-gold-600 transition tracking-widest text-xs uppercase">SIMPAN</button>
                        <a href="<?php echo e(route('admin.wilayah')); ?>" class="flex-1 bg-slate-100 text-slate-500 py-4 rounded-2xl font-bold hover:bg-slate-200 transition text-center leading-[1.2rem] tracking-widest text-xs uppercase">Batal</a>
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
    </script>
</body>
</html><?php /**PATH C:\laragon1\laragon\www\geo-sinfra\resources\views/admin/edit-wilayah.blade.php ENDPATH**/ ?>