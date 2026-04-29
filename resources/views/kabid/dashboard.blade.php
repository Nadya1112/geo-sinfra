<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kabid Dashboard | GEO-SINFRA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style> body { font-family: 'Plus Jakarta Sans', sans-serif; } </style>
</head>
<body class="bg-gray-50 flex h-screen overflow-hidden text-gray-800 text-left">

    @include('kabid.partials.sidebar')

    <main class="flex-1 flex flex-col h-screen overflow-y-auto">
        <header class="bg-white border-b border-gray-100 px-8 py-5 flex justify-between items-center z-10">
            <div>
                <p class="text-[10px] font-extrabold text-indigo-600 uppercase tracking-[0.2em] mb-1">Kepala Bidang Portal</p>
                <h2 class="text-xl font-black text-[#1e1b4b]">Overview Pengawasan</h2>
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
                        <p class="text-[9px] font-bold text-indigo-500 uppercase mt-1">Status: Pengawas</p>
                    </div>
                    <div class="w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600 border border-indigo-100 overflow-hidden">
                        @if(auth()->user()->profile_photo)
                            <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}" class="w-full h-full object-cover">
                        @else
                            <i class="fas fa-user-shield text-xl"></i>
                        @endif
                    </div>
                </div>
            </div>
        </header>

        <div class="p-8">
            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm relative overflow-hidden group">
                    <div class="relative z-10">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Butuh Validasi</p>
                        <h3 class="text-3xl font-black text-amber-500">{{ $totalPending }}</h3>
                        <p class="text-[9px] text-gray-400 mt-2 font-bold">Laporan masuk baru</p>
                    </div>
                    <i class="fas fa-clock absolute -right-4 -bottom-4 text-6xl text-gray-50 opacity-50 group-hover:text-amber-50 transition-colors"></i>
                </div>
                <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm relative overflow-hidden group">
                    <div class="relative z-10">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Terverifikasi</p>
                        <h3 class="text-3xl font-black text-emerald-500">{{ $totalVerified }}</h3>
                        <p class="text-[9px] text-gray-400 mt-2 font-bold">Disetujui pimpinan</p>
                    </div>
                    <i class="fas fa-check-double absolute -right-4 -bottom-4 text-6xl text-gray-50 opacity-50 group-hover:text-emerald-50 transition-colors"></i>
                </div>
                <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm relative overflow-hidden group">
                    <div class="relative z-10">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Total Objek</p>
                        <h3 class="text-3xl font-black text-indigo-600">{{ $totalInfrastruktur }}</h3>
                        <p class="text-[9px] text-gray-400 mt-2 font-bold">Infrastruktur terdata</p>
                    </div>
                    <i class="fas fa-database absolute -right-4 -bottom-4 text-6xl text-gray-50 opacity-50 group-hover:text-indigo-50 transition-colors"></i>
                </div>
                <div class="bg-gradient-to-br from-indigo-600 to-purple-700 p-6 rounded-3xl shadow-lg shadow-indigo-900/10 text-white flex flex-col justify-between">
                    <p class="text-[10px] font-bold text-indigo-100 uppercase tracking-widest">Aksi Cepat</p>
                    <button class="w-full py-2 bg-white/20 hover:bg-white/30 rounded-xl text-[10px] font-black uppercase transition-all backdrop-blur-sm border border-white/10">
                        Cetak Summary PDF
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Pending Validation List -->
                <div class="lg:col-span-2 bg-white rounded-[2.5rem] p-8 border border-gray-100 shadow-sm">
                    <div class="flex justify-between items-center mb-8">
                        <h4 class="font-black text-lg text-[#1e1b4b]">Menunggu Validasi Anda</h4>
                        <a href="#" class="text-[10px] font-black text-indigo-600 uppercase tracking-widest hover:underline">Lihat Semua <i class="fas fa-chevron-right ml-1"></i></a>
                    </div>

                    <div class="space-y-4">
                        @forelse($recentReports as $report)
                        <div class="flex items-center gap-6 p-4 rounded-3xl border border-gray-50 hover:border-indigo-100 hover:bg-indigo-50/30 transition-all group">
                            <div class="w-16 h-16 rounded-2xl bg-gray-100 overflow-hidden shadow-sm">
                                <img src="{{ asset('storage/' . $report->foto) }}" class="w-full h-full object-cover">
                            </div>
                            <div class="flex-1">
                                <p class="text-[9px] font-black text-indigo-500 uppercase tracking-widest mb-0.5">{{ $report->jenis_infrastruktur }}</p>
                                <h5 class="text-sm font-black text-[#1e1b4b] leading-tight mb-1">{{ $report->nama_infrastruktur }}</h5>
                                <div class="flex items-center gap-3">
                                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-tighter">Oleh: <span class="text-gray-600">{{ $report->user->name ?? 'Surveyor' }}</span></p>
                                    <div class="w-1 h-1 bg-gray-200 rounded-full"></div>
                                    <p class="text-[10px] text-gray-400 font-medium italic">{{ $report->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            <button class="px-5 py-2.5 bg-[#1e1b4b] text-white rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-indigo-900/10 opacity-0 group-hover:opacity-100 transition-all">
                                Validasi
                            </button>
                        </div>
                        @empty
                        <div class="text-center py-20 bg-gray-50/50 rounded-3xl border border-dashed border-gray-200">
                            <i class="fas fa-clipboard-check text-4xl text-gray-200 mb-4"></i>
                            <p class="text-xs text-gray-400 font-bold italic">Tidak ada laporan yang menunggu validasi.</p>
                        </div>
                        @endforelse
                    </div>
                </div>

                <!-- Distribution Summary -->
                <div class="space-y-6">
                    <div class="bg-[#1e1b4b] rounded-[2.5rem] p-8 text-white relative overflow-hidden shadow-xl">
                        <div class="absolute -right-10 -top-10 w-40 h-40 bg-indigo-500 opacity-20 rounded-full blur-3xl"></div>
                        <h4 class="font-black text-base mb-6 relative z-10 italic">Ringkasan Kondisi</h4>
                        <div class="space-y-4 relative z-10">
                            <div>
                                <div class="flex justify-between text-[10px] font-black uppercase mb-2 tracking-widest">
                                    <span>Kondisi Baik</span>
                                    <span class="text-emerald-400">75%</span>
                                </div>
                                <div class="w-full h-2 bg-white/10 rounded-full overflow-hidden">
                                    <div class="w-[75%] h-full bg-emerald-500"></div>
                                </div>
                            </div>
                            <div>
                                <div class="flex justify-between text-[10px] font-black uppercase mb-2 tracking-widest">
                                    <span>Rusak Ringan</span>
                                    <span class="text-amber-400">20%</span>
                                </div>
                                <div class="w-full h-2 bg-white/10 rounded-full overflow-hidden">
                                    <div class="w-[20%] h-full bg-amber-500"></div>
                                </div>
                            </div>
                            <div>
                                <div class="flex justify-between text-[10px] font-black uppercase mb-2 tracking-widest">
                                    <span>Rusak Berat</span>
                                    <span class="text-red-400">5%</span>
                                </div>
                                <div class="w-full h-2 bg-white/10 rounded-full overflow-hidden">
                                    <div class="w-[5%] h-full bg-red-500"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-[2.5rem] p-8 border border-gray-100 shadow-sm">
                        <h4 class="font-black text-[#1e1b4b] mb-4 italic">Butuh Perhatian</h4>
                        <p class="text-[10px] text-gray-500 leading-relaxed font-medium mb-6">
                            Sistem AI mendeteksi lonjakan laporan kerusakan di area **Banjarmasin Tengah** dalam 24 jam terakhir.
                        </p>
                        <button class="w-full py-3 bg-red-50 text-red-600 rounded-2xl text-[10px] font-black uppercase tracking-widest border border-red-100 hover:bg-red-100 transition-all">
                            Cek Wilayah Prioritas
                        </button>
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
