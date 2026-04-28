<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Wilayah | Admin SINFRA</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    </style>
</head>
<body class="bg-gray-50 flex h-screen overflow-hidden text-gray-800 text-left">

    <aside class="w-64 bg-[#1e1b4b] text-white flex flex-col hidden md:flex shadow-2xl z-20 text-left">
        <div class="p-6 flex-1 text-left">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 mb-10 hover:opacity-80 transition-opacity group">
                <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center shadow-lg shadow-blue-500/20 group-hover:scale-110 transition-transform">
                    <i class="fas fa-city text-xs text-white"></i>
                </div>
                <span class="font-extrabold text-xl tracking-tighter uppercase text-white">GEO-SINFRA</span>
            </a>
            
            <nav class="space-y-1">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 text-gray-400 hover:text-white hover:bg-white/5 rounded-xl text-sm font-semibold transition text-left">
                    <i class="fas fa-home"></i> Dashboard
                </a>
                <a href="{{ route('admin.users') }}" class="flex items-center gap-3 px-4 py-3 text-gray-400 hover:text-white hover:bg-white/5 rounded-xl text-sm font-semibold transition text-left">
                    <i class="fas fa-users-cog"></i> Manajemen Pengguna
                </a>
                <a href="{{ route('admin.wilayah') }}" class="flex items-center gap-3 px-4 py-3 bg-blue-600 rounded-xl text-sm font-bold transition shadow-lg shadow-blue-900/20 text-left">
                    <i class="fas fa-draw-polygon"></i> Manajemen Wilayah
                </a>
                <a href="#" class="flex items-center gap-3 px-4 py-3 text-gray-400 hover:text-white hover:bg-white/5 rounded-xl text-sm font-semibold transition text-left">
                    <i class="fas fa-database"></i> Manajemen Infrastruktur
                </a>
                <a href="{{ route('admin.peta') }}" class="flex items-center gap-3 px-4 py-3 text-gray-400 hover:text-white hover:bg-white/5 rounded-xl text-sm font-semibold transition text-left">
                    <i class="fas fa-map-marked-alt"></i> Peta Spasial
                </a>
                <a href="{{ route('admin.statistik') }}" class="flex items-center gap-3 px-4 py-3 text-gray-400 hover:text-white hover:bg-white/5 rounded-xl text-sm font-semibold transition text-left">
                    <i class="fas fa-chart-bar"></i> Statistik dan Laporan
                </a>
            </nav>
        </div>
        <div class="p-6 border-t border-white/5">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="flex items-center gap-3 text-red-400 hover:text-red-300 text-sm font-bold transition group">
                    <i class="fas fa-sign-out-alt group-hover:-translate-x-1 transition-transform"></i> Keluar Sistem
                </button>
            </form>
        </div>
    </aside>

    <main class="flex-1 flex flex-col h-screen overflow-hidden text-left">
        <header class="bg-white border-b border-gray-100 px-8 py-5 flex justify-between items-center z-10 text-left">
            <div class="flex items-center gap-4 text-left">
                <a href="{{ route('admin.wilayah') }}" 
                   class="w-10 h-10 bg-white border border-gray-100 rounded-xl flex items-center justify-center text-gray-400 hover:text-blue-600 hover:border-blue-100 hover:shadow-lg hover:shadow-blue-500/5 transition-all group"
                   title="Kembali ke Daftar">
                    <i class="fas fa-arrow-left text-xs group-hover:-translate-x-1 transition-transform"></i>
                </a>
                <div>
                    <p class="text-[10px] font-extrabold text-blue-600 uppercase tracking-[0.2em] mb-1">Administrator Portal</p>
                    <h2 class="text-xl font-black text-[#1e1b4b]">Edit Data Wilayah</h2>
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

        <div class="flex-1 p-8 overflow-y-auto custom-scrollbar">
            
            @if ($errors->any())
            <div class="mb-6 px-6 py-4 bg-red-50 border border-red-100 text-red-700 rounded-2xl">
                <div class="flex items-center gap-3 mb-2">
                    <i class="fas fa-exclamation-triangle"></i>
                    <p class="text-xs font-bold">Gagal memperbarui data. Silakan periksa kembali:</p>
                </div>
                <ul class="list-disc list-inside text-[11px] font-medium ml-4">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="max-w-3xl bg-white rounded-[2.5rem] p-10 border border-gray-100 shadow-sm mx-auto text-left">
                <form action="{{ route('admin.wilayah.update', $wilayah->id_kecamatan) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="space-y-6">
                        <div>
                            <label class="block text-[10px] font-black text-[#1e1b4b] uppercase tracking-widest mb-2">ID / Kode Kecamatan</label>
                            <input type="text" value="{{ $wilayah->id_kecamatan }}" class="w-full px-5 py-3 bg-gray-100 border border-gray-200 text-gray-500 rounded-2xl text-sm font-bold outline-none cursor-not-allowed" readonly title="ID Kecamatan tidak dapat diubah">
                            <p class="text-[10px] text-gray-400 mt-2 font-medium">ID Kecamatan bersifat tetap untuk menjaga relasi data pemetaan.</p>
                        </div>

                        <div>
                            <label class="block text-[10px] font-black text-[#1e1b4b] uppercase tracking-widest mb-2">Nama Kecamatan <span class="text-red-500">*</span></label>
                            <input type="text" name="nama_kecamatan" value="{{ old('nama_kecamatan', $wilayah->nama_kecamatan) }}" placeholder="Contoh: Banjarmasin Tengah" class="w-full px-5 py-3 bg-gray-50 border border-gray-100 rounded-2xl text-sm font-semibold focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all" required>
                        </div>

                        <div>
                            <label class="block text-[10px] font-black text-[#1e1b4b] uppercase tracking-widest mb-2">Daftar Kelurahan <span class="text-gray-400 font-medium">(Opsional)</span></label>
                            <textarea name="kelurahan" rows="3" placeholder="Contoh: Antasan Besar, Kelayan Luar, Pekapuran Laut..." class="w-full px-5 py-3 bg-gray-50 border border-gray-100 rounded-2xl text-sm font-semibold focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all custom-scrollbar">{{ old('kelurahan', $wilayah->kelurahan) }}</textarea>
                            <p class="text-[10px] text-gray-400 mt-2 font-medium">Pisahkan nama kelurahan dengan koma jika lebih dari satu.</p>
                        </div>

                        <div class="pt-8 flex gap-3">
                            <button type="submit" class="flex-1 bg-blue-600 text-white text-xs px-6 py-4 rounded-2xl font-bold shadow-lg shadow-blue-200 hover:bg-blue-700 transition">
                                <i class="fas fa-save mr-2"></i> Simpan Perubahan
                            </button>
                            <a href="{{ route('admin.wilayah') }}" class="flex-1 bg-gray-100 text-gray-500 text-xs px-6 py-4 rounded-2xl font-bold hover:bg-gray-200 transition text-center flex items-center justify-center gap-2">
                                <i class="fas fa-times-circle text-[10px]"></i> Batal
                            </a>
                        </div>
                    </div>
                </form>
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