
<button id="mobile-menu-btn" onclick="toggleMobileMenu()" class="fixed top-4 left-4 z-[9999] w-10 h-10 bg-navy-900 text-gold-500 rounded-xl flex items-center justify-center shadow-lg md:hidden border border-white/10 hover:bg-navy-800 transition-all active:scale-95">
    <i class="fas fa-bars text-sm" id="menu-icon"></i>
</button>


<div id="mobile-overlay" onclick="toggleMobileMenu()" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-[9998] hidden md:hidden transition-opacity duration-300 opacity-0"></div>


<aside class="w-64 bg-navy-900 text-white flex-col hidden md:flex shadow-2xl z-20 text-left shrink-0">
    <div class="p-6 flex-1 text-left">
        <a href="<?php echo e(route('surveyor.dashboard')); ?>" class="flex items-center gap-3 mb-10 hover:opacity-80 transition-opacity group">
            <div class="w-8 h-8 bg-white rounded-lg overflow-hidden shadow-lg shadow-gold-500/20 group-hover:scale-110 transition-transform">
                <img src="<?php echo e(asset('logo_geo-sinfra.png')); ?>" class="w-full h-full object-contain" alt="Logo">
            </div>
            <span class="font-extrabold text-xl tracking-tighter uppercase text-white">GEO-SINFRA</span>
        </a>
        
        <nav class="space-y-1">
            <a href="<?php echo e(route('surveyor.dashboard')); ?>" 
               class="flex items-center gap-3 px-4 py-3 <?php echo e(request()->routeIs('surveyor.dashboard') ? 'bg-gold-500 text-white font-bold shadow-lg shadow-gold-500/20' : 'text-slate-400 hover:text-white hover:bg-white/5'); ?> rounded-xl text-sm font-semibold transition group text-left">
                <i class="fas fa-th-large <?php echo e(request()->routeIs('surveyor.dashboard') ? '' : 'group-hover:text-gold-400'); ?>"></i> 
                Dashboard
            </a>

            <a href="<?php echo e(route('surveyor.laporan')); ?>" 
               class="flex items-center gap-3 px-4 py-3 <?php echo e(request()->routeIs('surveyor.laporan') ? 'bg-gold-500 text-white font-bold shadow-lg shadow-gold-500/20' : 'text-slate-400 hover:text-white hover:bg-white/5'); ?> rounded-xl text-sm font-semibold transition group text-left">
                <i class="fas fa-tasks <?php echo e(request()->routeIs('surveyor.laporan') ? '' : 'group-hover:text-gold-400'); ?>"></i> 
                Penugasan Laporan Warga
            </a>

            <a href="<?php echo e(route('surveyor.input')); ?>" 
               class="flex items-center gap-3 px-4 py-3 <?php echo e(request()->routeIs('surveyor.input') ? 'bg-gold-500 text-white font-bold shadow-lg shadow-gold-500/20' : 'text-slate-400 hover:text-white hover:bg-white/5'); ?> rounded-xl text-sm font-semibold transition group text-left">
                <i class="fas fa-plus-circle <?php echo e(request()->routeIs('surveyor.input') ? '' : 'group-hover:text-gold-400'); ?>"></i> 
                Input Data Lapangan
            </a>

            <a href="<?php echo e(route('surveyor.history')); ?>" 
               class="flex items-center gap-3 px-4 py-3 <?php echo e(request()->routeIs('surveyor.history') ? 'bg-gold-500 text-white font-bold shadow-lg shadow-gold-500/20' : 'text-slate-400 hover:text-white hover:bg-white/5'); ?> rounded-xl text-sm font-semibold transition group text-left">
                <i class="fas fa-history <?php echo e(request()->routeIs('surveyor.history') ? '' : 'group-hover:text-gold-400'); ?>"></i> 
                Riwayat Data Saya
            </a>

            <a href="<?php echo e(route('surveyor.map')); ?>" 
               class="flex items-center gap-3 px-4 py-3 <?php echo e(request()->routeIs('surveyor.map') ? 'bg-gold-500 text-white font-bold shadow-lg shadow-gold-500/20' : 'text-slate-400 hover:text-white hover:bg-white/5'); ?> rounded-xl text-sm font-semibold transition group text-left">
                <i class="fas fa-map-marked-alt <?php echo e(request()->routeIs('surveyor.map') ? '' : 'group-hover:text-gold-400'); ?>"></i> 
                Peta Sebaran Saya
            </a>
        </nav>
    </div>

    <div class="p-6 border-t border-white/5 text-left bg-navy-950/20 relative">
        

        <form method="POST" action="<?php echo e(route('logout')); ?>">
            <?php echo csrf_field(); ?>
            <button type="submit" class="flex items-center gap-3 px-4 py-3.5 text-red-400 hover:text-red-300 w-full text-left text-sm font-bold transition group rounded-xl hover:bg-red-500/10">
                <i class="fas fa-sign-out-alt group-hover:-translate-x-1 transition-transform"></i> 
                Log Out
            </button>
        </form>
    </div>
</aside>


<aside id="mobile-sidebar" class="fixed top-0 left-0 w-72 h-full bg-navy-900 text-white flex flex-col z-[9999] shadow-2xl transform -translate-x-full transition-transform duration-300 ease-in-out md:hidden">
    <div class="p-6 flex-1 text-left overflow-y-auto">
        
        <div class="flex items-center justify-between mb-8">
            <a href="<?php echo e(route('surveyor.dashboard')); ?>" class="flex items-center gap-3 hover:opacity-80 transition-opacity group">
                <div class="w-8 h-8 bg-white rounded-lg overflow-hidden shadow-lg shadow-gold-500/20">
                    <img src="<?php echo e(asset('logo_geo-sinfra.png')); ?>" class="w-full h-full object-contain" alt="Logo">
                </div>
                <span class="font-extrabold text-xl tracking-tighter uppercase text-white">GEO-SINFRA</span>
            </a>
            <button onclick="toggleMobileMenu()" class="w-8 h-8 text-slate-400 hover:text-white rounded-lg flex items-center justify-center hover:bg-white/10 transition-all">
                <i class="fas fa-times text-sm"></i>
            </button>
        </div>
        
        
        <p class="text-xs font-black text-slate-500 uppercase tracking-[0.2em] mb-3 px-2">Menu Utama</p>
        <nav class="space-y-1">
            <a href="<?php echo e(route('surveyor.dashboard')); ?>" 
               class="flex items-center gap-3 px-4 py-3.5 <?php echo e(request()->routeIs('surveyor.dashboard') ? 'bg-gold-500 text-white font-bold shadow-lg shadow-gold-500/20' : 'text-slate-400 hover:text-white hover:bg-white/5'); ?> rounded-xl text-sm font-semibold transition group text-left">
                <i class="fas fa-th-large text-sm <?php echo e(request()->routeIs('surveyor.dashboard') ? '' : 'group-hover:text-gold-400'); ?>"></i> 
                Dashboard
            </a>

            <a href="<?php echo e(route('surveyor.laporan')); ?>" 
               class="flex items-center gap-3 px-4 py-3.5 <?php echo e(request()->routeIs('surveyor.laporan') ? 'bg-gold-500 text-white font-bold shadow-lg shadow-gold-500/20' : 'text-slate-400 hover:text-white hover:bg-white/5'); ?> rounded-xl text-sm font-semibold transition group text-left">
                <i class="fas fa-tasks text-sm <?php echo e(request()->routeIs('surveyor.laporan') ? '' : 'group-hover:text-gold-400'); ?>"></i> 
                Penugasan Laporan Warga
            </a>

            <a href="<?php echo e(route('surveyor.input')); ?>" 
               class="flex items-center gap-3 px-4 py-3.5 <?php echo e(request()->routeIs('surveyor.input') ? 'bg-gold-500 text-white font-bold shadow-lg shadow-gold-500/20' : 'text-slate-400 hover:text-white hover:bg-white/5'); ?> rounded-xl text-sm font-semibold transition group text-left">
                <i class="fas fa-plus-circle text-sm <?php echo e(request()->routeIs('surveyor.input') ? '' : 'group-hover:text-gold-400'); ?>"></i> 
                Input Data Lapangan
            </a>

            <a href="<?php echo e(route('surveyor.history')); ?>" 
               class="flex items-center gap-3 px-4 py-3.5 <?php echo e(request()->routeIs('surveyor.history') ? 'bg-gold-500 text-white font-bold shadow-lg shadow-gold-500/20' : 'text-slate-400 hover:text-white hover:bg-white/5'); ?> rounded-xl text-sm font-semibold transition group text-left">
                <i class="fas fa-history text-sm <?php echo e(request()->routeIs('surveyor.history') ? '' : 'group-hover:text-gold-400'); ?>"></i> 
                Riwayat Data Saya
            </a>

            <a href="<?php echo e(route('surveyor.map')); ?>" 
               class="flex items-center gap-3 px-4 py-3.5 <?php echo e(request()->routeIs('surveyor.map') ? 'bg-gold-500 text-white font-bold shadow-lg shadow-gold-500/20' : 'text-slate-400 hover:text-white hover:bg-white/5'); ?> rounded-xl text-sm font-semibold transition group text-left">
                <i class="fas fa-map-marked-alt text-sm <?php echo e(request()->routeIs('surveyor.map') ? '' : 'group-hover:text-gold-400'); ?>"></i> 
                Peta Sebaran Saya
            </a>
        </nav>
    </div>

    <div class="p-6 border-t border-white/5 text-left">
        <form method="POST" action="<?php echo e(route('logout')); ?>">
            <?php echo csrf_field(); ?>
            <button type="submit" class="flex items-center gap-3 px-4 py-3.5 text-red-400 hover:text-red-300 w-full text-left text-sm font-bold transition group rounded-xl hover:bg-red-500/10">
                <i class="fas fa-sign-out-alt group-hover:-translate-x-1 transition-transform"></i> 
                Log Out
            </button>
        </form>
    </div>
</aside>


<script>
    function toggleMobileMenu() {
        const sidebar = document.getElementById('mobile-sidebar');
        const overlay = document.getElementById('mobile-overlay');
        const icon = document.getElementById('menu-icon');
        
        const isOpen = !sidebar.classList.contains('-translate-x-full');
        
        if (isOpen) {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('opacity-0');
            setTimeout(() => overlay.classList.add('hidden'), 300);
            icon.classList.remove('fa-times');
            icon.classList.add('fa-bars');
            document.body.style.overflow = '';
        } else {
            overlay.classList.remove('hidden');
            requestAnimationFrame(() => {
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('opacity-0');
            });
            icon.classList.remove('fa-bars');
            icon.classList.add('fa-times');
            document.body.style.overflow = 'hidden';
        }
    }
</script>
<?php /**PATH C:\laragon1\laragon\www\geo-sinfra\resources\views/surveyor/partials/sidebar.blade.php ENDPATH**/ ?>