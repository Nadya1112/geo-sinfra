<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surveyor Dashboard | GEO-SINFRA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style> body { font-family: 'Plus Jakarta Sans', sans-serif; } </style>
</head>
<body class="bg-gray-50 flex h-screen overflow-hidden text-gray-800 text-left">

    @include('surveyor.partials.sidebar')

    <main class="flex-1 flex flex-col h-screen overflow-y-auto">
        <header class="bg-white border-b border-gray-100 px-8 py-5 flex justify-between items-center z-10">
            <div>
                <p class="text-[10px] font-extrabold text-emerald-600 uppercase tracking-[0.2em] mb-1">Surveyor Portal</p>
                <h2 class="text-xl font-black text-[#1e1b4b]">Dashboard Utama</h2>
            </div>
            
            <div class="flex items-center gap-6">
                <div class="text-right hidden sm:block">
                    <p class="text-[11px] font-black text-[#1e1b4b]" id="mini-clock">00:00 WITA</p>
                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-tighter">{{ now()->translatedFormat('l, d F Y') }}</p>
                </div>
                <div class="h-8 w-[1px] bg-gray-100"></div>
                <div class="flex items-center gap-3">
                    <div class="text-right">
                        <p class="text-[11px] font-black text-[#1e1b4b] leading-none uppercase">{{ auth()->user()->name }}</p>
                        <p class="text-[9px] font-bold text-emerald-500 uppercase mt-1">Aktif Melaporkan</p>
                    </div>
                    <div class="w-10 h-10 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-600 border border-emerald-100 overflow-hidden">
                        @if(auth()->user()->profile_photo)
                            <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}" class="w-full h-full object-cover">
                        @else
                            <i class="fas fa-user-circle text-xl"></i>
                        @endif
                    </div>
                </div>
            </div>
        </header>

        <div class="p-8">
            <!-- Welcome Card -->
            <div class="relative bg-gradient-to-br from-emerald-600 to-teal-800 rounded-[2.5rem] p-10 mb-8 overflow-hidden shadow-lg shadow-emerald-900/10">
                <div class="absolute -right-10 -top-10 w-64 h-64 bg-white opacity-5 rounded-full blur-3xl"></div>
                <div class="relative z-10">
                    <h3 class="text-3xl font-black text-white mb-2 leading-tight">Selamat Bekerja, {{ explode(' ', auth()->user()->name)[0] }}!</h3>
                    <p class="text-emerald-50 text-sm font-medium max-w-xl leading-relaxed">
                        Siap untuk mendata infrastruktur hari ini? Pastikan GPS aktif dan foto yang diambil jelas untuk hasil analisis AI yang akurat.
                    </p>
                    <div class="mt-8 flex gap-4">
                        <button class="px-6 py-3 bg-white text-emerald-700 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-emerald-50 transition-all">
                            Mulai Survey Sekarang
                        </button>
                    </div>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
                    <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center mb-4"><i class="fas fa-file-alt"></i></div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Total Survey Saya</p>
                    <h3 class="text-2xl font-black text-[#1e1b4b]">{{ $totalSurvey }} <span class="text-xs font-medium text-gray-400">Laporan</span></h3>
                </div>
                <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
                    <div class="w-10 h-10 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center mb-4"><i class="fas fa-clock"></i></div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Menunggu Validasi</p>
                    <h3 class="text-2xl font-black text-[#1e1b4b]">{{ $waitingValidation }} <span class="text-xs font-medium text-gray-400">Objek</span></h3>
                </div>
                <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
                    <div class="w-10 h-10 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center mb-4"><i class="fas fa-check-double"></i></div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Terverifikasi AI</p>
                    <h3 class="text-2xl font-black text-[#1e1b4b]">{{ $verifiedAI }} <span class="text-xs font-medium text-gray-400">Selesai</span></h3>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Panduan Survey -->
                <div class="bg-white rounded-[2.5rem] p-8 border border-gray-100 shadow-sm">
                    <h4 class="font-black text-lg text-[#1e1b4b] mb-6">Panduan Survey Cepat</h4>
                    <div class="space-y-4">
                        <div class="flex items-start gap-4 p-4 bg-gray-50 rounded-2xl border border-gray-100">
                            <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center text-emerald-600 font-bold shadow-sm">1</div>
                            <div>
                                <p class="text-xs font-bold text-[#1e1b4b]">Pilih Jenis Infrastruktur</p>
                                <p class="text-[10px] text-gray-500 mt-1">Pastikan kategori objek sesuai (Jalan, Jembatan, atau Drainase).</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4 p-4 bg-gray-50 rounded-2xl border border-gray-100">
                            <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center text-emerald-600 font-bold shadow-sm">2</div>
                            <div>
                                <p class="text-xs font-bold text-[#1e1b4b]">Ambil Foto Fokus</p>
                                <p class="text-[10px] text-gray-500 mt-1">AI membutuhkan foto yang jelas dan terpusat pada area yang rusak.</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4 p-4 bg-gray-50 rounded-2xl border border-gray-100">
                            <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center text-emerald-600 font-bold shadow-sm">3</div>
                            <div>
                                <p class="text-xs font-bold text-[#1e1b4b]">Aktifkan GPS</p>
                                <p class="text-[10px] text-gray-500 mt-1">Koordinat akan terisi otomatis jika GPS HP Anda aktif saat memotret.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Info Wilayah Tugas -->
                <div class="bg-[#1e1b4b] rounded-[2.5rem] p-8 text-white relative overflow-hidden shadow-2xl shadow-indigo-900/40">
                    <div class="absolute -right-20 -bottom-20 w-80 h-80 bg-blue-600 opacity-10 rounded-full blur-3xl"></div>
                    <div class="relative z-10">
                        <h4 class="font-black text-lg mb-2">Wilayah Tugas Anda</h4>
                        <p class="text-blue-200 text-[10px] uppercase tracking-widest font-bold mb-8">Data Kecamatan Terdaftar</p>
                        
                        <div class="bg-white/5 border border-white/10 rounded-3xl p-6 backdrop-blur-md">
                            <div class="flex items-center gap-4 mb-4">
                                <div class="w-12 h-12 bg-emerald-500 rounded-2xl flex items-center justify-center text-xl shadow-lg shadow-emerald-500/20">
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>
                                <div>
                                    <h5 class="text-xl font-black">{{ auth()->user()->kecamatan->nama_kecamatan ?? 'Seluruh Wilayah' }}</h5>
                                    <p class="text-xs text-blue-200 font-medium">Kota Banjarmasin</p>
                                </div>
                            </div>
                            <div class="pt-4 border-t border-white/10 mt-4">
                                <p class="text-[10px] text-blue-200 italic font-medium leading-relaxed">
                                    "Anda bertanggung jawab untuk memantau dan melaporkan kondisi infrastruktur di wilayah ini secara berkala."
                                </p>
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
            document.getElementById('mini-clock').textContent = `${String(now.getHours()).padStart(2, '0')}:${String(now.getMinutes()).padStart(2, '0')} WITA`;
        }
        setInterval(updateClock, 1000); updateClock();
    </script>
</body>
</html>
