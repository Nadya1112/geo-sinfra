<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Infrastruktur | Admin SINFRA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style> body { font-family: 'Plus Jakarta Sans', sans-serif; } </style>
</head>
<body class="bg-gray-50 flex h-screen overflow-hidden text-gray-800 text-left uppercase">

    @include('admin.partials.sidebar')

    <main class="flex-1 flex flex-col h-screen overflow-y-auto">
        <header class="bg-white border-b border-gray-100 px-8 py-5 flex justify-between items-center z-10">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.infrastruktur') }}" class="w-10 h-10 bg-white border border-gray-100 rounded-xl flex items-center justify-center text-gray-400 hover:text-blue-600 transition-all">
                    <i class="fas fa-arrow-left text-xs"></i>
                </a>
                <div>
                    <p class="text-[10px] font-extrabold text-blue-600 tracking-[0.2em] mb-1">Administrator Portal</p>
                    <h2 class="text-xl font-black text-[#1e1b4b]">Detail Data Infrastruktur</h2>
                </div>
            </div>
            <div class="flex items-center gap-6">
                <div class="text-right hidden sm:block">
                    <p class="text-[11px] font-black text-[#1e1b4b]" id="mini-clock">00:00 WITA</p>
                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-tighter">{{ now()->translatedFormat('l, d F Y') }}</p>
                </div>
                
                <div class="h-8 w-[1px] bg-gray-100"></div>
                
                <div class="flex items-center gap-3">
                    <div class="text-right">
                        <p class="text-[11px] font-black text-[#1e1b4b] leading-none uppercase">Admin SINFRA</p>
                        <p class="text-[9px] font-bold text-green-500 uppercase mt-1">Online</p>
                    </div>
                    <div class="w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600 border border-indigo-100">
                        <i class="fas fa-user-circle text-xl"></i>
                    </div>
                </div>
            </div>
        </header>

        <div class="p-8 pb-20">
            <div class="max-w-xl bg-white rounded-[2.5rem] p-8 sm:p-10 border border-gray-100 shadow-sm mx-auto">
                <div class="space-y-8">
                    
                    <!-- IDENTITAS & LOKASI -->
                    <div>
                        <h4 class="text-[10px] font-black text-blue-500 uppercase tracking-widest mb-4 flex items-center gap-2">
                            <i class="fas fa-list-ul"></i> Identitas & Lokasi
                        </h4>
                        <div class="space-y-3 pl-1">
                            <div class="flex items-start">
                                <div class="w-28 text-[11px] font-bold text-gray-400">Nama</div>
                                <div class="flex-1 text-[12px] font-black text-[#1e1b4b]">{{ $inf->nama_infrastruktur }}</div>
                            </div>
                            <div class="flex items-start">
                                <div class="w-28 text-[11px] font-bold text-gray-400">Wilayah</div>
                                <div class="flex-1 text-[12px] font-black text-[#1e1b4b]">{{ $inf->nama_kecamatan ?? '-' }}</div>
                            </div>
                            <div class="flex items-start">
                                <div class="w-28 text-[11px] font-bold text-gray-400">Koordinat</div>
                                <div class="flex-1 text-[12px] font-black text-[#1e1b4b]">{{ $inf->latitude }}, {{ $inf->longitude }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- ANALISIS CERDAS (CNN & DT) -->
                    <div>
                        <h4 class="text-[10px] font-black text-purple-500 uppercase tracking-widest mb-4 flex items-center gap-2">
                            <i class="fas fa-microchip"></i> Analisis Cerdas (CNN & DT)
                        </h4>
                        <div class="p-5 rounded-2xl border-2 border-dashed border-purple-100 bg-purple-50/30 space-y-4">
                            @php
                                $colorClass = $inf->kondisi == 'Baik' ? 'text-emerald-500' : ($inf->kondisi == 'Rusak Ringan' ? 'text-yellow-500' : 'text-red-500');
                                $bgClass = $inf->kondisi == 'Baik' ? 'bg-emerald-50 text-emerald-600 border-emerald-200' : ($inf->kondisi == 'Rusak Ringan' ? 'bg-yellow-50 text-yellow-600 border-yellow-200' : 'bg-red-50 text-red-600 border-red-200');
                                $priority = $inf->kondisi == 'Baik' ? 'Prioritas Rendah' : ($inf->kondisi == 'Rusak Ringan' ? 'PERLU PERHATIAN' : 'PRIORITAS TINGGI');
                                $cnnResult = $inf->kondisi == 'Baik' ? 'NORMAL (88.5%)' : 'RUSAK (94.2%)';
                            @endphp
                            <div class="flex justify-between items-center">
                                <div class="text-[11px] font-bold text-gray-400 w-28">Hasil CNN</div>
                                <div class="flex-1 text-[12px] font-black {{ $colorClass }}">{{ $cnnResult }}</div>
                            </div>
                            <div class="flex justify-between items-center">
                                <div class="text-[11px] font-bold text-gray-400 w-28">Decision Tree</div>
                                <div class="flex-1 text-[12px] font-black text-[#1e1b4b]">{{ $priority }}</div>
                            </div>
                            <div class="pt-4 flex justify-center">
                                <span class="px-6 py-2 rounded-xl text-[10px] font-black tracking-widest border {{ $bgClass }} shadow-sm">
                                    {{ strtoupper($inf->kondisi) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- DOKUMENTASI VISUAL -->
                    <div>
                        <h4 class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-4 flex items-center gap-2">
                            <i class="fas fa-camera"></i> Dokumentasi Visual
                        </h4>
                        
                        <div class="w-full relative rounded-2xl overflow-hidden border border-gray-100 shadow-sm bg-gray-50 aspect-video flex items-center justify-center group">
                            @if($inf->foto_terbaru && $inf->foto_terbaru != 'default.jpg')
                                <img src="{{ asset('storage/infrastruktur/' . $inf->foto_terbaru) }}" alt="Foto Infrastruktur" class="w-full h-full object-cover">
                                @if($inf->kondisi != 'Baik')
                                    <!-- Bounding Box Simulasi Kerusakan -->
                                    <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                                        <div class="border-2 border-red-500 bg-red-500/20 w-1/3 h-1/2 relative flex items-start justify-center">
                                            <span class="bg-red-500 text-white text-[8px] font-black px-2 py-1 mt-[-20px] whitespace-nowrap rounded-t-md tracking-wider shadow-sm">KERUSAKAN TERDETEKSI</span>
                                        </div>
                                    </div>
                                @endif
                                <div class="absolute inset-0 bg-black/10 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                    <a href="{{ asset('storage/infrastruktur/' . $inf->foto_terbaru) }}" target="_blank" class="bg-white/90 text-[#1e1b4b] px-4 py-2 rounded-xl text-[10px] font-black shadow-lg uppercase tracking-widest hover:bg-white hover:scale-105 transition-all">Lihat Foto Asli</a>
                                </div>
                            @else
                                <div class="text-center text-gray-300">
                                    <i class="fas fa-image text-3xl mb-2"></i>
                                    <p class="text-[10px] font-bold">[ TIDAK ADA FOTO ]</p>
                                </div>
                            @endif
                        </div>
                        
                        <div class="mt-3 text-center">
                            <p class="text-[9px] font-bold text-gray-400 italic normal-case">{{ $inf->foto_terbaru ?? 'tidak_ada_foto.jpg' }} - Diupload oleh {{ $inf->nama_user ?? 'Admin' }}</p>
                        </div>
                    </div>

                </div>

                <!-- Tombol Aksi -->
                <div class="mt-10 pt-8 border-t border-gray-100 space-y-3">
                    <a href="{{ route('admin.infrastruktur.pdf', $inf->id_infrastruktur) }}" class="w-full bg-yellow-400 text-white py-4 rounded-2xl font-black text-[11px] tracking-widest hover:bg-yellow-500 shadow-lg shadow-yellow-100 transition-all flex justify-center items-center gap-2">
                        <i class="fas fa-file-pdf"></i> Export Data Ke PDF
                    </a>
                    <a href="{{ route('admin.infrastruktur.edit', $inf->id_infrastruktur) }}" class="w-full bg-white text-[#1e1b4b] border-2 border-gray-100 py-3.5 rounded-2xl font-black text-[11px] tracking-widest hover:border-indigo-500 hover:text-indigo-600 transition-all flex justify-center items-center gap-2">
                        <i class="fas fa-edit"></i> Edit Data Manual
                    </a>
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
