<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | GEO-SINFRA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 flex h-screen overflow-hidden text-gray-800">

    <aside class="w-64 bg-[#1e1b4b] text-white flex flex-col hidden md:flex shadow-2xl">
        <div class="p-6">
            <div class="flex items-center gap-3 mb-10">
                <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center">
                    <i class="fas fa-city text-xs"></i>
                </div>
                <span class="font-extrabold text-xl tracking-tighter">GEO-SINFRA</span>
            </div>
            
            <nav class="space-y-1">
                <a href="#" class="flex items-center gap-3 px-4 py-3 bg-blue-600 rounded-xl text-sm font-bold transition shadow-lg shadow-blue-900/20">
                    <i class="fas fa-th-large"></i> Dashboard
                </a>
                <a href="{{ url('/admin/peta') }}" class="flex items-center gap-3 px-4 py-3 text-gray-400 hover:text-white hover:bg-white/5 rounded-xl text-sm font-semibold transition group">
                    <i class="fas fa-map-marked-alt group-hover:text-blue-400"></i> Peta Spasial
                </a>
                <a href="#" class="flex items-center gap-3 px-4 py-3 text-gray-400 hover:text-white hover:bg-white/5 rounded-xl text-sm font-semibold transition group">
                    <i class="fas fa-database group-hover:text-blue-400"></i> Data Infrastruktur
                </a>
                <a href="#" class="flex items-center gap-3 px-4 py-3 text-gray-400 hover:text-white hover:bg-white/5 rounded-xl text-sm font-semibold transition group">
                    <i class="fas fa-brain group-hover:text-blue-400"></i> Analisis Hybrid AI
                </a>
            </nav>
        </div>
        
        <div class="mt-auto p-6 border-t border-white/5">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="flex items-center gap-3 text-red-400 hover:text-red-300 text-sm font-bold transition">
                    <i class="fas fa-sign-out-alt"></i> Keluar Sistem
                </button>
            </form>
        </div>
    </aside>

    <main class="flex-1 overflow-y-auto">
        
        <header class="sticky top-0 bg-white/80 backdrop-blur-md border-b border-gray-100 px-8 py-5 flex justify-between items-center z-40">
            <div>
                <p class="text-[10px] font-extrabold text-blue-600 uppercase tracking-[0.2em] mb-1">Administrator Portal</p>
                <h2 class="text-xl font-black text-[#1e1b4b]">Ringkasan Statistik</h2>
            </div>

            <div class="flex items-center gap-6">
                <div class="text-right hidden sm:block">
                    <p class="text-[11px] font-black text-[#1e1b4b]" id="mini-clock">00:00 WITA</p>
                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-tighter">{{ now()->translatedFormat('l, d F Y') }}</p>
                </div>
                <div class="h-8 w-[1px] bg-gray-100"></div>
                <div class="flex items-center gap-3">
                    <div class="text-right">
                        <p class="text-xs font-bold text-gray-800 leading-none">{{ auth()->user()->name }}</p>
                        <p class="text-[9px] font-bold text-green-500 uppercase mt-1">Online</p>
                    </div>
                    <div class="w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600 border border-indigo-100">
                        <i class="fas fa-user-circle text-xl"></i>
                    </div>
                </div>
            </div>
        </header>

        <div class="p-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm transition-transform hover:scale-[1.02]">
                    <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center mb-4">
                        <i class="fas fa-road"></i>
                    </div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Infrastruktur</p>
                    <h3 class="text-2xl font-black text-[#1e1b4b]">{{ $jumlahInfrastruktur }} <span class="text-xs font-medium text-gray-400">Objek</span></h3>
                </div>

                <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm transition-transform hover:scale-[1.02]">
                    <div class="w-10 h-10 bg-purple-50 text-purple-600 rounded-xl flex items-center justify-center mb-4">
                        <i class="fas fa-microchip"></i>
                    </div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Analisis AI</p>
                    <h3 class="text-2xl font-black text-[#1e1b4b]">{{ $jumlahAnalisis }} <span class="text-xs font-medium text-gray-400">Data</span></h3>
                </div>

                <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm transition-transform hover:scale-[1.02]">
                    <div class="w-10 h-10 bg-orange-50 text-orange-600 rounded-xl flex items-center justify-center mb-4">
                        <i class="fas fa-user-check"></i>
                    </div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Surveyor</p>
                    <h3 class="text-2xl font-black text-[#1e1b4b]">{{ $jumlahSurveyor }} <span class="text-xs font-medium text-gray-400">User</span></h3>
                </div>

                <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm transition-transform hover:scale-[1.02]">
                    <div class="w-10 h-10 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center mb-4">
                        <i class="fas fa-draw-polygon"></i>
                    </div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Wilayah</p>
                    <h3 class="text-2xl font-black text-[#1e1b4b]">{{ $jumlahWilayah }} <span class="text-xs font-medium text-gray-400">Area</span></h3>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <div class="lg:col-span-2 bg-white rounded-[2.5rem] p-8 border border-gray-100 shadow-sm">
                    <div class="flex justify-between items-center mb-8">
                        <div>
                            <h4 class="font-extrabold text-lg text-[#1e1b4b]">Prediksi Prioritas Perbaikan</h4>
                            <p class="text-xs text-gray-400 font-medium">Berdasarkan klasifikasi Hybrid Model (CNN)</p>
                        </div>
                        <button class="text-[10px] font-bold bg-gray-50 px-4 py-2 rounded-xl border border-gray-100 hover:bg-gray-100 transition">Lihat Semua</button>
                    </div>

                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-4 bg-red-50/50 rounded-2xl border border-red-100/50">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 bg-red-500 text-white rounded-xl flex items-center justify-center shadow-lg shadow-red-200">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-red-900 leading-none">Prioritas Tinggi (Sangat Rusak)</p>
                                    <p class="text-[10px] text-red-600 mt-1">48 Titik ditemukan di Banjarmasin Selatan</p>
                                </div>
                            </div>
                            <i class="fas fa-chevron-right text-red-300 text-xs"></i>
                        </div>

                        <div class="flex items-center justify-between p-4 bg-yellow-50/50 rounded-2xl border border-yellow-100/50">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 bg-yellow-500 text-white rounded-xl flex items-center justify-center shadow-lg shadow-yellow-200">
                                    <i class="fas fa-tools"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-yellow-900 leading-none">Prioritas Sedang (Rusak Ringan)</p>
                                    <p class="text-[10px] text-yellow-600 mt-1">102 Titik butuh pemeliharaan berkala</p>
                                </div>
                            </div>
                            <i class="fas fa-chevron-right text-yellow-300 text-xs"></i>
                        </div>

                        <div class="flex items-center justify-between p-4 bg-emerald-50/50 rounded-2xl border border-emerald-100/50">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 bg-emerald-500 text-white rounded-xl flex items-center justify-center shadow-lg shadow-emerald-200">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-emerald-900 leading-none">Kondisi Baik (Normal)</p>
                                    <p class="text-[10px] text-emerald-600 mt-1">215 Titik dalam kondisi layak dan stabil</p>
                                </div>
                            </div>
                            <i class="fas fa-chevron-right text-emerald-300 text-xs"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-[2.5rem] p-8 border border-gray-100 shadow-sm">
                    <h4 class="font-extrabold text-lg text-[#1e1b4b] mb-8">Log Aktivitas</h4>
                    <div class="relative">
                        <div class="absolute left-[15px] top-0 h-full w-[2px] bg-gray-50"></div>
                        
                        <div class="space-y-8 relative">
                            <div class="flex gap-4">
                                <div class="w-8 h-8 bg-blue-100 rounded-full border-4 border-white z-10 flex items-center justify-center">
                                    <i class="fas fa-plus text-[10px] text-blue-600"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-gray-800">Data Baru Masuk</p>
                                    <p class="text-[10px] text-gray-400 italic">2 menit yang lalu</p>
                                </div>
                            </div>

                            <div class="flex gap-4">
                                <div class="w-8 h-8 bg-purple-100 rounded-full border-4 border-white z-10 flex items-center justify-center">
                                    <i class="fas fa-brain text-[10px] text-purple-600"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-gray-800">Analisis AI Selesai</p>
                                    <p class="text-[10px] text-gray-400 italic">15 menit yang lalu</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </main>

    <script>
        function updateClock() {
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            document.getElementById('mini-clock').textContent = `${hours}:${minutes} WITA`;
        }
        setInterval(updateClock, 1000); updateClock();
    </script>
</body>
</html>