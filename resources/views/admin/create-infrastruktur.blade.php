<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Aset | Admin SINFRA</title>
    
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

    @include('admin.partials.sidebar')

    <main class="flex-1 flex flex-col h-screen overflow-hidden text-left">
        <header class="bg-white border-b border-gray-100 px-8 py-5 flex justify-between items-center z-10 text-left">
            <div class="flex items-center gap-4 text-left">
                <a href="{{ route('admin.infrastruktur') }}" 
                   class="w-10 h-10 bg-white border border-gray-100 rounded-xl flex items-center justify-center text-gray-400 hover:text-blue-600 hover:border-blue-100 hover:shadow-lg hover:shadow-blue-500/5 transition-all group"
                   title="Kembali ke Daftar">
                    <i class="fas fa-arrow-left text-xs group-hover:-translate-x-1 transition-transform"></i>
                </a>

                <div class="text-left">
                    <p class="text-[10px] font-extrabold text-blue-600 uppercase tracking-[0.2em] mb-1">Administrator Portal</p>
                    <h2 class="text-xl font-black text-[#1e1b4b]">Tambah Aset Infrastruktur</h2>
                </div>
            </div>
            
            <div class="flex items-center gap-6">
                <div class="text-right hidden sm:block text-left">
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

        <div class="flex-1 p-8 overflow-y-auto custom-scrollbar text-left">
            
            @if ($errors->any())
            <div class="max-w-4xl mx-auto mb-6 px-6 py-4 bg-red-50 border border-red-100 text-red-700 rounded-2xl">
                <div class="flex items-center gap-3 mb-2 text-left">
                    <i class="fas fa-exclamation-triangle"></i>
                    <p class="text-xs font-bold">Gagal menyimpan data. Silakan periksa kembali:</p>
                </div>
                <ul class="list-disc list-inside text-[11px] font-medium ml-4 text-left">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="max-w-4xl bg-white rounded-[2.5rem] p-10 border border-gray-100 shadow-sm mx-auto text-left">
                <div class="mb-10 border-b border-gray-50 pb-5">
                    <h3 class="text-lg font-black text-[#1e1b4b] uppercase tracking-tight">Identitas Objek</h3>
                    <p class="text-xs text-gray-400 font-medium">Lengkapi rincian data teknis dan lokasi aset infrastruktur.</p>
                </div>

                <form action="{{ route('admin.infrastruktur.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8 text-left">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 text-left">
                        <div class="text-left">
                            <label class="block text-[10px] font-black text-[#1e1b4b] uppercase tracking-widest mb-2">
                                Nama Infrastruktur <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nama_infrastruktur" value="{{ old('nama_infrastruktur') }}" class="w-full px-5 py-3 bg-gray-50 border border-gray-100 rounded-2xl text-sm font-semibold focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all" placeholder="Contoh: Jembatan Merdeka" required>
                        </div>
                        <div class="text-left">
                            <label class="block text-[10px] font-black text-[#1e1b4b] uppercase tracking-widest mb-2">Jenis Infrastruktur</label>
                            <select name="jenis_infrastruktur" class="w-full px-5 py-3 bg-gray-50 border border-gray-100 rounded-2xl text-sm font-semibold focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none appearance-none transition-all">
                                <option value="Jalan" {{ old('jenis_infrastruktur') == 'Jalan' ? 'selected' : '' }}>Jalan</option>
                                <option value="Jembatan" {{ old('jenis_infrastruktur') == 'Jembatan' ? 'selected' : '' }}>Jembatan</option>
                                <option value="Drainase" {{ old('jenis_infrastruktur') == 'Drainase' ? 'selected' : '' }}>Drainase</option>
                                <option value="Titian" {{ old('jenis_infrastruktur') == 'Titian' ? 'selected' : '' }}>Titian</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 text-left">
                        <div class="text-left">
                            <label class="block text-[10px] font-black text-[#1e1b4b] uppercase tracking-widest mb-2">Kecamatan</label>
                            <select name="id_kecamatan" class="w-full px-5 py-3 bg-gray-50 border border-gray-100 rounded-2xl text-sm font-semibold focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none appearance-none transition-all">
                                <option value="">Pilih Kecamatan</option>
                                @foreach($semuaKecamatan as $kec)
                                    <option value="{{ $kec->id_kecamatan }}" {{ old('id_kecamatan') == $kec->id_kecamatan ? 'selected' : '' }}>{{ $kec->nama_kecamatan }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="text-left">
                            <label class="block text-[10px] font-black text-[#1e1b4b] uppercase tracking-widest mb-2">Kelurahan</label>
                            <select name="id_kelurahan" class="w-full px-5 py-3 bg-gray-50 border border-gray-100 rounded-2xl text-sm font-semibold focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none appearance-none transition-all">
                                <option value="">Pilih Kelurahan</option>
                                @foreach($semuaKelurahan as $kel)
                                    <option value="{{ $kel->id_kelurahan }}" {{ old('id_kelurahan') == $kel->id_kelurahan ? 'selected' : '' }}>{{ $kel->nama_kelurahan }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 text-left">
                        <div class="text-left">
                            <label class="block text-[10px] font-black text-[#1e1b4b] uppercase tracking-widest mb-2">Latitude <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <input type="text" name="latitude" value="{{ old('latitude') }}" placeholder="-3.32..." class="w-full pl-12 pr-5 py-3 bg-gray-50 border border-gray-100 rounded-2xl text-sm font-semibold focus:ring-2 focus:ring-blue-500/20 outline-none transition-all" required>
                                <i class="fas fa-map-marker-alt absolute left-5 top-1/2 -translate-y-1/2 text-gray-300"></i>
                            </div>
                        </div>
                        <div class="text-left">
                            <label class="block text-[10px] font-black text-[#1e1b4b] uppercase tracking-widest mb-2">Longitude <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <input type="text" name="longitude" value="{{ old('longitude') }}" placeholder="114.59..." class="w-full pl-12 pr-5 py-3 bg-gray-50 border border-gray-100 rounded-2xl text-sm font-semibold focus:ring-2 focus:ring-blue-500/20 outline-none transition-all" required>
                                <i class="fas fa-location-arrow absolute left-5 top-1/2 -translate-y-1/2 text-gray-300"></i>
                            </div>
                        </div>
                    </div>

                    <div class="text-left">
                        <label class="block text-[10px] font-black text-[#1e1b4b] uppercase tracking-widest mb-2">Dokumentasi Lapangan</label>
                        <div class="relative">
                            <input type="file" name="foto" class="w-full px-5 py-3 bg-gray-50 border border-gray-100 rounded-2xl text-xs font-semibold file:mr-4 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-[10px] file:font-bold file:bg-blue-50 file:text-blue-600 hover:file:bg-blue-100 transition-all cursor-pointer">
                        </div>
                        <p class="text-[9px] text-gray-400 mt-2 italic">* Format file: JPG, PNG. Maksimal 2MB.</p>
                    </div>

                    <div class="pt-6 flex gap-4 text-left">
                        <button type="submit" class="flex-1 bg-blue-600 text-white text-xs px-6 py-4 rounded-2xl font-bold shadow-lg shadow-blue-200 hover:bg-blue-700 transition">
                            <i class="fas fa-save mr-2"></i> Simpan Data
                        </button>
                        <a href="{{ route('admin.infrastruktur') }}" class="flex-1 bg-gray-100 text-gray-500 text-xs px-6 py-4 rounded-2xl font-bold hover:bg-gray-200 transition text-center flex items-center justify-center gap-2">
                            Batal
                        </a>
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
        setInterval(updateClock, 1000); 
        updateClock();
    </script>
</body>
</html>