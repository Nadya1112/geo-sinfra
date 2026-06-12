<aside class="w-64 bg-navy-900 text-white flex flex-col hidden md:flex shadow-2xl z-20 text-left border-r border-navy-800">
    <div class="p-6 flex-1 text-left">
        <a href="<?php echo e(route('kabid.dashboard')); ?>" class="flex items-center gap-3 mb-10 hover:opacity-80 transition-opacity group">
            <div class="w-8 h-8 bg-white rounded-lg overflow-hidden shadow-lg shadow-gold-500/20 group-hover:scale-110 transition-transform">
                <img src="<?php echo e(asset('logo_geo-sinfra.png')); ?>" class="w-full h-full object-contain" alt="Logo">
            </div>
            <span class="font-extrabold text-xl tracking-tighter uppercase text-white">GEO-SINFRA</span>
        </a>
        
        <nav class="space-y-1">
            <a href="<?php echo e(route('kabid.dashboard')); ?>" 
               class="flex items-center gap-3 px-4 py-3 <?php echo e(request()->routeIs('kabid.dashboard') ? 'bg-gold-500 text-white font-bold shadow-lg shadow-gold-500/20' : 'text-slate-400 hover:text-white hover:bg-white/5'); ?> rounded-xl text-sm font-semibold transition group text-left">
                <i class="fas fa-th-large <?php echo e(request()->routeIs('kabid.dashboard') ? '' : 'group-hover:text-gold-400'); ?>"></i> 
                Dashboard
            </a>

            <a href="<?php echo e(route('kabid.monitoring')); ?>" 
               class="flex items-center gap-3 px-4 py-3 <?php echo e(request()->routeIs('kabid.monitoring') ? 'bg-gold-500 text-white font-bold shadow-lg shadow-gold-500/20' : 'text-slate-400 hover:text-white hover:bg-white/5'); ?> rounded-xl text-sm font-semibold transition group text-left">
                <i class="fas fa-satellite-dish <?php echo e(request()->routeIs('kabid.monitoring') ? '' : 'group-hover:text-gold-400'); ?>"></i> 
                Executive WebGIS
            </a>

            <a href="<?php echo e(route('kabid.prioritas')); ?>" 
               class="flex items-center gap-3 px-4 py-3 <?php echo e(request()->routeIs('kabid.prioritas') ? 'bg-rose-500 text-white font-bold shadow-lg shadow-rose-500/20' : 'text-slate-400 hover:text-rose-400 hover:bg-rose-500/10'); ?> rounded-xl text-sm font-semibold transition group text-left">
                <i class="fas fa-bolt <?php echo e(request()->routeIs('kabid.prioritas') ? 'animate-pulse' : 'text-rose-500 group-hover:text-rose-400'); ?>"></i> 
                Rekomendasi Prioritas
            </a>

            <?php
                $pendingValidasiCount = \App\Models\Infrastruktur::where('status_verifikasi', 'Verified')->where('status_validasi', 'Pending')->count();
            ?>
            <a href="<?php echo e(route('kabid.validasi')); ?>" 
               class="flex items-center justify-between px-4 py-3 <?php echo e(request()->routeIs('kabid.validasi') ? 'bg-gold-500 text-white font-bold shadow-lg shadow-gold-500/20' : 'text-slate-400 hover:text-white hover:bg-white/5'); ?> rounded-xl text-sm font-semibold transition group">
                <div class="flex items-center gap-3">
                    <i class="fas fa-clipboard-check <?php echo e(request()->routeIs('kabid.validasi') ? '' : 'group-hover:text-gold-400'); ?>"></i> 
                    Validasi Usulan
                </div>
                <?php if($pendingValidasiCount > 0): ?>
                    <span class="bg-rose-500 text-white text-[10px] font-black px-2 py-0.5 rounded-full shadow-sm animate-pulse">
                        <?php echo e($pendingValidasiCount); ?>

                    </span>
                <?php endif; ?>
            </a>



            <a href="<?php echo e(route('kabid.laporan')); ?>" 
               class="flex items-center gap-3 px-4 py-3 <?php echo e(request()->routeIs('kabid.laporan') ? 'bg-gold-500 text-white font-bold shadow-lg shadow-gold-500/20' : 'text-slate-400 hover:text-white hover:bg-white/5'); ?> rounded-xl text-sm font-semibold transition group text-left">
                <i class="fas fa-print <?php echo e(request()->routeIs('kabid.laporan') ? '' : 'group-hover:text-gold-400'); ?>"></i> 
                Cetak Laporan
            </a>
        </nav>
    </div>

    <div class="p-6 border-t border-white/5 text-left">
        <form method="POST" action="<?php echo e(route('logout')); ?>">
            <?php echo csrf_field(); ?>
            <button type="submit" class="flex items-center gap-3 px-4 py-3 text-[#be123c] hover:text-red-400 w-full text-left text-sm font-bold transition group">
                <i class="fas fa-sign-out-alt group-hover:-translate-x-1 transition-transform"></i> 
                Keluar Sistem
            </button>
        </form>
    </div>
</aside>
<?php /**PATH C:\laragon1\laragon\www\geo-sinfra\resources\views/kabid/partials/sidebar.blade.php ENDPATH**/ ?>