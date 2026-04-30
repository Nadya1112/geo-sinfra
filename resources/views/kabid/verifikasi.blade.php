<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Usulan | GEO-SINFRA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; }
        .glass-card { background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.2); }
    </style>
</head>
<body class="text-gray-800 antialiased">

    <div class="min-h-screen flex flex-col">
        <!-- HEADER -->
        <header class="bg-white border-b border-gray-100 px-8 py-5 flex justify-between items-center sticky top-0 z-50">
            <div class="flex items-center gap-4">
                <a href="{{ route('kabid.dashboard') }}" class="w-10 h-10 flex items-center justify-center bg-gray-50 text-gray-400 rounded-xl hover:bg-indigo-50 hover:text-indigo-600 transition-all border border-gray-100">
                    <i class="fas fa-arrow-left text-sm"></i>
                </a>
                <div>
                    <p class="text-[10px] font-extrabold text-indigo-600 uppercase tracking-[0.2em] mb-1">Manajemen Validasi</p>
                    <h2 class="text-xl font-black text-[#1e1b4b]">Verifikasi Usulan Masuk</h2>
                </div>
            </div>
            
            <div class="flex items-center gap-6">
                <div class="text-right hidden sm:block">
                    <p class="text-[11px] font-black text-[#1e1b4b]">{{ now()->translatedFormat('l, d F Y') }}</p>
                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">DATA REAL-TIME</p>
                </div>
                <div class="h-8 w-[1px] bg-gray-100"></div>
                <div class="flex items-center gap-3">
                    <div class="text-right">
                        <p class="text-[11px] font-black text-[#1e1b4b] leading-none uppercase">{{ auth()->user()->name }}</p>
                        <p class="text-[9px] font-bold text-indigo-500 uppercase mt-1">KABID</p>
                    </div>
                    <div class="w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600 border border-indigo-100 overflow-hidden">
                        @if(auth()->user()->profile_photo)
                            <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}" class="w-full h-full object-cover">
                        @else
                            <i class="fas fa-user-tie text-xl"></i>
                        @endif
                    </div>
                </div>
            </div>
        </header>

        <!-- MAIN CONTENT -->
        <main class="flex-1 p-8 max-w-7xl mx-auto w-full">
            
            @if(session('success'))
                <div class="mb-6 p-4 bg-emerald-50 border border-emerald-100 rounded-2xl flex items-center gap-3 text-emerald-600 shadow-sm animate-bounce">
                    <i class="fas fa-check-circle"></i>
                    <p class="text-sm font-bold">{{ session('success') }}</p>
                </div>
            @endif

            <!-- STATS SUMMARY -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="glass-card p-6 rounded-[2rem] shadow-sm border border-indigo-50 relative overflow-hidden group">
                    <div class="absolute -right-4 -top-4 w-24 h-24 bg-indigo-500/5 rounded-full blur-2xl group-hover:bg-indigo-500/10 transition-all"></div>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-4">Total Usulan Pending</p>
                    <div class="flex items-end gap-3">
                        <h3 class="text-4xl font-black text-[#1e1b4b]">{{ $usulan->count() }}</h3>
                        <span class="text-[10px] font-bold text-indigo-500 bg-indigo-50 px-2.5 py-1 rounded-lg mb-1.5">LAPORAN</span>
                    </div>
                </div>
                <!-- You can add more summary cards here if needed -->
            </div>

            <!-- TABLE SECTION -->
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-8 py-6 border-b border-gray-50 flex justify-between items-center bg-gray-50/30">
                    <h3 class="text-sm font-black text-[#1e1b4b] uppercase tracking-widest">Daftar Tunggu Verifikasi</h3>
                    <div class="flex gap-2">
                        <button class="px-4 py-2 bg-white border border-gray-200 rounded-xl text-[10px] font-bold text-gray-500 hover:bg-gray-50 transition-all flex items-center gap-2">
                            <i class="fas fa-filter"></i> Filter
                        </button>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="text-[10px] font-black text-gray-400 uppercase tracking-widest">
                                <th class="px-8 py-5">Informasi Infrastruktur</th>
                                <th class="px-6 py-5">Lokasi & Surveyor</th>
                                <th class="px-6 py-5">Analisis AI</th>
                                <th class="px-6 py-5">Kondisi</th>
                                <th class="px-8 py-5 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($usulan as $item)
                            <tr class="hover:bg-gray-50/50 transition-colors group">
                                <td class="px-8 py-6">
                                    <div class="flex items-center gap-4">
                                        <div class="w-16 h-16 rounded-2xl bg-gray-100 overflow-hidden shadow-sm border border-white">
                                            <img src="{{ asset('storage/' . $item->foto_terbaru) }}" class="w-full h-full object-cover">
                                        </div>
                                        <div>
                                            <p class="text-[9px] font-black text-indigo-500 uppercase tracking-tighter mb-0.5">{{ $item->jenis_infrastruktur }}</p>
                                            <h4 class="text-sm font-black text-[#1e1b4b] leading-tight">{{ $item->nama_infrastruktur }}</h4>
                                            <p class="text-[10px] text-gray-400 font-bold mt-1 uppercase">{{ $item->created_at->translatedFormat('d M Y') }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-6">
                                    <div class="flex flex-col gap-1">
                                        <div class="flex items-center gap-2">
                                            <i class="fas fa-map-marker-alt text-indigo-400 text-[10px]"></i>
                                            <span class="text-[11px] font-bold text-gray-600">{{ $item->kelurahan->nama_kelurahan ?? '-' }}</span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <i class="fas fa-user text-gray-300 text-[10px]"></i>
                                            <span class="text-[10px] font-black text-gray-400 uppercase">{{ $item->user->name ?? 'Surveyor' }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-6">
                                    <div class="flex flex-col gap-2">
                                        <div class="flex items-center justify-between bg-emerald-50 px-2 py-1 rounded-lg border border-emerald-100">
                                            <span class="text-[8px] font-black text-emerald-600 uppercase">CNN Score</span>
                                            <span class="text-[10px] font-black text-emerald-700">{{ $item->cnn ? number_format($item->cnn->skor_cnn * 100, 1) . '%' : '-' }}</span>
                                        </div>
                                        <div class="flex items-center justify-between bg-blue-50 px-2 py-1 rounded-lg border border-blue-100">
                                            <span class="text-[8px] font-black text-blue-600 uppercase">Priority</span>
                                            <span class="text-[9px] font-black text-blue-700 uppercase">{{ $item->analisis->label_prioritas ?? '-' }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-6">
                                    @php
                                        $color = match($item->kondisi) {
                                            'Baik' => 'emerald',
                                            'Rusak Ringan' => 'amber',
                                            'Rusak Berat' => 'red',
                                            default => 'gray'
                                        };
                                    @endphp
                                    <span class="px-3 py-1.5 rounded-full text-[9px] font-black uppercase tracking-widest bg-{{ $color }}-50 text-{{ $color }}-600 border border-{{ $color }}-100">
                                        {{ $item->kondisi }}
                                    </span>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex items-center justify-center gap-2">
                                        <form action="{{ route('kabid.verifikasi.proses', $item->id_infrastruktur) }}" method="POST" class="inline">
                                            @csrf
                                            <input type="hidden" name="status" value="Verified">
                                            <button type="submit" class="w-9 h-9 flex items-center justify-center bg-emerald-500 text-white rounded-xl hover:bg-emerald-600 transition-all shadow-lg shadow-emerald-500/20 group/btn">
                                                <i class="fas fa-check text-xs group-hover/btn:scale-110 transition-transform"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('kabid.verifikasi.proses', $item->id_infrastruktur) }}" method="POST" class="inline">
                                            @csrf
                                            <input type="hidden" name="status" value="Rejected">
                                            <button type="submit" class="w-9 h-9 flex items-center justify-center bg-white border border-gray-200 text-gray-400 rounded-xl hover:bg-red-50 hover:text-red-500 hover:border-red-100 transition-all group/btn">
                                                <i class="fas fa-times text-xs group-hover/btn:scale-110 transition-transform"></i>
                                            </button>
                                        </form>
                                        <a href="{{ route('surveyor.infrastruktur.show', $item->id_infrastruktur) }}" target="_blank" class="w-9 h-9 flex items-center justify-center bg-gray-50 text-gray-400 rounded-xl hover:bg-indigo-50 hover:text-indigo-600 transition-all border border-gray-100 group/btn">
                                            <i class="fas fa-eye text-xs group-hover/btn:scale-110 transition-transform"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-8 py-20 text-center">
                                    <div class="flex flex-col items-center gap-3">
                                        <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center text-gray-300">
                                            <i class="fas fa-clipboard-check text-2xl"></i>
                                        </div>
                                        <p class="text-sm font-bold text-gray-400 uppercase tracking-widest">Tidak ada usulan baru</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if($usulan->count() > 0)
                <div class="px-8 py-5 bg-gray-50/30 border-t border-gray-50">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest italic">
                        * Gunakan tombol <i class="fas fa-eye text-indigo-400"></i> untuk melihat detail teknis lengkap sebelum verifikasi.
                    </p>
                </div>
                @endif
            </div>

        </main>

        <!-- FOOTER -->
        <footer class="bg-white border-t border-gray-100 px-8 py-4 flex justify-between items-center text-[10px] font-bold text-gray-400 uppercase tracking-widest">
            <p>&copy; 2026 DISPERKIM BANJARMASIN - GEO-SINFRA</p>
            <div class="flex gap-4">
                <a href="#" class="hover:text-indigo-600 transition-colors">Panduan</a>
                <a href="#" class="hover:text-indigo-600 transition-colors">Bantuan</a>
            </div>
        </footer>
    </div>

</body>
</html>
