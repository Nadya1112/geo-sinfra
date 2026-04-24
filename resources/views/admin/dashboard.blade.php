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

    <aside class="w-64 bg-[#1e1b4b] text-white flex flex-col hidden md:flex shadow-2xl z-20 text-left">
        <div class="p-6 flex-1 text-left">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 mb-10 hover:opacity-80 transition-opacity group">
                <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center shadow-lg shadow-blue-500/20 group-hover:scale-110 transition-transform">
                    <i class="fas fa-city text-xs"></i>
                </div>
                <span class="font-extrabold text-xl tracking-tighter uppercase">GEO-SINFRA</span>
            </a>
            
            <nav class="space-y-1">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 bg-blue-600 rounded-xl text-sm font-bold transition shadow-lg shadow-blue-900/20">
                    <i class="fas fa-home"></i> Dashboard
                </a>

                <a href="{{ route('admin.users') }}" class="flex items-center gap-3 px-4 py-3 text-gray-400 hover:text-white hover:bg-white/5 rounded-xl text-sm font-semibold transition group">
                    <i class="fas fa-users-cog group-hover:text-blue-400"></i> Manajemen Pengguna
                </a>

                <a href="{{ route('admin.wilayah') }}" class="flex items-center gap-3 px-4 py-3 text-gray-400 hover:text-white hover:bg-white/5 rounded-xl text-sm font-semibold transition group">
                    <i class="fas fa-draw-polygon group-hover:text-blue-400"></i> Manajemen Wilayah
                </a>

                <a href="#" class="flex items-center gap-3 px-4 py-3 text-gray-400 hover:text-white hover:bg-white/5 rounded-xl text-sm font-semibold transition group">
                    <i class="fas fa-database group-hover:text-blue-400"></i> Manajemen Infrastruktur
                </a>

                <a href="{{ route('admin.peta') }}" class="flex items-center gap-3 px-4 py-3 text-gray-400 hover:text-white hover:bg-white/5 rounded-xl text-sm font-semibold transition group">
                    <i class="fas fa-map-marked-alt group-hover:text-blue-400"></i> Peta Spasial
                </a>

                <a href="{{ route('admin.statistik') }}" class="flex items-center gap-3 px-4 py-3 text-gray-400 hover:text-white hover:bg-white/5 rounded-xl text-sm font-semibold transition group">
                    <i class="fas fa-chart-bar group-hover:text-blue-400"></i> Statistik dan Laporan
                </a>
            </nav>
        </div>

        <div class="p-6 border-t border-white/5">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="flex items-center gap-3 text-red-400 hover:text-red-300 w-full text-left text-sm font-bold transition group">
                    <i class="fas fa-sign-out-alt group-hover:-translate-x-1 transition-transform"></i> Keluar Sistem
                </button>
            </form>
        </div>
    </aside>

    <main class="flex-1 overflow-y-auto custom-scrollbar text-left">
        <header class="sticky top-0 bg-white/80 backdrop-blur-md border-b border-gray-100 px-8 py-5 flex justify-between items-center z-40 text-left">
            <div class="text-left">
                <p class="text-[10px] font-extrabold text-blue-600 uppercase tracking-[0.2em] mb-1">Administrator Portal</p>
                <h2 class="text-xl font-black text-[#1e1b4b]">Beranda Utama</h2>
            </div>

            <div class="flex items-center gap-6">
                <div class="text-right hidden sm:block">
                    <p class="text-[11px] font-black text-[#1e1b4b]" id="mini-clock">00:00 WITA</p>
                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-tighter">{{ now()->translatedFormat('l, d F Y') }}</p>
                </div>
                <div class="h-8 w-[1px] bg-gray-100"></div>
                <div class="flex items-center gap-3">
                    <div class="text-right text-left">
                        <p class="text-[11px] font-black text-[#1e1b4b] leading-none uppercase">Admin SINFRA</p>
                        <p class="text-[9px] font-bold text-green-500 uppercase mt-1">Online</p>
                    </div>
                    <div class="w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600 border border-indigo-100">
                        <i class="fas fa-user-circle text-xl"></i>
                    </div>
                </div>
            </div>
        </header>

        <div class="p-8 text-left">
            
            <div class="relative bg-gradient-to-br from-blue-600 to-indigo-800 rounded-[2.5rem] p-10 mb-8 overflow-hidden shadow-lg shadow-blue-900/10 text-left">
                <div class="absolute inset-0 bg-pattern opacity-50"></div>
                <div class="absolute -right-10 -top-10 w-64 h-64 bg-white opacity-5 rounded-full blur-3xl"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6 text-left">
                    <div class="text-left">
                        <p class="text-blue-200 text-sm font-bold tracking-widest uppercase mb-2">Portal SIGAP-K</p>
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

            <div class="mb-8 text-left">
                <h4 class="font-extrabold text-lg text-[#1e1b4b] mb-6">Akses Cepat</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 text-left">
                    
                    <a href="{{ route('admin.users.create') }}" class="group bg-white p-6 rounded-3xl border border-gray-100 shadow-sm hover:shadow-xl hover:shadow-blue-500/10 hover:border-blue-200 transition-all text-left">
                        <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                            <i class="fas fa-user-plus text-lg"></i>
                        </div>
                        <h5 class="font-black text-[#1e1b4b] mb-1">Tambah User</h5>
                        <p class="text-[10px] text-gray-400 font-medium leading-relaxed text-left">Daftarkan Surveyor atau Admin baru ke dalam sistem.</p>
                    </a>

                    <a href="{{ route('admin.wilayah') }}" class="group bg-white p-6 rounded-3xl border border-gray-100 shadow-sm hover:shadow-xl hover:shadow-indigo-500/10 hover:border-indigo-200 transition-all text-left">
                        <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                            <i class="fas fa-draw-polygon text-lg"></i>
                        </div>
                        <h5 class="font-black text-[#1e1b4b] mb-1">Kelola Wilayah</h5>
                        <p class="text-[10px] text-gray-400 font-medium leading-relaxed text-left">Atur batas kecamatan dan zonasi warna pada peta.</p>
                    </a>

                    <a href="{{ route('admin.peta') }}" class="group bg-white p-6 rounded-3xl border border-gray-100 shadow-sm hover:shadow-xl hover:shadow-emerald-500/10 hover:border-emerald-200 transition-all text-left">
                        <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                            <i class="fas fa-map-marked-alt text-lg"></i>
                        </div>
                        <h5 class="font-black text-[#1e1b4b] mb-1">Lihat Peta</h5>
                        <p class="text-[10px] text-gray-400 font-medium leading-relaxed text-left">Pantau titik persebaran infrastruktur secara real-time.</p>
                    </a>

                    <a href="{{ route('admin.statistik') }}" class="group bg-white p-6 rounded-3xl border border-gray-100 shadow-sm hover:shadow-xl hover:shadow-purple-500/10 hover:border-purple-200 transition-all text-left">
                        <div class="w-12 h-12 bg-purple-50 text-purple-600 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                            <i class="fas fa-chart-pie text-lg"></i>
                        </div>
                        <h5 class="font-black text-[#1e1b4b] mb-1">Statistik Data</h5>
                        <p class="text-[10px] text-gray-400 font-medium leading-relaxed text-left">Lihat rekapitulasi data dan prediksi prioritas harian.</p>
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
</html>