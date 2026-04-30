<aside class="w-72 bg-[#1e1b4b] text-white flex flex-col hidden md:flex shadow-2xl z-20 text-left">
    <div class="p-8 flex-1 text-left">
        <a href="<?php echo e(route('kabid.dashboard')); ?>" class="flex items-center gap-4 mb-12 hover:opacity-80 transition-opacity group">
            <div class="w-10 h-10 bg-indigo-500 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-500/20 group-hover:scale-110 transition-transform">
                <i class="fas fa-globe-asia text-sm text-white"></i>
            </div>
            <span class="font-black text-2xl tracking-tighter uppercase text-white">GEO-SINFRA</span>
        </a>
        
        <nav class="space-y-3">
            <a href="<?php echo e(route('kabid.dashboard')); ?>" 
               class="flex items-center gap-4 px-5 py-4 <?php echo e(request()->routeIs('kabid.dashboard') ? 'bg-indigo-600 text-white font-bold shadow-lg shadow-indigo-900/20' : 'text-gray-400 hover:text-white hover:bg-white/5'); ?> rounded-2xl text-sm font-semibold transition group text-left">
                <i class="fas fa-desktop text-base <?php echo e(request()->routeIs('kabid.dashboard') ? '' : 'group-hover:text-indigo-400'); ?>"></i> 
                Overview Dashboard
            </a>

            <a href="#" 
               class="flex items-center gap-4 px-5 py-4 text-gray-400 hover:text-white hover:bg-white/5 rounded-2xl text-sm font-semibold transition group text-left">
                <i class="fas fa-map-location-dot text-base group-hover:text-indigo-400"></i> 
                Monitoring Peta Sebaran
            </a>

            <a href="#" 
               class="flex items-center gap-4 px-5 py-4 text-gray-400 hover:text-white hover:bg-white/5 rounded-2xl text-sm font-semibold transition group text-left">
                <i class="fas fa-file-circle-check text-base group-hover:text-indigo-400"></i> 
                Verifikasi Usulan
            </a>

            <a href="#" 
               class="flex items-center gap-4 px-5 py-4 text-gray-400 hover:text-white hover:bg-white/5 rounded-2xl text-sm font-semibold transition group text-left">
                <i class="fas fa-chart-line text-base group-hover:text-indigo-400"></i> 
                Statistik Tahunan
            </a>

            <a href="#" 
               class="flex items-center gap-4 px-5 py-4 text-gray-400 hover:text-white hover:bg-white/5 rounded-2xl text-sm font-semibold transition group text-left">
                <i class="fas fa-file-pdf text-base group-hover:text-indigo-400"></i> 
                Cetak Laporan Resmi
            </a>

            <div class="pt-6 mt-6 border-t border-white/5">
                <a href="<?php echo e(route('kabid.profile')); ?>" 
                   class="flex items-center gap-4 px-5 py-4 <?php echo e(request()->routeIs('kabid.profile') ? 'bg-indigo-600 text-white font-bold shadow-lg shadow-indigo-900/20' : 'text-gray-400 hover:text-white hover:bg-white/5'); ?> rounded-2xl text-sm font-semibold transition group text-left">
                    <i class="fas fa-user-circle text-base <?php echo e(request()->routeIs('kabid.profile') ? '' : 'group-hover:text-indigo-400'); ?>"></i> 
                    Profil Saya
                </a>
            </div>
        </nav>
    </div>

    <div class="p-8 border-t border-white/5 text-left">
        <form method="POST" action="<?php echo e(route('logout')); ?>">
            <?php echo csrf_field(); ?>
            <button type="submit" class="flex items-center gap-4 px-5 py-4 text-red-400 hover:text-red-300 w-full text-left text-sm font-bold transition group">
                <i class="fas fa-sign-out-alt text-base group-hover:-translate-x-1 transition-transform"></i> 
                Keluar Sistem
            </button>
        </form>
    </div>
</aside>
<?php /**PATH C:\laragon1\laragon\www\geo-sinfra\resources\views/kabid/partials/sidebar.blade.php ENDPATH**/ ?>