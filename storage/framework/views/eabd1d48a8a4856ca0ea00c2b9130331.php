
<button id="mobile-menu-btn" onclick="toggleMobileMenu()" class="fixed top-4 left-4 z-[9999] w-10 h-10 bg-navy-900 text-gold-500 rounded-xl flex items-center justify-center shadow-lg md:hidden border border-white/10 hover:bg-navy-800 transition-all active:scale-95">
    <i class="fas fa-bars text-sm" id="menu-icon"></i>
</button>


<div id="mobile-overlay" onclick="toggleMobileMenu()" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-[9998] hidden md:hidden transition-opacity duration-300 opacity-0"></div>


<aside class="w-64 bg-[#0f0e2c] text-white flex-col hidden md:flex shadow-2xl z-20 text-left border-r border-white/5 shrink-0 h-screen">
    <div class="p-6 flex-1 text-left overflow-y-auto custom-scrollbar">
        <a href="<?php echo e(route('admin.dashboard')); ?>" class="flex items-center gap-3 mb-10 hover:opacity-85 transition-opacity group">
            <div class="w-9 h-9 bg-white rounded-xl overflow-hidden shadow-lg shadow-navy-950/40 group-hover:scale-105 transition-all">
                <img src="<?php echo e(asset('logo_geo-sinfra.png')); ?>" class="w-full h-full object-contain" alt="Logo">
            </div>
            <span class="font-extrabold text-lg tracking-tighter uppercase text-white">GEO-SINFRA</span>
        </a>
        
        <nav class="space-y-1.5">
            <a href="<?php echo e(route('admin.dashboard')); ?>" 
               class="flex items-center gap-3 px-4 py-3.5 <?php echo e(request()->routeIs('admin.dashboard') ? 'bg-gold-500 text-navy-950 font-bold shadow-xl shadow-gold-500/10' : 'text-slate-400 hover:text-white hover:bg-white/5'); ?> rounded-xl text-sm font-semibold transition group text-left">
                <i class="fas fa-home <?php echo e(request()->routeIs('admin.dashboard') ? '' : 'group-hover:text-gold-500'); ?>"></i> 
                Dashboard
            </a>

            <a href="<?php echo e(route('admin.users')); ?>" 
               class="flex items-center gap-3 px-4 py-3.5 <?php echo e(request()->routeIs('admin.users*') ? 'bg-gold-500 text-navy-950 font-bold shadow-xl shadow-gold-500/10' : 'text-slate-400 hover:text-white hover:bg-white/5'); ?> rounded-xl text-sm font-semibold transition group text-left">
                <i class="fas fa-users-cog <?php echo e(request()->routeIs('admin.users*') ? '' : 'group-hover:text-gold-500'); ?>"></i> 
                Manajemen Pengguna
            </a>

            <a href="<?php echo e(route('admin.wilayah')); ?>" 
               class="flex items-center gap-3 px-4 py-3.5 <?php echo e(request()->routeIs('admin.wilayah*') ? 'bg-gold-500 text-navy-950 font-bold shadow-xl shadow-gold-500/10' : 'text-slate-400 hover:text-white hover:bg-white/5'); ?> rounded-xl text-sm font-semibold transition group text-left">
                <i class="fas fa-sitemap <?php echo e(request()->routeIs('admin.wilayah*') ? '' : 'group-hover:text-gold-500'); ?>"></i> 
                Manajemen Wilayah
            </a>

            <a href="<?php echo e(route('admin.infrastruktur')); ?>" 
               class="flex items-center gap-3 px-4 py-3.5 <?php echo e(request()->routeIs('admin.infrastruktur*') ? 'bg-gold-500 text-navy-950 font-bold shadow-xl shadow-gold-500/10' : 'text-slate-400 hover:text-white hover:bg-white/5'); ?> rounded-xl text-sm font-semibold transition group text-left">
                <i class="fas fa-database <?php echo e(request()->routeIs('admin.infrastruktur*') ? '' : 'group-hover:text-gold-500'); ?>"></i> 
                Manajemen Infrastruktur
            </a>

            <a href="<?php echo e(route('admin.laporan-warga')); ?>" 
               class="flex items-center justify-between px-4 py-3.5 <?php echo e(request()->routeIs('admin.laporan-warga*') ? 'bg-gold-500 text-navy-950 font-bold shadow-xl shadow-gold-500/10' : 'text-slate-400 hover:text-white hover:bg-white/5'); ?> rounded-xl text-sm font-semibold transition group text-left relative w-full">
                <div class="flex items-center gap-3">
                    <i class="fas fa-bullhorn <?php echo e(request()->routeIs('admin.laporan-warga*') ? '' : 'group-hover:text-gold-500'); ?>"></i> 
                    <span>Laporan Warga</span>
                </div>
                <?php if(isset($laporanMenungguCount) && $laporanMenungguCount > 0): ?>
                <span class="bg-red-500 text-white text-[10px] font-black px-1.5 py-0.5 rounded-md min-w-[20px] text-center shadow-lg"><?php echo e($laporanMenungguCount); ?></span>
                <?php endif; ?>
            </a>

            <a href="<?php echo e(route('admin.statistik')); ?>" 
               class="flex items-center gap-3 px-4 py-3.5 <?php echo e(request()->routeIs('admin.statistik') ? 'bg-gold-500 text-navy-950 font-bold shadow-xl shadow-gold-500/10' : 'text-slate-400 hover:text-white hover:bg-white/5'); ?> rounded-xl text-sm font-semibold transition group text-left">
                <i class="fas fa-chart-bar <?php echo e(request()->routeIs('admin.statistik') ? '' : 'group-hover:text-gold-500'); ?>"></i> 
                Ringkasan Statistik
            </a>

            <a href="<?php echo e(route('admin.statistik.tahunan')); ?>" 
               class="flex items-center gap-3 px-4 py-3.5 <?php echo e(request()->routeIs('admin.statistik.tahunan') ? 'bg-gold-500 text-navy-950 font-bold shadow-xl shadow-gold-500/10' : 'text-slate-400 hover:text-white hover:bg-white/5'); ?> rounded-xl text-sm font-semibold transition group text-left">
                <i class="fas fa-calendar-alt <?php echo e(request()->routeIs('admin.statistik.tahunan') ? '' : 'group-hover:text-gold-500'); ?>"></i> 
                Statistik Tahunan
            </a>

            <div class="pt-4 mt-2 border-t border-white/5">
                <p class="text-[8px] font-black text-slate-500 uppercase tracking-[0.2em] mb-3 px-2">Sistem & Keamanan</p>
                <a href="<?php echo e(route('admin.activity')); ?>" 
                   class="flex items-center gap-3 px-4 py-3.5 <?php echo e(request()->routeIs('admin.activity') ? 'bg-gold-500 text-navy-950 font-bold shadow-xl shadow-gold-500/10' : 'text-slate-400 hover:text-white hover:bg-white/5'); ?> rounded-xl text-sm font-semibold transition group text-left">
                    <i class="fas fa-shield-alt <?php echo e(request()->routeIs('admin.activity') ? '' : 'group-hover:text-gold-500'); ?>"></i> 
                    Log Aktivitas
                </a>
                
                <!-- Simulasi AI -->
                <a href="<?php echo e(route('admin.simulasi-ai')); ?>" 
                   class="flex items-center gap-3 px-4 py-3.5 <?php echo e(request()->routeIs('admin.simulasi-ai') ? 'bg-gold-500 text-navy-950 font-bold shadow-xl shadow-gold-500/10' : 'text-slate-400 hover:text-white hover:bg-white/5'); ?> rounded-xl text-sm font-semibold transition group text-left mt-1">
                    <i class="fas fa-robot <?php echo e(request()->routeIs('admin.simulasi-ai') ? '' : 'group-hover:text-gold-500'); ?>"></i> 
                    Simulasi Model AI
                </a>
            </div>
        </nav>
    </div>

    <div class="p-6 border-t border-white/5 text-left bg-navy-950/20 relative">
        <!-- Theme Switcher (Desktop) -->
        <div class="mb-2 relative">
            <button onclick="toggleThemeMenu('theme-menu-desktop')" class="flex items-center justify-between w-full px-4 py-3 text-slate-400 hover:text-white hover:bg-white/5 rounded-xl text-sm font-bold transition group">
                <div class="flex items-center gap-3">
                    <i class="fas fa-palette group-hover:text-gold-500 transition-colors"></i>
                    <span>Tema Tampilan</span>
                </div>
                <i class="fas fa-chevron-up text-[10px] opacity-50"></i>
            </button>
            
            <div id="theme-menu-desktop" class="hidden absolute bottom-14 left-0 w-full bg-[#1e1b4b] rounded-xl shadow-2xl border border-white/10 p-1.5 z-50 mb-1">
                <button onclick="setTheme('light')" class="w-full text-left px-3 py-2 text-xs font-bold text-slate-300 hover:text-white hover:bg-white/10 rounded-lg flex items-center gap-2 transition-colors">
                    <i class="fas fa-sun text-yellow-400 w-4 text-center"></i> Terang
                </button>
                <button onclick="setTheme('dark')" class="w-full text-left px-3 py-2 text-xs font-bold text-slate-300 hover:text-white hover:bg-white/10 rounded-lg flex items-center gap-2 transition-colors mt-0.5">
                    <i class="fas fa-moon text-blue-400 w-4 text-center"></i> Gelap
                </button>
                <button onclick="setTheme('system')" class="w-full text-left px-3 py-2 text-xs font-bold text-slate-300 hover:text-white hover:bg-white/10 rounded-lg flex items-center gap-2 transition-colors mt-0.5 border-t border-white/5 pt-2">
                    <i class="fas fa-desktop text-slate-400 w-4 text-center"></i> Sesuai Sistem
                </button>
            </div>
        </div>

        <form method="POST" action="<?php echo e(route('logout')); ?>">
            <?php echo csrf_field(); ?>
            <button type="submit" class="flex items-center gap-3 px-4 py-3 text-red-400 hover:text-red-300 w-full text-left text-sm font-bold transition group hover:bg-red-500/10 rounded-xl">
                <i class="fas fa-sign-out-alt group-hover:-translate-x-1 transition-transform"></i> 
                Log Out
            </button>
        </form>
    </div>
</aside>


<aside id="mobile-sidebar" class="fixed top-0 left-0 w-72 h-full bg-[#0f0e2c] text-white flex flex-col z-[9999] shadow-2xl transform -translate-x-full transition-transform duration-300 ease-in-out md:hidden">
    <div class="p-6 flex-1 text-left overflow-y-auto">
        <div class="flex items-center justify-between mb-8">
            <a href="<?php echo e(route('admin.dashboard')); ?>" class="flex items-center gap-3 hover:opacity-85 transition-opacity group">
                <div class="w-9 h-9 bg-white rounded-xl overflow-hidden shadow-lg shadow-navy-950/40">
                    <img src="<?php echo e(asset('logo_geo-sinfra.png')); ?>" class="w-full h-full object-contain" alt="Logo">
                </div>
                <span class="font-extrabold text-lg tracking-tighter uppercase text-white">GEO-SINFRA</span>
            </a>
            <button onclick="toggleMobileMenu()" class="w-8 h-8 text-slate-400 hover:text-white rounded-lg flex items-center justify-center hover:bg-white/10 transition-all">
                <i class="fas fa-times text-sm"></i>
            </button>
        </div>

        
        <a href="<?php echo e(route('admin.profile')); ?>" class="flex items-center gap-3 p-3 bg-white/5 rounded-2xl mb-6 border border-white/5 hover:bg-white/10 transition-all group">
            <div class="w-10 h-10 bg-navy-800 rounded-xl flex items-center justify-center text-gold-500 overflow-hidden border border-white/10">
                <?php if(auth()->user()->profile_photo): ?>
                    <img src="<?php echo e(asset('storage/' . auth()->user()->profile_photo)); ?>" class="w-full h-full object-cover">
                <?php else: ?>
                    <i class="fas fa-user-circle text-lg"></i>
                <?php endif; ?>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-xs font-black text-white uppercase truncate"><?php echo e(auth()->user()->name); ?></p>
                <p class="text-[9px] font-bold text-emerald-400 uppercase mt-0.5">● Online</p>
            </div>
            <i class="fas fa-chevron-right text-[8px] text-slate-500 group-hover:text-gold-400 transition-colors"></i>
        </a>
        
        <p class="text-[8px] font-black text-slate-500 uppercase tracking-[0.2em] mb-3 px-2">Menu Utama</p>
        <nav class="space-y-1.5">
            <a href="<?php echo e(route('admin.dashboard')); ?>" 
               class="flex items-center gap-3 px-4 py-3.5 <?php echo e(request()->routeIs('admin.dashboard') ? 'bg-gold-500 text-navy-950 font-bold shadow-xl shadow-gold-500/10' : 'text-slate-400 hover:text-white hover:bg-white/5'); ?> rounded-xl text-sm font-semibold transition group text-left">
                <i class="fas fa-home <?php echo e(request()->routeIs('admin.dashboard') ? '' : 'group-hover:text-gold-500'); ?>"></i> 
                Dashboard
            </a>

            <a href="<?php echo e(route('admin.users')); ?>" 
               class="flex items-center gap-3 px-4 py-3.5 <?php echo e(request()->routeIs('admin.users*') ? 'bg-gold-500 text-navy-950 font-bold shadow-xl shadow-gold-500/10' : 'text-slate-400 hover:text-white hover:bg-white/5'); ?> rounded-xl text-sm font-semibold transition group text-left">
                <i class="fas fa-users-cog <?php echo e(request()->routeIs('admin.users*') ? '' : 'group-hover:text-gold-500'); ?>"></i> 
                Manajemen Pengguna
            </a>

            <a href="<?php echo e(route('admin.wilayah')); ?>" 
               class="flex items-center gap-3 px-4 py-3.5 <?php echo e(request()->routeIs('admin.wilayah*') ? 'bg-gold-500 text-navy-950 font-bold shadow-xl shadow-gold-500/10' : 'text-slate-400 hover:text-white hover:bg-white/5'); ?> rounded-xl text-sm font-semibold transition group text-left">
                <i class="fas fa-sitemap <?php echo e(request()->routeIs('admin.wilayah*') ? '' : 'group-hover:text-gold-500'); ?>"></i> 
                Manajemen Wilayah
            </a>

            <a href="<?php echo e(route('admin.infrastruktur')); ?>" 
               class="flex items-center gap-3 px-4 py-3.5 <?php echo e(request()->routeIs('admin.infrastruktur*') ? 'bg-gold-500 text-navy-950 font-bold shadow-xl shadow-gold-500/10' : 'text-slate-400 hover:text-white hover:bg-white/5'); ?> rounded-xl text-sm font-semibold transition group text-left">
                <i class="fas fa-database <?php echo e(request()->routeIs('admin.infrastruktur*') ? '' : 'group-hover:text-gold-500'); ?>"></i> 
                Manajemen Infrastruktur
            </a>

            <a href="<?php echo e(route('admin.laporan-warga')); ?>" 
               class="flex items-center justify-between px-4 py-3.5 <?php echo e(request()->routeIs('admin.laporan-warga*') ? 'bg-gold-500 text-navy-950 font-bold shadow-xl shadow-gold-500/10' : 'text-slate-400 hover:text-white hover:bg-white/5'); ?> rounded-xl text-sm font-semibold transition group text-left relative w-full">
                <div class="flex items-center gap-3">
                    <i class="fas fa-bullhorn <?php echo e(request()->routeIs('admin.laporan-warga*') ? '' : 'group-hover:text-gold-500'); ?>"></i> 
                    <span>Laporan Warga</span>
                </div>
                <?php if(isset($laporanMenungguCount) && $laporanMenungguCount > 0): ?>
                <span class="bg-red-500 text-white text-[10px] font-black px-1.5 py-0.5 rounded-md min-w-[20px] text-center shadow-lg"><?php echo e($laporanMenungguCount); ?></span>
                <?php endif; ?>
            </a>

            <a href="<?php echo e(route('admin.statistik')); ?>" 
               class="flex items-center gap-3 px-4 py-3.5 <?php echo e(request()->routeIs('admin.statistik') ? 'bg-gold-500 text-navy-950 font-bold shadow-xl shadow-gold-500/10' : 'text-slate-400 hover:text-white hover:bg-white/5'); ?> rounded-xl text-sm font-semibold transition group text-left">
                <i class="fas fa-chart-bar <?php echo e(request()->routeIs('admin.statistik') ? '' : 'group-hover:text-gold-500'); ?>"></i> 
                Ringkasan Statistik
            </a>

            <a href="<?php echo e(route('admin.statistik.tahunan')); ?>" 
               class="flex items-center gap-3 px-4 py-3.5 <?php echo e(request()->routeIs('admin.statistik.tahunan') ? 'bg-gold-500 text-navy-950 font-bold shadow-xl shadow-gold-500/10' : 'text-slate-400 hover:text-white hover:bg-white/5'); ?> rounded-xl text-sm font-semibold transition group text-left">
                <i class="fas fa-calendar-alt <?php echo e(request()->routeIs('admin.statistik.tahunan') ? '' : 'group-hover:text-gold-500'); ?>"></i> 
                Statistik Tahunan
            </a>

            <div class="pt-4 mt-2 border-t border-white/5">
                <p class="text-[8px] font-black text-slate-500 uppercase tracking-[0.2em] mb-3 px-2">Sistem & Keamanan</p>
                <a href="<?php echo e(route('admin.activity')); ?>" 
                   class="flex items-center gap-3 px-4 py-3.5 <?php echo e(request()->routeIs('admin.activity') ? 'bg-gold-500 text-navy-950 font-bold shadow-xl shadow-gold-500/10' : 'text-slate-400 hover:text-white hover:bg-white/5'); ?> rounded-xl text-sm font-semibold transition group text-left">
                    <i class="fas fa-shield-alt <?php echo e(request()->routeIs('admin.activity') ? '' : 'group-hover:text-gold-500'); ?>"></i> 
                    Log Aktivitas
                </a>
                
                <!-- Simulasi AI -->
                <a href="<?php echo e(route('admin.simulasi-ai')); ?>" 
                   class="flex items-center gap-3 px-4 py-3.5 <?php echo e(request()->routeIs('admin.simulasi-ai') ? 'bg-gold-500 text-navy-950 font-bold shadow-xl shadow-gold-500/10' : 'text-slate-400 hover:text-white hover:bg-white/5'); ?> rounded-xl text-sm font-semibold transition group text-left mt-1">
                    <i class="fas fa-robot <?php echo e(request()->routeIs('admin.simulasi-ai') ? '' : 'group-hover:text-gold-500'); ?>"></i> 
                    Simulasi Model AI
                </a>
            </div>
        </nav>
    </div>

    <div class="p-6 border-t border-white/5 text-left bg-navy-950/20 relative">
        <!-- Theme Switcher (Mobile) -->
        <div class="mb-2 relative">
            <button onclick="toggleThemeMenu('theme-menu-mobile')" class="flex items-center justify-between w-full px-4 py-3.5 text-slate-400 hover:text-white hover:bg-white/5 rounded-xl text-sm font-bold transition group">
                <div class="flex items-center gap-3">
                    <i class="fas fa-palette group-hover:text-gold-500 transition-colors"></i>
                    <span>Tema Tampilan</span>
                </div>
                <i class="fas fa-chevron-up text-[10px] opacity-50"></i>
            </button>
            
            <div id="theme-menu-mobile" class="hidden absolute bottom-16 left-0 w-full bg-[#1e1b4b] rounded-xl shadow-2xl border border-white/10 p-1.5 z-50 mb-1">
                <button onclick="setTheme('light')" class="w-full text-left px-3 py-2.5 text-xs font-bold text-slate-300 hover:text-white hover:bg-white/10 rounded-lg flex items-center gap-2 transition-colors">
                    <i class="fas fa-sun text-yellow-400 w-4 text-center"></i> Terang
                </button>
                <button onclick="setTheme('dark')" class="w-full text-left px-3 py-2.5 text-xs font-bold text-slate-300 hover:text-white hover:bg-white/10 rounded-lg flex items-center gap-2 transition-colors mt-0.5">
                    <i class="fas fa-moon text-blue-400 w-4 text-center"></i> Gelap
                </button>
                <button onclick="setTheme('system')" class="w-full text-left px-3 py-2.5 text-xs font-bold text-slate-300 hover:text-white hover:bg-white/10 rounded-lg flex items-center gap-2 transition-colors mt-0.5 border-t border-white/5 pt-2">
                    <i class="fas fa-desktop text-slate-400 w-4 text-center"></i> Sesuai Sistem
                </button>
            </div>
        </div>

        <form method="POST" action="<?php echo e(route('logout')); ?>">
            <?php echo csrf_field(); ?>
            <button type="submit" class="flex items-center gap-3 px-4 py-3.5 text-red-400 hover:text-red-300 w-full text-left text-sm font-bold transition group rounded-xl hover:bg-red-500/10">
                <i class="fas fa-sign-out-alt group-hover:-translate-x-1 transition-transform"></i> 
                Keluar Sistem
            </button>
        </form>
    </div>
</aside>

<!-- Real-time Notification Toast Container -->
<div id="notification-container" class="fixed bottom-6 right-6 z-[9999] flex flex-col gap-3"></div>

<!-- Notification Audio Element -->
<audio id="notification-sound" src="https://actions.google.com/sounds/v1/alarms/beep_short.ogg" preload="auto"></audio>

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

    function toggleThemeMenu(menuId) {
        const menu = document.getElementById(menuId);
        menu.classList.toggle('hidden');
    }

    function setTheme(theme) {
        if (theme === 'system') {
            localStorage.removeItem('theme');
            if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        } else {
            localStorage.theme = theme;
            if (theme === 'dark') {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        }
        
        // Sembunyikan menu setelah memilih
        document.getElementById('theme-menu-desktop').classList.add('hidden');
        document.getElementById('theme-menu-mobile').classList.add('hidden');
    }

    // --- REAL-TIME NOTIFICATION SYSTEM ---
    let lastCheckedTime = Math.floor(Date.now() / 1000); // Waktu saat halaman dimuat (Unix Timestamp)

    function checkNewLaporan() {
        fetch(`/api/check-laporan?last_checked=${lastCheckedTime}`)
            .then(response => response.json())
            .then(data => {
                if (data.count > 0) {
                    // Update lastCheckedTime agar tidak memunculkan notifikasi yang sama dua kali
                    lastCheckedTime = data.timestamp;
                    
                    // Mainkan Suara
                    const audio = document.getElementById('notification-sound');
                    if (audio) {
                        audio.volume = 0.5;
                        audio.play().catch(e => console.log('Audio autoplay blocked by browser'));
                    }

                    // Tampilkan Toast untuk setiap laporan baru
                    data.reports.forEach(report => {
                        showToastNotification(
                            'Laporan Warga Baru Masuk!',
                            `${report.nama_pelapor} melaporkan masalah infrastruktur. Segera tinjau!`,
                            `/admin/laporan-warga/${report.id_laporan}`
                        );
                    });
                }
            })
            .catch(error => console.error('Error checking new laporan:', error));
    }

    function showToastNotification(title, message, link) {
        const container = document.getElementById('notification-container');
        const toastId = 'toast-' + Math.random().toString(36).substr(2, 9);
        
        const toastHTML = `
            <div id="${toastId}" class="bg-white dark:bg-navy-900 border border-slate-100 dark:border-white/10 rounded-2xl p-4 shadow-2xl flex items-start gap-4 transform translate-x-full transition-transform duration-500 ease-out max-w-sm">
                <div class="w-10 h-10 bg-gold-500/10 text-gold-500 rounded-xl flex items-center justify-center shrink-0">
                    <i class="fas fa-bell animate-pulse text-lg"></i>
                </div>
                <div class="flex-1">
                    <h5 class="text-xs font-black text-navy-900 dark:text-white mb-1 leading-tight">${title}</h5>
                    <p class="text-[10px] font-medium text-slate-500 dark:text-slate-400 mb-2">${message}</p>
                    <a href="${link}" class="inline-block bg-gold-500 text-white text-[9px] font-bold px-3 py-1.5 rounded-lg hover:bg-gold-600 transition-colors">Lihat Detail</a>
                </div>
                <button onclick="document.getElementById('${toastId}').remove()" class="text-slate-400 hover:text-red-500 transition-colors">
                    <i class="fas fa-times text-xs"></i>
                </button>
            </div>
        `;
        
        container.insertAdjacentHTML('beforeend', toastHTML);
        
        // Animasi masuk
        setTimeout(() => {
            document.getElementById(toastId).classList.remove('translate-x-full');
        }, 100);

        // Hapus otomatis setelah 10 detik
        setTimeout(() => {
            const el = document.getElementById(toastId);
            if(el) {
                el.classList.add('translate-x-full');
                setTimeout(() => el.remove(), 500);
            }
        }, 10000);
    }

    // Jalankan pengecekan setiap 15 detik
    setInterval(checkNewLaporan, 15000);
</script>
<?php /**PATH C:\laragon1\laragon\www\geo-sinfra\resources\views/admin/partials/sidebar.blade.php ENDPATH**/ ?>