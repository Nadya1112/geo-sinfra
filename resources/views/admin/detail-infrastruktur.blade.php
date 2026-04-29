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
            <div class="text-right">
                <p class="text-[11px] font-black text-[#1e1b4b]" id="mini-clock">00:00 WITA</p>
            </div>
        </header>

        <div class="p-8">
            <div class="max-w-4xl bg-white rounded-[2.5rem] p-10 border border-gray-100 shadow-sm mx-auto">
                <div class="mb-10 border-b border-gray-50 pb-5 flex justify-between items-end">
                    <div>
                        <h3 class="text-lg font-black text-[#1e1b4b] tracking-tight">Detail Objek Infrastruktur</h3>
                        <p class="text-xs text-gray-400 font-medium tracking-tighter">Informasi lengkap aset SINFRA</p>
                    </div>
                    <div>
                        <a href="{{ route('admin.infrastruktur.edit', $inf->id_infrastruktur) }}" class="bg-indigo-50 text-indigo-600 px-4 py-2 rounded-xl text-[10px] font-black hover:bg-indigo-100 transition shadow-sm flex items-center gap-2">
                            <i class="fas fa-edit"></i> Edit Data
                        </a>
                    </div>
                </div>

                <div class="space-y-8">
                    
                    <!-- Bagian 1: Identitas & Lokasi -->
                    <div class="space-y-6">
                        <div class="border-l-4 border-blue-500 pl-4 mb-4">
                            <h4 class="text-sm font-black text-[#1e1b4b] uppercase tracking-wider">1. Identitas & Lokasi</h4>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div>
                                <label class="block text-[10px] font-black text-gray-400 tracking-widest mb-2">Nama Infrastruktur</label>
                                <div class="w-full px-5 py-3 bg-gray-50 border border-gray-100 rounded-2xl text-sm font-bold text-[#1e1b4b]">
                                    {{ $inf->nama_infrastruktur }}
                                </div>
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-gray-400 uppercase mb-2">Jenis Infrastruktur</label>
                                <div class="w-full px-5 py-3 bg-gray-50 border border-gray-100 rounded-2xl text-sm font-bold text-[#1e1b4b]">
                                    {{ $inf->jenis_infrastruktur }}
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div>
                                <label class="block text-[10px] font-black text-gray-400 uppercase mb-2">Kecamatan</label>
                                <div class="w-full px-5 py-3 bg-gray-50 border border-gray-100 rounded-2xl text-sm font-bold text-[#1e1b4b]">
                                    {{ $inf->nama_kecamatan ?? '-' }}
                                </div>
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-gray-400 uppercase mb-2">Kelurahan</label>
                                <div class="w-full px-5 py-3 bg-gray-50 border border-gray-100 rounded-2xl text-sm font-bold text-[#1e1b4b]">
                                    {{ $inf->nama_kelurahan ?? '-' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Bagian 2: Sistem Informasi Geografis (SIG) -->
                    <div class="space-y-6 pt-6 border-t border-gray-100">
                        <div class="border-l-4 border-indigo-500 pl-4 mb-4">
                            <h4 class="text-sm font-black text-[#1e1b4b] uppercase tracking-wider">2. SIG (Sistem Informasi Geografis)</h4>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div>
                                <label class="block text-[10px] font-black text-gray-400 uppercase mb-2">Latitude</label>
                                <div class="w-full px-5 py-3 bg-gray-50 border border-gray-100 rounded-2xl text-sm font-bold text-[#1e1b4b]">
                                    {{ $inf->latitude }}
                                </div>
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-gray-400 uppercase mb-2">Longitude</label>
                                <div class="w-full px-5 py-3 bg-gray-50 border border-gray-100 rounded-2xl text-sm font-bold text-[#1e1b4b]">
                                    {{ $inf->longitude }}
                                </div>
                            </div>
                        </div>
                        <div class="w-full h-48 bg-gray-100 rounded-2xl overflow-hidden relative border border-gray-200">
                            <!-- Placeholder for a map snippet -->
                            <div class="absolute inset-0 flex items-center justify-center">
                                <p class="text-xs font-bold text-gray-400"><i class="fas fa-map-marker-alt mr-2 text-indigo-400"></i> Pratinjau Lokasi Peta</p>
                            </div>
                        </div>
                    </div>

                    <!-- Bagian 3: Koreksi Analisis AI (CNN & DT) -->
                    <div class="space-y-6 pt-6 border-t border-gray-100">
                        <div class="border-l-4 border-purple-500 pl-4 mb-4">
                            <h4 class="text-sm font-black text-[#1e1b4b] uppercase tracking-wider">3. Koreksi Analisis AI (CNN & DT)</h4>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div>
                                <label class="block text-[10px] font-black text-gray-400 uppercase mb-2">Akurasi CNN (%)</label>
                                <div class="w-full px-5 py-3 bg-purple-50 border border-purple-100 rounded-2xl flex items-center justify-between">
                                    <span class="text-sm font-black text-purple-700">92.5%</span>
                                    <i class="fas fa-brain text-purple-400"></i>
                                </div>
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-gray-400 uppercase mb-2">Hasil Akhir Decision Tree</label>
                                <div class="w-full px-5 py-3 bg-gray-50 border border-gray-100 rounded-2xl flex items-center gap-3">
                                    <span class="px-2.5 py-1 rounded-lg text-[10px] font-black tracking-widest border {{ $inf->kondisi == 'Baik' ? 'bg-green-50 text-green-600 border-green-200' : ($inf->kondisi == 'Rusak Ringan' ? 'bg-yellow-50 text-yellow-600 border-yellow-200' : 'bg-red-50 text-red-600 border-red-200') }}">
                                        {{ strtoupper($inf->kondisi) }}
                                    </span>
                                    <span class="text-[10px] font-bold text-gray-400 italic">Otomatis Terverifikasi</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Bagian 4: Dokumentasi Visual -->
                    <div class="space-y-6 pt-6 border-t border-gray-100">
                        <div class="border-l-4 border-emerald-500 pl-4 mb-4">
                            <h4 class="text-sm font-black text-[#1e1b4b] uppercase tracking-wider">4. Dokumentasi Visual</h4>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase mb-2">Foto Lapangan Terbaru</label>
                            @if($inf->foto_terbaru && $inf->foto_terbaru != 'default.jpg')
                                <div class="w-full h-64 bg-gray-100 rounded-2xl overflow-hidden border border-gray-200 relative">
                                    <img src="{{ asset('storage/infrastruktur/' . $inf->foto_terbaru) }}" alt="{{ $inf->nama_infrastruktur }}" class="w-full h-full object-cover">
                                    <div class="absolute inset-0 bg-black/10 flex items-center justify-center opacity-0 hover:opacity-100 transition-opacity">
                                        <a href="{{ asset('storage/infrastruktur/' . $inf->foto_terbaru) }}" target="_blank" class="bg-white/90 text-gray-800 px-4 py-2 rounded-xl text-xs font-black shadow-lg">Lihat Gambar Penuh</a>
                                    </div>
                                </div>
                            @else
                                <div class="w-full px-5 py-10 bg-gray-50 border border-gray-100 rounded-2xl text-center">
                                    <div class="w-16 h-16 bg-gray-200 rounded-full flex items-center justify-center mx-auto mb-3 text-gray-400">
                                        <i class="fas fa-camera text-2xl"></i>
                                    </div>
                                    <p class="text-sm font-bold text-gray-400 uppercase">Tidak Ada Foto</p>
                                </div>
                            @endif
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
