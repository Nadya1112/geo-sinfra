<aside class="w-64 bg-[#1e1b4b] text-white flex flex-col hidden md:flex shadow-2xl z-20 text-left">
    <div class="p-6 flex-1 text-left">
        <a href="<?php echo e(route('surveyor.dashboard')); ?>" class="flex items-center gap-3 mb-10 hover:opacity-80 transition-opacity group">
            <div class="w-8 h-8 bg-emerald-500 rounded-lg flex items-center justify-center shadow-lg shadow-emerald-500/20 group-hover:scale-110 transition-transform">
                <i class="fas fa-camera text-xs text-white"></i>
            </div>
            <span class="font-extrabold text-xl tracking-tighter uppercase text-white">GEO-SINFRA</span>
        </a>
        
        <nav class="space-y-1">
            <a href="<?php echo e(route('surveyor.dashboard')); ?>" 
               class="flex items-center gap-3 px-4 py-3 <?php echo e(request()->routeIs('surveyor.dashboard') ? 'bg-emerald-600 text-white font-bold shadow-lg shadow-emerald-900/20' : 'text-gray-400 hover:text-white hover:bg-white/5'); ?> rounded-xl text-sm font-semibold transition group text-left">
                <i class="fas fa-th-large <?php echo e(request()->routeIs('surveyor.dashboard') ? '' : 'group-hover:text-emerald-400'); ?>"></i> 
                Dashboard
            </a>

            <a href="<?php echo e(route('surveyor.input')); ?>" 
               class="flex items-center gap-3 px-4 py-3 <?php echo e(request()->routeIs('surveyor.input') ? 'bg-emerald-600 text-white font-bold shadow-lg shadow-emerald-900/20' : 'text-gray-400 hover:text-white hover:bg-white/5'); ?> rounded-xl text-sm font-semibold transition group text-left">
                <i class="fas fa-plus-circle <?php echo e(request()->routeIs('surveyor.input') ? '' : 'group-hover:text-emerald-400'); ?>"></i> 
                Input Data Baru
            </a>

            <a href="<?php echo e(route('surveyor.history')); ?>" 
               class="flex items-center gap-3 px-4 py-3 <?php echo e(request()->routeIs('surveyor.history') ? 'bg-emerald-600 text-white font-bold shadow-lg shadow-emerald-900/20' : 'text-gray-400 hover:text-white hover:bg-white/5'); ?> rounded-xl text-sm font-semibold transition group text-left">
                <i class="fas fa-history <?php echo e(request()->routeIs('surveyor.history') ? '' : 'group-hover:text-emerald-400'); ?>"></i> 
                Riwayat Data Saya
            </a>

            <a href="<?php echo e(route('surveyor.map')); ?>" 
               class="flex items-center gap-3 px-4 py-3 <?php echo e(request()->routeIs('surveyor.map') ? 'bg-emerald-600 text-white font-bold shadow-lg shadow-emerald-900/20' : 'text-gray-400 hover:text-white hover:bg-white/5'); ?> rounded-xl text-sm font-semibold transition group text-left">
                <i class="fas fa-map-marked-alt <?php echo e(request()->routeIs('surveyor.map') ? '' : 'group-hover:text-emerald-400'); ?>"></i> 
                Peta Sebaran Saya
            </a>
        </nav>
    </div>

    <div class="p-6 border-t border-white/5 text-left">
        <form method="POST" action="<?php echo e(route('logout')); ?>">
            <?php echo csrf_field(); ?>
            <button type="submit" class="flex items-center gap-3 px-4 py-3 text-red-400 hover:text-red-300 w-full text-left text-sm font-bold transition group">
                <i class="fas fa-sign-out-alt group-hover:-translate-x-1 transition-transform"></i> 
                Keluar Sistem
            </button>
        </form>
    </div>
</aside>
<?php /**PATH C:\laragon1\laragon\www\geo-sinfra\resources\views/surveyor/partials/sidebar.blade.php ENDPATH**/ ?>